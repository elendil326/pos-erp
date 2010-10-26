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


Utils.borrar = function(id, action, row){
	
			console.log(id+" "+action+" "+row);
			
			if( confirm("Esta seguro que desea borrar esta fila?") )
			{
				Utils.request({
					url: "../proxy.php",
					data: {action: action, id_usuario: id},
					success: function(msg){
					
						if(msg.success)
						{
							var selector = "#row-tabla-caffeina-"+row;
							$(selector).fadeOut('slow');
						}
						else
						{
							alert(msg.error);
						}
					
					}
			
				});
			}
			
		
	}
	

/**
*	Funcion para editar datos del grid
*/
Utils.editar = function(id, action, row){

	//console.log(id+" "+action+" "+row);
	var sel = "#row-tabla-caffeina-"+row;
	var selButon = "#editar-tabla-caffeina-"+row;
	
	
	//escondemos el boton de editar
	$(selButon).hide();
	
	var tds = $(sel).children(); // contiene las columnas de la fila especificada en el selector
	
	var i = 0;
	
	//Iteramos por todas las columnasn del renglon
	tds.each( function(){
	
		if(i < 3 && i != 0)
			$(this).html("<input type='text' value='"+$(this).html()+"' name='celda-"+row+"-"+i+"' />");
		
		//si es la columna de sucursal
		if(i == 3)
		{
			var valorSel = $(this).html();
			//var selSelect = "option [text="+valorSel+"]";
			$(this).html("<select >"+$('#select-sucursal').html()+"</select>");
			
			//console.log($(this).children()[0].options);
			$(this).children().each(function(){
			
				//iteramos por las opciones
				$(this).children().each(function(){
				
					console.log($(this).html()+"=="+valorSel);					
					if( $(this).html() == valorSel ) { 

						
						$(this).attr("selected", true); 
					}
					
				});
			
			});
		}
		
		//si es la columna de administracion
		if(i == 4)
		{
			var valorSel = $(this).html();
			//var selSelect = "option [text="+valorSel+"]";
			$(this).html("<select >"+$('#acceso').html()+"</select>");
			
			//console.log($(this).children()[0].options);
			$(this).children().each(function(){
			
				//iteramos por las opciones
				$(this).children().each(function(){
				
					console.log($(this).html()+"=="+valorSel);					
					if( $(this).html() == valorSel ) { 

						
						$(this).attr("selected", true); 
					}
					
				});
			
			});
		}
			
		if(i == 6) $(this).append("<button  id='boton-ok-editar-row-"+row+"' >OK</button><button id='boton-cancel-editar-row-"+row+"'>Cancelar</button>");
		i++;
	});
	
	var selOkButton = "#boton-ok-editar-row-"+row;
	var selCancelButton = "#boton-cancel-editar-row-"+row;
	
	//Evento para el boton de cancelar edicion
	$(selCancelButton).click(function(){
	
		var tds_  = $(this).parent().parent().children();
		
		
		//console.log(tds_);
		
		var j = 0;
		tds_.each(function(){
		
			if(j < 3 && j != 0)
			{
				$(this).html($(this).children()[0].value);
				//console.log($(this).children()[0].value);
			}
			
			
			if(j == 3 || j == 4)
			{
				
				var val = $(this).children()[0].value;
				var display = "";
				console.log(val);
				//$(this).html($(this).children()[0].display);
				
				//iteramos por las opciones del select para checar cual esta seleccionada
				$(this).children().children().each(function(){
				
					if($(this).val() == val){
						display = $(this).html();
					}
				
				});
				
				$(this).html(display);
			}
			
			if(j == 6) {
				$(this).children()[0].style.display = "block";
				$(this).html($(this).children()[0]);
				//console.log("cell[5] children: ");
				//console.log($(this).children()[0]);
			}
			j++;
			
		});
		
		
	
	});
	
	//Evento para el boton de Enviar edicion
	$(selOkButton).click(function(){
		
		//obtenemos las celdas de la fila que se quiere editar
		var tds_  = $(this).parent().parent().children();
		
		
		//console.log(tds_);
		
		var j = 0;
		var nombre;
		var usuario;
		var sucursal;
		var id;
		var grupo;
		
		tds_.each(function(){
		
			if(j == 0) //campo del id
			{
				id = $(this).text();
			}
		
			//si son las celdas de datos
			if(j < 5 && j != 0)
			{
				
				switch(j){
					case 1:
						nombre = $(this).children()[0].value;
						$(this).html($(this).children()[0].value);
					break;
					
					case 2:
						usuario = $(this).children()[0].value;
						$(this).html($(this).children()[0].value);
					break;
					
					case 3:
						sucursal = $(this).children()[0].value;
						var val = $(this).children()[0].value;
						var display = "";
						console.log(val);
						//$(this).html($(this).children()[0].display);
				
						//iteramos por las opciones del select para checar cual esta seleccionada
						$(this).children().children().each(function(){
				
							if($(this).val() == val){
								display = $(this).html();
							}
				
						});
				
						$(this).html(display);
					break;
					
					case 4:
						grupo = $(this).children()[0].value;
						var val = $(this).children()[0].value;
						var display = "";
						console.log(val);
						//$(this).html($(this).children()[0].display);
				
						//iteramos por las opciones del select para checar cual esta seleccionada
						$(this).children().children().each(function(){
				
							if($(this).val() == val){
								display = $(this).html();
							}
				
						});
				
						$(this).html(display);
					break;
				}
				
				
				
				//console.log($(this).children()[0].value);
			}
			
			//si es la celda de los botones
			if(j == 6) {
				$(this).children()[0].style.display = "block";
				$(this).html($(this).children()[0]);
			}
			j++;
			
		});
		
		
		Utils.request({
			url: "../proxy.php",
			data: { action: action, nombre: nombre, username: usuario, id_sucursal: sucursal, id_usuario : id, id_grupo: grupo },
			success: function(data){
				
				console.log(data);
				if(!data.success)
				{
					alert(data.error);
				}
				else
				{
					alert(data.datos);
				}
			}
		
		});
	
	});

}

