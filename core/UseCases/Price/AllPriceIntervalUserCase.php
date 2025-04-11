<?php

namespace  UseCases\Price;

use Exception;
use Resources\Strings;
use Interfaces\IUserCase;
use Resources\HttpStatus;
use Dtos\PricesIntervalsDto;
use Interfaces\Price\IPriceRepository;
 


class AllPriceIntervalUserCase implements IUserCase{
    private IPriceRepository $iPriceRepository;
    public function __construct(IPriceRepository $iPriceRepository){
        $this->iPriceRepository = $iPriceRepository;
    }     
    public function execute(PricesIntervalsDto  $pricesIntervalsDto){
       try{
        
           if(is_null($this->iPriceRepository->hasPriceIntervalType($pricesIntervalsDto->fk_branch_id,$pricesIntervalsDto->fk_princes_id))){
               return [];
            }
            return $this->iPriceRepository->allPriceInterval($pricesIntervalsDto->fk_branch_id);
       }catch(Exception $e){
        throw new Exception($e->getMessage(),$e->getCode());
       }
    }
}