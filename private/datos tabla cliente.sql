-- phpMyAdmin SQL Dump
-- version 2.10.1
-- http://www.phpmyadmin.net
-- 
-- Servidor: localhost
-- Tiempo de generación: 05-07-2010 a las 19:55:15
-- Versión del servidor: 5.0.45
-- Versión de PHP: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Base de datos: `pos`
-- 

-- 
-- Volcar la base de datos para la tabla `cliente`
-- 

INSERT INTO `cliente` (`id_cliente`, `rfc`, `nombre`, `direccion`, `telefono`, `e_mail`, `limite_credito`) VALUES 
(24, 'JMHR220486', 'JUAN MANUEL HERNANDEZ REYES', 'CALLEJON SANTO NINO No. 8', '4151858028', 'figu@gmail.com', 10000),
(25, 'GACJUM85', 'Juan Manuel Garcia Carmona', 'Diego Rivera', '4614234', 'mane@name.com', 900),
(28, 'GJHER140203', 'GUADALUPE DE JESUS HERNANDEZ REYES', 'Callejon Santo nino #8', '4151858028', 'chucho@chucho.com', 10000),
(36, 'RFCMIGUEL', 'Don Mike Azul', 'Direccion de Miguel', '46134873', 'donmike_azul@hotmail.com', 4000),
(37, 'LUM86393MD', 'Luis Michel Morales', 'la misma que rene', '4690834', 'luis@mail.com', 5000),
(38, 'RFCRENE', 'CARLOS RENE MICHEL MORALES', 'Direccion de rene', '', '', 10000),
(40, 'ALGONI345436', 'Alejandro Gomez Nicasio', 'Direccion de nick', '4129833', 'corre de nick', 600),
(45, 'RFCDIEGOVEN', 'Diego Ventura', 'Arboledas', '6713456', 'dventuras11@hotmail.com', 2500),
(47, 'RFCALANBOY', 'Alan Gonzalez Hernandez', 'direcion de alan boy', '467865', 'alan.gohe@alanboy.com', 40000),
(48, 'zohanrfc', 'Zohan Villaverde', 'Hidalgo #456', '', 'emailzohan@kll.com', 5000),
(49, 'BEL037842JP', 'BERNARDO LOPEZ', 'Direccion de Bernarado', '43237832', 'brr@fjfd.com', 50000),
(50, 'ZULTRERGHDF', 'ZULEMA TICOMAN', '', '', '', 1000),
(51, 'CUABLCO/58748', 'Cuau Blanco Bravo', '', '', '', 5000),
(52, 'RMONA54344', 'Ramon Ayala', '', '', '', 1000),
(53, 'RILOPZ34543', 'Ricardo Lopez ', 'Insurgentes #49', '43839', 'email@ricardo.com', 7500);
