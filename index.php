<?php 

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


use App\Models\Entity\Login;
use App\Models\Entity\Cidadao;


require 'bootstrap.php';


//Rotas
require '/Routes/Cidadao.php';



$app->run();