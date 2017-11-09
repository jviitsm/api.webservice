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


    $email = $request->getParam('email');
    $entityManager = $this->get('em');


    $loginRepository = $entityManager->getRepository('App\Models\Entity\Login');
    $existeEmail = $loginRepository->findBy(array('email' => $email));

    if ($existeEmail) {
        return $response->withStatus(202);
    } else {
        return $response->withStatus(204);
    }

});
$app->post('/verificar/login', function (Request $request, Response $response) use ($app) {

    $login = $request->getParam('login');

    $entityManager = $this->get('em');

    $loginRepository = $entityManager->getRepository('App\Models\Entity\Login');
    $existeLogin = $loginRepository->findBy(array('login' => $login));

    if ($existeLogin) {
        return $response->withStatus(202);
    } else {
        return $response->withStatus(204);
    }

});
$app->post('/retorno', function (Request $request, Response $response) use ($app) {

    $id_login = $request->getParam('id_login');

    $entityManager = $this->get('em');
    $usuarioRepository = $entityManager->getRepository('App\Models\Entity\Cidadao');
    $usuario = $usuarioRepository->findBy(array("fk_login_cidadao" => $id_login));

    if ($usuario) {
        $usuarioRetorno[] = array();
        foreach ($usuario as $usuarios) {
            $usuarioRetorno = ["cidadão" => array("id_cidadao" => $usuarios->id_cidadao, "nome" => $usuarios->nome,
                "sobrenome" => $usuarios->sobrenome, "sexo" => $usuarios->sexo, "estado" => $usuarios->estado,
                "cidade" => $usuarios->cidade, "dir_foto_usuario" => $usuarios->dir_foto_usuario)];
        }
        return $response->withJson($usuarioRetorno, 222)->withHeader('Content-type', 'application/json');
    }

    $empresaRepository = $entityManager->getRepository('App\Models\Entity\Empresa');
    $empresa = $empresaRepository->findBy(array("fk_login_empresa" => $id_login));

    if ($empresa) {
        $empresaRetorno[] = array();
        foreach ($empresa as $empresas) {
            $empresaRetorno = ["empresa" => array("id_empresa" => $empresas->id_empresa, "nome_fantasia" => $empresas->nome_fantasia,
                "cnpj" => $empresas->cnpj, "razao_social" => $empresas->razao_social, "estado" => $empresas->estado,
                "cidade" => $empresas->cidade, "dir_foto_usuario" => $empresas->dir_foto_usuario)];
        }
        return $response->withJson($empresaRetorno, 223)->withHeader('Content-type', 'application/json');
    } else {
        return $response->withJson(0, 404)->withHeader('Content-type', 'application/json');
    }

});
$app->run();

