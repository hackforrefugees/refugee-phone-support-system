<?php

// areas
$app->get($appConfig['apiPrefix'] . '/areas', function() use ($app) {
    $app->render(200, ['result' => Order::all()]);
});

$app->post($appConfig['apiPrefix'] . '/areas', function() use ($app) {
    $area = new Area();
    $valid = $area->validate($app->jsonBody);
    if (!$valid) {
        $app->render(400, ['validation' => $area->errors]);
    }

    $area->fill($app->jsonBody);
    if ($area->save()) {
        $app->render(200);
    } else {
        $app->render(500);
    }
});

$app->put($appConfig['apiPrefix'] . '/areas/:id', function($id) use ($app) {
    $area = Area::find($id);
    $valid = $area->validate($app->jsonBody);
    if (!$valid) {
        $app->render(400, ['validation' => $area->errors]);
    }

    $area->fill($app->jsonBody);
    if ($area->save()) {
        $app->render(200);
    } else {
        $app->render(500);
    }
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

    $valid = $order->validate($app->jsonBody);
    if (!$valid) {
        $app->render(400, ['validation' => $order->errors]);
    }

    $order->fill($app->jsonBody);
    if ($order->save()) {
        $app->render(200);
    } else {
        $app->render(500);
    }
});

$app->post($appConfig['apiPrefix'] . '/orders', function () use ($app) {
    $order = new Order();
    $valid = $order->validate($app->jsonBody);
    if (!$valid) {
        $app->render(400, ['validation' => $order->errors]);
    }

    $order->fill($app->jsonBody);
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
