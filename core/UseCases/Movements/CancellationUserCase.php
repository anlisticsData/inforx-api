<?php

namespace UseCases\Movements;

use Exception;
use Interfaces\Movements\IMovements;




class CancellationUserCase{
    private IMovements $IMovements;
    public function __construct(IMovements $IMovements)
    {
        $this->IMovements = $IMovements;
        return $this;
    }
    public function execute($id)
    {
        try {
          return $this->IMovements->cancellation($id);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }




}