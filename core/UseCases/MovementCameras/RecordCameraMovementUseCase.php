<?php


namespace UseCases\MovementCameras;

use Exception;
use Commons\Uteis;
use Interfaces\IUserCase;
use Dtos\MovementCameraDto;
use Interfaces\MovementCameras\IMovimentCameras;
use Resources\HttpStatus;
use Resources\Strings;

class RecordCameraMovementUseCase implements IUserCase
{
    private IMovimentCameras $iMovimentCameras;
    public function __construct(iMovimentCameras $iMovimentCameras)
    {
        $this->iMovimentCameras = $iMovimentCameras;
        return $this;
    }
    public function execute(MovementCameraDto $input)
    {

        try {
            $isMovement = $this->iMovimentCameras->findByUuid($input->uuid);
            if($isMovement==null){
                return $this->iMovimentCameras->created($input);
            }
            throw new Exception(Strings::$STR_EMPYTY,HttpStatus::$HTTP_CODE_NO_CONTENT);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(),$e->getCode());
        }

        return null;
    }
}
