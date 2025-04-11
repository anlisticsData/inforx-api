<?php

namespace Interfaces\MonthlyPayers;

use Models\MonthlyPayer;
use Dtos\MonthlyPayerDto;
use Models\MonthlyCartypeAssociate;

interface IMonthlyPayersRepository{
    function findOne($primary);
    function findOneEmailOrCpfOrRg(MonthlyPayerDto $monthlyDto);
    function findAllCustomerAndBranche($fkCustomer,$fkBranch);
    function findAll($fkCustomer,$fkBranch);
    function created(MonthlyPayer $monthlyPayer);
    function update(MonthlyPayer $monthlyPayer);
    function associateCarWithMonthlyMember(MonthlyCartypeAssociate $monthlyCartypeAssociate);
    function findAllCarsBy($monthlyCode);
    function removeCarAssociate($monthlyCode);
    function disableMonthlySubscription($monthlyCode);
    function activateMonthlySubscription($monthlyCode);
    function monthlyBlock($monthlyCode);



    



}

