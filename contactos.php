<?php
$titulo_pagina = 'Contactos';
$descripcion_pagina = 'Escribinos tus consultas sobre el Bastón Inteligente: producto, curso o colaboraciones.';
require_once __DIR__ . '/includes/header.php';
?>

<main id="contenido-principal" class="contenedor" style="display:block;">
  <h1>Contactos</h1>
  <p>¿Tenés dudas sobre el bastón, el curso o querés colaborar con el proyecto? Completá el formulario y te respondemos a la brevedad.</p>

  <form id="form-contacto" action="/enviar-contacto" method="post" novalidate>

    <div class="campo">
      <label for="nombre">Nombre completo</label>
      <input type="text" id="nombre" name="nombre" placeholder="Ingresá tu nombre" required minlength="2">
      <span class="error" id="error-nombre"></span>
    </div>

    <div class="campo">
      <label for="email">Correo electrónico</label>
      <input type="email" id="email" name="email" placeholder="tuemail@ejemplo.com" required>
      <span class="error" id="error-email"></span>
    </div>

    <div class="campo">
      <label for="telefono">Teléfono (opcional)</label>
      <input type="tel" id="telefono" name="telefono" placeholder="Ej: 387 000 0000" pattern="[0-9 +-]{6,15}">
    </div>

    <div class="campo">
      <label for="asunto">Motivo de tu consulta</label>
      <select id="asunto" name="asunto">
        <option value="producto">Consulta sobre el producto</option>
        <option value="curso">Consulta sobre el curso</option>
        <option value="colaboracion">Quiero colaborar</option>
        <option value="otro">Otro</option>
      </select>
    </div>

    <div class="campo">
      <label for="mensaje">Mensaje</label>
      <textarea id="mensaje" name="mensaje" rows="5" placeholder="Contanos en qué te podemos ayudar" required minlength="10"></textarea>
      <span class="error" id="error-mensaje"></span>
    </div>

    <div class="campo campo-checkbox">
      <input type="checkbox" id="acepto" name="acepto" required>
      <label for="acepto">Acepto ser contactado/a por este medio</label>
    </div>

    <button type="submit">Enviar</button>
    <button type="reset">Borrar</button>

    <div id="resumen-envio" aria-live="polite"></div>
  </form>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
