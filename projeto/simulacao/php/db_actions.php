<?php
require_once("db_connection.php");


function insert_data($name, $age) {
    $connection = connect_to_db();
    if ($connection) {
        $query = "INSERT INTO users (name, age) VALUES (?, ?)";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("si", $name, $age); // "s" para string, "i" para inteiro
        
        if ($stmt->execute()) {
            echo "Dados inseridos com sucesso! " . $stmt->affected_rows . " linha(s) afetada(s).";
        } else {
            echo "Erro: " . $stmt->error;
        }
        
        $stmt->close();
        $connection->close();
    }
}

function select_data() {
    $connection = connect_to_db();
    if ($connection) {
        $query = "SELECT codigo, placa, data FROM movimentoscameras";
        $stmt = $connection->prepare($query);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            echo "Dados da tabela 'movimentoscameras':<br>";
            
            while ($row = $result->fetch_assoc()) {
                echo "ID: {$row['codigo']}, Placa: {$row['placa']}, Data: {$row['data']}<br>";
            }
        } else {
            echo "Erro: " . $stmt->error;
        }

        $stmt->close();
        $connection->close();
    }
}

function insert_movimentoscameras($nsr, $status, $data, $hora, $nuvem, $codigosensor, $portatirasensor, $placa) {
    $connection = connect_to_db();
    
    if ($connection) {
        $query = "INSERT INTO movimentoscameras (nsr, status, data, hora, nuvem, codigosensor, portatirasensor, placa, created_at, update_at) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $current_time = date('Y-m-d H:i:s');
        $stmt = $connection->prepare($query);
        $stmt->bind_param("issssiiiss", $nsr, $status, $data, $hora, $nuvem, $codigosensor, $portatirasensor, $placa, $current_time, $current_time);
        
        if ($stmt->execute()) {
            echo "Inserido com sucesso! " . $stmt->affected_rows . " linha(s) afetada(s).";
        } else {
            echo "Erro: " . $stmt->error;
        }
        
        $stmt->close();
        $connection->close();
    }
}

function contar_placa($placa, $data) {
    $connection = connect_to_db();
    
    if ($connection) {
        $query = "SELECT * FROM movimentoscameras WHERE placa = ? AND data = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("ss", $placa, $data);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            $connection->close();
            return $rows;
        } else {
            echo "Erro: " . $stmt->error;
        }

        $stmt->close();
        $connection->close();
    }
    
    return []; // Retorna um array vazio em caso de erro
}


function localizar_placa_movimento($placa) {
    $connection = connect_to_db();
    if ($connection) {
        $query = "SELECT * FROM movements 
                  WHERE `park_vehicle_plate` = ? 
                  AND YEAR(`created_at`) = YEAR(NOW()) 
                  AND MONTH(`created_at`) = 3 
                  AND DAY(`created_at`) = 27 
                  AND branches_id = 29 
                  AND park_date_departure IS NULL";

        $stmt = $connection->prepare($query);
        if (!$stmt) {
            echo "Erro na preparação da query: " . $connection->error;
            return [];
        }

        $stmt->bind_param("s", $placa);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            $connection->close();
            return $rows;
        } else {
            echo "Erro na execução da query: " . $stmt->error;
        }

        $stmt->close();
        $connection->close();
    }

    return []; // Retorna um array vazio em caso de erro
}


