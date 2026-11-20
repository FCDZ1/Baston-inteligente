<?php
$titulo_pagina = 'Preguntas Frecuentes';
$descripcion_pagina = 'Resolvé tus dudas sobre el Bastón Inteligente: funcionamiento, componentes, precio, mantenimiento y garantía.';
require_once __DIR__ . '/includes/header.php';
?>

<main id="contenido-principal" class="contenedor" style="display:block;">
  <h1>Preguntas Frecuentes</h1>
  <p>Encontrá acá las respuestas a las dudas más comunes sobre el Bastón Inteligente.</p>

  <section class="bloque">
    <h2>Sobre el producto</h2>

    <details>
      <summary>¿Cómo funciona el Bastón Inteligente?</summary>
      <p>Utiliza un sensor ultrasónico ubicado en la punta que detecta obstáculos a distintas distancias. Cuando encuentra un obstáculo, un motor vibrador avisa al usuario mediante distintas intensidades de vibración según la cercanía.</p>
    </details>

    <details>
      <summary>¿Qué componentes tiene?</summary>
      <p>Una placa Arduino, un sensor ultrasónico HC-SR04, un motor vibrador, una batería recargable y la estructura del bastón tradicional.</p>
    </details>

    <details>
      <summary>¿Cuánto dura la batería?</summary>
      <p>Con uso normal, la batería dura aproximadamente 8 horas continuas antes de necesitar recarga.</p>
    </details>
  </section>

  <section class="bloque">
    <h2>Sobre la compra y el uso</h2>

    <details>
      <summary>¿Dónde puedo conseguir uno?</summary>
      <p>Por el momento el proyecto se encuentra en etapa de desarrollo educativo. Podés escribirnos desde la sección de <a href="contactos.php">Contactos</a> para más información.</p>
    </details>

    <details>
      <summary>¿Necesito conocimientos técnicos para usarlo?</summary>
      <p>No. El bastón está pensado para un uso simple: se enciende y funciona automáticamente, sin necesidad de configuración por parte del usuario final.</p>
    </details>

    <details>
      <summary>¿Puedo aprender a construir uno yo mismo?</summary>
      <p>Sí, en nuestra sección <a href="curso.php">Curso</a> encontrás una guía paso a paso para armar y programar tu propio bastón inteligente.</p>
    </details>
  </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
