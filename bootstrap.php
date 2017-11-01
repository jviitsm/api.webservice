<?php 

require './vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Psr7Middlewares\Middleware\TrailingSlash;

//Configurações
$configs = [
	'settings' => [
		'displayErroDetails' => true,
        'determineRouteBeforeAppMiddleware' => true,
		'addContentLengthHeader' => false,
	],
];

/**
 * Container Resources do Slim.
 */
$container = new \Slim\Container($configs);

$container['errorHandler'] = function ($c){
	return function ($request, $response, $exception) use ($c){
		$statusCode = $exception -> getCode() ? $exception-> getCode() : 500;
		return $c['response'] -> withStatus($statusCode) -> withHeader('Content-type','application/json')
		->withJson(['message' => $exception-> getMessage()], $statusCode);
	};
};

$isDevMode = true;


//Diretório das Entidades e Metadata do Doctrine
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/src/Models/Entity"), $isDevMode);

$conn = array(
	'driver' => 'pdo_mysql',
	'host' => 'localhost',
	'user' => 'root',/*citycare_web*/
	'password' => '',/*T0*oO3HfwSzv*/
	'dbname' => 'citycare_db',
    'charset' => 'utf8',
);


//Instância do EntityManager
$entityManager = EntityManager::create($conn,$config);

$container['em'] = $entityManager;

/**
 * Token do nosso JWT
 */
$container['secretkey'] = "thissecret";


$app = new \Slim\App($container);


//Middleware que controla a barra após a url
$app->add(new TrailingSlash(false));

/**
 * Auth básica HTTP
 */
$app->add(new \Slim\Middleware\HttpBasicAuthentication([
    /**
     * Usuários existentes
     */
    "users" => [
        "root" => "carecity",
        "jv" => "jviits"
    ],
    /**
     * Blacklist - Deixa todas liberadas e só protege as dentro do array
     */
    "path" => ["/auth"],
    /**
     * Whitelist - Protege todas as rotas e só libera as de dentro do array
     */
    //"passthrough" => ["/auth/liberada", "/admin/ping"],
]));

$app->add(new \Slim\Middleware\JwtAuthentication([

    "regexp" => "/(.*)/", //Regex para encontrar o Token nos Headers - Livre
    "header" => "X-Token", //O Header que vai conter o token
    "path" => "/", //Vamos cobrir toda a API a partir do /
    "passthrough" => ["/auth","/denuncia/er"], //Vamos adicionar a exceção de cobertura a rota /auth
    "realm" => "Protected",
    "secret" => $container['secretkey'],
    "secure" => false,
    "error" => function ($request, $response, $arguments) {
        $data["code"] = "401";
        $data["message"] = $arguments["message"];
        return $response
            ->withHeader("Content-Type", "application/json")->withStatus(401)
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }

]));

//Middleware para pegar o ip do usuario
$checkProxyHeaders = true;
$trustedProxies = ['10.0.0.1', '10.0.0.2'];
$app->add(new RKA\Middleware\IpAddress($checkProxyHeaders, $trustedProxies));



 ?>