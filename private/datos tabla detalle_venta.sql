-- phpMyAdmin SQL Dump
-- version 2.10.1
-- http://www.phpmyadmin.net
-- 
-- Servidor: localhost
-- Tiempo de generación: 05-07-2010 a las 19:57:19
-- Versión del servidor: 5.0.45
-- Versión de PHP: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Base de datos: `pos`
-- 

-- 
-- Volcar la base de datos para la tabla `detalle_venta`
-- 

INSERT INTO `detalle_venta` (`id_venta`, `id_producto`, `cantidad`, `precio`) VALUES 
(1, 4, 1, 10),
(2, 4, 1, 10),
(3, 6, 1, 9),
(4, 8, 1, 8),
(5, 4, 1, 9.5),
(5, 6, 1, 8.5),
(5, 8, 1, 8),
(6, 4, 2, 9.5),
(6, 6, 2, 8.5),
(6, 8, 3, 8),
(7, 8, 2, 8);
