<?php
namespace Interfaces\States;

interface IStateRepository{
   function records();
   function All();
   function By($stateId);
}