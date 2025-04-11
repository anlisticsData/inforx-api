<?php

namespace Interfaces\Box;

use Models\Box;


Interface IBoxRepository{
    function openBox(Box $box);
    function closeBox(Box $box);
    function isOpenBox($date,$branchCode);
    function isCloseBox($date,$branchCode);

    function by($date,$branchCode);

}