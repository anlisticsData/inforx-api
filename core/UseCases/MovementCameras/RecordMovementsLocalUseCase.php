<?php


namespace UseCases\MovementCameras;

use Commons\Clock;
use Exception;
use Commons\Uteis;
use Interfaces\IUserCase;
use Dtos\MovementCameraDto;
use Dtos\MovementsDto;
use Interfaces\MovementCameras\IMovimentCameras;
use Resources\HttpStatus;
use Resources\Strings;

class RecordMovementsLocalUseCase implements IUserCase
{
    private IMovimentCameras $iMovimentCameras;
    private RecordCameraMovementUseCase $recordCameraMovementUse;

    public function __construct(iMovimentCameras $iMovimentCameras)
    {
        $this->iMovimentCameras = $iMovimentCameras;
        $this->recordCameraMovementUse =  new RecordCameraMovementUseCase($iMovimentCameras);
        return $this;
    }
    public function execute($listMovementRemote,$fkBranchId)
    {
        try {
            foreach($listMovementRemote as $movement){
                $movementLocal =  new MovementCameraDto([
                    "fk_branch_id"=>$fkBranchId,
                    "nsr" =>$movement["nsr"],
                    "data"=>$movement["data"],
                    "hours"=>$movement["hora"],
                    "processed"=>0,
                    "sensor_code"=>$movement["codigosensor"],
                    "concierge"=>$movement["portatirasensor"],
                    "plate"=>$movement["placa"],
                    "created_at"=>$movement["created_at"],
                    "remote_ref"=>$movement["codigo"],
                    "update_at"=>Clock::NowDate()
                ]);
                $this->recordCameraMovementUse->execute($movementLocal);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(),$e->getCode());
        }

 
    }
}
