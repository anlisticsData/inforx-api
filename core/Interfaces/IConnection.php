<?php
namespace Interfaces;

interface IConnection{
    function getInstance();
    function close();
}