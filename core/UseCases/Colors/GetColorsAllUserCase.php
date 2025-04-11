<?php


namespace UseCases\Colors;

 


class GetColorsAllUserCase{
    private  $colorsRepository;
    public function __construct($colorsRepository){
        $this->colorsRepository = $colorsRepository;
    }     
    public function execute(){
        return $this->colorsRepository->all();
    }

}


