<?php 

namespace Repositories\Price;

use Exception;
use Models\Price;
use Commons\Uteis;
use Resources\HttpStatus;
use Commons\StringBuilder;
use Models\PricesIntervals;
use Interfaces\IConnections;
use Commons\DataBaseRepository;
use Commons\DataBaseModuleRepository;
use Models\PricesIntervalsAndVehicle;
use Interfaces\Price\IPriceRepository;

class PriceRepository implements IPriceRepository{
    private IConnections $repository;

    public function __construct() {
        $this->repository = new DataBaseRepository();
    }
   
    function all($branchCode){
        try {
            $isPriceActive=1;
            $sql = new StringBuilder();
            $sql->Insert("SELECT * FROM `pricing` where active_pricing=? and 	branches_id =? ORDER BY `pricing_category` DESC");
            $resultData = $this->repository->query($sql->toString(),[$isPriceActive,$branchCode]);
            if(!is_null($resultData) && count($resultData) > 0){
                $prices=[];
                foreach($resultData as $key => $row){
                    $prices[]=new Price($row);
                }
                return $prices;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }

    function priceActive($branchCode){
        try {
            $isPriceActive=1;
            $sql = new StringBuilder();
            $sql->Insert("SELECT * FROM `pricing` where active_pricing=? and 	branches_id =? ORDER BY `pricing_category` DESC");
            $resultData = $this->repository->query($sql->toString(),[$isPriceActive,$branchCode]);
            if(!is_null($resultData) && count($resultData) > 0){
                $prices=[];
                foreach($resultData as $key => $row){
                    $prices[]=new Price($row);
                }
                return $prices;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }



    function createPriceInterval(PricesIntervals $pricesIntervals){
        try {
            $sql = new StringBuilder();
            $sql->Insert("INSERT INTO prices_by_intervals(`fk_princes_id`,`fk_branch_id`,`initial_start`,initial_end,");
            $sql->Insert("`tolerence`,`mult`,sum,price,`created_at`)");
            $sql->Insert(" VALUES(?,?,?,?,?,?,?,?,now())");
            $data = [
                $pricesIntervals->fk_princes_id,
                $pricesIntervals->fk_branch_id,
                $pricesIntervals->initial_start,
                $pricesIntervals->initial_end,
                $pricesIntervals->tolerence,
                $pricesIntervals->mult,
                $pricesIntervals->sum,
                $pricesIntervals->price,
            ];
            $resultData = $this->repository->executeAutoIncrement($sql->toString(), $data);
            return $resultData;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }

    function hasPriceIntervalType($fkBranchId,$fkPriceId){
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT * FROM `prices_by_intervals` where deleted_at is null  and fk_branch_id =? and fk_princes_id=?  ");
       
            $resultData = $this->repository->query($sql->toString(),[$fkBranchId,$fkPriceId]);
            if(!is_null($resultData) && count($resultData) > 0){
                $prices=[];
                foreach($resultData as $key => $row){
                    $prices[]=new PricesIntervals($row);
                }
                return $prices;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }

    function hasPriceInterval($fkBranchId,$fkPriceId,$intervalStart,$intervalEnd){
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT * FROM `prices_by_intervals` where deleted_at is null  and fk_branch_id =? and fk_princes_id=?  ");
            $sql->Insert(" and initial_start=? and initial_end=?");
            $resultData = $this->repository->query($sql->toString(),[$fkBranchId,$fkPriceId,$intervalStart,$intervalEnd]);
            if(!is_null($resultData) && count($resultData) > 0){
                $prices=[];
                foreach($resultData as $key => $row){
                    $prices[]=new PricesIntervals($row);
                }
                return $prices;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }


    function allPriceIntervalAndTypeVehicle($fkBranchId){
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT  tyv.id as type_car_id,pby.*  FROM `prices_by_intervals` pby ");
            $sql->Insert("join type_of_vehicle tyv on tyv.fk_price_id=pby.fk_princes_id ");
            $sql->Insert("where pby.deleted_at is null  and pby.fk_branch_id=? ");
            $resultData = $this->repository->query($sql->toString(),[$fkBranchId]);
            if(!is_null($resultData) && count($resultData) > 0){
                $prices=[];
                foreach($resultData as $key => $row){
                    $prices[]=new PricesIntervalsAndVehicle($row);
                }
                return $prices;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }


    function allPriceInterval($fkBranchId){
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT * FROM `prices_by_intervals` where deleted_at is null  and fk_branch_id =? ");
            $resultData = $this->repository->query($sql->toString(),[$fkBranchId]);
            if(!is_null($resultData) && count($resultData) > 0){
                $prices=[];
                foreach($resultData as $key => $row){
                    $prices[]=new PricesIntervals($row);
                }
                return $prices;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;

    }
    function deletePriceInterval($fkBranchId,$fkPriceId,$priceId){
        try {
            $sql = new StringBuilder();
            $sql->Insert("UPDATE prices_by_intervals set  deleted_at=now()  where fk_branch_id=? and fk_princes_id=? and  id=? and deleted_at is null ");
            $data = [
              $fkBranchId,$fkPriceId,$priceId
            ];
            $resultData = $this->repository->executeRowsCount($sql->toString(), $data);
            if($resultData > 0 ) return $resultData;
            return null;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;

    }

}