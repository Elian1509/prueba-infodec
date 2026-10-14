<?php

namespace App\Core;

use PDO;

/**
 * Maneja la conexión a MySQL.
 * Se crea una sola vez y todos los modelos la reutilizan (Singleton).
 */
class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        // Solo se conecta la primera vez; las siguientes devuelve la misma conexión.
        if (self::$connection === null) {
            $config = require __DIR__ . '/../../config/config.php';
            $db = $config['db'];

            $dsn = "mysql:host={$db['host']};port={$db['port']};dbname={$db['name']};charset=utf8mb4";

            self::$connection = new PDO($dsn, $db['user'], $db['password'], [
                // Si un SQL falla, que lance un error en vez de seguir como si nada
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                // Las filas llegan con el nombre de la columna: $fila['Name']
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                // Los parámetros viajan separados del SQL (evita inyección SQL)
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        }

        return self::$connection;
    }
}
