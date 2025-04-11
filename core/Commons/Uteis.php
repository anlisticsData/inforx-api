<?php

namespace  Commons;

use DateTime;
use Exception;
use Resources\Strings;

class Uteis
{
    private function __construct() {}
    private function __clone() {}

    public  static function generationCode($sizeCode = 6)
    {
        $numbers = [];
        for ($next = 0; $next < $sizeCode; $next++) {
            $numbers[] = rand(0, 9);
        }
        return $numbers;
    }


    static function removeSeconds($datetime)
    {
        return substr($datetime, 0, -3);
    }

    static function addDaysToDate($date, $days, $format = 'Y-m-d H:i:s')
    {
        $dateTime = new DateTime($date); // Converte para DateTime
        $dateTime->modify("+$days days"); // Adiciona os dias
        return $dateTime->format($format); // Retorna a nova data formatada
    }


    static  function isFirstTimeGreater($time1, $time2)
    {
        // Convert times to timestamps
        $timestamp1 = strtotime($time1);
        $timestamp2 = strtotime($time2);

        // Check if the first time is greater than the second
        if ($timestamp1 > $timestamp2) {
            return true; // The first time is greater
        } else {
            return false; // The first time is NOT greater
        }
    }

    static function validateTime($time)
    {
        // Split the time into hours and minutes
        list($h, $m) = explode(":", $time);

        // Check if the hours and minutes are valid
        if ($h >= 0 && $h <= 23 && $m >= 0 && $m <= 59) {
            return true;
        } else {
            return false;
        }
    }

    static function cleanToken($token)
    {
        // Remove o "Bearer " (caso exista) e espaços ou quebras de linha
        $token = str_replace('Bearer ', '', $token);

        // Remove quebras de linha e espaços extras
        $token = preg_replace('/\s+/', '', $token);

        return $token;
    }


    static function validateSchedule($time)
    {
        $date = DateTime::createFromFormat('H:i', $time);
        // Verifica se o formato foi corretamente interpretado
        if ($date && $date->format('H:i') === $time) {
            return true;  // Formato válido
        }
        return false;  // Formato inválido
    }


    static function validateScheduleHMS($time)
    {
        $date = DateTime::createFromFormat('H:i:s', $time);
        // Verifica se o formato foi corretamente interpretado
        if ($date && $date->format('H:i:s') === $time) {
            return true;  // Formato válido
        }
        return false;  // Formato inválido
    }
    static function separateDateTime($dataHora)
    {
        // Separar a data e a hora
        list($data, $hora) = explode(" ", $dataHora);

        // Separar ano, mês e dia
        list($ano, $mes, $dia) = explode("-", $data);

        // Separar horas, minutos e segundos
        list($horas, $minutos, $segundos) = explode(":", $hora);

        // Retornar os valores separados em um array
        return [
            "ano" => (int) $ano,
            "mes" => (int) $mes,
            "dia" => (int) $dia,
            "horas" => (int) $horas,
            "minutos" => (int) $minutos,
            "segundos" => (int) $segundos,
        ];
    }





    public static function ip()
    {
        return $_SERVER["REMOTE_ADDR"];
    }

    public static function dateSplit($dataReceive, $type = 0, $separator = " ")
    {
        $strArray =  explode($separator, $dataReceive);
        return ($type == 0) ? $strArray[0] :  $strArray[1];
    }


    public static  function Uuid()
    {
        return sha1(uniqid());
    }


    public static function gerarPlacaCarro($separar = false)
    {
        // Gerando as três primeiras letras (A-Z)
        $letras = '';
        for ($i = 0; $i < 3; $i++) {
            $letras .= chr(rand(65, 90)); // 65-90 são os valores ASCII das letras maiúsculas
        }

        // Gerando os números e a quarta letra
        $numeros = rand(0, 9); // Primeiro número (1 dígito)
        $letra_meio = chr(rand(65, 90)); // Letra do meio
        $numeros .= rand(0, 9) . rand(0, 9); // Dois últimos números

        // Verificando se o usuário deseja separar letras de números
        if ($separar) {
            return [
                'letras' => $letras,
                'numero_letra' => $numeros . $letra_meio
            ];
        }

        // Combinando no formato ABC1D23
        return $letras . $numeros . $letra_meio;
    }





