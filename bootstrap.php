<?php 

require './vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

//Configurações
$configs = [
	'settings' => [
                'determineRouteBeforeAppMiddleware' => true,
		'displayErroDetails' => true,
                'addContentLengthHeader' => false,
	],
];


/**
 * Container Resources do Slim.
 */
$container = new \Slim\Container($configs);


$container['erroHandler'] = function ($c){
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
	'host' => '127.0.0.1',
	'user' => 'root',/*citycare_web*/
	'password' => 'root',/*T0*oO3HfwSzv*/
	'dbname' => 'citycare_db'
);


//Instância do EntityManager
$entityManager = EntityManager::create($conn,$config);

$container['em'] = $entityManager;




$app = new \Slim\App($container);
 ?>