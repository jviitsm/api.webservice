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

    $entityManager = $this->get('em');
    $senhaParametro = $request->getParam('senha');
    $loginParametro = $request->getParam('login');

    try {
        //login por cidadao
        $query = $entityManager->createQuery("SELECT c, l FROM App\Models\Entity\Cidadao c JOIN c.fk_login_cidadao l 
            WHERE l = l.id_login AND l.senha = :senha OR l.email = :login AND l.senha = :senha");
        $query->setParameters(
            array(':login' => $loginParametro,
                ':senha' => $senhaParametro));
        $cidadao = $query->getResult();

        if (!$cidadao) {
            $query = $entityManager->createQuery("SELECT c, l FROM App\Models\Entity\Empresa c JOIN c.fk_login_empresa l 
            WHERE l = l.id_login AND l.senha = :senha OR l.email = :login AND l.senha = :senha");
            $query->setParameters(
                array(':login' => $loginParametro,
                    ':senha' => $senhaParametro));

            $empresa = $query->getResult();

            if (!$empresa) {
                throw new Exception("Login ou Senha incorretos", 404);
            }
            else {

                $return = $response->withJson($empresa)->withStatus(223);

            }
        } else {
            $return = $response->withJson($cidadao)->withStatus(223);
        }
    } catch (Exception $ex) {
        throw new Exception($ex->getMessage(), $ex->getCode());
    }
    return $return;

});
