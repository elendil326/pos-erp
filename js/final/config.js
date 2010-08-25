

/* --------------------------------------------
	CONFIG
-------------------------------------------- */


DEBUG = true;

/* 
	CONSTANTES DE LA APLICACIN
*/
//var MOSTRADOR_IVA = .15;

var MULTIPLE_SAME_ITEMS = true;



/*
	SUBTOTAL PESOS 
	IVA PORCENTAJE 0/100
	DESCUENTO PORCENTAJE 0/100
*/
function calcularTotal ( subtotal, iva, descuento ){

	iva /= 100;
	descuento /= 100;

	total = (subtotal- (subtotal*descuento)) + ((subtotal-descuento)*iva);
	
	console.log("Calculado total","subtotal:"+ subtotal, "iva:"+iva, "descuento: "+descuento);
	console.log("total"+total);
	return total;

}
