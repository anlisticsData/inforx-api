<?php


namespace Requests;

use Exception;
use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;

//search=name,cnpj&value=A
class AllRequest{

    private $inputs=["search","value","pager","order","limit"];
    public  $search;
    public  $value;
    public  $pager;
    public  $order;
    public  $limit;
    
    public function __construct($requesInputs=null){
        
        foreach($this->inputs as $key => $input){
            if(isset($requesInputs[$input])){
                $this->$input =$requesInputs[$input];
            }
        }
        
        if(is_null($this->order) || (!in_array($this->order,["asc","desc"]))){
            $this->order="asc";
        }
        $this->pager = (is_null($this->pager)) ? 1 : $this->pager;
        $this->limit = (is_null($this->limit)) ? 15 : $this->limit;
        if(!Uteis::isNullOrEmpty($this->value) && Uteis::isNullOrEmpty($this->search)){
            throw new Exception(Strings::$APP_ALL_RECORDS_INVALID,HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }

        $filtered = array_filter( explode(",",$this->search), function($item) {
            return !empty($item); // Retorna verdadeiro para itens não vazios
        });
        $this->search = implode(",",array_values($filtered));
    }


    
    function toArray()  
    {
        $rows = [];
        foreach ($this->inputs as $key => $value) {
            $rows[$value] = $this->$value;
        }
        return $rows;
    }


}