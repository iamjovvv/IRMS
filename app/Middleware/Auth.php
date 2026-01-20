<?php

class Auth
{
    public static function requireLogin()
    {
        session_start();

        if (!isset($_SESSION['user'])) {
            header('Location: /RMS/public/index.php?url=login');
            exit;
        }
    }

    public static function requireRole(array $roles)
    {
        self::requireLogin();

        if (!in_array($_SESSION['user']['role'], $roles)) {
            http_response_code(403);
            die('Access denied.');
        }
    }
}
