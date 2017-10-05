<?php 

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


use App\Models\Entity\Login;
use App\Models\Entity\Empresa;

$app -> post('/empresa/cadastrar', function(Request $request, Response $response) use ($app){

    if (!$request->getBody()){
        throw new Exception("Corpo da requisição vazia", 204);
    } else {
        //Container do EntityManager
        $entityManager = $this -> get('em');
        try{
            //Instância da entidade Login
            $login = new Login();
            //recuperando o parâmetro objeto login do json
            $fk_login_empresa = $request->getParam('fk_login_empresa');
            //setando valores do objeto login
            $login ->setLogin($fk_login_empresa['login']);
            $login ->setEmail($fk_login_empresa['email']);
            $login ->setSenha(base64_encode(fk_login_empresa['senha']));
            $login ->setStatus_login($fk_login_empresa['status_login']);
            $login ->setAsAdministrador($fk_login_empresa['administrador']);
            //salvando login
            $entityManager->persist($login);
            $entityManager->flush();

            //buscando login recém salvo
            $loginRepository = $entityManager->getRepository('App\Models\Entity\Login');

            //pegando login
            $loginEmpresa = $loginRepository->find($login->getId_login());


            //Instância da entidade empresa
            $empresa = new Empresa();

            //setando valores do objeto empresa
            $empresa ->setFk_login_empresa($loginEmpresa);
            $empresa->setCnpj($request->getParam('cnpj'));
            $empresa->setRazao_Social($request->getParam('razao_social'));
            $empresa ->setNome_fantasia($request->getParam('nome_fantasia'));
            $empresa ->setEstado($request->getParam('estado'));
            $empresa ->setCidade($request->getParam('cidade'));
            $empresa ->setDir_foto_usuario($request->getParam('dir_foto_usuario'));

            //salvando empresa
            $entityManager->persist($empresa);
            $entityManager->flush();

            //retornando confirmação do evento completo
            $return = $response->withJson(["result" => true],201)->withHeader('Content-type', 'application/json');
        } catch (Exception $ex){
            //código e mensagem do erro
            throw new Exception($ex->getMessage(), $ex->getCode());
        }
        return $return;
    }

});

	//retornar Empresa específica
$app->post('/empresa/exibir', function(Request $request, Response $response) use ($app){

    if(!$request->getParsedBody()){
        throw new Exception("Corpo de requisição vazio", 204);
    }
    try{
        $entityManager = $this->get('em');

        $id = $request->getParam('id_empresa');

        $empresa = $entityManager->find('App\Models\Entity\Empresa', $id);

        if(!$empresa)
		{
		    throw new Exception("Nenhum Resultado", 404);
		}
        $return = $response -> withJson($empresa, 200);


    }catch(Exception $ex){
        throw new Exception($ex->getMessage(), $ex->getCode());
	} 
	return $return;
});

$app->put('/empresa/desativar', function(Request $request, Response $response) use ($app){
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
$app->put('/empresa/alterar', function(Request $request, Response $response) use ($app){

    if(!$request->getParsedBody()){
        throw new Exception("Corpo de requisição vazio", 204);
    }
    $entityManager = $this->get('em');
    try{
        $empresa = new Empresa();
        $login = new Login();

        $fk_login_empresa = $request->getParam('fk_login_empresa');
        //setando valores do objeto login
        $login ->setId_login($fk_login_empresa['id_login']);
        $login ->setLogin(fk_login_empresa['login']);
        $login ->setEmail(fk_login_empresa['email']);
        $login ->setSenha(base64_encode(fk_login_empresa['senha']));
        $login ->setStatus_login(fk_login_empresa['status_login']);
        $login ->setAsAdministrador(fk_login_empresa['administrador']);
        //salvando login
        $entityManager->merge($login);
        $entityManager->flush();

        $loginRepository = $entityManager->getRepository('App\Models\Entity\Login');
        $loginCidadao = $loginRepository->find($login->getId_login());

        $empresa->setId_cidadao($request->getParam('id_cidadao'));
        $empresa->setNome($request->getParam('nome'));
        $empresa->setSexo($request->getParam('sexo'));
        $empresa->setSobrenome($request->getParam('sobrenome'));
        $empresa->setEstado($request->getParam('estado'));
        $empresa->setCidade($request->getParam('cidade'));
        $empresa->setDir_foto_usuario($request->getParam('dir_foto_usuario'));
        $empresa ->setFk_login_cidadao($loginCidadao);

        $entityManager->merge($empresa);
        $entityManager->flush();

        $return = $response->withJson(["result" => true],201)->withHeader('Content-type', 'application/json');
    }catch(Exception $ex){
        throw new Exception($ex->getMessage(), $ex->getCode());
    }
    return $return;



});
