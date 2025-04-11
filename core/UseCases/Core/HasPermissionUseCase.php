<?php

namespace UseCases\Core;


class HasPermissionUseCase {

    public function __construct()
    {
        // Constructor logic if needed
    }


  
    
    public function execute(array $permissions, array $listPermissions): bool
    {
        foreach ($permissions as $permission) {
            if (!in_array($permission, array_column($listPermissions, 'router'))) {
                return false;
            }
        }
        return true;
    }
   

}