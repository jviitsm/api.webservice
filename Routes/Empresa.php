<?php 

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


use App\Models\Entity\Login;
use App\Models\Entity\Empresa;

$app -> post('/empresa/cadastrar', function(Request $request, Response $response) use ($app){

				//Container do EntityManager
	$entityManager = $this -> get('em');
	try{
					//Instância da entidade Login
		$login = new Login();
										//recuperando o parâmetro objeto login do json
		$fk_login_empresa = $request->getParam('fk_login_empresa');
										//setando valores do objeto login
		$login ->setLogin($request->getParam('login'));
		$login ->setEmail($request->getParam('email'));
		$login ->setSenha($request->getParam('senha'));
		$login ->setStatus_login($request->getParam('status_login'));
		$login ->setAsAdministrador($request->getParam('administrador'));
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
		$return = $response->withJson(["result" => true],200)->withHeader('Content-type', 'application/json');
	} catch (Exception $e){
						//código e mensagem do erro
		$error = array (
			'Code:' => $e->getCode(),
			'Message' => $e->getMessage()
		);
						//retornando o erro ao cliente
		$return =  $response->withJson($error);
	}

	return $return;
});