<?php 


namespace Adapters;

use PDO;
use Exception;
use PDOException;
use Commons\Uteis;
use Dtos\ModuleDto;
use Resources\Strings;
use Resources\HttpStatus;
use Interfaces\IConnections;

class PdoMysqlConectedModuleAdapter implements IConnections{
    private $pdo=null;
    private $servername;
    private $username ;
    private $password ;
    private $port;
    private $database;

    function __construct(ModuleDto $module)
    {
         
        $this->servername =  $module->ip_server;
        $this->username =    $module->user_server;
        $this->password =    $module->passw_db;
        $this->port =        $module->port_server;
        $this->database =    $module->user_db;
        try {
            $this->pdo = new PDO("mysql:host=$this->servername;dbname=".$this->database.";port=".$this->port.";charset=utf8", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

          } catch(PDOException $e) {
            throw new Exception(Strings::$STR_CONNECTION_FAILED, HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
          }
          
    }

    function query($sql,$parameters=null,$returnList=true){

        
        if($returnList){
            if(is_null($parameters)){
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                return  $stmt->fetchAll(PDO::FETCH_ASSOC);
            }else{
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($parameters);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }else{
            if(is_null($parameters)){
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }else{
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($parameters);
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }

    }


    function queryArray($sql,$parameters=null,$returnList=true){
        

     

        if($returnList){
            if(is_null($parameters)){
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                return  $stmt->fetchAll(PDO::FETCH_ASSOC);
            }else{
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($parameters);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }else{
            if(is_null($parameters)){
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }else{
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($parameters);
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }

    }




    function execute($sql,$parameters=null){
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($parameters);
    }


    
    function executeRowsCount($sql,$parameters=null){
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($parameters);
        $rowsAffected = $stmt->rowCount();
        return $rowsAffected;

    }
    
    function close(){
        $this->pdo=null;
    }

    function executeAutoIncrement($sql,$parameters=null){
        $stmt = $this->pdo->prepare($sql);
        if($stmt->execute($parameters)){
           return $this->pdo->lastInsertId();
        }
        return -1;

    }
}