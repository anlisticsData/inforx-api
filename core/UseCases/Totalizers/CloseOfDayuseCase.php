<?php

namespace UseCases\Totalizers;

use Commons\Clock;
use Commons\Uteis;
use Exception;
use Interfaces\Car\IMonthlyRepository;
use Interfaces\IUserCase;
use Resources\HttpStatus;
use Interfaces\Movements\IMovements;
use Resources\Strings;

class CloseOfDayuseCase implements IUserCase
{

    private IMovements $iMovements;
    private IMonthlyRepository  $iMonthlyRepository;


    function __construct(IMovements $iMovements, IMonthlyRepository  $iMonthlyRepository)
    {
        $this->iMovements =  $iMovements;
        $this->iMonthlyRepository =  $iMonthlyRepository;
    }

    function execute($data,$formart)
    {

        $data = Clock::splitDateTime($data, $formart);
        try {
            $resultDate = $this->iMovements->closeOfDay($data['year'], $data['month'], $data['day']);
            $resultMovements = $this->iMovements->movementsDay($data['year'], $data['month'], $data['day']);
            $totalRecebido = 0;
            $totalDescontos = 0;
            $totalPagamentos = 0;
            $totalPagamentosAgreement = 0;
            $totalmonthly = 0;
            $deletedCount = 0;
            $notExit=0;
            $entryDates = [];
            $monthlys = [];
            foreach ($resultDate as $key => $row) {
                $isMonthlyPlayer=false;
                $isMonthly =  $this->iMonthlyRepository->byPlate($row->plate, $row->fk_branch_id);
                if ($isMonthly) {
                    $uuid = sprintf("%s%s", $row->plate, $row->fk_branch_id);
                    if (!in_array($uuid, $monthlys)) {
                        $monthlys[] = $uuid;
                        $totalmonthly++;
                        $isMonthlyPlayer = true;    
                    }
                }
                if ($row->fk_agreement_id != -1) {
                    $totalPagamentosAgreement++;
                }
                if (!$isMonthlyPlayer) {
                    $totalRecebido += $row->receipt_by_box;
                    $totalDescontos += $row->discount_applied;
                    $totalPagamentos++;
                }
              
            }

            $totalizer = [];
            foreach ($resultDate as $entry) {
                $nameType = $entry->name_type;
                $minutes = ($entry->hours * 60) + $entry->minutes;
                $receiptByBox = $entry->receipt_by_box;

                if (!isset($totalizer[$nameType])) {
                    $totalizer[$nameType] = [
                        "quantidade" => 0,
                        "total_recebido" => 0
                    ];
                }
                $totalizer[$nameType]["quantidade"]++;
                $totalizer[$nameType]["total_recebido"] += $receiptByBox;
            }

            $newTotalizer = [];
            foreach ($totalizer as $key => $value) {
                $newTotalizer[] = [
                    "name" => (strlen($key) == 0) ? strtoupper(Strings::$APP_PAYMENT_TYPE_UNDEFINED) : strtoupper($key),
                    "quantidade" => $value["quantidade"],
                    "total_recebido" => number_format($value["total_recebido"], 2, '.', '')
                ];
            }


            foreach ($resultMovements as $entry) {
                if (!is_null($entry->deleted_at)) {
                    $deletedCount++;
                }

                if (is_null($entry->deleted_at) &&  is_null($entry->park_date_departure)) {
                    $notExit++;
                }
           }

           

            return [
                "totalRecebido" => number_format($totalRecebido, 2, '.', ''),
                "totalDescontos" => number_format($totalDescontos, 2, '.', ''),
                "vehicles" => $totalPagamentos,
                "separate" => $totalPagamentos - $totalmonthly,
                "accredited" => $totalPagamentosAgreement,
                "monthlypayers" => $totalmonthly,
                "payment_methods" => $newTotalizer,
                "cancelations" => $deletedCount,
                "not_exit" => $notExit
            ];
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
    }
}
