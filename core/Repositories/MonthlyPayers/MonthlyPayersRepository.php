<?php

namespace Repositories\MonthlyPayers;

use DateTime;
use Exception;
use DateTimeZone;
use Commons\Uteis;
use Models\MonthlyPayer;
use Commons\ResponseJson;
use Dtos\MonthlyPayerDto;
use Resources\HttpStatus;
use Commons\StringBuilder;
use Resources\APPLICATION;
use Interfaces\IConnections;
use Middleware\Authorization;
use Commons\DataBaseRepository;
use Models\MonthlyCartypeAssociate;
use Interfaces\MonthlyPayers\IMonthlyPayersRepository;
use Models\CarAssociation;

class MonthlyPayersRepository implements IMonthlyPayersRepository
{

    private IConnections $repository;
    private $userPayload;
    public function __construct()
    {
        $this->repository =  new DataBaseRepository();
    }


    function disableMonthlySubscription($monthlyCode)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("update monthly_payers set monthly_date_change=now() , active_monthly=0 where monthly_id=?");
            $resultData = $this->repository->execute($sql->toString(), [$monthlyCode]);
            return $resultData;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }
    function activateMonthlySubscription($monthlyCode)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("update monthly_payers set monthly_date_change=now() , active_monthly=1 where monthly_id=?");
            $resultData = $this->repository->execute($sql->toString(), [$monthlyCode]);
            return $resultData;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }
    function monthlyBlock($monthlyCode)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("update monthly_payers set monthly_date_change=now() , active_monthly=2 where monthly_id=?");
            $resultData = $this->repository->execute($sql->toString(), [$monthlyCode]);
            return $resultData;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }


    function removeCarAssociate($monthlyCode)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("update monthly_cars set deleted_at=now() where id=?");
            $resultData = $this->repository->executeAutoIncrement($sql->toString(), [$monthlyCode]);
            return $resultData;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }

    function findAllCarsBy($monthlyCode)
    {
        $resultData = [];
        try {
            $sql = new StringBuilder();
            $sql->Insert("select mc.id,umc.cancel_at, mc.plate, tc.model,tc.brand,cl.description,mc.created_at from monthly_cars mc join types_of_car tc");
            $sql->Insert("on mc.types_of_cars_id=tc.id left join colors cl ");
            $sql->Insert("on mc.color=cl.id join user_monthy_cars umc ");
            $sql->Insert("on umc.fk_car=mc.id where umc.fk_monthy_players_id=? and mc.deleted_at is  null ");

            $data = $this->repository->query($sql->toString(), array($monthlyCode));
            if (count($data) > 0) {
                foreach ($data as $key => $row) {
                    $resultData[] = new CarAssociation($row);
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $resultData;
    }

    function findOne($primary)
    {
        $resultData = null;
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT *  FROM monthly_payers where `monthly_id`=?  and `active_monthly`=?");
            $data = $this->repository->query($sql->toString(), array($primary, APPLICATION::$APP_STATUS_NEW));
            if (count($data) > 0) {
                foreach ($data as $key => $row) {
                    $resultData = new MonthlyPayer([
                        "name" => $row['monthly_name'],
                        "id" => $row['monthly_id'],
                        "created_at" => $row['monthly_date_registration'],
                        "dt_nasc" => $row['monthly_date_birth'],
                        "cpf" => $row['monthly_cpf'],
                        "rg" => $row['monthly_rg'],
                        "email" => $row['monthly_email'],
                        "phone" => $row['monthly_fixed_phone'],
                        "phone_movel" => $row['monthly_telefone_mobile'],
                        "cep" => $row['monthly_cep'],
                        "address" => $row['monthly_address'],
                        "complement" => $row['monthly_complement'],
                        "number" => $row['number'],
                        "city" => $row['monthly_city'],
                        "monthly_status" => $row['monthly_status'],
                        "day_payment" => $row['monthly_day_expiry_date'],
                        "obs" => $row['monthly_observation'],
                        "update" => $row['monthly_date_change'],
                        "used_vacancies" => $row['quantity_of_vacancies'],
                        "active_monthly" => $row['active_monthly'],
                        "userId" => $row['fk_user_id'],
                        "fk_branch" => $row['branch_id'],
                        "fk_curtomers" => $row['customer_id']
                    ]);
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $resultData;
    }

    function associateCarWithMonthlyMember(MonthlyCartypeAssociate $monthlyCartypeAssociate)
    {

        try {
            $monthlyCartypeAssociate->generateUuid();
            $sql = new StringBuilder();
            $sql->Insert("INSERT INTO user_monthy_cars(uuid,fk_monthy_players_id,fk_car,created_at) ");
            $sql->Insert(" VALUES(?,?,?,now())");
            $data = array($monthlyCartypeAssociate->uuid, $monthlyCartypeAssociate->fk_monthy_players_id, $monthlyCartypeAssociate->fk_car);
            $resultData = $this->repository->execute($sql->toString(), $data);
            return $resultData;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }
    function findAll($fkCustomer, $fkBranch)
    {
        $resultData = [];
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT *  FROM monthly_payers where `branch_id`=? and `customer_id`=? and `active_monthly`=?");
            $data = $this->repository->query($sql->toString(), array($fkBranch, $fkCustomer, APPLICATION::$APP_STATUS_NEW));
            if (count($data) > 0) {
                foreach ($data as $key => $row) {
                    $resultData[] = new MonthlyPayer([
                        "name" => $row['monthly_name'],
                        "id" => $row['monthly_id'],
                        "created_at" => $row['monthly_date_registration'],
                        "dt_nasc" => $row['monthly_date_birth'],
                        "cpf" => $row['monthly_cpf'],
                        "rg" => $row['monthly_rg'],
                        "email" => $row['monthly_email'],
                        "phone" => $row['monthly_fixed_phone'],
                        "phone_movel" => $row['monthly_telefone_mobile'],
                        "cep" => $row['monthly_cep'],
                        "address" => $row['monthly_address'],
                        "complement" => $row['monthly_complement'],
                        "number" => $row['number'],
                        "city" => $row['monthly_city'],
                        "monthly_status" => $row['monthly_status'],
                        "day_payment" => $row['monthly_day_expiry_date'],
                        "obs" => $row['monthly_observation'],
                        "update" => $row['monthly_date_change'],
                        "used_vacancies" => $row['quantity_of_vacancies'],
                        "active_monthly" => $row['active_monthly'],
                        "userId" => $row['fk_user_id'],
                        "fk_branch" => $row['branch_id'],
                        "fk_curtomers" => $row['customer_id'],
                        "type_of_monthly_fee" => $row['type_of_monthly_fee'],
                        "monthly_fee_model" => $row['monthly_fee_model'],
                        "monthly_fee_amount" => $row['monthly_fee_amount'],
                    ]);
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $resultData;
    }




    function findAllCustomerAndBranche($fkCustomer, $fkBranch)
    {
        $resultData = [];
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT *  FROM monthly_payers where `branch_id`=? and `customer_id`=? and `active_monthly`=? ");
            $data = $this->repository->query($sql->toString(), array($fkBranch, $fkCustomer, APPLICATION::$APP_STATUS_NEW));

            if (count($data) > 0) {
                foreach ($data as $key => $row) {
                    $resultData[] = new MonthlyPayer([
                        "name" => $row['monthly_name'],
                        "id" => $row['monthly_id'],
                        "created_at" => $row['monthly_date_registration'],
                        "dt_nasc" => $row['monthly_date_birth'],
                        "cpf" => $row['monthly_cpf'],
                        "rg" => $row['monthly_rg'],
                        "email" => $row['monthly_email'],
                        "phone" => $row['monthly_fixed_phone'],
                        "phone_movel" => $row['monthly_telefone_mobile'],
                        "cep" => $row['monthly_cep'],
                        "address" => $row['monthly_address'],
                        "complement" => $row['monthly_complement'],
                        "number" => $row['monthly_neighborhood'],
                        "city" => $row['monthly_city'],
                        "monthly_status" => $row['monthly_status'],
                        "day_payment" => $row['monthly_day_expiry_date'],
                        "obs" => $row['monthly_observation'],
                        "update" => $row['monthly_date_change'],
                        "used_vacancies" => $row['quantity_of_vacancies'],
                        "active_monthly" => $row['active_monthly'],
                        "userId" => $row['fk_user_id'],
                        "fk_branch" => $row['branch_id'],
                        "fk_curtomers" => $row['customer_id']
                    ]);
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $resultData;
    }

    function findOneEmailOrCpfOrRg(MonthlyPayerDto $monthlyDto)
    {
    
        $resultData = [];
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT *  FROM monthly_payers where   `monthly_email`=?");
            $data = $this->repository->query($sql->toString(), array(  $monthlyDto->email));
            if (count($data) > 0) {
                foreach ($data as $key => $row) {
                    $resultData[] = new MonthlyPayer([
                        "name" => $row['monthly_name']
                    ]);
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }

       
        return $resultData;
    }

    function update(MonthlyPayer $monthlyPayer)
    {
        try {

            $user_id = $monthlyPayer->userId;
            $date = new DateTime($monthlyPayer->dt_nasc);
            $date->setTimezone(new DateTimeZone('UTC'));
            $monthlyPayer->dt_nasc = $date->format('Y-m-d H:i:s');
            $sql = new StringBuilder();
            $sql->Insert("update monthly_payers set monthly_name=?,monthly_date_birth=?,");
            $sql->Insert("monthly_fixed_phone=?, monthly_telefone_mobile=?, ");
            $sql->Insert("monthly_cep=?, monthly_address=?, monthly_complement=?,");
            $sql->Insert("monthly_city=?,monthly_day_expiry_date=?,monthly_observation=?,");
            $sql->Insert("quantity_of_vacancies=?,monthly_date_change=now(),number=? ");
            $sql->Insert(",type_of_monthly_fee=?,monthly_fee_model=?,monthly_fee_amount=? ");
            $sql->Insert(" where   monthly_id=?");


            $resultData = $this->repository->execute($sql->toString(), [
                $monthlyPayer->name,
                $monthlyPayer->dt_nasc,
                $monthlyPayer->phone,
                $monthlyPayer->phone_movel,
                $monthlyPayer->cep,
                $monthlyPayer->address,
                $monthlyPayer->complement,
                $monthlyPayer->city,
                $monthlyPayer->day_payment,
                $monthlyPayer->obs,
                $monthlyPayer->used_vacancies,
                $monthlyPayer->number,
                $monthlyPayer->type_of_monthly_fee,
                $monthlyPayer->monthly_fee_model,
                $monthlyPayer->monthly_fee_amount,
                $monthlyPayer->id
            ]);
            return $resultData;
        } catch (Exception $e) {
            Uteis::dd($e);
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }


    function created(MonthlyPayer $monthlyPayer)
    {
        try {


            $monthlyPayer->monthly_fee_amount=( $monthlyPayer->monthly_fee_amount==null)?0:$monthlyPayer->monthly_fee_amount;
            $user_id = $monthlyPayer->userId;
            $date = new DateTime($monthlyPayer->dt_nasc);
            $date->setTimezone(new DateTimeZone('UTC'));
            $monthlyPayer->dt_nasc = $date->format('Y-m-d H:i:s');
            $sql = new StringBuilder();
            $sql->Insert("INSERT INTO monthly_payers(monthly_name, monthly_date_birth, monthly_cpf,");
            $sql->Insert(" monthly_rg, monthly_email, monthly_fixed_phone, monthly_telefone_mobile, ");
            $sql->Insert(" monthly_cep, monthly_address, monthly_complement,");
            $sql->Insert(" monthly_city, monthly_status, monthly_day_expiry_date, monthly_observation,");
            $sql->Insert(" quantity_of_vacancies, active_monthly, fk_user_id,monthly_date_registration,customer_id,branch_id,`number`,type_of_monthly_fee,monthly_fee_model,monthly_fee_amount)");
            $sql->Insert(" VALUES('" . $monthlyPayer->name . "','" . $monthlyPayer->dt_nasc . "','" . $monthlyPayer->cpf . "'
                          ,'" . $monthlyPayer->rg . "','" . $monthlyPayer->email . "','" . $monthlyPayer->phone . "',
                          '" . $monthlyPayer->phone_movel . "','" . $monthlyPayer->cep . "','" . $monthlyPayer->address . "',
                          '" . $monthlyPayer->complement . "','" . $monthlyPayer->city . "',
                          'P','" . $monthlyPayer->day_payment . "','" . $monthlyPayer->obs . "',
                          '" . $monthlyPayer->used_vacancies . "','" . APPLICATION::$APP_STATUS_NEW . "','" . $user_id . "',now()
                          ," . $monthlyPayer->fk_curtomers . "," . $monthlyPayer->fk_branch . " ,'" . $monthlyPayer->number . "','" . $monthlyPayer->type_of_monthly_fee . "','" . $monthlyPayer->monthly_fee_model . "'," . $monthlyPayer->monthly_fee_amount . " )");




                        

            $resultData = $this->repository->executeAutoIncrement($sql->toString());
            return $resultData;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }
}
