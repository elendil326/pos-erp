-- phpMyAdmin SQL Dump
-- version 2.10.1
-- http://www.phpmyadmin.net
-- 
-- Servidor: localhost
-- Tiempo de generación: 05-07-2010 a las 19:57:43
-- Versión del servidor: 5.0.45
-- Versión de PHP: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Base de datos: `pos`
-- 

-- 
-- Volcar la base de datos para la tabla `pagos_venta`
-- 

INSERT INTO `pagos_venta` (`id_pago`, `id_venta`, `fecha`, `monto`) VALUES 
(1, 5, '2010-07-02 04:02:23', 10),
(2, 5, '2010-07-02 04:02:23', 6),
(3, 6, '2010-07-02 15:11:40', 30),
(4, 7, '2010-07-02 18:58:29', 14),
(5, 7, '2010-07-02 18:58:29', 2);
