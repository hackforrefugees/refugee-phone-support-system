<?php
require '../vendor/autoload.php';
require '../config.php';

$app = new \Slim\Slim();
$app->view(new \JsonApiView());
$app->add(new \JsonApiMiddleware());

// users
$app->get($appConfig['apiPrefix'] . '/users', function () use ($app) {
    $app->render(200, ['result' => array(
            ['id' => 1, 'name' => 'Name 1'],
            ['id' => 2, 'name' => 'Name 2'],
            ['id' => 3, 'name' => 'Name 3']
        )]
    );
});

$app->get($appConfig['apiPrefix'] . '/users/:id', function ($id) {
    $app->render(200, ['result' => array(
            'id' => 1, 'name' => 'Name 1'
        )]
    );
});

// orders
$app->get($appConfig['apiPrefix'] . '/orders', function () use ($app) {

    $app->render(200, ['result' => Order::all()->toJson()]);
});

$app->get($appConfig['apiPrefix'] . '/orders/:id', function ($id) {
    $app->render(200, ['result' => array(
            'id' => 1, 'name' => 'Order 1'
        )]
    );
});

$app->run();
?>
