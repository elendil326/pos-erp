<?php 



# *******************************
# LOGS
# *******************************
$POS_CONFIG["LOG_TO_FILE"]      = true;
$POS_CONFIG["LOG_ACCESS_FILE"] 	= "/var/log/mx.caffeina.pos/access.log";
$POS_CONFIG["LOG_ERROR_FILE"] 	= "/var/log/mx.caffeina.pos/error.log";
$POS_CONFIG["LOG_TRACKBACK"] 	= false;
$POS_CONFIG["LOG_DB_QUERYS"] 	= true;





# *******************************
# FACTURACION
# *******************************





# *******************************
# ZONA HORARIA
# *******************************
$POS_CONFIG["ZONA_HORARIA"] 	= "America/Mexico_City";




# ******************************
# BASE DE DATOS 
# ******************************
$POS_CONFIG["CORE_DB_USER"] 	= "root";
$POS_CONFIG["CORE_DB_PASSWORD"] = "";
$POS_CONFIG["CORE_DB_NAME"] 	= "pos";
$POS_CONFIG["CORE_DB_DRIVER"] 	= "mysqlt";
$POS_CONFIG["CORE_DB_HOST"] 	= "localhost";
$POS_CONFIG["CORE_DB_DEBUG"] 	= false;


# *******************************
# Seguridad
# *******************************




# *******************************
# Correo Electronico
# *******************************
$POS_CONFIG["MAIL"] 			= false;
$POS_CONFIG["MAIL_FROM"] 		= "no-reply@caffeina.mx";
$POS_CONFIG["MAIL_HOST"]		= "mail.caffeina.mx";
$POS_CONFIG["MAIL_USERNAME"]	= "no-reply@caffeina.mx";
$POS_CONFIG["MAIL_PASSWORD"]	= "";
$POS_CONFIG["MAIL_PORT"]		= "26";
$POS_CONFIG["MAIL_METHOD"]		= "SMTP";




# *******************************
# phpunit
# *******************************
$POS_CONFIG["PHPUNIT_INSTANCE_TO_TEST"]		= "71";


$POS_CONFIG["GOOGLE_ANALYTICS_ID"]		= null;

