<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'bootstrap.php';

//Rotas dos clientes
require 'Routes/CidadaoRoutes.php';
require 'Routes/EmpresaRoutes.php';
require 'Routes/LoginRoutes.php';
require 'Routes/DenunciaRoutes.php';
require 'Routes/ComentarioRoutes.php';




 # HTTP Auth - Autenticação minimalista para retornar um JWT


$app->get('/auth', function (Request $request, Response $response) use ($app) {


    return $response->withJson(["status" => "Autenticado!"], 200)
        ->withHeader('Content-type', 'application/json');
});







$app->run();