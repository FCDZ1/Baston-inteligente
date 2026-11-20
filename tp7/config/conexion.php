<?php
/**
 * conexion.php - Conexión a MySQL con PDO.
 * Los datos de acceso vienen del archivo .env (ver includes/config.php).
 * Expone la variable $pdo. Todo el sitio usa consultas preparadas con ella.
 */
require_once __DIR__ . '/../includes/config.php';

$dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4';

$opciones = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // los errores lanzan excepciones
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,                    // prepared statements reales del motor
];

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $opciones);
} catch (PDOException $e) {
    error_log('Error de conexión a la base de datos: ' . $e->getMessage());
    http_response_code(500);
    // En local mostramos el detalle para depurar; en otros entornos, un mensaje genérico.
    if (APP_ENV === 'local') {
        die('Error crítico de conexión: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
    }
    die('No se pudo conectar con la base de datos. Intentá nuevamente más tarde.');
}
