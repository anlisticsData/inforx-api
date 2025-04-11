<?php


namespace UseCases\Settings;

use Commons\Uteis;
use Dtos\CarTypeDto;
use Interfaces\Car\ICartype;
use Interfaces\Settings\ISettingRepository;

class CreateTypeUseCase{
    private ISettingRepository $iSettingRepository;
    public function __construct(ISettingRepository $iSettingRepository){
        $this->iSettingRepository =$iSettingRepository;
        return $this;
    }
    public function execute($settingType){
       $hasConfiguration=$this->iSettingRepository->oneSettingType($settingType->type); 
       if(is_null($hasConfiguration)){
        return  $this->iSettingRepository->created($settingType);
       }else{
        return $hasConfiguration->id;
       }   
    }

}


