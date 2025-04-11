<?php


namespace UseCases\Settings;

use Exception;
use Commons\Uteis;
use Models\Setting;
use Dtos\CarTypeDto;
use Interfaces\Car\ICartype;
use Interfaces\Settings\ISettingRepository;

class UpdateContentTypeUseCase{
    private ISettingRepository $iSettingRepository;
    public function __construct(ISettingRepository $iSettingRepository){
        $this->iSettingRepository =$iSettingRepository;
        return $this;
    }
    public function execute(Setting $settingType){
       try{
        if(isset($settingType->content) && strlen($settingType->content) > 0){
            return $this->iSettingRepository->update($settingType);
        }
       }catch(Exception $e){
        throw new Exception($e->getMessage(),$e->getCode());
       }
      
    }

}


