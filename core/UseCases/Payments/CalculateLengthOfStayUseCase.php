<?php

namespace UseCases\Payments;

use DateTime;
use Exception;
use Commons\Uteis;
use Interfaces\Car\ICartype;
use Resources\Strings;
use Interfaces\IUserCase;
use Resources\HttpStatus;
use Services\BranchesServices;
use Interfaces\Movements\IMovements;
use Repositories\Price\PriceRepository;
use UseCases\Price\PriceActiveUserCase;
use UseCases\MonthyPlates\ByUuidMonthPlatesUserCase;




class Output
{
    private $inputs = ["total", "time", "intervals_used"];
    public $total;
    public $time;
    public $intervals_used;
    public function __construct($parameters = null)
    {
        foreach ($this->inputs as $key => $input) {
            if (isset($parameters[$input])) {
                $this->$input = $parameters[$input];
            }
        }

        return $this;
    }

    function toArray()
    {
        $rows = [];
        foreach ($this->inputs as $key => $value) {
            $rows[$value] = $this->$value;
        }
        return $rows;
    }
}




class CalculateLengthOfStayUseCase implements IUserCase
{

    private IMovements $iMovements;
    private PriceActiveUserCase $byPriceUserCase;
    private ByUuidMonthPlatesUserCase $byUuidMonthPlatesUserCase;
    private CheckMonthlyPaymentUseCase $checkMonthlyPaymentUseCase;
    private BranchesServices $sBrancheService;
    private PriceRepository $priceRepository;
    private ICartype $iCartypeRepository;

    public function __construct(
        IMovements $iMovements,
        PriceActiveUserCase $byPriceUserCase,
        ByUuidMonthPlatesUserCase $byUuidMonthPlatesUserCase,
        CheckMonthlyPaymentUseCase $checkMonthlyPaymentUseCase,
        PriceRepository $priceRepository,
        ICartype $iCartypeRepository
    ) {
        $this->iMovements   =  $iMovements;
        $this->byPriceUserCase = $byPriceUserCase;
        $this->byUuidMonthPlatesUserCase =  $byUuidMonthPlatesUserCase;
        $this->checkMonthlyPaymentUseCase =  $checkMonthlyPaymentUseCase;
        $this->sBrancheService =  new BranchesServices();
        $this->priceRepository = $priceRepository;
        $this->iCartypeRepository = $iCartypeRepository;
    }

    public function execute($uuid, $fk_branch_code)
    {
        try {

         
            $timeIntervals =  $this->priceRepository->allPriceInterval($fk_branch_code);
            $vechicleTypes =  $this->iCartypeRepository->typeOfVehicles();
            $movementData = $this->iMovements->byUuid($uuid);
            $entryDate = $movementData->park_entry_date;
            $type = null;
            $intervalsUsed = [];
            foreach ($vechicleTypes as $key => $row) {
                if ($row->id == $movementData->fk_type_of_vehicle) {
                    $type = $row->fk_price_id;
                    break;
                }
            }
            $total = 0;
            $stayDuration = $this->calculateDifferenceInMinutes($entryDate, date("Y-m-d H:i:s"));
            $permanenceHourAndMinute = $this->convertMinutesToHours($stayDuration);
            foreach ($timeIntervals as $k => $item) {
                $intervalInitial = $this->convertToMinutes($item->initial_start);
                $intervalEnd = $this->convertToMinutes($item->initial_end);
                $tolerance = $this->convertToMinutes($item->tolerence);
                if ($item->fk_princes_id == $type) {
                    $intervalsUsed[] = $item;
                    if ($stayDuration >= $intervalInitial && $stayDuration <= $intervalEnd) {
                        if ($item->mult) {
                            if ($permanenceHourAndMinute['hours'] == 0) {
                                $permanenceHourAndMinute['hours'] = 1;
                            }
                            if ($permanenceHourAndMinute['minutes'] < $tolerance) {
                                $permanenceHourAndMinute['hours']--;
                            }
                            $total = $item->price * $permanenceHourAndMinute['hours'];
                        } else {
                            $total = $item->price;
                        }
                    }
                }
            }

            return new Output([
                'total' => number_format($total, 2),
                'time' => $permanenceHourAndMinute,
                'intervals_used' => $intervalsUsed
            ]);
        } catch (Exception $e) {

           
            throw new Exception($e->getMessage());
        }
        return null;
    }


    function convertToMinutes($horario)
    {
        // Divide o horário em horas, minutos e segundos
        list($hora, $minuto, $segundo) = explode(":", $horario);

        // Converte tudo para minutos
        return ($hora * 60) + $minuto + ($segundo / 60);
    }
    private function calculateDifferenceInMinutes($date1, $date2)
    {
        $date1 = new DateTime($date1); // First date
        $date2 = new DateTime($date2); // Second date
        $difference = $date1->diff($date2);
        $minutes = ($difference->h * 60) + $difference->i + ($difference->days * 24 * 60);
        return $minutes;
    }
    private function convertMinutesToHours($totalMinutes)
    {
        return [
            "hours" => $hours = floor($totalMinutes / 60),
            "minutes" => $totalMinutes % 60
        ];
    }
}
