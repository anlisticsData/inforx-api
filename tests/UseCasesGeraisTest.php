<?php

use Dtos\AgreementDto;
use PHPUnit\Framework\TestCase;
use UseCases\Core\PlateAndValidUseCase;
use UseCases\Agreements\CreateAgreementUseCase;
use Repositories\Agreements\AgreementRepository;

require_once "vendor/autoload.php";
/*

ABC1D23, JKL2E45, MNO3F67, PQR4G89, STU5H01,
VWX6I23, YZA7J45, BCD8K67, EFG9L89, HIJ0M01,
KLM1N23, NOP2O45, QRS3P67, TUV4Q89, WXY5R01,
ZAB6S23, CDE7T45, FGH8U67, IJK9V89, LMN0W01,
OPQ1X23, RST2Y45, UVW3Z67, XYZ4A89, ABD5B01,
CEF6C23, GHI7D45, JKL8E67, MNO9F89, PQR0G01,
STU1H23, VWX2I45, YZA3J67, BCD4K89, EFG5L01,
HIJ6M23, KLM7N45, NOP8O67, QRS9P89, TUV0Q01,
WXY1R23, ZAB2S45, CDE3T67, FGH4U89, IJK5V01,
LMN6W23, OPQ7X45, RST8Y67, UVW9Z89, XYZ0A01,
ABD1B23, CEF2C45, GHI3D67, JKL4E89, MNO5F01,
PQR6G23, STU7H45, VWX8I67, YZA9J89, BCD0K01,
EFG1L23, HIJ2M45, KLM3N67, NOP4O89, QRS5P01,
TUV6Q23, WXY7R45, ZAB8S67, CDE9T89, FGH0U01,
IJK1V23, LMN2W45, OPQ3X67, RST4Y89, UVW5Z01,
XYZ6A23, ABD7B45, CEF8C67, GHI9D89, JKL0E01,
MNO1F23, PQR2G45, STU3H67, VWX4I89, YZA5J01,
BCD6K23, EFG7L45, HIJ8M67, KLM9N89, NOP0O01,
QRS1P23, TUV2Q45, WXY3R67, ZAB4S89, CDE5T01,
FGH6U23, IJK7V45, LMN8W67, OPQ9X89, RST0Y01,
UVW1Z23, XYZ2A45, ABD3B67, CEF4C89, GHI5D01,
JKL6E23, MNO7F45, PQR8G67, STU9H89, VWX0I01,
YZA1J23, BCD2K45, EFG3L67, HIJ4M89, KLM5N01


*/




class UseCasesGeraisTest extends TestCase
{
    public function testSeAplacaEvalida()
    {
        $placa = "ABC1D23";
        $input = $placa;
        $plateAndValidUseCase = new PlateAndValidUseCase();
        $result = $plateAndValidUseCase->execute($input);
        $this->assertEquals(1, $result);
    }


    public function testSeAplacaEinvalida()
    {
        $placa = "XXXXXXXX";
        $input = $placa;
        $plateAndValidUseCase = new PlateAndValidUseCase();
        $result = $plateAndValidUseCase->execute($input);
        $this->assertEquals(0, $result);
    }

    public function testSeAplacaConten3LetrasMineNumeros()
    {
        $placa = "ABD7B45";
        $input = $placa;
        $plateAndValidUseCase = new PlateAndValidUseCase();
        $result = $plateAndValidUseCase->execute($input);
        $this->assertEquals(1, $result);
    }

    public function testFalhaSeAplacaNaoConten3LetrasOuMineNumeros()
    {
        $placa = "AB7B45-";
        $input = $placa;
        $plateAndValidUseCase = new PlateAndValidUseCase();
        $result = $plateAndValidUseCase->execute($input);
        $this->assertEquals(0, $result);
    }


    public function testFalhaSeAplacaForVazia()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("A placa não pode estar vazia.");

        $useCase = new PlateAndValidUseCase();
        $useCase->execute("");
    }

    public function testFalhaSeAplacaTiverMenosDeDuasLetras()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("A placa deve conter no mínimo duas letras.");

        $useCase = new PlateAndValidUseCase();
        $useCase->execute("A-123");
    }
}
