<?php

use Commons\Uteis;
use Commons\HttpRequests;
use Commons\ResponseJson;
use Dtos\MonthlyPayerDto;
use Resources\HttpStatus;
use Middleware\Authorization;
use Requests\MonthlyPayerUpdateRequest;
use Repositories\Branches\BranchesRepository;
use UseCases\Monthlypayers\MonthlyPayerUpdateUseCase;
use Repositories\MonthlyPayers\MonthlyPayersRepository;
use UseCases\Monthlypayers\ReserveAPlaceForTheMonthlyMemberUseCase;

 
require_once __DIR__."./../../core/Settings.php";
try {
   
    Authorization::Init();
    
    $userData = Authorization::playload();
    $request =  new MonthlyPayerUpdateRequest(HttpRequests::Requests());
    
    $MonthlyPayersRepositoryContex=new MonthlyPayersRepository();

    

    
    $BranchRepository =  new BranchesRepository();

    $MonthlyPayerUseCase = new MonthlyPayerUpdateUseCase(
        $BranchRepository,
        $MonthlyPayersRepositoryContex,
        new ReserveAPlaceForTheMonthlyMemberUseCase($MonthlyPayersRepositoryContex)
    );
    $MonthlyPayerDto=new MonthlyPayerDto($request->toArray());
    $MonthlyPayerDto->fk_curtomers=$userData["customer"];
    $MonthlyPayerDto->fk_branch=$userData["branch"];
    if($MonthlyPayerUseCase->execute($MonthlyPayerDto)){
        new ResponseJson(HttpStatus::$HTTP_CODE_CREATED,null);
    }
} catch (Exception $e) {
    
    new ResponseJson($e->getCode(), $e->getMessage());
}
