<?php

namespace UseCases\Modules;

use Commons\Uteis;
use Interfaces\Movements\IMovements;

class LocalhostPrintProcessedUseCase
{
    private IMovements $iMovementsRepository;
    private $typePrintDefault=2;
       public function __construct(IMovements $movementsRepository)
    {
        $this->iMovementsRepository = $movementsRepository;
    }

    public function execute($uuid)
    {
        
       return $this->iMovementsRepository->printExecuted($uuid);
    }
}