<?php
require 'vendor/autoload.php';

define('CONSOLE_BP', __DIR__);

$app = new \Slim\App();

// Get container
$container = $app->getContainer();

// Register component on container
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('src/twig/view', [
        'cache' => false
    ]);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container['router'],
        $container['request']->getUri()
    ));

    return $view;
};

$app->get('/', '\M2Console\Controllers\Console')->setName('index');
$app->post('/code_runner', '\M2Console\Controllers\CodeRunner')->setName('code_runner');
$app->post('/code_generator', '\M2Console\Controllers\CodeGenerator')->setName('code_generator');

$app->run();