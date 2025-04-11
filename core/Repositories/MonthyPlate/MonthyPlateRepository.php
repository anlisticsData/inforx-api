<?php

namespace Repositories\MonthyPlate;
use Exception;
use Commons\Clock;
use Models\MonthyPlate;
use Resources\HttpStatus;
use Commons\StringBuilder;
use Interfaces\IConnections;
use Dtos\MonthlyDataModelView;
use Commons\DataBaseRepository;
use Interfaces\MonthyPlate\IMonthyPlateRepository;
 

class MonthyPlateRepository implements IMonthyPlateRepository{
    private IConnections $repository;

    public function __construct()
    {
        $this->repository =  new DataBaseRepository();
    }


    function completeVehicleInformation($plate){
        try{
            $sql = new StringBuilder();
            $sql->Insert("SELECT mc.id, mc.monthly_filiais_clientes_id, mc.types_of_cars_id, mc.plate, mc.color, mc.created_at,");
            $sql->Insert("tc.model,tc.brand,umc.*,mp.*");
            $sql->Insert("FROM monthly_cars mc join types_of_car tc on mc.types_of_cars_id=tc.id");
            $sql->Insert("join user_monthy_cars umc on umc.fk_car=mc.id join monthly_payers mp ");
            $sql->Insert("on   mp.monthly_id = umc.fk_monthy_players_id where mc.plate=?");
            
            $resultData = $this->repository->query($sql->toString(),array($plate),false);
            $monthyPlate= new MonthlyDataModelView($resultData);
            if(isset($monthyPlate->monthly_filiais_clientes_id) &&  !is_null($monthyPlate->monthly_filiais_clientes_id)){
                return $monthyPlate->toArray();
            }
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }

    public function findOnePlate($plate){
        try{
            $sql = new StringBuilder();
            $sql->Insert("SELECT id, monthly_filiais_clientes_id, types_of_cars_id, plate, color, created_at FROM monthly_cars ");
            $sql->Insert(" where plate=?");
            $resultData = $this->repository->query($sql->toString(),array($plate),false);
            $monthyPlate= new MonthyPlate($resultData);
            if(isset($monthyPlate->monthly_filiais_clientes_id) &&  !is_null($monthyPlate->monthly_filiais_clientes_id)){
                return $monthyPlate->toArray();
            }
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }
    function findOne($id){}
    function registerNewPlateAtTheBranch(MonthyPlate $monthyPlate){
        try{
            $nowDate=Clock::NowDate();
            $sql = new StringBuilder();
            $sql->Insert("INSERT INTO monthly_cars( monthly_filiais_clientes_id, types_of_cars_id, plate, color, created_at)");
            $sql->Insert(" VALUES(?,?,?,?,?)");
            $data=[
                $monthyPlate->monthly_filiais_clientes_id,
                $monthyPlate->types_of_cars_id,
                $monthyPlate->plate,
                $monthyPlate->color,
                $nowDate];
            $resultData = $this->repository->executeAutoIncrement($sql->toString(),$data);
            return $resultData;
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
        
    }

    
}
