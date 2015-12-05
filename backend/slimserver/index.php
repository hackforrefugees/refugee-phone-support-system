<?php
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
$app->run();
?>
