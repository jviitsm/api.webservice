<?php


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Models\Entity\Login;
use App\Models\Entity\Cidadao;
$app ->post('/cidadao/cadastrar', function(Request $request, Response $response) use ($app){
    //Container do EntityManager
    $entityManager = $this -> get('em');

    $json = $request->getParam('cidadao');
    $json = json_decode($json);


    try{
        //Instância da entidade Login
        $login = new Login();
        //recuperando o parâmetro objeto login do json
        $fk_login_cidadao = $json -> fk_login_cidadao;
        //setando valores do objeto login
        $login ->setLogin($fk_login_cidadao -> login);
        $login ->setEmail($fk_login_cidadao -> email);
        $login ->setSenha($fk_login_cidadao -> senha);
        $login ->setStatus_login($fk_login_cidadao -> status_login);
        $login ->setAsAdministrador($fk_login_cidadao -> administrador);

        //salvando login
        $entityManager->persist($login);
        $entityManager->flush();
        //buscando login recém salvo
        $loginRepository = $entityManager->getRepository('App\Models\Entity\Login');
        //pegando login
        $loginCidadao = $loginRepository->find($login->getId_login());
        //Salvar foto
        $files = $request->getUploadedFiles();
        $newimage = $files['foto'];
        if($newimage){
        if ($newimage->getError() === UPLOAD_ERR_OK) {
            $uploadFileName = $newimage->getClientFilename();
            $type = $newimage->getClientMediaType();
            $name = uniqid('img-' . date('d-m-y') . '-');
            $name .= $newimage->getClientFilename();
            //  $imgs[] = array('url' => '/Photos/' . $name);
            //local server
            $newimage->moveTo("/home/citycare/imgs/$name");#/home/citycare/Imgs/User/$name
            //localdev
            $photoURL = "http://projetocitycare.com.br/Imgs/User/$name";#/home/citycare/Imgs/User/$name
        }}
        else {
            if ($json->sexo = "masculino") {
                $photoURL = "http://projetocitycare.com.br/Imgs/User/Masculino.jpg";
            }
            else if ($json->sexo = "feminino") {

            $photoURL = "http://projetocitycare.com.br/Imgs/User/Feminino.jpg";}
        }



        //Instância da entidade Cidadao
        $cidadao = new Cidadao();
        //setando valores do objeto cidadao
        $cidadao ->setFk_login_cidadao($loginCidadao);
        $cidadao->setNome($json -> nome);
        $cidadao->setSexo($json -> sexo);
        $cidadao ->setSobrenome($json -> sobrenome);
        $cidadao ->setEstado($json -> estado);
        $cidadao ->setCidade($json -> cidade);
        $cidadao ->setDir_foto_usuario($photoURL);
        $entityManager->persist($cidadao);
        $entityManager->flush();
        //retornando confirmação do evento completo
        $return = $response->withStatus(201)->withHeader('Content-type', 'application/json');


    } catch (Exception $ex){
        //código e mensagem do erro
        throw new Exception($ex->getMessage(), $ex->getCode());
    }
    return $return;
});
//retornar Cidadão específico
$app->post('/cidadao/exibir', function(Request $request, Response $response) use ($app){
    $entityManager = $this->get('em');
    if(!$request->getParsedBody()){
        throw new Exception("Corpo de requisição vazio", 204);
    }
    try{
        $id = $request->getParam('id_cidadao');
        $cidadao = $entityManager->find('App\Models\Entity\Cidadao', $id);
        if(!$cidadao){
            throw new Exception("Cidadão não encontrado", 404);
        }
        $return = $response->withJson($cidadao, 200);
    }catch(Exception $ex){
        //Exception do banco
        throw new Exception($ex->getMessage(), $ex->getCode());
    }
    return $return;
});


