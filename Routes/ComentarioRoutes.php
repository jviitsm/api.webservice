<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use App\Models\Entity\Denuncia;
use App\Models\Entity\Login;
use App\Models\Entity\Comentario;

$app->post('/comentario/cadastrar', function (Request $request, Response $response) use ($app) {
    if(!$request->getParsedBody()){
        throw new Exception("Corpo de requisição vazio", 204);
    }else{
        $entityManager = $this->get('em');
        try{
            $fk_login_comentario = ($request->getParam('fk_login_comentario'));
            $fk_denuncia_comentario = ($request->getParam('fk_denuncia_comentario'));

            //Buscando login no banco
            $loginRepository = $entityManager->getRepository('App\Models\Entity\Login');
            $loginComentario = $loginRepository->find($fk_login_comentario['id_login']);

            //Buscando denuncia no banco
            $denunciaRepository = $entityManager->getRepository('App\Models\Entity\Denuncia');
            $denunciaComentario = $denunciaRepository->find($fk_denuncia_comentario['id_denuncia']);

            //Criando objeto comentario
            $comentario = new Comentario();

            //Setando atributos do objeto comentario
            $comentario->setDescricao_comentario($request->getParam('descricao_comentario'));
            $comentario->setFk_login_comentario($loginComentario);
            $comentario->setFk_denuncia_comentario($denunciaComentario);

            $entityManager->persist($comentario);
            $entityManager->flush();

            $return = $response->withJson($comentario,201)->withHeader('Content-type', 'application/json');

        }catch(Exception $ex)
        {
            throw new Exception($ex->getMessage(), $ex->getCode());
        } return $return;
    }
});

$app->post('/comentario/exibir', function (Request $request, Response $response) use ($app) {

    if(!$request->getParsedBody()){
        throw new Exception("Corpo de requisição vazio", 204);
    }else{
        $entityManager = $this->get('em');
        try{
            $id = $request->getParam('id_comentario');

            $comentarioRepository = $entityManager->getRepository('App\Models\Entity\Comentario');
            $comentario = $comentarioRepository->find($id);


            if($comentario){
                $return = $response->withJson($comentario, 200);
            }
            else{
                throw new Exception("Comentario não encontrado", 404);
            }

        }catch(Exception $ex)
        {
            throw new Exception($ex->getMessage(), $ex->getCode());
        } return $return;
    }

});
$app->post('/comentario/deletar', function (Request $request, Response $response) use ($app) {
    if (!$request->getParsedBody()) {
        throw new Exception("Corpo de requisição vazio", 204);
    } else {
        try {
            $entityManager = $this->get('em');
            $id = $request->getParam('id_comentario');

            $comentarioRepository = $entityManager->getRepository('App\Models\Entity\Comentario');
            $comentario = $comentarioRepository->find($id);


            $entityManager->remove($comentario);
            $entityManager->flush();

            $return = $response->withJson(1, 200);



        } catch (Exception $ex) {
            throw new Exception($ex->getMessage(), $ex->getCode());
        }
        return $return;

    }
});
