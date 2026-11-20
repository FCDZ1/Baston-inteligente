<?php
/**
 * registro.php - Alta de usuarios.
 * Sanitiza y valida en servidor, verifica que el correo no exista y guarda la
 * contraseña como hash bcrypt con una consulta preparada.
 */
require_once __DIR__ . '/config/conexion.php';
require_once __DIR__ . '/includes/formularios.php';

iniciar_sesion_segura();

if (isset($_SESSION['usuario_id'])) {          // ya está logueado: no tiene sentido registrarse
    header('Location: dashboard.php');
    exit;
}

$errores = [];
$datos = ['nombre' => '', 'apellido' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1) Captura + sanitización. La contraseña NO se modifica (ni trim ni limpieza):
    //    cambiarla alteraría lo que la persona eligió. Nunca se imprime ni se reenvía al formulario.
    $datos['nombre']   = post_limpio('nombre');
    $datos['apellido'] = post_limpio('apellido');
    $datos['email']    = mb_strtolower(post_limpio('email'), 'UTF-8');
    $password  = is_string($_POST['password']  ?? null) ? $_POST['password']  : '';
    $password2 = is_string($_POST['password2'] ?? null) ? $_POST['password2'] : '';

    // 2) Validación en servidor
    if (!csrf_valido(post_limpio('csrf'))) {
        $errores['general'] = 'El formulario expiró. Recargá la página e intentá de nuevo.';
    }

    foreach (['nombre' => 'El nombre', 'apellido' => 'El apellido'] as $campo => $etiqueta) {
        if ($datos[$campo] === '') {
            $errores[$campo] = $etiqueta . ' es obligatorio.';
        } elseif (largo($datos[$campo]) < 2 || largo($datos[$campo]) > 60) {
            $errores[$campo] = $etiqueta . ' debe tener entre 2 y 60 caracteres.';
        } elseif (!preg_match('/^[\p{L}\p{M}\s.\'-]+$/u', $datos[$campo])) {
            $errores[$campo] = $etiqueta . ' solo puede contener letras, espacios, punto, apóstrofe y guion.';
        }
    }

    if ($datos['email'] === '') {
        $errores['email'] = 'El correo electrónico es obligatorio.';
    } elseif (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL) || largo($datos['email']) > 100) {
        $errores['email'] = 'Ingresá un correo electrónico válido (ej: nombre@dominio.com).';
    }

    // bcrypt solo usa los primeros 72 bytes: por eso se fija ese máximo
    if ($password === '') {
        $errores['password'] = 'La contraseña es obligatoria.';
    } elseif (strlen($password) < 8) {
        $errores['password'] = 'La contraseña debe tener al menos 8 caracteres.';
    } elseif (strlen($password) > 72) {
        $errores['password'] = 'La contraseña no puede superar los 72 caracteres.';
    } elseif ($password !== $password2) {
        $errores['password2'] = 'Las contraseñas no coinciden.';
    }

    // 3) El correo no debe existir previamente
    if (!isset($errores['email']) && !isset($errores['general'])) {
        $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $datos['email']]);
        if ($stmt->fetch()) {
            $errores['email'] = 'Ese correo electrónico ya está registrado.';
        }
    }

    // 4) Alta con hash bcrypt y consulta preparada
    if (!$errores) {
        try {
            $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => PASSWORD_COSTO]);
            $sql = 'INSERT INTO usuarios (rol_id, nombre, apellido, email, password_hash)
                    VALUES ((SELECT id FROM roles WHERE nombre = :rol), :nombre, :apellido, :email, :hash)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':rol'      => 'usuario',
                ':nombre'   => $datos['nombre'],
                ':apellido' => $datos['apellido'],
                ':email'    => $datos['email'],
                ':hash'     => $hash,
            ]);
            header('Location: login.php?mensaje=registro_ok');
            exit;
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? 0) === 1062) {        // UNIQUE: otro registro idéntico en simultáneo
                $errores['email'] = 'Ese correo electrónico ya está registrado.';
            } else {
                error_log('registro.php: ' . $e->getMessage());
                $errores['general'] = 'No se pudo crear la cuenta. Verificá que la base esté migrada (sql/migracion_tp8.sql).';
            }
        }
    }
}

$titulo_pagina = 'Crear cuenta';
$descripcion_pagina = 'Registrate para acceder a tu panel del Bastón Inteligente.';
require_once __DIR__ . '/includes/header.php';
?>

<main id="contenido-principal" class="contenedor" style="display:block;">
  <h1>Crear cuenta</h1>
  <p>Completá tus datos para registrarte. ¿Ya tenés cuenta? <a href="login.php">Ingresá acá</a>.</p>

<?php if ($errores): ?>
  <div class="alerta alerta-error" role="alert">
    <h2>No pudimos crear tu cuenta</h2>
    <ul>
<?php foreach ($errores as $mensaje_error): ?>
      <li><?= e($mensaje_error) ?></li>
<?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

  <form class="form-auth" action="registro.php" method="post" novalidate>
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

    <div class="campo">
      <label for="nombre">Nombre</label>
      <input type="text" id="nombre" name="nombre" maxlength="60" autocomplete="given-name"
             value="<?= e($datos['nombre']) ?>"<?= isset($errores['nombre']) ? ' aria-invalid="true"' : '' ?>>
      <span class="error"><?= e($errores['nombre'] ?? '') ?></span>
    </div>

    <div class="campo">
      <label for="apellido">Apellido</label>
      <input type="text" id="apellido" name="apellido" maxlength="60" autocomplete="family-name"
             value="<?= e($datos['apellido']) ?>"<?= isset($errores['apellido']) ? ' aria-invalid="true"' : '' ?>>
      <span class="error"><?= e($errores['apellido'] ?? '') ?></span>
    </div>

    <div class="campo">
      <label for="email">Correo electrónico</label>
      <input type="email" id="email" name="email" maxlength="100" autocomplete="email"
             value="<?= e($datos['email']) ?>"<?= isset($errores['email']) ? ' aria-invalid="true"' : '' ?>>
      <span class="error"><?= e($errores['email'] ?? '') ?></span>
    </div>

    <div class="campo">
      <label for="password">Contraseña (mínimo 8 caracteres)</label>
      <input type="password" id="password" name="password" maxlength="72" autocomplete="new-password"<?= isset($errores['password']) ? ' aria-invalid="true"' : '' ?>>
      <span class="error"><?= e($errores['password'] ?? '') ?></span>
    </div>

    <div class="campo">
      <label for="password2">Repetir contraseña</label>
      <input type="password" id="password2" name="password2" maxlength="72" autocomplete="new-password"<?= isset($errores['password2']) ? ' aria-invalid="true"' : '' ?>>
      <span class="error"><?= e($errores['password2'] ?? '') ?></span>
    </div>

    <button type="submit">Crear cuenta</button>
  </form>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
