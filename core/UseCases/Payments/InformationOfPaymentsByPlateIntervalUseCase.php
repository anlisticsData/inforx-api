<?php

namespace UseCases\Payments;

use Exception;
use Commons\Clock;
use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Dtos\JSONValidatorDto;
use Resources\APPLICATION;
use Dtos\OvernightDefinition;
use Services\BranchesServices;
use Services\CalculatePayment;
use Commons\DateTimeCalculator;
use Dtos\GeneralCarInformationDto;
use Interfaces\Movements\IMovements;
use Models\PaymentOfMonthlyPayments;
use UseCases\Price\PriceActiveUserCase;
use UseCases\Payments\CheckMonthlyPaymentUseCase;
use UseCases\MonthyPlates\ByUuidMonthPlatesUserCase;






class InformationOfPaymentsByPlateIntervalUseCase
{
    private IMovements $iMovements;
    private PriceActiveUserCase $byPriceUserCase;
    private ByUuidMonthPlatesUserCase $byUuidMonthPlatesUserCase;
    private CheckMonthlyPaymentUseCase $checkMonthlyPaymentUseCase;
    private BranchesServices $sBrancheService;

    public function __construct(IMovements $iMovements, PriceActiveUserCase $byPriceUserCase, ByUuidMonthPlatesUserCase $byUuidMonthPlatesUserCase, CheckMonthlyPaymentUseCase $checkMonthlyPaymentUseCase)
    {
        $this->iMovements   =  $iMovements;
        $this->byPriceUserCase = $byPriceUserCase;
        $this->byUuidMonthPlatesUserCase =  $byUuidMonthPlatesUserCase;
        $this->checkMonthlyPaymentUseCase =  $checkMonthlyPaymentUseCase;
        $this->sBrancheService =  new BranchesServices();
    }

    private  function getPricingByCategory($price_grid, $category)
    {
        foreach ($price_grid as $row) {
            if ($row->pricing_category == $category) return $row;
        }

        return null;
    }

