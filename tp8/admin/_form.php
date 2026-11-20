<?php
/**
 * _form.php - Formulario compartido por crear.php y editar.php.
 * Variables esperadas: $datos, $errores, $categorias, $autores, $accion, $texto_boton
 */
?>
  <form class="form-admin" action="<?= e($accion) ?>" method="post" novalidate>

    <div class="campo">
      <label for="titulo">Título</label>
      <input type="text" id="titulo" name="titulo" maxlength="150" value="<?= e($datos['titulo']) ?>"<?= isset($errores['titulo']) ? ' aria-invalid="true"' : '' ?>>
      <span class="error"><?= e($errores['titulo'] ?? '') ?></span>
    </div>

    <div class="campo">
      <label for="categoria_id">Categoría</label>
      <select id="categoria_id" name="categoria_id">
        <option value="">Elegí una categoría</option>
<?php foreach ($categorias as $c): ?>
        <option value="<?= e((string) $c['id']) ?>"<?= (string) $datos['categoria_id'] === (string) $c['id'] ? ' selected' : '' ?>><?= e($c['nombre']) ?></option>
<?php endforeach; ?>
      </select>
      <span class="error"><?= e($errores['categoria_id'] ?? '') ?></span>
    </div>

    <div class="campo">
      <label for="autor_id">Autor</label>
      <select id="autor_id" name="autor_id">
        <option value="">Elegí un autor</option>
<?php foreach ($autores as $a): ?>
        <option value="<?= e((string) $a['id']) ?>"<?= (string) $datos['autor_id'] === (string) $a['id'] ? ' selected' : '' ?>><?= e($a['nombre']) ?></option>
<?php endforeach; ?>
      </select>
      <span class="error"><?= e($errores['autor_id'] ?? '') ?></span>
    </div>

    <div class="campo">
      <label for="resumen">Resumen</label>
      <textarea id="resumen" name="resumen" rows="2" maxlength="500"<?= isset($errores['resumen']) ? ' aria-invalid="true"' : '' ?>><?= e($datos['resumen']) ?></textarea>
      <span class="error"><?= e($errores['resumen'] ?? '') ?></span>
    </div>

    <div class="campo">
      <label for="contenido">Contenido</label>
      <textarea id="contenido" name="contenido" rows="7" maxlength="5000"<?= isset($errores['contenido']) ? ' aria-invalid="true"' : '' ?>><?= e($datos['contenido']) ?></textarea>
      <span class="error"><?= e($errores['contenido'] ?? '') ?></span>
    </div>

    <div class="campo">
      <label for="imagen">Imagen (opcional)</label>
      <input type="text" id="imagen" name="imagen" maxlength="120" placeholder="img/blog-post1.svg" value="<?= e($datos['imagen']) ?>"<?= isset($errores['imagen']) ? ' aria-invalid="true"' : '' ?>>
      <span class="error"><?= e($errores['imagen'] ?? '') ?></span>
    </div>

    <div class="campo">
      <label for="fecha_publicacion">Fecha de publicación</label>
      <input type="date" id="fecha_publicacion" name="fecha_publicacion" value="<?= e($datos['fecha_publicacion']) ?>"<?= isset($errores['fecha_publicacion']) ? ' aria-invalid="true"' : '' ?>>
      <span class="error"><?= e($errores['fecha_publicacion'] ?? '') ?></span>
    </div>

    <div class="campo campo-checkbox">
      <input type="checkbox" id="publicado" name="publicado"<?= $datos['publicado'] ? ' checked' : '' ?>>
      <label for="publicado">Publicada (visible en el sitio)</label>
    </div>

    <button type="submit"><?= e($texto_boton) ?></button>
    <a class="boton boton-secundario" href="index.php">Cancelar</a>
  </form>
