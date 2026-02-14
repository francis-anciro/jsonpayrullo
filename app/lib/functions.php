<?php
function dumpNDie($dump){
    echo '<pre>';
    var_dump($dump);
    echo '</pre>';
    die();
}

function redirect($page){
    header("Location: " .URLROOT. "/". $page);
    exit();
}

