<?php
/**
 * auth_guard.php - Guardián de rutas (se incluye al INICIO de cada página protegida,
 * antes de cualquier salida HTML).
 *
 *   $base = '';                      // '../' si la página está dentro de admin/
 *   $roles_permitidos = ['admin'];   // opcional; si se omite basta con estar autenticado
 *   require_once __DIR__ . '/includes/auth_guard.php';
 *
 * - Sin sesión iniciada            -> redirige a login.php?error=acceso_denegado
 * - Sesión inactiva más de 30 min  -> cierra la sesión y redirige a login.php
 * - Rol no permitido               -> responde 403 con un mensaje
 * El rol se vuelve a leer de la base en cada pedido: si a alguien le quitan el
 * rol de admin o lo desactivan, pierde el acceso de inmediato.
 */
require_once __DIR__ . '/sesion.php';
require_once __DIR__ . '/../config/conexion.php';

function proteger_ruta(PDO $pdo, string $base, ?array $roles): void
{
    iniciar_sesion_segura();

    // Las páginas privadas no se guardan en caché (evita verlas con el botón "Atrás" tras salir)
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');

    if (!isset($_SESSION['usuario_id'])) {
        header('Location: ' . $base . 'login.php?error=acceso_denegado');
        exit;
    }

    if (time() - (int) ($_SESSION['ultima_actividad'] ?? 0) > SESION_INACTIVIDAD) {
        cerrar_sesion();
        header('Location: ' . $base . 'login.php?error=sesion_expirada');
        exit;
    }
    $_SESSION['ultima_actividad'] = time();

    $stmt = $pdo->prepare(
        'SELECT u.nombre, u.activo, r.nombre AS rol
         FROM usuarios u
         INNER JOIN roles r ON r.id = u.rol_id
         WHERE u.id = :id'
    );
    $stmt->execute([':id' => (int) $_SESSION['usuario_id']]);
    $fila = $stmt->fetch();

    if (!$fila || !$fila['activo']) {
        cerrar_sesion();
        header('Location: ' . $base . 'login.php?error=acceso_denegado');
        exit;
    }
    $_SESSION['usuario_nombre'] = $fila['nombre'];
    $_SESSION['rol']            = $fila['rol'];

    if ($roles !== null && !in_array($fila['rol'], $roles, true)) {
        http_response_code(403);
        $titulo_pagina = 'Acceso restringido';
        require __DIR__ . '/header.php';
        echo '<main id="contenido-principal" class="contenedor" style="display:block;">' . "\n";
        echo '  <h1>Acceso restringido</h1>' . "\n";
        echo '  <div class="alerta alerta-error" role="alert"><p>Tu rol (' . e($fila['rol'])
           . ') no tiene permiso para ver esta página.</p></div>' . "\n";
        echo '  <p><a class="boton" href="' . e($base) . 'dashboard.php">Volver a mi panel</a></p>' . "\n";
        echo '</main>' . "\n";
        require __DIR__ . '/footer.php';
        exit;
    }
}

proteger_ruta($pdo, $base ?? '', $roles_permitidos ?? null);
