<?php
/**
 * nav.php - Menú principal. Marca como activa la página actual.
 */
require_once __DIR__ . '/formularios.php';
$base = $base ?? '';
$pagina_actual = basename($_SERVER['PHP_SELF']);
$en_admin = str_contains(str_replace('\\', '/', $_SERVER['PHP_SELF']), '/admin/');

// Páginas que "pertenecen" a una sección del menú
$seccion_de = [
    'guia-estudio.php' => 'curso.php',
];
$activa = $en_admin ? 'admin_panel.php' : ($seccion_de[$pagina_actual] ?? $pagina_actual);

$menu = [
    'index.php'         => 'Inicio',
    'curso.php'         => 'Curso',
    'blog.php'          => 'Blog',
    'faq.php'           => 'Preguntas Frecuentes',
    'quienes-somos.php' => '¿Quiénes Somos?',
    'contactos.php'     => 'Contactos',
];

// Ítems que dependen de si hay un usuario logueado y de su rol
$usuario_sesion = $usuario_sesion ?? null;
if ($usuario_sesion) {
    $menu['dashboard.php'] = 'Mi panel';
    if ($usuario_sesion['rol'] === 'admin') {
        $menu['admin_panel.php'] = 'Administración';
    }
} else {
    $menu['login.php']    = 'Ingresar';
    $menu['registro.php'] = 'Registrarse';
}
?>
  <nav aria-label="Navegación principal">
    <ul class="menu" id="menu-principal">
<?php foreach ($menu as $archivo => $texto): ?>
<?php if ($archivo === $activa): ?>
      <li><a href="<?= e($base . $archivo) ?>" class="active" aria-current="page"><?= e($texto) ?></a></li>
<?php else: ?>
      <li><a href="<?= e($base . $archivo) ?>"><?= e($texto) ?></a></li>
<?php endif; ?>
<?php endforeach; ?>
<?php if ($usuario_sesion): ?>
      <li>
        <form action="<?= e($base) ?>logout.php" method="post" class="form-en-linea">
          <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
          <button type="submit" class="boton-menu">Cerrar sesión (<?= e($usuario_sesion['nombre']) ?>)</button>
        </form>
      </li>
<?php endif; ?>
    </ul>
  </nav>
