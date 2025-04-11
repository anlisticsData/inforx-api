<?php

namespace Repositories\MovementCameras;

use Commons\Clock;
use DateTime;
use Exception;
use DateTimeZone;
use Commons\Uteis;
use Resources\HttpStatus;
use Commons\StringBuilder;
use Dtos\MovementCameraDto;
use Interfaces\IConnections;
use Commons\DataBaseRepository;
use Interfaces\MovementCameras\IMovimentCameras;


class MovimentCamerasRepository implements IMovimentCameras
{

    private IConnections $repository;

    public function __construct()
    {
        $this->repository =  new DataBaseRepository();
    }



    function allProcessedFalse($limit = 10)
    {
        try {
            $movimentsCameras = [];
            $sql = new StringBuilder();
            $sql->Insert("select  *  from movement_cameras where processed=0  order by id desc limit 0,?");
            $resultData = $this->repository->query($sql->toString(), [$limit]);
            if (!is_null($resultData) && count($resultData) > 0) {
                if ($resultData) {
                    foreach ($resultData as $mv) {
                        $movimentsCameras[] =  new MovementCameraDto($mv);
                    }
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $movimentsCameras;
    }


    function  maxRemoteRef()
    {
        try {
            $movimentCamera = null;
            $sql = new StringBuilder();
            $sql->Insert("select  *  from movement_cameras  order by remote_ref desc limit 0,1 ");
            $resultData = $this->repository->query($sql->toString());
            if (!is_null($resultData) && count($resultData) > 0) {
                if ($resultData) {
                    $resultData =  $resultData[0];
                    $movimentCamera =  new MovementCameraDto($resultData);
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $movimentCamera;
    }


    function  findByUuid($uuid)
    {
        try {
            $movimentCamera = null;
            $sql = new StringBuilder();
            $sql->Insert("select   *  from movement_cameras where  uuid=?");
            $resultData = $this->repository->query($sql->toString(), [$uuid]);
            if (!is_null($resultData) && count($resultData) > 0) {
                if ($resultData) {
                    $resultData =  $resultData[0];
                    $movimentCamera =  new MovementCameraDto($resultData);
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $movimentCamera;
    }



    function processedTrue($uuid)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert(" update movement_cameras set processed=1 where  uuid=?");
            $result = $this->repository->executeRowsCount($sql->toString(),[$uuid]);
            return $result;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }


    function created(MovementCameraDto $movimentCamera)
    {


        try {

            $sql = new StringBuilder();
            $sql->Insert(" INSERT INTO movement_cameras(uuid,nsr,`data`,`hours`, sensor_code, concierge, plate, created_at,remote_ref,update_at,fk_branch_id,processed) ");
            $sql->Insert(" VALUES (?,?, ?, ?, ?, ?, ?, ?,?,?,?,0)");
            //$movimentCamera->created_at = Clock::NowDate();
            $result = $this->repository->executeAutoIncrement($sql->toString(), [
                $movimentCamera->uuid,
                $movimentCamera->nsr,
                $movimentCamera->data,
                $movimentCamera->hours,
                $movimentCamera->sensor_code,
                $movimentCamera->concierge,
                $movimentCamera->plate,
                $movimentCamera->created_at,
                $movimentCamera->remote_ref,
                $movimentCamera->update_at,
                $movimentCamera->fk_branch_id
            ]);
            return $result;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }
}
