<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Models\Entity\Login;
use App\Models\Entity\Cidadao;
use App\Models\Entity\Categoria;
use App\Models\Entity\Denuncia;
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-Token");


$app->post('/denuncia/cadastrar', function (Request $request, Response $response) use ($app) {

    if(!$request->getParsedBody()){
        throw new Exception("Corpo de requisição vazio", 204);
    }else {
        $entityManager = $this->get('em');

        $json = $request->getParam('denuncia');
        $json = json_decode($json);


        try{
            $fk_login_denuncia = $json -> fk_login_denuncia;
            $fk_categoria_denuncia = $json -> fk_categoria_denuncia;

            $denuncia = new Denuncia();


            $loginRepository = $entityManager->getRepository('App\Models\Entity\Login');
            $loginDenuncia = $loginRepository->find($fk_login_denuncia -> id_login);



            $categoriaRepository = $entityManager->getRepository('App\Models\Entity\Categoria');
            $categoriaDenuncia = $categoriaRepository->find($fk_categoria_denuncia -> id_categoria);


            //Salvar foto
            $files = $request->getUploadedFiles();
            $newimage = $files['fotoDenuncia'];
            if($newimage){
                if ($newimage->getError() === UPLOAD_ERR_OK) {
                    $uploadFileName = $newimage->getClientFilename();
                    $type = $newimage->getClientMediaType();
                    $name = uniqid('img-' . date('d-m-y') . '-');
                    $name .= $newimage->getClientFilename();
                    //  $imgs[] = array('url' => '/Photos/' . $name);
                    //local server
                    $newimage->moveTo("/home/citycare/public_html/Imgs/Denuncia/$name");#/home/citycare/Imgs/User/$name
                    //localdev
                    $photoURL = "http://projetocitycare.com.br/Imgs/Denuncia/$name";#/home/citycare/Imgs/User/$name
                }
            }
            else {
                $photoURL = null;
            }



            //setando os campos da denuncia
            $denuncia->setDescricao_denuncia($json -> descricao_denuncia);
            $denuncia->setDir_foto_denuncia($photoURL);
            $denuncia->setLatitude_denuncia($json -> latitude_denuncia);
            $denuncia->setLongitude_denuncia($json -> longitude_denuncia);
            $denuncia->setData_denuncia($json -> data_denuncia);
            $denuncia->setStatus_denuncia($json -> status_denuncia);
            $denuncia->setFk_login_denuncia($loginDenuncia);
            $denuncia->setFk_categoria_denuncia($categoriaDenuncia);
            $denuncia->setCidade($json -> cidade);
            $denuncia->setEstado($json -> estado);


            $entityManager->persist($denuncia);
            $entityManager->flush();

            $return = $response->withJson($denuncia,201)->withHeader('Content-type', 'application/json');

        }catch(Exception $ex)
        {
            throw new Exception($ex->getMessage(), $ex->getCode());
        }
        return $return;

    }
});

$app->put('/denuncia/alterar', function (Request $request, Response $response) use ($app) {

    $entityManager = $this->get('em');
    if(!$request->getParsedBody()){
        throw new Exception("Corpo de requisição vazio", 204);
    }else {
        $entityManager = $this->get('em');

        $json = $request->getParam('denuncia');
        $json = json_decode($json);


        try{
            $fk_login_denuncia = $json -> fk_login_denuncia;
            $fk_categoria_denuncia = $json -> fk_categoria_denuncia;

            $denuncia = new Denuncia();


            $loginRepository = $entityManager->getRepository('App\Models\Entity\Login');
            $loginDenuncia = $loginRepository->find($fk_login_denuncia -> id_login);



            $categoriaRepository = $entityManager->getRepository('App\Models\Entity\Categoria');
            $categoriaDenuncia = $categoriaRepository->find($fk_categoria_denuncia -> id_categoria);


            //Salvar foto
            $files = $request->getUploadedFiles();
            $newimage = $files['fotoDenuncia'];
            if($newimage){
                if ($newimage->getError() === UPLOAD_ERR_OK) {
                    $uploadFileName = $newimage->getClientFilename();
                    $type = $newimage->getClientMediaType();
                    $name = uniqid('img-' . date('d-m-y') . '-');
                    $name .= $newimage->getClientFilename();
                    //  $imgs[] = array('url' => '/Photos/' . $name);
                    //local server
                    $newimage->moveTo("/home/citycare/public_html/Imgs/Denuncia/$name");#/home/citycare/Imgs/User/$name
                    //localdev
                    $photoURL = "http://projetocitycare.com.br/Imgs/Denuncia/$name";#/home/citycare/Imgs/User/$name
                }
            }
            else {
                $photoURL = null;
            }



            //setando os campos da denuncia
            $denuncia->setDescricao_denuncia($json -> descricao_denuncia);
            $denuncia->setDir_foto_denuncia($photoURL);
            $denuncia->setLatitude_denuncia($json -> latitude_denuncia);
            $denuncia->setLongitude_denuncia($json -> longitude_denuncia);
            $denuncia->setData_denuncia($json -> data_denuncia);
            $denuncia->setStatus_denuncia($json -> status_denuncia);
            $denuncia->setFk_login_denuncia($loginDenuncia);
            $denuncia->setFk_categoria_denuncia($categoriaDenuncia);
            $denuncia->setCidade($json -> cidade);
            $denuncia->setEstado($json -> estado);


            $entityManager->merge($denuncia);
            $entityManager->flush();

            $return = $response->withJson($denuncia,201)->withHeader('Content-type', 'application/json');
        }catch(Exception $ex)
        {
            throw new Exception($ex->getMessage(), $ex->getCode());
        } return $return;
    }
});

