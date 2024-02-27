-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-02-2024 a las 18:27:45
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
(1, 'mariela', NULL, NULL, NULL, ''),
(17, 'luciano', NULL, NULL, NULL, ''),
(18, 'cliente', NULL, NULL, NULL, ''),
(19, 'test', NULL, NULL, NULL, ''),
(20, 'test', '[\"milanesa a caballo\",\"hamburguesa de garbanzo\",\"corona\",\"daikiri\"]', 'Hyperscape 2020.07.12 - 21.27.18.22.DVR_Moment.jpg', 6, '2'),
(21, 'julian', NULL, NULL, NULL, '4');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta`
--

CREATE TABLE `cuenta` (
  `id` int(11) NOT NULL,
  `numMesa` int(11) NOT NULL,
  `moso` int(11) NOT NULL,
  `idPedidos` varchar(500) NOT NULL,
  `totalAPagar` float NOT NULL,
  `foto` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `cuenta`
--

INSERT INTO `cuenta` (`id`, `numMesa`, `moso`, `idPedidos`, `totalAPagar`, `foto`) VALUES
(6, 2, 2, '[\"175\",\"176\",\"177\",\"178\"]', 1015, 'Hyperscape 2020.07.12 - 21.27.18.22.DVR_Moment.jpg'),
(7, 3, 2, '[\"175\",\"176\",\"177\",\"178\"]', 2000, 'Hyperscape 2020.07.12 - 21.27.18.22.DVR_Moment.jpg'),
(8, 2, 2, '[\"175\",\"176\",\"177\",\"178\"]', 4000, 'Hyperscape 2020.07.12 - 21.27.18.22.DVR_Moment.jpg'),
(9, 2, 2, '[\"175\",\"176\",\"177\",\"178\"]', 1200, 'Hyperscape 2020.07.12 - 21.27.18.22.DVR_Moment.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `rol` varchar(25) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `pedidoAsignado` varchar(50) DEFAULT NULL,
  `ultimaSesion` varchar(50) DEFAULT NULL,
  `contrato` varchar(25) NOT NULL,
  `contrasenia` varchar(200) DEFAULT NULL,
  `token` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `nombre`, `rol`, `estado`, `pedidoAsignado`, `ultimaSesion`, `contrato`, `contrasenia`, `token`) VALUES
(1, 'juan', 'admin', 'desocupado', NULL, '2024-02-20 05:38:43', 'cervecero', 'admin1', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MDkwMDg3MjMsImV4cCI6MTcwOTA2ODcyMywiYXVkIjoiYjIxOWMzZTMyODljYWRlNGZiMjc2MTdlOGYwZTYwMGRlNTc3YTFhZiIsImRhdGEiOiJqdWFuIiwiYXBwIjoiVGVzdCBKV1QifQ.oDEcOthCtiL0NYHjRJUQ8Q-JFkOuLDjWYp7M_wJEKeg'),
(2, 'EJuan', 'bartender', 'desocupado', '6', '2024-02-20 05:38:28', 'nuevo', '1234', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MDkwMDg3MDgsImV4cCI6MTcwOTA2ODcwOCwiYXVkIjoiYjIxOWMzZTMyODljYWRlNGZiMjc2MTdlOGYwZTYwMGRlNTc3YTFhZiIsImRhdGEiOiJFSnVhbiIsImFwcCI6IlRlc3QgSldUIn0.sRL-uPsBCEFu_N8unZGL41OF3l7IqerMIVbmjT3uidM'),
(3, 'ERamiro', 'cervecero', 'atendiendo', '[177,179]', '2024-02-27 16:18:10', 'nuevo', '1234', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MDkwNDcwOTAsImV4cCI6MTcwOTEwNzA5MCwiYXVkIjoiYjIxOWMzZTMyODljYWRlNGZiMjc2MTdlOGYwZTYwMGRlNTc3YTFhZiIsImRhdGEiOiJFUmFtaXJvIiwiYXBwIjoiVGVzdCBKV1QifQ.eWxIsAAMi76ma4_XQXuqYW7n_TRWTMqUjR8hv483VR8'),
(4, 'ERoberto', 'cocinero', 'desocupado', NULL, '2024-02-20 05:38:13', 'nuevo', '1234', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MDkwMDg2OTMsImV4cCI6MTcwOTA2ODY5MywiYXVkIjoiYjIxOWMzZTMyODljYWRlNGZiMjc2MTdlOGYwZTYwMGRlNTc3YTFhZiIsImRhdGEiOiJFUm9iZXJ0byIsImFwcCI6IlRlc3QgSldUIn0.9IvaYcBOLnoqjXNTabuQ8oAkiD_itbjFm8fLqiHZZLA'),
(5, 'EJose', 'mozo', 'atendiendo', NULL, '2024-02-27 17:11:41', 'nuevo', '1234', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MDkwNTAzMDEsImV4cCI6MTcwOTExMDMwMSwiYXVkIjoiYjIxOWMzZTMyODljYWRlNGZiMjc2MTdlOGYwZTYwMGRlNTc3YTFhZiIsImRhdGEiOiJFSm9zZSIsImFwcCI6IlRlc3QgSldUIn0.hKy6iq8Mqxcfuu4DQGn5zPxfU_5W3dfzDHfKcULPxVk');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuestas`
--

