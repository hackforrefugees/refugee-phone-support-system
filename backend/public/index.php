<?php
require '../vendor/autoload.php';
require '../config.php';

$app = new \Slim\Slim();
$app->view(new \JsonApiView());
$app->add(new \JsonApiMiddleware());
$app->add(new \Slim\Middleware\JwtAuthentication([
    'secret'   => $appConfig['jwtSecret'],
    'path'     => '/api'
]));

require '../routes/users.php';
require '../routes/orders.php';

$app->run();
