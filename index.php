<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\JWT;
use PHPMailer\PHPMailer\PHPMailer;
use App\Models\Entity\Login;

require 'bootstrap.php';

//Rotas dos clientes
require 'Routes/CidadaoRoutes.php';
require 'Routes/EmpresaRoutes.php';
require 'Routes/LoginRoutes.php';
require 'Routes/DenunciaRoutes.php';
require 'Routes/ComentarioRoutes.php';
require 'Routes/AgilizaRoutes.php';
require 'Routes/RecuperarSenhaRoute.php';


/**
 * HTTP Auth - Autenticação minimalista para retornar um JWT
 */
$app->get('/auth', function (Request $request, Response $response) use ($app) {

    $key = $this->get("secretkey");

    $now = new DateTime();
    $future = new DateTime("now +1 hour");
    $ipAddress = $request->getAttribute('ip_address');
    $user = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];


    $token = array(
        "user" => $user,
        "senha" => $password,
        "exp" => $future->getTimeStamp(),
        "ip" => $ipAddress

    );

    $jwt = JWT::encode($token, $key);

    return $response->withJson(["token" => $jwt], 200)
        ->withHeader('Content-type', 'application/json');


});

$app->post('/imagem/user', function (Request $request, Response $response) use ($app) {

    $files = $request->getUploadedFiles();
    $newimage = $files['Profile_photo'];

    if ($newimage->getError() === UPLOAD_ERR_OK) {
        $uploadFileName = $newimage->getClientFilename();
        $type = $newimage->getClientMediaType();
        $name = uniqid('img-' . date('d-m-y') . '-');
        $name .= $newimage->getClientFilename();
        //  $imgs[] = array('url' => '/Photos/' . $name);

        //local server

        $newimage->moveTo("/home/citycare/Imgs/User/$name");

        //localdev
        $photoURL = "/home/citycare/Imgs/User/$name";

        return $response->withJson($photoURL, 201);
    }

});

$app->post('/verificar/email', function (Request $request, Response $response) use ($app) {

    $fk_login_cidadao = $request->getParam('fk_login_cidadao');
    $email = $fk_login_cidadao[email];
    $entityManager = $this -> get('em');

    $existeEmail = $entityManager->createQuery("SELECT l from App\Models\Entity\Login l WHERE l.email = :email");
    $existeEmail->setParameters(':email', $email);

    if($existeEmail){
        return $response->withJson(1, 201);
    }
    else{
        return $response->withJson(1,404);
    }



});
$app->run();

