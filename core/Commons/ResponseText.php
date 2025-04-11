<?php


namespace Commons;


class ResponseText{
    function __construct($status=200,$data,$bundle=[])
    {
        header("HTTP/1.1 ".$status);
        header('Content-Type: application/text');
        echo($data);
        exit();
    }
    
}