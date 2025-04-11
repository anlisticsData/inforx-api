<?php

namespace Repositories\Reports;

use Exception;
use Commons\Uteis;
use Dtos\IntervalDto;
use Resources\HttpStatus;
use Commons\StringBuilder;
use Interfaces\IConnections;
use Commons\DataBaseRepository;
use Interfaces\Reports\IReports;

 

class ReportsRepository implements IReports
{

    private IConnections $repository;
    public function __construct()
    {
        $this->repository = new DataBaseRepository();
    }



    function summaryBoxPeriods(IntervalDto $intervalDto){
        $summary = [];
        
        try {
            $sql = new StringBuilder();
            $sql->Insert("select p.fk_movements_id,mv.park_vehicle_plate,mv.created_at,mv.park_date_departure, p.fk_movements_id, pt.payment_methods_name,a.name,a.cnpj,a.price,p.created_at,p.fk_movements_id, ");
            $sql->Insert("p.receipt_by_box,p.discount_applied,p.payment_change,mv.deleted_at,a.id as `agreements`");
            $sql->Insert("from payments p  left join payment_types pt on  ");
            $sql->Insert("pt.id=p.fk_payment_types left join agreements a on p.fk_agreement_id=a.id ");
            $sql->Insert("left join movements mv  on p.fk_movements_id=mv.park_id ");
            $sql->Insert("where  p.created_at BETWEEN ? AND ?");
 
            $data=[
                $intervalDto->initialDate,$intervalDto->endDate
            ];
            $resultData = $this->repository->query($sql->toString(),$data);
            foreach ($resultData as $index => $row) {
                $summary[] = $row;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $summary;
    }



    function summaryBoxAgreements(IntervalDto $intervalDto){
        $summary = [];
        $splitDate =  $intervalDto->separateDate();
        try {
            $sql = new StringBuilder();
            $sql->Insert("select a.name,sum(p.receipt_by_box) as `receveid`,sum(p.discount_applied) as `discounted`,sum(p.payment_change) as `difference` ");
            $sql->Insert("from payments p  left join payment_types pt on  ");
            $sql->Insert("pt.id=p.fk_payment_types left join agreements a on p.fk_agreement_id=a.id ");
            $sql->Insert("left join movements mv  on p.fk_movements_id=mv.park_id ");
            $sql->Insert("where (year(p.created_at)=? and month(p.created_at)=? and day(p.created_at)=?) and a.name is not null group by a.name");
            $data=[
                $splitDate['year'],
                $splitDate['month'],
                $splitDate['day']
            ];
            $resultData = $this->repository->query($sql->toString(),$data);
            foreach ($resultData as $index => $row) {
                $summary[] = $row;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $summary;
    }

    function summaryBox(IntervalDto $intervalDto)
    {
        
        $summary = [];
        $splitDate =  $intervalDto->separateDate();
        try {
            $sql = new StringBuilder();
            $sql->Insert("select p.fk_movements_id,mv.park_vehicle_plate,mv.created_at,mv.park_date_departure, p.fk_movements_id, pt.payment_methods_name,a.name,a.cnpj,a.price,p.created_at,p.fk_movements_id, ");
            $sql->Insert("p.receipt_by_box,p.discount_applied,p.payment_change,mv.deleted_at,a.id as `agreements`");
            $sql->Insert("from payments p  left join payment_types pt on  ");
            $sql->Insert("pt.id=p.fk_payment_types left join agreements a on p.fk_agreement_id=a.id ");
            $sql->Insert("left join movements mv  on p.fk_movements_id=mv.park_id ");
            $sql->Insert("where (year(p.created_at)=? and month(p.created_at)=? and day(p.created_at)=?)");
            $data=[
                $splitDate['year'],
                $splitDate['month'],
                $splitDate['day']
            ];
            $resultData = $this->repository->query($sql->toString(),$data);
            foreach ($resultData as $index => $row) {
                $summary[] = $row;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $summary;
    }
}
