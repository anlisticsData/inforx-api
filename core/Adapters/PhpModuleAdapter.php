<?php
namespace Adapters;

use Exception;
class PhpModuleAdapter{
    private function __construct(){}
    private function __clone(){}


    public static function Implode($data,$charUnionType=null){
        $list=null;

        if(strpos(phpversion(),"7.")){
            $list = implode($data,$charUnionType);
        }else{
            $list = implode($charUnionType,$data);
        }
        return $list;
    }

    public static function Utf8Encode($value){
        try{
            return utf8_encode($value);
        }catch(Exception $e){
            return mb_convert_encoding($value, 'UTF-8');
        }
    }

    public static function Utf8Dencode($value){
        try{
            return utf8_decode($value);
        }catch(Exception $e){
            return mb_convert_encoding($value, 'ISO-8859-1', 'UTF-8');
        }
    }


    





}