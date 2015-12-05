<?php
require '../vendor/autoload.php';
require '../config.php';

$app = new \Slim\Slim();
$app->view(new \JsonApiView());
$app->add(new \JsonApiMiddleware());

// users
$app->get($appConfig['apiPrefix'] . '/users', function () use ($app) {
    $app->render(200, ['result' => User::all()]);
});

// orders
$app->get($appConfig['apiPrefix'] . '/orders', function () use ($app) {
    $app->render(200, ['result' => Order::with('products', 'user')->get()]);
});

$app->get($appConfig['apiPrefix'] . '/orders/:id', function ($id) use ($app) {
    $order = Order::with('products')->find($id);
    if ($order) {
        $app->render(200, ['result' => $order]);
    } else {
        $app->render(404);
    }
});

$app->put($appConfig['apiPrefix'] . '/orders/:id', function ($id) use ($app) {
    $order = Order::find($id);

    $valid = $order->validate($app->request->post());
    if (!$valid) {
        $app->render(400, ['validation' => $order->errors]);
    }

    $order->fill($app->request->post());
    if ($order->save()) {
        $app->render(200);
    } else {
        $app->render(500);
    }
});

$app->post($appConfig['apiPrefix'] . '/orders', function () use ($app) {
    $order = new Order();
    $valid = $order->validate($app->request->post());
    if (!$valid) {
        $app->render(400, ['validation' => $order->errors]);
    }

    $order->fill($app->request->post());
    if ($order->save()) {
        $app->render(200);
    } else {
        $app->render(500);
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
