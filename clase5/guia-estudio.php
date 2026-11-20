<?php
$titulo_pagina = 'Guía de estudio';
$descripcion_pagina = 'Guía de estudio completa del curso de Bastón Inteligente: temas, subtemas y detalles organizados con encabezados HTML.';
require_once __DIR__ . '/includes/header.php';
?>

<main id="contenido-principal" class="contenedor" style="display:block;">
  <h1>Guía de estudio: Bastón Inteligente</h1>
  <p><a href="curso.php">← Volver al curso</a></p>

  <section class="bloque guia-tema">
    <h2>Tema 1: Introducción a la tecnología asistiva</h2>

    <h3 class="guia-subtema">1.1 ¿Qué es la tecnología asistiva?</h3>
    <p class="guia-detalle">Dispositivos y sistemas que ayudan a personas con discapacidad a ganar autonomía.</p>

    <h3 class="guia-subtema">1.2 Casos de uso del bastón inteligente</h3>
    <p class="guia-detalle">Desplazamiento urbano, interiores, espacios con obstáculos bajos.</p>
    <p class="guia-detalle">Diferencias frente al bastón blanco tradicional.</p>
  </section>

  <section class="bloque guia-tema">
    <h2>Tema 2: Electrónica básica</h2>

    <h3 class="guia-subtema">2.1 Arduino: qué es y cómo se programa</h3>
    <p class="guia-detalle">Placa, IDE, carga de programas (sketches).</p>

    <h3 class="guia-subtema">2.2 Sensor ultrasónico HC-SR04</h3>
    <p class="guia-detalle">Principio de funcionamiento: emisión y rebote de ondas de sonido.</p>
    <p class="guia-detalle">Conexión de pines Trig y Echo.</p>

    <h3 class="guia-subtema">2.3 Motor vibrador</h3>
    <p class="guia-detalle">Cómo traducir la distancia detectada en una señal háptica.</p>
  </section>

  <section class="bloque guia-tema">
    <h2>Tema 3: Programación del bastón</h2>

    <h3 class="guia-subtema">3.1 Lectura de distancia</h3>
    <p class="guia-detalle">Cálculo de distancia a partir del tiempo de eco.</p>

    <h3 class="guia-subtema">3.2 Umbrales de alerta</h3>
    <p class="guia-detalle">Definir a qué distancia se activa la vibración (por ejemplo, 50 cm).</p>

    <h3 class="guia-subtema">3.3 Pruebas y depuración</h3>
    <p class="guia-detalle">Uso del monitor serie para verificar lecturas antes de instalar el motor.</p>
  </section>

  <section class="bloque guia-tema">
    <h2>Tema 4: Montaje final</h2>

    <h3 class="guia-subtema">4.1 Ensamblado en el bastón físico</h3>
    <p class="guia-detalle">Ubicación del sensor en la punta, cableado y batería.</p>

    <h3 class="guia-subtema">4.2 Pruebas de campo</h3>
    <p class="guia-detalle">Recorridos guiados y ajuste de sensibilidad según el entorno.</p>
  </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
