<?php
namespace Commons;

class ValidateInputs{
    private function __construct(){}
    private function __clone(){}
    
    function validateCnpj($cnpj){}
    public static function validateCnpjOrCpf($CnpjOrCpf){
          // Extrai os números
            $CnpjOrCpf = preg_replace('/[^0-9]/is', '', $CnpjOrCpf);

            if (strlen($CnpjOrCpf) > 14) {
                return false;
            }

            // Valida tamanho & Identifica se é CPF ou CNPJ
            if (strlen($CnpjOrCpf) == 11) {
                if (preg_match('/(\d)\1{10}/', $CnpjOrCpf)) {
                    return false;
                }

                for ($t = 9; $t < 11; $t++) {
                    // Inicializa variáveis de multiplicação e soma
                    $soma = 0;
                    $mult = $t + 1;
            
                    // Calcula a soma dos produtos
                    for ($i = 0; $i < $t; $i++) {
                        $soma += $CnpjOrCpf[$i] * $mult;
                        $mult--;
                    }
            
                    // Calcula o dígito verificador
                    $resto = $soma % 11;
                    $digito = ($resto < 2) ? 0 : (11 - $resto);
            
                    // Verifica o dígito verificador
                    if ($CnpjOrCpf[$t] != $digito) {
                        return false;
                    }
                }

                return true;
            }

            // Verifica sequência de digitos repetidos. Ex: 11.111.111/111-11
            if (preg_match('/(\d)\1{13}/', $CnpjOrCpf)) {
                return false;
            }

            // Valida dígitos verificadores
            for ($t = 12; $t < 14; $t++) {
                for ($d = 0, $m = ($t - 7), $i = 0; $i < $t; $i++) {
                    $d += $CnpjOrCpf[$i] * $m;
                    $m = ($m == 2 ? 9 : --$m);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($CnpjOrCpf[$i] != $d) {
                    return false;
                }
            }

            return true;
    }

    public static function clearCnpjOrCpf($CnpjOrCpf) {
        $CnpjOrCpf = preg_replace('/[^0-9]/is', '', $CnpjOrCpf);

        return $CnpjOrCpf;
    }
}