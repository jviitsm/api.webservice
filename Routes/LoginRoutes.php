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
        $emailParametro = $request->getParam('email');
        $senhaParametro = $request->getParam('senha');
        $loginParametro = $request->getParam('login');
        try {
            //Verifica se o usuário realizou login digitando seu login
            if ($loginParametro) {
                $query = $entityManager->createQuery("SELECT c, l FROM App\Models\Entity\Cidadao c JOIN c.fk_login_cidadao l 
            WHERE l = l.id_login AND l.login = :login AND l.senha = :senha");
                //Parametros da query
                $query->setParameters(
                    array(':login' => $loginParametro,
                        ':senha' => $senhaParametro));
                //Resultado da query

                $cidadao = $query->getResult();
                //Verifica se o usuário é cidadao
                if ($cidadao) {
                    //O usuário é cidadao
                    $return = $response->withJson($cidadao, 200);
                } else if (!$cidadao) {
                    //Os dados informados não correspondem a um cidadão
                    $queryEmpresa = $entityManager->createQuery("SELECT c, l FROM App\Models\Entity\Empresa c JOIN c.fk_login_empresa l 
            WHERE l = l.id_login AND l.login = :login AND l.senha = :senha");
                    //Parametros da query
                    $queryEmpresa->setParameters(
                        array(':login' => $loginParametro,
                            ':senha' => $senhaParametro));
                    $empresa = $queryEmpresa->getResult();
                    //Verifica se o usuário é empresa
                    if (!$empresa) {
                        //Os dados informados não batem com nada no banco
                        throw new Exception("Login ou Senha incorretos", 404);
                    } else {
                        //o usuário é empresa
                        $return = $response->withJson($empresa, 200);
                    }
                }
            }
            //O usuário não digitou login e sim e-mail
            else {

                $query = $entityManager->createQuery("SELECT c, l FROM App\Models\Entity\Cidadao c JOIN c.fk_login_cidadao l 
            WHERE l = l.id_login AND l.email = :email AND l.senha = :senha");
                //Parametros da query
                $query->setParameters(
                    array(':email' => $emailParametro,
                        ':senha' => $senhaParametro));
                //Resultado da query

                $cidadao = $query->getResult();
                if ($cidadao) {
                    $return = $response->withJson($cidadao, 200);
                } else if (!$cidadao) {
                    $queryEmpresa = $entityManager->createQuery("SELECT c, l FROM App\Models\Entity\Empresa c JOIN c.fk_login_empresa l 
            WHERE l = l.id_login AND l.email = :email AND l.senha = :senha");
                    //Parametros da query
                    $queryEmpresa->setParameters(
                        array(':email' => $emailParametro,
                            ':senha' => $senhaParametro));
                    $empresa = $queryEmpresa->getResult();
                    if (!$empresa) {
                        throw new Exception("Login ou Senha incorretos", 404);
                    } else {
                        $return = $response->withJson($empresa, 200);
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