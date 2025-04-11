<?php
namespace Commons;

use DateTime;

class DateTimeCalculator
{
    /**
     * Calcula a diferença em horas e minutos entre duas datas.
     *
     * @param string $dateTime1 A primeira data no formato "Y-m-d H:i:s".
     * @param string $dateTime2 A segunda data no formato "Y-m-d H:i:s".
     * @return array Um array com as chaves 'hours' e 'minutes'.
     */
    public static function calculateDifference(string $dateTime1, string $dateTime2): array
    {
        // Criar objetos DateTime a partir das strings
        $dt1 = new DateTime($dateTime1);
        $dt2 = new DateTime($dateTime2);

        // Calcular a diferença
        $interval = $dt1->diff($dt2);

        // Retornar o resultado em horas e minutos
        return [
            'hours' => ($interval->days * 24) + $interval->h,
            'minutes' => $interval->i
        ];
    }


   public static function convertToHours($hours, $minutes) {
        $hoursDecimals = $hours + ($minutes / 60);
        $hoursCalculated =number_format((($hoursDecimals * 100) /100),2,'.','');
       
        return ( $hoursCalculated);
    }


   public static function validateDate($date) {
        preg_match_all('/\d+/',$date, $matches);
        if(is_array($matches) && count($matches[0]) !=3) return false;
        $matches=$matches[0];
        $date =sprintf("%s-%s-%s",$matches[2],$matches[1],$matches[0]);
       
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return false;
        }
        // Divide a string em partes: ano, mês e dia
        list($ano, $mes, $dia) = explode('-', $date);
        // Verifica se é uma data válida
        return checkdate((int)$mes, (int)$dia, (int)$ano);
    }

    public static function dateParser($date) {
        preg_match_all('/\d+/',$date, $matches);
        if(is_array($matches) && count($matches[0]) !=3) return false;
        $matches=$matches[0];
        return sprintf("%s-%s-%s",$matches[2],$matches[1],$matches[0]);
    }


    static  function isDateLessThan($data1, $data2) {
        // Criando os objetos DateTime
        $data1 = new DateTime($data1);
        $data2 = new DateTime($data2);
    
        // Comparando as duas datas
        if ($data1 <= $data2) {
            return true; // A data1 é menor que a data2
        } else {
            return false; // A data1 não é menor que a data2
        }
    }

    



}
 
