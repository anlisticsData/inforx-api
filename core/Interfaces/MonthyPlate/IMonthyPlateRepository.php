<?php

namespace Interfaces\MonthyPlate;

use Models\MonthyPlate;

interface IMonthyPlateRepository{
    function findOne($id);
    function findOnePlate($plate);
    function registerNewPlateAtTheBranch(MonthyPlate $monthyPlate);
    function completeVehicleInformation($plate);
}
