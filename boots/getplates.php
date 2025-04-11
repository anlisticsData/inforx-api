<?php

// Definir as URLs da API e dados de autenticação


$url_authenticate ="/car/queries/check-entrances-and-exits-local";
$boots=[
    array("login"=>"kkidsconsultaplacas@boots.com","password"=>"123")
];
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
                echo "OK.\n";
                print_r($response);
            } else {
                echo "Falha na autenticação.\n";
            }
        }catch(Exception $e){
            print_r($e);
        }
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
