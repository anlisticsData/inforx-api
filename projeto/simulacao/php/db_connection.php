<?php
function connect_to_db() {
   
    if(false){
        $host = "162.214.76.190";
        $user = "inforparkuser";
        $password = "ASD7N#!a)k6a";
        $database = "InforPark_0011_0011";
    }else{
        $host = "127.0.0.1";
        $user = "root";
        $password = "root";
        $database = "estacionamento2";
    }

    $connection = new mysqli($host, $user, $password, $database);

    if ($connection->connect_error) {
        die("Falha na conexão: " . $connection->connect_error);
    }

    return $connection;
}
?>
