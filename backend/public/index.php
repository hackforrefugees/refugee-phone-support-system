<?php
require '../vendor/autoload.php';
require '../config.php';

// Bootstrap Eloquent ORM
$connFactory = new \Illuminate\Database\Connectors\ConnectionFactory();
$conn = $connFactory->make($appConfig['db']);
$resolver = new \Illuminate\Database\ConnectionResolver();
$resolver->addConnection('default', $conn);
$resolver->setDefaultConnection('default');
\Illuminate\Database\Eloquent\Model::setConnectionResolver($resolver);

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
$app->get($appConfig['apiPrefix'] . '/orders', function () {
    $app->render(200, ['result' => array(
            ['id' => 1, 'name' => 'Order 1'],
            ['id' => 2, 'name' => 'Order 2'],
            ['id' => 3, 'name' => 'Order 3']
        )]
    );
});

$app->get($appConfig['apiPrefix'] . '/orders/:id', function ($id) {
    $app->render(200, ['result' => array(
            'id' => 1, 'name' => 'Order 1'
        )]
    );
});

$app->run();
?>
