<?php 

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'bootstrap.php';

//Rotas dos clientes
require '/Routes/Cidadao.php';
require '/Routes/Empresa.php';

$app->run();