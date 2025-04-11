<?php

namespace Interfaces\Payments;

use Models\Payment;
use Models\PaymentOfMonthlyPayments;


interface IPaymentsRepository{

    function all($branchCode);
    function created(Payment $payment);
    function endEntry($movementId,$userId,$paymentId);
    function checkMonthlyPayment(PaymentOfMonthlyPayments $PaymentOfMonthlyPayments);
    function monthlyPayerMadePayment(PaymentOfMonthlyPayments $PaymentOfMonthlyPayments);
    function checkPaymentBy($movementId);


  
}
