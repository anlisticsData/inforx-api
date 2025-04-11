<?php

namespace Commons;

use Adapters\PhpModuleAdapter;
use Resources\APPLICATION;

class StringBuilder{
    private $data=[];
    function clear(){
        $this->data =[];
    }
    function Insert($obj){
        $this->data[] = $obj;
    }
    function toString($charUnionType=null){
        $returnType =APPLICATION::$APP_RETURN_SPACE;
        if(!is_null($charUnionType)){
            $returnType =$charUnionType;
        }
        if(count($this->data) > 0){
            return PhpModuleAdapter::Implode($this->data,$returnType);
        } 
        return APPLICATION::$APP_RETURN_EMPTY;
    }
}