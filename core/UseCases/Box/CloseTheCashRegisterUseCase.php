<?php

namespace UseCases\Box;

use Exception;
use Models\Box;
use Dtos\BoxDto;
use Commons\Clock;
use Commons\Uteis;
use Interfaces\Box\IBoxRepository;

class CloseTheCashRegisterUseCase
{
    private  IBoxRepository $iBoxRepository;
    public function __construct(IBoxRepository $iBoxRepository)
    {
        $this->iBoxRepository = $iBoxRepository;
    }
    public function execute(BoxDto $boxDto)
    {
        try {
            return $this->iBoxRepository->closeBox(new Box($boxDto->toArray()));
        } catch (Exception $e) {
            throw new Exception($e->getMessage(),$e->getCode());
        }
        return null;
    }
}
