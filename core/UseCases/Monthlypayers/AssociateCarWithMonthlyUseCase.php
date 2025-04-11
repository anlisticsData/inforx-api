<?php
namespace UseCases\Monthlypayers;

use Exception;
use Commons\Uteis;
use Models\Monthly;
use Resources\Strings;
use Interfaces\IUserCase;
use Resources\HttpStatus;
use Models\MonthlyCartypeAssociate;
use Dtos\MonthlyCartypeAssociateDto;
use Interfaces\Car\IMonthlyRepository;
use Interfaces\MonthlyPayers\IMonthlyPayersRepository;

class AssociateCarWithMonthlyUseCase implements IUserCase
{
    private IMonthlyPayersRepository $iMonthyPlateRepository;
    private IMonthlyRepository $iMonthlyRepository;
    
    public function __construct(IMonthlyPayersRepository $iMonthyPlateRepository,IMonthlyRepository $iMonthlyRepository)
    {   
        $this->iMonthyPlateRepository = $iMonthyPlateRepository;
        $this->iMonthlyRepository = $iMonthlyRepository;
        return $this;
    }
    public function execute(MonthlyCartypeAssociateDto $monthlyCar)
    {
      
        try{
            $monthlyPayer = $this->iMonthyPlateRepository->findOne($monthlyCar->monthly_id);
            $hasCarRegistred =$this->iMonthlyRepository->byPlate($monthlyCar->plate,$monthlyCar->fk_branch);
            if(count($hasCarRegistred) > 0){
                throw new Exception(Strings::$STR_CAR_MONTHLY_PAYER_PLATE,HttpStatus::$HTTP_CODE_BAD_REQUEST);
            } 
            $newCar = new Monthly(
                [   
                    "fk_color_id"=>$monthlyCar->fk_color,
                    "types_of_cars_id"=>$monthlyCar->fk_typecar,
                    "prisma" =>$monthlyCar->prisma,
                    "monthly_filiais_clientes_id"=>$monthlyCar->fk_branch,
                    "plate" =>$monthlyCar->plate
                ]
            );
            $resultNewCarCode =  $this->iMonthlyRepository->created($newCar);
            $associatedCar =  new MonthlyCartypeAssociate(
                [
                    "fk_monthy_players_id"=>$monthlyCar->monthly_id,
                    "fk_car" =>$resultNewCarCode               
                ]
            );
            $resultAssociated = $this->iMonthyPlateRepository->associateCarWithMonthlyMember($associatedCar);
            if($resultAssociated){
                return true;
            }
            $this->iMonthlyRepository->delete($resultNewCarCode);
            return false;
        }catch(Exception $e){
            throw new Exception($e->getMessage(), $e->getCode());
        }
        return false;
     }
}
