<?php


namespace UseCases\Users;

use Exception;
use Commons\Uteis;
use Resources\Strings;
use Commons\PasswordHash;
use Resources\HttpStatus;
use Services\UserServices;
use Dtos\UserRequeChangeDto;
use Interfaces\Users\IUserRepository;
use Repositories\Users\UserRepository;

class UpdateChangePasswordUseCase
{
    private IUserRepository $userRepository;
    private UserServices $userServices;

    public function __construct(IUserRepository $userRepository, UserServices $userServices)
    {
        $this->userRepository = $userRepository;
        $this->userServices =  $userServices;
        return $this;
    }
    public function execute($userPlayLoad, UserRequeChangeDto $userRequeChangeDto)
    {
        try {
            $userData = $this->userRepository->auth($userPlayLoad['email'], $userRequeChangeDto->current_password);
            if (is_null($userData) || !PasswordHash::Verify($userRequeChangeDto->current_password, $userData->password)) {
                throw new Exception(Strings::$STR_USER_INVALIDE_PASSW_CURRENT, HttpStatus::$HTTP_CODE_UNAUTHORIZED);
            }
            $newPassword =  PasswordHash::Create($userRequeChangeDto->new_password);
            return $this->userRepository->changePassword($userData->id, $newPassword);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}
