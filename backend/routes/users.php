<?php

require('../config.php');

// auth
$app->post('/login', function() use ($app, $appConfig) {
    $user = User::whereEmail($app->request->post('email'))->first();
    if (!$user) {
        $app->render(403);
    }

    $password = $app->request->post('password');
    if (password_verify($password, $user->password_hash)) {
        $signer = new Lcobucci\JWT\Signer\Hmac\Sha256();
        $token = (new Lcobucci\JWT\Builder())->setIssuer('http://example.com') // Configures the issuer (iss claim)
                        ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
                        ->setExpiration(time() + 3600) // Configures the expiration time of the token (exp claim)
                        ->set('user_id', $user->id)
                        ->set('role', $user->role)
                        ->sign($signer, $appConfig['jwtSecret'])
                        ->getToken(); // Retrieves the generated token

        $app->render(200, ['token' => (string)$token]);
    } else {
        $app->render(403);
    }
});

$app->post('/register', function() use ($app) {
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
