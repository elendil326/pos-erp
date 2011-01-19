<?php

# *******************************
# Definiciones
# *******************************
define('POS_SEMANA', 1);
define('POS_MES', 1);


# *******************************
# Configuracion Basica
# *******************************

#carpeta donde se encuentran los scripts del servidor,
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . "/var/www/alan/trunk/server");
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . "/var/www/pos/trunk/server");
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . "/Applications/XAMPP/xamppfiles/htdocs/caffeina/pos/trunk/server");

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
define("POS_LOG_TO_FILE", true);

# archivo donde se guardaran los logs
define("POS_LOG_TO_FILE_FILENAME", "/var/log/mx.caffeina.pos/pos.log");




# *******************************
# ZONA HORARIA
# *******************************
date_default_timezone_set("America/Mexico_City");



# ******************************
# BASE DE DATOS 
# ******************************

define('DB_USER',       'pos');
define('DB_PASSWORD',   'pos');
define('DB_NAME',       'pos');
define('DB_DRIVER',     'mysqlt');
define('DB_HOST',       'localhost');
define('DB_DEBUG',      false);
//define('ADODB_ERROR_LOG_TYPE',3);
//define('ADODB_ERROR_LOG_DEST','/var/log/mx.caffeina.pos/pos.log');

//conectarse a la base de datos
require_once('db/DBConnection.php');



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
    //Logger::log("imposible iniciar sesion");
	echo "{\"success\": false , \"reason\": -1,  \"text\" : \"Imposible iniciar sesion. Debe habilitar las cookies para ingresar.\" }";
	return;
}



require_once('utils.php');


