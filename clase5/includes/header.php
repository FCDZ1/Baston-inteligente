<?php
/**
 * header.php - <head>, cabecera y menú (se incluye al inicio de cada página).
 * Cada página define antes: $titulo_pagina y (opcional) $descripcion_pagina.
 */
require_once __DIR__ . '/config.php';

$titulo_pagina      = $titulo_pagina ?? 'Inicio';
$descripcion_pagina = $descripcion_pagina ?? 'Bastón Inteligente: dispositivo con sensores ultrasónicos para una movilidad más segura.';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($titulo_pagina) ?> | <?= e(APP_NAME) ?></title>
<meta name="description" content="<?= e($descripcion_pagina) ?>">
<link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<a href="#contenido-principal" class="skip-link">Saltar al contenido principal</a>

<header>
  <div class="top-bar">
    <a href="index.php" class="marca"><?= e(APP_NAME) ?></a>
    <div style="display:flex; align-items:center; gap:10px;">
      <button id="toggle-tema" class="tema-toggle" type="button">Modo oscuro</button>
      <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="menu-principal">Menú</button>
    </div>
  </div>
<?php require_once __DIR__ . '/nav.php'; ?>
</header>

