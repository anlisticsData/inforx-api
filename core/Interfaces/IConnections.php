<?php
namespace Interfaces;

interface IConnections{
    function query($sql,$parameters=null,$returnList=true);
    function execute($sql,$parameters=null);
    function executeRowsCount($sql,$parameters=null);
    function executeAutoIncrement($sql,$parameters=null);
    function close();
}