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
	'dbname' => 'citycare_db'
);


//Instância do EntityManager
$entityManager = EntityManager::create($conn,$config);

$container['em'] = $entityManager;
/**
 * Token do nosso JWT
 */
$container['secretkey'] = "secretloko";


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
        "root" => "carecity"
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
    "passthrough" => ["/auth"], //Vamos adicionar a exceção de cobertura a rota /auth
    "realm" => "Protected",
    "secret" => $container['secretkey'] //Nosso secretkey criado
]));


 ?>