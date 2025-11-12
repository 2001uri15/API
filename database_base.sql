-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Tiempo de generación: 12-11-2025 a las 12:17:24
-- Versión del servidor: 10.8.2-MariaDB-1:10.8.2+maria~focal
-- Versión de PHP: 8.2.27

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
  `ultimaModifi` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CALENDARIO_Evento_Usuario`
--

CREATE TABLE `CALENDARIO_Evento_Usuario` (
  `idUsuario` int(11) NOT NULL,
  `idEvento` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
  `rol` int(11) NOT NULL DEFAULT 0 COMMENT '0-> Solo puede Iniciar sesión y chatear; 1-> Puede añadir eventos; 2->ç'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `Usuarios`
--

INSERT INTO `Usuarios` (`id`, `username`, `nombre`, `apellidos`, `mail`, `password`, `activo`, `rol`) VALUES
(1, 'admin', 'Administrador', 'API', 'alarrazabal@innoboatbizkaia.eus', '$2y$10$3OqHLtpMWfvzCJPZPz6sWOUEVizWDdY0eozC1Tn7AHQhd6EHpCBli', 1, 2);

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
-- AUTO_INCREMENT de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id unico que identifica al usuario', AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
