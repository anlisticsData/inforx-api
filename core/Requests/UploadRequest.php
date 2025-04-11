<?php

namespace Requests;

use Commons\HttpRequests;
use Exception;
use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Commons\ValidateInputs;
use Interfaces\IRequestValidate;

class UploadRequest implements IRequestValidate{
    private $inputs=["id","uuid","description","path","ext","created_at","deleted_at","user_code"];
   
   
    public  $id;
    public  $uuid;
    public  $description;
    public  $path;
    public  $ext;
    public  $file;
    public  $created_at;
    public  $deleted_at;
    public  $user_code;

    
    public function __construct($requesInputs=null){
        $requesInputs = HttpRequests::Requests();
        $this->file=HttpRequests::requestPOSTFILES();
        foreach($this->inputs as $key => $input){
            if(isset($requesInputs[$input])){
                $this->$input =$requesInputs[$input];
            }
        }
        $this->isValid();
    }

    function isValid()
    {

        if(!isset($this->file['file']['name']) &&  !isset($this->file['file']['tmp_name'])){
            throw new Exception(Strings::$STR_HTTP_NOT_FILE,HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
        $errors=[];
        if(Uteis::isNullOrEmpty($this->description)){
           $errors[]=str_replace("[:input]","description",Strings::$STR_INPUTS_MANDATORY);
        }
       
      
        if(count($errors)>0){
            throw new Exception(implode("[*]",$errors),HttpStatus::$HTTP_CODE_BAD_REQUEST);
        }
        
    }
}