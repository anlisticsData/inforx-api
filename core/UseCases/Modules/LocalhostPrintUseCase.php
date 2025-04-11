<?php

namespace UseCases\Modules;

use Commons\Uteis;
use Interfaces\Modules\IModuleRepository;
use Interfaces\Movements\IMovements;

class LocalhostPrintUseCase
{
    private IMovements $iMovementsRepository;
    private $typePrintDefault = 2;
    public function __construct(IMovements $movementsRepository)
    {
        $this->iMovementsRepository = $movementsRepository;
    }

    public function execute($branch)
    {

        $prints = $this->iMovementsRepository->isprint($this->typePrintDefault, $branch);

        if (count($prints) > 0) {
            foreach ($prints as $key => $print) {
                $this->iMovementsRepository->printExecuted($print->park_id);
            }
        }
        return $prints;
    }
}
