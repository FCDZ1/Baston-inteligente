<?php
/**
 * config.php - Carga las variables de entorno desde ".env"
 * y define las constantes que usa el sitio.
 */

function cargar_env(string $ruta): array
{
    $variables = [];
    if (!is_readable($ruta)) {
        return $variables;
    }
    foreach (file($ruta, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linea) {
        $linea = trim($linea);
        if ($linea === '' || $linea[0] === '#' || strpos($linea, '=') === false) {
            continue;
        }
        [$clave, $valor] = explode('=', $linea, 2);
        $valor = trim($valor);
        // Quita comillas simples o dobles alrededor del valor
        if (strlen($valor) >= 2 && in_array($valor[0], ['"', "'"], true) && substr($valor, -1) === $valor[0]) {
            $valor = substr($valor, 1, -1);
        }
        $variables[trim($clave)] = $valor;
    }
    return $variables;
}

$env = cargar_env(__DIR__ . '/../.env');

// Valores por defecto si falta el .env o alguna variable
define('APP_NAME', $env['APP_NAME'] ?? 'Bastón Inteligente');
define('CONTACT_EMAIL', $env['CONTACT_EMAIL'] ?? 'contacto@ejemplo.com');
define('APP_ENV', $env['APP_ENV'] ?? 'production');

// Conexión a MySQL (los valores reales van en .env, que no se sube a Git)
define('DB_HOST', $env['DB_HOST'] ?? 'localhost');
define('DB_PORT', $env['DB_PORT'] ?? '3306');
define('DB_NAME', $env['DB_NAME'] ?? 'baston_inteligente');
define('DB_USER', $env['DB_USER'] ?? 'root');
define('DB_PASS', $env['DB_PASS'] ?? '');

// En local se muestran los errores; en producción no
if (APP_ENV === 'local') {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
}

/** Escapa texto para imprimirlo de forma segura en HTML. */
function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}
