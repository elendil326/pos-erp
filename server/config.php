<?php

/* *******************************
Configuracion Basica
********************************* */

//carpeta donde se encuentran los scripts del servidor
//ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . "/Applications/XAMPP/xamppfiles/htdocs/svn/pos/trunk/server");
//ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . "/var/www/caffeina/pos/trunk/server");
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . "/var/www/alan/trunk/server");


//titulo de la aplicacion
define("_POS_HTMLTITLE", "Papas Supremas");

define('POS_MAX_LIMITE_DE_CREDITO', 20000);
define('POS_MAX_LIMITE_DESCUENTO', 35.0);
define('POS_ENABLE_GMAPS', true);	


/* *******************************
LOG
********************************* */
define("_POS_LOG_TO_FILE", true);
define("_POS_LOG_TO_FILE_FILENAME", "/var/log/mx.caffeina.pos/pos.log");




/* *******************************
ZONA HORARIA
********************************* */
date_default_timezone_set("America/Mexico_City");



/* *******************************
BASE DE DATOS 
********************************* */
define('DB_USER',       'pos');
define('DB_PASSWORD',   'pos');
define('DB_NAME',       'pos');
define('DB_DRIVER',     'mysqlt');
define('DB_HOST',       'localhost');
define('DB_DEBUG',      false);

//conectarse a la base de datos
require_once('db/DBConnection.php');



/* *******************************
Seguridad
********************************* */
//cada que una sesion sobrepase de este valor, volvera a pedir las credenciales
$__ADMIN_TIME_OUT 	= 3600;
$__GERENTE_TIME_OUT = 3600;
$__CAJERO_TIME_OUT 	= 3600;

define( 'POS_SUCURSAL_TEST_TOKEN', 'FULL_UA' );
//define( 'POS_SUCURSAL_TEST_TOKEN', 'SID_TOKEN' ); //SID={00000} dentro del UA

//nombre de la galleta
//session_set_cookie_params ( int $lifetime [, string $path [, string $domain [, bool $secure = false [, bool $httponly = false ]]]] )
session_name("POS_ID");
session_set_cookie_params ( 0  , '/' );


$ss = session_start (  );

if(!$ss){
    Logger::log("imposible iniciar sesion");
	echo "{\"success\": false , \"reason\": -1,  \"text\" : \"Imposible iniciar sesion. Debe habilitar las cookies para ingresar.\" }";
	return;
}




/* *******************************
Funciones de ayuda
********************************* */
function endsWith( $str, $sub ) {
return ( substr( $str, strlen( $str ) - strlen( $sub ) ) == $sub );
}

function __pos__calcularTotal($subtotal, $iva, $descuento)
{
//funcion para calular el total
//subtotal - pesos
//iva - porcentaje
//descuento - porcentaje
$iva /= 100;
$descuento /= 100;
//descuento sobre iva

return ( ($subtotal- ($subtotal*$descuento)) + (($subtotal-($subtotal*$descuento))*$iva) );

}




function parseJSON($json){

        try{
            	if($json != stripslashes($json)){
                        return json_decode(stripslashes($json));
                }else{
                      	return json_decode($json);
                }
        }catch(Exception $e){
                return null;
        }
}

