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
function userBadgeCheck($role) {
    $baseClasses = "inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset";

    switch ($role) {
        case 'admin':
            return '<span class="' . $baseClasses . ' bg-red-400/10 text-red-400 ring-red-400/20">Admin</span>';
        case 'manager':
            return '<span class="' . $baseClasses . ' bg-amber-400/10 text-amber-400 ring-amber-400/20">Manager</span>';
        case 'employee':
            return '<span class="' . $baseClasses . ' bg-indigo-400/10 text-indigo-400 ring-indigo-400/20">Employee</span>';
        default:
            return '<span class="' . $baseClasses . ' bg-slate-400/10 text-slate-400 ring-slate-400/20">' . htmlspecialchars($role) . '</span>';
    }
}
function isActive($pageName) {
    // Get the 'url' param from the GET request
    $currentUrl = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
    $urlArray = explode('/', $currentUrl);

    // Active: White text + absolute bottom indicator
    // Inactive: Muted zinc text + hover effect
    if ($urlArray[0] === $pageName) {
        return 'text-white after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-full after:bg-white';
    }

    return 'text-zinc-400 hover:text-white';
}