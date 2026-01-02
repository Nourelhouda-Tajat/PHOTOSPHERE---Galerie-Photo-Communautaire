<?php

final class Database
{
    private static ?PDO $connection = null;

    private function __construct() {}

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {

            // 1️⃣ Charger la configuration
            $config = require __DIR__ . '/../../config/database.php';

            // 2️⃣ Construire le DSN
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8";

            // 3️⃣ Créer la connexion PDO
            self::$connection = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                $config['options']
            );
        }

        return self::$connection;
    }
}
