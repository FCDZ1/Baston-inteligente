<?php
$titulo_pagina = 'Movilidad segura para personas con discapacidad visual';
$descripcion_pagina = 'Bastón Inteligente: dispositivo con sensores ultrasónicos que ayuda a personas con discapacidad visual a desplazarse con más seguridad y autonomía. Conocé el proyecto, el curso y el blog.';
require_once __DIR__ . '/includes/header.php';
?>

<main id="contenido-principal">
  <aside class="galeria" aria-label="Galería de imágenes del bastón">
    <h3>GALERÍA</h3>

    <div class="imagen" tabindex="0">
      <img src="img/baston1.jpeg" alt="Bastón inteligente">
      <p>Bastón 1</p>
    </div>

    <div class="imagen" tabindex="0">
      <img src="img/baston2.jpeg" alt="Bastón inteligente plegable con sensor ultrasónico en la punta">
      <p>Bastón 2</p>
    </div>
  </aside>

  <section class="contenido">
    <h1>Bastón Inteligente</h1>
    <button type="button" onclick="document.querySelector('.tarjeta').scrollIntoView({behavior:'smooth'})">VER MÁS</button>

    <div class="tarjeta">
      <h2>¿Qué es un Bastón Inteligente?</h2>
      <p>
Un bastón inteligente es un dispositivo tecnológico diseñado para ayudar a las personas con discapacidad visual a desplazarse de manera más segura, independiente y autónoma en diferentes entornos. Su principal objetivo es facilitar la movilidad de quienes tienen dificultades para ver, permitiéndoles detectar obstáculos y reconocer posibles peligros durante sus recorridos.
</br>
</br>
A diferencia de un bastón tradicional, el bastón inteligente incorpora componentes electrónicos y sensores que permiten identificar objetos, obstáculos y desniveles que se encuentran en el camino del usuario. Además, puede utilizar diferentes sistemas de alerta, como vibraciones o señales sonoras, para avisar cuando detecta algún peligro cercano.
</br>
</br>
Este dispositivo contribuye a mejorar la orientación y la seguridad durante los desplazamientos, ayudando a prevenir golpes, caídas y accidentes. También permite que las personas con discapacidad visual puedan desenvolverse con mayor confianza en lugares públicos y privados, reduciendo su dependencia de otras personas.
</br>
</br>
En conclusión, el bastón inteligente representa una innovación tecnológica que busca mejorar la calidad de vida de las personas con discapacidad visual, brindándoles una herramienta que favorece su autonomía, seguridad e inclusión en la sociedad.
      </p>
    </div>
  </section>

  <aside class="faq" aria-label="Información rápida">
    <details>
      <summary>¿Cómo funciona?</summary>
      <p>Utiliza sensores para detectar obstáculos.</p>
    </details>

    <details>
      <summary>Componentes</summary>
      <p>Arduino, sensor ultrasónico, motor vibrador.</p>
    </details>

    <details>
      <summary>Sensores</summary>
      <p>HC-SR04 y otros sensores de apoyo.</p>
    </details>

    <details>
      <summary>Beneficios</summary>
      <p>Mayor seguridad y autonomía.</p>
    </details>

    <details>
      <summary>Instrucciones de uso</summary>
      <p>Sostener el bastón y encender el sistema.</p>
    </details>
  </aside>
</main>

<!-- Lightbox de la galería -->
<div class="lightbox" id="lightbox">
  <p id="lightbox-titulo" style="color:white;"></p>
  <img id="lightbox-img" src="" alt="">
  <div class="lightbox-controles">
    <button type="button" id="lightbox-anterior">‹ Anterior</button>
    <button type="button" id="lightbox-cerrar">Cerrar</button>
    <button type="button" id="lightbox-siguiente">Siguiente ›</button>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
