<?php

namespace Services;

use Models\City;
use Dtos\CityDto;
use Commons\Uteis;
use Commons\Paginator;
use Interfaces\IServices;
use Repositories\Citys\CityRepository;

class CityServices implements IServices{
    private $CityRepository;
    function __construct()
    {

       
        $this->CityRepository =  new CityRepository();
       
    }
    
    function SearchCitiesByState($stateId){
        $citys=[];
        $citysData = $this->CityRepository->SearchCitiesByState($stateId);
        foreach($citysData as $key =>$row){
            $city =  new CityDto();
            $city->id= $row->id;
            $city->name= $row->name;
            $city->state_id= $row->state_id;
            $city->created_at= $row->created_at;
            $city->updated_at= $row->updated_at;
            $citys[]=$city;
        }
        return $citys;
    }


    function SearchCitiesBy($stateId){
        $citys=[];
        $citysData = $this->CityRepository->SearchCitiesBy($stateId);
        foreach($citysData as $key =>$row){
            $city =  new CityDto();
            $city->id= $row->id;
            $city->name= $row->name;
            $city->state_id= $row->state_id;
            $city->created_at= $row->created_at;
            $city->updated_at= $row->updated_at;
            $citys[]=$city;
        }
        return $citys;
    }
    


 



    public function SearchCitiesStatePaginator($stateId, $pager=1) {
        $citys=[];
        $paginator = null;
        if(is_null($pager)){
            $citysData = $this->CityRepository->SearchCitiesByStatePaginator(null,null);
        }else{
            $sizeCity=$this->CityRepository->recordsPaginator($stateId);
            $paginatorComponent = new Paginator($pager);
            $paginatorComponent->sizeRecords($sizeCity);
            $citysData = $this->CityRepository->SearchCitiesByStatePaginator($stateId, $paginatorComponent->start, $paginatorComponent->limit);
            $paginator = $paginatorComponent->paginator();
        }

        foreach($citysData as $key =>$row){
            $city =  new CityDto();
            $city->id= $row->id;
            $city->name= $row->name;
            $city->state_id= $row->state_id;
            $city->created_at= $row->created_at;
            $city->updated_at= $row->updated_at;
            $citys[]=$city;
        }
        return ["citys"=>$citys,"paginator"=>$paginator];
    }

    public function SearchCitiesAll($pager=1){
        
        $citys=[];
        $paginator = null;
        if(is_null($pager)){
            
            $citysData = $this->CityRepository->SearchCitiesAll(null,null);
        }else{
            
            $sizeCity=$this->CityRepository->records();
            
            $paginatorComponent =  new Paginator($pager);
            $paginatorComponent->sizeRecords($sizeCity);
            $citysData = $this->CityRepository->SearchCitiesAll($paginatorComponent->start,$paginatorComponent->limit);
            $paginator = $paginatorComponent->paginator();
        }
        foreach($citysData as $key =>$row){
            $city =  new CityDto();
            $city->id= $row->id;
            $city->name= $row->name;
            $city->state_id= $row->state_id;
            $city->created_at= $row->created_at;
            $city->updated_at= $row->updated_at;
            $citys[]=$city;

        }
        return ["citys"=>$citys,"paginator"=>$paginator];
    }

}