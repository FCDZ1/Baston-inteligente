<?php
$titulo_pagina = 'Blog';
$descripcion_pagina = 'Novedades, avances y consejos sobre el proyecto Bastón Inteligente: tecnología asistiva, electrónica y accesibilidad.';
require_once __DIR__ . '/includes/header.php';
?>

<main id="contenido-principal" class="contenedor" style="display:block;">
  <h1>Blog</h1>
  <p>Novedades y aprendizajes del proyecto Bastón Inteligente.</p>

  <article class="entrada-blog">
    <img src="img/blog-post1.svg" alt="Persona probando el bastón inteligente en la calle">
    <h2>Probamos el bastón en la calle</h2>
    <p class="meta-blog">Publicado el 10/08/2026 · Categoría: Pruebas de campo</p>
    <p>
      Después de varias semanas de trabajo en el taller, llevamos el
      prototipo a la calle por primera vez. El sensor detectó correctamente
      postes, escalones y autos estacionados, y el motor vibrador avisó con
      la anticipación esperada.
    </p>
    <h3>Lo que aprendimos</h3>
    <p>
      Notamos que en espacios muy concurridos las vibraciones constantes
      podían resultar molestas, así que ajustamos el umbral de distancia
      para reducir las falsas alarmas.
    </p>

    <section class="comentario" aria-label="Comentarios de la entrada">
      <h4>Comentarios</h4>
      <div class="comentario">
        <h4>Marina G.</h4>
        <p>¡Excelente iniciativa! ¿Van a compartir el código completo en el curso?</p>
      </div>
      <div class="comentario">
        <h4>Equipo Bastón Inteligente</h4>
        <p>¡Sí! Todo el código de esta etapa ya está en la <a href="guia-estudio.php">guía de estudio</a>.</p>
      </div>
    </section>
  </article>

  <article class="entrada-blog">
    <img src="img/blog-post2.svg" alt="Componentes electrónicos del bastón sobre una mesa de trabajo">
    <h2>Componentes que usamos y por qué los elegimos</h2>
    <p class="meta-blog">Publicado el 02/08/2026 · Categoría: Electrónica</p>
    <p>
      Elegimos el sensor ultrasónico HC-SR04 por su bajo costo y precisión
      suficiente para distancias cortas. Lo combinamos con un Arduino 
      Uno, es recomendable usar un arduino nano para ahorrar mas espacio, ideal para integrarlo dentro del bastón.
    </p>
    <h3>Lista de materiales</h3>
    <p>Arduino Nano, sensor HC-SR04, motor vibrador, batería LiPo recargable y regulador de voltaje.</p>

    <section class="comentario" aria-label="Comentarios de la entrada">
      <h4>Comentarios</h4>
      <div class="comentario">
        <h4>Julián R.</h4>
        <p>¿Sirve reemplazar el Arduino Nano por un Uno para quien recién arranca?</p>
      </div>
      <div class="comentario">
        <h4>Equipo Bastón Inteligente</h4>
        <p>Totalmente, el Uno funciona igual de bien para practicar antes del montaje final.</p>
      </div>
    </section>
  </article>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
