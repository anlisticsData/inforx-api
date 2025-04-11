<?php

use Commons\Uteis;

require_once __DIR__ . "./../core/Settings.php";
?>



<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Api-inforcomp-services</title>

    <style>
        body {
            position: relative;
            width: 100vw;
            height: 100vh;
            box-sizing: "border-box";
            overflow: hidden;
           

            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;

        }
        .logo {
            min-width: 90%;
            overflow: hidden;
            width: 50%;
            object-fit: cover;
            border-radius: 7px;

        }

        .logo-container {
            padding: 1rem;
            position: relative;
            background-color: beige;
            width: 50%;
        }


        .main {
            position: relative;
            width: 30%;
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 0 12px  #000;
            overflow: hidden;
            padding: 1rem;
            box-sizing: border-box;
            border-radius: 7px;

        }

        .p-version{
            
            position: relative;
            display: block;
            width: 100%;
            padding: 1rem;
            text-align: right;
        }
    </style>

</head>

<body>

    <div class="main">

        <div class="logo-container">
            <img class="logo" src="https://www.inforcomp.com.br/themes/wc_agenciar3/images/logo.png" alt="">
        </div>

        <h1>Api de Serviço Inforcomp</h1>
        <h3>Tecnologis em pontos e estacionamentos</h3>
        <p class="p-version">Versão V.1.0.0</p>


    </div>



</body>

</html>