<?php
namespace Middleware;

use Commons\HttpRequests;
use Exception;
use Commons\Jwt;
use Commons\Uteis;
use Resources\Strings;
use Resources\HttpStatus;
use Services\UserServices;

class Authorization {
    private static array $headers = ["Authorization", "User-Authorization"];

    private static function getToken(): ?string {
        $headersHttpRequest = [];
        
        foreach (getallheaders() as $header => $value) {
            if (in_array($header, self::$headers)) {
                $headersHttpRequest[$header] = trim($value);
            }
        }
        
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $headerFormatted = ucwords(strtolower(str_replace('_', ' ', str_replace('HTTP_', '', $key))));
                if (in_array($headerFormatted, self::$headers)) {
                    $headersHttpRequest[$headerFormatted] = trim($value);
                }
            }
        }

        if (empty($headersHttpRequest)) {
            $request = HttpRequests::Requests();
            if (isset($request['token'])) {
                return trim($request['token']);
            }
        }

        foreach (self::$headers as $header) {
            if (isset($headersHttpRequest[$header])) {
                return preg_replace('/^\s*Bearer\s+/i', '', $headersHttpRequest[$header]);
            }
        }

        throw new Exception(Strings::$STR_USER_TOKEN_INVALIDE, HttpStatus::$HTTP_CODE_UNAUTHORIZED);
    }

    public static function Init($show = false) {
        if ($show) {
            Uteis::dd(Uteis::transformScriptFileToAccess());
        }
        
        $token = self::getToken();
        $serviceUser = new UserServices();
        $serviceUser->ValidateUserAccess($token, Uteis::transformScriptFileToAccess());
    }

    public static function Token() {
        return Jwt::decode(self::getToken(), $_SERVER["TOKEN_UUID_INSTALL"]);
    }

    public static function CustomerId() {
        return self::Token()['customer'] ?? null;
    }

    public static function playload() {
        return self::Token();
    }

    public static function getBranchCode() {
        return self::Token()['branch'] ?? null;
    }

    public static function getUserCode() {
        return self::Token()['user'] ?? null;
    }
}
