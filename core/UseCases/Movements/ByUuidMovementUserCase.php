<?php

namespace UseCases\Movements;

use Commons\ResponseJson;
use Exception;
use MockPlate;
use Commons\Uteis;
use Dtos\ModuleDto;
use Models\Movements;
use Dtos\MovementsDto;
use Models\MonthyPlate;
use Dtos\MonthyPlateDto;
use Interfaces\IUserCase;
use Interfaces\Movements\IMovements;

class ByUuidMovementUserCase implements IUserCase
{
    private IMovements $IMovements;
    private $list=null;
    public function __construct(IMovements $IMovements)
    {
        $this->IMovements = $IMovements;
        return $this;
    }
    public function execute($uuid)
    {
        try {
            $this->list=$this->IMovements->byUuid($uuid);
            return $this->list;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }


    public function by($id){
        try{

            new ResponseJson(200,$this->list);
            
            foreach($this->list as $row){
          
             if($row->park_id==$id) return $row;
            }
        }catch(Exception $e){
         throw new Exception($e->getMessage(),$e->getCode());
        }

        return null;
     }

}