/**
*	Funcion para hacer un grid sacando datos con ajax
*
*	@param {Object}	config Objecto javascript que contiene la configuracion de la peticion AJAX
*		-> url URL donde se hara la peticiones
*		-> data datos en forma de objecto, o en una cadena que se enviaran en la peticiones
*		-> success {function} funcion que se ejecuta cuando la peticion regresa
*		-> renderTo {String} div a donde se va a renderear la tabla
*		-> deleteAction {int} php action que sirve para borra el elemento mediante su id
*		-> columns {Array} esquema de columnas de la tabla, solo se requieren los titulos ej. ['id', 'nombre', 'edad']
*/
Utils.grid = function(config){


	// funcion para borrar un elemento, id del elemento en la db, action el numero de accion, row es el id de la fila
	/*function borrar(id, action, row){
	
			console.log(id+" "+action+" "+row);
			
			Utils.request({
				url: "../proxy.php",
				data: {action: action, id: id},
				success: function(){
				
					var selector = "#row-tabla-caffeina-"+row;
					$(selector).slideDown();
					
				}
			
			});
		
	}*/


	//funcion privada para actualizar la tabla despues de la primera creacion
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
					html += "<tr id='row-tabla-caffeina-"+i+"' class='row-tabla-caffeina'>";
					
					for(var k=0; k < config.columns.length; k++)
					{
						html += "<td>"+msg.data[i][k]+"</td>";
					}
					
					html += "<td><button id='borrar-tabla-caffeina-"+i+"' onclick='Utils.borrar("+msg.data[i][0]+","+config.deleteAction+", "+i+")'><img src='../media/admin/cross.png'  /></button></td><td><button id='editar-tabla-caffeina-"+i+"' onclick='Utils.editar("+msg.data[i][0]+","+config.editAction+", "+i+")' ><img src='../media/admin/icon_date_picker_input.gif' /></button></td></tr>";
					html += "</tr>";
				}
				
				html += "</table>";
				html += "<div class='tabla-nav'>";
				
				//apend navegacion
				
				if(msg.page != 1)
				{
					//onclick='createTable("+config.data.rp+","+ (parseInt(msg.page)-1) + ");'
					html += "<a href='#tabla' class='tabla-nav-link' id='tabla-nav-link-first' >&lt;&lt;&nbsp;&nbsp;</a>";
					html += "<a href='#tabla' class='tabla-nav-link' id='tabla-nav-link-prev' >&lt;</a>";
				}
				
				//calcular total de paginas
				var paginas = Math.ceil(msg.total/config.data.rp);
				
				//mostrar pagina actual
				html += "<input type='text' value='"+msg.page+"' size='2' class='tabla-input'id='tabla-page-input'/>"+" de "+paginas+" p&aacute;ginas ";
				
				// links de navegacion
				if(msg.page != paginas)
				{
					//onclick='createTable("+config.data.rp+","+ (parseInt(msg.page)+1) + ");'
					html += "<a href='#tabla' class='tabla-nav-link' id='tabla-nav-link-next' >&gt;</a>";
					html += "<a href='#tabla' class='tabla-nav-link' id='tabla-nav-link-last' >&nbsp;&nbsp;&gt;&gt;</a>";
				}
				
				var currEnd = Math.min(config.data.rp*msg.page, msg.data.length);
				
				if(currEnd == msg.data.length)
				{
					currEnd += config.data.rp * (msg.page-1);
				}
				
				var currRP = config.data.rp*msg.page;
				var currStart = ((config.data.rp*msg.page)-config.data.rp)+1;
				
				html += "Mostrando elemento "+currStart+" al "+currEnd+" de "+msg.total+ " <input type='text' value='"+config.data.rp+"' size='2' class='tabla-input'id='tabla-rp-input'/> Filas por p&aacute;gina ";

				
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
						     createTable($('#tabla-rp-input').val(), 1);
						     
						   }
						  
						  
						  
						});
						
						//keypress handler
						$('#tabla-page-input').keypress(function(event) {
						  if (event.keyCode == '13') {
						  
						  	//si se presiono enter, recargar la tabla con datos nuevos
						     event.preventDefault();
						     
						     
						     //alert($(this).val());
						     createTable(config.data.rp, $('#tabla-page-input').val());
						     
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
						
						
						$('#tabla-nav-link-first').click(function(){
						
							//alert("next");
							createTable(config.data.rp, 1);
						});
						
						$('#tabla-nav-link-last').click(function(){
						
							//alert("next");
							createTable(config.data.rp, paginas);
						});
						
						/*for(var l=0; l < msg.data.length; l++)
						{
							 //onclick='borrar("+msg.data[i][0]+","+config.deleteAction+", "+i+")'
							 var sel = "#borrar-tabla-caffeina-"+l;
							 $(sel).click(function(){
							 	borrar(msg.data[l][0], config.deleteAction, l);
							 });
						}*/
					
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
	
	}//end createTable


	// primera vez que se rendera la tabla
	var selector = "#"+config.renderTo;
	var html = "";


	//ajax para obtener los datos
	this.request({
	
		url: config.url,
		data: config.data,
		success: function(msg){
			
			if(msg.success)
			{
				//creamos div contenedor y tabla renglon por renglon
				html += "<a name=tabla></a>";
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
					html += "<tr id='row-tabla-caffeina-"+i+"' class='row-tabla-caffeina'>";
					
					for(var k=0; k < config.columns.length; k++)
					{
						html += "<td>"+msg.data[i][k]+"</td>";
					}
					
					html += "<td><button id='borrar-tabla-caffeina-"+i+"' onclick='Utils.borrar("+msg.data[i][0]+","+config.deleteAction+", "+i+")'><img src='../media/admin/cross.png' /></button></td><td><button id='editar-tabla-caffeina-"+i+"' onclick='Utils.editar("+msg.data[i][0]+","+config.editAction+","+i+")' ><img src='../media/admin/icon_date_picker_input.gif' /></button></td></tr>";
					html += "</tr>";
				}
				
				html += "</table>";
				html += "<div class='tabla-nav'>";
				
				//apend navegacion
				
				if(msg.page != 1)
				{
					html += "<a href='#tabla' class='tabla-nav-link' id='tabla-nav-link-first'>&lt;&lt;&nbsp;&nbsp;</a>";
					html += "<a href='#tabla' class='tabla-nav-link' id='tabla-nav-link-prev'>&lt;</a>";
				}
				
				//calcular total de paginas
				var paginas = Math.ceil(msg.total/config.data.rp);
				
				//mostrar pagina actual
				html += "<input type='text' value='"+msg.page+"' size='2' class='tabla-input'id='tabla-page-input'/>"+ "de "+paginas+" p&aacute;ginas ";
				
				// links de navegacion
				if(msg.page != paginas)
				{
					html += "<a href='#tabla' class='tabla-nav-link' id='tabla-nav-link-next'>&gt;</a>";
					html += "<a href='#tabla' class='tabla-nav-link' id='tabla-nav-link-last'>&nbsp;&nbsp;&gt;&gt;</a>";
				}
				
				var currEnd = Math.min(config.data.rp*msg.page, msg.data.length);
				
				if(currEnd == msg.data.length)
				{
					currEnd += config.data.rp * (msg.page-1);
				}
				
				var currRP = config.data.rp*msg.page;
				var currStart = ((config.data.rp*msg.page)-config.data.rp)+1;
				
				html += "Mostrando elemento "+currStart+" al "+currEnd+" de "+msg.total+ " <input type='text' value='"+config.data.rp+"' size='2' class='tabla-input'id='tabla-rp-input'/> Filas por p&aacute;gina ";

				
				html += "</div>";
				html += "</div>";
				
				$(selector).append(html);
				
				//keypress handler
				$('#tabla-rp-input').keypress(function(event) {
				  if (event.keyCode == '13') {
				  
				  	//si se presiono enter, recargar la tabla con datos nuevos
				     event.preventDefault();
				     
				     
				     //alert($(this).val());
				     createTable($(this).val(), 1);
				     
				   }
				  
				  
				});
				
				$('#tabla-page-input').keypress(function(event) {
				  if (event.keyCode == '13') {
				  
				  	//si se presiono enter, recargar la tabla con datos nuevos
				     event.preventDefault();
				     
				     
				     //alert($(this).val());
				     createTable(config.data.rp, $('#tabla-page-input').val());
				     
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
				
				$('#tabla-nav-link-first').click(function(){
						
							//alert("next");
							createTable(config.data.rp, 1);
						});
						
				$('#tabla-nav-link-last').click(function(){
				
					//alert("next");
					createTable(config.data.rp, paginas);
				});
				
				
				for(var l=0; l < msg.data.length; l++)
				{
					 //onclick='borrar("+msg.data[i][0]+","+config.deleteAction+", "+i+")'
					 var sel = "#borrar-tabla-caffeina-"+l;
					 var id = msg.data[l][0];
					 
					 
					 /*
					 $(sel).click(function(){
					 	//console.log(id);
					 	//eval("borrar("+id+", "+config.deleteAction+", "+l+");");
					 	//$(sel).slideDown();					 
					 	
					 });*/
				}
				
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
