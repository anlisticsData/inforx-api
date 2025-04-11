<?php
namespace Interfaces\Car;

use Models\Monthly;
 

interface IMonthlyRepository{
    function records();
    function all();
    function created(Monthly $monthly);
    function delete($modelId);
    function by($monthlyId);
    function byPlate($plate,$branchId);


}



 