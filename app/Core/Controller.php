<?php

namespace App\Core;

/**
 * Clase base de los controladores: muestra vistas o responde JSON.
 */
abstract class Controller
{
    /**
     * Muestra una vista dentro del layout (cabecera y pie comunes).
     *
     * @param string $view Nombre del archivo en app/Views, sin .php
     * @param array  $data Datos para la vista; $data['x'] queda disponible como $x
     */
    protected function view(string $view, array $data = []): void
    {
        extract($data);

        // Guardamos el HTML de la vista en $content para meterlo dentro del layout
        ob_start();
        require __DIR__ . "/../Views/{$view}.php";
        $content = ob_get_clean();

        require __DIR__ . '/../Views/layout.php';
    }

    /**
     * Responde en JSON con el código HTTP indicado (lo usa la API).
     */
    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
