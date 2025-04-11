<?php

namespace UseCases\Movements;

use Exception;
use Commons\Uteis;
use Interfaces\IUserCase;
use Interfaces\Movements\IMovements;
 

class CheckIfItIsNotTheSamePlate implements IUserCase
{
    private IMovements $IMovements;
  
    public function __construct(IMovements $IMovements)
    {
        $this->IMovements = $IMovements;
        return $this;
    }

    public function execute($plate)
    {
        try {
           return  (count($this->IMovements->byPlate($plate)) > 0) ? true : false;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
        return false;
    }
}

