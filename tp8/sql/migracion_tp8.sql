-- =====================================================================
-- migracion_tp8.sql - Bastón Inteligente (TP 8)
-- Ejecutar UNA vez en phpMyAdmin (pestaña SQL de la base baston_inteligente)
-- después de haber importado tp6/schema.sql.
-- =====================================================================
USE baston_inteligente;

-- Rol que se asigna a quien se registra desde el sitio.
-- (Los roles 'admin' y 'editor' ya existen desde el TP 6.)
INSERT IGNORE INTO roles (nombre, descripcion)
VALUES ('usuario', 'Usuario registrado desde el sitio');

-- ---------------------------------------------------------------------
-- Cómo crear el primer administrador (hacerlo DESPUÉS de registrarte):
--   1) Registrate normalmente en /registro.php.
--   2) Cambiá el correo de abajo por el tuyo y ejecutá la sentencia.
--   3) Cerrá sesión y volvé a ingresar (o recargá: el rol se lee de la base).
-- ---------------------------------------------------------------------
-- UPDATE usuarios
-- SET rol_id = (SELECT id FROM roles WHERE nombre = 'admin')
-- WHERE email = 'tu_correo@ejemplo.com';
