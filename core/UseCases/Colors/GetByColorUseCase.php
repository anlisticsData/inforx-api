<?php


namespace UseCases\Colors;

use Interfaces\Colors\IColorsRepository;

class GetByColorUseCase{
    private  IColorsRepository $colorsRepository;
    public function __construct(IColorsRepository $colorsRepository){
        $this->colorsRepository = $colorsRepository;
    }     
    public function execute($code){
        return $this->colorsRepository->one($code);
    }

}


