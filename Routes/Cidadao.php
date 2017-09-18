<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app -> post('/cidadao/cadastrar', function(Request $request, Response $response) use ($app){

				//Container do EntityManager
	$entityManager = $this -> get('em');
	try{
					//Instância da entidade Login
		$login = new Login();
										//recuperando o parâmetro objeto login do json
		$fk_login_cidadao = $request->getParam('fk_login_cidadao');
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
		$loginCidadao = $loginRepository->find($login->getId_login());

						//Instância da entidade Cidadao
		$cidadao = new Cidadao();
						//setando valores do objeto cidadao
		$cidadao ->setFk_login_cidadao($loginCidadao);
		$cidadao->setNome($request->getParam('nome'));
		$cidadao->setSexo($request->getParam('sexo'));
		$cidadao ->setSobrenome($request->getParam('sobrenome'));
		$cidadao ->setEstado($request->getParam('estado'));
		$cidadao ->setCidade($request->getParam('cidade'));
		$cidadao ->setDir_foto_usuario($request->getParam('dir_foto_usuario'));
						//salvando cidadao
		$entityManager->persist($cidadao);
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


	//retornar Cidadão específico
$app->get('/cidadao/exibir/{id}', function(Request $request, Response $response) use ($app){
	try{
				//pegando parâmetro do link
		$route = $request -> getAttribute('route');
		$id = $route -> getArgument('id');
		$entityManager = $this->get('em');
				//Query em Doctrine para conrtornar o erro de Proxy
		$query = $entityManager->createQuery("SELECT c, l FROM App\Models\Entity\Cidadao c JOIN c.fk_login_cidadao l WHERE l = l.id_login AND c.id_cidadao = :id")->setParameter(":id", $id);
		$cidadao = $query -> getResult();

		$return = $response -> withJson($cidadao, 200);
	}catch(Exception $ex){
		$error = array(
			'Code' => $ex->getCode(),
			'Message'=> $ex->getMessage()
		);
		$return = $response-> withJson($error);
	} 
	return $return;
});

$app->delete('/cidadao/desativar', function(Request $request, Response $response) use ($app){


});
