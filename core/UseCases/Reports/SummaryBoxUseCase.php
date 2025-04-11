<?php

namespace UseCases\Reports;

use Commons\Uteis;
use Exception;
use Dtos\IntervalDto;
use Dtos\SummaryBoxDay;
use Interfaces\Reports\IReports;

class SummaryBoxUseCase{
    private IReports $iReports;
    public function __construct( IReports $iReports){
        $this->iReports   =  $iReports;
    }     
    public function execute(IntervalDto $interval){
        try{

            $summaryDay=new SummaryBoxDay();
            $resultSummarry =  $this->iReports->summaryBox($interval);
            $resultSummarryAgreements =  $this->iReports->summaryBoxAgreements($interval);
            $summaryDay->summary = $resultSummarry;
            $cancelled=null;
            $agreements=[];
            $receved=0;
            $disconted=0;

            foreach($summaryDay->summary  as $index => $row){
                if(!is_null($row["agreements"])){
                    $agreements[]=$row;
                }
                
                $receved+=$row['receipt_by_box'];
                $disconted+=$row['discount_applied'];

                
            }
            foreach($summaryDay->summary  as $index => $row){
                if(!is_null($row["deleted_at"])){
                    $cancelled[]=$row;
                }
            }
            $summaryDay->cancelled = $cancelled;

         
            $summaryDay->agreements= $agreements;
            foreach($resultSummarryAgreements as $row){
              $summaryDay->agreementsSummary[] =[
                "name"=>$row["name"],
                "receveid"=>number_format($row["receveid"],2),
                "discounted"=>number_format($row["discounted"],2),
                "difference"=>number_format($row["difference"],2)

              ] ;
            }
            $summaryDay->paymentsSummary=[
                "subtotal"=>number_format($receved,2),
                "disconted"=>number_format($disconted,2),
                "total"=>number_format($receved - $disconted,2),
                
            ];
            return $summaryDay;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

}
