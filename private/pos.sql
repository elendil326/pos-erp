-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 07, 2012 at 08:30 PM
-- Server version: 5.1.44
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `instances`
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
  `fecha_creacion` int(11) NOT NULL COMMENT 'fecha de creacion de esta instancia',
  PRIMARY KEY (`instance_id`),
  KEY `instance_token` (`instance_token`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=80 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `last_seen` datetime NOT NULL,
  PRIMARY KEY (`id_user`),
  KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;



CREATE TABLE IF NOT EXISTS `instance_request` (
  `id_request` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(32) NOT NULL,
  `fecha` int(11) NOT NULL,
  `ip` varchar(19) NOT NULL,
  `token` varchar(32) NOT NULL,
  `date_validated` int(11) DEFAULT NULL,
  `date_installed` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_request`),
  KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;
