-- phpMyAdmin SQL Dump
-- version 2.10.1
-- http://www.phpmyadmin.net
-- 
-- Servidor: localhost
-- Tiempo de generación: 05-07-2010 a las 19:57:05
-- Versión del servidor: 5.0.45
-- Versión de PHP: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Base de datos: `pos`
-- 

-- 
-- Volcar la base de datos para la tabla `detalle_inventario`
-- 

INSERT INTO `detalle_inventario` (`id_producto`, `id_sucursal`, `precio_venta`, `min`, `existencias`) VALUES 
(4, 1, 9.5, 1000, 1000),
(4, 2, 10, 200, 1000),
(5, 1, 9, 1000, 1000),
(5, 2, 9.5, 200, 1000),
(6, 1, 8.5, 1000, 1000),
(6, 2, 9, 100, 1000),
(7, 1, 8.5, 100, 1000),
(7, 2, 8.9, 100, 1000),
(8, 1, 8, 50, 1000),
(8, 2, 8, 50, 1000);
