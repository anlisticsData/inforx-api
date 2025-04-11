<?php


namespace UseCases\Settings;

use Interfaces\Settings\ISettingRepository;

 

class GetTypeUseCase{
    private ISettingRepository $iSettingRepository;
    public function __construct(ISettingRepository $iSettingRepository){
        $this->iSettingRepository =$iSettingRepository;
    }
    public function execute($settingType){
        $settingData  = $this->iSettingRepository->oneSettingType($settingType);
        return  (strlen(trim($settingData->content)) > 0) ? $settingData: null; 
    }

}


