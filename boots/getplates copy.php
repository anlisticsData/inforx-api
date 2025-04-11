<?php

// Definir as URLs da API e dados de autenticação
$url_authenticate = "/users/authenticate";
$url_check_entrances = "/car/queries/check-entrances-and-exits";
$boots=[
    array("login"=>"consultaplacas@boots.com","password"=>"123")
];






// Supondo que você tenha os dados do arquivo .env
$env_vars = parse_ini_file(getcwd() . '/core/.env');
$MODULE_PARKING_BOOT = explode("|", $env_vars['MODULE_PARKING_BOOT']);
$url_api = $MODULE_PARKING_BOOT[3];
// Função para montar a URL completa da API
function Api($resource_api) {
    global $url_api;
    return $url_api . $resource_api;
}
$search=0;
while(true){

    foreach($boots as $bootsCredentials){
        print_r(sprintf("Pesquisando Boot:%s\n",$bootsCredentials['login']));
        try{
            // Dados de login
            $login_data = $bootsCredentials;
            // Enviar a requisição de autenticação
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, Api($url_authenticate));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($login_data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
            ]);
            $response = curl_exec($ch);
            curl_close($ch);
            if ($response !== false) {
                $response_data = json_decode($response, true);
                if (isset($response_data['data']['token'])) {
                    $token = $response_data['data']['token'];
                   // echo "Token de autenticação obtido com sucesso! = " . $token . "\n";
                    
                    // Decodificar o payload do JWT
                    $decoded_payload = json_decode(base64_decode(explode('.', $token)[1]), true);
                    
                    // Requisição para o endpoint /car/queries/check-entrances-and-exits a cada 30 segundos
                    $headers = [
                        "User-Authorization: $token",
                        "Content-Type: application/json"
                    ];
                        try {
                            $input = [
                                'module' => $MODULE_PARKING_BOOT[0],
                                'customer' => $decoded_payload['customer'],
                                'branch' => $decoded_payload['branch']
                            ];
                            
                            // Fazer a requisição a cada 30 segundos
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, Api($url_check_entrances));
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($input));
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                            $check_response = curl_exec($ch);
                            curl_close($ch);
                            
                            // Verificar o status da resposta
                            if ($check_response !== false) {
                                $check_response_data = json_decode($check_response, true);
                            //    echo "Consulta realizada com sucesso!\n";
                                // Você pode fazer algo com a resposta aqui, como exibir o conteúdo
                    //            print_r($check_response_data);  // Exibe a resposta JSON
                            } else {
                      //          echo "Erro na requisição para 'check-entrances-and-exits'.\n";
                            }
                        } catch (Exception $e) {
              //              echo "Erro ao realizar requisição: " . $e->getMessage() . "\n";
                        }
                } else {
                    echo "Token não encontrado na resposta de autenticação.\n";
                }
            } else {
                echo "Falha na autenticação.\n";
            }
        }catch(Exception $e){}
        finally{
            print_r("|-------------------------------------------------------------|\n\n");
            print_r(sprintf("Pesquisando Boot:%s -[ %s ]\n",$bootsCredentials['login'],$search));
            print_r("|-------------------------------------------------------------|\n\n");
            sleep(30);
            $search++;
        }

    }
}

?>
