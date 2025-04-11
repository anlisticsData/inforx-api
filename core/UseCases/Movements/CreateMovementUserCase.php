<?php

namespace UseCases\Movements;

use Exception;
use Models\Movements;
use Dtos\MovementsDto;
use Interfaces\IUserCase;
use Interfaces\Movements\IMovements;
 

class CreateMovementUserCase implements IUserCase
{
    private IMovements $IMovements;
    public function __construct(IMovements $IMovements)
    {
        $this->IMovements = $IMovements;
        return $this;
    }
    public function execute(MovementsDto $movements)
    {
        try {
            return $this->IMovements->created(new Movements($movements->toArray()));
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
        return null;
    }
}
