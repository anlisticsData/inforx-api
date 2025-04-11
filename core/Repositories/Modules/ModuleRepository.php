<?php 

namespace Repositories\Modules;
use Exception;
use Models\Plate;
use Commons\Uteis;
use Models\Module;
use Resources\HttpStatus;
use Commons\StringBuilder;
use Resources\APPLICATION;
use Interfaces\IConnections;
use Commons\DataBaseModuleRepository;
use Interfaces\Modules\IModuleRepository;

class ModuleRepository implements IModuleRepository{
    private IConnections $repository;

    public function __construct($connection=null)
    {
        $this->repository = (is_null($connection)) ? new DataBaseModuleRepository() : $connection;  
    }
    
    function findOne($moduleCode){
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT id, module, `description`,`state`, ip_server, user_server, port_server, user_db, passw_db");
            $sql->Insert("  FROM modules where module=? and state=1 ");
            $resultData = $this->repository->query($sql->toString(), array($moduleCode));
            if(!is_null($resultData) && count($resultData) > 0){
                $row=$resultData[0];
                return  new Module($row);
               
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }

        return null;
    }


    function hasNewPlates($lastPlate=0){
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT * FROM `movimentoscameras` where  %s   ORDER BY `codigo` ASc limit 0,10");
            $day=date("d");
            $month=date("m");
            $year=date("Y");
            $directions=explode(",",APPLICATION::$APP_ENTRANCE_DIRECTION);
            foreach($directions as $value){
                $transportation[]= sprintf(" portatirasensor=%s ",$value);
            }
            $query=sprintf(" (%s) and day(created_at)=%s and month(created_at)=%s and year(created_at)=%s and codigo > %s ",implode(" || ",$transportation),$day,$month,$year,$lastPlate);
            $resultData = $this->repository->query(sprintf($sql->toString(),$query));
            if(!is_null($resultData) && count($resultData) > 0){
                $plates=[];
                foreach($resultData as $key => $row){
                    $plates[]=new Plate($row);
                }
                return $plates;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
}


}