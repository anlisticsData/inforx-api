<?php

namespace Interfaces\RecorevyUser;


interface IRecoveryUser{
    function createdRecovery($userCode,$codeGenetated);
    function codeValidate($codeGenetated,$codeUser);
    function delete($code);
    function by($code);

}