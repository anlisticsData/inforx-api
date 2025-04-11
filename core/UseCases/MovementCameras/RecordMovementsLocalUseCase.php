<?php


namespace UseCases\MovementCameras;

use Exception;
use Commons\Clock;
use Commons\Uteis;
use Dtos\MovementsDto;
use Resources\Strings;
use Interfaces\IUserCase;
use Resources\HttpStatus;
use Dtos\MovementCameraDto;
use UseCases\Core\PlateAndValidUseCase;
use Interfaces\MovementCameras\IMovimentCameras;

class RecordMovementsLocalUseCase implements IUserCase
{
    private IMovimentCameras $iMovimentCameras;
    private RecordCameraMovementUseCase $recordCameraMovementUse;
    private PlateAndValidUseCase $plateAndValidUseCase;
    private RecordCameraMovementUseCase $recordCameraMovementUseCase;





    public function __construct(iMovimentCameras $iMovimentCameras, PlateAndValidUseCase $plateAndValidUseCase , RecordCameraMovementUseCase $recordCameraMovementUseCase)
    {
        $this->iMovimentCameras = $iMovimentCameras;
        $this->plateAndValidUseCase = $plateAndValidUseCase;
        $this->recordCameraMovementUseCase = $recordCameraMovementUseCase;
    }
 
    public function execute($listMovementRemote, $fkBranchId)
    {
        try {
            foreach ($listMovementRemote as $movement) {
                if ($this->plateAndValidUseCase->execute($movement["placa"]) == 1) {

                    $movementLocal =  new MovementCameraDto([
                        "fk_branch_id" => $fkBranchId,
                        "nsr" => $movement["nsr"],
                        "data" => $movement["data"],
                        "hours" => $movement["hora"],
                        "processed" => 0,
                        "sensor_code" => $movement["codigosensor"],
                        "concierge" => $movement["portatirasensor"],
                        "plate" => $movement["placa"],
                        "created_at" => $movement["created_at"],
                        "remote_ref" => $movement["codigo"],
                        "update_at" => Clock::NowDate()
                    ]);

                  
                    
                    $this->recordCameraMovementUse->execute($movementLocal);
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}
