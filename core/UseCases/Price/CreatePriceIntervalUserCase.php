<?php

namespace  UseCases\Price;

use Commons\Uteis;
use Exception;
use Interfaces\IUserCase;
use Models\PricesIntervals;
use Dtos\PricesIntervalsDto;
use Dtos\PricesByIntervalsDto;
use Interfaces\Price\IPriceRepository;
use Resources\HttpStatus;
use Resources\Strings;


class CreatePriceIntervalUserCase implements IUserCase{
    private IPriceRepository $iPriceRepository;
    public function __construct(IPriceRepository $iPriceRepository){
        $this->iPriceRepository = $iPriceRepository;
    }     
    public function execute(PricesIntervalsDto  $pricesIntervalsDto){
       try{
            if(is_null($this->iPriceRepository->hasPriceIntervalType($pricesIntervalsDto->fk_branch_id,$pricesIntervalsDto->fk_princes_id))){
                throw new Exception(Strings::$APP_PRINCES_TYPES_ERROR,HttpStatus::$HTTP_CODE_BAD_REQUEST);
            }
            if(!is_null($this->iPriceRepository->hasPriceInterval($pricesIntervalsDto->fk_branch_id,$pricesIntervalsDto->fk_princes_id,
            $pricesIntervalsDto->initial_start,$pricesIntervalsDto->initial_end))){
                throw new Exception(Strings::$APP_PRINCES_INPUT_VALUE_INTERVAL_DUPLICATE,HttpStatus::$HTTP_CODE_BAD_REQUEST);
            }
            return $this->iPriceRepository->createPriceInterval(new PricesIntervals($pricesIntervalsDto->toArray()));
       }catch(Exception $e){
        throw new Exception($e->getMessage(),$e->getCode());
       }
    }
}