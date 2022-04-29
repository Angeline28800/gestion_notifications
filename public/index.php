<?php

use App\Http\Controllers\ConnexionController;
use App\Http\Controllers\HomeController;
use DI\ContainerBuilder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Inclusion de l'autoload des classes
require_once('../app/config/Autoloader.php');
Autoloader::register('../src/classes');
Autoloader::register('../src/controllers');

// Create Container Connexion----------------------------------------------------
// Create Container using PHP-DI

$builder = new \DI\ContainerBuilder();
$builder->addDefinitions('../app/config/config.env.php');
$container = $builder->build();

use function \DI\create;

// Set container to create App with on AppFactory
AppFactory::setContainer($container);
$app = AppFactory::create();


$checkProxyHeaders = true; // Note: Never trust the IP address for security processes!
$trustedProxies = ['10.0.0.1', '10.0.0.2']; // Note: Never trust the IP address for security processes!
$app->add(new RKA\Middleware\IpAddress($checkProxyHeaders, $trustedProxies));

// to get the ip address
$app->get('/ip', function ($request, $response, $args) {
    $ipAddress = $request->getAttribute('ip_address');
    $response->getBody()->write('ip=' . $ipAddress);
    return $response;
});


$app->get('/aff/', \GenericEvent::class . ':webhook');
$app->post('/webhooks', \MailjetEvent::class . ':webhook');




$app->run();
