<?php

namespace Requests;

use Exception;
use Commons\Uteis;
use Resources\Strings;
use Commons\HttpRequests;
use Resources\HttpStatus;
use Interfaces\IRequestValidate;


class PaginatorRequestBrancheServicesAll implements IRequestValidate{
    private $inputs=["pager","branche"];
    public $branche;
    public  $pager=1;
    public function __construct($requesInputs=null){
        if(is_null($requesInputs)){
            $requesInputs = HttpRequests::Requests();
        }

      
    }
    function isValid(){
        if(Uteis::isNullOrEmpty($this->branche)){
            throw new Exception(str_replace("[:input]","branche",Strings::$STR_BRANCHES_COSTUMER_INVALID),HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
    }
}