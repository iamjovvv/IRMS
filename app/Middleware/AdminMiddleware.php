<?php

function requireAdmin()
{
    if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
        http_response_code(403);
        die('Access denied');
    }
}


?>