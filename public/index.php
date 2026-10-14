<?php
/**
 * Punto de entrada de la aplicación: todas las peticiones pasan por aquí.
 * Carga las clases, decide qué controlador responde y maneja los errores.
 */

declare(strict_types=1);

use App\Controllers\CityController;

// Carga cada clase la primera vez que se usa: App\Models\City -> app/Models/City.php
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $file = __DIR__ . '/../app/' . $relative . '.php';
    if (is_file($file)) {
        require $file;
    }
});

/** Escapa el texto antes de mostrarlo en HTML (evita XSS). */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/** Formato con puntos de miles: 7472000 -> 7.472.000 */
function num(int|float|null $value): string
{
    return number_format((float) $value, 0, ',', '.');
}

// Qué controlador y método atienden cada ruta (?route=...)
$routes = [
    'home'       => [CityController::class, 'index'],
    'api/cities' => [CityController::class, 'apiCities'],
];

$route = $_GET['route'] ?? 'home';

try {
    if (!isset($routes[$route])) {
        http_response_code(404);
        echo 'Página no encontrada';
        exit;
    }

    [$controllerClass, $method] = $routes[$route];
    (new $controllerClass())->$method();
} catch (PDOException $ex) {
    // El detalle técnico queda en el log; al usuario solo le mostramos un mensaje claro
    error_log('[DB] ' . $ex->getMessage());
    http_response_code(500);
    echo 'No fue posible conectar con la base de datos. Verifica que MySQL esté encendido y que la BD "world" exista.';
}
