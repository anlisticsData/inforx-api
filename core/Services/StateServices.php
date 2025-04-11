<?php

namespace Services;

use Dtos\StateDto;
use Interfaces\IServices;
use Repositories\States\StateRepository;

class StateServices implements IServices{
    private $repository;
    function __construct()
    {
        $this->repository =  new StateRepository();
    }

    function All(){
        return $this->repository->All();
    }
    function By($stateId){
        $states=[];
        $stateData = $this->repository->By($stateId);
        foreach($stateData as $key =>$row){
            $state =  new StateDto();
            $state->id= $row->id;
            $state->name= $row->name;
            $state->uf= $row->uf;
            $state->created_at= $row->created_at;
            $state->updated_at= $row->updated_at;
            $states[]=$state;

        }
        return $states;
    }

}