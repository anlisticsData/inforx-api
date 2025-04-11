<?php

namespace  UseCases\Price;

use Commons\Uteis;
use Exception;
use Interfaces\IUserCase;
use Interfaces\Price\IPriceRepository;

class PriceActiveUserCase implements IUserCase{
    private IPriceRepository $iPriceRepository;
    private $list=null;
    public function __construct(IPriceRepository $iPriceRepository){
        $this->iPriceRepository = $iPriceRepository;
    }     
    public function execute($branchCode){
       try{
            $this->list =  $this->iPriceRepository->priceActive($branchCode);
            return $this->list;
       }catch(Exception $e){
        throw new Exception($e->getMessage(),$e->getCode());
       }
    }

 

}