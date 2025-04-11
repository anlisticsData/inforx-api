<?php

namespace Interfaces\Price;

use Models\PricesIntervals;

interface IPriceRepository{
    function all($branchCode);
    function priceActive($branchCode);
    
    function createPriceInterval(PricesIntervals $pricesIntervals);
    function allPriceInterval($fkBranchId);
    function allPriceIntervalAndTypeVehicle($fkBranchId);
    function hasPriceInterval($fkBranchId,$fkPriceId,$intervalStart,$intervalEnd);
    function hasPriceIntervalType($fkBranchId,$fkPriceId);
    function deletePriceInterval($fkBranchId,$fkPriceId,$priceId);




}