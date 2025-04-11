<?php
namespace Interfaces\Users;

use Models\User;

interface IUserRepository{
   function records();
   function auth($login,$password);
   function By($userId);
   function GroupByUser($userId);
   function UserAccess($groupId);
   function UserTokenRegister($userId,$token,$expiredSession);
   function UserByToken($token);
   function UserUpdateToken($token);
   function UserInvalidToken($token);
   function UserTokenVeryfy($token);
   function created(User $user);
   function menusBy($code);
   function validateUserNameEmail($userName,$userEmail);
   function byEmail($userEmail);
   function createdOperator(User $user);
   function changePassword($userId,$newPassword);
   function permissions($groupId);
   


}