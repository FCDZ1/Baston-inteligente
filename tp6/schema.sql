-- =====================================================================
-- schema.sql - Bastón Inteligente
-- TP 6: Esquema relacional en MySQL (3FN)
-- Ejecutar en phpMyAdmin (pestaña SQL / Importar) o por consola:
--   mysql -u root < schema.sql
-- =====================================================================

CREATE DATABASE IF NOT EXISTS baston_inteligente
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE baston_inteligente;

-- Orden inverso al de creación, para poder re-ejecutar el script
DROP TABLE IF EXISTS comentarios;
DROP TABLE IF EXISTS entrada_etiqueta;
DROP TABLE IF EXISTS etiquetas;
DROP TABLE IF EXISTS entradas;
DROP TABLE IF EXISTS consultas;
DROP TABLE IF EXISTS motivos_consulta;
DROP TABLE IF EXISTS categorias;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS roles;

-- ---------------------------------------------------------------------
-- Tablas maestras
-- ---------------------------------------------------------------------

-- Roles de usuario (admin, editor, visitante...)
CREATE TABLE roles (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  nombre      VARCHAR(30)  NOT NULL UNIQUE,
  descripcion VARCHAR(120) NULL
) ENGINE=InnoDB;

-- Categorías del blog (Pruebas de campo, Electrónica...)
CREATE TABLE categorias (
  id     INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(60) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- Etiquetas libres para las entradas (sensor, arduino...)
CREATE TABLE etiquetas (
  id     INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(40) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- Motivos del formulario de contacto (producto, curso, colaboración, otro)
CREATE TABLE motivos_consulta (
  id     INT AUTO_INCREMENT PRIMARY KEY,
  clave  VARCHAR(20) NOT NULL UNIQUE,
  nombre VARCHAR(60) NOT NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Usuarios (autenticación + datos personales)
-- ---------------------------------------------------------------------
CREATE TABLE usuarios (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  rol_id        INT          NOT NULL,
  nombre        VARCHAR(60)  NOT NULL,
  apellido      VARCHAR(60)  NOT NULL,
  email         VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,          -- se guarda el hash (password_hash de PHP), nunca la clave
  telefono      VARCHAR(15)  NULL,
  activo        TINYINT(1)   NOT NULL DEFAULT 1,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_usuarios_rol
    FOREIGN KEY (rol_id) REFERENCES roles (id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Tabla principal de negocio: entradas del blog
-- ---------------------------------------------------------------------
CREATE TABLE entradas (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  autor_id     INT          NOT NULL,
  categoria_id INT          NOT NULL,
  titulo       VARCHAR(150) NOT NULL,
  resumen      TEXT         NOT NULL,
  contenido    TEXT         NOT NULL,
  imagen       VARCHAR(120) NULL,
  publicado    TINYINT(1)   NOT NULL DEFAULT 1,
  fecha_publicacion DATE    NOT NULL,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_entradas_autor
    FOREIGN KEY (autor_id) REFERENCES usuarios (id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_entradas_categoria
    FOREIGN KEY (categoria_id) REFERENCES categorias (id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Relación N:M entradas <-> etiquetas
-- ---------------------------------------------------------------------
CREATE TABLE entrada_etiqueta (
  entrada_id  INT NOT NULL,
  etiqueta_id INT NOT NULL,
  PRIMARY KEY (entrada_id, etiqueta_id),
  CONSTRAINT fk_ee_entrada
    FOREIGN KEY (entrada_id) REFERENCES entradas (id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_ee_etiqueta
    FOREIGN KEY (etiqueta_id) REFERENCES etiquetas (id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Comentarios de las entradas
-- ---------------------------------------------------------------------
CREATE TABLE comentarios (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  entrada_id INT  NOT NULL,
  usuario_id INT  NOT NULL,
  texto      TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_comentarios_entrada
    FOREIGN KEY (entrada_id) REFERENCES entradas (id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_comentarios_usuario
    FOREIGN KEY (usuario_id) REFERENCES usuarios (id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Consultas recibidas por el formulario de contacto (Clase 5)
-- usuario_id es opcional: puede escribir alguien sin cuenta.
-- ---------------------------------------------------------------------
CREATE TABLE consultas (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  motivo_id  INT          NOT NULL,
  usuario_id INT          NULL,
  nombre     VARCHAR(60)  NOT NULL,
  email      VARCHAR(100) NOT NULL,
  telefono   VARCHAR(15)  NULL,
  mensaje    TEXT         NOT NULL,
  atendida   TINYINT(1)   NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_consultas_motivo
    FOREIGN KEY (motivo_id) REFERENCES motivos_consulta (id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_consultas_usuario
    FOREIGN KEY (usuario_id) REFERENCES usuarios (id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Índices explícitos (campos de filtrado frecuente)
-- ---------------------------------------------------------------------
-- Alta cardinalidad: búsqueda de consultas por correo del remitente
CREATE INDEX idx_consultas_email ON consultas (email);
-- Filtrado del blog por categoría y orden por fecha
CREATE INDEX idx_entradas_categoria_fecha ON entradas (categoria_id, fecha_publicacion);
-- Bandeja de consultas pendientes
CREATE INDEX idx_consultas_atendida ON consultas (atendida);
