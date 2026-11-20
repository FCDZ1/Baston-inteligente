<?php
/**
 * blog.php - Listado de entradas con buscador y filtro (método GET).
 * Parámetros: ?q=texto&categoria=Electrónica
 * Los parámetros se sanitizan y validan antes de usarse, y se imprimen con e().
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/formularios.php';

// Entradas del blog
$entradas = [
    [
        'imagen'     => 'img/blog-post1.svg',
        'alt'        => 'Persona probando el bastón inteligente en la calle',
        'titulo'     => 'Probamos el bastón en la calle',
        'fecha'      => '10/08/2026',
        'categoria'  => 'Pruebas de campo',
        'intro'      => 'Después de varias semanas de trabajo en el taller, llevamos el prototipo a la calle por primera vez. El sensor detectó correctamente postes, escalones y autos estacionados, y el motor vibrador avisó con la anticipación esperada.',
        'subtitulo'  => 'Lo que aprendimos',
        'detalle'    => 'Notamos que en espacios muy concurridos las vibraciones constantes podían resultar molestas, así que ajustamos el umbral de distancia para reducir las falsas alarmas.',
        'comentarios' => [
            ['autor' => 'Marina G.', 'texto' => '¡Excelente iniciativa! ¿Van a compartir el código completo en el curso?', 'enlace' => false],
            ['autor' => 'Equipo Bastón Inteligente', 'texto' => '¡Sí! Todo el código de esta etapa ya está en la guía de estudio.', 'enlace' => true],
        ],
    ],
    [
        'imagen'     => 'img/blog-post2.svg',
        'alt'        => 'Componentes electrónicos del bastón sobre una mesa de trabajo',
        'titulo'     => 'Componentes que usamos y por qué los elegimos',
        'fecha'      => '02/08/2026',
        'categoria'  => 'Electrónica',
        'intro'      => 'Elegimos el sensor ultrasónico HC-SR04 por su bajo costo y precisión suficiente para distancias cortas. Lo combinamos con un Arduino Uno, es recomendable usar un arduino nano para ahorrar mas espacio, ideal para integrarlo dentro del bastón.',
        'subtitulo'  => 'Lista de materiales',
        'detalle'    => 'Arduino Nano, sensor HC-SR04, motor vibrador, batería LiPo recargable y regulador de voltaje.',
        'comentarios' => [
            ['autor' => 'Julián R.', 'texto' => '¿Sirve reemplazar el Arduino Nano por un Uno para quien recién arranca?', 'enlace' => false],
            ['autor' => 'Equipo Bastón Inteligente', 'texto' => 'Totalmente, el Uno funciona igual de bien para practicar antes del montaje final.', 'enlace' => false],
        ],
    ],
];

// Categorías disponibles (lista blanca para el filtro)
$categorias = array_values(array_unique(array_column($entradas, 'categoria')));
sort($categorias);

// 1) Captura + sanitización de los parámetros GET
$busqueda  = get_limpio('q');
$categoria = get_limpio('categoria');
$error_busqueda = '';

// 2) Validación
if (largo($busqueda) > 50) {
    $error_busqueda = 'La búsqueda no puede superar los 50 caracteres.';
    $busqueda = '';
}
if ($categoria !== '' && !in_array($categoria, $categorias, true)) {
    $error_busqueda = 'La categoría elegida no es válida.';
    $categoria = '';
}

// 3) Filtrado
$filtrando = ($busqueda !== '' || $categoria !== '');
$resultados = array_filter($entradas, function (array $entrada) use ($busqueda, $categoria): bool {
    if ($categoria !== '' && $entrada['categoria'] !== $categoria) {
        return false;
    }
    if ($busqueda === '') {
        return true;
    }
    $texto = $entrada['titulo'] . ' ' . $entrada['categoria'] . ' ' . $entrada['intro'] . ' '
           . $entrada['subtitulo'] . ' ' . $entrada['detalle'];
    return mb_stripos($texto, $busqueda, 0, 'UTF-8') !== false;
});

$titulo_pagina = 'Blog';
$descripcion_pagina = 'Novedades, avances y consejos sobre el proyecto Bastón Inteligente: tecnología asistiva, electrónica y accesibilidad.';
require_once __DIR__ . '/includes/header.php';
?>

<main id="contenido-principal" class="contenedor" style="display:block;">
  <h1>Blog</h1>
  <p>Novedades y aprendizajes del proyecto Bastón Inteligente.</p>

  <form class="form-busqueda" action="blog.php" method="get" role="search" aria-label="Buscar en el blog">
    <div class="campo">
      <label for="q">Buscar en el blog</label>
      <input type="search" id="q" name="q" maxlength="50" placeholder="Ej: sensor, calle, Arduino"
             value="<?= e($busqueda) ?>">
    </div>
    <div class="campo">
      <label for="categoria">Categoría</label>
      <select id="categoria" name="categoria">
        <option value="">Todas</option>
<?php foreach ($categorias as $cat): ?>
        <option value="<?= e($cat) ?>"<?= $categoria === $cat ? ' selected' : '' ?>><?= e($cat) ?></option>
<?php endforeach; ?>
      </select>
    </div>
    <button type="submit">Buscar</button>
<?php if ($filtrando): ?>
    <a class="boton boton-secundario" href="blog.php">Limpiar</a>
<?php endif; ?>
  </form>

<?php if ($error_busqueda): ?>
  <div class="alerta alerta-error" role="alert"><p><?= e($error_busqueda) ?></p></div>
<?php endif; ?>

<?php if ($filtrando): ?>
  <p class="resumen-busqueda" role="status">
    <?= count($resultados) ?> <?= count($resultados) === 1 ? 'entrada encontrada' : 'entradas encontradas' ?>
<?php if ($busqueda !== ''): ?>
    para «<strong><?= e($busqueda) ?></strong>»
<?php endif; ?>
<?php if ($categoria !== ''): ?>
    en la categoría <strong><?= e($categoria) ?></strong>
<?php endif; ?>
  </p>
<?php endif; ?>

<?php if (!$resultados): ?>
  <div class="alerta alerta-info" role="status">
    <p>No encontramos entradas con esos criterios. Probá con otra palabra o <a href="blog.php">mirá todas las entradas</a>.</p>
  </div>
<?php endif; ?>

<?php foreach ($resultados as $entrada): ?>
  <article class="entrada-blog">
    <img src="<?= e($entrada['imagen']) ?>" alt="<?= e($entrada['alt']) ?>">
    <h2><?= e($entrada['titulo']) ?></h2>
    <p class="meta-blog">Publicado el <?= e($entrada['fecha']) ?> · Categoría: <?= e($entrada['categoria']) ?></p>
    <p><?= e($entrada['intro']) ?></p>
    <h3><?= e($entrada['subtitulo']) ?></h3>
    <p><?= e($entrada['detalle']) ?></p>

    <section class="comentario" aria-label="Comentarios de la entrada">
      <h4>Comentarios</h4>
<?php foreach ($entrada['comentarios'] as $comentario): ?>
      <div class="comentario">
        <h4><?= e($comentario['autor']) ?></h4>
<?php if ($comentario['enlace']): ?>
        <p><?= e($comentario['texto']) ?> <a href="guia-estudio.php">Ver la guía de estudio</a>.</p>
<?php else: ?>
        <p><?= e($comentario['texto']) ?></p>
<?php endif; ?>
      </div>
<?php endforeach; ?>
    </section>
  </article>
<?php endforeach; ?>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
