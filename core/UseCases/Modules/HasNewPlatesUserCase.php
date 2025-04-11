<?php

namespace UseCases\Modules;

use Commons\Uteis;
use Dtos\ModuleDto;
use Interfaces\Modules\IModuleRepository;

 

class HasNewPlatesUserCase{
    private IModuleRepository $moduleRepository;
    public function __construct(IModuleRepository $moduleRepository){
        $this->moduleRepository = $moduleRepository;
    }     
    public function execute($lastPlate=null){
        return $this->moduleRepository->hasNewPlates($lastPlate);
    }
}
