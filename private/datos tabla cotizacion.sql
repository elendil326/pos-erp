-- phpMyAdmin SQL Dump
-- version 2.10.1
-- http://www.phpmyadmin.net
-- 
-- Servidor: localhost
-- Tiempo de generación: 05-07-2010 a las 19:55:44
-- Versión del servidor: 5.0.45
-- Versión de PHP: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Base de datos: `pos`
-- 

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `cotizacion`
-- 

CREATE TABLE `cotizacion` (
  `id_cotizacion` int(11) NOT NULL auto_increment COMMENT 'id de la cotizacion',
  `id_cliente` int(11) NOT NULL COMMENT 'id del cliente',
  `fecha` date NOT NULL COMMENT 'fecha de cotizacion',
  `subtotal` float NOT NULL COMMENT 'subtotal de la cotizacion',
  `iva` float NOT NULL COMMENT 'iva sobre el subtotal',
  PRIMARY KEY  (`id_cotizacion`),
  KEY `cotizacion_cliente` (`id_cliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Volcar la base de datos para la tabla `cotizacion`
-- 

INSERT INTO `cotizacion` (`id_cotizacion`, `id_cliente`, `fecha`, `subtotal`, `iva`) VALUES 
(2, 34, '2010-06-18', 36.5, 5.84);

-- 
-- Procedimientos
-- 
DELIMITER $$
-- 
CREATE DEFINER=`root`@`localhost` PROCEDURE `mi_proc`(venta INT)
SET @id_venta = venta$$

-- 
DELIMITER ;
-- 
