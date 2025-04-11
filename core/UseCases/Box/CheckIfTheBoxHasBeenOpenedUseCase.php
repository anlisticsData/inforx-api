<?php

namespace UseCases\Box;

use Exception;
 
use Interfaces\Box\IBoxRepository;

class CheckIfTheBoxHasBeenOpenedUseCase
{
    private  IBoxRepository $iBoxRepository;
    public function __construct(IBoxRepository $iBoxRepository)
    {
        $this->iBoxRepository = $iBoxRepository;
    }
    public function execute($branchCode,$isRow=false)
    {
        try {
            $dateCurrent =  date("Y-m-d");
            if($isRow){
                return $this->iBoxRepository->by($dateCurrent,$branchCode,0) ;
            }else{
                return $this->iBoxRepository->isOpenBox($dateCurrent, $branchCode) ;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}
