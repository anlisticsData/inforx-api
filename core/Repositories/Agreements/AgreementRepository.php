<?php

namespace Repositories\Agreements;

use Exception;
use Dtos\AllDto;
use Commons\Uteis;
use Models\Agreement;
use Commons\Paginator;
use Resources\HttpStatus;
use Commons\StringBuilder;
use Interfaces\IConnections;
use Commons\DataBaseRepository;
use Interfaces\Agreements\IAgreementRepository;


class AgreementRepository implements IAgreementRepository
{
    private IConnections $repository;

    public function __construct()
    {
        $this->repository = new DataBaseRepository();
    }

    function records($where=null)
    {
        try {
            $sql = new StringBuilder();
            if(!is_null($where)){
                $sql->Insert("SELECT count(*) as records FROM `agreements` where deleted_at is null ".$where);
            }else{
                $sql->Insert("SELECT count(*) as records FROM `agreements` where deleted_at is null;");
            }
            $resultData = $this->repository->query($sql->toString(), null, false);
            return $resultData['records'];
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }

        return 0;
    }

    function activeAgreement($codeAgreement, $branchCode)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("UPDATE  agreements set `state`='A'  where id=? and  fk_branche_id=? ");
            $data = [
                $codeAgreement,
                $branchCode
            ];

            $resultData = $this->repository->execute($sql->toString(), $data);
            return $resultData;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }
    function desactiveAgreement($codeAgreement, $branchCode)
    {


        try {
            $sql = new StringBuilder();
            $sql->Insert("UPDATE  agreements set `state`='D'  where id=? and  fk_branche_id=? ");
            $data = [
                $codeAgreement,
                $branchCode
            ];

            $resultData = $this->repository->execute($sql->toString(), $data);
            return $resultData;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }
    function delete($codeAgreement, $branchCode)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("UPDATE  agreements set `deleted_at`=now()  where id=? and  fk_branche_id=? ");
            $data = [
                $codeAgreement,
                $branchCode
            ];
            $resultData = $this->repository->execute($sql->toString(), $data);
            return $resultData;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }



    function byAgreement($codeAgreement, $codeBranch)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("select * from agreements where deleted_at is null and fk_branche_id=? and id=?");
            $resultData = $this->repository->query($sql->toString(), array($codeBranch, intval($codeAgreement)));
            return (count($resultData)) ?  new Agreement($resultData[0]) : null;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }


    function nameOfTheAgreementExists($name, $branchCode)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("select * from agreements where deleted_at is null and name=? and fk_branche_id=?");
            $resultData = $this->repository->query($sql->toString(), array($name, intval($branchCode)));
            return (count($resultData)) ?  new Agreement($resultData[0]) : null;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }


    function allAgreementsPaginator($codeBranch, AllDto $allDto)
    {
        
        try {
            $agreements = [];
            $where=null;
            $limit="";
            if(!is_null($allDto->value)){
                Uteis::dd($allDto);
            }
            $total =  $this->records($where);
            $paginator =  new Paginator($allDto->pager);
            $paginator->setLimit($allDto->limit);
            $paginator->sizeRecords($total);
            $limit=sprintf(" limit %s,%s",$paginator->start,$paginator->limit);
            $sql = new StringBuilder();
            $sql->Insert("select *,price as prices ,cnpj as doc from agreements where deleted_at is null and fk_branche_id=? ".$limit);
            $resultData = $this->repository->query($sql->toString(), array($codeBranch));
            foreach ($resultData as $key => $row) {
                array_push($agreements, new Agreement($row));
            }
            return [
                "data"=> $agreements,
                "pagination"=>$paginator->paginator()
            ];
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }

    function allAgreements($codeBranch)
    {

        try {
            $agreements = [];
            $sql = new StringBuilder();
            $sql->Insert("select * from agreements where deleted_at is null and fk_branche_id=?");
            $resultData = $this->repository->query($sql->toString(), array($codeBranch));
           
            foreach ($resultData as $key => $row) {
                array_push($agreements, new Agreement($row));
            }
            return $agreements;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }

    function create(Agreement $agreement)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("INSERT INTO agreements(`name`,`cnpj`,`address`,obs,`start`,`end`,price,fk_branche_id,`created_at`,`state`) ");
            $sql->Insert(" VALUES(?,?,?,?,?,?,?,?,now(),'A')");
            $data = [
                $agreement->name,
                $agreement->doc,
                $agreement->address,
                '',
                $agreement->start,
                $agreement->end,
                $agreement->prices,
                $agreement->fk_branche_id

            ];
            $resultData = $this->repository->executeAutoIncrement($sql->toString(), $data);
            return $resultData;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }


    function update(Agreement $agreement)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("UPDATE  agreements set `cnpj`=?,`address`=?,obs=?,`start`=?,`end`=?,price=?  where fk_branche_id=? and  id=? ");

            $data = [
                $agreement->doc,
                $agreement->address,
                '',
                $agreement->start,
                $agreement->end,
                $agreement->prices,
                $agreement->fk_branche_id,
                intval($agreement->id)
            ];

            $resultData =( $this->repository->execute($sql->toString(), $data)) ? 1 : 0;
            return $resultData;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }
}
