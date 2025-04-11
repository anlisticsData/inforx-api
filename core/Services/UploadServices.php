<?php

namespace Services;

use Repositories\UploadRepository\UploadRepository;
use Dtos\UploadDto;
use Resources\HttpStatus;
use Exception;
use Interfaces\IServices;

class UploadServices implements IServices{
    private $repository;
    function __construct()
    {
        $this->repository = new UploadRepository();
    }

    public function save(UploadDto $file){
        try{
            return $this->repository->created($file);
        }catch(Exception $e){
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
        
    }
 
   

   
}