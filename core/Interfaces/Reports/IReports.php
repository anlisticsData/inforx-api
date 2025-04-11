<?php

namespace Interfaces\Reports;

use Dtos\IntervalDto;

interface IReports{
    function summaryBox(IntervalDto $intervalDto);
    function summaryBoxAgreements(IntervalDto $intervalDto);
    function summaryBoxPeriods(IntervalDto $intervalDto);
}