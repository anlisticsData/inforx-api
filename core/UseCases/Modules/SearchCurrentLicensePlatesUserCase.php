<?php


namespace UseCases\Modules;

use Interfaces\IUserCase;
use Adapters\PdoMysqlConectedModuleAdapter;
use Commons\Uteis;
use Dtos\MovementCameraDto;
use Exception;

class  SearchCurrentLicensePlatesUserCase implements IUserCase
{
    private PdoMysqlConectedModuleAdapter $iModule;



    function  __construct(PdoMysqlConectedModuleAdapter $iModule)
    {
        $this->iModule =  $iModule;
    }

    function execute(MovementCameraDto $movementCameraDto,$branchInformations)
    {
        $outPlateUserCase =[];
        try {
            $dayCurrency = date("d");
            $monthCurrency = date("m");
            $yearCurrency = date("Y");
            $cameras = [];
            $camerasCampos = [];
            foreach ($branchInformations['cams'] as $i => $value) {
                $cameras[] = $value;
                $camerasCampos[] = "portatirasensor=?";
            }
            $where = sprintf("  year(created_at)=%s and month(created_at)=%s and day(created_at)=%s  ", $yearCurrency, $monthCurrency, $dayCurrency);
            $sql = "select *  from movimentoscameras where  codigo > ".$movementCameraDto->remote_ref."  and   (" . implode(" or ", $camerasCampos) . ") and  " . $where . ' ORDER by created_at asc  ';
            $outPlateUserCase = $this->iModule->query($sql,$cameras);
        } catch (Exception $e) {
          throw new Exception($e->getMessage(),$e->getCode());
        }

        return  $outPlateUserCase ;
    
    }
}
