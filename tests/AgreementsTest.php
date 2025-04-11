<?php

use Dtos\AgreementDto;
use PHPUnit\Framework\TestCase;
use Repositories\Agreements\AgreementRepository;
use UseCases\Agreements\CreateAgreementUseCase;
 
require_once "vendor/autoload.php";
 
class AgreementsTest extends TestCase
{
    public function testCreate()
    {

        $input=[
            "name"=>uniqid(),
            "doc"=>uniqid(),
            "description"=>uniqid(),
            "address"=>uniqid(),
            "start"=>"00:00",
            "end"=>"02:00",
            "prices"=>10.00
        
        ];

        $createAgreementUseCase =new CreateAgreementUseCase(new AgreementRepository());
        $result=$createAgreementUseCase->execute(new AgreementDto($input));
        $this->assertGreaterThan(0,$result);
    }
}
