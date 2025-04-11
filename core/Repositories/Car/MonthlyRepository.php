<?php

namespace Repositories\Car;

use Exception;
use Commons\Uteis;
use Models\Monthly;
use Dtos\MonthlyDto;
use Resources\HttpStatus;
use Commons\StringBuilder;
use Dtos\MonthlyCartypeDto;
use Interfaces\IConnections;
use Commons\DataBaseRepository;
use Interfaces\Car\IMonthlyRepository;
  
 


class MonthlyRepository implements IMonthlyRepository{
    private IConnections $repository;
    public function __construct()
    {
        $this->repository =  new DataBaseRepository();
    }

    function records(){
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT count(*) as records FROM monthly_cars  where  deleted_at is  null   ");
            $resultData = $this->repository->query($sql->toString(), null, false);
            return $resultData['records'];
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }

    }
    function byPlate($plate,$branchId){
        $list=[];
        try {

   
            $sql = new StringBuilder();
            $sql->Insert("select  toc.id as cartypeId,toc.model ,toc.brand ,mc.id as monthlyId,");
            $sql->Insert("mc.plate, mc.color ,mc.created_at,c.description ,c.cnpj,c.group_id");     
            $sql->Insert("from monthly_cars mc  left join types_of_car toc on mc.types_of_cars_id =toc.id");  
            $sql->Insert("join branches b on b.id=mc.monthly_filiais_clientes_id");
            $sql->Insert("join customers c on c.id=b.costumers_id");
            $sql->Insert("where mc.plate=? and mc.monthly_filiais_clientes_id=? and  mc.deleted_at is  null"); 
            $resultData = $this->repository->query($sql->toString(),[strtoupper(trim($plate)),$branchId]);
            if(!is_null($resultData) && count($resultData) > 0){
                foreach( $resultData  as $index => $row){
                    $rows=[
                        "cartypeId"=>$row['cartypeId'],
                        "model"=>$row['model'],
                        "brand"=>$row['brand'],
                        "monthlyId"=>$row['monthlyId'],
                        "plate"=>$row['plate'],
                        "color"=>$row['color'],
                        "created_at"=>$row['created_at'],
                        "description"=>$row['description'],
                        "cnpj"=>$row['cnpj'],
                        "group_id"=>$row['group_id']
                    ];
                    $list[]=new MonthlyCartypeDto($rows);
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $list;
    }
    function by($monthlyId){
        $list=[];
        try {
            $sql = new StringBuilder();
            $sql->Insert("select  toc.id as cartypeId,toc.model ,toc.brand ,mc.id as monthlyId,mc.plate,");
            $sql->Insert("mc.color ,mc.created_at,c.description ,c.cnpj,c.group_id  ");
            $sql->Insert("from monthly_cars mc ");
            $sql->Insert("left join types_of_car toc on mc.types_of_cars_id =toc.id ");
            $sql->Insert("join customers c on c.id=mc.monthly_filiais_clientes_id ");
            $sql->Insert(" where mc.id=? and  mc.deleted_at is  null ");

            $resultData = $this->repository->query($sql->toString(),[$monthlyId]);
            if(!is_null($resultData) && count($resultData) > 0){
                foreach( $resultData  as $index => $row){
                    $rows=[
                        "cartypeId"=>$row['cartypeId'],
                        "model"=>$row['model'],
                        "brand"=>$row['brand'],
                        "monthlyId"=>$row['monthlyId'],
                        "plate"=>$row['plate'],
                        "color"=>$row['color'],
                        "created_at"=>$row['created_at'],
                        "description"=>$row['description'],
                        "cnpj"=>$row['cnpj'],
                        "group_id"=>$row['group_id']
                    ];
                    $list[]=new MonthlyCartypeDto($rows);
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $list;
    }


    function all(){
        $list=[];
        try {
            $sql = new StringBuilder();
            $sql->Insert("select  toc.id as cartypeId,toc.model ,toc.brand ,mc.id as monthlyId,mc.plate,");
            $sql->Insert("mc.color ,mc.created_at,c.description ,c.cnpj,c.group_id  ");
            $sql->Insert("from monthly_cars mc ");
            $sql->Insert("left join types_of_car toc on mc.types_of_cars_id =toc.id ");
            $sql->Insert("join customers c on c.id=mc.monthly_filiais_clientes_id ");
            $sql->Insert(" where  mc.deleted_at is  null ");

            $resultData = $this->repository->query($sql->toString());
            if(!is_null($resultData) && count($resultData) > 0){
                foreach( $resultData  as $index => $row){
                    $rows=[
                        "cartypeId"=>$row['cartypeId'],
                        "model"=>$row['model'],
                        "brand"=>$row['brand'],
                        "monthlyId"=>$row['monthlyId'],
                        "plate"=>$row['plate'],
                        "color"=>$row['color'],
                        "created_at"=>$row['created_at'],
                        "description"=>$row['description'],
                        "cnpj"=>$row['cnpj'],
                        "group_id"=>$row['group_id']
                    ];
                    $list[]=new MonthlyCartypeDto($rows);
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $list;
    }
    function created(Monthly $monthly){
        try{
            $monthly->plate =  strtoupper(trim($monthly->plate));
            $sql = new StringBuilder();
            $sql->Insert("INSERT INTO monthly_cars(monthly_filiais_clientes_id,types_of_cars_id,plate,color,created_at) ");
            $sql->Insert(" VALUES(?,?,?,?,now())");
            $data=[
                $monthly->monthly_filiais_clientes_id,
                $monthly->types_of_cars_id,
                $monthly->plate,
                $monthly->fk_color_id
            ];
            $resultData = $this->repository->executeAutoIncrement($sql->toString(),$data);
            return $resultData;
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }
   


    function delete($modelId){
        try{
            $sql = new StringBuilder();
            $sql->Insert("UPDATE  monthly_cars  set deleted_at=now()  where id=?");
            $resultData = $this->repository->execute($sql->toString(),[$modelId]);
            if($resultData){
                return true;
            }
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return false;
    }

}