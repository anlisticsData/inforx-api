 
<?php

namespace Interfaces\Payments;


interface IResumeBoxPeriodRepository{

    function cashPeriodSummary($initalDate,$endDate,$branchCode);
  
}
