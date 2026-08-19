-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-08-2026 a las 18:49:04
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `hospital_de_clinicas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acompaniante`
--

CREATE TABLE `acompaniante` (
  `id_cedula_ac` int(11) NOT NULL,
  `parentesco` varchar(30) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `carne_salud` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ambulancia`
--

CREATE TABLE `ambulancia` (
  `numero_de_serie` int(11) NOT NULL,
  `matricula` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos`
--

CREATE TABLE `documentos` (
  `id_documentos` int(12) NOT NULL,
  `hora` varchar(5) DEFAULT NULL,
  `autor` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuesta`
--

CREATE TABLE `encuesta` (
  `id_encuesta` int(13) NOT NULL,
  `opciones` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo_medico`
--

CREATE TABLE `equipo_medico` (
  `id_equipo_medico` int(11) NOT NULL,
  `insumos` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `funcionario`
--

CREATE TABLE `funcionario` (
  `id_funcionario` int(11) NOT NULL,
  `credenciales` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paciente`
--

CREATE TABLE `paciente` (
  `id_paciente` int(15) NOT NULL,
  `tipo_de_sangre` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `cedula` int(20) NOT NULL,
  `carne_salud` int(30) NOT NULL,
  `telefono` int(20) NOT NULL,
  `direccion` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `qr`
--

CREATE TABLE `qr` (
  `id_qr` int(11) NOT NULL,
  `link` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuesta`
--

CREATE TABLE `respuesta` (
  `id_respuesta` int(14) NOT NULL,
  `grafico` varchar(50) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_f`
--

CREATE TABLE `tipo_f` (
  `id_tipo` int(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `traslado`
--

CREATE TABLE `traslado` (
  `id_traslado` int(11) NOT NULL,
  `hora_de_salida` varchar(5) DEFAULT NULL,
  `hora_de_llegada` varchar(5) DEFAULT NULL,
  `direccion` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `traslado_biologico`
--

CREATE TABLE `traslado_biologico` (
  `id_traslado_biologico` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `traslado_no_biologico`
--

CREATE TABLE `traslado_no_biologico` (
  `id_traslado_no_biologico` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `traslado_paciente`
--

CREATE TABLE `traslado_paciente` (
  `id_traslado_paciente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `acompaniante`
--
ALTER TABLE `acompaniante`
  ADD PRIMARY KEY (`id_cedula_ac`);

--
-- Indices de la tabla `ambulancia`
--
ALTER TABLE `ambulancia`
  ADD PRIMARY KEY (`numero_de_serie`);

--
-- Indices de la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id_documentos`);

--
-- Indices de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  ADD PRIMARY KEY (`id_encuesta`);

--
-- Indices de la tabla `equipo_medico`
--
ALTER TABLE `equipo_medico`
  ADD PRIMARY KEY (`id_equipo_medico`);

--
-- Indices de la tabla `funcionario`
--
ALTER TABLE `funcionario`
  ADD PRIMARY KEY (`id_funcionario`);

--
-- Indices de la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD PRIMARY KEY (`id_paciente`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`cedula`);

--
-- Indices de la tabla `qr`
--
ALTER TABLE `qr`
  ADD PRIMARY KEY (`id_qr`);

--
-- Indices de la tabla `respuesta`
--
ALTER TABLE `respuesta`
  ADD PRIMARY KEY (`id_respuesta`);

--
-- Indices de la tabla `tipo_f`
--
ALTER TABLE `tipo_f`
  ADD PRIMARY KEY (`id_tipo`);

--
-- Indices de la tabla `traslado`
--
ALTER TABLE `traslado`
  ADD PRIMARY KEY (`id_traslado`);

--
-- Indices de la tabla `traslado_biologico`
--
ALTER TABLE `traslado_biologico`
  ADD PRIMARY KEY (`id_traslado_biologico`);

--
-- Indices de la tabla `traslado_no_biologico`
--
ALTER TABLE `traslado_no_biologico`
  ADD PRIMARY KEY (`id_traslado_no_biologico`);

--
-- Indices de la tabla `traslado_paciente`
--
ALTER TABLE `traslado_paciente`
  ADD PRIMARY KEY (`id_traslado_paciente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `acompaniante`
--
ALTER TABLE `acompaniante`
  MODIFY `id_cedula_ac` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ambulancia`
--
ALTER TABLE `ambulancia`
  MODIFY `numero_de_serie` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id_documentos` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  MODIFY `id_encuesta` int(13) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `equipo_medico`
--
ALTER TABLE `equipo_medico`
  MODIFY `id_equipo_medico` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `funcionario`
--
ALTER TABLE `funcionario`
  MODIFY `id_funcionario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `paciente`
--
ALTER TABLE `paciente`
  MODIFY `id_paciente` int(15) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `cedula` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `qr`
--
ALTER TABLE `qr`
  MODIFY `id_qr` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `respuesta`
--
ALTER TABLE `respuesta`
  MODIFY `id_respuesta` int(14) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_f`
--
ALTER TABLE `tipo_f`
  MODIFY `id_tipo` int(13) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `traslado`
--
ALTER TABLE `traslado`
  MODIFY `id_traslado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `traslado_biologico`
--
ALTER TABLE `traslado_biologico`
  MODIFY `id_traslado_biologico` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `traslado_no_biologico`
--
ALTER TABLE `traslado_no_biologico`
  MODIFY `id_traslado_no_biologico` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `traslado_paciente`
--
ALTER TABLE `traslado_paciente`
  MODIFY `id_traslado_paciente` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `acompaniante`
--
ALTER TABLE `acompaniante`
  ADD CONSTRAINT `acompaniante_ibfk_1` FOREIGN KEY (`id_cedula_ac`) REFERENCES `persona` (`cedula`);

--
-- Filtros para la tabla `ambulancia`
--
ALTER TABLE `ambulancia`
  ADD CONSTRAINT `ambulancia_ibfk_1` FOREIGN KEY (`numero_de_serie`) REFERENCES `traslado` (`id_traslado`);

--
-- Filtros para la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD CONSTRAINT `documentos_ibfk_1` FOREIGN KEY (`id_documentos`) REFERENCES `funcionario` (`id_funcionario`);

--
-- Filtros para la tabla `encuesta`
--
ALTER TABLE `encuesta`
  ADD CONSTRAINT `encuesta_ibfk_1` FOREIGN KEY (`id_encuesta`) REFERENCES `paciente` (`id_paciente`);

--
-- Filtros para la tabla `equipo_medico`
--
ALTER TABLE `equipo_medico`
  ADD CONSTRAINT `equipo_medico_ibfk_1` FOREIGN KEY (`id_equipo_medico`) REFERENCES `traslado_paciente` (`id_traslado_paciente`);

--
-- Filtros para la tabla `funcionario`
--
ALTER TABLE `funcionario`
  ADD CONSTRAINT `funcionario_ibfk_1` FOREIGN KEY (`id_funcionario`) REFERENCES `persona` (`cedula`);

--
-- Filtros para la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD CONSTRAINT `paciente_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `qr` (`id_qr`);

--
-- Filtros para la tabla `qr`
--
ALTER TABLE `qr`
  ADD CONSTRAINT `qr_ibfk_1` FOREIGN KEY (`id_qr`) REFERENCES `documentos` (`id_documentos`);

--
-- Filtros para la tabla `respuesta`
--
ALTER TABLE `respuesta`
  ADD CONSTRAINT `respuesta_ibfk_1` FOREIGN KEY (`id_respuesta`) REFERENCES `encuesta` (`id_encuesta`);

--
-- Filtros para la tabla `tipo_f`
--
ALTER TABLE `tipo_f`
  ADD CONSTRAINT `tipo_f_ibfk_1` FOREIGN KEY (`id_tipo`) REFERENCES `funcionario` (`id_funcionario`);

--
-- Filtros para la tabla `traslado`
--
ALTER TABLE `traslado`
  ADD CONSTRAINT `traslado_ibfk_1` FOREIGN KEY (`id_traslado`) REFERENCES `funcionario` (`id_funcionario`);

--
-- Filtros para la tabla `traslado_biologico`
--
ALTER TABLE `traslado_biologico`
  ADD CONSTRAINT `traslado_biologico_ibfk_1` FOREIGN KEY (`id_traslado_biologico`) REFERENCES `traslado` (`id_traslado`);

--
-- Filtros para la tabla `traslado_no_biologico`
--
ALTER TABLE `traslado_no_biologico`
  ADD CONSTRAINT `traslado_no_biologico_ibfk_1` FOREIGN KEY (`id_traslado_no_biologico`) REFERENCES `traslado` (`id_traslado`);

--
-- Filtros para la tabla `traslado_paciente`
--
ALTER TABLE `traslado_paciente`
  ADD CONSTRAINT `traslado_paciente_ibfk_1` FOREIGN KEY (`id_traslado_paciente`) REFERENCES `traslado` (`id_traslado`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
