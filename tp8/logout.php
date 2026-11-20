<?php
/**
 * logout.php - Cierre de sesión.
 * Solo cierra con POST + token CSRF (un enlace de otro sitio no puede desloguearte).
 * Si se entra por GET se muestra una confirmación con el botón para cerrar.
 */
require_once __DIR__ . '/config/conexion.php';
require_once __DIR__ . '/includes/formularios.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    iniciar_sesion_segura();
    if (csrf_valido(post_limpio('csrf'))) {
        cerrar_sesion();                      // unset + cookie borrada + session_destroy()
        header('Location: login.php?mensaje=logout');
    } else {
        header('Location: index.php');
    }
    exit;
}

$titulo_pagina = 'Cerrar sesión';
require_once __DIR__ . '/includes/header.php';
?>

<main id="contenido-principal" class="contenedor" style="display:block;">
  <h1>Cerrar sesión</h1>
<?php if ($usuario_sesion): ?>
  <p>Estás ingresado como <strong><?= e($usuario_sesion['nombre']) ?></strong>. ¿Querés cerrar tu sesión?</p>
  <form class="form-auth" action="logout.php" method="post">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <button type="submit">Sí, cerrar sesión</button>
    <a class="boton boton-secundario" href="dashboard.php">Cancelar</a>
  </form>
<?php else: ?>
  <p>No hay ninguna sesión iniciada. <a href="login.php">Ir a ingresar</a>.</p>
<?php endif; ?>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
