<?php
/**
 * admin/index.php - Listado de entradas (Read).
 */
$base = '../';
$roles_permitidos = ['admin'];
require_once __DIR__ . '/../includes/auth_guard.php';   // solo administradores
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../includes/entradas_crud.php';

// Mensajes permitidos según ?status= (lista blanca: nunca se imprime el valor recibido)
$mensajes = [
    'created' => ['exito', 'La entrada se creó correctamente.'],
    'updated' => ['exito', 'La entrada se actualizó correctamente.'],
    'deleted' => ['exito', 'La entrada se eliminó correctamente.'],
    'notfound' => ['error', 'La entrada solicitada no existe.'],
    'error'   => ['error', 'No se pudo completar la operación. Intentá nuevamente.'],
];
$status = get_limpio('status');
$aviso = $mensajes[$status] ?? null;

$sql = "SELECT e.id, e.titulo, e.fecha_publicacion, e.publicado,
               c.nombre AS categoria,
               CONCAT(u.nombre, ' ', u.apellido) AS autor
        FROM entradas e
        INNER JOIN categorias c ON c.id = e.categoria_id
        INNER JOIN usuarios u   ON u.id = e.autor_id
        ORDER BY e.id DESC";
$registros = $pdo->query($sql)->fetchAll();   // consulta fija, sin datos del usuario
$token = csrf_token();

$titulo_pagina = 'Administración de entradas';
$descripcion_pagina = 'Panel para crear, editar y eliminar las entradas del blog.';
require_once __DIR__ . '/../includes/header.php';
?>

<main id="contenido-principal" class="contenedor" style="display:block;">
  <h1>Administración de entradas</h1>
  <p>Gestioná las entradas del blog guardadas en la base de datos.</p>

<?php if ($aviso): ?>
  <div class="alerta alerta-<?= e($aviso[0]) ?>" role="<?= $aviso[0] === 'error' ? 'alert' : 'status' ?>">
    <p><?= e($aviso[1]) ?></p>
  </div>
<?php endif; ?>

  <p><a class="boton" href="crear.php">Agregar nueva entrada</a></p>

  <div class="tabla-scroll">
    <table class="tabla-admin">
      <thead>
        <tr>
          <th>ID</th>
          <th>Título</th>
          <th>Categoría</th>
          <th>Autor</th>
          <th>Fecha</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
<?php if (!$registros): ?>
        <tr><td colspan="7">Todavía no hay entradas cargadas.</td></tr>
<?php endif; ?>
<?php foreach ($registros as $item): ?>
        <tr>
          <td><?= e((string) $item['id']) ?></td>
          <td><?= e($item['titulo']) ?></td>
          <td><?= e($item['categoria']) ?></td>
          <td><?= e($item['autor']) ?></td>
          <td><?= e($item['fecha_publicacion']) ?></td>
          <td><?= $item['publicado'] ? 'Publicada' : 'Borrador' ?></td>
          <td class="acciones">
            <a class="boton boton-aviso" href="editar.php?id=<?= (int) $item['id'] ?>">Editar</a>
            <form action="eliminar.php" method="post" class="form-en-linea"
                  onsubmit="return confirm('¿Confirma eliminar este registro?')">
              <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
              <input type="hidden" name="csrf" value="<?= e($token) ?>">
              <button type="submit" class="boton-peligro">Eliminar</button>
            </form>
          </td>
        </tr>
<?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
