<?php
/**
 * entradas_crud.php - Funciones del CRUD de entradas del blog.
 * Todas las consultas usan sentencias preparadas (PDO).
 */
require_once __DIR__ . '/formularios.php';

/** Categorías para el <select>. */
function listar_categorias(PDO $pdo): array
{
    return $pdo->query('SELECT id, nombre FROM categorias ORDER BY nombre')->fetchAll();
}

/** Usuarios que pueden figurar como autor (admin y editor). */
function listar_autores(PDO $pdo): array
{
    $sql = "SELECT u.id, CONCAT(u.nombre, ' ', u.apellido) AS nombre
            FROM usuarios u
            INNER JOIN roles r ON r.id = u.rol_id
            WHERE r.nombre IN (:r1, :r2) AND u.activo = 1
            ORDER BY u.nombre";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':r1' => 'admin', ':r2' => 'editor']);
    return $stmt->fetchAll();
}

/** Lee y sanitiza los datos del formulario de entradas ($_POST). */
function leer_entrada_post(): array
{
    return [
        'titulo'            => post_limpio('titulo'),
        'categoria_id'      => post_limpio('categoria_id'),
        'autor_id'          => post_limpio('autor_id'),
        'resumen'           => post_limpio('resumen', true),
        'contenido'         => post_limpio('contenido', true),
        'imagen'            => post_limpio('imagen'),
        'fecha_publicacion' => post_limpio('fecha_publicacion'),
        'publicado'         => isset($_POST['publicado']) ? 1 : 0,
    ];
}

/** Valores por defecto de un formulario vacío. */
function entrada_vacia(): array
{
    return [
        'titulo' => '', 'categoria_id' => '', 'autor_id' => '', 'resumen' => '',
        'contenido' => '', 'imagen' => '', 'fecha_publicacion' => date('Y-m-d'), 'publicado' => 1,
    ];
}

/** Valida los datos. Devuelve un arreglo campo => mensaje (vacío si todo está bien). */
function validar_entrada(array $d, array $categorias, array $autores): array
{
    $errores = [];

    if ($d['titulo'] === '') {
        $errores['titulo'] = 'El título es obligatorio.';
    } elseif (largo($d['titulo']) < 5 || largo($d['titulo']) > 150) {
        $errores['titulo'] = 'El título debe tener entre 5 y 150 caracteres.';
    }

    if (!in_array($d['categoria_id'], array_map(fn($c) => (string) $c['id'], $categorias), true)) {
        $errores['categoria_id'] = 'Elegí una categoría de la lista.';
    }

    if (!in_array($d['autor_id'], array_map(fn($a) => (string) $a['id'], $autores), true)) {
        $errores['autor_id'] = 'Elegí un autor de la lista.';
    }

    if ($d['resumen'] === '') {
        $errores['resumen'] = 'El resumen es obligatorio.';
    } elseif (largo($d['resumen']) > 500) {
        $errores['resumen'] = 'El resumen no puede superar los 500 caracteres.';
    }

    if ($d['contenido'] === '') {
        $errores['contenido'] = 'El contenido es obligatorio.';
    } elseif (largo($d['contenido']) < 20) {
        $errores['contenido'] = 'El contenido debe tener al menos 20 caracteres.';
    } elseif (largo($d['contenido']) > 5000) {
        $errores['contenido'] = 'El contenido no puede superar los 5000 caracteres.';
    }

    // Imagen opcional: solo una ruta relativa simple dentro del sitio (sin "..", sin http://)
    if ($d['imagen'] !== '' && (!preg_match('#^[A-Za-z0-9_\-/]+\.(svg|png|jpe?g|webp|gif)$#', $d['imagen'])
        || str_contains($d['imagen'], '..') || largo($d['imagen']) > 120)) {
        $errores['imagen'] = 'La imagen debe ser una ruta del sitio, por ejemplo img/blog-post1.svg.';
    }

    $f = DateTime::createFromFormat('Y-m-d', $d['fecha_publicacion']);
    if (!$f || $f->format('Y-m-d') !== $d['fecha_publicacion']) {
        $errores['fecha_publicacion'] = 'Ingresá una fecha válida.';
    }

    return $errores;
}
