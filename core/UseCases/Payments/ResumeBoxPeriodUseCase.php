<?php

namespace UseCases\Payments;

use Exception;
use Commons\Uteis;
use Interfaces\IUserCase;
use Dtos\PaymentResumePeriodoDto;
use Interfaces\Agreements\IAgreementRepository;
use Interfaces\Payments\IPaymentTypesRepository;
use Repositories\Payments\ResumeBoxPeriodRepository;


class ResumeBoxPeriodUseCase implements IUserCase
{

    private ResumeBoxPeriodRepository $iResumeBoxPeriodRepository;
    private IAgreementRepository $iAgreementRepository;
    private IPaymentTypesRepository $iPaymentTypesRepository;

    public function __construct(
        ResumeBoxPeriodRepository $iResumeBoxPeriodRepository,
        IAgreementRepository $iAgreementRepository,
        IPaymentTypesRepository $iPaymentTypesRepository
    ) {
        $this->iResumeBoxPeriodRepository = $iResumeBoxPeriodRepository;
        $this->iAgreementRepository = $iAgreementRepository;
        $this->iPaymentTypesRepository = $iPaymentTypesRepository;
    }

    public function execute($initialDate, $endDate, $codeBranche)
    {
        try {
            $agreements =  $this->iAgreementRepository->allAgreements($codeBranche);
            $paymentsTypes =  $this->iPaymentTypesRepository->all($codeBranche);
            $summary =   $this->iResumeBoxPeriodRepository->cashPeriodSummary($initialDate, $endDate, $codeBranche);
            $lists = [];
            $summaryOfPayments = (!is_null($summary) > 0) ? count($summary) : 0;
            $summaryOfInputs = 0;
            $summaryOfDisconts = 0;
            $summaryDiscontsList = [];
            foreach ($summary  as $index => $row) {
                $paymentSummary = new PaymentResumePeriodoDto($row->toArray());

               
                $paymentSummary->value_payment_types = $this->getGenericsList($paymentsTypes, $paymentSummary->fk_payment_types, 'id');
                $paymentSummary->value_agreements_discont = $this->getGenericsList($agreements, $paymentSummary->fk_agreement_id, 'id');
               
               
                $summaryOfInputs += ($paymentSummary->receipt_by_box) - $paymentSummary->payment_change;
                $summaryOfDisconts += $paymentSummary->discount_applied;
               
                if ($paymentSummary->fk_agreement_id > 0) {
                    $summaryDiscontsList[] = $this->getGenericsList($agreements, $paymentSummary->fk_agreement_id, 'id');
                }
                $lists[] = $paymentSummary;

                
            }

            
            return [
                "total" => [
                    "entries" => intval($summaryOfPayments),
                    "releases" =>number_format($summaryOfInputs, 2),
                    "discounts" => number_format($summaryOfDisconts, 2),
                    "liquid" =>number_format($summaryOfInputs - $summaryOfDisconts,2)
                ],
                "summary" => $lists,
                "summary_disconts" => $summaryDiscontsList
            ];
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
        return null;
    }
    private function getGenericsList($array, $value, $column)
    {
        foreach ($array as $_generics) {
            if ($_generics->$column == $value) {
                return $_generics; // Retorna o método de pagamento inteiro que corresponde ao id
            }
        }
        return null; // Caso não encontre nenhum método de pagamento com o id
    }
}
