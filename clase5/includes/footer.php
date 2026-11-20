
<footer>
  <hr>
  <p class="footer-links">
    <a href="index.php">Inicio</a> ·
    <a href="curso.php">Curso</a> ·
    <a href="blog.php">Blog</a> ·
    <a href="faq.php">FAQ</a> ·
    <a href="quienes-somos.php">Quiénes Somos</a> ·
    <a href="contactos.php">Contactos</a>
  </p>
  <p>Contacto: <a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a></p>
  <p id="reloj"></p>
  <p>© <?= date('Y') ?> <?= e(APP_NAME) ?>. Todos los derechos reservados.</p>
</footer>

<script src="js/main.js"></script>
</body>
</html>