    public static  function generated($number = 1)
    {
        $numbers = [];
        for ($next = 0; $next < $number; $next++) {
            $numbers[] = rand(0, 9);
        }
        return implode("", $numbers);
    }



    public static function  generateCPF()
    {
        // Gera os primeiros 9 dígitos do CPF
        $cpf = [];
        for ($i = 0; $i < 9; $i++) {
            $cpf[] = rand(0, 9);
        }

        // Cálculo do primeiro dígito verificador
        $soma = 0;
        for ($i = 0, $peso = 10; $i < 9; $i++, $peso--) {
            $soma += $cpf[$i] * $peso;
        }
        $resto = $soma % 11;
        $cpf[9] = ($resto < 2) ? 0 : 11 - $resto;

        // Cálculo do segundo dígito verificador
        $soma = 0;
        for ($i = 0, $peso = 11; $i < 10; $i++, $peso--) {
            $soma += $cpf[$i] * $peso;
        }
        $resto = $soma % 11;
        $cpf[10] = ($resto < 2) ? 0 : 11 - $resto;

        // Retorna o CPF formatado
        return implode('', $cpf);
    }





    public static function passwordSha1($password)
    {
        return sha1($password);
    }

    public static function isNullOrEmpty($input)
    {
        try {
            if (is_null($input) || !is_scalar($input)) return true;
            if (is_string($input) && strlen(trim($input)) == 0) return true;
        } catch (Exception $e) {
            return true; // Em caso de erro, considera como vazio
        }
        return false;
    }


    public static function Split($input, $separator)
    {
        return explode($separator, $input);
    }

    public static function formatkeyAndRegistration($input, $size = 16)
    {
        return  str_pad($input, $size, '0', STR_PAD_LEFT);
    }

    public static function formatInputToSizeZero($input, $size = 16)
    {
        return  str_pad($input, $size, '0', STR_PAD_LEFT);
    }



    public static function transformScriptFileToAccess($scriptFileName = null)
    {

        if (is_null($scriptFileName)) {
            $scriptFileName = $_SERVER["SCRIPT_FILENAME"];
        }
        $access = substr($scriptFileName, strpos($scriptFileName, "public"));
        $access = str_replace(".php", "", $access);
        $access = str_replace("-", "#", $access);
        $access = str_replace("_", "#", $access);
        $access = str_replace("__", "#", $access);
        $access = str_replace("/", "#", $access);
        $access = str_replace("\\", "#", $access);
        return $access;
    }


    public static function parserDate($data)
    {
        $dataSplit =  explode("/", $data);
    }


    public static function StringInHoursMinute($strTime)
    {
        try {
            $split =  explode(":", $strTime);
            if (count($split) == 2) {
                return [
                    "hours" => intval($split[0]),
                    "minutes" => intval($split[1])
                ];
            }
        } catch (Exception $e) {
        }

        return [
            "hours" => 0,
            "minutes" => 0
        ];
    }

    public static function dd($obj, $html = false)
    {
        if ($html) {
            var_dump([$obj]);
            die;
        } else {
            header("HTTP/1.1 200");
            header('Content-Type: application/json');
            echo (json_encode($obj));
            exit();
        }
        die;
    }


    public static function formatNumber($valor, $decimal = 2)
    {
        // Remover vírgulas, se existirem
        $valor = str_replace(',', '', $valor);

        // Converter o valor para float
        $valor = (float) $valor;

        // Retornar o valor formatado com 2 casas decimais
        return number_format($valor, $decimal, ',', '.');  // Exemplo de formatação com vírgula como separador de decimais
    }



    public static function extractNumbers($string)
    {
        return preg_replace('/\D/', '', $string);
    }
}
