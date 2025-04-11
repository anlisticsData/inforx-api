<?php
 
use Commons\ResponseJson;
use Resources\HttpStatus;
 
 


require_once "../core/Settings.php";
require_once __DIR__ . "./../core/Settings.php";
try {
         new ResponseJson(HttpStatus::$HTTP_CODE_NO_CONTENT);
} catch (Exception $e) {
    new ResponseJson($e->getCode(), $e->getMessage());
}
