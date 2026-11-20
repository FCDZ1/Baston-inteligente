<?php
/**
 * nav.php - Menú principal. Marca como activa la página actual.
 */
$base = $base ?? '';
$pagina_actual = basename($_SERVER['PHP_SELF']);
$en_admin = str_contains(str_replace('\\', '/', $_SERVER['PHP_SELF']), '/admin/');

// Páginas que "pertenecen" a una sección del menú
$seccion_de = [
    'guia-estudio.php' => 'curso.php',
];
$activa = $en_admin ? 'admin/index.php' : ($seccion_de[$pagina_actual] ?? $pagina_actual);

$menu = [
    'index.php'         => 'Inicio',
    'curso.php'         => 'Curso',
    'blog.php'          => 'Blog',
    'faq.php'           => 'Preguntas Frecuentes',
    'quienes-somos.php' => '¿Quiénes Somos?',
    'contactos.php'     => 'Contactos',
    'admin/index.php'   => 'Administración',
];
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
    </ul>
  </nav>
