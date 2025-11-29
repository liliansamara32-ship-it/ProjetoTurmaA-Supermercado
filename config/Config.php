<?php

namespace GrupoA\Supermercado\Config;

use PHPMailer\PHPMailer\PHPMailer;

class Config
{
    public static function getDatabaseConfig(): array
    {
        return [
            'host' => getenv('DB_HOST') ?: '192.168.0.231',
            'name' => getenv('DB_NAME') ?: 'BANCOSID',
            'user' => getenv('DB_USER') ?: 'Sidsmart',
            'pass' => getenv('DB_PASS') ?: 'Senha2DS!',
        ];
    }

    public static function getEmailConfig(): array
    {
        return [
            'host' => 'smtp.gmail.com',
            'username' => 'sidsmart404@gmail.com',
            'password' => getenv('EMAIL_PASSWORD') ?: 'SENHA_DO_APP_FIXA', // It's better to use an environment variable
            'secure' => PHPMailer::ENCRYPTION_STARTTLS,
            'port' => 587,
            'from_email' => 'sidsmart404@gmail.com',
            'from_name' => 'Supermercado',
        ];
    }
}
