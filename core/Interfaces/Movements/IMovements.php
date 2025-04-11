<?php

namespace Interfaces\Movements;

use Models\IsOpenCar;
use Models\Movements;
use Commons\TypeDateTime;

interface IMovements
{
    function records();
    function all();
    function lastPrimaryKey();
    function IsVacancyIOccupied($vacancy);
    function created(Movements $movements);
    function delete($modelId);
    function byUuid($uuid);
    function byPlate($plate);
    function isOpenCarPlateOrCode(IsOpenCar $isOpenCar);
    function redeemOpenPlatesOfTheDay($uuid = null);
    function checkIfThePlateHasEnteredTheParkingLot($plate, TypeDateTime $typeDateTime, $isCount = false);
    function  checkIfThePlateHasAlreadyLeftTheParkingLot($plate, TypeDateTime $typeDateTime, $isCount = false);
    function cancellation($uuid);
    function isprint($typePrint, $branch);
    function printExecuted($uuid);
    function printReset($uuid);
    function closeOfDay($year, $month, $day);
    function movementsDay($year, $month, $day);
    function byPlateDay($plate,$year, $month, $day,$branch=null);
}
