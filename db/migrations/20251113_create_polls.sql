-- Migration: crear tablas para encuestas/votaciones
-- Ejecutar en la base de datos del proyecto

CREATE TABLE IF NOT EXISTS `Encuestas` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `idEvento` INT DEFAULT NULL,
  `titulo` VARCHAR(255) NOT NULL,
  `descripcion` TEXT,
  `creador` INT DEFAULT NULL,
  `tipo` VARCHAR(20) DEFAULT 'votacion',
  `fechaCreado` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX (`idEvento`)
);

CREATE TABLE IF NOT EXISTS `Encuesta_Opciones` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `idEncuesta` INT NOT NULL,
  `texto` VARCHAR(255) NOT NULL,
  `orden` INT DEFAULT 0,
  FOREIGN KEY (`idEncuesta`) REFERENCES `Encuestas`(`id`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `Encuesta_Votos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `idEncuesta` INT NOT NULL,
  `idOpcion` INT NOT NULL,
  `idUsuario` INT NOT NULL,
  `fechaVoto` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`idEncuesta`) REFERENCES `Encuestas`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`idOpcion`) REFERENCES `Encuesta_Opciones`(`id`) ON DELETE CASCADE,
  UNIQUE KEY `unique_encuesta_usuario` (`idEncuesta`, `idUsuario`)
);

