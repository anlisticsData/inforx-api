<?php

namespace Interfaces\MovementCameras;

use Dtos\MovementCameraDto;

interface IMovimentCameras{

    function created(MovementCameraDto $movimentCamera);
    function findByUuid($uuid);
    function maxRemoteRef();
    function allProcessedFalse($limit=10);
    function processedTrue($uuid);




}