-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 07-05-2026 a las 17:20:51
-- Versión del servidor: 10.6.23-MariaDB-0ubuntu0.22.04.1
-- Versión de PHP: 8.1.2-1ubuntu2.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `IoTMonitorizacion`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Lectura`
--

CREATE TABLE `Lectura` (
  `id` int(11) NOT NULL,
  `idListado` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `valor` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Lectura`
--

INSERT INTO `Lectura` (`id`, `idListado`, `fecha`, `valor`) VALUES
(181, 2, '2026-04-21 00:00:00', 18.8),
(182, 2, '2026-04-21 01:00:00', 18.8),
(183, 2, '2026-04-21 02:00:00', 17.8),
(184, 2, '2026-04-21 03:00:00', 17.8),
(185, 2, '2026-04-21 04:00:00', 16.8),
(186, 2, '2026-04-21 05:00:00', 16.8),
(187, 2, '2026-04-21 06:00:00', 17.8),
(188, 2, '2026-04-21 07:00:00', 17.8),
(189, 2, '2026-04-21 08:00:00', 18.8),
(190, 2, '2026-04-21 09:00:00', 19.8),
(191, 2, '2026-04-21 10:00:00', 19.8),
(192, 2, '2026-04-21 11:00:00', 20.8),
(193, 2, '2026-04-21 12:00:00', 20.8),
(194, 2, '2026-04-21 13:00:00', 21.8),
(195, 2, '2026-04-21 14:00:00', 22.8),
(196, 2, '2026-04-21 15:00:00', 22.8),
(197, 2, '2026-04-21 16:00:00', 22.8),
(198, 2, '2026-04-21 17:00:00', 22.8),
(199, 2, '2026-04-21 18:00:00', 21.8),
(200, 2, '2026-04-21 19:00:00', 21.8),
(201, 2, '2026-04-21 20:00:00', 20.8),
(202, 2, '2026-04-21 21:00:00', 20.8),
(203, 2, '2026-04-21 22:00:00', 19.8),
(204, 2, '2026-04-21 23:00:00', 19.8),
(205, 2, '2026-04-22 00:00:00', 18.8),
(206, 2, '2026-04-22 01:00:00', 18.8),
(207, 2, '2026-04-22 02:00:00', 17.8),
(208, 2, '2026-04-22 03:00:00', 17.8),
(209, 2, '2026-04-22 04:00:00', 16.8),
(210, 2, '2026-04-22 05:00:00', 16.8),
(211, 2, '2026-04-22 06:00:00', 17.8),
(212, 2, '2026-04-22 07:00:00', 17.8),
(213, 2, '2026-04-22 08:00:00', 18.8),
(214, 2, '2026-04-22 09:00:00', 19.8),
(215, 2, '2026-04-22 10:00:00', 19.8),
(216, 2, '2026-04-22 11:00:00', 20.8),
(217, 2, '2026-04-22 12:00:00', 20.8),
(218, 2, '2026-04-22 13:00:00', 21.8),
(219, 2, '2026-04-22 14:00:00', 22.8),
(220, 2, '2026-04-22 15:00:00', 22.8),
(221, 2, '2026-04-22 16:00:00', 22.8),
(222, 2, '2026-04-22 17:00:00', 22.8),
(223, 2, '2026-04-22 18:00:00', 21.8),
(224, 2, '2026-04-22 19:00:00', 21.8),
(225, 2, '2026-04-22 20:00:00', 20.8),
(226, 2, '2026-04-22 21:00:00', 20.8),
(227, 2, '2026-04-22 22:00:00', 19.8),
(228, 2, '2026-04-22 23:00:00', 19.8),
(229, 3, '2026-04-22 00:00:00', 18.8),
(230, 3, '2026-04-22 01:00:00', 18.8),
(231, 3, '2026-04-22 02:00:00', 17.8),
(232, 3, '2026-04-22 03:00:00', 17.8),
(233, 3, '2026-04-22 04:00:00', 16.8),
(234, 3, '2026-04-22 05:00:00', 16.8),
(235, 3, '2026-04-22 06:00:00', 17.8),
(236, 3, '2026-04-22 07:00:00', 17.8),
(237, 3, '2026-04-22 08:00:00', 18.8),
(238, 3, '2026-04-22 09:00:00', 19.8),
(239, 3, '2026-04-22 10:00:00', 19.8),
(240, 3, '2026-04-22 11:00:00', 20.8),
(241, 3, '2026-04-22 12:00:00', 20.8),
(242, 3, '2026-04-22 13:00:00', 21.8),
(243, 3, '2026-04-22 14:00:00', 22.8),
(244, 3, '2026-04-22 15:00:00', 22.8),
(245, 3, '2026-04-22 16:00:00', 22.8),
(246, 3, '2026-04-22 17:00:00', 22.8),
(247, 3, '2026-04-22 18:00:00', 21.8),
(248, 3, '2026-04-22 19:00:00', 21.8),
(249, 3, '2026-04-22 20:00:00', 20.8),
(250, 3, '2026-04-22 21:00:00', 20.8),
(251, 3, '2026-04-22 22:00:00', 19.8),
(252, 3, '2026-04-22 23:00:00', 19.8),
(253, 4, '2026-04-22 00:00:00', 18.8),
(254, 4, '2026-04-22 01:00:00', 18.8),
(255, 4, '2026-04-22 02:00:00', 17.8),
(256, 4, '2026-04-22 03:00:00', 17.8),
(257, 4, '2026-04-22 04:00:00', 16.8),
(258, 4, '2026-04-22 05:00:00', 16.8),
(259, 4, '2026-04-22 06:00:00', 17.8),
(260, 4, '2026-04-22 07:00:00', 17.8),
(261, 4, '2026-04-22 08:00:00', 18.8),
(262, 4, '2026-04-22 09:00:00', 19.8),
(263, 4, '2026-04-22 10:00:00', 19.8),
(264, 4, '2026-04-22 11:00:00', 20.8),
(265, 4, '2026-04-22 12:00:00', 20.8),
(266, 4, '2026-04-22 13:00:00', 21.8),
(267, 4, '2026-04-22 14:00:00', 22.8),
(268, 4, '2026-04-22 15:00:00', 22.8),
(269, 4, '2026-04-22 16:00:00', 22.8),
(270, 4, '2026-04-22 17:00:00', 22.8),
(271, 4, '2026-04-22 18:00:00', 21.8),
(272, 4, '2026-04-22 19:00:00', 21.8),
(273, 4, '2026-04-22 20:00:00', 20.8),
(274, 4, '2026-04-22 21:00:00', 20.8),
(275, 4, '2026-04-22 22:00:00', 19.8),
(276, 4, '2026-04-22 23:00:00', 19.8),
(277, 2, '2026-04-23 00:00:00', 18.8),
(278, 2, '2026-04-23 01:00:00', 18.8),
(279, 2, '2026-04-23 02:00:00', 17.8),
(280, 2, '2026-04-23 03:00:00', 17.8),
(281, 2, '2026-04-23 04:00:00', 16.8),
(282, 2, '2026-04-23 05:00:00', 16.8),
(283, 2, '2026-04-23 06:00:00', 17.8),
(284, 2, '2026-04-23 07:00:00', 17.8),
(285, 2, '2026-04-23 08:00:00', 18.8),
(286, 2, '2026-04-23 09:00:00', 19.8),
(287, 2, '2026-04-23 10:00:00', 19.8),
(288, 2, '2026-04-23 11:00:00', 20.8),
(289, 2, '2026-04-23 12:00:00', 20.8),
(290, 2, '2026-04-23 13:00:00', 21.8),
(291, 2, '2026-04-23 14:00:00', 22.8),
(292, 2, '2026-04-23 15:00:00', 22.8),
(293, 2, '2026-04-23 16:00:00', 22.8),
(294, 2, '2026-04-23 17:00:00', 22.8),
(295, 2, '2026-04-23 18:00:00', 21.8),
(296, 2, '2026-04-23 19:00:00', 21.8),
(297, 2, '2026-04-23 20:00:00', 20.8),
(298, 2, '2026-04-23 21:00:00', 20.8),
(299, 2, '2026-04-23 22:00:00', 19.8),
(300, 2, '2026-04-23 23:00:00', 19.8),
(301, 3, '2026-04-23 00:00:00', 18.8),
(302, 3, '2026-04-23 01:00:00', 18.8),
(303, 3, '2026-04-23 02:00:00', 17.8),
(304, 3, '2026-04-23 03:00:00', 17.8),
(305, 3, '2026-04-23 04:00:00', 16.8),
(306, 3, '2026-04-23 05:00:00', 16.8),
(307, 3, '2026-04-23 06:00:00', 17.8),
(308, 3, '2026-04-23 07:00:00', 17.8),
(309, 3, '2026-04-23 08:00:00', 18.8),
(310, 3, '2026-04-23 09:00:00', 19.8),
(311, 3, '2026-04-23 10:00:00', 19.8),
(312, 3, '2026-04-23 11:00:00', 20.8),
(313, 3, '2026-04-23 12:00:00', 20.8),
(314, 3, '2026-04-23 13:00:00', 21.8),
(315, 3, '2026-04-23 14:00:00', 22.8),
(316, 3, '2026-04-23 15:00:00', 22.8),
(317, 3, '2026-04-23 16:00:00', 22.8),
(318, 3, '2026-04-23 17:00:00', 22.8),
(319, 3, '2026-04-23 18:00:00', 21.8),
(320, 3, '2026-04-23 19:00:00', 21.8),
(321, 3, '2026-04-23 20:00:00', 20.8),
(322, 3, '2026-04-23 21:00:00', 20.8),
(323, 3, '2026-04-23 22:00:00', 19.8),
(324, 3, '2026-04-23 23:00:00', 19.8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ListadoSensoresTrafo`
--

CREATE TABLE `ListadoSensoresTrafo` (
  `id` int(11) NOT NULL,
  `idTransformador` int(11) NOT NULL,
  `idSensor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ListadoSensoresTrafo`
--

INSERT INTO `ListadoSensoresTrafo` (`id`, `idTransformador`, `idSensor`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 2),
(4, 3, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Magnitud`
--

CREATE TABLE `Magnitud` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Magnitud`
--

INSERT INTO `Magnitud` (`id`, `nombre`) VALUES
(1, 'Presión'),
(2, 'Temperatura');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Sensor`
--

CREATE TABLE `Sensor` (
  `id` int(11) NOT NULL,
  `idMagnitud` int(11) NOT NULL,
  `codigo` varchar(6) NOT NULL,
  `modelo` varchar(100) NOT NULL,
  `rango` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Sensor`
--

INSERT INTO `Sensor` (`id`, `idMagnitud`, `codigo`, `modelo`, `rango`) VALUES
(1, 1, 'PSN001', 'Sensor PSN1', '0.1 a 1 Mpa'),
(2, 2, 'TMP001', 'Sensor T02', '-50 a 100º C'),
(3, 2, 'TMPX03', 'Sensor T05', '-100 a 65º C');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Subestacion`
--

CREATE TABLE `Subestacion` (
  `id` int(11) NOT NULL,
  `codigo` char(6) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `poblacion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Subestacion`
--

INSERT INTO `Subestacion` (`id`, `codigo`, `nombre`, `poblacion`) VALUES
(1, 'BCN001', 'Urgell', 'Barcelona'),
(2, 'BCN002', 'Tibidabo', 'Barcelona'),
(3, 'BCN003', 'Viladecans', 'Viladecans');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Transformador`
--

CREATE TABLE `Transformador` (
  `id` int(11) NOT NULL,
  `idSubestacion` int(11) NOT NULL,
  `numeroSerie` varchar(6) NOT NULL,
  `modelo` varchar(100) NOT NULL,
  `fechaFabricacion` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Transformador`
--

INSERT INTO `Transformador` (`id`, `idSubestacion`, `numeroSerie`, `modelo`, `fechaFabricacion`) VALUES
(1, 1, 'TRA001', 'Tesla A148', '2001-10-25'),
(2, 1, 'TRA002', 'Tesla A148', '2008-03-14'),
(3, 2, 'TRK001', 'Fermat G25', '2011-11-25'),
(4, 3, 'TRX002', 'Coulomb K19', '1999-05-26');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Lectura`
--
ALTER TABLE `Lectura`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_Lectura_01` (`idListado`);

--
-- Indices de la tabla `ListadoSensoresTrafo`
--
ALTER TABLE `ListadoSensoresTrafo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_ListadoSensoresTrafo_01` (`idTransformador`),
  ADD KEY `FK_ListadoSensoresTrafo_02` (`idSensor`);

--
-- Indices de la tabla `Magnitud`
--
ALTER TABLE `Magnitud`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `Sensor`
--
ALTER TABLE `Sensor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `FK_Sensor_01` (`idMagnitud`);

--
-- Indices de la tabla `Subestacion`
--
ALTER TABLE `Subestacion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `Transformador`
--
ALTER TABLE `Transformador`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numeroSerie` (`numeroSerie`),
  ADD KEY `FK_Transformador_01` (`idSubestacion`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Lectura`
--
ALTER TABLE `Lectura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=325;

--
-- AUTO_INCREMENT de la tabla `ListadoSensoresTrafo`
--
ALTER TABLE `ListadoSensoresTrafo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `Magnitud`
--
ALTER TABLE `Magnitud`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `Sensor`
--
ALTER TABLE `Sensor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `Subestacion`
--
ALTER TABLE `Subestacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de la tabla `Transformador`
--
ALTER TABLE `Transformador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Lectura`
--
ALTER TABLE `Lectura`
  ADD CONSTRAINT `FK_Lectura_01` FOREIGN KEY (`idListado`) REFERENCES `ListadoSensoresTrafo` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `ListadoSensoresTrafo`
--
ALTER TABLE `ListadoSensoresTrafo`
  ADD CONSTRAINT `FK_ListadoSensoresTrafo_01` FOREIGN KEY (`idTransformador`) REFERENCES `Transformador` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_ListadoSensoresTrafo_02` FOREIGN KEY (`idSensor`) REFERENCES `Sensor` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `Sensor`
--
ALTER TABLE `Sensor`
  ADD CONSTRAINT `FK_Sensor_01` FOREIGN KEY (`idMagnitud`) REFERENCES `Magnitud` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `Transformador`
--
ALTER TABLE `Transformador`
  ADD CONSTRAINT `FK_Transformador_01` FOREIGN KEY (`idSubestacion`) REFERENCES `Subestacion` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