    public function execute($uuid)
    {
        try {
            $total = 0;
            $movementData = $this->iMovements->byUuid($uuid);


            if (is_null($movementData)) throw new Exception(Strings::$STR_PLATE_INVALID, HttpStatus::$HTTP_CODE_BAD_REQUEST);
            $priceActive = $this->byPriceUserCase->execute($movementData->branches_id);
            $priceActive = $priceActive[0];
            $dateCreatedAt = $movementData->park_entry_date;
            $dateCurrenc = Clock::NowDate();
            $lengthOfStay = DateTimeCalculator::calculateDifference($dateCreatedAt, $dateCurrenc);
            $movementData->permanence = $lengthOfStay;
            $movementData->hours = DateTimeCalculator::convertToHours($lengthOfStay["hours"], $lengthOfStay["minutes"]);
            $vehicleAndMonthlyFee = $this->byUuidMonthPlatesUserCase->execute($movementData->park_vehicle_plate);
            $lengthOfStay = $movementData->permanence;
            $toleranceOtherDefault = Uteis::StringInHoursMinute($priceActive->pricing_of_other_hours_tolerance);


            if (is_null($vehicleAndMonthlyFee)) {
                if (intval($lengthOfStay['hours']) == 0 && $lengthOfStay['minutes'] < 30) {
                    $tolerance = Uteis::StringInHoursMinute($priceActive->pricing_value_first_half_hour_tolerance);
                    if ($lengthOfStay["minutes"] > $tolerance['minutes']) {
                        $total += $priceActive->pricing_value_first_half_hour;
                    }
                } else {
                    if (intval($lengthOfStay['hours']) == 0 && $lengthOfStay['minutes'] > 30 &&  $lengthOfStay['minutes'] < 60) {
                        $total += $priceActive->pricing_value_first_hour;
                    }else{

                        $tolerance = Uteis::StringInHoursMinute($priceActive->pricing_of_other_hours_tolerance);
                        if ($lengthOfStay["minutes"] < $tolerance['minutes']) {
                            $total+=  $priceActive->pricing_value_first_hour;
                        }else{

                            
                          
                            $dailyTolerance= Uteis::StringInHoursMinute($priceActive->pricing_value_half_day_tolerance); 
                            if($lengthOfStay['hours'] < $dailyTolerance['hours']){
                                if($lengthOfStay['minutes'] > $toleranceOtherDefault['minutes']){
                                    $total+= ((($lengthOfStay['hours'] * $priceActive->pricing_of_other_hours))) +
                                    $priceActive->pricing_value_first_hour;
                                }else{
                                    $total+= ((($lengthOfStay['hours'] * $priceActive->pricing_of_other_hours))) +
                                    $priceActive->pricing_value_first_hour;
                                }
                            }else{
                                $total+=$priceActive->pricing_daily_value; 
                            } 
                           

                        }
                    }
                }

                $debitsOpen = 0;
                $inputPaymentOfMonthlyPayments = [
                    "fk_monthly_payer" => $vehicleAndMonthlyFee['monthly_id']
                ];
                $paymentIsPendents = $this->checkMonthlyPaymentUseCase->execute(new PaymentOfMonthlyPayments($inputPaymentOfMonthlyPayments));
                $paymentIsPendentsCurrent = null;
                $paymentsDebts = [
                    "debits_of_open" => $debitsOpen,
                    "debits_total" => number_format($total, 2) * $debitsOpen,
                    "debits" => $paymentIsPendents,
                    "paymentis_pendents_current" => $paymentIsPendentsCurrent
                ];
                $GeneralCarInformationDto =  new GeneralCarInformationDto(
                    [
                        "movement" => $movementData,
                        "price_grid" => $priceActive,
                        "complete_information" => $vehicleAndMonthlyFee,
                        "used_grid" => null,
                        "total" => number_format($total, 2),
                        "monthly_day_expiry_date" => null,
                        "outstanding_debts" =>$paymentsDebts
                    ]
                );
            } else {
                //Messalista
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }




        return $GeneralCarInformationDto;
    }





    /*
    
    
    public function execute($uuid)
    {



        try {
            $total = 0;
            $used_grid = "DEFAULT_WITHOUT_REGISTRATION";
            $movementData = $this->iMovements->byUuid($uuid);
            $branchInformations = $this->sBrancheService->services($movementData->branches_id);
            if (count($branchInformations) == 0) {
                throw new Exception(Strings::$STR_BRANCH__INVALID, HttpStatus::$HTTP_CODE_BAD_REQUEST);
            }
            $data = JSONValidatorDto::validateAndTransform(base64_decode($branchInformations['results'][0]['settings'], true));
            if (
                !isset($data['overnightstay']) || Uteis::isNullOrEmpty($data['overnightstay'])
                || count(explode("|", $data['overnightstay'])) != 2
            ) {
                $data['overnightstay'] = APPLICATION::$APP_OVERNIGHT_STAY;
            }
            $overnightstaySetting = explode("|", $data['overnightstay']);

            if (
                !isset($data['tolerance']) || Uteis::isNullOrEmpty($data['tolerance'])
                || count(explode(":", $data['tolerance'])) != 3
            ) {
                $data['tolerance'] = "00:00:00";
            }
            if (is_null($movementData)) throw new Exception(Strings::$STR_PLATE_INVALID, HttpStatus::$HTTP_CODE_BAD_REQUEST);
            $dateCreatedAt = $movementData->park_entry_date;
            $dateCurrenc = Clock::NowDate();
            $recoveringStoppedTime = Clock::calculateDateDifference($dateCreatedAt, $dateCurrenc);
            $lengthOfStay = DateTimeCalculator::calculateDifference($dateCreatedAt, $dateCurrenc);
            $movementData->permanence = $lengthOfStay;
            $movementData->hours = DateTimeCalculator::convertToHours($lengthOfStay["hours"], $lengthOfStay["minutes"]);
            $vehicleAndMonthlyFee = $this->byUuidMonthPlatesUserCase->execute($movementData->park_vehicle_plate);
            $taleOfPricesOfBranch = $this->byPriceUserCase->execute($movementData->branches_id);
            if (is_null($vehicleAndMonthlyFee)) {
                $paymentRefCalculator = $this->getPricingByCategory($taleOfPricesOfBranch, $used_grid);
                $permanence = $movementData->permanence;
                if ($recoveringStoppedTime['days'] == 0) {
                    $IsPernoite = CalculatePayment::calculateOvernight($dateCreatedAt, $dateCurrenc, new OvernightDefinition($overnightstaySetting[0], $overnightstaySetting[1]));
                    if ($permanence['hours'] < 1 && $permanence['minutes'] < 30) {

                        $tolerance = explode(":",$data['tolerance']);
                        if($permanence['minutes'] < intval($tolerance[1])){
                            $total=0;
                        }else{
                            $total = $paymentRefCalculator->pricing_value_first_half_hour;
                        }
                    } else {

                        if ($permanence['hours'] < 1 && $permanence['minutes'] > 30 && $permanence['minutes'] < 60) {
                            $total = $paymentRefCalculator->pricing_value_first_hour;
                        } else if ($permanence['hours'] > 1 && $permanence['hours'] < 2) {
                            $total = $paymentRefCalculator->pricing_value_first_hour;
                        } else {
                            $total = ((($permanence['hours'] * $paymentRefCalculator->pricing_of_other_hours))) +
                                $paymentRefCalculator->pricing_value_first_hour;
                        }
                    }
                    if ($IsPernoite['hours'] > 0) {
                        $total += $paymentRefCalculator->pricing_value_overnight;
                    }
                } else if ($recoveringStoppedTime['days'] == 1) {


                    $total = $paymentRefCalculator->pricing_daily_value * $recoveringStoppedTime['days'];
                } else if ($recoveringStoppedTime['days'] > 1) {
                    $total = $paymentRefCalculator->pricing_daily_value * $recoveringStoppedTime['days'];
                }
            } else {
                $used_grid = "STANDARD_MONTHLY_REGISTRATION";
                $paymentRefCalculator = $this->getPricingByCategory($taleOfPricesOfBranch, $used_grid);
                $total = $paymentRefCalculator->pricing_monthly_value;
            }


            $inputPaymentOfMonthlyPayments = [
                "fk_monthly_payer" => $vehicleAndMonthlyFee['monthly_id']
            ];
            $paymentIsPendents = $this->checkMonthlyPaymentUseCase->execute(new PaymentOfMonthlyPayments($inputPaymentOfMonthlyPayments));
            $paymentIsPendentsCurrent = null;
            $debitsOpen = (is_null($paymentIsPendents)) ? 0 : count($paymentIsPendents);
            $nextMonthlyPaymentDueDate = sprintf("%s%s", str_pad($vehicleAndMonthlyFee['monthly_day_expiry_date'], 2, "0", STR_PAD_LEFT), Clock::addOneormoremonths(null, 1, 'br'));
            if ($debitsOpen > 0) {
                $nextMonthlyPaymentDueDate = sprintf("%s%s", str_pad($vehicleAndMonthlyFee['monthly_day_expiry_date'], 2, "0", STR_PAD_LEFT), Clock::addOneormoremonths(null, 0, 'br'));
                foreach ($paymentIsPendents as $index => $row) {
                    $date = Uteis::separateDateTime($row->payment_made_on);
                    if (intval(date("d")) >= intval($date['dia'])) {
                        $paymentIsPendentsCurrent = $row;
                    }
                }
            }
            $paymentsDebts = [
                "debits_of_open" => $debitsOpen,
                "debits_total" => number_format($total, 2) * $debitsOpen,
                "debits" => $paymentIsPendents,
                "paymentis_pendents_current" => $paymentIsPendentsCurrent
            ];

            $GeneralCarInformationDto =  new GeneralCarInformationDto(
                [
                    "movement" => $movementData,
                    "price_grid" => $taleOfPricesOfBranch,
                    "complete_information" => $vehicleAndMonthlyFee,
                    "used_grid" => $used_grid,
                    "total" => number_format($total, 2),
                    "monthly_day_expiry_date" => $nextMonthlyPaymentDueDate,
                    "outstanding_debts" => $paymentsDebts
                ]
            );
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }

        return $GeneralCarInformationDto;
    }
        
    
    */
}
