<?php
$titulo_pagina = 'CV';
$descripcion_pagina = 'Currículum del equipo del proyecto Bastón Inteligente.';
require_once __DIR__ . '/includes/header.php';
?>

<main id="contenido-principal" class="contenedor" style="display:block;">

  <div class="cv-header">
    <img src="img/cv-foto.svg" alt="Foto de perfil">
    <div>
      <h1>Nombre Apellido</h1>
      <p>Estudiante — Desarrollo Web y Tecnología Asistiva</p>
    </div>
  </div>

  <section class="cv-seccion">
    <h2>Experiencia laboral</h2>

    <div class="cv-item">
      <h3>Desarrollador/a del proyecto Bastón Inteligente</h3>
      <p class="periodo">2026 — Actualidad</p>
      <p>Diseño y desarrollo del sitio web, integración de contenidos del curso, blog y documentación técnica del dispositivo.</p>
    </div>

    <div class="cv-item">
      <h3>Prácticas en electrónica básica</h3>
      <p class="periodo">2025</p>
      <p>Armado y programación de circuitos con Arduino y sensores para proyectos escolares.</p>
    </div>
  </section>

  <section class="cv-seccion">
    <h2>Educación</h2>

    <div class="cv-item">
      <h3>Escuela Técnica</h3>
      <p class="periodo">2023 — 2026</p>
      <p>Formación orientada a informática y desarrollo de proyectos tecnológicos.</p>
    </div>
  </section>

  <section class="cv-seccion">
    <h2>Habilidades</h2>
    <ul class="cv-lista">
      <li>HTML</li>
      <li>CSS</li>
      <li>JavaScript</li>
      <li>Arduino</li>
      <li>Accesibilidad web</li>
      <li>Trabajo en equipo</li>
    </ul>
  </section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
