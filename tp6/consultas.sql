-- =====================================================================
-- consultas.sql - Bastón Inteligente
-- TP 6: Consultas de prueba CRUD (ejecutar DESPUÉS de schema.sql)
-- =====================================================================
USE baston_inteligente;

-- =====================================================================
-- CREATE: inserción de datos iniciales
-- =====================================================================

-- Tablas maestras (más de 2 inserciones)
INSERT INTO roles (nombre, descripcion) VALUES
  ('admin',      'Administra todo el sitio'),
  ('editor',     'Publica entradas del blog'),
  ('visitante',  'Usuario registrado que comenta');

INSERT INTO categorias (nombre) VALUES
  ('Pruebas de campo'),
  ('Electrónica');

INSERT INTO etiquetas (nombre) VALUES
  ('sensor'), ('arduino'), ('prototipo'), ('accesibilidad');

INSERT INTO motivos_consulta (clave, nombre) VALUES
  ('producto',     'Consulta sobre el producto'),
  ('curso',        'Consulta sobre el curso'),
  ('colaboracion', 'Quiero colaborar'),
  ('otro',         'Otro');

-- Usuarios (hash de ejemplo; en la app se genera con password_hash() de PHP)
INSERT INTO usuarios (rol_id, nombre, apellido, email, password_hash, telefono) VALUES
  (2, 'Equipo', 'Bastón Inteligente', 'equipo@baston.com',
      '$2y$10$abcdefghijklmnopqrstuuABCDEFGHIJKLMNOPQRSTUVWXYZ0123', NULL),
  (3, 'Marina', 'Gómez',  'marina@mail.com',
      '$2y$10$abcdefghijklmnopqrstuuABCDEFGHIJKLMNOPQRSTUVWXYZ0123', '387 111 2222'),
  (3, 'Julián', 'Ramos',  'julian@mail.com',
      '$2y$10$abcdefghijklmnopqrstuuABCDEFGHIJKLMNOPQRSTUVWXYZ0123', NULL);

-- Tabla principal: entradas vinculadas a autor y categoría (claves foráneas)
INSERT INTO entradas (autor_id, categoria_id, titulo, resumen, contenido, imagen, fecha_publicacion) VALUES
  (1, 1, 'Probamos el bastón en la calle',
      'Llevamos el prototipo a la calle por primera vez.',
      'El sensor detectó correctamente postes, escalones y autos estacionados, y el motor vibrador avisó con la anticipación esperada.',
      'img/blog-post1.svg', '2026-08-10'),
  (1, 2, 'Componentes que usamos y por qué los elegimos',
      'Sensor HC-SR04 y Arduino Nano como base del bastón.',
      'Elegimos el sensor ultrasónico HC-SR04 por su bajo costo y precisión suficiente para distancias cortas.',
      'img/blog-post2.svg', '2026-08-02');

-- Relación N:M: etiquetas de cada entrada
INSERT INTO entrada_etiqueta (entrada_id, etiqueta_id) VALUES
  (1, 1), (1, 3), (1, 4),
  (2, 1), (2, 2);

-- Comentarios
INSERT INTO comentarios (entrada_id, usuario_id, texto) VALUES
  (1, 2, '¡Excelente iniciativa! ¿Van a compartir el código completo en el curso?'),
  (1, 1, '¡Sí! Todo el código de esta etapa ya está en la guía de estudio.'),
  (2, 3, '¿Sirve reemplazar el Arduino Nano por un Uno para quien recién arranca?');

-- Consultas del formulario de contacto
INSERT INTO consultas (motivo_id, usuario_id, nombre, email, telefono, mensaje) VALUES
  (2, 2,    'Marina Gómez', 'marina@mail.com', '387 111 2222', 'Quisiera saber cuándo empieza el próximo curso.'),
  (1, NULL, 'Carlos Pérez', 'carlos@mail.com', NULL,           'Me interesa conseguir un bastón para mi padre.');

-- =====================================================================
-- READ
-- =====================================================================

-- 1) SELECT con WHERE y ORDER BY: entradas publicadas desde agosto, más nuevas primero
SELECT id, titulo, fecha_publicacion
FROM entradas
WHERE publicado = 1
  AND fecha_publicacion >= '2026-08-01'
ORDER BY fecha_publicacion DESC;

-- 2) INNER JOIN: entrada con su autor y su categoría
SELECT e.titulo,
       CONCAT(u.nombre, ' ', u.apellido) AS autor,
       c.nombre AS categoria,
       e.fecha_publicacion
FROM entradas e
INNER JOIN usuarios   u ON u.id = e.autor_id
INNER JOIN categorias c ON c.id = e.categoria_id
ORDER BY e.fecha_publicacion DESC;

-- 3) INNER JOIN con la tabla N:M: entradas con sus etiquetas
SELECT e.titulo, GROUP_CONCAT(t.nombre ORDER BY t.nombre SEPARATOR ', ') AS etiquetas
FROM entradas e
INNER JOIN entrada_etiqueta ee ON ee.entrada_id = e.id
INNER JOIN etiquetas t         ON t.id = ee.etiqueta_id
GROUP BY e.id, e.titulo;

-- 4) INNER JOIN: comentarios de una entrada con el nombre de quien comentó
SELECT e.titulo, u.nombre AS comentarista, cm.texto, cm.created_at
FROM comentarios cm
INNER JOIN entradas e ON e.id = cm.entrada_id
INNER JOIN usuarios u ON u.id = cm.usuario_id
WHERE e.id = 1
ORDER BY cm.created_at;

-- 5) Consultas de contacto pendientes, con su motivo
SELECT q.id, q.nombre, q.email, m.nombre AS motivo, q.mensaje
FROM consultas q
INNER JOIN motivos_consulta m ON m.id = q.motivo_id
WHERE q.atendida = 0
ORDER BY q.created_at;

-- =====================================================================
-- UPDATE (siempre con WHERE)
-- =====================================================================

-- Marcar como atendida la consulta de Carlos
UPDATE consultas
SET atendida = 1
WHERE id = 2;

-- Verificación
SELECT id, nombre, atendida FROM consultas WHERE id = 2;

-- =====================================================================
-- DELETE (siempre con WHERE)
-- =====================================================================

-- Dar de baja un comentario de prueba
INSERT INTO comentarios (entrada_id, usuario_id, texto) VALUES (2, 3, 'Comentario de prueba para borrar');
DELETE FROM comentarios
WHERE texto = 'Comentario de prueba para borrar';

-- Verificación: no debe quedar el comentario de prueba
SELECT COUNT(*) AS comentarios_de_prueba
FROM comentarios
WHERE texto = 'Comentario de prueba para borrar';
