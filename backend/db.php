<?php
use Illuminate\Database\Capsule\Manager as Capsule;

require('../config.php');

/**
 * Configure the database and boot Eloquent
 */
$capsule = new Capsule;
$capsule->addConnection($appConfig['db']);
$capsule->addConnection($appConfig['userDb'], 'userDb');
$capsule->setAsGlobal();
$capsule->bootEloquent();
// set timezone for timestamps etc
date_default_timezone_set($appConfig['timezone']);