<?php

class Auth
{
    public static function requireLogin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: /RMS/public/index.php?url=login');
            exit;
        }
    }

    public static function requireRole(array $roles)
    {
        self::requireLogin();

        if (
            !isset($_SESSION['user']['role']) ||
            !in_array($_SESSION['user']['role'], $roles, true)
        ) {
            http_response_code(403);
            exit('Access denied.');
        }
    }

    /* ================================
       Middleware-style helpers
       ================================ */

    public static function staffOnly()
    {
        self::requireRole(['staff']);
    }

    public static function responderOnly()
    {
        self::requireRole(['responder']);
    }

    public static function staffOrResponder()
    {
        self::requireRole(['staff', 'responder']);
    }

    public static function reporterOnly()
    {
        self::requireRole(['reporter']);
    }
}