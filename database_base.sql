-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Tiempo de generación: 18-11-2025 a las 22:07:51
-- Versión del servidor: 10.8.2-MariaDB-1:10.8.2+maria~focal
-- Versión de PHP: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `database`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CALENDARIO_Eventos`
--

CREATE TABLE `CALENDARIO_Eventos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha` date NOT NULL,
  `horaIni` time NOT NULL,
  `horaFin` time NOT NULL,
  `fechaCreado` datetime NOT NULL,
  `creador` int(11) NOT NULL DEFAULT 1,
  `ultimaModifi` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CALENDARIO_Evento_Usuario`
--

CREATE TABLE `CALENDARIO_Evento_Usuario` (
  `idUsuario` int(11) NOT NULL,
  `idEvento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Chat_Grupos`
--

CREATE TABLE `Chat_Grupos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `creador` int(11) NOT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Chat_Grupo_Miembros`
--

CREATE TABLE `Chat_Grupo_Miembros` (
  `id` int(11) NOT NULL,
  `idGrupo` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `fecha_union` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Chat_Mensajes`
--

CREATE TABLE `Chat_Mensajes` (
  `id` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `idGrupo` int(11) DEFAULT NULL,
  `mensaje` text NOT NULL,
  `tipo` enum('privado','grupo') NOT NULL DEFAULT 'privado',
  `fecha` datetime NOT NULL DEFAULT current_timestamp()
) ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Encuestas`
--

CREATE TABLE `Encuestas` (
  `id` int(11) NOT NULL,
  `idEvento` int(11) DEFAULT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `creador` int(11) DEFAULT NULL,
  `tipo` varchar(20) DEFAULT 'votacion',
  `fechaCreado` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Encuesta_Opciones`
--

CREATE TABLE `Encuesta_Opciones` (
  `id` int(11) NOT NULL,
  `idEncuesta` int(11) NOT NULL,
  `texto` varchar(255) NOT NULL,
  `orden` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Encuesta_Votos`
--

CREATE TABLE `Encuesta_Votos` (
  `id` int(11) NOT NULL,
  `idEncuesta` int(11) NOT NULL,
  `idOpcion` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `fechaVoto` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuarios`
--

CREATE TABLE `Usuarios` (
  `id` int(11) NOT NULL COMMENT 'Id unico que identifica al usuario',
  `username` varchar(20) NOT NULL COMMENT 'Username del usuario',
  `nombre` varchar(50) NOT NULL COMMENT 'Nombre del usuario',
  `apellidos` varchar(255) NOT NULL COMMENT 'Apellido del usuario',
  `mail` varchar(255) NOT NULL COMMENT 'mail del usuario',
  `password` varchar(255) NOT NULL COMMENT 'Contraseña del usuario',
  `activo` int(1) NOT NULL COMMENT 'Estado del usuario: 0-> Desactivado; 1-> Activo',
  `rol` int(11) NOT NULL DEFAULT 0 COMMENT '0-> Solo puede Iniciar sesión y chatear; 1-> Puede añadir eventos; 2-> Admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `Usuarios`
--

INSERT INTO `Usuarios` (`id`, `username`, `nombre`, `apellidos`, `mail`, `password`, `activo`, `rol`) VALUES
(1, 'admin', 'Administrador', 'API', 'admin@apicolab.eus', '$2y$10$3OqHLtpMWfvzCJPZPz6sWOUEVizWDdY0eozC1Tn7AHQhd6EHpCBli', 1, 2),
(2, 'admin2', 'Administrador2', 'API2', 'admin2@apicolab.eus', '$2y$10$3OqHLtpMWfvzCJPZPz6sWOUEVizWDdY0eozC1Tn7AHQhd6EHpCBli', 1, 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `CALENDARIO_Eventos`
--
ALTER TABLE `CALENDARIO_Eventos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `CALENDARIO_Evento_Usuario`
--
ALTER TABLE `CALENDARIO_Evento_Usuario`
  ADD KEY `fk_usuario` (`idUsuario`),
  ADD KEY `fk_evento` (`idEvento`);

--
-- Indices de la tabla `Chat_Grupos`
--
ALTER TABLE `Chat_Grupos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `creador` (`creador`);

--
-- Indices de la tabla `Chat_Grupo_Miembros`
--
ALTER TABLE `Chat_Grupo_Miembros`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_grupo_usuario` (`idGrupo`,`idUsuario`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Indices de la tabla `Chat_Mensajes`
--
ALTER TABLE `Chat_Mensajes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idUsuario` (`idUsuario`),
  ADD KEY `idGrupo` (`idGrupo`);

--
-- Indices de la tabla `Encuestas`
--
ALTER TABLE `Encuestas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idEvento` (`idEvento`);

--
-- Indices de la tabla `Encuesta_Opciones`
--
ALTER TABLE `Encuesta_Opciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idEncuesta` (`idEncuesta`);

--
-- Indices de la tabla `Encuesta_Votos`
--
ALTER TABLE `Encuesta_Votos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_encuesta_usuario` (`idEncuesta`,`idUsuario`),
  ADD KEY `idOpcion` (`idOpcion`);

--
-- Indices de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `CALENDARIO_Eventos`
--
ALTER TABLE `CALENDARIO_Eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Chat_Grupos`
--
ALTER TABLE `Chat_Grupos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Chat_Grupo_Miembros`
--
ALTER TABLE `Chat_Grupo_Miembros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Chat_Mensajes`
--
ALTER TABLE `Chat_Mensajes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Encuestas`
--
ALTER TABLE `Encuestas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Encuesta_Opciones`
--
ALTER TABLE `Encuesta_Opciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Encuesta_Votos`
--
ALTER TABLE `Encuesta_Votos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id unico que identifica al usuario', AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Chat_Grupos`
--
ALTER TABLE `Chat_Grupos`
  ADD CONSTRAINT `chat_grupos_ibfk_1` FOREIGN KEY (`creador`) REFERENCES `Usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `Chat_Grupo_Miembros`
--
ALTER TABLE `Chat_Grupo_Miembros`
  ADD CONSTRAINT `chat_grupo_miembros_ibfk_1` FOREIGN KEY (`idGrupo`) REFERENCES `Chat_Grupos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_grupo_miembros_ibfk_2` FOREIGN KEY (`idUsuario`) REFERENCES `Usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `Chat_Mensajes`
--
ALTER TABLE `Chat_Mensajes`
  ADD CONSTRAINT `chat_mensajes_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `Usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_mensajes_ibfk_2` FOREIGN KEY (`idGrupo`) REFERENCES `Chat_Grupos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `Encuesta_Opciones`
--
ALTER TABLE `Encuesta_Opciones`
  ADD CONSTRAINT `encuesta_opciones_ibfk_1` FOREIGN KEY (`idEncuesta`) REFERENCES `Encuestas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `Encuesta_Votos`
--
ALTER TABLE `Encuesta_Votos`
  ADD CONSTRAINT `encuesta_votos_ibfk_1` FOREIGN KEY (`idEncuesta`) REFERENCES `Encuestas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `encuesta_votos_ibfk_2` FOREIGN KEY (`idOpcion`) REFERENCES `Encuesta_Opciones` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
