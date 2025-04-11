<?php
namespace Interfaces\Car;

use Models\Cartype;

interface ICartype{
    function records();
    function all();
    function one($code);
    function created(Cartype $cartype);
    function hasCarToModel($model);
    function delete($modelId);

    function typeOfVehicles();
    function byTypeOfVehicles($code);


}

