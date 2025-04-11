<?php


namespace UseCases\Payments;

use Exception;
use Commons\ResponseJson;
use Interfaces\IUserCase;
use Interfaces\Payments\IPaymentsRepository;
 

class CheckMonthlyPaymentUseCase implements IUserCase
{

    private IPaymentsRepository $iPaymentsRepository;

    public function __construct(IPaymentsRepository $iPaymentsRepository)
    {
        $this->iPaymentsRepository = $iPaymentsRepository;
     
    }

    
    public function execute($input)
    {
      
        try {


            return $this->iPaymentsRepository->checkMonthlyPayment($input);
        } catch (Exception $e) {

            
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}
