<?php
$titulo_pagina = '¿Quiénes Somos?';
$descripcion_pagina = 'Conocé al equipo detrás del proyecto Bastón Inteligente y nuestra misión de mejorar la autonomía de personas con discapacidad visual.';
require_once __DIR__ . '/includes/header.php';
?>

<main id="contenido-principal" class="contenedor" style="display:block;">

  <section class="hero">
    <img src="img/equipo.svg" alt="Ilustración del equipo del proyecto Bastón Inteligente">
    <div class="texto">
      <h1>¿Quiénes Somos?</h1>
      <p>
        Somos un equipo de estudiantes que desarrolló el proyecto
        <strong>Bastón Inteligente</strong> como una iniciativa de tecnología
        asistiva, con el objetivo de mejorar la autonomía y seguridad de
        personas con discapacidad visual.
      </p>
    </div>
  </section>

  <section class="bloque tarjeta">
    <h2>Nuestra misión</h2>
    <p>
      Diseñar soluciones tecnológicas accesibles y de bajo costo que ayuden
      a prevenir accidentes y mejoren la calidad de vida de las personas con
      discapacidad visual en su desplazamiento diario.
    </p>
  </section>

  <section class="bloque tarjeta">
    <h2>Nuestra visión</h2>
    <p>
      Que la tecnología asistiva sea accesible para cualquier persona que la
      necesite, combinando electrónica simple con diseño centrado en el
      usuario.
    </p>
  </section>

  <section class="bloque">
    <h2>Cómo empezó el proyecto</h2>
    <h3>Origen</h3>
    <p>Bastón Inteligente nació como un trabajo práctico enfocado en tecnología asistiva y desarrollo web.</p>
    <h3>Evolución</h3>
    <p>
      Con el tiempo sumamos el curso online, el blog de novedades y toda la
      documentación técnica del dispositivo, integrando todo en este sitio.<br>
      El proyecto sigue en desarrollo constante.
    </p>
  </section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
