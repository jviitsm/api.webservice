<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 20/09/2017
 * Time: 01:14
 */
$app->post('/login', function (Request $request, Response $response) use ($app) {

    if(!$request->getBody()){
        throw new Exception("Corpo de requisição vazio", 204);
    }
    else {
        $entityManager = $this->get('em');
        $loginParametro = $request->getParam('login');
        $senhaParametro = $request->getParam('senha');
        $emailParametro = $request->getParam('email');
        try
        {
            $query = $entityManager->createQuery("SELECT c, l FROM App\Models\Entity\Cidadao c JOIN c.fk_login_cidadao l WHERE l = l.id_login AND l.login = :login OR l.email = :email AND l.senha = :senha");
            //Parametros da query
            $query->setParameters(
                array(':login"' => $loginParametro,
                    ':email' => $emailParametro,
                    ':senha' => $senhaParametro));
            //Resultado da query
            $cidadao = $query->getResult();
            if(!$cidadao) {
                throw new Exception("E-mail/Login ou Senha incorretos", 404);
            }
            $return = $response->withJson($cidadao, 200);
        }catch(Exception $ex){
            throw new Exception($ex->getMessage(), $ex->getCode());
        }
        return $return;
    }
});	