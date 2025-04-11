<?php

namespace Repositories\Payments;

use Exception;
use Commons\Uteis;
use Models\Payment;
use Resources\HttpStatus;
use Commons\StringBuilder;
use Interfaces\IConnections;
use Commons\DataBaseRepository;
use Interfaces\Agreements\IAgreementRepository;
use Interfaces\Payments\IPaymentTypesRepository;

class ResumeBoxPeriodRepository
{
    private IConnections $repository;


    public function __construct()
    {

        $this->repository = new DataBaseRepository();
    }

    function cashPeriodSummary($initalDate, $endDate, $branchCode)
    {
        try {

            $sql = new StringBuilder();
            $sql->Insert("select *  from payments p where p.created_at between ? and ?  and fk_branch_id=? order by p.created_at desc");
            $resultData = $this->repository->query($sql->toString(), [$initalDate, $endDate, $branchCode]);
            if (!is_null($resultData) && count($resultData) > 0) {
                $payments = [];
                foreach ($resultData as $key => $row) {
                    $payments[] = new Payment($row);
                }
                return $payments;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }
}
