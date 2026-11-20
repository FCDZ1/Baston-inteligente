
<footer>
  <hr>
  <p class="footer-links">
    <a href="<?= e($base ?? '') ?>index.php">Inicio</a> ·
    <a href="<?= e($base ?? '') ?>curso.php">Curso</a> ·
    <a href="<?= e($base ?? '') ?>blog.php">Blog</a> ·
    <a href="<?= e($base ?? '') ?>faq.php">FAQ</a> ·
    <a href="<?= e($base ?? '') ?>quienes-somos.php">Quiénes Somos</a> ·
    <a href="<?= e($base ?? '') ?>contactos.php">Contactos</a>
  </p>
  <p>Contacto: <a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a></p>
  <p id="reloj"></p>
  <p>© <?= date('Y') ?> <?= e(APP_NAME) ?>. Todos los derechos reservados.</p>
</footer>

<script src="<?= e($base ?? '') ?>js/main.js"></script>
</body>
</html>
