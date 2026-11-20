<?php
/**
 * admin_panel.php - Panel de administración (solo rol 'admin').
 */
$base = '';
$roles_permitidos = ['admin'];
require_once __DIR__ . '/includes/auth_guard.php';     // sin sesión -> login; otro rol -> 403
require_once __DIR__ . '/includes/formularios.php';

// Consultas fijas (no reciben datos del usuario)
$totales = [
    'Usuarios registrados'  => (int) $pdo->query('SELECT COUNT(*) FROM usuarios')->fetchColumn(),
    'Entradas del blog'     => (int) $pdo->query('SELECT COUNT(*) FROM entradas')->fetchColumn(),
    'Consultas pendientes'  => (int) $pdo->query('SELECT COUNT(*) FROM consultas WHERE atendida = 0')->fetchColumn(),
];

$usuarios = $pdo->query(
    'SELECT u.id, u.nombre, u.apellido, u.email, u.activo, u.created_at, r.nombre AS rol
     FROM usuarios u
     INNER JOIN roles r ON r.id = u.rol_id
     ORDER BY u.id DESC'
)->fetchAll();

$titulo_pagina = 'Panel de administración';
$descripcion_pagina = 'Panel exclusivo para administradores.';
require_once __DIR__ . '/includes/header.php';
?>

<main id="contenido-principal" class="contenedor" style="display:block;">
  <h1>Panel de administración</h1>
  <p>Solo accesible con rol <strong>admin</strong>.</p>

  <div class="resumen-cuentas">
<?php foreach ($totales as $etiqueta => $cantidad): ?>
    <div><strong><?= $cantidad ?></strong><?= e($etiqueta) ?></div>
<?php endforeach; ?>
  </div>

  <p><a class="boton" href="admin/index.php">Administrar entradas del blog</a></p>

  <h2>Usuarios</h2>
  <div class="tabla-scroll">
    <table class="tabla-admin">
      <thead>
        <tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Rol</th><th>Estado</th><th>Alta</th></tr>
      </thead>
      <tbody>
<?php foreach ($usuarios as $u): ?>
        <tr>
          <td><?= e((string) $u['id']) ?></td>
          <td><?= e($u['nombre'] . ' ' . $u['apellido']) ?></td>
          <td><?= e($u['email']) ?></td>
          <td><span class="insignia<?= $u['rol'] === 'admin' ? ' insignia-admin' : '' ?>"><?= e($u['rol']) ?></span></td>
          <td><?= $u['activo'] ? 'Activo' : 'Inactivo' ?></td>
          <td><?= e($u['created_at']) ?></td>
        </tr>
<?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
