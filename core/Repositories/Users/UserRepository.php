<?php

namespace Repositories\Users;

use PDO;
use Exception;
use Models\Menu;
use Models\User;
use Models\Group;
use Commons\Uteis;
use Models\Permission;
use Resources\Strings;
use Models\TokenVerify;
use Resources\HttpStatus;
use Commons\StringBuilder;
use Interfaces\IConnection;
use Interfaces\IConnections;
use Adapters\PdoMysqlAdapter;
use Commons\DataBaseRepository;
use Interfaces\Users\IUserRepository;

class UserRepository implements IUserRepository
{
    private IConnections $repository;

    public function __construct()
    {
        $this->repository =  new DataBaseRepository();
    }
    function records()
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT count(*) as records  FROM states;");

            $resultData = $this->repository->query($sql->toString(), null, false);
            return $resultData['records'];
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return 0;
    }


    function permissions($groupId){
        $permissions = [];
        try {
            $sql = new StringBuilder();
            $sql->Insert("select  p.id,p.router,p.state  from permissions p  join permissions_has_groups pg ");
            $sql->Insert(" on pg.permissions_id=p.id where  pg.groups_id =?");
            $resultData =  $this->repository->query($sql->toString(), array($groupId));
            foreach ($resultData as $index => $row) {
                $permissions[] =[
                    'id' => $row['id'],
                    'router' => $row['router'],
                    'state' => $row['state']
                ];  

            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $permissions;
    }



    function userUpdatePassword($codeUser, $password)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("UPDATE  users  set updated_at=now() , `password`=? where id=?");
            $resultData = $this->repository->execute($sql->toString(), [$password, $codeUser]);
            if ($resultData) {
                return true;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return false;
    }




    function byEmail($userEmail)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT `id`, `name`, `email` FROM users ");
            $sql->Insert(" where email=?");
            $resultData = $this->repository->query($sql->toString(), array($userEmail), false);
            if ($resultData) {
                return new User($resultData);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }

    function validateUserNameEmail($userName, $userEmail)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT `id`, `name`, `email` FROM users ");
            $sql->Insert(" where name=? and email=?");
            $resultData = $this->repository->query($sql->toString(), array($userName, $userEmail), false);
            if ($resultData) {
                return new User($resultData);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }

    function updateAvatar($codeUser, $codeUpload)
    {

        try {
            $sql = new StringBuilder();
            $sql->Insert("UPDATE  users  set avatar_id=? , updated_at=now() where id=?");
            $resultData = $this->repository->execute($sql->toString(), [$codeUpload, $codeUser]);
            if ($resultData) {
                return true;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return false;
    }



    function UserByToken($token)
    {
        try {


            $sql = new StringBuilder();
            $sql->Insert("SELECT id, token, user, created_at, expired_time, updated_at  FROM tokens where token=?;");

            $resultData = $this->repository->query($sql->toString(), [$token], false);
            if ($resultData) {
                return new TokenVerify($resultData);
            }
            return null;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }

    function UserTokenVeryfy($token)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT id, token, user, created_at, expired_time, updated_at  FROM tokens where token=?;");
            $resultData = $this->repository->query($sql->toString(), [$token], false);
            if ($resultData) {
                return new TokenVerify($resultData);
            }
            return false;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }


    function UserUpdateToken($token)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("UPDATE  tokens  set updated_at=now() where token=?");
            $resultData = $this->repository->execute($sql->toString(), [$token]);
            if ($resultData) {
                return true;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return false;
    }
    function UserInvalidToken($token)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("UPDATE  tokens  set expired_time=? where token=?");
            $resultData = $this->repository->execute($sql->toString(), ["0", $token]);
            if ($resultData) {
                return true;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return false;
    }

    function updateFullName($codeUser, $newFullName)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("UPDATE  users  set `name`=? ,updated_at=now() where id =?");
            $resultData = $this->repository->execute($sql->toString(), [$newFullName, $codeUser]);
            if ($resultData) {
                return true;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return false;
    }

    function updatePassword($codeUser, $newPassword)
    {
        try {



            $sql = new StringBuilder();
            $sql->Insert("UPDATE  users  set `password`=? ,updated_at=now() where id =?");
            $resultData = $this->repository->execute($sql->toString(), [$newPassword, $codeUser]);
            if ($resultData) {
                return true;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return false;
    }




    function created(User $user)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("INSERT INTO users(`name`,email,`password`,`state`,created_at,groups_id,customer_id,branches_id)");
            $sql->Insert(" VALUES(?,?,?,?,now(),?,?,?)");

            $data = [
                $user->name,
                $user->email,
                $user->password,
                $user->state,
                $user->groups_id,
                $user->customer_id,
                $user->branches_id
            ];
            return $this->repository->executeAutoIncrement($sql->toString(), $data);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }



    function createdOperator(User $user)
    {

        try {
            $sql = new StringBuilder();
            $sql->Insert("INSERT INTO users(`name`,email,`password`,`state`,created_at,groups_id,customer_id,branches_id)");
            $sql->Insert(" VALUES(?,?,?,?,now(),?,?,?)");

            $data = [
                $user->name,
                $user->email,
                $user->password,
                $user->state,
                $user->groups_id,
                $user->customer_id,
                $user->branches_id
            ];
            return $this->repository->executeAutoIncrement($sql->toString(), $data);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }






    function UserTokenRegister($userId, $token, $expiredSession)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("INSERT INTO tokens(token, user, created_at, expired_time, updated_at) ");
            $sql->Insert(" VALUES(?,?, now(),?,now())");
            $resultData = $this->repository->execute($sql->toString(), [$token, $userId, $expiredSession]);
            if ($resultData) {
                return new User($resultData);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }


    function auth($login, $password)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT `id`, `name`, `email`, `password`, `state`, `created_at`, `updated_at`, `groups_id`,`avatar_id`,`customer_id`,`branches_id`,`settings` FROM users ");
            $sql->Insert(" where email=?");
            $resultData = $this->repository->query($sql->toString(), array($login), false);
            if ($resultData) {
                return new User($resultData);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }


    function GroupByUser($userId)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT `id`, `descriptions`, `created_at`, `deleted_at`, `state` FROM `groups` where id=?");
            $resultData = $this->repository->query($sql->toString(), array($userId), false);
            if ($resultData) {
                return new Group($resultData);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }



    function menusBy($code)
    {
        $menus = [];
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT x.group_fk,m.* ");
            $sql->Insert("FROM group_menus x join menus m on x.menu_fk =m.id  where x.group_fk =?");
            $resultData =  $this->repository->query($sql->toString(), array($code));
            foreach ($resultData as $index => $row) {
                $menus[] =  new Menu($row);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $menus;
    }

    function UserAccess($groupId)
    {
        $states = [];
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT pg.permissions_id, pg.groups_id, pg.uuid, p.id, ");
            $sql->Insert("p.router, p.created_at, p.state  from permissions_has_groups pg join permissions p");
            $sql->Insert("on pg.permissions_id=p.id where pg.groups_id=?");
            $resultData =  $this->repository->query($sql->toString(), array($groupId));
            foreach ($resultData as $index => $row) {
                $states[] =  new Permission($row);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return $states;
    }



    function By($userId)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("SELECT `id`, `name`, `email`, `password`, `state`, `created_at`, `updated_at`, `groups_id`,`avatar_id`,`customer_id`,`branches_id`,`settings` FROM users ");
            $sql->Insert(" where id=? ");
            $resultData = $this->repository->query($sql->toString(), array($userId), false);

            if ($resultData) {

                return new User($resultData);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }




    function changePassword($userId, $newPassword)
    {
        try {
            $sql = new StringBuilder();
            $sql->Insert("update users set `password`=? where id=?");
            $data = [$newPassword,$userId];
            return $this->repository->execute($sql->toString(), $data);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), HttpStatus::$HTTP_CODE_INTERNAL_SERVER_ERROR);
        }
        return null;
    }
}