$app->put('/cidadao/alterar', function (Request $request, Response $response) use ($app) {
    $entityManager = $this -> get('em');

    $json = $request->getParam('cidadao');
    $json = json_decode($json);


    try{
        //Instância da entidade Login
        $login = new Login();
        //recuperando o parâmetro objeto login do json
        $fk_login_cidadao = $json -> fk_login_cidadao;
        //setando valores do objeto login
        $login ->setLogin($fk_login_cidadao -> login);
        $login ->setEmail($fk_login_cidadao -> email);
        $login ->setSenha($fk_login_cidadao -> senha);
        $login ->setStatus_login($fk_login_cidadao -> status_login);
        $login ->setAsAdministrador($fk_login_cidadao -> administrador);

        //salvando login
        $entityManager->merge($login);
        $entityManager->flush();
        //buscando login recém salvo
        $loginRepository = $entityManager->getRepository('App\Models\Entity\Login');
        //pegando login
        $loginCidadao = $loginRepository->find($login->getId_login());
        //Salvar foto
        $files = $request->getUploadedFiles();
        $newimage = $files['foto'];
        if($newimage){
            if ($newimage->getError() === UPLOAD_ERR_OK) {
                $uploadFileName = $newimage->getClientFilename();
                $type = $newimage->getClientMediaType();
                $name = uniqid('img-' . date('d-m-y') . '-');
                $name .= $newimage->getClientFilename();
                //  $imgs[] = array('url' => '/Photos/' . $name);
                //local server
                $newimage->moveTo("/home/citycare/imgs/$name");#/home/citycare/Imgs/User/$name
                //localdev
                $photoURL = "http://projetocitycare.com.br/Imgs/User/$name";#/home/citycare/Imgs/User/$name
            }}
        else {
            if ($json->sexo = "masculino") {
                $photoURL = "http://projetocitycare.com.br/Imgs/User/Masculino.jpg";
            }
            else if ($json->sexo = "feminino") {

                $photoURL = "http://projetocitycare.com.br/Imgs/User/Feminino.jpg";}
        }



        //Instância da entidade Cidadao
        $cidadao = new Cidadao();
        //setando valores do objeto cidadao
        $cidadao ->setFk_login_cidadao($loginCidadao);
        $cidadao->setNome($json -> nome);
        $cidadao->setSexo($json -> sexo);
        $cidadao ->setSobrenome($json -> sobrenome);
        $cidadao ->setEstado($json -> estado);
        $cidadao ->setCidade($json -> cidade);
        $cidadao ->setDir_foto_usuario($photoURL);
        $entityManager->merge($cidadao);
        $entityManager->flush();
        //retornando confirmação do evento completo
        $return = $response->withStatus(201)->withHeader('Content-type', 'application/json');
    }catch(Exception $ex){
        throw new Exception($ex->getMessage(), $ex->getCode());
    }
    return $return;
});

$app->put('/cidadao/desativar', function (Request $request, Response $response) use ($app) {
    if(!$request->getParsedBody()){
        throw new Exception("Corpo de requisição vazio", 204);
    }
    else {
        $entityManager = $this->get('em');
        try {
            $loginRepository = $entityManager->getRepository('App\Models\Entity\Login');
            $login = $loginRepository->find($request->getParam('id_login'));
            $login->setStatus_login(false);
            $entityManager->merge($login);
            $entityManager->flush();
            $return = $response->withJson(["result" => true], 201)->withHeader('Content-type', 'application/json');
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage(), $ex->getCode());
        }
        return $return;
    }
});


$app->post('/verificar/email', function (Request $request, Response $response) use ($app) {


    $email = $request->getParam('email');
    $entityManager = $this -> get('em');


    $loginRepository = $entityManager->getRepository('App\Models\Entity\Login');
    $existeEmail = $loginRepository->findBy(array('email' => $email));

    if($existeEmail){
        return $response->withStatus(202);
    }
    else{
        return $response->withStatus(204);
    }

});

$app->post('/verificar/login', function (Request $request, Response $response) use ($app) {

    $login = $request->getParam('login');

    $entityManager = $this -> get('em');

    $loginRepository = $entityManager->getRepository('App\Models\Entity\Login');
    $existeLogin  = $loginRepository->findBy(array('login' => $login));

    if($existeLogin){
        return $response->withStatus(202);
    }
    else{
        return $response->withStatus(204);
    }

});



