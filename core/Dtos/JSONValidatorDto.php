<?php
namespace Dtos;

use Exception;



class JSONValidatorDto {
    // Valida e transforma o JSON recebido
    public static function validateAndTransform($jsonString) {
    
        // Tenta decodificar o JSON
        $data = json_decode($jsonString, true);
  
        // Verifica se o JSON é válido
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("JSON inválido: " . json_last_error_msg());
        }
  
        // Verifica a estrutura do JSON
        $requiredKeys = ["host", "user", "pwd", "port", "base"];
        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Exception("Chave obrigatória ausente: $key");
            }
        }
  
        // Retorna os dados validados
        return $data;
    }
  }


  