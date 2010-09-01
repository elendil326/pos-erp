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


	function createTable(rp, page){
		
		//alert();
		
		config.data.rp = rp;
		config.data.page = page;
		
		Utils.request({
	
		url: config.url,
		data: config.data,
		success: function(msg){
			
			var html = "";
			
			if(msg.success)
			{
				//creamos div contenedor y tabla renglon por renglon
				//html += "<div class='tabla-caffeina'>";
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
					//onclick='createTable("+config.data.rp+","+ (parseInt(msg.page)-1) + ");'
					html += "<a href='#' class='tabla-nav-link' id='tabla-nav-link-prev' >&lt;</a>";
				}
				
				//calcular total de paginas
				var paginas = Math.ceil(msg.total/config.data.rp);
				
				//mostrar pagina actual
				html += msg.page+" de "+paginas+" p&aacute;ginas ";
				
				// links de navegacion
				if(msg.page != paginas)
				{
					//onclick='createTable("+config.data.rp+","+ (parseInt(msg.page)+1) + ");'
					html += "<a href='#' class='tabla-nav-link' id='tabla-nav-link-next' >&gt;</a>";
				}
				
				var currEnd = Math.min(config.data.rp*msg.page, msg.data.length);
				
				if(currEnd == msg.data.length)
				{
					currEnd += config.data.rp * (msg.page-1);
				}
				
				var currRP = config.data.rp*msg.page;
				var currStart = ((config.data.rp*msg.page)-config.data.rp)+1;
				
				html += "Mostrando elemento "+currStart+" al "+currEnd+" de "+msg.total+ " Filas por p&aacute;gina <input type='text' value='"+config.data.rp+"' size='2' class='tabla-input'id='tabla-rp-input'/>";

				
				html += "</div>";
				html += "</div>";
				
				$(".tabla-caffeina").fadeOut('slow', function(){
					
					$(".tabla-caffeina").html("");
					$(".tabla-caffeina").html(html);
					
					$(".tabla-caffeina").fadeIn('slow', function(){
					
						//keypress handler
						$('#tabla-rp-input').keypress(function(event) {
						  if (event.keyCode == '13') {
						  
						  	//si se presiono enter, recargar la tabla con datos nuevos
						     event.preventDefault();
						     
						     
						     //alert($(this).val());
						     createTable($('#tabla-rp-input').val(), msg.page);
						     
						   }
						  
						  
						  
						});
						
						
						$('#tabla-nav-link-prev').click(function(){
							//alert("prev");
							createTable(config.data.rp, msg.page-1);
						});
						
						$('#tabla-nav-link-next').click(function(){
						
							//alert("next");
							createTable(config.data.rp, msg.page+1);
						});
					
					});
				});
				
				
			}
			else
			{
				html += "<div class='tabla-datos'>No se pudo obtener datos, intente nuevamente</div>";
				$(selector).append(html);
			}
			
			config.success(msg);
		}
	
	});
	
	}


	// primera vez que se rendera la tabla
	var selector = "#"+config.renderTo;
	var html = "";

	this.request({
	
		url: config.url,
		data: config.data,
		success: function(msg){
			
			if(msg.success)
			{
				//creamos div contenedor y tabla renglon por renglon
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
					html += "<a href='#' class='tabla-nav-link' id='tabla-nav-link-prev'>&lt;</a>";
				}
				
				//calcular total de paginas
				var paginas = Math.ceil(msg.total/config.data.rp);
				
				//mostrar pagina actual
				html += msg.page+" de "+paginas+" p&aacute;ginas ";
				
				// links de navegacion
				if(msg.page != paginas)
				{
					html += "<a href='#' class='tabla-nav-link' id='tabla-nav-link-next'>&gt;</a>";
				}
				
				var currEnd = Math.min(config.data.rp*msg.page, msg.data.length);
				
				if(currEnd == msg.data.length)
				{
					currEnd += config.data.rp * (msg.page-1);
				}
				
				var currRP = config.data.rp*msg.page;
				var currStart = ((config.data.rp*msg.page)-config.data.rp)+1;
				
				html += "Mostrando elemento "+currStart+" al "+currEnd+" de "+msg.total+ " Filas por p&aacute;gina <input type='text' value='"+config.data.rp+"' size='2' class='tabla-input'id='tabla-rp-input'/>";

				
				html += "</div>";
				html += "</div>";
				
				$(selector).append(html);
				
				//keypress handler
				$('#tabla-rp-input').keypress(function(event) {
				  if (event.keyCode == '13') {
				  
				  	//si se presiono enter, recargar la tabla con datos nuevos
				     event.preventDefault();
				     
				     
				     //alert($(this).val());
				     createTable($(this).val(), msg.page);
				     
				   }
				  
				  
				});
				
				$('#tabla-nav-link-prev').click(function(){
					//alert("prev");
					createTable(config.data.rp, msg.page-1);
					
				});
				
				$('#tabla-nav-link-next').click(function(){
					//alert("next");
					createTable(config.data.rp, msg.page+1);
					
				});
			}
			else
			{
				html += "<div class='tabla-datos'>No se pudo obtener datos, intente nuevamente</div>";
				$(selector).append(html);
			}
			
			config.success(msg);
		}
	
	});
	
	
	

}
