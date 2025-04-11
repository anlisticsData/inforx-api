<style>

    
    pre {
        background-color: #ccc;
        display: inline;
        padding: 1em;
        margin: 1em;
        float: left;
        max-width: 25%;
        word-break: break-word;
        height: 5em;
        padding: 5px;
        border: 1px solid;
        display: table;
        max-width: 100%;

    }
</style>

<?php

/*
    Demo de Uso da Class
    Author:edilson Claudino da Silva
    Email:edilsonclaudinosulva@gmail.com
    Criado Em: 07/04/2024  
    Versão : v.1
 */

use AnalisticsData\ClockSystem;


require_once __DIR__ . "/vendor/autoload.php";

ClockSystem::TimeZone();
echo "<pre>";
print_r(ClockSystem::StringToDate("04-11-2024"));
echo "<hr>";
echo "</pre>";
echo "<pre>";
print_r(ClockSystem::StringToDate("1982-01-25"));
echo "<hr>";
echo "</pre>";

echo "<pre>";
print_r(ClockSystem::StringToDateTime('2023-02-19 15:02:15'));
echo "<hr>";
echo "</pre>";


echo "<pre>";
print_r(ClockSystem::diff('2023-02-18 14:02:15', '2023-02-19 15:02:15', 'Y-m-d H:i:s'));
echo "<hr>";
echo "</pre>";


echo "<pre>";
print_r(ClockSystem::NowTime());
echo "<hr>";
echo "</pre>";

echo "<pre>";
print_r(ClockSystem::NowDate());
echo "<hr>";
echo "</pre>";

echo "<pre>";
print_r(ClockSystem::NowDateTimeToStrToTimer());
echo "<hr>";
echo "</pre>";
