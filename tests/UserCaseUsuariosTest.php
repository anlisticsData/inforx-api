<?php

use PHPUnit\Framework\TestCase;
use UseCases\Core\HasPermissionUseCase;
use Interfaces\Permissions\IPermissionsRepository;

require_once "vendor/autoload.php";



class UserCaseUsuariosTest extends TestCase
{

  public function testValidaSeUsuarioTemPermisaoDeAcesso()
  {
    $PermissionsRepositoryInMemory = new class implements IPermissionsRepository {
      public function all()
      {
        return [
          ['id' => 1, 'router' => 'public#services#get#branches#all', "state" => 1],
          ['id' => 2, 'router' => 'public#services#update#service', "state" => 1],
          ['id' => 3, 'router' => 'public#box#close#the#cash#register', "state" => 1],
        ];
      }
    };
    $listPermissions = $PermissionsRepositoryInMemory->all();
    $hasPermissionUseCase =  new HasPermissionUseCase();
    $this->assertTrue($hasPermissionUseCase->execute(['public#services#get#branches#all'], $listPermissions));
  }


  public function testValidaSeUsuarioTemPermisaoDeAcessoComMaisDeUmRecurso()
  {
    $PermissionsRepositoryInMemory = new class implements IPermissionsRepository {
      public function all()
      {
        return [
          ['id' => 1, 'router' => 'public#services#get#branches#all', "state" => 1],
          ['id' => 2, 'router' => 'public#services#update#service', "state" => 1],
          ['id' => 3, 'router' => 'public#box#close#the#cash#register', "state" => 1],
        ];
      }
    };
    $listPermissions = $PermissionsRepositoryInMemory->all();
    $hasPermissionUseCase =  new HasPermissionUseCase();
    $this->assertTrue($hasPermissionUseCase->execute(['public#services#get#branches#all','public#services#update#service'], $listPermissions));
  }

  public function testValidaSeUsuarioNaoemPermisaoDeAcesso()
  {
    $PermissionsRepositoryInMemory = new class implements IPermissionsRepository {
      public function all()
      {
        return [
          ['id' => 1, 'router' => 'public#services#get#branches#all', "state" => 1],
          ['id' => 2, 'router' => 'public#services#update#service', "state" => 1],
          ['id' => 3, 'router' => 'public#box#close#the#cash#register', "state" => 1],
        ];
      }
    };
    $listPermissions = $PermissionsRepositoryInMemory->all();
    $hasPermissionUseCase =  new HasPermissionUseCase();
    $this->assertFalse($hasPermissionUseCase->execute(['public#services#get#branches#alls'], $listPermissions));
  }





 

}



