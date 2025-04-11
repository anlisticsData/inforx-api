<?php
namespace Interfaces\Citys;

interface ICityRepository{
   function records();
   function recordsPaginator($stateId);
   function All();
   function SearchCitiesAll($start=1,$sizeCity,$limit=15);
   function SearchCitiesByState($stateId);
   function SearchCitiesBy($stateId);

}