<?php

require('../config.php');

// auth
$app->post('/login', function() use ($app, $appConfig) {

    $user = User::whereEmail($app->jsonBody['email'])->first();
    if (!$user) {
        $app->render(403);
    }

    $password = $app->jsonBody['password'];
    if (password_verify($password, $user->password_hash)) {
        $signer = new Lcobucci\JWT\Signer\Hmac\Sha256();
        $token = (new Lcobucci\JWT\Builder())->setIssuer('http://example.com') // Configures the issuer (iss claim)
                        ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
                        ->setExpiration(time() + 3600) // Configures the expiration time of the token (exp claim)
                        ->set('user_id', $user->id)
                        ->sign($signer, $appConfig['jwtSecret'])
                        ->getToken(); // Retrieves the generated token

        $app->render(200, ['token' => (string)$token, 'user' => $user]);
    } else {
        $app->render(403);
    }
});

$app->post('/register', function() use ($app) {
    $user = new User();
    $user->email = $app->jsonBody['email'];
    $user->password_hash = password_hash($app->jsonBody['password'], PASSWORD_DEFAULT);

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

    $valid = $user->validate($app->jsonBody);
    if (!$valid) {
        $app->render(400, ['validation' => $user->errors]);
    }

    $user->fill($app->jsonBody);
    if ($user->save()) {
        $app->render(200);
    } else {
        $app->render(500);
    }
});

// modules

$app->get($appConfig['apiPrefix'] . '/users/:id/modules', function ($id) use ($app) {
    $user = User::find($id);
    if ($user) {
        $app->render(200, ['result' => $user->modules()]);
    } else {
        $app->render(404);
    }
});
