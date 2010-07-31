

/* --------------------------------------------
	appImpresora contiene todas las funciones para 
	la impresion a dispositivos.
-------------------------------------------- */


appImpresora = function ()
{
	
	
	this._init();

};



appImpresora.prototype._init = function ()
{

};

/*------------------------------------------------------------
ejemplo de json que recibe:


'{ "id_venta" : "234" , "nombre_sucursal" : "Almacen" , "direccion" :"esta es la direccion de esta sucursal",  "productos" : [{ "cantidad" : "3" , "producto": "papa supersuprema" , "precioUnitario" : "12", "importe" : "36.00"},{ "cantidad" : "5" , "producto": "prod1" , "precioUnitario" : "10", "importe" : "50.00"},{ "cantidad" : "5" , "producto": "prod1" , "precioUnitario" : "10" , "importe" : "50.00"}], "subtotal" : "136.00" , "iva" : "21.50" , "total" : "157.50" , "pagado" : "200" , "adeuda" : "0.00" , "cambio" : "42.50" , "atendio" : "Diego Ventura" }';

------------------------------------------------------------*/
appImpresora.prototype.ticket_print = function (datos)
{
	json= Ext.util.JSON.decode(datos);
	//var ventanaImpresion = window.open("", "ticket","");
	ventanaImpresion = window.open("", "ticket","width=200,height=650,Titlebar=NO,Directories=NO,resizable=NO");
	
	ventanaImpresion.document.write("venta "+json.id_venta+"<br><br>");
	
	
	ventanaImpresion.document.write("<center>Papas Supremas</center><br>");
	
	ventanaImpresion.document.write("<center>"+json.nombre_sucursal+"</center><br>");
	
	ventanaImpresion.document.write(json.direccion+"<br><br>");
	
	fecha=new Date();
	
	ventanaImpresion.document.write(fecha.getDate()+"/"+fecha.getMonth()+"/"+(fecha.getYear()+1900)+" - "+fecha.getHours()+":"+fecha.getMinutes()+"<br><br>");
	
	//ventanaImpresion.document.write("<pre>Can  producto P.U.  Importe</pre>");
	ventanaImpresion.document.write("<table>");
	
	ventanaImpresion.document.write("<TR><TH>Can</TH>  <TH>producto</TH><TH>P.U.</TH><TH>Importe</TH></TR>");
	
	ventanaImpresion.document.write("<TR><TD COLSPAN=4>- - - - - - - - - - - - - - - - - -<TD></TR>");
	
	for(i=0;i<json.productos.length;i++)
	{
		cantidad=json.productos[i].cantidad;
		producto=json.productos[i].producto.substring(0,12);
		pu=json.productos[i].precioUnitario;
		importe=json.productos[i].importe;
		ventanaImpresion.document.write("<TR><TD>"+cantidad+"</TD><TD>"+producto+"</TD><TD>"+pu+"</TD><TD>$"+importe+"</TD></TR>");
	}
	
	ventanaImpresion.document.write("<TR><TD COLSPAN=4>- - - - - - - - - - - - - - - - - -<TD></TR>");
	
	ventanaImpresion.document.write("<TR><TD COLSPAN=3>Subtotal:<TD>$"+json.subtotal+"</TD></TR>");
	
	ventanaImpresion.document.write("<TR><TD COLSPAN=3>iva:<TD>$"+json.iva+"</TD></TR>");
	
	ventanaImpresion.document.write("<TR><TD COLSPAN=4>- - - - - - - - - - - - - - - - - -<TD></TR>");
	
	ventanaImpresion.document.write("<TR><TH COLSPAN=3>Total:<TH>$"+json.total+"</TD></TR>");
	
	ventanaImpresion.document.write("<TR><TD COLSPAN=3>Pago:<TD>$"+json.pagado+"</TD></TR>");
	
	ventanaImpresion.document.write("<TR><TD COLSPAN=3>Adeuda:<TD>$"+json.adeuda+"</TD></TR>");
	
	ventanaImpresion.document.write("<TR><TD COLSPAN=3>Cambio:<TD>$"+json.cambio+"</TD></TR>");
	
	
	ventanaImpresion.document.write("<TR><TD COLSPAN=4>- - - - - - - - - - - - - - - - - -<TD></TR>");
	
	//ventanaImpresion.document.write("<TR><TD COLSPAN=4>------------------------------------------<TD></TR>");
	
	ventanaImpresion.document.write("</table>");
	
	ventanaImpresion.document.write("<br><br>Lo Atendio: "+json.atendio);
	
	ventanaImpresion.document.write("<center>Gracias Por Su Preferencia</center><br>");

	ventanaImpresion.document.close();

	ventanaImpresion.print( );

	ventanaImpresion.close();
	
};//ticket_print


