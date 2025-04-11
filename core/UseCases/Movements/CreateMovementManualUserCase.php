<?php

namespace UseCases\Movements;

use Exception;
use MockPlate;
use Commons\Uteis;
use Dtos\ModuleDto;
use Models\Movements;
use Dtos\MovementsDto;
use Resources\Strings;
use Models\MonthyPlate;
use Dtos\MonthyPlateDto;
use Commons\ResponseJson;
use Interfaces\IUserCase;
use Interfaces\Movements\IMovements;
use UseCases\Colors\GetByColorUseCase;
use UseCases\Cartypes\GetByCartypeUseCase;
use UseCases\Cartypes\ByTypeOfVehiclesUseCase;

class CreateMovementManualUserCase implements IUserCase
{
    private IMovements $IMovements;
    private GetByColorUseCase $GetByColorUseCase;
    private GetByCartypeUseCase $GetByCartypeUseCase;
    private ByTypeOfVehiclesUseCase $byTypeOfVehiclesUseCase;


    public function __construct(IMovements $IMovements, GetByColorUseCase $GetByColorUseCase,
                                GetByCartypeUseCase $GetByCartypeUseCase,
                                ByTypeOfVehiclesUseCase $byTypeOfVehiclesUseCase)
    {
        $this->IMovements = $IMovements;
        $this->GetByColorUseCase = $GetByColorUseCase;
        $this->GetByCartypeUseCase = $GetByCartypeUseCase;
        $this->byTypeOfVehiclesUseCase =  $byTypeOfVehiclesUseCase;

        return $this;
    }

    public function execute(MovementsDto $movements)
    {
 

        try {
            if (count($this->GetByColorUseCase->execute($movements->fk_color_id)) == 0) {
                throw new Exception(Strings::$STR_COLOR_TYPE_CODE_NOT_FOUND  , 400);
            }
            if (count($this->GetByCartypeUseCase->execute($movements->fk_cartype_id)) == 0) {
                throw new Exception(Strings::$STR_CAR_TYPE_CODE_NOT_FOUND, 400);
            }

            if (count($this->byTypeOfVehiclesUseCase->execute($movements->fk_type_of_vehicle)) == 0) {
                throw new Exception(Strings::$STR_CAR_TYPE_VEHICLE_CODE_NOT_FOUND, 400);
            }
            
           
            $movements->park_id=$this->IMovements->lastPrimaryKey();
            $movements->uuid_ref=$movements->park_id+1;
            return $this->IMovements->created(new Movements($movements->toArray()));
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
        return null;
    }
}
