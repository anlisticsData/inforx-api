<?php


namespace UseCases\MovementCameras;

use Exception;
use Commons\Uteis;
use Dtos\MovementsDto;
use Interfaces\IUserCase;
use Interfaces\Movements\IMovements;
use Interfaces\MovementCameras\IMovimentCameras;
use Interfaces\Payments\IPaymentsRepository;

class ProcessMovementRemoteInLocalUserCase implements IUserCase
{
    private IMovimentCameras $iMovimentCamerasRepository;
    private IMovements $iMovementsRepository;
    private IPaymentsRepository $iPaymentsRepository;


    public function __construct(iMovimentCameras $iMovimentCamerasRepository, IMovements $iMovementsRepository, IPaymentsRepository $iPaymentsRepository)
    {
        $this->iMovimentCamerasRepository = $iMovimentCamerasRepository;
        $this->iMovementsRepository = $iMovementsRepository;
        $this->iPaymentsRepository = $iPaymentsRepository;


        return $this;
    }
    public function execute($user, $vehicles, $byUuidMovementUserCase, $createMovementUserCase)
    {
        try {
            $movementNotProcessed =  $this->iMovimentCamerasRepository->allProcessedFalse();

            foreach ($movementNotProcessed  as  $mv) {
                $newCar =  new MovementsDto();
                $newCar->park_entry_date = $mv->created_at;
                $newCar->park_vehicle_plate =  $mv->plate;
                $newCar->uuid_ref =  $mv->remote_ref;
                $newCar->double_vacancy = 0;
                $newCar->branches_id = $user->branches_id;
                $newCar->user_entry = $user->id;
                $newCar->fk_type_of_vehicle = $vehicles[0]->id;
                $newCar->type_print = 2;
                $uuid = str_replace("-", "", str_replace(":", "", str_replace(" ", "_", $newCar->park_entry_date)));
                $newCar->uuid_id_plate_direction_create = sprintf("%s_%s_%s_%s", $mv->remote_ref, $mv->plate, $newCar->branches_id, $uuid);
                $resultMovementsData = $byUuidMovementUserCase->execute($mv->plate);


                if (Uteis::isNullOrEmpty($resultMovementsData->park_id)) {
                    if (count($resultMovementsData) > 0) {
                        $isPayment = $this->iPaymentsRepository->checkPaymentBy($resultMovementsData[0]->park_id);
                        $isDeleted = is_null($resultMovementsData[0]->deleted_at);

                     
                        if (!is_null($isPayment) &&   count($isPayment) % 2 == 1) {
                            if ($createMovementUserCase->execute($newCar) > 0  ) {
                                $this->iMovimentCamerasRepository->processedTrue($mv->uuid);
                            }
                        }else{
                            $diff = strtotime(date('Y-m-d H:i:s')) - strtotime($resultMovementsData[0]->created_at);
                            $minute = $diff / (60 );
                            if ($isDeleted &&  $minute > 15) {
                                if ($createMovementUserCase->execute($newCar) > 0) {
                                    $this->iMovimentCamerasRepository->processedTrue($mv->uuid);
                                }
                            }
                        }
                    } else {
                        if ($createMovementUserCase->execute($newCar) > 0) {
                            $this->iMovimentCamerasRepository->processedTrue($mv->uuid);
                            $this->iMovementsRepository->printReset($newCar->uuid_id_plate_direction_create);
                        }
                    }
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}
