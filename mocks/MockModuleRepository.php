<?php

use Models\Plate;
use Interfaces\Modules\IModuleRepository;

class MockModuleRepository implements IModuleRepository{
    function findOne($moduleCode){
        return null;
    }

    function hasNewPlates($lastPlate=0){
        return [
            new Plate([
                "codigo"=>123,
                "portatirasensor"=>1,
                "created_at"=>"2024-12-10 14:52:10",
                "placa"=>"DDS123"
            ]),
            new Plate([
                "codigo"=>8754,
                "portatirasensor"=>2,
                "created_at"=>"2024-12-14 14:52:10",
                "placa"=>"FFS123"
            ])
        ];
    }
}