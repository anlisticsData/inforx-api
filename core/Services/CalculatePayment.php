<?php

namespace Services;

use DateTime;
use Dtos\OvernightDefinition;


class CalculatePayment
{
    private function __construct() {}
    private function __clone() {}
    public static function calculateOvernight($start, $end, OvernightDefinition $overnightDefinition)
    {
        $overnightStart = $overnightDefinition->overnightStart;
        $overnightEnd = $overnightDefinition->overnightEnd;
        $startDateTime = new DateTime($start);
        $endDateTime = new DateTime($end);
        if ($startDateTime >= $endDateTime) {
            return null;
        }
        $totalOvernightTime = 0; // In seconds
        while ($startDateTime < $endDateTime) {
            $overnightStartTime = clone $startDateTime;
            $splitTime = explode(":", $overnightStart);
            $overnightStartTime->setTime($splitTime[0], $splitTime[1]); // 20:00 on the same day
            $overnightEndTime = clone $startDateTime;
            if ($startDateTime->format('H:i') >= $overnightEnd) {
                $overnightEndTime->modify('+1 day');
            }
            $splitTime = explode(":", $overnightEnd);
            $overnightEndTime->setTime($splitTime[0], $splitTime[1]); // 06:00 on the next day
            $intersectionStart = max($startDateTime, $overnightStartTime);
            $intersectionEnd = min($endDateTime, $overnightEndTime);
            if ($intersectionStart < $intersectionEnd) {
                $totalOvernightTime += $intersectionEnd->getTimestamp() - $intersectionStart->getTimestamp();
            }
            $startDateTime = $overnightEndTime;
        }
        $hours = floor($totalOvernightTime / 3600);
        $minutes = ($totalOvernightTime % 3600) / 60;
        return [
            'hours' => $hours,
            'minute' => $minutes
        ];
    }
}