$app->post('/denuncia/exibir', function (Request $request, Response $response) use ($app) {

    $entityManager = $this->get('em');

    try{
        $id = $request->getParam('id_denuncia');


        $denunciaRepository = $entityManager->getRepository('App\Models\Entity\Denuncia');
        $denuncia = $denunciaRepository->find($id);

        $queryAgiliza = $entityManager->createQuery("SELECT (a.fk_login_agiliza)id_login, (a.interacao)interacao FROM App\Models\Entity\Agiliza a
          where a.fk_denuncia_agiliza = :id");
        $queryAgiliza->setParameters(
            array(':id' => $id));


        $queryComentario  = $entityManager->createQuery("SELECT (c.fk_login_comentario)id_login,(c.descricao_comentario)descricao
            FROM App\Models\Entity\Comentario c
             where c.fk_denuncia_comentario = :id");
        $queryComentario->setParameters(
            array(':id' => $id));

        $agiliza =$queryAgiliza->getResult();
        $comentario = $queryComentario->getResult();



        $retorno = array (
            "denuncia" => $denuncia,
            "agiliza" => $agiliza,
            "comentario" => $comentario
        );

    }catch(Exception $ex)
    {
        throw new Exception($ex->getMessage(), $ex->getCode());
    } return json_encode($retorno);

});
$app->get('/denuncia/er', function (Request $request, Response $response) use ($app) {

    $entityManager = $this->get('em');

    $denunciaRepository = $entityManager->getRepository('App\Models\Entity\Denuncia');
    $denuncia = $denunciaRepository->findAll();

    $return = $response->withJson($denuncia,200)->withHeader('Content-type', 'application/json');
    return $return;

});
$app->post('/denuncia/excluir', function (Request $request, Response $response) use ($app) {
    $id = $request->getParam('id_denuncia');
    $entityManager = $this->get('em');
    $denunciaRepository = $entityManager->getRepository('App\Models\Entity\Denuncia');

    try{
    $denuncia = $denunciaRepository->find($id);


    $denuncia->setStatus_denuncia(2);



    $entityManager->merge($denuncia);
    $entityManager->flush();


    $return = $response->withJson(1,200)->withHeader('Content-type', 'application/json');
    }catch(Exception $ex)
    {
        throw new Exception($ex->getMessage(), $ex->getCode());
    }

    return $return;


});

$app->post('/denuncia/feedSecundario', function (Request $request, Response $response) use ($app) {
    $id = $request->getParam('id_denuncia');

    $entityManager = $this->get('em');

    $denunciaRepository = $entityManager->getRepository('App\Models\Entity\Denuncia');
    $denuncia = $denunciaRepository->findBy(array("status_denuncia" =>  array(0,1)));

    $arrayDenuncia = array();
    $array = array();
    $i =0;
    $agilizaRepository = $entityManager->getRepository('App\Models\Entity\Agiliza');
    $comentarioRepository = $entityManager->getRepository('App\Models\Entity\Comentario');


    try{

        foreach($denuncia as $index){

            if($index-> id_denuncia >= $id){
                continue;
            }
            $arrayAgiliza = array();
            $agiliza = $agilizaRepository->findBy(array('fk_denuncia_agiliza' => $index -> id_denuncia, 'interacao' => 1));

            foreach ($agiliza as $agilizas){
                $arrayAgiliza[] = ["fk_login_agiliza" => array("id_login" => $agilizas -> fk_login_agiliza -> id_login),
                    "interacao" => $agilizas -> interacao,
                    "fk_denuncia_agiliza" => array( "id_denuncia" => $agilizas -> fk_denuncia_agiliza -> id_denuncia),

                ];
            }

            $arrayComentario = array();
            $comentario = $comentarioRepository->findBy(array('fk_denuncia_comentario' => $index -> id_denuncia));

            foreach ($comentario as $comentarios){
                $arrayComentario[] = ["fk_login_comentario" => array("id_login" => $comentarios -> fk_login_comentario -> id_login),
                    "descricao_comentario" => $comentarios -> descricao_comentario,
                    "fk_denuncia_comentario" => array( "id_denuncia" => $comentarios -> fk_denuncia_comentario -> id_denuncia),
                ];
            }

            $arrayDenuncia[] = ["id_denuncia" => $index ->id_denuncia,
                "fk_login_denuncia" => array( "id_login" => $index -> fk_login_denuncia -> id_login),
                "descricao_denuncia" => $index -> descricao_denuncia,
                "dir_foto_denuncia" => $index -> dir_foto_denuncia,
                "latitude_denuncia" => $index -> latitude_denuncia,
                "longitude_denuncia" => $index -> longitude_denuncia,
                "cidade" => $index -> cidade,
                "estado" => $index -> estado,
                "data_denuncia" => $index -> data_denuncia,
                "status_denuncia" => $index -> status_denuncia,
                "fk_categoria_denuncia" => $index -> fk_categoria_denuncia,
                "fk_solucao_denuncia" => $index -> fk_solucao_denuncia,

            ];












            $array[] = ["denuncia" => $arrayDenuncia[$i],"agiliza" => $arrayAgiliza, "comentario" => $arrayComentario];


            $i++;

        }
    }catch(Exception $ex){
        throw new Exception($ex->getMessage(), $ex->getCode());
    }

    $arrayReverso = array_reverse($array);

    return $response->withJson($arrayReverso,200)->withHeader('Content-type', 'application/json');
});

$app->get('/denuncia/all', function (Request $request, Response $response) use ($app) {

    $entityManager = $this->get('em');

    $denunciaRepository = $entityManager->getRepository('App\Models\Entity\Denuncia');
    $denuncia = $denunciaRepository->findBy(array("status_denuncia" => array(0,1)));

    $arrayDenuncia = array();
    $array = array();
    $i =0;
    $agilizaRepository = $entityManager->getRepository('App\Models\Entity\Agiliza');
    $comentarioRepository = $entityManager->getRepository('App\Models\Entity\Comentario');

    try{

        foreach($denuncia as $index){


            $arrayAgiliza = array();
            $agiliza = $agilizaRepository->findBy(array('fk_denuncia_agiliza' => $index -> id_denuncia, 'interacao' => 1));

            foreach ($agiliza as $agilizas){
                $arrayAgiliza[] = ["fk_login_agiliza" => array("id_login" => $agilizas -> fk_login_agiliza -> id_login),
                    "interacao" => $agilizas -> interacao,
                    "fk_denuncia_agiliza" => array( "id_denuncia" => $agilizas -> fk_denuncia_agiliza -> id_denuncia),

                ];
            }

            $arrayComentario = array();
            $comentario = $comentarioRepository->findBy(array('fk_denuncia_comentario' => $index -> id_denuncia));

            foreach ($comentario as $comentarios){
                $arrayComentario[] = ["fk_login_comentario" => array("id_login" => $comentarios -> fk_login_comentario -> id_login),
                    "descricao_comentario" => $comentarios -> descricao_comentario,
                    "fk_denuncia_comentario" => array( "id_denuncia" => $comentarios -> fk_denuncia_comentario -> id_denuncia),
                ];
            }

            $arrayDenuncia[] = ["id_denuncia" => $index ->id_denuncia,
                "fk_login_denuncia" => array( "id_login" => $index -> fk_login_denuncia -> id_login),
                "descricao_denuncia" => $index -> descricao_denuncia,
                "dir_foto_denuncia" => $index -> dir_foto_denuncia,
                "latitude_denuncia" => $index -> latitude_denuncia,
                "longitude_denuncia" => $index -> longitude_denuncia,
                "cidade" => $index -> cidade,
                "estado" => $index -> estado,
                "data_denuncia" => $index -> data_denuncia,
                "status_denuncia" => $index -> status_denuncia,
                "fk_categoria_denuncia" => $index -> fk_categoria_denuncia,
                "fk_solucao_denuncia" => $index -> fk_solucao_denuncia,

            ];

            $array[] = ["denuncia" => $arrayDenuncia[$i],"agiliza" => $arrayAgiliza, "comentario" => $arrayComentario];


            $i++;

        }
    }catch(Exception $ex){
        throw new Exception($ex->getMessage(), $ex->getCode());
    }


    $arrayReverso = array_reverse($array);
    $novoArray = array();

    for ($o = 0;$o <= 5; $o++) {
        $novoArray[$o] = $arrayReverso[$o];
    }


    return $response->withJson($novoArray,200)->withHeader('Content-type', 'application/json');
});
