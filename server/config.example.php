<?php

# *******************************
# Definiciones
# *******************************
define('POS_SEMANA', 1);
define('POS_MES', 1);


# *******************************
# Configuracion Basica
# *******************************

#segundos que deben de pasar antes de negar la eliminacion de la ultima venta
define('POS_ELIMINATION_TIME', 7200);

#carpeta donde se encuentran los scripts del servidor,
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . "/path/to/server/folder");


# titulo de la aplicacion para las paginas html
define("_POS_HTMLTITLE", "Papas Supremas");

define('POS_MAX_LIMITE_DE_CREDITO', 20000);
define('POS_MAX_LIMITE_DESCUENTO', 35.0);

define('POS_PERIODICIDAD_SALARIO', POS_SEMANA);

# habilitar o deshabilitar el uso de mapas en la aplicacion
define('POS_ENABLE_GMAPS', true);	

#estilo para las fechas
define('POS_DATE_FORMAT', 'j/m/y h:i:s A');

# *******************************
# Logs
# *******************************

# habilitar los logs
define("POS_LOG_TO_FILE", false);

# archivo donde se guardaran los logs
define("POS_LOG_TO_FILE_FILENAME", "/path/to/log");

define("POS_LOG_TRACKBACK", false);

define("POS_LOG_DB_QUERYS", false);





# *******************************
# ZONA HORARIA
# *******************************
date_default_timezone_set("America/Mexico_City");



# ******************************
# BASE DE DATOS 
# ******************************
define('DB_USER',       '');
define('DB_PASSWORD',   '');
define('DB_NAME',       '');

define('DB_DRIVER',     'mysqlt');
define('DB_HOST',       'localhost');
define('DB_DEBUG',      false);


# *******************************
# Seguridad
# *******************************
//cada que una sesion sobrepase de este valor, volvera a pedir las credenciales
$__ADMIN_TIME_OUT 	= 3600;
$__GERENTE_TIME_OUT = 3600;
$__CAJERO_TIME_OUT 	= 3600;

# metodo de validacion de sucursales mediante user-agent
# puede ser 'FULL_UA' o bien 'SID_TOKEN'
# 'FULL_UA' validara 
# 'SID_TOKEN' buscara la subcadena SID={00000} dentro del UA y
# comparara esta cadena de 5 caracteres contra un equipo en la 
# base de datos.
define( 'POS_SUCURSAL_TEST_TOKEN', 'FULL_UA' );


//nombre de la galleta
//session_set_cookie_params ( int $lifetime [, string $path [, string $domain [, bool $secure = false [, bool $httponly = false ]]]] )
session_name("POS_ID");

session_set_cookie_params ( 0  , '/' );


$ss = session_start (  );

if(!$ss){
	echo '{"success": false,"reason": "Imposible iniciar sesion." }';
	return;
}

//logger
require_once("logger.php");

//conectarse a la base de datos
require_once('db/DBConnection.php');

require_once('utils.php');


