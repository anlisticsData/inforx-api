<?php

namespace UseCases\Modules;

use Commons\Uteis;
use Dtos\ModuleDto;
use Interfaces\Modules\IModuleRepository;

 

class FindOneUserCase{
    private IModuleRepository $moduleRepository;
    public function __construct(IModuleRepository $moduleRepository){
        $this->moduleRepository = $moduleRepository;
    }     
    public function execute($moduleCode){
        return new ModuleDto( $this->moduleRepository->findOne($moduleCode)->toArray());
    }
}
