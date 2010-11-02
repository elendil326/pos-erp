

/* --------------------------------------------
	appImpresora contiene todas las funciones para 
	la impresion a dispositivos.
-------------------------------------------- */


appImpresora = function ()
{
	
	


};

/*

*/	
appImpresora.ImprimirTicket = function ( cliente, items, ventaTotales )
{
	if(DEBUG){
		console.log("Imprmir Ticket");
		console.log("Items",items);
		console.log("Cliente", cliente);
		console.log("Totales de venta", ventaTotales);
	}


	ventanaImpresion = window.open("", "ticket","width=190,height=250,Titlebar=NO,Directories=NO,resizable=NO");
	
	ventanaImpresion.document.write("<html>");
	ventanaImpresion.document.write("<body>");	
	
		
	ventanaImpresion.document.write("<style>");	
	ventanaImpresion.document.write("	.titulo{color: red; width: 100%;}");
	ventanaImpresion.document.write("	.datos_cliente{margin-top: 20px; margin-bottom: 20px;}");

	ventanaImpresion.document.write("</style>");
	
	ventanaImpresion.document.write("<div class='titulo' align=center>Papas Supremas</div>");
	ventanaImpresion.document.write("<div class='sucursal' align=center>" +POS_SUCURSAL_NOMBRE+ "</div>");
	ventanaImpresion.document.write("<div class='vendedor' align=center>Cajero <b>" +POS_CAJERO_NOMBRE+ "</b></div>");

	ventanaImpresion.document.write("<div class='datos_cliente'>");	
		if(cliente === null){
			//caja comun
			ventanaImpresion.document.write("<div align=center>Venta de Mostrador</div>");		
		}else{
		//cliente definido
		/*descuento: "7"
		direccion: "P.O. Box 841, 5000 Sed St."
		e_mail: "dictum@Curabiturconsequatlectus.org"
		iden: "66"
		limite_credito: "10311"
		nombre: "Alisa Church"
		rfc: "PDQ20AUE6PD"
		telefono: "(155) 290-1737"*/

		ventanaImpresion.document.write("<div >Cliente: "+ cliente.nombre +"</div>");		
		ventanaImpresion.document.write("<div >"+ cliente.rfc +"</div>");	
		ventanaImpresion.document.write("<div >"+ cliente.direccion +"</div>");	
		ventanaImpresion.document.write("<div >"+ cliente.telefono +"</div>");
		}
	ventanaImpresion.document.write("</div>");	
	
	
	/*
	cantidad: 1
	cost: "203"
	description: "malesuada fames ac turpis eges"
	existencias: "152"
	id: "9"
	name: "Vivamus sit"
	*/
	
	ventanaImpresion.document.write("<div align=center>");
	ventanaImpresion.document.write("<table>");
	ventanaImpresion.document.write("<tr>");
		ventanaImpresion.document.write("<td></td>");
		ventanaImpresion.document.write("<td><b>Producto</b></td>");
		ventanaImpresion.document.write("<td><b>Costo</b></td>");
		ventanaImpresion.document.write("<td><b>Ctd</b></td>");
		ventanaImpresion.document.write("<td><b>Importe</b></td>");
	ventanaImpresion.document.write("</tr>");
	
	for( a = 0; a < items.length;  a++){
		ventanaImpresion.document.write("<tr>");

			ventanaImpresion.document.write("<td><b>"+ items[a].id +"</b></td>"); 
			ventanaImpresion.document.write("<td>"+items[a].name+"</td>");
			ventanaImpresion.document.write("<td>"+ POS.currencyFormat(items[a].cost) +"</td>");
			ventanaImpresion.document.write("<td align=center>"+ items[a].cantidad +"</td>");
			ventanaImpresion.document.write("<td>"+ POS.currencyFormat(parseFloat(items[a].cantidad)*parseFloat(items[a].cost)) +"</td>");

		ventanaImpresion.document.write("</tr>");		
	}
	ventanaImpresion.document.write("</table>");	
	ventanaImpresion.document.write("</div>");
	
	
	
	ventanaImpresion.document.write("<div align='right' style='margin-left: 20px'>");
	ventanaImpresion.document.write("<div >SubTotal "+ POS.currencyFormat(ventaTotales.subtotal) +"</div>");		
	ventanaImpresion.document.write("<div >IVA "+ POS.currencyFormat(ventaTotales.iva) +"</div>");		
		
			
	if(cliente !== null){
		//cliente definido
		ventanaImpresion.document.write("<div >Descuento "+ ventaTotales.descuento +" %</div>");	
	}	
		
	ventanaImpresion.document.write("<div ><b>Total "+ POS.currencyFormat(ventaTotales.total) +"</b></div>");		
	ventanaImpresion.document.write("</div>");	
	
	ventanaImpresion.document.write("</body>");		
	ventanaImpresion.document.write("</html>");


	ventanaImpresion.print( false );
	//ventanaImpresion.close();
	
};//ticket_print


