<?php

namespace UseCases\PaymentTypes;

use Exception;
use Commons\Uteis;
use Interfaces\Payments\IPaymentTypesRepository;

class AllPaymentsUseCase{
    private IPaymentTypesRepository $iPaymentTypesRepository;
    private $list=null;
    public function __construct( IPaymentTypesRepository $iPaymentTypesRepository){
        $this->iPaymentTypesRepository   =  $iPaymentTypesRepository;
    }     
    public function execute($branchCode){
        try{

           
            
            $this->list =$this->iPaymentTypesRepository->all($branchCode);
            return $this->list;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }


    public function by($id){
        try{



  
            foreach($this->list as $row){
             if($row->id==$id) return $row;
            }
        }catch(Exception $e){
         throw new Exception($e->getMessage(),$e->getCode());
        }

        return null;
     }

}