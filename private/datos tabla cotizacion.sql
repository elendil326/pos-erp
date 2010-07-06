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
