<?php

namespace  UseCases\Price;

use Exception;
use Resources\Strings;
use Interfaces\IUserCase;
use Resources\HttpStatus;
use Dtos\PricesIntervalsDto;
use Interfaces\Price\IPriceRepository;
 

class DeletePriceIntervalUserCase implements IUserCase{
    private IPriceRepository $iPriceRepository;
    public function __construct(IPriceRepository $iPriceRepository){
        $this->iPriceRepository = $iPriceRepository;
    }     
    public function execute(PricesIntervalsDto  $pricesIntervalsDto){
       try{
        
           if(is_null($this->iPriceRepository->hasPriceIntervalType($pricesIntervalsDto->fk_branch_id,$pricesIntervalsDto->fk_princes_id))){
               throw new Exception(Strings::$APP_PRINCES_TYPES_ERROR,HttpStatus::$HTTP_CODE_BAD_REQUEST);
              
            }
            return $this->iPriceRepository->deletePriceInterval(
                $pricesIntervalsDto->fk_branch_id,
                $pricesIntervalsDto->fk_princes_id,
                $pricesIntervalsDto->id,
        );
       }catch(Exception $e){
        throw new Exception($e->getMessage(),$e->getCode());
       }
    }
}