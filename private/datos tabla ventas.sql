-- phpMyAdmin SQL Dump
-- version 2.10.1
-- http://www.phpmyadmin.net
-- 
-- Servidor: localhost
-- Tiempo de generación: 05-07-2010 a las 19:58:34
-- Versión del servidor: 5.0.45
-- Versión de PHP: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Base de datos: `pos`
-- 

-- 
-- Volcar la base de datos para la tabla `ventas`
-- 

INSERT INTO `ventas` (`id_venta`, `id_cliente`, `tipo_venta`, `fecha`, `subtotal`, `iva`, `sucursal`, `id_usuario`) VALUES 
(1, 24, 2, '2010-07-01 00:37:35', 10, 0, 2, 1),
(2, 25, 2, '2010-07-01 00:40:17', 10, 0, 1, 1),
(3, 24, 1, '2010-07-01 01:18:22', 9, 0, 2, 1),
(4, 24, 1, '2010-07-01 01:20:12', 8, 0, 2, 1),
(5, 47, 2, '2010-07-02 03:58:58', 26, 0, 1, 1),
(6, 47, 2, '2010-07-02 15:09:33', 50, 0, 3, 1),
(7, 47, 2, '2010-07-02 18:57:16', 16, 0, 2, 1);
