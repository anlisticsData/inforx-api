<?php

namespace UseCases\Core;


class PlateAndValidUseCase
{
    public function execute($input)
    {
        $placa = strtoupper($input);
        $placa = preg_replace('/[^A-Z0-9]/', '', $placa);

        if (empty($placa)) {
            throw new \Exception("A placa não pode estar vazia.");
        }

        // Verifica se há pelo menos duas letras
        $letras = preg_match_all('/[A-Z]/', $placa);
        if ($letras < 2) {
            throw new \Exception("A placa deve conter no mínimo duas letras.");
        }

        // Valida padrão Mercosul: LLL1L11
        if (preg_match('/^[A-Z]{3}[0-9][A-Z][0-9]{2}$/', $placa)) {
            return 1; // válida
        } else {
           return 0;
        }
    }
}
