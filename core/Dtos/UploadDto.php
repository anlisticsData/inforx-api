<?php

namespace Dtos;


class UploadDto{
    private $inputs=["id","uuid","description","path","ext","created_at","deleted_at"];
    public  $id;
    public  $uuid;
    public  $description;
    public  $path;
    public  $ext;
    public  $file;
    public  $created_at;
    public  $deleted_at;
    public function __construct($requesInputs=null){
        foreach($this->inputs as $key => $input){
            if(isset($requesInputs[$input])){
                $this->$input =$requesInputs[$input];
            }
        }
    }
}