CREATE TABLE `encuestas` (
  `id` int(11) NOT NULL,
  `numMesa` int(11) NOT NULL,
  `cliente` int(11) NOT NULL,
  `pedidos` int(11) NOT NULL,
  `totalDeLaCuenta` int(11) DEFAULT NULL,
  `puntuarLaMesa` int(11) DEFAULT NULL,
  `puntuarElRestaurante` int(11) DEFAULT NULL,
  `puntuarLaMozo` int(11) DEFAULT NULL,
  `puntuarLaCocinero` int(11) DEFAULT NULL,
  `experiencia` varchar(500) DEFAULT NULL,
  `fecha` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `encuestas`
--

INSERT INTO `encuestas` (`id`, `numMesa`, `cliente`, `pedidos`, `totalDeLaCuenta`, `puntuarLaMesa`, `puntuarElRestaurante`, `puntuarLaMozo`, `puntuarLaCocinero`, `experiencia`, `fecha`) VALUES
(1, 2, 20, 0, 1015, 2, 3, 3, 3, 'aaaa', '2024-02-27'),
(2, 2, 20, 0, 1015, 1, 2, 4, 35, 'aaaa', '2024-02-28'),
(3, 3, 20, 0, 2000, 5, 1, 5, 3, 'aaaa', '2024-02-27'),
(4, 2, 20, 0, 1015, 5, 5, 5, 5, 'dDD', '2024-02-30'),
(5, 2, 20, 0, 1100, 5, 5, 5, 5, 'dDD', '2024-02-27');

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
  `foto` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `numMesa`, `cliente`, `pedidos`, `estado`, `totalDeLaCuenta`, `foto`) VALUES
(1, 1, NULL, NULL, 'vacia', 0, NULL),
(2, 2, 20, '[\"milanesa a caballo\",\"hamburguesa de garbanzo\",\"corona\",\"daikiri\"]', 'cliente pagando', 1015, 'Hyperscape 2020.07.12 - 21.27.18.22.DVR_Moment.jpg'),
(4, 3, NULL, NULL, 'vacia', 0, NULL),
(5, 4, 21, NULL, 'cliente esperando atencion', 0, NULL);

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
  `moso` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `nombre`, `tipoDePedido`, `cantidad`, `precio`, `estado`, `tiempo`, `cliente`, `moso`) VALUES
(175, 'milanesa a caballo', 'cocina', 1, 380, 'nuevo', '8', 20, '2'),
(176, 'hamburguesa de garbanzo', 'cocina', 2, 600, 'nuevo', '15', 20, '2'),
(177, 'corona', 'chopera', 1, 10, 'entregado', '2', 20, '2'),
(178, 'daikiri', 'barra', 1, 25, 'nuevo', '3', 20, '2'),
(179, 'corona', 'chopera', 1, 10, 'entregado', '2', 20, '20');

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
(5, 'cocina', 'milanesa a caballo', 380, 8),
(6, 'cocina', 'hamburguesa de garbanzo', 300, 15),
(7, 'barra', 'daikiri', 25, 3),
(8, 'chopera', 'corona', 10, 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cuenta`
--
ALTER TABLE `cuenta`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `encuestas`
--
ALTER TABLE `encuestas`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `cuenta`
--
ALTER TABLE `cuenta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `encuestas`
--
ALTER TABLE `encuestas`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=180;

--
-- AUTO_INCREMENT de la tabla `platos`
--
ALTER TABLE `platos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
