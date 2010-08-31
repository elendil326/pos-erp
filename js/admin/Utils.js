/**
*	@fileoverview	Javascript que contiene algunas utilidades que se pueden usar
*	con jQuery a traves de la seccion de administracion	
*
*	@author	Rene Michel rene@caffeina.mx
*	@version 0.1
*/

Utils = function(){

}


/**
*	Funcion para hacer peticiones mediante ajax
*
*	@param {Object}	config Objecto javascript que contiene la configuracion de la peticion AJAX
*		-> url URL donde se hara la peticiones
*		-> data datos en forma de objecto, o en una cadena que se enviaran en la peticiones
*		-> success funcion que se ejecuta cuando la peticion regresa
*/
Utils.request = function(config){

	$.getJSON(
		config.url,
		config.data,
		function(data){
			config.success(data);
			
		},
		function(data){
			config.failure(data);
		}
	);

}


/**
*	Funcion para hacer un grid sacando datos con ajax
*
*	@param {Object}	config Objecto javascript que contiene la configuracion de la peticion AJAX
*		-> url URL donde se hara la peticiones
*		-> data datos en forma de objecto, o en una cadena que se enviaran en la peticiones
*		-> success funcion que se ejecuta cuando la peticion regresa
*		-> renderTo
*		-> deleteAction
*		-> columns
*/
Utils.grid = function(config){

	var selector = "#"+config.renderTo;
	var html = "";

	this.request({
	
		url: config.url,
		data: config.data,
		success: function(msg){
			
			if(msg.success)
			{
				html += "<div class='tabla-caffeina'>";
				html += "<table class='tabla-datos'>";
				html += "<tr>";
			
				for(var j=0; j < config.columns.length; j++)
				{
					html += "<td><b>"+config.columns[j]+"</b></td>"
				}
				
				html += "<td><b>Borrar</b></td><td><b>Modificar</b></td></tr>"	
			
				for(var i=0; i < msg.data.length; i++)
				{
					html += "<tr id='row-usuario-"+i+"' class='row-usuario'>";
					
					for(var k=0; k < config.columns.length; k++)
					{
						html += "<td>"+msg.data[i][k]+"</td>";
					}
					
					html += "<td><button><img src='../media/admin/cross.png' onclick='borrar("+msg.data[i][0]+","+config.deleteAction+")' /></button></td><td><img src='../media/admin/icon_date_picker_input.gif' onclick='edit("+msg.data[i][0]+")' /></td></tr>";
					html += "</tr>";
				}
				
				html += "</table>";
				html += "<div class='tabla-nav'>";
				
				//apend navegacion
				
				if(msg.page != 1)
				{
					html += "<a href='#'>Anterior</a>";
				}
				
				var paginas = Math.round(msg.total/config.data.rp);
				
				html += msg.page+"/"+paginas;
				
				if(msg.page != paginas)
				{
					html += "<a href='#'>Siguiente</a>";
				}
				
				var currRP = config.data.rp*msg.page;
				
				html += "Mostrando "+currRP+" de "+msg.total;

				
				html += "</div>";
				html += "</div>";
				
				$(selector).append(html);
			}
			
			config.success(msg);
		}
	
	});

}
