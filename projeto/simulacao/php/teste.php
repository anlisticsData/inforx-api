<?php

require_once("db_actions.php");
echo "<pre>";

$placa = "DZH5E31";
$data = "20250327";
$registros = localizar_placa_movimento($placa);
if (!empty($registros)) {




    print_r($registros);


}

//print_r(contar_placa("EAA2F56","20250327"));
/*

$placa = "DZH5E31";
$data = "20250327";


$registros = contar_placa($placa, $data);

if (!empty($registros)) {
    $pares = array_chunk($registros, 2); // Divide em grupos de 2 elementos

    foreach ($pares as $indice => $par) {
        echo "Par " . ($indice + 1) . ":\n";

        if (count($par) == 2) {
            $data1 = strtotime($par[0]['created_at']);
            $data2 = strtotime($par[1]['created_at']);
            $diferenca = abs($data2 - $data1); // Diferença em segundos

            echo "Código 1: {$par[0]['codigo']}, Placa: {$par[0]['placa']}, Data: {$par[0]['data']}, Criado em: {$par[0]['created_at']}\n";
            echo "Código 2: {$par[1]['codigo']}, Placa: {$par[1]['placa']}, Data: {$par[1]['data']}, Criado em: {$par[1]['created_at']}\n";

            echo "Diferença de tempo: " . gmdate("H:i:s", $diferenca) . " (hh:mm:ss)\n";
        } else {
            // Caso o último par tenha apenas um item
            echo "Código: {$par[0]['codigo']}, Placa: {$par[0]['placa']}, Data: {$par[0]['data']}, Criado em: {$par[0]['created_at']}\n";
            echo "Sem par para calcular a diferença de tempo.\n";
        }

        echo "----------------------\n";
    }
} else {
    echo "Nenhum registro encontrado.\n";
}
*/
