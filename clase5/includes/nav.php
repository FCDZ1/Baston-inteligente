<?php
/**
 * nav.php - Menú principal. Marca como activa la página actual.
 */
$pagina_actual = basename($_SERVER['PHP_SELF']);

// Páginas que "pertenecen" a una sección del menú
$seccion_de = [
    'guia-estudio.php' => 'curso.php',
];
$activa = $seccion_de[$pagina_actual] ?? $pagina_actual;

$menu = [
    'index.php'         => 'Inicio',
    'curso.php'         => 'Curso',
    'blog.php'          => 'Blog',
    'faq.php'           => 'Preguntas Frecuentes',
    'quienes-somos.php' => '¿Quiénes Somos?',
    'contactos.php'     => 'Contactos',
];
?>
  <nav aria-label="Navegación principal">
    <ul class="menu" id="menu-principal">
<?php foreach ($menu as $archivo => $texto): ?>
<?php if ($archivo === $activa): ?>
      <li><a href="<?= e($archivo) ?>" class="active" aria-current="page"><?= e($texto) ?></a></li>
<?php else: ?>
      <li><a href="<?= e($archivo) ?>"><?= e($texto) ?></a></li>
<?php endif; ?>
<?php endforeach; ?>
    </ul>
  </nav>
