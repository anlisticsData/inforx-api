<?php


/*
    #ClockSystem

    Author:edilson Claudino da Silva
    Email:edilsonclaudinosulva@gmail.com
    Criado Em: 07/04/2024  
    Versão : v.1
    Definições : TimeZone 
    Funcao : TimeZone  - Null ou TimeZone 'America/Sao_Paulo'
 */


 


namespace AnalisticsData;




use DateTime;
use Exception;

class ClockSystem{
    private function __construct(){}
    private function __clone(){}

  

    public static function TimeZone($timeZone=null){
      try{
        if(is_null($timeZone)){
            date_default_timezone_set('America/Sao_Paulo');
        }else{
            date_default_timezone_set($timeZone);
        }
      }catch(Exception $e){
        throw new Exception($e->getMessage());
      }
        
    }

    public static function StringToDate($data,$formatOut="Y-m-d"){
        try{
            return date($formatOut,strtotime($data));
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }

    }


    public static function StringToDateTime($dataTime,$formatOut="Y-m-d H:i:s"){
        try{
            $data = DateTime::createFromFormat($formatOut, $dataTime);
            return  $data->format($formatOut);
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    
    }

    public static function diff($datatimeOne,$dataTimeTwo,$formatOut="Y-m-d H:i:s"){
        try{
            $datatime1 = new DateTime($datatimeOne);
            $datatime2 = new DateTime($dataTimeTwo);
            return $datatime1->diff($datatime2);
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    
    }

    public static function NowTime($timeFormatOut="H:i:s"){
        try{
            return date($timeFormatOut); 
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    
    }

    public static function NowDate($timeFormatOut="Y-m-d"){
        try{
             return date($timeFormatOut); 
        }catch(Exception $e){
                throw new Exception($e->getMessage());
        }
        
    }
    public static function NowDateTimeToStrToTimer($timeFormatOut="Y-m-d H:i:s"){
        try{
            return strtotime(date($timeFormatOut));
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    
    }

    
}