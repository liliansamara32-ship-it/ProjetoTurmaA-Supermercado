<?php

namespace GrupoA\Supermercado\Service;

class Util
{
    public static function averigua()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }
    }
}
