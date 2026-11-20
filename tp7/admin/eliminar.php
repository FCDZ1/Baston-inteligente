<?php
/**
 * admin/eliminar.php - Lógica de eliminación (Delete).
 * Solo acepta POST con token CSRF: un enlace GET no puede borrar datos.
 */
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../includes/entradas_crud.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1 || !csrf_valido(post_limpio('csrf'))) {
    header('Location: index.php?status=error');
    exit;
}

try {
    $stmt = $pdo->prepare('DELETE FROM entradas WHERE id = :id');
    $stmt->execute([':id' => $id]);
    header('Location: index.php?status=' . ($stmt->rowCount() > 0 ? 'deleted' : 'notfound'));
} catch (PDOException $e) {
    error_log('eliminar.php: ' . $e->getMessage());
    header('Location: index.php?status=error');
}
exit;
