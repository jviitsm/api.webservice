<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\JWT;

require 'bootstrap.php';

//Rotas dos clientes
require 'Routes/CidadaoRoutes.php';
require 'Routes/EmpresaRoutes.php';
require 'Routes/LoginRoutes.php';
require 'Routes/DenunciaRoutes.php';
require 'Routes/ComentarioRoutes.php';

/**
 * HTTP Auth - Autenticação minimalista para retornar um JWT
 */
$app->get('/auth', function (Request $request, Response $response) use ($app) {
    $key = $this->get("secretkey");

    /*
    $now = new DateTime();
    $future = new DateTime("now +1 hour");

   $payload = [
        "user" => "jv",
        "passsword" => "jviits",
        "iat" => $now->getTimeStamp(),
        "nbf" => $future->getTimeStamp()
    ];*/
    $token = array(
        "user" => "CityCare",
        "senha" => "projetocitycare",
        "desenvolvedores" => "bruno e jv"
    );


    $jwt = JWT::encode($token,$key);


    return $response->withJson(["auth-jwt" => $jwt], 200)
        ->withHeader('Content-type', 'application/json');


});

$app->run();