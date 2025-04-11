<?php

namespace Repositories\Movements;

use Exception;
use Commons\Clock;
use Models\IsOpenCar;
use Models\Movements;
use Commons\TypeDateTime;
use Resources\HttpStatus;
use Commons\StringBuilder;
use Resources\APPLICATION;
use Interfaces\IConnections;
use Middleware\Authorization;
use Commons\DataBaseRepository;
use Commons\Uteis;
use Interfaces\Car\IMonthlyRepository;
use Interfaces\Movements\IMovements;
use Models\ParkingTransaction;
use Repositories\Car\MonthlyRepository;

class MovementsRepository implements IMovements
{
    private IConnections $repository;
    private IMonthlyRepository $monthlyRepository;
    private $userPayload;
    public function __construct($connection = null)
    {
        $this->repository =  new DataBaseRepository();
        $this->userPayload = Authorization::playload();
        $this->monthlyRepository = new MonthlyRepository();
    }


    function movementsDay($year, $month, $day)
    {
        $listMovements = [];
        try {
            $sql = new StringBuilder();
            $sql->Insert("select * from movements where branches_id=? and year(created_at)=? and month(created_at)=? and day(created_at)=?");
            $sql->Insert("  and park_date_departure is null and deleted_at  is null");
            $resultData = $this->repository->query($sql->toString(), [$this->userPayload['branch'], $year, $month, $day]);
            foreach ($resultData as $index => $row) {
                $listMovements[] = new Movements($row);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $listMovements;
    }



    function closeOfDay($year, $month, $day)
    {
        $listCloseDay = [];
        try {
            $sql = new StringBuilder();
            $sql->Insert("select p.id,p.created_at,pt.id as 'id_type',m.park_vehicle_plate as 'plate',pt.payment_methods_name as 'name_type',");
            $sql->Insert("p.hours,p.minutes, p.receipt_by_box,p.payment_change,p.discount_applied,m.park_entry_date,m.park_date_departure,p.fk_agreement_id");
            $sql->Insert(",p.fk_branch_id from  payments p ");
            $sql->Insert("left join movements m on m.park_id = p.fk_movements_id ");
            $sql->Insert("left join payment_types pt on pt.id =  p.fk_payment_types");
            $sql->Insert("where  p.fk_branch_id=? and   year(p.created_at)=? and  month(p.created_at)=? and day(p.created_at)=?");
            $sql->Insert("and p.deleted_at is null");
            $data = [
                $this->userPayload['branch'],
                $year,
                $month,
                $day
            ];
            $resultData = $this->repository->query($sql->toString(), $data);

            foreach ($resultData as $index => $row) {
                $listCloseDay[] = new ParkingTransaction($row);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $listCloseDay;
    }







    function isprint($typePrint, $branch)
    {
        $toprint = [];
        try {
            $sql = new StringBuilder();

            $sql->Insert("select * from movements m where ( is_print=0 or is_print is null) and type_print=? and branches_id=?");
            $sql->Insert(" and (year(m.created_at)=? and month(m.created_at)=? and day(m.created_at)=?)");
            $sql->Insert(" and deleted_at  is null and park_date_departure is null");
            $data = [
                $typePrint,
                $branch,
                date("Y"),
                date("m"),
                date("d")
            ];
            $resultData = $this->repository->query($sql->toString(), $data);
            foreach ($resultData as $index => $row) {
                $toprint[] = new Movements($row);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $toprint;
    }

    function printReset($uuid)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("UPDATE movements  set is_print=null , type_print=2 where uuid_id_plate_direction_create=?  ");
            $isCount = $this->repository->executeRowsCount($sql->toString(), [$uuid]);
            return ($isCount > 0) ?  true : false;
        } catch (Exception $e) {

            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }
    function printExecuted($uuid)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("UPDATE movements  set is_print=1 where park_id=?  ");
            $isCount = $this->repository->executeRowsCount($sql->toString(), [$uuid]);
            return ($isCount > 0) ?  true : false;
        } catch (Exception $e) {

            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }


    function cancellation($uuid)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("UPDATE movements  set deleted_at=now() where uuid_id_plate_direction_create=? and deleted_at  is null");
            $data = [$uuid];
            $isCount = $this->repository->executeRowsCount($sql->toString(), $data);
            return ($isCount > 0) ?  true : false;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }

    function isOpenCarPlateOrCode(IsOpenCar $isOpenCar)
    {
        $monthyPlates = [];
        try {
            $sql = new StringBuilder();
            $sql->Insert("select * from movements m where  m.park_date_departure is null and");
            $sql->Insert("m.branches_id=? and (m.park_vehicle_plate=? or m.uuid_ref=?) and deleted_at  is null");
            $resultData = $this->repository->query($sql->toString(), [$isOpenCar->branche, $isOpenCar->plateOrCode, $isOpenCar->plateOrCode]);
            foreach ($resultData as $index => $row) {
                $monthyPlates[] = new Movements($row);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $monthyPlates;
    }

    function  checkIfThePlateHasAlreadyLeftTheParkingLot($plate, TypeDateTime $typeDateTime, $isCount = false)
    {
        $monthyPlates = [];
        try {
            $sql = new StringBuilder();

            $sql->Insert("select *   from  movements where  park_vehicle_plate = ?   and branches_id=?");
            $sql->Insert(" and  park_date_departure is not null and ");
            $sql->Insert("(year(created_at)=? and month(created_at)=? and day(created_at)=?) and deleted_at  is null");
            $resultData = $this->repository->query($sql->toString(), [
                $plate,
                $this->userPayload['branch'],
                $typeDateTime->year,
                $typeDateTime->month,
                $typeDateTime->day
            ]);
            foreach ($resultData as $index => $row) {
                $monthyPlates[] = new Movements($row);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return (!$isCount) ? $monthyPlates : count($monthyPlates);
    }


    #001
    function checkIfThePlateIsEnteredInTheParkingLotUsingTheCompositeKey($compositeKey, TypeDateTime $typeDateTime, $isCount = false)
    {
        $monthyPlates = [];
        try {
            $sql = new StringBuilder();
            $sql->Insert("select *   from  movements where  uuid_id_plate_direction_create = ?   and branches_id=?");
            $sql->Insert(" and (year(created_at)=? and month(created_at)=? and day(created_at)=?)");
            $resultData = $this->repository->query($sql->toString(), [
                $compositeKey,
                $this->userPayload['branch'],
                $typeDateTime->year,
                $typeDateTime->month,
                $typeDateTime->day
            ]);
            foreach ($resultData as $index => $row) {
                $monthyPlates[] = new Movements($row);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }

        if ($isCount) {
            return count($monthyPlates);
        }
        return  $monthyPlates;
    }


    function checkIfThePlateHasEnteredTheParkingLot($plate, TypeDateTime $typeDateTime, $isCount = false)
    {
        $monthyPlates = [];
        try {
            $sql = new StringBuilder();
            $sql->Insert("select *   from  movements where  park_vehicle_plate = ?   and branches_id=?");
            $sql->Insert(" and  park_date_departure is null and ");
            $sql->Insert("(year(created_at)=? and month(created_at)=? and day(created_at)=?) and deleted_at  is null");
            $resultData = $this->repository->query($sql->toString(), [
                $plate,
                $this->userPayload['branch'],
                $typeDateTime->year,
                $typeDateTime->month,
                $typeDateTime->day
            ]);
            foreach ($resultData as $index => $row) {
                $monthyPlates[] = new Movements($row);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return (!$isCount) ? $monthyPlates : count($monthyPlates);
    }


    function byPlateDay($plate, $year, $month, $day, $branch = null)
    {
        $monthyPlates = [];
        try {
            if (!is_null($branch)) {
                $this->userPayload['branch'] = $branch;
            }
            $sql = new StringBuilder();
            $sql->Insert("SELECT * FROM movements where park_vehicle_plate=?  and branches_id=? ");
            $sql->Insert(" and year(created_at)=? and month(created_at)=? and day(created_at)=? ");
            $sql->Insert(" and park_date_departure is null");
            $resultData = $this->repository->query($sql->toString(), [$plate, $this->userPayload['branch'], $year, $month, $day]);
            foreach ($resultData as $index => $row) {
                $monthyPlates[] = new Movements($row);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $monthyPlates;
    }




    function byPlate($plate)
    {



        $monthyPlates = [];
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT * FROM movements where park_vehicle_plate=?  and branches_id=?  and park_date_departure is null and deleted_at  is null");
            $resultData = $this->repository->query($sql->toString(), [$plate, $this->userPayload['branch']]);
            foreach ($resultData as $index => $row) {
                $monthyPlates[] = new Movements($row);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $monthyPlates;
    }

    function redeemOpenPlatesOfTheDay($uuid = null)
    {

        try {
            $sql = new StringBuilder();
            $query = '';
            if (is_null($uuid)) {
                $sql->Insert("SELECT * FROM `movements` where deleted_at is null and   %s    ORDER BY `park_entry_date` ASc ");
                $day = date("d");
                $month = date("m");
                $year = date("Y");
                $directions = explode(",", APPLICATION::$APP_ENTRANCE_DIRECTION);
                foreach ($directions as $value) {
                    $transportation[] = sprintf(" portatirasensor=%s ", $value);
                }
                $query = sprintf(
                    " day(park_entry_date)=%s and month(park_entry_date)=%s and year(park_entry_date)=%s and park_date_departure is null  and branches_id=%s",
                    $day,
                    $month,
                    $year,
                    $this->userPayload['branch']
                );
                $resultData = $this->repository->query(sprintf($sql->toString(), $query));
            } else {
                $sql->Insert("SELECT * FROM `movements` where  uuid_id_plate_direction_create=?  and deleted_at  is null  ORDER BY `park_entry_date` ASc ");
                $resultData = $this->repository->query(sprintf($sql->toString(), $query), [$uuid]);
            }

            if (!is_null($resultData) && count($resultData) > 0) {
                $movements = [];
                foreach ($resultData as $key => $row) {
                    $movements[] = new Movements($row);
                }

                return $movements;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }



    function byUuid($uuid)
    {

        $monthyPlate = null;
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT * FROM movements where uuid_id_plate_direction_create=?  and branches_id=?  and park_date_departure is null and deleted_at  is null");
            $resultData = $this->repository->query($sql->toString(), [$uuid, $this->userPayload['branch']]);
            if (isset($resultData[0])) {
                $monthyPlate = new Movements($resultData[0]);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $monthyPlate;
    }

    function lastPrimaryKey()
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT max(movements.park_id) as lastId  FROM movements where park_date_departure is null  and branches_id=? and deleted_at  is null");
            $resultData = $this->repository->query($sql->toString(), [$this->userPayload['branch']], false);
            return $resultData['lastId'];
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return 0;
    }



    function IsVacancyIOccupied($vacancy)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT count(*) as records  FROM movements where prisma=?  and park_date_departure is null and branches_id=? and deleted_at  is null");
            $resultData = $this->repository->query($sql->toString(), [$vacancy, $this->userPayload['branch']], false);
            return $resultData['records'];
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return 0;
    }


    function records()
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT count(*) as records  FROM movements; and park_date_departure is null and and branches_id=? and deleted_at  is null");
            $resultData = $this->repository->query($sql->toString(), [$this->userPayload['branch']], false);
            return $resultData['records'];
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return 0;
    }

    function all() {}
    function created(Movements $movements)
    {

        try {


            $signIsInTheYard = false;
            $exitDiff = null;
            $data = [
                "year" => date("Y"),
                "month" => date("m"),
                "day" => date("d")

            ];
            #001
            $countEnteredMoviment = $this->checkIfThePlateIsEnteredInTheParkingLotUsingTheCompositeKey($movements->uuid_id_plate_direction_create, new TypeDateTime($data));
            if (count($countEnteredMoviment) == 0) {
                if (count($countEnteredMoviment) != 0) {
                    $signIsInTheYard = true;
                }



                $exitMoviment = $this->checkIfThePlateIsEnteredInTheParkingLotUsingTheCompositeKey($movements->uuid_id_plate_direction_create, new TypeDateTime($data));
                if (count($exitMoviment) != 0) {
                    $exitDiff = Clock::calculateDateDifference($exitMoviment[0]->park_date_departure, date("Y-m-d H:m:s"));
                }
                if (!is_null($exitDiff) && $exitDiff["minutes"] < APPLICATION::$APP_TIME_FOR_THE_NEXT_READING_OF_THE_SAME_PLATE_MINUTES) {
                    $signIsInTheYard = true;
                }


                if ($signIsInTheYard) {
                    return null;
                }


                $monthlyPlayer = $this->monthlyRepository->byPlate($movements->park_vehicle_plate, $this->userPayload['branch']);
                if (count($monthlyPlayer) > 0) {
                    $movements->car_monthly_id = $monthlyPlayer[0]->monthlyId;
                }

                $sql = new StringBuilder();
                $sql->Insert("INSERT INTO movements(park_vehicle_plate,park_entry_date,obs_entry,");
                $sql->Insert("double_vacancy,car_monthly_id,user_entry,branches_id,uuid_ref,uuid_id_plate_direction_create,created_at,");
                $sql->Insert("fk_color_id,fk_cartype_id,prisma,fk_type_of_vehicle,type_print)");
                $sql->Insert(" VALUES(?,?,?,?,?,?,?,?,?,now(),?,?,?,?,?)");
                $data = [
                    $movements->park_vehicle_plate,
                    $movements->park_entry_date,
                    $movements->obs_entry,
                    $movements->double_vacancy,
                    $movements->car_monthly_id,
                    $movements->user_entry,
                    $movements->branches_id,
                    $movements->uuid_ref,
                    $movements->uuid_id_plate_direction_create,
                    $movements->fk_color_id,
                    $movements->fk_cartype_id,
                    $movements->prisma,
                    $movements->fk_type_of_vehicle,
                    $movements->type_print
                ];
                $resultData = $this->repository->executeAutoIncrement($sql->toString(), $data);
                return $resultData;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }
    function delete($modelId) {}
}
