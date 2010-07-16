<?php

require_once('../../../server/mx.caffeina.logger/logger.php');

$logger->log('Mensaje sin nivel de prioridad');
$logger->log('Este es otro mensaje. Y es critico', PEAR_LOG_CRIT);
$logger->setIdent("Módulo de ventas");
$logger->log('Este es un mensaje desde un módulo especifico');
$logger->log('Mensaje desde un módulo y con priodidad', PEAR_LOG_ERR);
