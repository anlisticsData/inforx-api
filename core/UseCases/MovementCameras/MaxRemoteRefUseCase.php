<?php


namespace UseCases\MovementCameras;

use Exception;
use Commons\Uteis;
use Interfaces\IUserCase;
use Dtos\MovementCameraDto;
use Interfaces\MovementCameras\IMovimentCameras;
use Resources\HttpStatus;
use Resources\Strings;

class MaxRemoteRefUseCase implements IUserCase
{
    private IMovimentCameras $iMovimentCameras;
    public function __construct(iMovimentCameras $iMovimentCameras)
    {
        $this->iMovimentCameras = $iMovimentCameras;
        return $this;
    }
    public function execute()
    {
        try {
            $movement = $this->iMovimentCameras->maxRemoteRef();
            if(is_null($movement)){
                return new MovementCameraDto([
                    "remote_ref"=> 0
                ]);
            }
            return $this->iMovimentCameras->maxRemoteRef();
        } catch (Exception $e) {
            throw new Exception($e->getMessage(),$e->getCode());
        }
         
    }
}
