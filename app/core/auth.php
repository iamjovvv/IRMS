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



$allowedRoles = ['admin', 'staff', 'reporter'];

if(
    $_SERVER['SERVER_NAME'] === 'localhost' && 
    isset($_GET['as_role']) &&
    in_array($_GET['as_role'], $allowedRoles, true)
){
    $_SESSION['user_id'] =1;
    $_SESSION['role'] = $_GET['as_role'];
}

?>