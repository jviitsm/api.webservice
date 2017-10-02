<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 20/09/2017
 * Time: 01:14
 */
$app->post('/login', function (Request $request, Response $response) use ($app) {
    if (!$request->getBody()) {
        throw new Exception("Corpo de requisição vazio", 204);
    } else {
        $entityManager = $this->get('em');
        $senhaParametro = $request->getParam('senha');
        $loginParametro = $request->getParam('login');
        try {
            //login por cidadao
            $query = $entityManager->createQuery("SELECT c, l FROM App\Models\Entity\Cidadao c JOIN c.fk_login_cidadao l 
            WHERE l = l.id_login AND l.login = :login AND l.senha = :senha");
            $query->setParameters(
                array(':login' => $loginParametro,
                    ':senha' => $senhaParametro));
            $cidadaoPorLogin = $query->getResult();
            if($cidadaoPorLogin){
                $return = $response->withJson($cidadaoPorLogin, 200);
            }
            else if (!$cidadaoPorLogin) {
                $query = $entityManager->createQuery("SELECT c, l FROM App\Models\Entity\Cidadao c JOIN c.fk_login_cidadao l 
            WHERE l = l.id_login AND l.email = :email AND l.senha = :senha");
                $query->setParameters(
                    array(':email' => $loginParametro,
                        ':senha' => $senhaParametro));
                $cidadaoPorEmail = $query->getResult();
                if($cidadaoPorEmail){
                    $return = $response->withJson($cidadaoPorEmail, 200);
                }
                else if (!$cidadaoPorEmail) {
                    $query = $entityManager->createQuery("SELECT c, l FROM App\Models\Entity\Empresa c JOIN c.fk_login_empresa l 
            WHERE l = l.id_login AND l.login = :login AND l.senha = :senha");
                    $query->setParameters(
                        array(':login' => $loginParametro,
                            ':senha' => $senhaParametro));
                    $empresaPorLogin = $query->getResult();
                    if($empresaPorLogin) {
                        $return = $response->withJson($empresaPorLogin, 200);
                    }
                    else if(!$empresaPorLogin){
                        $query = $entityManager->createQuery("SELECT c, l FROM App\Models\Entity\Empresa c JOIN c.fk_login_empresa l 
            WHERE l = l.id_login AND l.email = :email AND l.senha = :senha");
                        $query->setParameters(
                            array(':email' => $loginParametro,
                                ':senha' => $senhaParametro));
                        $empresaPorEmail = $query->getResult();
                        if($empresaPorEmail){
                            $return = $response->withJson($empresaPorEmail, 200);
                        }
                        else{
                            throw new Exception("Login ou Senha incorretos", 404);
                        }
                    }
                }
            }
            return $return;
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage(), $ex->getCode());
        }
        return $return;
    }
});