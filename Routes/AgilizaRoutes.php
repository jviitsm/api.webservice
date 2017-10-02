<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Models\Entity\Agiliza;




$app -> post('/agiliza/cadastrar', function(Request $request, Response $response) use ($app){
    if(!$request->getParsedBody()){
        throw new Exception("Corpo de requisição vazio", 204);
    }else{
        $entityManager = $this->get('em');
        try{
            $fk_login_agiliza = $request->getParam('fk_login_agiliza');
            $fk_denuncia_agiliza = $request->getParam('fk_denuncia_agiliza');

            //Buscando login no banco
            $loginRepository = $entityManager->getRepository('App\Models\Entity\Login');
            $loginAgiliza = $loginRepository->find($fk_login_agiliza['id_login']);

            //Buscando denuncia no banco
            $denunciaRepository = $entityManager->getRepository('App\Models\Entity\Denuncia');
            $denunciaAgiliza = $denunciaRepository->find($fk_denuncia_agiliza['id_denuncia']);

            //Criando objeto Agiliza
            $agiliza = new Agiliza();

            //Setando atributos do objeto comentario
            $agiliza->setInteracao($request->getParam('interacao'));
            $agiliza->setFk_login_agiliza($loginAgiliza);
            $agiliza->setFk_denuncia_agiliza($denunciaAgiliza);

            $entityManager->persist($agiliza);
            $entityManager->flush();


            $return = $response->withJson(["result" => true],201)->withHeader('Content-type', 'application/json');

        }catch(Exception $ex)
        {
            throw new Exception($ex->getMessage(), $ex->getCode());
        } return $return;
    }
});