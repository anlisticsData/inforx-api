<?php 


namespace Adapters;

use Commons\Uteis;
use PDO;
use Exception;
use PDOException;
use Interfaces\IConnection;
use Resources\HttpStatus;
use Resources\Strings;

class MysqlAdapter implements IConnection{
    private $pdo=null;
    private $servername;
    private $username ;
    private $password ;
    private $port;
    private $database;

    function __construct()
    {

        $this->servername =  $_SERVER["MYSQL_LOCALHOST"];
        $this->username =    $_SERVER["MYSQL_USER"];
        $this->password =    $_SERVER["MYSQL_USER_PASSWORD"];
        $this->port =        $_SERVER["MYSQL_PORT"];
        $this->database =    $_SERVER["MYSQL_DATABASE"];
        try {
            $this->pdo = new PDO("mysql:host=$this->servername;dbname=".$this->database.";port=".$this->port, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

          } catch(PDOException $e) {
            throw new Exception(Strings::$STR_CONNECTION_FAILED, HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
          }
          
    }
    function getInstance(){
        return $this->pdo;
    }
    function close(){
        $this->pdo =  null;
    }
}