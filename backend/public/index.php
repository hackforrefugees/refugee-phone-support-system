<?php
require '../vendor/autoload.php';
require '../config.php';

$app = new \Slim\Slim();
$app->view(new \JsonApiView());
$app->add(new \JsonApiMiddleware());
$app->add(new \Slim\Middleware\JwtAuthentication([
    'secret'   => $appConfig['jwtSecret'],
    'secure'   => false,
    'path'     => $appConfig['apiPrefix'],
    'callback' => function ($options) use ($app) {
        $app->jwt = $options['decoded'];
    }
]));

$corsOptions = [
	'origin' => '*'
];
$app->add(new \CorsSlim\CorsSlim($corsOptions));

require '../routes/users.php';
require '../routes/orders.php';

$app->run();
