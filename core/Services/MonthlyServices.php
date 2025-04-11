<?php

namespace Services;

use Exception;
use Models\Monthly;
use Dtos\MonthlyDto;
use Interfaces\IServices;
use Resources\HttpStatus;
use Dtos\MonthlyCartypeDto;
use Requests\MonthlyRequest;
use Repositories\Car\MonthlyRepository;



class MonthlyServices implements IServices {
    private $MonthlyRepository;
    function __construct()
    {
      
        $this->MonthlyRepository =  new MonthlyRepository();       
    }

        
    public function create(MonthlyRequest $monthlyRequest){
        try{
            $monthlyModel =  new Monthly($monthlyRequest->toArray());
            $out = $this->MonthlyRepository->created($monthlyModel);
            return $out;
        }catch(Exception $e){
            throw new Exception($e->getMessage(),HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
    }

    public function by($monthlyId){
        try{
            $outData = $this->MonthlyRepository->by($monthlyId);
            $MonthlyCarTypes=[];
            foreach($outData as $index =>$item){
                $MonthlyCarType= new MonthlyCartypeDto(
                    [
                        "cartypeId"=>$item->cartypeId,
                        "model"=>$item->model,
                        "brand"=>$item->brand,
                        "monthlyId"=>$item->monthlyId,
                        "plate"=>$item->plate,
                        "color"=>$item->color,
                        "created_at"=>$item->created_at,
                        "description"=>$item->description,
                        "cnpj"=>$item->cnpj,
                        "group_id"=>$item->group_id,
                        
                    ]
                );
                $MonthlyCarTypes[]=$MonthlyCarType;
            }
            return $MonthlyCarTypes;
        }catch(Exception $e){
            throw new Exception($e->getMessage(),HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
    }

    public function byPlate($plate,$branch){
        try{
            $outData = $this->MonthlyRepository->byPlate($plate,$branch);
            $MonthlyCarTypes=[];
            foreach($outData as $index =>$item){
                $MonthlyCarType= new MonthlyCartypeDto(
                    [
                        "cartypeId"=>$item->cartypeId,
                        "model"=>$item->model,
                        "brand"=>$item->brand,
                        "monthlyId"=>$item->monthlyId,
                        "plate"=>$item->plate,
                        "color"=>$item->color,
                        "created_at"=>$item->created_at,
                        "description"=>$item->description,
                        "cnpj"=>$item->cnpj,
                        "group_id"=>$item->group_id,
                        
                    ]
                );
                $MonthlyCarTypes[]=$MonthlyCarType;
            }
            return $MonthlyCarTypes;
        }catch(Exception $e){
            throw new Exception($e->getMessage(),HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
    }



    public function all(){
        try{
            $outData = $this->MonthlyRepository->all();
            $MonthlyCarTypes=[];
            foreach($outData as $index =>$item){
                $MonthlyCarType= new MonthlyCartypeDto(
                    [
                        "cartypeId"=>$item->cartypeId,
                        "model"=>$item->model,
                        "brand"=>$item->brand,
                        "monthlyId"=>$item->monthlyId,
                        "plate"=>$item->plate,
                        "color"=>$item->color,
                        "created_at"=>$item->created_at,
                        "description"=>$item->description,
                        "cnpj"=>$item->cnpj,
                        "group_id"=>$item->group_id,
                        
                    ]
                );
                $MonthlyCarTypes[]=$MonthlyCarType;
            }
            return $MonthlyCarTypes;
        }catch(Exception $e){
            throw new Exception($e->getMessage(),HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
    }


    public function delete( $monthlyId){
        try{
           
            $out = $this->MonthlyRepository->delete($monthlyId);
            return $out;
        }catch(Exception $e){
            throw new Exception($e->getMessage(),HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
    }




}