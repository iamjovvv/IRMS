<?php

// session_start();

// if (
//     $_SERVER['SERVER_NAME'] === 'localhost' && isset($_GET['as_role'])
// ){
//     $_SESSION['user_id'] = 1;
//     $_SESSION['role'] = $_GET['as_role'];
// }

// if (!isset($_SESSION['user_id'], $_SESSION['role'])) {
//     header('Location: /login.php');
//     exit;
// }

// $allowedRoles = ['admin', 'staff', 'reporter'];

// if (
//     $_SERVER['SERVER_NAME'] === 'localhost' &&
//     isset($_GET['as_role']) &&
//     in_array($_GET['as_role'], $allowedRoles, true)
// ) {
//     $_SESSION['user_id'] = 1;
//     $_SESSION['role'] = $_GET['as_role'];
// }



// $allowedRoles = ['admin', 'staff', 'reporter'];

// if(
//     $_SERVER['SERVER_NAME'] === 'localhost' && 
//     isset($_GET['as_role']) &&
//     in_array($_GET['as_role'], $allowedRoles, true)
// ){
//     $_SESSION['user_id'] =1;
//     $_SESSION['role'] = $_GET['as_role'];
// }


class Auths
{
    public static function requireRole(string $role)
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
            http_response_code(403);
            exit('Access denied');
        }
    }

    public static function requireAny(array $roles)
    {
        if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $roles, true)) {
            http_response_code(403);
            exit('Access denied');
        }
    }

}


?>