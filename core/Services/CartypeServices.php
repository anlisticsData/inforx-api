<?php

namespace Services;

use Commons\Uteis;
use Models\Cartype;
use Dtos\CarTypeDto;
use Interfaces\IServices;
use Requests\TypeCarRequest;
use Repositories\Car\CartypeRepository;
 

 
class CartypeServices implements IServices {
    private $CartypeRepository;
    function __construct()
    {
      
        $this->CartypeRepository =  new CartypeRepository();       
    }
    
    public function createdCarType(TypeCarRequest $carType){
        try{
            $carTypeModel =  new Cartype($carType->toArray());
            $out = $this->CartypeRepository->created($carTypeModel);
            return $out;
        }catch(Exception $e){
            throw new Exception($e->getMessage(),HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
    }
    public function all(){
        try{
            $outData = $this->CartypeRepository->all();
            $carTypes=[];
            foreach($outData as $index =>$item){
                $cartype= new CarTypeDto(
                    [
                        "id"=>$item->id,
                        "model"=>$item->model,
                        "brand"=>$item->brand
                        
                    ]
                );
               
                $carTypes[]=$cartype;

            }
            return $carTypes;
        }catch(Exception $e){
            throw new Exception($e->getMessage(),HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
    }

    public function remove($modelId){
        try{
            return $this->CartypeRepository->delete($modelId);
            
        }catch(Exception $e){
            throw new Exception($e->getMessage(),HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
    }


    


}


 