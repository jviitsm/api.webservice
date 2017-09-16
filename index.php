<?php 
	
	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;
        
	use App\Models\Entity\Login;
	use App\Models\Entity\Cidadao;


	require 'bootstrap.php';

        $app -> post('/cidadao/cadastrar', function(Request $request, Response $response) use ($app){
        
          //Container do EntityManager
          $entityManager = $this -> get('em');
          
            //Instância da entidade Login
              $login = new Login();
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
              $loginCidadao = $loginRepository->find($login->getId_login());
              
              //Instância da entidade Cidadao
              $cidadao = new Cidadao();
              //setando valores do objeto cidadao
                     $cidadao ->setFk_login_cidadao($loginCidadao);
                     $cidadao ->setNome($request->getParam('nome'));
                     $cidadao ->setSexo($request->getParam('sexo'));
                     $cidadao ->setSobrenome($request->getParam('sobrenome'));
                     $cidadao ->setEstado($request->getParam('estado'));
                     $cidadao ->setCidade($request->getParam('cidade'));
                     $cidadao ->setDir_foto_usuario($request->getParam('dir_foto_usuario'));
              //salvando cidadao
              $entityManager->persist($cidadao);
              $entityManager->flush();
              
              //retornando confirmação do evento completo
              return $response->withJson(["result" => 1],200)->withHeader('Content-type', 'application/json');
     
  }); 

    //Deletar Cidadão e Login por id
   $app -> delete('/cidadao/deletar/{id}', function(Request $request, Response $response) use ($app){
          $route = $request->getAttribute('route');
          $id = $route->getArgument('id');

         $entityManager = $this->get('em');
         
             
            $cidadaoRepository = $entityManager->getRepository('App\Models\Entity\Cidadao');
            $cidadao = $cidadaoRepository->find($id); 

            $idLogin = $cidadao->getFk_login_cidadao();

            $entityManager->remove($cidadao);
            $entityManager->flush();

            $loginRepository = $entityManager->getRepository('App\Models\Entity\Login');
            $login = $loginRepository->find($idLogin);

            $entityManager->remove($login);
            $entityManager->flush();

             return $response->withJson(['msg' => "Deletando o Cidadao {$id}"], 204)->withHeader('Content-type', 'application/json');

       
 }); 

   $app -> get('/cidadao/exibir/{id}', function(Request $request, Response $response) use ($app){
          
          $route = $request->getAttribute('route');
          $id = $route->getArgument('id');

         $entityManager = $this->get('em');   

          
            $cidadaoRepository = $entityManager->getRepository('App\Models\Entity\Cidadao');
            $cidadao = $cidadaoRepository->find($id); 

            $idLogin = $cidadao->getFk_login_cidadao();

            $loginRepository = $entityManager->getRepository('App\Models\Entity\Login');
            $login = $loginRepository->find($idLogin);

           return json_encode($cidadao);

        

 });

  $app->run();