<?php
/**
 * admin/crear.php - Formulario + inserción (Create).
 */
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../includes/entradas_crud.php';

$categorias = listar_categorias($pdo);
$autores    = listar_autores($pdo);
$datos      = entrada_vacia();
$errores    = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos   = leer_entrada_post();
    $errores = validar_entrada($datos, $categorias, $autores);

    if (!$errores) {
        try {
            $sql = 'INSERT INTO entradas (autor_id, categoria_id, titulo, resumen, contenido, imagen, publicado, fecha_publicacion)
                    VALUES (:autor_id, :categoria_id, :titulo, :resumen, :contenido, :imagen, :publicado, :fecha)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':autor_id'     => (int) $datos['autor_id'],
                ':categoria_id' => (int) $datos['categoria_id'],
                ':titulo'       => $datos['titulo'],
                ':resumen'      => $datos['resumen'],
                ':contenido'    => $datos['contenido'],
                ':imagen'       => $datos['imagen'] !== '' ? $datos['imagen'] : null,
                ':publicado'    => $datos['publicado'],
                ':fecha'        => $datos['fecha_publicacion'],
            ]);
            header('Location: index.php?status=created');
            exit;
        } catch (PDOException $e) {
            error_log('crear.php: ' . $e->getMessage());
            $errores['general'] = 'No se pudo guardar la entrada. Intentá nuevamente.';
        }
    }
}

$base = '../';
$accion = 'crear.php';
$texto_boton = 'Guardar entrada';
$titulo_pagina = 'Nueva entrada';
$descripcion_pagina = 'Formulario para crear una entrada del blog.';
require_once __DIR__ . '/../includes/header.php';
?>

<main id="contenido-principal" class="contenedor" style="display:block;">
  <h1>Nueva entrada</h1>

<?php if ($errores): ?>
  <div class="alerta alerta-error" role="alert">
    <h2>Revisá el formulario</h2>
    <ul>
<?php foreach ($errores as $mensaje_error): ?>
      <li><?= e($mensaje_error) ?></li>
<?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<?php require __DIR__ . '/_form.php'; ?>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
