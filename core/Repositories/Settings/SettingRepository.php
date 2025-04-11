<?php

namespace Repositories\Settings;

use Exception;
use Models\Setting;
use Resources\HttpStatus;
use Commons\StringBuilder;
use Interfaces\IConnections;
use Commons\DataBaseRepository;
use Commons\Uteis;
use Interfaces\Settings\ISettingRepository;
 

class SettingRepository implements ISettingRepository
{
   private IConnections $repository;
   public function __construct()
   {
      $this->repository =  new DataBaseRepository();

      
   }

   function update(Setting $setting){
      try{
         $sql = new StringBuilder();
         $sql->Insert("UPDATE  settings  set updated_at=now(),content=? where id=?");
         $resultData = $this->repository->execute($sql->toString(),[$setting->content,$setting->id]);
         if($resultData){
             return true;
         }
     }catch(Exception $e){
         throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
     }
     return false;
   }

   function created(Setting $setting){
      try{
         $sql = new StringBuilder();
         $sql->Insert("INSERT INTO settings(`type`,content) ");
         $sql->Insert(" VALUES(?,?)");
         $data=[$setting->type,$setting->content];
         $resultData = $this->repository->executeAutoIncrement($sql->toString(),$data);
         return $resultData;
     }catch(Exception $e){

         throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
     }
     return null;
   }

   function records(Setting $setting) {
      try{
         $sql = new StringBuilder();
         $sql->Insert("SELECT count(*) as records  FROM settings;");
         $resultData =$this->repository->query($sql->toString(),null,false);
         return $resultData['records']; 
       
     }catch(Exception $e){
         throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
     }
     return 0;
   }
   function all() {}
   function oneSettingType($type)
   {
      try {
         $sql = new StringBuilder();
         $sql->Insert("SELECT id,`type`,content FROM settings where type=?");
         $parameters = [$type];
         $resultData =new Setting($this->repository->query($sql->toString(), $parameters, false));
         if(isset($resultData->type) && strlen($resultData->type) > 0)
         {
            return $resultData ;
         }         
      } catch (Exception $e) {
         throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
      } 
      return null;
   }
}
