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

$app->post('/email', function (Request $request, Response $response) use ($app) {

         $entityManager = $this->get('em');

        //Atributos
        $id = $request->getParam('id_login');

        //Buscar login
        $loginRepository = $entityManager->getRepository('App\Models\Entity\Login');
        $login = $loginRepository->find($id);

        //Buscar email no login
        $email =  $login->getEmail();

        //Gerar senha randomica
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $novaSenha =  substr(str_shuffle($chars),0,8);

        //Criptografar senha randomica
        $login->setSenha($novaSenha);

        //Salvar nova senha no banco
        $entityManager->merge($login);
        $entityManager->flush();

        //Envio de e-mail
        $mail = new PHPMailer(true);
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Username = "projetocitycare@gmail.com";
        $mail->Password = "citycare123";
        $mail->setFrom($email, 'City Care');
        $mail->addAddress($email);
        $mail->Subject = 'Nova Senha';
        $mail->Body = $novaSenha;
        if (!$mail->send()) {

            return "Mailer Error: " . $mail->ErrorInfo;
        } else
        {
            $return = $response->withJson(1, 201);
        }
    return $return;
    });

$app->run();

