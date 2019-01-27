
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de datos: `mensajes`
--

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE IF NOT EXISTS `mensajes` (
  `id` int(11) NOT NULL,
  `remitente` int(11) NOT NULL,
  `destinatario` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `asunto` varchar(50) NOT NULL,
  `texto` varchar(500) NOT NULL,
  `respuestade` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Estructura de tabla para la tabla `usuarios`
--
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(8) NOT NULL,
  `clave` varchar(32) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `fecha_alta` date NOT NULL,
  `foto` varchar(15) DEFAULT 'foto.jpg'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `remitente` (`remitente`),
  ADD KEY `destinatario` (`destinatario`),
  ADD KEY `respuestade` (`respuestade`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
  
--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `mensajes_ibfk_1` FOREIGN KEY (`respuestade`) REFERENCES `mensajes` (`id`),
  ADD CONSTRAINT `mensajes_ibfk_2` FOREIGN KEY (`remitente`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `mensajes_ibfk_3` FOREIGN KEY (`destinatario`) REFERENCES `usuarios` (`id`);
