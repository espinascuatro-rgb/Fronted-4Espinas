-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-09-2026 a las 21:52:39
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
-- Estructura de tabla para la tabla `ambulancia`
--

CREATE TABLE `ambulancia` (
  `numero_de_serie` varchar(20) NOT NULL,
  `kilometraje` int(20) DEFAULT NULL,
  `matricula` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ambulancia`
--

INSERT INTO `ambulancia` (`numero_de_serie`, `kilometraje`, `matricula`) VALUES
('1', 1234, '5769');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento`
--

CREATE TABLE `documento` (
  `id_documentos` int(11) NOT NULL,
  `ruta` varchar(150) NOT NULL,
  `nombre` varchar(70) NOT NULL,
  `id_qr` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuesta`
--

CREATE TABLE `encuesta` (
  `id_encuesta` int(11) NOT NULL,
  `tipo_encuesta` varchar(35) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo_medico`
--

CREATE TABLE `equipo_medico` (
  `id_equipo_medico` int(11) NOT NULL,
  `insumos` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `funcionario`
--

CREATE TABLE `funcionario` (
  `id_tipo` varchar(20) NOT NULL,
  `usuario` varchar(35) NOT NULL,
  `contrasenia` varchar(60) NOT NULL,
  `credenciales` text DEFAULT NULL,
  `cedula` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `funcionario`
--

INSERT INTO `funcionario` (`id_tipo`, `usuario`, `contrasenia`, `credenciales`, `cedula`) VALUES
('1234', 'zMatepan', '$2y$10$X0bBYdiDoBV6IgbPYoPom.y8Ryh3IoV1lKTBShbTXP7ekibvOcqZC', '1234', NULL),
('1234567', 'Thomas', '$2y$10$65dkXWG.TU.NWjSemaAiG.d7g99ZmmhK3vrA62p95RBMLn/fLWYbO', 'viejo', NULL),
('54321', 'Mateo', '$2y$10$DPzB29SOOK77/lCi9sK59u3fuadN5EJDQCaEA0nuipN6TjWUeVnbq', '12345', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `funcionario_carga_documento`
--

CREATE TABLE `funcionario_carga_documento` (
  `cedula_funcionario` varchar(20) NOT NULL,
  `id_documentos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `cedula` varchar(20) NOT NULL,
  `carne_de_salud` varchar(30) DEFAULT NULL,
  `numero_de_telefono` varchar(12) DEFAULT NULL,
  `direccion` varchar(70) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`cedula`, `carne_de_salud`, `numero_de_telefono`, `direccion`) VALUES
('1234', '1234', '1234', 'Casa'),
('1234567', '1234567', '1234567', 'Casa'),
('54321', '1234', '1234', 'Casa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

CREATE TABLE `preguntas` (
  `id_pregunta` int(11) NOT NULL,
  `tipo_pregunta` varchar(35) DEFAULT NULL,
  `id_encuesta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `qr`
--

CREATE TABLE `qr` (
  `id_qr` int(11) NOT NULL,
  `link` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuesta`
--

CREATE TABLE `respuesta` (
  `id_respuesta` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `grafico` varchar(1000) DEFAULT NULL,
  `id_pregunta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_f`
--

CREATE TABLE `tipo_f` (
  `id_tipo` int(11) NOT NULL,
  `tipo` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_u`
--

CREATE TABLE `tipo_u` (
  `id_tipo_u` int(11) NOT NULL,
  `paciente` varchar(50) DEFAULT '0',
  `acompaniante` varchar(50) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `traslado`
--

CREATE TABLE `traslado` (
  `id_traslado` int(11) NOT NULL,
  `origen` varchar(50) DEFAULT NULL,
  `hora_de_salida` time DEFAULT NULL,
  `hora_de_llegada` time DEFAULT NULL,
  `cedula_funcionario` varchar(20) NOT NULL,
  `matricula_ambulancia` varchar(20) NOT NULL,
  `destino` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `traslado_biologico`
--

CREATE TABLE `traslado_biologico` (
  `id_traslado` int(11) NOT NULL,
  `id_traslado_b` varchar(20) DEFAULT NULL,
  `organo_parte` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `traslado_nobiologico`
--

CREATE TABLE `traslado_nobiologico` (
  `id_traslado` int(20) NOT NULL,
  `id_traslado_nb` varchar(20) DEFAULT NULL,
  `insumo_t` varchar(35) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `traslado_paciente`
--

CREATE TABLE `traslado_paciente` (
  `id_traslado` int(20) NOT NULL,
  `id_equipo_medico` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(20) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `id_tipo_u` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_participa_traslado`
--

CREATE TABLE `usuario_participa_traslado` (
  `id_usuario` int(20) NOT NULL,
  `id_traslado` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_responde_encuesta`
--

CREATE TABLE `usuario_responde_encuesta` (
  `id_usuario` int(20) NOT NULL,
  `id_encuesta` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ambulancia`
--
ALTER TABLE `ambulancia`
  ADD PRIMARY KEY (`numero_de_serie`);

--
-- Indices de la tabla `documento`
--
ALTER TABLE `documento`
  ADD PRIMARY KEY (`id_documentos`),
  ADD UNIQUE KEY `id_qr` (`id_qr`);

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
  ADD PRIMARY KEY (`id_tipo`),
  ADD KEY `fk_funcionario_tipo` (`cedula`);

--
-- Indices de la tabla `funcionario_carga_documento`
--
ALTER TABLE `funcionario_carga_documento`
  ADD PRIMARY KEY (`cedula_funcionario`,`id_documentos`),
  ADD KEY `fk_f_carga_documento` (`id_documentos`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`cedula`);

--
-- Indices de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD PRIMARY KEY (`id_pregunta`),
  ADD KEY `fk_preguntas_encuesta` (`id_encuesta`);

--
-- Indices de la tabla `qr`
--
ALTER TABLE `qr`
  ADD PRIMARY KEY (`id_qr`);

--
-- Indices de la tabla `respuesta`
--
ALTER TABLE `respuesta`
  ADD PRIMARY KEY (`id_respuesta`),
  ADD KEY `fk_respuesta_pregunta` (`id_pregunta`);

--
-- Indices de la tabla `tipo_f`
--
ALTER TABLE `tipo_f`
  ADD PRIMARY KEY (`id_tipo`);

--
-- Indices de la tabla `tipo_u`
--
ALTER TABLE `tipo_u`
  ADD PRIMARY KEY (`id_tipo_u`);

--
-- Indices de la tabla `traslado`
--
ALTER TABLE `traslado`
  ADD PRIMARY KEY (`id_traslado`),
  ADD KEY `fk_traslado_funcionario` (`cedula_funcionario`),
  ADD KEY `fk_traslado_ambulancia` (`matricula_ambulancia`);

--
-- Indices de la tabla `traslado_biologico`
--
ALTER TABLE `traslado_biologico`
  ADD PRIMARY KEY (`id_traslado`);

--
-- Indices de la tabla `traslado_nobiologico`
--
ALTER TABLE `traslado_nobiologico`
  ADD PRIMARY KEY (`id_traslado`);

--
-- Indices de la tabla `traslado_paciente`
--
ALTER TABLE `traslado_paciente`
  ADD PRIMARY KEY (`id_traslado`),
  ADD KEY `fk_traslado_p_equipo` (`id_equipo_medico`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `fk_usuario_persona` (`cedula`),
  ADD KEY `fk_usuario_tipo` (`id_tipo_u`);

--
-- Indices de la tabla `usuario_participa_traslado`
--
ALTER TABLE `usuario_participa_traslado`
  ADD PRIMARY KEY (`id_usuario`,`id_traslado`),
  ADD KEY `fk_u_participa_traslado` (`id_traslado`);

--
-- Indices de la tabla `usuario_responde_encuesta`
--
ALTER TABLE `usuario_responde_encuesta`
  ADD PRIMARY KEY (`id_usuario`,`id_encuesta`),
  ADD KEY `fk_u_responde_encuesta` (`id_encuesta`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `documento`
--
ALTER TABLE `documento`
  MODIFY `id_documentos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  MODIFY `id_encuesta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `equipo_medico`
--
ALTER TABLE `equipo_medico`
  MODIFY `id_equipo_medico` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `id_pregunta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `qr`
--
ALTER TABLE `qr`
  MODIFY `id_qr` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `respuesta`
--
ALTER TABLE `respuesta`
  MODIFY `id_respuesta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_f`
--
ALTER TABLE `tipo_f`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_u`
--
ALTER TABLE `tipo_u`
  MODIFY `id_tipo_u` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `traslado`
--
ALTER TABLE `traslado`
  MODIFY `id_traslado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(20) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `documento`
--
ALTER TABLE `documento`
  ADD CONSTRAINT `fk_documento_qr` FOREIGN KEY (`id_qr`) REFERENCES `qr` (`id_qr`) ON DELETE SET NULL;

--
-- Filtros para la tabla `funcionario`
--
ALTER TABLE `funcionario`
  ADD CONSTRAINT `fk_funcionario_persona` FOREIGN KEY (`id_tipo`) REFERENCES `persona` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_funcionario_tipo` FOREIGN KEY (`cedula`) REFERENCES `tipo_f` (`id_tipo`);

--
-- Filtros para la tabla `funcionario_carga_documento`
--
ALTER TABLE `funcionario_carga_documento`
  ADD CONSTRAINT `fk_f_carga_documento` FOREIGN KEY (`id_documentos`) REFERENCES `documento` (`id_documentos`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_f_carga_funcionario` FOREIGN KEY (`cedula_funcionario`) REFERENCES `funcionario` (`id_tipo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD CONSTRAINT `fk_preguntas_encuesta` FOREIGN KEY (`id_encuesta`) REFERENCES `encuesta` (`id_encuesta`) ON DELETE CASCADE;

--
-- Filtros para la tabla `respuesta`
--
ALTER TABLE `respuesta`
  ADD CONSTRAINT `fk_respuesta_pregunta` FOREIGN KEY (`id_pregunta`) REFERENCES `preguntas` (`id_pregunta`) ON DELETE CASCADE;

--
-- Filtros para la tabla `traslado`
--
ALTER TABLE `traslado`
  ADD CONSTRAINT `fk_traslado_ambulancia` FOREIGN KEY (`matricula_ambulancia`) REFERENCES `ambulancia` (`numero_de_serie`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_traslado_funcionario` FOREIGN KEY (`cedula_funcionario`) REFERENCES `funcionario` (`id_tipo`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `traslado_biologico`
--
ALTER TABLE `traslado_biologico`
  ADD CONSTRAINT `fk_traslado_b_traslado` FOREIGN KEY (`id_traslado`) REFERENCES `traslado` (`id_traslado`) ON DELETE CASCADE;

--
-- Filtros para la tabla `traslado_nobiologico`
--
ALTER TABLE `traslado_nobiologico`
  ADD CONSTRAINT `fk_traslado_nb_traslado` FOREIGN KEY (`id_traslado`) REFERENCES `traslado` (`id_traslado`) ON DELETE CASCADE;

--
-- Filtros para la tabla `traslado_paciente`
--
ALTER TABLE `traslado_paciente`
  ADD CONSTRAINT `fk_traslado_p_equipo` FOREIGN KEY (`id_equipo_medico`) REFERENCES `equipo_medico` (`id_equipo_medico`),
  ADD CONSTRAINT `fk_traslado_p_traslado` FOREIGN KEY (`id_traslado`) REFERENCES `traslado` (`id_traslado`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_persona` FOREIGN KEY (`cedula`) REFERENCES `persona` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuario_tipo` FOREIGN KEY (`id_tipo_u`) REFERENCES `tipo_u` (`id_tipo_u`);

--
-- Filtros para la tabla `usuario_participa_traslado`
--
ALTER TABLE `usuario_participa_traslado`
  ADD CONSTRAINT `fk_u_participa_traslado` FOREIGN KEY (`id_traslado`) REFERENCES `traslado` (`id_traslado`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_u_participa_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuario_responde_encuesta`
--
ALTER TABLE `usuario_responde_encuesta`
  ADD CONSTRAINT `fk_u_responde_encuesta` FOREIGN KEY (`id_encuesta`) REFERENCES `encuesta` (`id_encuesta`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_u_responde_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
