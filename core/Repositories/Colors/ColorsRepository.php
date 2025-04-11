<?php

namespace Repositories\Colors;

use Exception;
use Models\Colors;
use Dtos\ColorsDto;
use Resources\HttpStatus;
use Commons\StringBuilder;
use Interfaces\IConnections;
use Commons\DataBaseRepository;
use Interfaces\Colors\IColorsRepository;

class ColorsRepository implements IColorsRepository{
    private IConnections $repository;
    public function __construct()
    {
        $this->repository =  new DataBaseRepository();
    }

    
    function created(Colors $cartype){}
    function hasColorToModel($model){}
    function delete($corId){}


    function records()
    {
        try{
            $sql = new StringBuilder();
            $sql->Insert("SELECT count(*) as records FROM colors;");
            $resultData =$this->repository->query($sql->toString(),null,false);
            return $resultData['records']; 
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return 0;
    }
    function all(){
        $list=[];
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT  `id`, `description`, created_at FROM colors ");
            $resultData = $this->repository->query($sql->toString());
            if(!is_null($resultData) && count($resultData) > 0){
                foreach( $resultData  as $index => $row){
                    $rows=[
                        "id"=>$row['id'],
                        "description"=>strtoupper($row['description']),
                        "created_at"=>$row['created_at'],
                    ];
                    $list[]=new ColorsDto($rows);
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }

        return $list;
    }


    function one($code){
        $list=[];
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT  `id`, `description`, created_at FROM colors where id=?");
            $resultData = $this->repository->query($sql->toString(),[$code]);
            if(!is_null($resultData) && count($resultData) > 0){
                foreach( $resultData  as $index => $row){
                    $rows=[
                        "id"=>$row['id'],
                        "description"=>$row['description'],
                        "created_at"=>$row['created_at'],
                    ];
                    $list[]= new ColorsDto($rows);
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }

        return $list;
    }

}


