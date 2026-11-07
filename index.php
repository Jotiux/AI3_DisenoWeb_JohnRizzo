<?php
/**
 * Punto de entrada único (front controller), igual que el ejemplo del docente:
 * decide qué acción del controlador ejecutar según el método HTTP.
 */
require_once __DIR__ . '/controllers/EstudianteController.php';

$controller = new EstudianteController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'guardar') {
    $controller->guardar();
} else {
    $controller->index();
}
