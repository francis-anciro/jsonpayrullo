<?php
require_once "../app/init.php";

$isApi = (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) ||
    (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false);


if ($isApi || $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
//    CORS
    header_remove("Access-Control-Allow-Origin");
    header("Access-Control-Allow-Origin: http://localhost:5173");

    header("Access-Control-Allow-Credentials: true");

    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Accept, Authorization, X-Requested-With");

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        http_response_code(200);
        exit;
    }
}

$app = new App;