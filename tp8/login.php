<?php
/**
 * login.php - Inicio de sesión.
 * Compara la contraseña con el hash guardado usando password_verify(). Si es correcta:
 * session_regenerate_id(true) (anti fijación de sesión) y se guardan los datos del usuario.
 */
require_once __DIR__ . '/config/conexion.php';
require_once __DIR__ . '/includes/formularios.php';

// Hash descartable del mismo costo: se verifica contra él cuando el correo no existe,
// para que el tiempo de respuesta no revele qué correos están registrados.
const HASH_FALSO = '$2y$12$p7VFaYUTgcclw6bAfS3nVulFJ7z.cwWIiFraezQ8PYDlt.lUTxDJC';

iniciar_sesion_segura();
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

if (isset($_SESSION['usuario_id'])) {          // ya está logueado
    header('Location: dashboard.php');
    exit;
}

// Mensajes permitidos según ?error= y ?mensaje= (lista blanca: nunca se imprime el valor recibido)
$avisos_error = [
    'acceso_denegado' => 'Tenés que iniciar sesión para ver esa página.',
    'sesion_expirada' => 'Tu sesión expiró por inactividad. Ingresá de nuevo.',
];
$avisos_ok = [
    'registro_ok' => '¡Cuenta creada correctamente! Ya podés ingresar.',
    'logout'      => 'Cerraste sesión correctamente.',
];
$aviso_error = $avisos_error[get_limpio('error')] ?? null;
$aviso_ok    = $avisos_ok[get_limpio('mensaje')] ?? null;

$errores = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = mb_strtolower(post_limpio('email'), 'UTF-8');
    $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';   // no se modifica

    if (!csrf_valido(post_limpio('csrf'))) {
        $errores['general'] = 'El formulario expiró. Recargá la página e intentá de nuevo.';
    } elseif ($email === '' || $password === '') {
        $errores['general'] = 'Completá tu correo y tu contraseña.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) > 72) {
        $errores['general'] = 'Correo o contraseña incorrectos.';
    } else {
        $stmt = $pdo->prepare(
            'SELECT u.id, u.nombre, u.password_hash, u.activo, r.nombre AS rol
             FROM usuarios u
             INNER JOIN roles r ON r.id = u.rol_id
             WHERE u.email = :email'
        );
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch();

        $hash = $usuario['password_hash'] ?? HASH_FALSO;
        $valida = password_verify($password, $hash);

        if ($usuario && $valida && $usuario['activo']) {
            // Si el costo de bcrypt cambió desde que se guardó el hash, se actualiza
            if (password_needs_rehash($hash, PASSWORD_BCRYPT, ['cost' => PASSWORD_COSTO])) {
                $nuevo = password_hash($password, PASSWORD_BCRYPT, ['cost' => PASSWORD_COSTO]);
                $up = $pdo->prepare('UPDATE usuarios SET password_hash = :hash WHERE id = :id');
                $up->execute([':hash' => $nuevo, ':id' => $usuario['id']]);
            }

            session_regenerate_id(true);            // nuevo ID de sesión: mitiga la fijación de sesión
            unset($_SESSION['csrf']);               // el token del formulario no se reutiliza
            $_SESSION['usuario_id']     = (int) $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['rol']            = $usuario['rol'];
            $_SESSION['ultima_actividad'] = time();

            header('Location: dashboard.php');
            exit;
        }
        // Mismo mensaje para "no existe", "clave incorrecta" y "cuenta desactivada"
        $errores['general'] = 'Correo o contraseña incorrectos.';
    }
}

$titulo_pagina = 'Ingresar';
$descripcion_pagina = 'Iniciá sesión para acceder a tu panel del Bastón Inteligente.';
require_once __DIR__ . '/includes/header.php';
?>

<main id="contenido-principal" class="contenedor" style="display:block;">
  <h1>Ingresar</h1>
  <p>¿Todavía no tenés cuenta? <a href="registro.php">Registrate acá</a>.</p>

<?php if ($aviso_ok): ?>
  <div class="alerta alerta-exito" role="status"><p><?= e($aviso_ok) ?></p></div>
<?php endif; ?>
<?php if ($aviso_error && !$errores): ?>
  <div class="alerta alerta-info" role="alert"><p><?= e($aviso_error) ?></p></div>
<?php endif; ?>
<?php if ($errores): ?>
  <div class="alerta alerta-error" role="alert"><p><?= e($errores['general']) ?></p></div>
<?php endif; ?>

  <form class="form-auth" action="login.php" method="post" novalidate>
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

    <div class="campo">
      <label for="email">Correo electrónico</label>
      <input type="email" id="email" name="email" maxlength="100" autocomplete="username"
             value="<?= e($email) ?>">
    </div>

    <div class="campo">
      <label for="password">Contraseña</label>
      <input type="password" id="password" name="password" maxlength="72" autocomplete="current-password">
    </div>

    <button type="submit">Ingresar</button>
  </form>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
