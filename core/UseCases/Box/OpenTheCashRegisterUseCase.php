<?php

namespace UseCases\Box;

use Exception;
use Models\Box;
use Dtos\BoxDto;
use Commons\Clock;
use Commons\Uteis;
use Interfaces\Box\IBoxRepository;

class OpenTheCashRegisterUseCase
{
    private  IBoxRepository $iBoxRepository;
    public function __construct(IBoxRepository $iBoxRepository)
    {
        $this->iBoxRepository = $iBoxRepository;
    }
    public function execute(BoxDto $boxDto)
    {
        try {
            $boxDto->date =  date("Y-m-d");
            return $this->iBoxRepository->openBox(new Box($boxDto->toArray()));
        } catch (Exception $e) {
            throw new Exception($e->getMessage(),$e->getCode());
        }
    }
}
