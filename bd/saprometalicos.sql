-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 06-06-2023 a las 21:19:43
-- Versión del servidor: 8.0.31
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `saprometalicos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamento`
--

DROP TABLE IF EXISTS `departamento`;
CREATE TABLE IF NOT EXISTS `departamento` (
  `pk_dep` int NOT NULL AUTO_INCREMENT,
  `nom_dep` varchar(40) NOT NULL,
  PRIMARY KEY (`pk_dep`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `departamento`
--

INSERT INTO `departamento` (`pk_dep`, `nom_dep`) VALUES
(1, 'ADMINISTRACION'),
(2, 'COMERCIAL'),
(3, 'COMPRAS'),
(4, 'CONTABILIDAD'),
(5, 'FINANCIERA'),
(6, 'GERENCIA COMERCIAL'),
(7, 'GERENCIA FIN Y OPE'),
(8, 'METROLOGIA'),
(9, 'PRODUCCION'),
(10, 'PROYECTOS-INGENIERIA'),
(11, 'SERVICIOS'),
(12, 'SOFTWARE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `list_arse`
--

DROP TABLE IF EXISTS `list_arse`;
CREATE TABLE IF NOT EXISTS `list_arse` (
  `pk_list` int NOT NULL AUTO_INCREMENT,
  `fk_num_sol` int NOT NULL,
  `codigo_articulo` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nom_arse` varchar(200) NOT NULL,
  `proveedor` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `fecha_nec` date NOT NULL,
  `cant_nec` int DEFAULT NULL,
  `precio_info` float DEFAULT NULL,
  `cuenta_mayor` varchar(50) NOT NULL,
  `uen` varchar(40) NOT NULL,
  `linea` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `sublinea` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `proyecto` varchar(40) NOT NULL,
  `por_desc` float NOT NULL,
  `ind_imp` varchar(40) NOT NULL,
  `total_ml` float NOT NULL,
  PRIMARY KEY (`pk_list`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_compra`
--

DROP TABLE IF EXISTS `solicitud_compra`;
CREATE TABLE IF NOT EXISTS `solicitud_compra` (
  `pk_num_sol` int NOT NULL,
  `numSAP` varchar(20) DEFAULT NULL,
  `docEntry` int DEFAULT NULL,
  `estado_sol` varchar(40) NOT NULL,
  `nom_solicitante` varchar(40) NOT NULL,
  `sucursal` varchar(40) NOT NULL,
  `correo_sol` varchar(40) NOT NULL,
  `propietario` varchar(40) DEFAULT NULL,
  `comentarios` varchar(200) DEFAULT NULL,
  `fk_cod_usr` varchar(40) NOT NULL,
  `depart_sol` varchar(40) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `cantidad` int NOT NULL,
  `fecha_documento` date NOT NULL,
  `fecha_necesaria` date NOT NULL,
  PRIMARY KEY (`pk_num_sol`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `pk_cod_usr` varchar(40) NOT NULL,
  `nom_usr` varchar(50) NOT NULL,
  `rol_usr` varchar(50) NOT NULL,
  `sucursal` varchar(40) NOT NULL,
  `fk_depart` int NOT NULL,
  `tipo_usuario` int NOT NULL,
  `pass_usr` varchar(200) NOT NULL,
  PRIMARY KEY (`pk_cod_usr`),
  KEY `fk_depart` (`fk_depart`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`pk_cod_usr`, `nom_usr`, `rol_usr`, `sucursal`, `fk_depart`, `tipo_usuario`, `pass_usr`) VALUES
('admin', 'admin', 'admin', 'admin', 1, 3, '$2y$07$ci00ykkJdV/8zUuTkOqJs.vh7EgJ3xUwl8yWFoXg4MCx5UPMqjfo2'),
('usuario', 'usuario', 'usuario', 'principal', 2, 1, '$2y$07$WmgSwAR3ZG5n8LQiUDL9kOAPc2rnM74fjGgjZ.BZO2lZ2Yt7e1ZR6');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`fk_depart`) REFERENCES `departamento` (`pk_dep`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
