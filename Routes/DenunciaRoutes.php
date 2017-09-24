    <?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Models\Entity\Login;
use App\Models\Entity\Cidadao;
use App\Models\Entity\Categoria;
use App\Models\Entity\Denuncia;


$app->post('/denuncia/cadastrar', function (Request $request, Response $response) use ($app) {

    if(!$request->getParsedBody()){
        throw new Exception("Corpo de requisição vazio", 204);
    }else {
        $entityManager = $this->get('em');
        try{
            $fk_login_denuncia = ($request->getParam('fk_login_denuncia'));
            $fk_categoria_denuncia= ($request->getParam('fk_categoria_denuncia'));

            $denuncia = new Denuncia();

            $loginRepository = $entityManager->getRepository('App\Models\Entity\Login');
            $loginDenuncia = $loginRepository->find($fk_login_denuncia['id_login']);

            $categoriaRepository = $entityManager->getRepository('App\Models\Entity\Categoria');
            $categoriaDenuncia = $categoriaRepository->find($fk_categoria_denuncia['id_categoria']);


            //setando os campos da denuncia
            $denuncia->setDescricao_denuncia($request->getParam('descricao_denuncia'));
            $denuncia->setDir_foto_denuncia($request->getParam('dir_foto_denuncia'));
            $denuncia->setLatitude_denuncia($request->getParam('latitude_denuncia'));
            $denuncia->setLongitude_denuncia($request->getParam('longitude_denuncia'));
            $denuncia->setData_denuncia($request->getParam('data_denuncia'));
            $denuncia->setStatus_denuncia($request->getParam('status_denuncia'));
            $denuncia->setFk_login_denuncia($loginDenuncia);
            $denuncia->setFk_categoria_denuncia($categoriaDenuncia);

            $entityManager->persist($denuncia);
            $entityManager->flush();

            $return = $response->withJson(["result" => true],201)->withHeader('Content-type', 'application/json');

        }catch(Exception $ex)
        {
            throw new Exception($ex->getMessage(), $ex->getCode());
        } return $return;

    }
});