<?php
namespace Dtos;
class OvernightDefinition {
    public $overnightStart;
    public $overnightEnd;

    public function __construct($overnightStart, $overnightEnd) {
        $this->overnightStart = $overnightStart;
        $this->overnightEnd = $overnightEnd;
    }
}
