<?php
/**
 * Configuración de la aplicación.
 *
 * Los valores se leen de variables de entorno si existen; si no,
 * se usan los valores por defecto de Laragon.
 * Así no se suben credenciales por error al repositorio.
 */
return [
    'db' => [
        'host'     => getenv('DB_HOST') ?: '127.0.0.1',
        'port'     => (int) (getenv('DB_PORT') ?: 3306),
        'name'     => getenv('DB_NAME') ?: 'world',
        'user'     => getenv('DB_USER') ?: 'root',
        'password' => getenv('DB_PASS') ?: '',
    ],
];
