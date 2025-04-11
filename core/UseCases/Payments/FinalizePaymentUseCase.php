<?php


namespace UseCases\Payments;

use Exception;
use Commons\Uteis;
use Models\Payment;
use Resources\Strings;
use Commons\ResponseJson;
use Interfaces\IUserCase;
use Resources\HttpStatus;
use Dtos\JSONValidatorDto;
use Services\BranchesServices;
use UseCases\Price\ByPriceUserCase;
use Models\PaymentOfMonthlyPayments;
use Repositories\Price\PriceRepository;
use UseCases\Price\PriceActiveUserCase;
use Repositories\Payments\PaymentsRepository;
use UseCases\PaymentTypes\AllPaymentsUseCase;
use UseCases\Movements\ByUuidMovementUserCase;
use Repositories\Movements\MovementsRepository;
use Repositories\MonthyPlate\MonthyPlateRepository;
use Resources\APPLICATION;
use UseCases\Agreements\ByAgreementUseCase;
use UseCases\MonthyPlates\ByUuidMonthPlatesUserCase;
use UseCases\Payments\InformationOfPaymentsByPlateUseCase;


class FinalizePaymentUseCase implements IUserCase
{

    private AllPaymentsUseCase $allPaymentsUseCase;
    private ByPriceUserCase $byPriceUserCase;
    private ByUuidMovementUserCase $byUuidMovementUserCase;
    private PaymentsRepository $paymentsRepository;
    private CheckMonthlyPaymentUseCase $checkMonthlyPaymentUseCase;
    private BranchesServices $sBrancheService;
    private ByAgreementUseCase $byAgreementUseCase;

    public function __construct(
        PaymentsRepository $paymentsRepository,
        AllPaymentsUseCase $allPaymentsUseCase,
        ByPriceUserCase $byPriceUserCase,
        ByUuidMovementUserCase $byUuidMovementUserCase,
        CheckMonthlyPaymentUseCase $checkMonthlyPaymentUseCase,
        ByAgreementUseCase $byAgreementUseCase
    ) {
        $this->allPaymentsUseCase = $allPaymentsUseCase;
        $this->byPriceUserCase = $byPriceUserCase;
        $this->byUuidMovementUserCase = $byUuidMovementUserCase;
        $this->paymentsRepository = $paymentsRepository;
        $this->checkMonthlyPaymentUseCase = $checkMonthlyPaymentUseCase;
        $this->sBrancheService =  new BranchesServices();
        $this->byAgreementUseCase =  $byAgreementUseCase;
    }

    public function execute($input)
    {
        try {

            $discounts = 0;
            $discountsUseCase = null;
            if ($input['fk_agreements_discont'] > 0) {
                $discountsUseCase = $this->byAgreementUseCase->execute($input['fk_agreements_discont'], $input['fk_branch_id']);
                $discounts = $discountsUseCase->price;
            }
            $this->allPaymentsUseCase->execute($input['fk_branch_id']);
            $this->byPriceUserCase->execute($input['fk_branch_id']);
            $movements = $this->byUuidMovementUserCase->execute($input['uuid_id_plate_direction_create']);
            if ($input['fk_agreements_discont'] > 0 && is_null($discountsUseCase)) {
                throw new Exception(Strings::$STR_AGREEMENT_VALUE_INVALID, HttpStatus::$HTTP_CODE_BAD_REQUEST);
            }
            if (is_null($movements)) {
                throw new Exception(Strings::$STR_PAYMENT_MOVEMENT_VALUE_INVALID, HttpStatus::$HTTP_CODE_BAD_REQUEST);
            }
            $branchInformations = $this->sBrancheService->services($input['fk_branch_id']);
            if (count($branchInformations) == 0) {
                throw new Exception(Strings::$STR_BRANCH__INVALID, HttpStatus::$HTTP_CODE_BAD_REQUEST);
            }
            $InformationOfPaymentsByPlateUseCase =  new InformationOfPaymentsByPlateUseCase(
                new MovementsRepository(),
                new PriceActiveUserCase(new PriceRepository()),
                new ByUuidMonthPlatesUserCase(new MonthyPlateRepository()),
                $this->checkMonthlyPaymentUseCase
            );
            $resume = $InformationOfPaymentsByPlateUseCase->execute($input['uuid_id_plate_direction_create']);
            if ($resume->outstanding_debts['debits_of_open'] > 0) {
                $valueDebit = $resume->outstanding_debts['debits_of_open']  * $resume->total;
                $calculate = number_format(floatval($input['receipt_by_box']), 2) - number_format(floatval($valueDebit), 2);
                $calculate = number_format(floatval($input['receipt_by_box']), 2) -  $calculate;
            } else {
                if (!is_null($discountsUseCase) &&   $discountsUseCase->price == 0) {
                    $discounts = $input['debts']->total;
                }
                $calculate = (number_format(floatval($input['debts']->total), 2) - number_format(floatval($discounts), 2));
                $calculate = number_format(floatval($input['receipt_by_box']), 2) -  $calculate;
            }
            $calculate =round($calculate,2);
            if ($resume->is_monthly) {
                $input['receipt_by_box'] = 0;
                $resume->total = 0;
            }




            if (abs($calculate) < APPLICATION::$APP_SETTING_TOLERANCE_NUMBER || ($calculate > 0) || $resume->is_monthly) {

                $resultData = $this->paymentsRepository->created(new Payment(
                    [
                        "fk_branch_id" => $input['fk_branch_id'],
                        "ip_address" => $input['ip_address'],
                        "fk_user_id" => $input['fk_user_id'],
                        "fk_payment_types" => $input['fk_payment_types'],
                        "fk_pricing_id" => $input['fk_pricing_id'],
                        "fk_movements_id" => $movements->park_id,
                        "uuid_id_plate_direction_create" => $input['uuid_id_plate_direction_create'],
                        "receipt_by_box" => $input['receipt_by_box'],
                        "payment_change" => $calculate,
                        "total" => $resume->total,
                        "hours" => $resume->movement->permanence['hours'],
                        "minutes" => $resume->movement->permanence['minutes'],
                        "fk_agreements_discont" => $input['fk_agreements_discont'],
                        "discount_applied" => $discounts
                    ]
                ));
                if ($resultData > 0) {
                    if ($resume->outstanding_debts['debits_of_open'] > 0) {
                        $debits =  $resume->outstanding_debts['debits'];
                        foreach ($debits as $index => $row) {
                            $this->paymentsRepository->monthlyPayerMadePayment(new PaymentOfMonthlyPayments([
                                "id" => $row->id,
                                "payment_value" => $resume->total
                            ]));
                        }
                        return $this->paymentsRepository->endEntry($movements->park_id, $input['fk_user_id'], $resultData);
                    } else {
                        return $this->paymentsRepository->endEntry($movements->park_id, $input['fk_user_id'], $resultData);
                    }
                }
            } else {
                throw new Exception(Strings::$STR_PAYMENT_MOVEMENT_VALUE_PAYMENT_INVALID, HttpStatus::$HTTP_CODE_BAD_REQUEST);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }

        return null;
    }
}
