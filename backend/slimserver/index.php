<?php

$api_path_prepend = '/v1';

require 'vendor/autoload.php';

$app = new \Slim\Slim();
$app->get('/hello/:name', function ($name) {

    $mysqli = new mysqli("192.168.1.188", "refugeephone", "phones2000", "refugeephone", 8889);
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }
    if ($result = $mysqli->query("SELECT 1")) {

        $numberOfRows = $result->num_rows;

        /* free result set */
        $result->close();
    }
    echo "Hello, " . $numberOfRows;
});

$app->view(new \JsonApiView());
$app->add(new \JsonApiMiddleware());

// users
$app->get($api_path_prepend . '/users', function () use ($app) {
    $app->render(200, ['result' => array(
            ['id' => 1, 'name' => 'Name 1'],
            ['id' => 2, 'name' => 'Name 2'],
            ['id' => 3, 'name' => 'Name 3']
        )]
    );
});

$app->get($api_path_prepend . '/users/:id', function ($id) {
    $app->render(200, ['result' => array(
            'id' => 1, 'name' => 'Name 1'
        )]
    );
});

// orders
$app->get($api_path_prepend . '/orders', function () {
    $app->render(200, ['result' => array(
            ['id' => 1, 'name' => 'Order 1'],
            ['id' => 2, 'name' => 'Order 2'],
            ['id' => 3, 'name' => 'Order 3']
        )]
    );
});

$app->get($api_path_prepend . '/orders/:id', function ($id) {
    $app->render(200, ['result' => array(
            'id' => 1, 'name' => 'Order 1'
        )]
    );
});

$app->run();
?>
