-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-09-2023 a las 23:32:17
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tpcomanda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `pedidosPendientes` varchar(250) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `relacionPedido` int(11) DEFAULT NULL,
  `mesa` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `pedidosPendientes`, `foto`, `relacionPedido`, `mesa`) VALUES
(1, 'mariela', NULL, NULL, NULL, '1'),
(17, 'luciano', NULL, 'Hyperscape 2020.07.12 - 21.27.18.22.DVR_Moment.jpg', NULL, '3'),
(18, 'cliente', NULL, NULL, NULL, '4');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `rol` varchar(25) NOT NULL,
  `nombrePedido` varchar(50) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `estado` varchar(50) NOT NULL,
  `pedidoAsignado` varchar(50) DEFAULT NULL,
  `ultimaSesion` varchar(50) DEFAULT NULL,
  `contrato` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `nombre`, `rol`, `nombrePedido`, `cantidad`, `estado`, `pedidoAsignado`, `ultimaSesion`, `contrato`) VALUES
(1, 'juan', 'admin', 'milanesa a caballo', 2, 'desocupado', '36', '2023-07-12 00:20:50', 'cervecero'),
(2, 'EJuan', 'bartender', NULL, NULL, 'desocupado', NULL, '2023-07-12 00:21:42', 'nuevo'),
(3, 'ERamiro', 'cervecero', NULL, NULL, 'desocupado', NULL, NULL, 'nuevo'),
(4, 'ERoberto', 'cocinero', NULL, NULL, 'desocupado', NULL, NULL, 'nuevo'),
(5, 'EJose', 'mozo', 'helado', 2, 'desocupado', '37', NULL, 'nuevo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `numMesa` int(11) NOT NULL,
  `cliente` int(11) DEFAULT NULL,
  `pedidos` varchar(250) DEFAULT NULL,
  `estado` varchar(50) NOT NULL,
  `totalDeLaCuenta` float NOT NULL,
  `foto` varchar(50) DEFAULT NULL,
  `puntuarLaMesa` int(11) DEFAULT NULL,
  `puntuarLaRestaurante` int(11) DEFAULT NULL,
  `puntuarLaMozo` int(11) DEFAULT NULL,
  `puntuarLaCocinero` int(11) DEFAULT NULL,
  `experiencia` varchar(100) DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `numMesa`, `cliente`, `pedidos`, `estado`, `totalDeLaCuenta`, `foto`, `puntuarLaMesa`, `puntuarLaRestaurante`, `puntuarLaMozo`, `puntuarLaCocinero`, `experiencia`, `fecha`) VALUES
(1, 1, 15, NULL, 'vacia', 50, NULL, NULL, NULL, NULL, NULL, NULL, '2023-06-11'),
(2, 2, 16, NULL, 'vacia', 500, NULL, NULL, NULL, NULL, NULL, NULL, '2023-06-01'),
(4, 3, 17, NULL, 'vacia', 150, 'Hyperscape 2020.07.12 - 21.27.18.22.DVR_Moment.jpg', NULL, NULL, NULL, NULL, NULL, '2023-07-03'),
(5, 4, NULL, NULL, 'vacia', 0, NULL, 2, 3, 3, 3, 'aaaa', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `tipoDePedido` varchar(25) NOT NULL,
  `cantidad` float DEFAULT NULL,
  `precio` float NOT NULL,
  `estado` varchar(25) NOT NULL,
  `tiempo` varchar(25) NOT NULL,
  `cliente` int(11) NOT NULL,
  `moso` varchar(50) NOT NULL,
  `numeroMesa` int(11) DEFAULT NULL,
  `fotoMesa` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `nombre`, `tipoDePedido`, `cantidad`, `precio`, `estado`, `tiempo`, `cliente`, `moso`, `numeroMesa`, `fotoMesa`) VALUES
(1, 'cerveza', 'chopera', NULL, 100, 'entregado', '2', 0, 'juan', NULL, NULL),
(14, 'helado', 'candyBar', 2, 50, 'en preparación', '10', 17, '5', NULL, NULL),
(15, 'helado', 'candyBar', 2, 50, 'en preparación', '10', 17, '5', NULL, 'Hyperscape 2020.07.12 - 21.27.18.22.DVR_Moment.jpg'),
(31, 'helado', 'candyBar', 2, 50, 'en preparación', '10', 17, '5', NULL, NULL),
(32, 'helado', 'candyBar', 2, 50, 'en preparación', '10', 17, '5', NULL, NULL),
(33, 'milanesa a caballo', 'cocina', 2, 380, 'en preparación', '8', 17, '1', NULL, NULL),
(34, 'milanesa a caballo', 'cocina', 2, 380, 'en preparación', '8', 17, '1', NULL, NULL),
(35, 'milanesa a caballo', 'cocina', 2, 380, 'en preparación', '8', 17, '1', NULL, NULL),
(36, 'milanesa a caballo', 'cocina', 2, 380, 'en preparación', '8', 17, '1', 4, NULL),
(37, 'helado', 'candyBar', 2, 50, 'en preparación', '10', 17, '5', 3, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `platos`
--

CREATE TABLE `platos` (
  `id` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `nombre` varchar(25) NOT NULL,
  `precio` float NOT NULL,
  `timepoEstimado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `platos`
--

INSERT INTO `platos` (`id`, `tipo`, `nombre`, `precio`, `timepoEstimado`) VALUES
(1, 'chopera', 'cerveza', 100, 5),
(2, 'barra', 'ron', 150, 5),
(3, 'candyBar', 'helado', 50, 10),
(4, 'cocina', 'asado', 400, 20),
(5, 'cocina', 'milanesa a caballo', 380, 8);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `platos`
--
ALTER TABLE `platos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `platos`
--
ALTER TABLE `platos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
