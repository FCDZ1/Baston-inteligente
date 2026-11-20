<?php
/**
 * dashboard.php - Panel del usuario (página protegida: cualquier usuario autenticado).
 */
$base = '';
require_once __DIR__ . '/includes/auth_guard.php';     // redirige a login.php si no hay sesión
require_once __DIR__ . '/includes/formularios.php';

$stmt = $pdo->prepare(
    'SELECT u.nombre, u.apellido, u.email, u.created_at, r.nombre AS rol
     FROM usuarios u
     INNER JOIN roles r ON r.id = u.rol_id
     WHERE u.id = :id'
);
$stmt->execute([':id' => (int) $_SESSION['usuario_id']]);
$perfil = $stmt->fetch();

$titulo_pagina = 'Mi panel';
$descripcion_pagina = 'Panel privado del usuario.';
require_once __DIR__ . '/includes/header.php';
?>

<main id="contenido-principal" class="contenedor" style="display:block;">
  <h1>Bienvenido/a, <?= e($_SESSION['usuario_nombre']) ?></h1>
  <p>Esta página solo la ven los usuarios que iniciaron sesión.</p>

  <dl class="panel-datos">
    <dt>Nombre completo</dt>
    <dd><?= e($perfil['nombre'] . ' ' . $perfil['apellido']) ?></dd>
    <dt>Correo</dt>
    <dd><?= e($perfil['email']) ?></dd>
    <dt>Rol</dt>
    <dd><span class="insignia<?= $perfil['rol'] === 'admin' ? ' insignia-admin' : '' ?>"><?= e($perfil['rol']) ?></span></dd>
    <dt>Miembro desde</dt>
    <dd><?= e($perfil['created_at']) ?></dd>
  </dl>

<?php if ($_SESSION['rol'] === 'admin'): ?>
  <p><a class="boton" href="admin_panel.php">Ir al panel de administración</a></p>
<?php endif; ?>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
