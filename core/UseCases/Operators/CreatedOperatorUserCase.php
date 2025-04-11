<?php

namespace UseCases\Operators;

use Models\User;
use Commons\Clock;
use Commons\Uteis;
use Dtos\OperatorDto;
use Commons\PasswordHash;
use Resources\APPLICATION;
use Interfaces\Users\IUserRepository;
use PHPUnit\Framework\Constraint\Operator;

class CreatedOperatorUserCase{
    private IUserRepository $userRepository;
    public function __construct( IUserRepository $userRepository){
        $this->userRepository   =  $userRepository;
    }     
    public function execute(OperatorDto $operatorDto){
        $operatorDto->state = APPLICATION::$APP_CODE_ACTIVE_STATE;
        $operatorDto->created_at = Clock::NowDate();
        $operatorDto->groups_id = APPLICATION::$APP_CODE_DEFAULT_CUSTOMERS_OPERATOR;
        $operatorDto->password = PasswordHash::Create($operatorDto->password);
        return $this->userRepository->createdOperator(new User($operatorDto->toArray()));

    }
}