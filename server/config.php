<?php 

// Modificado el Wed, 22 Jun 2011 13:51:03 -0500 desde 189.234.180.141


 


# *******************************
# Configuracion Basica
# *******************************
#carpeta donde se encuentran los scripts del servidor,
#define( "POS_PATH_TO_SERVER_ROOT", "/opt/pos/branches/papassupremas/server");

#
define("POS_FACTURACION_PRODUCCION", true);
define("POS_FACTURACION_ALL", false);

# *******************************
# Logs
# *******************************
# habilitar los logs
define("POS_LOG_TO_FILE", true);

# archivo donde se guardaran los logs
define("POS_LOG_TO_FILE_FILENAME", "/opt/pos/branches/papassupremas/server/pos.log");
define("POS_LOG_ACCESS_FILE","/opt/pos/branches/papassupremas/server/pos.log");
define("POS_ERROR_FILE","/opt/pos/branches/papassupremas/server/pos.log");
define("POS_LOG_TRACKBACK", false);
define("POS_LOG_DB_QUERYS", false);

# *******************************
# ZONA HORARIA
# *******************************
date_default_timezone_set("America/Mexico_City");

# ******************************
# BASE DE DATOS 
# ******************************
define('POS_CORE_DB_USER',       'root');
define('POS_CORE_DB_PASSWORD',   'anti4581549');
define('POS_CORE_DB_NAME',       'ps_core');
define('POS_CORE_DB_DRIVER',     'mysqlt');
define('POS_CORE_DB_HOST',       'localhost');
define('POS_CORE_DB_DEBUG',      false);


# *******************************
# Seguridad
# *******************************
#cada que una sesion sobrepase de este valor, volvera a pedir las credenciales
$__ADMIN_TIME_OUT 	= 3600;
$__GERENTE_TIME_OUT = 3600;
$__CAJERO_TIME_OUT 	= 3600;
