<?php
require '../vendor/autoload.php';
require '../config.php';

$app = new \Slim\Slim();
$app->view(new \JsonApiView());
$app->add(new \JsonApiMiddleware());

// orders
$app->get($appConfig['apiPrefix'] . '/orders', function () use ($app) {
    $app->render(200, ['result' => Order::with('products')->get()]);
});

$app->get($appConfig['apiPrefix'] . '/orders/:id', function ($id) use ($app) {
    $order = Order::with('products')->find($id);
    if ($order) {
        $app->render(200, ['result' => $order]);
    } else {
        $app->render(404);
    }
});

// products
$app->get($appConfig['apiPrefix'] . '/products', function () use ($app) {
    $app->render(200, ['result' => Product::all()]);
});

$app->get($appConfig['apiPrefix'] . '/products/:id', function ($id) use ($app) {
    $product = Product::find($id);
    if ($product) {
        $app->render(200, ['result' => $product]); 
    } else {
        $app->render(404);
    }
});

$app->run();
