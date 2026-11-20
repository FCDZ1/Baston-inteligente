<?php
/**
 * contactos.php - Formulario de contacto (método POST).
 * Flujo: captura -> sanitiza -> valida en servidor -> mensaje al usuario.
 * Si hay errores se vuelven a mostrar los datos ingresados (persistencia).
 * Si todo está bien se redirige (patrón Post/Redirect/Get) para que al
 * recargar la página no se reenvíe el formulario.
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/formularios.php';

iniciar_sesion_segura();

// Lista blanca de motivos permitidos (el valor enviado debe estar acá)
$asuntos = [
    'producto'     => 'Consulta sobre el producto',
    'curso'        => 'Consulta sobre el curso',
    'colaboracion' => 'Quiero colaborar',
    'otro'         => 'Otro',
];

$errores = [];
$datos = [
    'nombre'   => '',
    'email'    => '',
    'telefono' => '',
    'asunto'   => 'producto',
    'mensaje'  => '',
    'acepto'   => false,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1) Captura + sanitización
    $datos['nombre']   = post_limpio('nombre');
    $datos['email']    = post_limpio('email');
    $datos['telefono'] = post_limpio('telefono');
    $datos['asunto']   = post_limpio('asunto');
    $datos['mensaje']  = post_limpio('mensaje', true);
    $datos['acepto']   = isset($_POST['acepto']);

    // 2) Validación en servidor
    if ($datos['nombre'] === '') {
        $errores['nombre'] = 'El nombre es obligatorio.';
    } elseif (largo($datos['nombre']) < 2 || largo($datos['nombre']) > 60) {
        $errores['nombre'] = 'El nombre debe tener entre 2 y 60 caracteres.';
    } elseif (!preg_match('/^[\p{L}\p{M}\s.\'-]+$/u', $datos['nombre'])) {
        $errores['nombre'] = 'El nombre solo puede contener letras, espacios, punto, apóstrofe y guion.';
    }

    if ($datos['email'] === '') {
        $errores['email'] = 'El correo electrónico es obligatorio.';
    } elseif (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL) || largo($datos['email']) > 100) {
        $errores['email'] = 'Ingresá un correo electrónico válido (ej: nombre@dominio.com).';
    }

    // El teléfono es opcional, pero si viene debe tener formato válido
    if ($datos['telefono'] !== '' && !preg_match('/^[0-9 +-]{6,15}$/', $datos['telefono'])) {
        $errores['telefono'] = 'El teléfono debe tener entre 6 y 15 caracteres (números, espacios, + o -).';
    }

    if (!array_key_exists($datos['asunto'], $asuntos)) {
        $errores['asunto'] = 'Elegí un motivo de consulta de la lista.';
        $datos['asunto'] = 'producto';
    }

    if ($datos['mensaje'] === '') {
        $errores['mensaje'] = 'El mensaje es obligatorio.';
    } elseif (largo($datos['mensaje']) < 10) {
        $errores['mensaje'] = 'El mensaje debe tener al menos 10 caracteres.';
    } elseif (largo($datos['mensaje']) > 1000) {
        $errores['mensaje'] = 'El mensaje no puede superar los 1000 caracteres.';
    }

    if (!$datos['acepto']) {
        $errores['acepto'] = 'Tenés que aceptar ser contactado/a para poder enviar la consulta.';
    }

    // 3) Sin errores: guardamos un "mensaje flash" y redirigimos (PRG)
    if (!$errores) {
        $_SESSION['contacto_ok'] = [
            'nombre'   => $datos['nombre'],
            'email'    => $datos['email'],
            'telefono' => $datos['telefono'],
            'asunto'   => $asuntos[$datos['asunto']],
            'mensaje'  => $datos['mensaje'],
        ];
        header('Location: contactos.php');
        exit;
    }
}

// Mensaje de éxito (se muestra una sola vez)
$exito = $_SESSION['contacto_ok'] ?? null;
unset($_SESSION['contacto_ok']);

$titulo_pagina = 'Contactos';
$descripcion_pagina = 'Escribinos tus consultas sobre el Bastón Inteligente: producto, curso o colaboraciones.';
require_once __DIR__ . '/includes/header.php';
?>

<main id="contenido-principal" class="contenedor" style="display:block;">
  <h1>Contactos</h1>
  <p>¿Tenés dudas sobre el bastón, el curso o querés colaborar con el proyecto? Completá el formulario y te respondemos a la brevedad.</p>

<?php if ($exito): ?>
  <div class="alerta alerta-exito" role="status">
    <h2>¡Gracias, <?= e($exito['nombre']) ?>! Tu mensaje fue enviado correctamente.</h2>
    <p>Estos son los datos que recibimos:</p>
    <ul>
      <li><strong>Nombre:</strong> <?= e($exito['nombre']) ?></li>
      <li><strong>Correo:</strong> <?= e($exito['email']) ?></li>
      <li><strong>Teléfono:</strong> <?= $exito['telefono'] !== '' ? e($exito['telefono']) : 'No informado' ?></li>
      <li><strong>Motivo:</strong> <?= e($exito['asunto']) ?></li>
      <li><strong>Mensaje:</strong> <?= nl2br(e($exito['mensaje'])) ?></li>
    </ul>
  </div>
<?php endif; ?>

<?php if ($errores): ?>
  <div class="alerta alerta-error" role="alert">
    <h2>No pudimos enviar tu consulta</h2>
    <p>Revisá los siguientes puntos:</p>
    <ul>
<?php foreach ($errores as $mensaje_error): ?>
      <li><?= e($mensaje_error) ?></li>
<?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

  <form id="form-contacto" action="contactos.php" method="post" novalidate>

    <div class="campo">
      <label for="nombre">Nombre completo</label>
      <input type="text" id="nombre" name="nombre" placeholder="Ingresá tu nombre" maxlength="60"
             value="<?= e($datos['nombre']) ?>"<?= isset($errores['nombre']) ? ' aria-invalid="true"' : '' ?>>
      <span class="error" id="error-nombre"><?= e($errores['nombre'] ?? '') ?></span>
    </div>

    <div class="campo">
      <label for="email">Correo electrónico</label>
      <input type="email" id="email" name="email" placeholder="tuemail@ejemplo.com" maxlength="100"
             value="<?= e($datos['email']) ?>"<?= isset($errores['email']) ? ' aria-invalid="true"' : '' ?>>
      <span class="error" id="error-email"><?= e($errores['email'] ?? '') ?></span>
    </div>

    <div class="campo">
      <label for="telefono">Teléfono (opcional)</label>
      <input type="tel" id="telefono" name="telefono" placeholder="Ej: 387 000 0000" maxlength="15"
             value="<?= e($datos['telefono']) ?>"<?= isset($errores['telefono']) ? ' aria-invalid="true"' : '' ?>>
      <span class="error" id="error-telefono"><?= e($errores['telefono'] ?? '') ?></span>
    </div>

    <div class="campo">
      <label for="asunto">Motivo de tu consulta</label>
      <select id="asunto" name="asunto">
<?php foreach ($asuntos as $valor => $texto): ?>
        <option value="<?= e($valor) ?>"<?= $datos['asunto'] === $valor ? ' selected' : '' ?>><?= e($texto) ?></option>
<?php endforeach; ?>
      </select>
      <span class="error" id="error-asunto"><?= e($errores['asunto'] ?? '') ?></span>
    </div>

    <div class="campo">
      <label for="mensaje">Mensaje</label>
      <textarea id="mensaje" name="mensaje" rows="5" maxlength="1000" placeholder="Contanos en qué te podemos ayudar"<?= isset($errores['mensaje']) ? ' aria-invalid="true"' : '' ?>><?= e($datos['mensaje']) ?></textarea>
      <span class="error" id="error-mensaje"><?= e($errores['mensaje'] ?? '') ?></span>
    </div>

    <div class="campo campo-checkbox">
      <input type="checkbox" id="acepto" name="acepto"<?= $datos['acepto'] ? ' checked' : '' ?>>
      <label for="acepto">Acepto ser contactado/a por este medio</label>
    </div>
    <span class="error" id="error-acepto"><?= e($errores['acepto'] ?? '') ?></span>

    <button type="submit">Enviar</button>
    <a class="boton boton-secundario" href="contactos.php">Borrar</a>
  </form>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
