<?php
/**
 * admin/editar.php - Formulario + actualización (Update).
 */
$base = '../';
$roles_permitidos = ['admin'];
require_once __DIR__ . '/../includes/auth_guard.php';   // solo administradores
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../includes/entradas_crud.php';

// El id viaja por GET (?id=) tanto al abrir como al enviar el formulario
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    header('Location: index.php?status=notfound');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM entradas WHERE id = :id');
$stmt->execute([':id' => $id]);
$entrada = $stmt->fetch();
if (!$entrada) {
    header('Location: index.php?status=notfound');
    exit;
}

$categorias = listar_categorias($pdo);
$autores    = listar_autores($pdo);
$errores    = [];
$datos      = [
    'titulo'            => $entrada['titulo'],
    'categoria_id'      => (string) $entrada['categoria_id'],
    'autor_id'          => (string) $entrada['autor_id'],
    'resumen'           => $entrada['resumen'],
    'contenido'         => $entrada['contenido'],
    'imagen'            => (string) $entrada['imagen'],
    'fecha_publicacion' => $entrada['fecha_publicacion'],
    'publicado'         => (int) $entrada['publicado'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos   = leer_entrada_post();
    $errores = validar_entrada($datos, $categorias, $autores);

    if (!$errores) {
        try {
            $sql = 'UPDATE entradas
                    SET autor_id = :autor_id, categoria_id = :categoria_id, titulo = :titulo,
                        resumen = :resumen, contenido = :contenido, imagen = :imagen,
                        publicado = :publicado, fecha_publicacion = :fecha
                    WHERE id = :id';
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
                ':id'           => $id,
            ]);
            header('Location: index.php?status=updated');
            exit;
        } catch (PDOException $e) {
            error_log('editar.php: ' . $e->getMessage());
            $errores['general'] = 'No se pudo actualizar la entrada. Intentá nuevamente.';
        }
    }
}

$accion = 'editar.php?id=' . $id;
$texto_boton = 'Guardar cambios';
$titulo_pagina = 'Editar entrada';
$descripcion_pagina = 'Formulario para editar una entrada del blog.';
require_once __DIR__ . '/../includes/header.php';
?>

<main id="contenido-principal" class="contenedor" style="display:block;">
  <h1>Editar entrada #<?= (int) $id ?></h1>

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
