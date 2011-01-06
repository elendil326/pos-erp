<?php
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
