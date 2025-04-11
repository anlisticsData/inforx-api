<?php

namespace UseCases\Movements;

use Exception;
use MockPlate;
use Commons\Clock;
use Commons\Uteis;
use Dtos\ModuleDto;
use Models\Movements;
use Dtos\MovementsDto;
use Models\MonthyPlate;
use Dtos\MonthyPlateDto;
use Interfaces\IUserCase;
use Commons\DateTimeCalculator;
use Interfaces\Movements\IMovements;

class RedeemOpenPlatesOfTheDay implements IUserCase
{
    private IMovements $IMovements;
    public function __construct(IMovements $IMovements)
    {
        $this->IMovements = $IMovements;
        return $this;
    }
    public function execute($uuid=null)
    {
        try {
            $movements=[];
            $movementsData = $this->IMovements->redeemOpenPlatesOfTheDay($uuid);
            foreach($movementsData as $iten){
                $dateCreatedAt = $iten->park_entry_date;
                $dateCurrenc = Clock::NowDate();
                $lengthOfStay = DateTimeCalculator::calculateDifference($dateCreatedAt,$dateCurrenc);
                $iten->permanence =$lengthOfStay ;
                $iten->hours = DateTimeCalculator::convertToHours($lengthOfStay["hours"],$lengthOfStay["minutes"]);
                $movements[]=$iten;
            }
            return $movements; 
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
        return null;
    }
}
