<?php


namespace Commons;


class ResponseJson{
    function __construct($status=200,$data=[],$bundle=[])
    {
        header("HTTP/1.1 ".$status);
        header('Content-Type: application/json');
        $error=!in_array($status,array(200,201));
        $response["status"]=$status;
        $response["error"]=$error;
        $response["api"]="";
        $response["data"]=$data;
        $response["bundle"]=$bundle;
        echo(json_encode($response));
        exit();
    }
    
}