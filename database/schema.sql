-- Sistema Web + Multimedia - Esquema base
-- Ejecutar en MySQL/MariaDB (PHP 8+ / XAMPP-WAMP-Laragon)

CREATE DATABASE IF NOT EXISTS sistema_web CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_web;

-- Usuarios (Admin, Docente, Estudiante)
CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  correo VARCHAR(120) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  rol ENUM('admin','docente','estudiante') NOT NULL DEFAULT 'estudiante',
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Cursos
CREATE TABLE IF NOT EXISTS cursos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(200) NOT NULL,
  descripcion TEXT,
  imagen_portada VARCHAR(255) DEFAULT NULL,
  docente_id INT NOT NULL,
  activo TINYINT(1) DEFAULT 1,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (docente_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Inscripciones (estudiante - curso)
CREATE TABLE IF NOT EXISTS inscripciones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  estudiante_id INT NOT NULL,
  curso_id INT NOT NULL,
  inscrito_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY (estudiante_id, curso_id),
  FOREIGN KEY (estudiante_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE
);

-- Contenido multimedia por curso
CREATE TABLE IF NOT EXISTS contenidos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(200) NOT NULL,
  tipo VARCHAR(80) NOT NULL,
  url VARCHAR(500) NOT NULL,
  curso_id INT NOT NULL,
  orden INT DEFAULT 0,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE
);

-- Evaluaciones
CREATE TABLE IF NOT EXISTS evaluaciones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(200) NOT NULL,
  curso_id INT NOT NULL,
  docente_id INT NOT NULL,
  activa TINYINT(1) DEFAULT 1,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE,
  FOREIGN KEY (docente_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Preguntas de evaluación
CREATE TABLE IF NOT EXISTS preguntas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  evaluacion_id INT NOT NULL,
  enunciado TEXT NOT NULL,
  tipo ENUM('opcion_multiple','verdadero_falso','abierta') DEFAULT 'opcion_multiple',
  opciones JSON,
  respuesta_correcta VARCHAR(500),
  orden INT DEFAULT 0,
  FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones(id) ON DELETE CASCADE
);

-- Respuestas enviadas por estudiantes
CREATE TABLE IF NOT EXISTS respuestas_evaluacion (
  id INT AUTO_INCREMENT PRIMARY KEY,
  evaluacion_id INT NOT NULL,
  estudiante_id INT NOT NULL,
  pregunta_id INT NOT NULL,
  respuesta_texto TEXT,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones(id) ON DELETE CASCADE,
  FOREIGN KEY (estudiante_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (pregunta_id) REFERENCES preguntas(id) ON DELETE CASCADE
);

-- Resultados (calificación por evaluación y estudiante)
CREATE TABLE IF NOT EXISTS resultados (
  id INT AUTO_INCREMENT PRIMARY KEY,
  evaluacion_id INT NOT NULL,
  estudiante_id INT NOT NULL,
  calificacion DECIMAL(5,2),
  enviado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY (evaluacion_id, estudiante_id),
  FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones(id) ON DELETE CASCADE,
  FOREIGN KEY (estudiante_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Usuario admin por defecto (password: admin123)
INSERT INTO usuarios (nombre, correo, password, rol) VALUES
('Administrador', 'admin@sistema.edu', 'admin123', 'admin'),
('Docente Demo', 'docente@sistema.edu', 'docente123', 'docente'),
('Estudiante Demo', 'estudiante@sistema.edu', 'estudiante123', 'estudiante')
ON DUPLICATE KEY UPDATE nombre=VALUES(nombre);
