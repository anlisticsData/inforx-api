<?php

namespace Commons;
class ContentValues{
    private $data=[];
    function clear(){
        $this->data =[];
    }
    function put($str,$obj){
        $this->data[$str] = $obj;
    }
    function get($str){
        if(isset($this->data[$str])){
            return $this->data[$str];
        }
        return null;
    }
}