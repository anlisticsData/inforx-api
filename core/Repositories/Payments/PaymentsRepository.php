<?php

namespace Repositories\Payments;

use Commons\Clock;
use Exception;
use Commons\Uteis;
use Models\Payment;
use Models\PaymentType;
use Resources\HttpStatus;
use Commons\StringBuilder;
use Interfaces\IConnections;
use Commons\DataBaseRepository;
use Models\PaymentOfMonthlyPayments;
use Interfaces\Payments\IPaymentsRepository;

class PaymentsRepository implements IPaymentsRepository
{

    private IConnections $repository;

    public function __construct()
    {
        $this->repository = new DataBaseRepository();
    }



    function monthlyPayerMadePayment(PaymentOfMonthlyPayments $PaymentOfMonthlyPayments)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("UPDATE  payment_of_monthlypayments  set payment_date=now(),state='P' ,payment_value=? where id=?");
            $resultData = $this->repository->execute(
                $sql->toString(),
                [
                    $PaymentOfMonthlyPayments->payment_value,
                    $PaymentOfMonthlyPayments->id
                ]
            );
            if ($resultData) {
                return true;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return false;
    }

    function checkPaymentBy($movementId)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("select * from payments p where  p.deleted_at  is  null and p.fk_movements_id=?");
            $resultData = $this->repository->query($sql->toString(), [$movementId]);
            if (!is_null($resultData) && count($resultData) > 0) {
                return new Payment($resultData[0]);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }


    function checkMonthlyPayment(PaymentOfMonthlyPayments $PaymentOfMonthlyPayments)
    {
        try {

            $payments = [];
            $sql = new StringBuilder();
            $sql->Insert("select * from payment_of_monthlypayments p where  p.deleted_at  is  null and p.payment_value is null and p.fk_monthly_payer=? and  p.state=?");
            $resultData = $this->repository->query($sql->toString(), [$PaymentOfMonthlyPayments->fk_monthly_payer, $PaymentOfMonthlyPayments->state]);
            if (!is_null($resultData) && count($resultData) > 0) {
                $payments = [];
                foreach ($resultData as $key => $row) {
                    $payments[] = new PaymentOfMonthlyPayments($row);
                }
                return $payments;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }


    function all($branchCode)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("select * from payment_types p where  p.deleted_at  is  null and p.fk_branches_id=? and  p.payment_methods_active=1");
            $resultData = $this->repository->query($sql->toString(), [$branchCode]);
            if (!is_null($resultData) && count($resultData) > 0) {
                $payments = [];
                foreach ($resultData as $key => $row) {
                    $payments[] = new PaymentType($row);
                }
                return $payments;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }



    function endEntry($movementId, $userId, $paymentId)
    {
        try {
            $sql = new StringBuilder();
            $exitDate = Clock::NowDate();
            $sql->Insert("UPDATE  movements  set park_date_departure=?,user_exit=? ,fk_payment_id=? where park_id=?");
            $resultData = $this->repository->execute($sql->toString(), [$exitDate, $userId, $paymentId, $movementId]);
            if ($resultData) {
                return true;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return false;
    }

    function created(Payment $payment)
    {

        try {
            $paymentsTypes =  $this->all($payment->fk_branch_id);
            $paymentsTypesDefault = null;
            foreach ($paymentsTypes as $key => $row) {
                if ($row->default == 1) {
                    $paymentsTypesDefault = $row;
                    break;
                }
            }
            if ($paymentsTypesDefault == null && count($paymentsTypes) > 0) {
                $paymentsTypesDefault = $paymentsTypes[0];
            }
            if ($payment->fk_payment_types == null || $payment->fk_payment_types == -1) {
                $payment->fk_payment_types = $paymentsTypesDefault->id;
            }
            $sql = new StringBuilder();
            $sql->Insert("INSERT INTO payments( created_at, fk_branch_id, ip_address, fk_user_id,");
            $sql->Insert("fk_payment_types, fk_pricing_id, fk_movements_id, uuid_id_plate_direction_create, receipt_by_box, payment_change,");
            $sql->Insert("total,hours,minutes,discount_applied,fk_agreement_id) ");
            $sql->Insert(" VALUES(now(),?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $data = [
                $payment->fk_branch_id,
                $payment->ip_address,
                $payment->fk_user_id,
                $payment->fk_payment_types,
                $payment->fk_pricing_id,
                $payment->fk_movements_id,
                $payment->uuid_id_plate_direction_create,
                $payment->receipt_by_box,
                $payment->payment_change,
                $payment->total,
                $payment->hours,
                $payment->minutes,
                $payment->discount_applied,
                $payment->fk_agreements_discont
            ];
            $resultData = $this->repository->executeAutoIncrement($sql->toString(), $data);
            return $resultData;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }
}
