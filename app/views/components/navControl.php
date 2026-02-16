<?php
$role = $data['role'];

switch ($role) {
    case 'admin':
        require 'nav_versions/adminNav.php';
        break;
    case 'manager':
        require 'nav_versions/managerNav.php';
        break;
    case 'employee':
        require 'nav_versions/employeeNav.php';
        break;
    default:
        echo "Role can't be determined";
        break;
}
