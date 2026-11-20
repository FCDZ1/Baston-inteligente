<?php
/**
 * sesion.php - Manejo seguro de sesiones y cookies de sesión.
 * Todas las páginas que usan $_SESSION deben iniciarla con iniciar_sesion_segura()
 * (nunca con session_start() directo), así la configuración es siempre la misma.
 */
require_once __DIR__ . '/config.php';

const SESION_NOMBRE      = 'baston_sid';  // nombre propio: no se mezcla con otras apps en localhost
const SESION_INACTIVIDAD = 1800;          // 30 minutos sin actividad => se cierra la sesión
const PASSWORD_COSTO     = 12;            // costo de bcrypt

function es_https(): bool
{
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['SERVER_PORT'] ?? '') === '443');
}

/** Inicia la sesión con cookie endurecida (HttpOnly, SameSite, Secure si hay HTTPS). */
function iniciar_sesion_segura(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    ini_set('session.use_strict_mode', '1');   // rechaza IDs de sesión que el servidor no creó
    ini_set('session.use_only_cookies', '1');  // el ID nunca viaja en la URL
    ini_set('session.use_trans_sid', '0');
    session_name(SESION_NOMBRE);
    session_set_cookie_params([
        'lifetime' => 0,                       // cookie de sesión: se borra al cerrar el navegador
        'path'     => '/',
        'secure'   => es_https(),              // en producción con HTTPS se activa sola
        'httponly' => true,                    // JavaScript no puede leer la cookie
        'samesite' => 'Lax',                   // no se envía en peticiones cruzadas
    ]);
    session_start();
}

/**
 * Datos del usuario logueado o null. No crea sesiones nuevas para visitantes anónimos:
 * solo la abre si el navegador ya trae la cookie.
 */
function usuario_actual(): ?array
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        if (!isset($_COOKIE[SESION_NOMBRE])) {
            return null;
        }
        iniciar_sesion_segura();
    }
    if (!isset($_SESSION['usuario_id'])) {
        return null;
    }
    return [
        'id'     => (int) $_SESSION['usuario_id'],
        'nombre' => (string) ($_SESSION['usuario_nombre'] ?? ''),
        'rol'    => (string) ($_SESSION['rol'] ?? ''),
    ];
}

/** Cierre completo: vacía $_SESSION, borra la cookie del navegador y destruye la sesión. */
function cerrar_sesion(): void
{
    iniciar_sesion_segura();
    $_SESSION = [];                                          // unset de todas las variables
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', [                      // cookie vencida => el navegador la elimina
            'expires'  => time() - 42000,
            'path'     => $p['path'],
            'domain'   => $p['domain'],
            'secure'   => $p['secure'],
            'httponly' => $p['httponly'],
            'samesite' => $p['samesite'] ?: 'Lax',
        ]);
    }
    session_destroy();                                       // destruye los datos en el servidor
}
