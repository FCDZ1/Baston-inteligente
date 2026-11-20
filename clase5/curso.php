<?php
$titulo_pagina = 'Curso: aprendé a construir tu dispositivo';
$descripcion_pagina = 'Curso online para aprender a diseñar y programar un bastón inteligente con Arduino y sensores ultrasónicos, paso a paso.';
require_once __DIR__ . '/includes/header.php';
?>

<main id="contenido-principal" class="contenedor" style="display:block;">

  <section class="hero">
    <img src="img/curso-hero.svg" alt="Ilustración de una guía de estudio junto a un bastón inteligente">
    <div class="texto">
      <h1>Curso: Construí tu propio Bastón Inteligente</h1>
      <p>
        Aprendé, de forma práctica, a diseñar, armar y programar un bastón
        inteligente con Arduino y sensores ultrasónicos. Pensado para
        estudiantes sin experiencia previa en electrónica.
      </p>
      <a class="boton" href="guia-estudio.php">VER GUÍA DE ESTUDIO</a>
    </div>
  </section>

  <section class="bloque tarjeta">
    <h2>¿A quién está dirigido?</h2>
    <p>
      A estudiantes, docentes y makers interesados en tecnología
      asistiva.<br>
      No se requieren conocimientos previos: el curso empieza desde cero.
    </p>
  </section>

  <section class="bloque tarjeta">
    <h2>Primer vistazo al código</h2>
    <p>Así se ve una lectura básica del sensor ultrasónico en Arduino, uno de los primeros ejercicios del curso:</p>
    <pre><code>const int trigPin = 9;
const int echoPin = 10;

void setup() {
  pinMode(trigPin, OUTPUT);
  pinMode(echoPin, INPUT);
  Serial.begin(9600);
}

void loop() {
  long duracion, distanciaCm;

  digitalWrite(trigPin, LOW);
  delayMicroseconds(2);
  digitalWrite(trigPin, HIGH);
  delayMicroseconds(10);
  digitalWrite(trigPin, LOW);

  duracion = pulseIn(echoPin, HIGH);
  distanciaCm = duracion * 0.034 / 2;

  Serial.print("Distancia: ");
  Serial.print(distanciaCm);
  Serial.println(" cm");
  delay(200);
}</code></pre>
  </section>

  <section class="bloque">
    <h2>Programa del curso</h2>
    <div class="guia-tema">
      <h3>1. Introducción a la tecnología asistiva</h3>
      <p class="guia-subtema">Contexto, objetivos y casos de uso reales.</p>
    </div>
    <div class="guia-tema">
      <h3>2. Electrónica básica</h3>
      <p class="guia-subtema">Arduino, protoboard, sensores y actuadores.</p>
    </div>
    <div class="guia-tema">
      <h3>3. Programación del bastón</h3>
      <p class="guia-subtema">Lectura de sensores y respuesta con motor vibrador.</p>
    </div>
    <div class="guia-tema">
      <h3>4. Montaje final</h3>
      <p class="guia-subtema">Ensamblado, pruebas de campo y ajustes.</p>
    </div>
  </section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
