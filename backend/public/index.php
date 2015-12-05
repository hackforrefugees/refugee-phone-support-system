<?php
require '../vendor/autoload.php';
require '../config.php';

$app = new \Slim\Slim();
$app->view(new \JsonApiView());
$app->add(new \JsonApiMiddleware());
// $app->add(new \Slim\Middleware\JwtAuthentication([
//     "secret" => $appConfig['jwtSecret']
// ]));

// auth
$app->post($appConfig['apiPrefix'] . '/login', function() use ($app) {
    $password_hash = password_hash($app->request->post('password'), PASSWORD_DEFAULT);
    $user = User::whereEmail($app->request->post('email'))->first();
    if ($password_hash == $user->password_hash) {
        $app->render(200);
    } else {
        $app->render(403);
    }
});

$app->post($appConfig['apiPrefix'] . '/register', function() use ($app) {
    $user = new User();
    $user->email = $app->request->post('email');
    $user->password_hash = password_hash($app->request->post('password'), PASSWORD_DEFAULT);

    if ($user->save()) {
        $app->render(200);
    } else {
        $app->render(500);
    }
});

// users
$app->get($appConfig['apiPrefix'] . '/users', function () use ($app) {
    $app->render(200, ['result' => User::all()]);
});

$app->get($appConfig['apiPrefix'] . '/users/:id', function ($id) use ($app) {
    $user = User::find($id);
    if ($user) {
        $app->render(200, ['result' => $user]);
    } else {
        $app->render(404);
    }
});

$app->put($appConfig['apiPrefix'] . '/users/:id', function ($id) use ($app) {
    $user = User::find($id);

    $valid = $user->validate($app->request->post());
    if (!$valid) {
        $app->render(400, ['validation' => $user->errors]);
    }

    $user->fill($app->request->post());
    if ($user->save()) {
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
