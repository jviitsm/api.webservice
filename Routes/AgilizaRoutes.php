<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Models\Entity\Agiliza;

$app -> post('/agiliza/cadastrar', function(Request $request, Response $response) use ($app){

    if(!$request->getParsedBody()){
        throw new Exception("Corpo de requisição vazio", 204);
    }else{

        $fk_login_agiliza = $request->getParam('fk_login_agiliza');
        $fk_denuncia_agiliza = $request->getParam('fk_denuncia_agiliza');
        $entityManager = $this->get('em');

        try{

            $agiliza = new Agiliza();

            $loginRepository = $entityManager->getRepository('App\Models\Entity\Login');
            $loginAgiliza = $loginRepository->find($fk_login_agiliza['id_login']);

            //Buscando denuncia no banco
            $denunciaRepository = $entityManager->getRepository('App\Models\Entity\Denuncia');
            $denunciaAgiliza = $denunciaRepository->find($fk_denuncia_agiliza['id_denuncia']);

            $agiliza->setInteracao($request->getParam('interacao'));
            $agiliza->setFk_login_agiliza($loginAgiliza);
            $agiliza->setFk_denuncia_agiliza($denunciaAgiliza);

            $entityManager->merge($agiliza);
            $entityManager->flush();

            $return = $response->withJson(["result" => true],201)->withHeader('Content-type', 'application/json');


        }catch(Exception $ex)
        {
            throw new Exception($ex->getMessage(), $ex->getCode());
        }
        return $return;

    }

});
