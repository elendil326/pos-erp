-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 04-10-2011 a las 16:52:01
-- Versión del servidor: 5.1.44
-- Versión de PHP: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de datos: `pos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instances`
--

CREATE TABLE IF NOT EXISTS `instances` (
  `instance_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'the id for this instance',
  `instance_token` varchar(64) NOT NULL,
  `descripcion` varchar(256) NOT NULL,
  `db_user` varchar(32) NOT NULL,
  `db_password` varchar(32) NOT NULL,
  `db_name` varchar(32) NOT NULL,
  `db_driver` varchar(32) NOT NULL,
  `db_host` varchar(32) NOT NULL,
  `db_debug` tinyint(1) NOT NULL,
  PRIMARY KEY (`instance_id`),
  KEY `instance_token` (`instance_token`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `last_seen` datetime NOT NULL,
  PRIMARY KEY (`id_user`),
  KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;