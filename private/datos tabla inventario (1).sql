-- phpMyAdmin SQL Dump
-- version 2.10.1
-- http://www.phpmyadmin.net
-- 
-- Servidor: localhost
-- Tiempo de generación: 05-07-2010 a las 19:57:31
-- Versión del servidor: 5.0.45
-- Versión de PHP: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Base de datos: `pos`
-- 

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `inventario`
-- 

CREATE TABLE `inventario` (
  `id_producto` int(11) NOT NULL auto_increment COMMENT 'id del producto',
  `nombre` varchar(90) collate utf8_unicode_ci NOT NULL COMMENT 'Descripcion o nombre del producto',
  `denominacion` varchar(30) collate utf8_unicode_ci NOT NULL COMMENT 'es lo que se le mostrara a los clientes',
  PRIMARY KEY  (`id_producto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

-- 
-- Volcar la base de datos para la tabla `inventario`
-- 

INSERT INTO `inventario` (`id_producto`, `nombre`, `denominacion`) VALUES 
(4, '1as', 'Papa Grande'),
(5, '2as', 'Papa Mediana'),
(6, '3as', 'Papa Chica'),
(7, '4as', 'Papa Morada'),
(8, 'Mixtas', 'Papa Surtida'),
(9, 'Roñas', 'Papa baja');

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
