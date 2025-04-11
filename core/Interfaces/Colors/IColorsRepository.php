<?php 

namespace Interfaces\Colors;

use Models\Colors;



interface IColorsRepository{
    function records();
    function all();
    function one($code);
    function created(Colors $cartype);
    function hasColorToModel($model);
    function delete($corId);
}