<?php

namespace Interfaces\Modules;



interface IModuleRepository{
    function findOne($moduleCode);

    function hasNewPlates($lastPlate=0);

    
    


}
