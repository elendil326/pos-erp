-- phpMyAdmin SQL Dump
-- version 3.3.7deb5build0.10.10.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 26-07-2011 a las 13:13:14
-- Versión del servidor: 5.1.49
-- Versión de PHP: 5.3.3-1ubuntu9.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `pos_core`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `core_functionality`
--

CREATE TABLE IF NOT EXISTS `core_functionality` (
  `instance_id` int(11) NOT NULL,
  `multi_sucursal` tinyint(1) NOT NULL COMMENT 'Verdadero cuando se tienen mas de una sucursal',
  `compra_a_clientes` tinyint(1) NOT NULL COMMENT 'Verdadero cuando se desea comprar a los clientes',
  `POS_MODULO_CONTABILIDAD` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`instance_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instances`
--

CREATE TABLE IF NOT EXISTS `instances` (
  `instance_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'the id for this instance',
  `desc` varchar(256) NOT NULL,
  `MAX_LIMITE_DE_CREDITO` float NOT NULL,
  `MAX_LIMITE_DESCUENTO` float NOT NULL,
  `PERIODICIDAD_SALARIO` varchar(32) NOT NULL,
  `ENABLE_GMAPS` tinyint(1) NOT NULL,
  `DATE_FORMAT` varchar(32) NOT NULL,
  `DB_USER` varchar(32) NOT NULL,
  `DB_PASSWORD` varchar(32) NOT NULL,
  `DB_NAME` varchar(32) NOT NULL,
  `DB_DRIVER` varchar(32) NOT NULL,
  `DB_HOST` varchar(32) NOT NULL,
  `DB_DEBUG` tinyint(1) NOT NULL,
  `HEARTBEAT_METHOD_TRIGGER` tinyint(1) NOT NULL DEFAULT '0',
  `HEARTBEAT_INTERVAL` varchar(32) NOT NULL,
  `POS_SUCURSAL_TEST_TOKEN` varchar(32) NOT NULL,
  `DEMO` tinyint(1) NOT NULL,
  PRIMARY KEY (`instance_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personalization`
--

CREATE TABLE IF NOT EXISTS `personalization` (
  `instance_id` int(11) NOT NULL,
  `mod_clientes_banner` varchar(128) DEFAULT NULL,
  `mod_sucursales_banner` varchar(128) DEFAULT NULL,
  `mod_ventas_banner` varchar(128) DEFAULT NULL,
  `mod_autorizaciones_banner` varchar(128) DEFAULT NULL,
  `mod_contabilidad_banner` varchar(128) DEFAULT NULL,
  `mod_proveedores_banner` varchar(128) DEFAULT NULL,
  `mod_inventario_banner` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`instance_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `jedi_id` int(11) NOT NULL,
  `nickname` varchar(64) NOT NULL,
  `pass` varchar(64) NOT NULL,
  `last_access` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
