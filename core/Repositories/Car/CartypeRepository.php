<?php

namespace Repositories\Car;

use Exception;
use Commons\Uteis;
use Models\Cartype;
use Models\CartypeVehicle;
use Resources\Strings;
use Resources\HttpStatus;
use Commons\StringBuilder;
use Interfaces\Car\ICartype;
use Interfaces\IConnections;
use Commons\DataBaseRepository;





class CartypeRepository implements ICartype
{
    private IConnections $repository;
    public function __construct()
    {
        $this->repository =  new DataBaseRepository();
    }

    function records()
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT count(*) as records FROM types_of_car ");
            $resultData = $this->repository->query($sql->toString(), null, false);
            return $resultData['records'];
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
    }



    function byTypeOfVehicles($code)
    {
        $list = [];
        try {


            $sql = new StringBuilder();
            $sql->Insert("select id, name , created_at  from type_of_vehicle  where id=? and deleted_at  is  null");
            $resultData = $this->repository->query($sql->toString(), [$code]);
            if (!is_null($resultData) && count($resultData) > 0) {
                foreach ($resultData  as $index => $row) {
                    $rows = [
                        "id" => $row['id'],
                        "name" => $row['name'],
                        "created_at" => $row['created_at'],
                    ];
                    $list[] = new CartypeVehicle($rows);
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }

        return $list;
    }


    function one($code)
    {
        $list = [];
        try {

            $sql = new StringBuilder();
            $sql->Insert("SELECT `id`, `model`, `brand` FROM types_of_car where id=?");
            $resultData = $this->repository->query($sql->toString(), [$code]);
            if (!is_null($resultData) && count($resultData) > 0) {
                foreach ($resultData  as $index => $row) {
                    $rows = [
                        "id" => $row['id'],
                        "model" => $row['model'],
                        "brand" => $row['brand'],
                    ];
                    $list[] = new Cartype($rows);
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }

        return $list;
    }



    function typeOfVehicles()
    {
        $list = [];
        try {
            $sql = new StringBuilder();
            $sql->Insert("select id,fk_price_id, name , created_at  from type_of_vehicle  where  deleted_at  is  null");
            $resultData = $this->repository->query($sql->toString());
            if (!is_null($resultData) && count($resultData) > 0) {
                foreach ($resultData  as $index => $row) {
                    $rows = [
                        "id" => $row['id'],
                        "fk_price_id" => $row['fk_price_id'],
                        "name" => $row['name'],
                        "created_at" => $row['created_at'],

                    ];
                    $list[] = new CartypeVehicle($rows);
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }

        return $list;
    }


    function all()
    {
        $list = [];
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT `id`, `model`, `brand` FROM types_of_car");
            $resultData = $this->repository->query($sql->toString());
            if (!is_null($resultData) && count($resultData) > 0) {
                foreach ($resultData  as $index => $row) {
                    $rows = [
                        "id" => $row['id'],
                        "model" => $row['model'],
                        "brand" => $row['brand'],
                    ];
                    $list[] = new Cartype($rows);
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }

        return $list;
    }
    function created(Cartype $cartype)
    {
        try {

            if (!is_null($this->hasCarToModel($cartype->model))) {
                throw new Exception(Strings::$STR_CAR_TYPE_NOT_CREATED, HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
            }
            $sql = new StringBuilder();
            $sql->Insert("INSERT INTO types_of_car(model,brand) ");
            $sql->Insert(" VALUES(?,?)");
            $data = [
                $cartype->model,
                $cartype->brand
            ];
            $resultData = $this->repository->executeAutoIncrement($sql->toString(), $data);
            return $resultData;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }

    function hasCarToModel($model)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT `id`, `model`, `brand` FROM types_of_car where model=?");
            $resultData = $this->repository->query($sql->toString(), array($model));

            if (!is_null($resultData) && count($resultData) > 0) {
                $resultData = $resultData[0];
                $rows = [
                    "id" => $resultData['id'],
                    "model" => $resultData['model'],
                    "brand" => $resultData['brand'],
                ];
                return  new Cartype($rows);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }

        return null;
    }


    function delete($modelId)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert(" Delete from types_of_car  where id=?");
            $data = [$modelId];
            $resultData = $this->repository->execute($sql->toString(), $data);
            return $resultData;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }
}
