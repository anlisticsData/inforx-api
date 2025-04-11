<?php
namespace  UseCases\Settings;

use Commons\ResponseJson;
use Commons\Uteis;
use Dtos\PixCodeDto;
use Interfaces\Settings\ISettingRepository;
use Models\Setting;

class CreatePixSettingUseCase{
    private ISettingRepository $iSettingRepository;
    public function __construct(ISettingRepository $iSettingRepository){
        $this->iSettingRepository = $iSettingRepository;
    }     
    public function  execute(PixCodeDto $pixCodeDtos){
        $setting = new Setting([
            "type"=>$pixCodeDtos->pix_code,
            "content"=>$pixCodeDtos->pix_key
        ]);
        $isSetting =  $this->iSettingRepository->oneSettingType($pixCodeDtos->pix_code);
        if(is_null($isSetting)){
            return $this->iSettingRepository->created($setting);
        }else{
            $setting->id=$isSetting->id;
            return $this->iSettingRepository->update($setting);
        }
    }
}



