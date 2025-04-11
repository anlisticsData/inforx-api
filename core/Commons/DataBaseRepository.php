<?php

namespace Commons;

use Adapters\MysqlAdapter;
 
use Interfaces\IConnections;
use Adapters\PdoMysqlAdapter;

class DataBaseRepository implements IConnections {
    private $database ;
    public function __construct()
    {
        /*
           instancia global dos repositorios.
           data:07-03-2024 : 15:47
           dev:EdilsonCsilva  edilsonclaudinosilva@gmail.com
        */
       $this->database = new PdoMysqlAdapter();
    }

    function query($sql,$parameters=null,$returnList=true){
        return $this->database->query($sql,$parameters,$returnList);
    }
    
    function queryArray($sql,$parameters=null,$returnList=true){
        return $this->database->queryArray($sql,$parameters,$returnList);
    }

    function execute($sql,$parameters=null){
        return $this->database->execute($sql,$parameters);
    }

    function executeRowsCount($sql,$parameters=null){
        return $this->database->executeRowsCount($sql,$parameters);
    }
    
    function close(){
        $this->database->close();
    }


    function executeAutoIncrement($sql,$parameters=null){
        return $this->database->executeAutoIncrement($sql,$parameters);
    }


 



}

