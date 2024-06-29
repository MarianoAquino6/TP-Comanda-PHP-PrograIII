-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 29-06-2024 a las 06:01:06
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `la_comanda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria`
--

CREATE TABLE `auditoria` (
  `id` int(11) NOT NULL,
  `username` varchar(500) NOT NULL,
  `url` varchar(600) NOT NULL,
  `parametros` varchar(1000) DEFAULT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `auditoria`
--

INSERT INTO `auditoria` (`id`, `username`, `url`, `parametros`, `fecha`) VALUES
(63, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/registrar', '{\"username\":\"admin2\",\"pass\":\"Adm1n!!!\",\"sector\":\"ADMIN\"}', '2024-06-28 19:10:48'),
(64, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/registrar', '{\"username\":\"admin3\",\"pass\":\"Adm1n!!!\",\"sector\":\"ADMIN\"}', '2024-06-28 19:11:00'),
(65, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/registrar', '{\"username\":\"admin4\",\"pass\":\"Adm1n!!!\",\"sector\":\"ADMIN\"}', '2024-06-28 19:11:04'),
(66, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/registrar', '{\"username\":\"mozo1\",\"pass\":\"M0zzo!!!\",\"sector\":\"MOZO\"}', '2024-06-28 19:24:01'),
(67, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/registrar', '{\"username\":\"mozo2\",\"pass\":\"M0zzo!!!\",\"sector\":\"MOZO\"}', '2024-06-28 19:24:23'),
(68, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/registrar', '{\"username\":\"mozo3\",\"pass\":\"M0zzo!!!\",\"sector\":\"MOZO\"}', '2024-06-28 19:24:26'),
(69, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/registrar', '{\"username\":\"mozo4\",\"pass\":\"M0zzo!!!\",\"sector\":\"MOZO\"}', '2024-06-28 19:24:29'),
(70, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/registrar', '{\"username\":\"cervecero1\",\"pass\":\"C3rve!!!\",\"sector\":\"CERVECERO\"}', '2024-06-28 19:25:45'),
(71, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/registrar', '{\"username\":\"cervecero2\",\"pass\":\"C3rve!!!\",\"sector\":\"CERVECERO\"}', '2024-06-28 19:25:58'),
(72, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/registrar', '{\"username\":\"cervecero3\",\"pass\":\"C3rve!!!\",\"sector\":\"CERVECERO\"}', '2024-06-28 19:26:02'),
(73, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/registrar', '{\"username\":\"bartender1\",\"pass\":\"B4rT!!!\",\"sector\":\"BARTENDER\"}', '2024-06-28 19:27:17'),
(74, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/registrar', '{\"username\":\"bartender2\",\"pass\":\"B4rT!!!\",\"sector\":\"BARTENDER\"}', '2024-06-28 19:27:37'),
(75, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/registrar', '{\"username\":\"bartender3\",\"pass\":\"B4rT!!!\",\"sector\":\"BARTENDER\"}', '2024-06-28 19:27:41'),
(76, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/registrar', '{\"username\":\"cocinero1\",\"pass\":\"C0Cin4!!\",\"sector\":\"BARTENDER\"}', '2024-06-28 19:28:17'),
(77, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/registrar', '{\"username\":\"cocinero2\",\"pass\":\"C0Cin4!!\",\"sector\":\"COCINERO\"}', '2024-06-28 19:28:37'),
(78, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/registrar', '{\"username\":\"cocinero3\",\"pass\":\"C0Cin4!!\",\"sector\":\"COCINERO\"}', '2024-06-28 19:28:40'),
(79, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/modificar-username', '{\"usernameNuevo\":\"cocinero5\",\"usernameOriginal\":\"cocinero1\"}', '2024-06-28 19:30:18'),
(80, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/modificar-username', '{\"usernameNuevo\":\"cocinero1\",\"usernameOriginal\":\"cocinero5\"}', '2024-06-28 19:31:30'),
(81, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/modificar-pass', '{\"username\":\"cocinero1\",\"pass\":\"Mozo!!114\"}', '2024-06-28 19:31:54'),
(82, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/modificar-pass', '{\"username\":\"cocinero1\",\"pass\":\"C0Cin4!!\"}', '2024-06-28 19:32:48'),
(83, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/modificar-sector', '{\"username\":\"cocinero1\",\"sector\":\"MOZO\"}', '2024-06-28 19:33:49'),
(84, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/modificar-sector', '{\"username\":\"cocinero1\",\"sector\":\"COCINERO\"}', '2024-06-28 19:36:08'),
(85, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/baja', '{\"username\":\"cocinero1\"}', '2024-06-28 19:37:24'),
(86, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/reactivar', '{\"username\":\"cocinero1\"}', '2024-06-28 19:38:42'),
(87, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/borrar', '{\"username\":\"bartender3\"}', '2024-06-28 19:39:26'),
(88, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/', NULL, '2024-06-28 19:40:02'),
(89, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/', NULL, '2024-06-28 19:40:29'),
(90, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/', NULL, '2024-06-28 19:41:19'),
(91, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/logs-usuario?username=admin1', '{\"username\":\"admin1\"}', '2024-06-28 19:41:50'),
(92, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/logs-usuario?username=cocinero1', '{\"username\":\"cocinero1\"}', '2024-06-28 19:41:59'),
(93, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/logs-usuario?username=cocinero1', '{\"username\":\"cocinero1\"}', '2024-06-28 19:42:23'),
(94, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/pdf', NULL, '2024-06-28 19:42:48'),
(95, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/pdf', NULL, '2024-06-28 19:42:57'),
(96, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/registrar', '{\"codigo\":\"mesa1\"}', '2024-06-28 21:28:24'),
(97, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/registrar', '{\"codigo\":\"mesa2\"}', '2024-06-28 21:28:44'),
(98, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/registrar', '{\"codigo\":\"mesa3\"}', '2024-06-28 21:28:48'),
(99, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/registrar', '{\"codigo\":\"mesa4\"}', '2024-06-28 21:28:51'),
(100, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/borrar', '{\"codigo\":\"mesa1\"}', '2024-06-28 21:31:56'),
(101, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/', NULL, '2024-06-28 21:32:38'),
(102, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/mas-usada', NULL, '2024-06-28 21:32:55'),
(103, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/menos-usada', NULL, '2024-06-28 21:33:44'),
(104, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/mas-usada', NULL, '2024-06-28 21:34:44'),
(105, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/menos-usada', NULL, '2024-06-28 21:34:50'),
(106, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/mayor-facturacion', NULL, '2024-06-28 21:35:08'),
(107, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/mayor-facturacion', NULL, '2024-06-28 21:35:41'),
(108, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/menor-facturacion', NULL, '2024-06-28 21:35:55'),
(109, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/mayor-importe', NULL, '2024-06-28 21:36:08'),
(110, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/mayor-importe', NULL, '2024-06-28 21:36:36'),
(111, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/menor-importe', NULL, '2024-06-28 21:36:47'),
(112, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/ordenar-segun-importe', NULL, '2024-06-28 21:36:58'),
(113, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/ordenar-segun-importe', NULL, '2024-06-28 21:37:58'),
(114, 'admin1', '/TP-Comanda-PHP-PrograIII/app/productos', NULL, '2024-06-28 21:39:15'),
(115, 'admin1', '/TP-Comanda-PHP-PrograIII/app/productos', NULL, '2024-06-28 21:39:45'),
(116, 'admin1', '/TP-Comanda-PHP-PrograIII/app/productos/csv', NULL, '2024-06-28 21:39:57'),
(117, 'admin1', '/TP-Comanda-PHP-PrograIII/app/productos/registrar', '{\"tipo\":\"COMIDA\",\"codigo\":\"milanesaCaballo1\",\"nombre\":\"Milanesa a caballo\",\"precio\":15000}', '2024-06-28 21:42:09'),
(118, 'admin1', '/TP-Comanda-PHP-PrograIII/app/productos/registrar', '{\"tipo\":\"COMIDA\",\"codigo\":\"hamburguesaGarbanzo\",\"nombre\":\"Hamburguesa de Garbanzo\",\"precio\":7000}', '2024-06-28 21:42:53'),
(119, 'admin1', '/TP-Comanda-PHP-PrograIII/app/productos/registrar', '{\"tipo\":\"CERVEZA\",\"codigo\":\"corona700\",\"nombre\":\"Corona 700 ml\",\"precio\":3000}', '2024-06-28 21:43:47'),
(120, 'admin1', '/TP-Comanda-PHP-PrograIII/app/productos/registrar', '{\"tipo\":\"TRAGO\",\"codigo\":\"daikiri1\",\"nombre\":\"Daikiri\",\"precio\":5000}', '2024-06-28 21:44:35'),
(121, 'admin1', '/TP-Comanda-PHP-PrograIII/app/productos/registrar', '{\"tipo\":\"COMIDA\",\"codigo\":\"pizzaPepperoni\",\"nombre\":\"Pizza de Pepperoni\",\"precio\":16000}', '2024-06-28 21:45:44'),
(122, 'admin1', '/TP-Comanda-PHP-PrograIII/app/productos/registrar', '{\"tipo\":\"CERVEZA\",\"codigo\":\"AndesIPA\",\"nombre\":\"Andes IPA 1 lt\",\"precio\":6500}', '2024-06-28 21:46:18'),
(123, 'admin1', '/TP-Comanda-PHP-PrograIII/app/productos/registrar', '{\"tipo\":\"TRAGO\",\"codigo\":\"campari\",\"nombre\":\"Campari\",\"precio\":4500}', '2024-06-28 21:46:50'),
(124, 'SocioMaestro3', '/TP-Comanda-PHP-PrograIII/app/productos/csv', NULL, '2024-06-28 21:48:39'),
(125, 'admin1', '/TP-Comanda-PHP-PrograIII/app/productos/csv', NULL, '2024-06-28 21:48:49'),
(126, 'admin1', '/TP-Comanda-PHP-PrograIII/app/productos', NULL, '2024-06-28 21:49:10'),
(127, 'admin1', '/TP-Comanda-PHP-PrograIII/app/productos/csv', NULL, '2024-06-28 21:49:29'),
(128, 'admin1', '/TP-Comanda-PHP-PrograIII/app/productos/csv', NULL, '2024-06-28 21:50:48'),
(129, 'admin1', '/TP-Comanda-PHP-PrograIII/app/productos/csv', NULL, '2024-06-28 21:50:56'),
(130, 'admin1', '/TP-Comanda-PHP-PrograIII/app/productos/importar', NULL, '2024-06-28 21:52:16'),
(131, 'admin1', '/TP-Comanda-PHP-PrograIII/app/productos/importar', NULL, '2024-06-28 21:55:49'),
(132, 'admin1', '/TP-Comanda-PHP-PrograIII/app/productos/importar', NULL, '2024-06-28 21:56:13'),
(133, 'admin1', '/TP-Comanda-PHP-PrograIII/app/productos/importar', NULL, '2024-06-28 21:56:55'),
(134, 'admin1', '/TP-Comanda-PHP-PrograIII/app/productos/importar', NULL, '2024-06-28 21:58:14'),
(135, 'admin1', '/TP-Comanda-PHP-PrograIII/app/productos/importar', NULL, '2024-06-28 21:58:53'),
(136, 'admin1', '/TP-Comanda-PHP-PrograIII/app/productos/importar', NULL, '2024-06-28 22:00:40'),
(137, 'admin1', '/TP-Comanda-PHP-PrograIII/app/productos/csv', NULL, '2024-06-28 22:00:45'),
(138, 'admin1', '/TP-Comanda-PHP-PrograIII/app/productos/actualizar-precio', '{\"codigo\":\"corona700\",\"precio\":3500}', '2024-06-28 22:02:02'),
(139, 'admin1', '/TP-Comanda-PHP-PrograIII/app/productos/borrar', '{\"codigo\":\"mojito\"}', '2024-06-28 22:02:34'),
(140, 'mozo1', '/TP-Comanda-PHP-PrograIII/app/pedidos/registrar', '{\"codigoMesa\":\"mesa1\",\"nombreCliente\":\"Pedro Rodriguez\",\"productos\":[{\"codigo\":\"milanesaCaballo1\",\"cantidad\":1},{\"codigo\":\"hamburguesaGarbanzo\",\"cantidad\":2},{\"codigo\":\"corona700\",\"cantidad\":1},{\"codigo\":\"daikiri1\",\"cantidad\":1}]}', '2024-06-28 22:07:49'),
(141, 'mozo1', '/TP-Comanda-PHP-PrograIII/app/pedidos/foto', '{\"codigoPedido\":\"fd271\"}', '2024-06-28 22:13:01'),
(142, 'mozo1', '/TP-Comanda-PHP-PrograIII/app/pedidos/foto?codigoPedido=fd271', '{\"codigoPedido\":\"fd271\"}', '2024-06-28 22:13:55'),
(143, 'mozo1', '/TP-Comanda-PHP-PrograIII/app/pedidos/foto?codigoPedido=fd271', '{\"codigoPedido\":\"fd271\"}', '2024-06-28 22:14:01'),
(144, 'mozo1', '/TP-Comanda-PHP-PrograIII/app/pedidos/obtener-pedidos-tomados-mozo', NULL, '2024-06-28 22:15:29'),
(145, 'mozo1', '/TP-Comanda-PHP-PrograIII/app/pedidos/obtener-pedidos-tomados-mozo', NULL, '2024-06-28 22:16:12'),
(146, 'mozo1', '/TP-Comanda-PHP-PrograIII/app/mesas/actualizar-estado', '{\"codigo\":\"mesa1\",\"estado\":\"Con cliente esperando pedido\"}', '2024-06-28 22:20:35'),
(147, 'mozo1', '/TP-Comanda-PHP-PrograIII/app/pedidos/obtener-pedidos-tomados-mozo', NULL, '2024-06-28 22:21:32'),
(148, 'admin1', '/TP-Comanda-PHP-PrograIII/app/pedidos/', NULL, '2024-06-28 22:23:27'),
(149, 'admin1', '/TP-Comanda-PHP-PrograIII/app/pedidos/', NULL, '2024-06-28 22:23:27'),
(150, 'admin1', '/TP-Comanda-PHP-PrograIII/app/pedidos/', NULL, '2024-06-28 22:24:47'),
(151, 'admin1', '/TP-Comanda-PHP-PrograIII/app/pedidos/', NULL, '2024-06-28 22:24:47'),
(152, 'admin1', '/TP-Comanda-PHP-PrograIII/app/pedidos/', NULL, '2024-06-28 22:27:14'),
(153, 'admin1', '/TP-Comanda-PHP-PrograIII/app/pedidos/', NULL, '2024-06-28 22:27:14'),
(154, 'cocinero1', '/TP-Comanda-PHP-PrograIII/app/pedidos/obtener-pendientes', NULL, '2024-06-28 22:29:22'),
(155, 'cocinero1', '/TP-Comanda-PHP-PrograIII/app/pedidos/tomar', '{\"codigoPedido\":\"fd271\",\"codigoProducto\":\"mojito\",\"tiempoEstimado\":8}', '2024-06-28 22:42:58'),
(156, 'cocinero1', '/TP-Comanda-PHP-PrograIII/app/pedidos/tomar', '{\"codigoPedido\":\"fd271\",\"codigoProducto\":\"mojito\",\"tiempoEstimado\":8}', '2024-06-28 22:43:29'),
(157, 'cocinero1', '/TP-Comanda-PHP-PrograIII/app/pedidos/tomar', '{\"codigoPedido\":\"fd271\",\"codigoProducto\":\"milanesaCaballo1\",\"tiempoEstimado\":60}', '2024-06-28 22:43:54'),
(158, 'cocinero1', '/TP-Comanda-PHP-PrograIII/app/pedidos/tomar', '{\"codigoPedido\":\"fd271\",\"codigoProducto\":\"hamburguesaGarbanzo\",\"tiempoEstimado\":20}', '2024-06-28 22:44:41'),
(159, 'cocinero2', '/TP-Comanda-PHP-PrograIII/app/pedidos/obtener-pendientes', NULL, '2024-06-28 22:46:27'),
(160, 'cocinero2', '/TP-Comanda-PHP-PrograIII/app/pedidos/tomar', '{\"codigoPedido\":\"fd271\",\"codigoProducto\":\"hamburguesaGarbanzo\",\"tiempoEstimado\":20}', '2024-06-28 22:46:42'),
(161, 'cervecero1', '/TP-Comanda-PHP-PrograIII/app/pedidos/obtener-pendientes', NULL, '2024-06-28 22:47:58'),
(162, 'cocinero2', '/TP-Comanda-PHP-PrograIII/app/pedidos/tomar', '{\"codigoPedido\":\"fd271\",\"codigoProducto\":\"hamburguesaGarbanzo\",\"tiempoEstimado\":20}', '2024-06-28 22:48:16'),
(163, 'cocinero2', '/TP-Comanda-PHP-PrograIII/app/pedidos/tomar', '{\"codigoPedido\":\"fd271\",\"codigoProducto\":\"hamburguesaGarbanzo\",\"tiempoEstimado\":20}', '2024-06-28 22:49:22'),
(164, 'cocinero2', '/TP-Comanda-PHP-PrograIII/app/pedidos/tomar', '{\"codigoPedido\":\"fd271\",\"codigoProducto\":\"hamburguesaGarbanzo\",\"tiempoEstimado\":20}', '2024-06-28 22:50:50'),
(165, 'cervecero1', '/TP-Comanda-PHP-PrograIII/app/pedidos/tomar', '{\"codigoPedido\":\"fd271\",\"codigoProducto\":\"hamburguesaGarbanzo\",\"tiempoEstimado\":20}', '2024-06-28 22:51:23'),
(166, 'cervecero1', '/TP-Comanda-PHP-PrograIII/app/pedidos/tomar', '{\"codigoPedido\":\"fd271\",\"codigoProducto\":\"hamburguesaGarbanzo\",\"tiempoEstimado\":20}', '2024-06-28 22:51:31'),
(167, 'cervecero1', '/TP-Comanda-PHP-PrograIII/app/pedidos/tomar', '{\"codigoPedido\":\"fd271\",\"codigoProducto\":\"hamburguesaGarbanzo\",\"tiempoEstimado\":20}', '2024-06-28 22:51:34'),
(168, 'cervecero1', '/TP-Comanda-PHP-PrograIII/app/pedidos/tomar', '{\"codigoPedido\":\"fd271\",\"codigoProducto\":\"hamburguesaGarbanzo\",\"tiempoEstimado\":20}', '2024-06-28 22:52:10'),
(169, 'cervecero1', '/TP-Comanda-PHP-PrograIII/app/pedidos/tomar', '{\"codigoPedido\":\"fd271\",\"codigoProducto\":\"corona700\",\"tiempoEstimado\":5}', '2024-06-28 22:52:18'),
(170, 'bartender1', '/TP-Comanda-PHP-PrograIII/app/pedidos/obtener-pendientes', NULL, '2024-06-28 22:53:27'),
(171, 'bartender1', '/TP-Comanda-PHP-PrograIII/app/pedidos/tomar', '{\"codigoPedido\":\"fd271\",\"codigoProducto\":\"corona700\",\"tiempoEstimado\":5}', '2024-06-28 22:53:39'),
(172, 'bartender1', '/TP-Comanda-PHP-PrograIII/app/pedidos/tomar', '{\"codigoPedido\":\"fd271\",\"codigoProducto\":\"daikiri1\",\"tiempoEstimado\":5}', '2024-06-28 22:53:44'),
(173, 'admin1', '/TP-Comanda-PHP-PrograIII/app/pedidos/', NULL, '2024-06-28 22:58:49'),
(174, 'admin1', '/TP-Comanda-PHP-PrograIII/app/pedidos/', NULL, '2024-06-28 22:58:49'),
(175, 'admin1', '/TP-Comanda-PHP-PrograIII/app/pedidos/', NULL, '2024-06-28 23:12:11'),
(176, 'admin1', '/TP-Comanda-PHP-PrograIII/app/pedidos/', NULL, '2024-06-28 23:12:11'),
(177, 'bartender1', '/TP-Comanda-PHP-PrograIII/app/pedidos/obtener-pendientes', NULL, '2024-06-28 23:17:06'),
(178, 'bartender1', '/TP-Comanda-PHP-PrograIII/app/pedidos/obtener-pendientes', NULL, '2024-06-28 23:17:38'),
(179, 'bartender1', '/TP-Comanda-PHP-PrograIII/app/pedidos/obtener-pendientes', NULL, '2024-06-28 23:18:08'),
(180, 'cocinero1', '/TP-Comanda-PHP-PrograIII/app/pedidos/obtener-pedidos-tomados-empleado', NULL, '2024-06-28 23:18:24'),
(181, 'cocinero2', '/TP-Comanda-PHP-PrograIII/app/pedidos/obtener-pedidos-tomados-empleado', NULL, '2024-06-28 23:18:32'),
(182, 'cocinero1', '/TP-Comanda-PHP-PrograIII/app/pedidos/obtener-pedidos-tomados-empleado', NULL, '2024-06-28 23:18:39'),
(183, 'cocinero1', '/TP-Comanda-PHP-PrograIII/app/pedidos/terminar', '{\"codigoPedido\":\"fd271\",\"codigoProducto\":\"corona700\"}', '2024-06-28 23:20:48'),
(184, 'cocinero1', '/TP-Comanda-PHP-PrograIII/app/pedidos/terminar', '{\"codigoPedido\":\"fd271\",\"codigoProducto\":\"milanesaCaballo1\"}', '2024-06-28 23:21:03'),
(185, 'cocinero1', '/TP-Comanda-PHP-PrograIII/app/pedidos/terminar', '{\"codigoPedido\":\"fd271\",\"codigoProducto\":\"hamburguesaGarbanzo\"}', '2024-06-28 23:21:38'),
(186, 'cocinero1', '/TP-Comanda-PHP-PrograIII/app/pedidos/terminar', '{\"codigoPedido\":\"fd271\",\"codigoProducto\":\"hamburguesaGarbanzo\"}', '2024-06-28 23:30:52'),
(187, 'cocinero2', '/TP-Comanda-PHP-PrograIII/app/pedidos/terminar', '{\"codigoPedido\":\"fd271\",\"codigoProducto\":\"hamburguesaGarbanzo\"}', '2024-06-28 23:31:08'),
(188, 'cervecero1', '/TP-Comanda-PHP-PrograIII/app/pedidos/obtener-pedidos-tomados-empleado', NULL, '2024-06-28 23:36:10'),
(189, 'bartender1', '/TP-Comanda-PHP-PrograIII/app/pedidos/obtener-pendientes', NULL, '2024-06-28 23:36:28'),
(190, 'cervecero1', '/TP-Comanda-PHP-PrograIII/app/pedidos/obtener-pedidos-tomados-empleado', NULL, '2024-06-28 23:38:16'),
(191, 'cervecero1', '/TP-Comanda-PHP-PrograIII/app/pedidos/obtener-pedidos-tomados-empleado', NULL, '2024-06-28 23:39:55'),
(192, 'cocinero2', '/TP-Comanda-PHP-PrograIII/app/pedidos/terminar', '{\"codigoPedido\":\"fd271\",\"codigoProducto\":\"corona700\"}', '2024-06-28 23:40:02'),
(193, 'cervecero1', '/TP-Comanda-PHP-PrograIII/app/pedidos/terminar', '{\"codigoPedido\":\"fd271\",\"codigoProducto\":\"corona700\"}', '2024-06-28 23:40:10'),
(194, 'bartender1', '/TP-Comanda-PHP-PrograIII/app/pedidos/obtener-pedidos-tomados-empleado', NULL, '2024-06-28 23:40:25'),
(195, 'bartender1', '/TP-Comanda-PHP-PrograIII/app/pedidos/terminar', '{\"codigoPedido\":\"fd271\",\"codigoProducto\":\"daikiri1\"}', '2024-06-28 23:40:36'),
(196, 'bartender1', '/TP-Comanda-PHP-PrograIII/app/pedidos/terminar', '{\"codigoPedido\":\"fd271\",\"codigoProducto\":\"daikiri1\"}', '2024-06-28 23:44:48'),
(197, 'mozo1', '/TP-Comanda-PHP-PrograIII/app/pedidos/obtener-pedidos-listos', NULL, '2024-06-28 23:47:04'),
(198, 'mozo1', '/TP-Comanda-PHP-PrograIII/app/mesas/actualizar-estado', '{\"codigo\":\"mesa1\",\"estado\":\"Con cliente comiendo\"}', '2024-06-28 23:48:25'),
(199, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/', NULL, '2024-06-28 23:49:30'),
(200, 'mozo1', '/TP-Comanda-PHP-PrograIII/app/pedidos/cobrar', '{\"codigoPedido\":\"fd271\"}', '2024-06-28 23:51:17'),
(201, 'mozo1', '/TP-Comanda-PHP-PrograIII/app/mesas/actualizar-estado', '{\"codigo\":\"mesa1\",\"estado\":\"Con cliente pagando\"}', '2024-06-28 23:55:53'),
(202, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/cerrar', '{\"codigo\":\"mesa1\"}', '2024-06-28 23:56:45'),
(203, 'admin1', '/TP-Comanda-PHP-PrograIII/app/rese%C3%B1as/top', NULL, '2024-06-29 00:09:04'),
(204, 'admin1', '/TP-Comanda-PHP-PrograIII/app/rese%C3%B1as/top', NULL, '2024-06-29 00:15:43'),
(205, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/mas-usada', NULL, '2024-06-29 00:17:20'),
(206, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/mas-usada', NULL, '2024-06-29 00:18:21'),
(207, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/mas-usada', NULL, '2024-06-29 00:18:43'),
(208, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/menos-usada', NULL, '2024-06-29 00:19:32'),
(209, 'admin1', '/TP-Comanda-PHP-PrograIII/app/productos/csv', NULL, '2024-06-29 00:21:46'),
(210, 'admin1', '/TP-Comanda-PHP-PrograIII/app/pedidos/demoras-productos', NULL, '2024-06-29 00:22:55'),
(211, 'admin1', '/TP-Comanda-PHP-PrograIII/app/pedidos/demoras-pedidos', NULL, '2024-06-29 00:23:10'),
(212, 'admin1', '/TP-Comanda-PHP-PrograIII/app/pedidos/demoras-pedidos', NULL, '2024-06-29 00:25:08'),
(213, 'admin1', '/TP-Comanda-PHP-PrograIII/app/pedidos/demoras-pedidos', NULL, '2024-06-29 00:25:19'),
(214, 'admin1', '/TP-Comanda-PHP-PrograIII/app/pedidos/demoras-pedidos', NULL, '2024-06-29 00:25:56'),
(215, 'admin1', '/TP-Comanda-PHP-PrograIII/app/pedidos/demoras-pedidos', NULL, '2024-06-29 00:27:22'),
(216, 'admin1', '/TP-Comanda-PHP-PrograIII/app/pedidos/demoras-pedidos', NULL, '2024-06-29 00:27:38'),
(217, 'admin1', '/TP-Comanda-PHP-PrograIII/app/pedidos/demoras-productos', NULL, '2024-06-29 00:28:45'),
(218, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/pdf', NULL, '2024-06-29 00:29:56'),
(219, 'admin1', '/TP-Comanda-PHP-PrograIII/app/pedidos/operaciones-por-sector-empleados', NULL, '2024-06-29 00:31:22'),
(220, 'admin1', '/TP-Comanda-PHP-PrograIII/app/pedidos/operaciones-por-sector', NULL, '2024-06-29 00:32:09'),
(221, 'admin1', '/TP-Comanda-PHP-PrograIII/app/pedidos/operaciones-por-empleados', NULL, '2024-06-29 00:34:23'),
(222, 'admin1', '/TP-Comanda-PHP-PrograIII/app/pedidos/productos-mas-vendidos', NULL, '2024-06-29 00:34:59'),
(223, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/logs-usuario?username=cocinero1', '{\"username\":\"cocinero1\"}', '2024-06-29 00:36:37'),
(224, 'admin1', '/TP-Comanda-PHP-PrograIII/app/usuarios/logs-usuario?username=cocinero1', '{\"username\":\"cocinero1\"}', '2024-06-29 00:36:43'),
(225, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/menor-importe', NULL, '2024-06-29 00:38:24'),
(226, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/ordenar-segun-importe', NULL, '2024-06-29 00:38:58'),
(227, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/ordenar-segun-importe-menor-mayor', NULL, '2024-06-29 00:41:52'),
(228, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/ordenar-segun-importe-menor-mayor', NULL, '2024-06-29 00:43:14'),
(229, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/ordenar-segun-importe-menor-mayor', NULL, '2024-06-29 00:43:31'),
(230, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/ordenar-segun-importe-menor-mayor', NULL, '2024-06-29 00:46:17'),
(231, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/facturacion-fechas?codigoMesa=mesa1&fechaDesde=2024-06-10&fechaHasta=2024-06-30', '{\"codigoMesa\":\"mesa1\",\"fechaDesde\":\"2024-06-10\",\"fechaHasta\":\"2024-06-30\"}', '2024-06-29 00:47:16'),
(232, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/facturacion-fechas?codigoMesa=mesa1&fechaDesde=2024-06-10&fechaHasta=2024-06-30', '{\"codigoMesa\":\"mesa1\",\"fechaDesde\":\"2024-06-10\",\"fechaHasta\":\"2024-06-30\"}', '2024-06-29 00:48:07'),
(233, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/facturacion-fechas?codigoMesa=mesa1&fechaDesde=2024-06-10&fechaHasta=2024-06-30', '{\"codigoMesa\":\"mesa1\",\"fechaDesde\":\"2024-06-10\",\"fechaHasta\":\"2024-06-30\"}', '2024-06-29 00:48:28'),
(234, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/facturacion-fechas?codigoMesa=mesa1&fechaDesde=2024-06-10&fechaHasta=2024-06-30', '{\"codigoMesa\":\"mesa1\",\"fechaDesde\":\"2024-06-10\",\"fechaHasta\":\"2024-06-30\"}', '2024-06-29 00:49:08'),
(235, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/facturacion-fechas?codigoMesa=mesa1&fechaDesde=2024-06-10&fechaHasta=2024-06-30', '{\"codigoMesa\":\"mesa1\",\"fechaDesde\":\"2024-06-10\",\"fechaHasta\":\"2024-06-30\"}', '2024-06-29 00:49:29'),
(236, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/facturacion-fechas?codigoMesa=mesa1&fechaDesde=2024-06-10&fechaHasta=2024-06-30', '{\"codigoMesa\":\"mesa1\",\"fechaDesde\":\"2024-06-10\",\"fechaHasta\":\"2024-06-30\"}', '2024-06-29 00:49:35'),
(237, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/facturacion-fechas?codigoMesa=mesa1&fechaDesde=2024-06-29&fechaHasta=2024-06-30', '{\"codigoMesa\":\"mesa1\",\"fechaDesde\":\"2024-06-29\",\"fechaHasta\":\"2024-06-30\"}', '2024-06-29 00:49:47'),
(238, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/facturacion-fechas?codigoMesa=mesa1&fechaDesde=2024-06-29&fechaHasta=2024-06-30', '{\"codigoMesa\":\"mesa1\",\"fechaDesde\":\"2024-06-29\",\"fechaHasta\":\"2024-06-30\"}', '2024-06-29 00:50:31'),
(239, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/facturacion-fechas?codigoMesa=mesa1&fechaDesde=2024-06-29&fechaHasta=2024-06-30', '{\"codigoMesa\":\"mesa1\",\"fechaDesde\":\"2024-06-29\",\"fechaHasta\":\"2024-06-30\"}', '2024-06-29 00:51:03'),
(240, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/facturacion-fechas?codigoMesa=mesa1&fechaDesde=2024-06-29&fechaHasta=2024-06-30', '{\"codigoMesa\":\"mesa1\",\"fechaDesde\":\"2024-06-29\",\"fechaHasta\":\"2024-06-30\"}', '2024-06-29 00:51:57'),
(241, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/facturacion-fechas?codigoMesa=mesa1&fechaDesde=2024-06-29&fechaHasta=2024-06-30', '{\"codigoMesa\":\"mesa1\",\"fechaDesde\":\"2024-06-29\",\"fechaHasta\":\"2024-06-30\"}', '2024-06-29 00:52:06'),
(242, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/facturacion-fechas?codigoMesa=mesa1&fechaDesde=2024-06-28&fechaHasta=2024-06-30', '{\"codigoMesa\":\"mesa1\",\"fechaDesde\":\"2024-06-28\",\"fechaHasta\":\"2024-06-30\"}', '2024-06-29 00:52:10'),
(243, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/facturacion-fechas?codigoMesa=mesa1&fechaDesde=2024-06-28&fechaHasta=2024-06-30', '{\"codigoMesa\":\"mesa1\",\"fechaDesde\":\"2024-06-28\",\"fechaHasta\":\"2024-06-30\"}', '2024-06-29 00:52:50'),
(244, 'admin1', '/TP-Comanda-PHP-PrograIII/app/mesas/facturacion-fechas?codigoMesa=mesa1&fechaDesde=2024-06-28&fechaHasta=2024-06-30', '{\"codigoMesa\":\"mesa1\",\"fechaDesde\":\"2024-06-28\",\"fechaHasta\":\"2024-06-30\"}', '2024-06-29 00:52:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logins`
--

CREATE TABLE `logins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `sector` varchar(50) NOT NULL,
  `fecha_ingreso` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `logins`
--

INSERT INTO `logins` (`id`, `username`, `sector`, `fecha_ingreso`) VALUES
(14, 'admin1', 'ADMIN', '2024-06-28 19:10:12'),
(15, 'admin2', 'ADMIN', '2024-06-28 19:11:28'),
(16, 'admin1', 'ADMIN', '2024-06-28 19:11:32'),
(17, 'admin1', 'ADMIN', '2024-06-28 19:30:02'),
(18, 'cocinero1', 'BARTENDER', '2024-06-28 19:32:19'),
(19, 'admin1', 'ADMIN', '2024-06-28 19:32:29'),
(20, 'cocinero1', 'BARTENDER', '2024-06-28 19:33:03'),
(21, 'admin1', 'ADMIN', '2024-06-28 19:33:06'),
(22, 'admin1', 'ADMIN', '2024-06-28 19:38:27'),
(23, 'cocinero1', 'COCINERO', '2024-06-28 19:42:20'),
(24, 'admin1', 'ADMIN', '2024-06-28 19:42:31'),
(25, 'mozo1', 'MOZO', '2024-06-28 21:27:47'),
(26, 'admin1', 'ADMIN', '2024-06-28 21:28:14'),
(27, 'admin1', 'ADMIN', '2024-06-28 21:29:31'),
(28, 'admin1', 'ADMIN', '2024-06-28 21:30:33'),
(29, 'mozo1', 'MOZO', '2024-06-28 22:07:15'),
(30, 'admin1', 'ADMIN', '2024-06-28 22:26:54'),
(31, 'cocinero1', 'COCINERO', '2024-06-28 22:28:04'),
(32, 'cocinero2', 'COCINERO', '2024-06-28 22:46:05'),
(33, 'cervecero1', 'CERVECERO', '2024-06-28 22:47:34'),
(34, 'bartender1', 'BARTENDER', '2024-06-28 22:52:57'),
(35, 'cocinero1', 'COCINERO', '2024-06-28 23:16:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `is_deleted` float NOT NULL,
  `codigo` varchar(5) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `fecha_creacion` date NOT NULL,
  `fecha_modificacion` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `is_deleted`, `codigo`, `estado`, `fecha_creacion`, `fecha_modificacion`) VALUES
(11, 0, 'mesa1', 'Cerrada', '2024-06-28', '2024-06-28'),
(12, 0, 'mesa2', 'Cerrada', '2024-06-28', '2024-06-28'),
(13, 0, 'mesa3', 'Cerrada', '2024-06-28', '2024-06-28'),
(14, 0, 'mesa4', 'Cerrada', '2024-06-28', '2024-06-28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(50) NOT NULL,
  `vigente` tinyint(1) NOT NULL,
  `id_mesa` int(50) NOT NULL,
  `id_mozo` int(11) NOT NULL,
  `codigo` varchar(5) NOT NULL,
  `nombre_cliente` varchar(50) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `fecha_modificacion` datetime NOT NULL,
  `foto_mesa` varchar(500) DEFAULT NULL,
  `importe_total` double DEFAULT NULL,
  `tiempo_total_estimado` int(100) DEFAULT NULL,
  `fecha_finalizacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `vigente`, `id_mesa`, `id_mozo`, `codigo`, `nombre_cliente`, `fecha_creacion`, `fecha_modificacion`, `foto_mesa`, `importe_total`, `tiempo_total_estimado`, `fecha_finalizacion`) VALUES
(2, 0, 11, 29, 'fd271', 'Pedro Rodriguez', '2024-06-28 22:43:00', '2024-06-28 23:56:45', './img/fd271.jpg', 37500, 60, '2024-06-28 23:45:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos_productos`
--

CREATE TABLE `pedidos_productos` (
  `id` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `estado` varchar(50) NOT NULL,
  `tiempo_estimado` int(50) DEFAULT NULL,
  `hora_inicio` datetime DEFAULT NULL,
  `hora_fin` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos_productos`
--

INSERT INTO `pedidos_productos` (`id`, `id_pedido`, `id_producto`, `id_usuario`, `estado`, `tiempo_estimado`, `hora_inicio`, `hora_fin`) VALUES
(4, 2, 10, 39, 'Listo para servir', 60, '2024-06-28 22:43:54', '2024-06-28 23:21:03'),
(5, 2, 11, 39, 'Listo para servir', 20, '2024-06-28 22:44:41', '2024-06-28 23:21:38'),
(6, 2, 11, 40, 'Listo para servir', 20, '2024-06-28 22:46:42', '2024-06-28 23:31:08'),
(7, 2, 12, 33, 'Listo para servir', 5, '2024-06-28 22:52:18', '2024-06-28 23:40:10'),
(8, 2, 13, 36, 'Listo para servir', 5, '2024-06-28 22:53:44', '2024-06-28 23:40:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(50) NOT NULL,
  `is_deleted` float NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `precio` double NOT NULL,
  `fecha_creacion` date NOT NULL,
  `fecha_modificacion` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `is_deleted`, `tipo`, `codigo`, `nombre`, `precio`, `fecha_creacion`, `fecha_modificacion`) VALUES
(10, 0, 'COMIDA', 'milanesaCaballo1', 'Milanesa a caballo', 15000, '2024-06-28', '2024-06-28'),
(11, 0, 'COMIDA', 'hamburguesaGarbanzo', 'Hamburguesa de Garbanzo', 7000, '2024-06-28', '2024-06-28'),
(12, 0, 'CERVEZA', 'corona700', 'Corona 700 ml', 3500, '2024-06-28', '2024-06-28'),
(13, 0, 'TRAGO', 'daikiri1', 'Daikiri', 5000, '2024-06-28', '2024-06-28'),
(14, 0, 'COMIDA', 'pizzaPepperoni', 'Pizza de Pepperoni', 16000, '2024-06-28', '2024-06-28'),
(15, 0, 'CERVEZA', 'andesIPA', 'Andes IPA 1 lt', 6500, '2024-06-28', '2024-06-28'),
(16, 0, 'TRAGO', 'campari', 'Campari', 5555, '2024-06-28', '2024-06-28'),
(18, 0, 'TRAGO', 'mojito', 'Mojito', 6660, '2024-06-28', '2024-06-28'),
(19, 0, 'TRAGO', 'gintonic', 'Gin Tonic', 4444, '2024-06-28', '2024-06-28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reseñas`
--

CREATE TABLE `reseñas` (
  `id` int(11) NOT NULL,
  `id_mesa` int(11) NOT NULL,
  `codigo_pedido` varchar(5) NOT NULL,
  `puntuacion_mesa` int(11) NOT NULL,
  `id_mozo` int(11) NOT NULL,
  `puntuacion_mozo` int(11) NOT NULL,
  `id_cocinero` varchar(50) NOT NULL,
  `puntuacion_cocinero` int(11) NOT NULL,
  `puntuacion_restaurante` int(11) NOT NULL,
  `experiencia` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reseñas`
--

INSERT INTO `reseñas` (`id`, `id_mesa`, `codigo_pedido`, `puntuacion_mesa`, `id_mozo`, `puntuacion_mozo`, `id_cocinero`, `puntuacion_cocinero`, `puntuacion_restaurante`, `experiencia`) VALUES
(2, 11, 'fd271', 9, 29, 8, '39,39,40', 6, 8, 'Me gusto mucho'),
(3, 11, 'fd271', 10, 29, 8, '39,40', 6, 8, 'Me gusto mucho'),
(4, 11, 'fd271', 10, 29, 4, '39,40', 6, 6, 'Me gusto bastante');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `username` varchar(50) NOT NULL,
  `pass` varchar(250) NOT NULL,
  `sector` varchar(50) NOT NULL,
  `fecha_creacion` date NOT NULL,
  `fecha_modificacion` date NOT NULL,
  `activo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `is_deleted`, `username`, `pass`, `sector`, `fecha_creacion`, `fecha_modificacion`, `activo`) VALUES
(25, 0, 'admin1', '$2y$10$PvZ55WWPU30zhuiKaLv1Kue.gtw3SGLoMYuAOqwPZ67WSb3AtH7AO', 'ADMIN', '2024-06-28', '2024-06-28', 1),
(26, 0, 'admin2', '$2y$10$plULbE6U/Y65x5eUY3GI4uT3/YT.AsTI7uIp5JGHrfMdbEF8TQu3G', 'ADMIN', '2024-06-28', '2024-06-28', 1),
(27, 0, 'admin3', '$2y$10$uv2mniA68PWY8VCO026RzOiac5sv4swADl2XvE9gqX2784PXsBXB2', 'ADMIN', '2024-06-28', '2024-06-28', 1),
(28, 0, 'admin4', '$2y$10$kCX7iuWOlOJ8kk6P97aiEu6c52ucnR/wfW.U342S3ch5GEL8//Coe', 'ADMIN', '2024-06-28', '2024-06-28', 1),
(29, 0, 'mozo1', '$2y$10$Ui07Bd8xRf04iwN5gKnybOSNus.ayFwYhazVKqH3iLtVMHC.iAU.S', 'MOZO', '2024-06-28', '2024-06-28', 1),
(30, 0, 'mozo2', '$2y$10$1IXypuEJ0KnL9KmWt.ZrKuZELzNBJWHCRre9CRLnDm/3hh5w/ElGW', 'MOZO', '2024-06-28', '2024-06-28', 1),
(31, 0, 'mozo3', '$2y$10$IHjyfahO5mTvhKh9zY/HWOXWp5cSEYsYyOy0iIpnHw6xuDIkU2Kg2', 'MOZO', '2024-06-28', '2024-06-28', 1),
(32, 0, 'mozo4', '$2y$10$YzXa9TfqVfiBZKI3bNQ6.uuGVeWuhMANZtWQAsktlabKBUVfduEni', 'MOZO', '2024-06-28', '2024-06-28', 1),
(33, 0, 'cervecero1', '$2y$10$8K5x0q9Qkpi6iYFOBknS2.GyZ5XlcKrpeKvPGLVvwPtfPmEhWYmcW', 'CERVECERO', '2024-06-28', '2024-06-28', 1),
(34, 0, 'cervecero2', '$2y$10$ZG0ycWWMTpDRkVGb9l0lfed5pO1Fzzi2Cf/7.wpocweRjr/zjW39e', 'CERVECERO', '2024-06-28', '2024-06-28', 1),
(35, 0, 'cervecero3', '$2y$10$6qlggnIGPn4a3nxJ982ZjOekPz46f3wed.edqZnYBFdNHyj6/J8aW', 'CERVECERO', '2024-06-28', '2024-06-28', 1),
(36, 0, 'bartender1', '$2y$10$YfKD9Bcg4OQpxdzCbTc1zexoQzAPNIXbNhVebAe2E0uklBsVS82fW', 'BARTENDER', '2024-06-28', '2024-06-28', 1),
(37, 0, 'bartender2', '$2y$10$CHR.xWtUkHn8iIR35SY7OOT2zFtXolFOLceLIEAAMJ7ujUsFHHyp.', 'BARTENDER', '2024-06-28', '2024-06-28', 1),
(38, 0, 'bartender3', '$2y$10$1bwm6qDvSlcezmzO8DBcPuRWYKzR9Zxh6w8TnI88x8kqfLkkXNF8O', 'BARTENDER', '2024-06-28', '2024-06-28', 1),
(39, 0, 'cocinero1', '$2y$10$VQe7cJ4EUokvdVvyak6LrOkRtE430tzad.55GglWHcqs/OAEAevkm', 'COCINERO', '2024-06-28', '2024-06-28', 1),
(40, 0, 'cocinero2', '$2y$10$1lIpCUjfl20KVUmFRdqQuO0TDhZ/E2RPua4urgmhaWeq74PpzOffC', 'COCINERO', '2024-06-28', '2024-06-28', 1),
(41, 0, 'cocinero3', '$2y$10$VL.wvoVCiVnEJuJWx3Hg6eNDEeNTZFoIuTkG6afPj.jTdXJgGXMsO', 'COCINERO', '2024-06-28', '2024-06-28', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `logins`
--
ALTER TABLE `logins`
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
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_mesa` (`id_mesa`),
  ADD KEY `id_mozo` (`id_mozo`);

--
-- Indices de la tabla `pedidos_productos`
--
ALTER TABLE `pedidos_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `pedidos_productos_ibfk_2` (`id_producto`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reseñas`
--
ALTER TABLE `reseñas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_mesa` (`id_mesa`),
  ADD KEY `id_mozo` (`id_mozo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=245;

--
-- AUTO_INCREMENT de la tabla `logins`
--
ALTER TABLE `logins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pedidos_productos`
--
ALTER TABLE `pedidos_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `reseñas`
--
ALTER TABLE `reseñas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_mesa`) REFERENCES `mesas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`id_mozo`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedidos_productos`
--
ALTER TABLE `pedidos_productos`
  ADD CONSTRAINT `pedidos_productos_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pedidos_productos_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pedidos_productos_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reseñas`
--
ALTER TABLE `reseñas`
  ADD CONSTRAINT `reseñas_ibfk_1` FOREIGN KEY (`id_mesa`) REFERENCES `mesas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reseñas_ibfk_2` FOREIGN KEY (`id_mozo`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
