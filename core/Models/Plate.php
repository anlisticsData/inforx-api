<?php

namespace Models;

use Interfaces\IToArray;

class Plate implements IToArray{

    private $inputs=["codigo", "nsr", "status", "data", "hora","codigosensor","portatirasensor","placa","created_at","update_at","tempoDePermanencia"];
    public $codigo;
    public $nsr;
    public $status;
    public $hora;
    public $codigosensor;
    public $portatirasensor;
    public $placa;
    public $tempoDePermanencia;
    public $created_at;
    public $update_at;
    

    public function __construct($data=null){
        $this->tempoDePermanencia=date("Y-m-d H:i:s");
        if(!is_null($data) && is_array($data)){
            foreach($data as $key =>$row ){
                if(in_array($key,$this->inputs)){
                   $this->$key=$row;     
                }
            }
        }
    }
    function toArray()  {
        $rows=[];
        foreach($this->inputs as $key =>$value){
            $rows[$value]=$this->$value;
        }
        return $rows;
    }


}


