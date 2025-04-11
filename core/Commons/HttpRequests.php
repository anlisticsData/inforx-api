<?php

namespace Commons;

use Interfaces\IHttpRequest;

class HttpRequests implements IHttpRequest {
    private function __clone() {}
    private function __construct() {}

    private static function mergeRequestData(array &$buffer, array $data) {
        foreach ($data as $key => $value) {
            $buffer[$key] = $value;
        }
    }

    public static function Init() {
        return self::getRequestData();
    }

    public static function Requests() {
        return self::getRequestData();
    }

    private static function getRequestData() {
        header('Content-Type: application/json; charset=utf-8');
        
        $buffer = [];
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $json = file_get_contents('php://input');
            $httpData = json_decode($json, true);
            if (is_array($httpData)) {
                self::mergeRequestData($buffer, $httpData);
            }
        }
        
        self::mergeRequestData($buffer, $_POST);
        self::mergeRequestData($buffer, $_GET);
        self::mergeRequestData($buffer, $_FILES);
        self::mergeRequestData($buffer, $_SERVER);
        self::mergeRequestData($buffer, getallheaders());
        
        return $buffer;
    }

    public static function requestJSON() {
        header('Content-Type: application/json; charset=utf-8');
        
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            return json_decode(file_get_contents('php://input'), true);
        }
        
        return json_decode($_SERVER["HTTP_CONTENTJSON"] ?? "{}", true);
    }

    public static function requestPOST() {
        return $_POST;
    }

    public static function requestPOSTFILES() {
        return $_FILES;
    }

    public static function requestGET() {
        return $_GET;
    }
}
