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
		$login ->setLogin($fk_login_cidadao['login']);
		$login ->setEmail($fk_login_cidadao['email']);
		$login ->setSenha($fk_login_cidadao['senha']);
		$login ->setStatus_login($fk_login_cidadao['status_login']);
		$login ->setAsAdministrador($fk_login_cidadao['administrador']);
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


	//retornar Empresa específica
$app->get('/empresa/exibir/{id}', function(Request $request, Response $response) use ($app){
	try{
				//pegando parâmetro do link
		$route = $request -> getAttribute('route');
		$id = $route -> getArgument('id');

		$entityManager = $this->get('em');
				//Query em Doctrine para conrtornar o erro de Proxy
		$query = $entityManager->createQuery("SELECT c, l FROM App\Models\Entity\Empresa c JOIN c.fk_login_empresa l WHERE l = l.id_login AND c.id_empresa = :id")->setParameter(":id", $id);
		
		
		$empresa = $query -> getResult();


		if($empresa)
		{
			$return = $response -> withJson($empresa, 200);

		}
		else
		{
			$noResult = array(
				'Code' => "0",
				'Message' => "No Result"
			);
			$return = $response-> withJson($noResult);

			return $return;

		}

	}catch(Exception $ex){
		$error = array(
			'Code' => $ex->getCode(),
			'Message'=> $ex->getMessage()
		);
		$return = $response-> withJson($error);
	} 
	return $return;
});
