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

// appel fonction pop de GenericEvent
$source = new GenericEvent($container);
while ($event = $source->pop()) {
    $event->process();
}
echo "Done\n";