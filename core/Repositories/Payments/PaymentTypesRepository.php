<?php

namespace Repositories\Payments;

use Exception;
use Models\Payment;
use Models\PaymentType;
use Resources\HttpStatus;
use Commons\StringBuilder;
use Interfaces\IConnections;
use Commons\DataBaseRepository;
use Interfaces\Payments\IPaymentsRepository;
use Interfaces\Payments\IPaymentTypesRepository;

class PaymentTypesRepository implements IPaymentTypesRepository{

    private IConnections $repository;

    public function __construct() {
        $this->repository = new DataBaseRepository();
    }
    
    function all($branchCode){
        try {
            $sql = new StringBuilder();
            $sql->Insert("select * from payment_types p where  p.deleted_at  is  null and p.fk_branches_id=? and  p.payment_methods_active=1");
            $resultData = $this->repository->query($sql->toString(),[$branchCode]);
            if(!is_null($resultData) && count($resultData) > 0){
                $payments=[];
                foreach($resultData as $key => $row){
                    $payments[]=new PaymentType($row);
                }
                return $payments;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }
}
