<?php
// 1. Load initialization
require_once "../app/init.php";

// 2. Identify if it is an API request
$isApi = (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) ||
    (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false);

// In public/index.php

// public/index.php

if ($isApi || $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // 1. CLEAR ANY PREVIOUS HEADERS
    header_remove("Access-Control-Allow-Origin");

    // 2. SET SPECIFIC ORIGIN (No wildcards '*')
    header("Access-Control-Allow-Origin: http://localhost:5173");

    // 3. ALLOW CREDENTIALS (This allows the PHPSESSID cookie)
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