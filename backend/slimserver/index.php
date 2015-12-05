<?php
require 'vendor/autoload.php';
$app = new \Slim\Slim();
$app->get('/hello/:name', function ($name) {

    $mysqli = new mysqli("192.168.1.188", "refugeephone", "phones2000", "refugeephone", 8889);
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }
    if ($result = $mysqli->query("SELECT * FROM orders")) {

        /* parse to array */
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $rows[] = $row;
        }

        /* free result set */
        $result->close();
    }
    echo json_encode($rows);
});
$app->run();
?>
