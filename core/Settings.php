<?php
date_default_timezone_set('America/Sao_Paulo');
error_reporting(1);
ini_set("display_errors", 1);
use Middleware\Cors;
require_once __DIR__."/vendor/autoload.php";
define('DEFAULT_WITHOUT_REGISTRATION', 'DEFAULT_WITHOUT_REGISTRATION');
define('DEFAULT_WITH_REGISTRATION', 'DEFAULT_WITH_REGISTRATION');
define('CUSTOM_MODE', 'CUSTOM_MODE');

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();
$_SERVER["TOKEN_UUID_INSTALL"]="44982c72-b09f-4a41-92db-4a8dc948e3a0";
Cors::Cors();