/*
	APLICACION DE ADMINISTRACION, ESTA APP TENDRA FUNCIONES PARA AGREGAR PESTANAS
	CONFIGURAR MENUS, ETC RELACIONADOS A LA ESTRUCTURA Y ACOMODO DE MINI APPS

*/


AppAdmin = function(){

	AppAdmin.currentInstance = this;
	return this._init();

}

/*
	_init inicializa todo lo que tenga que asignarse al inicio dle arranque de la app
*/
AppAdmin.prototype._init = function(){

	this.loadStructure();

}

/*
	loadStructure carga la estructura inicial de la app de administracion, paneles laterales,
	pesta~nas y todo lo referente a controles. DEBE LLAMARSE EN LA FUNCION _init()
*/
AppAdmin.prototype.loadStructure = function(){

	//Creamos logout Message
	this.createLogoutMessage();
	
	//Metemos contenido al menu 
	
	$("#menu-left").html('<div id="accordion">\
	    <h3><a href="#" id="reportes-boton" >Reportes</a></h3>\
	    <div >Consulte reportes especificos</div>\
	    <h3><a href="#" id="datos-boton">Ver datos</a></h3>\
	    <div >Visualice datos completos</div>\
	    <h3><a href="#" id="sucursales-boton">Sucursales</a></h3>\
	    <div >Consulte datos de sus sucursales</div>\
	</div>');
	
	
	
	//Convertimos a acordion
	$('#accordion').accordion({active: false});
	
	
	 //TODO: Agregamos listeners a los botones
	 $('#reportes-boton').click(function(){
	 
	 				if(DEBUG){ console.log('click en reportes');}
	 				new Reports();
	 				
	 				});
	 				
	 $('#datos-boton').click(function(){
	 
	 				if(DEBUG){ console.log('click en datos');}
	 				new Datos();
	 				});
	 				
	 $('#sucursales-boton').click(function(){
	 
	 				if(DEBUG){ console.log('click en sucursales');}
	 				
	 				});
}

/*
	addGraph Funcion para agregar graficas a un div especifico
---------------------------------------------------------------------------------------------------------------
	@param config -objeto para cambiar caracteristicas de la grafica, configuracion inicial:
		-> width -ancho de la grafica
		-> height - alto de la grafica
		-> renderTo - un div donde queremos que se renderee la grafica
		-> divID -el id del div donde se rendereara.. se necesita <div id="algo"><canvas id="nombre"></canvas></div>
		-> canvasName -nombre del canvas
		-> tipo - el tipo de grafica [pie|line|bar]
		-> data - un array con arreglos en el formato [ [x1,y1], [x2,y2]]
		-> remoteData - bool que nos indica si cargaremos datos desde un ajax
		-> url - request url
		-> params - parametros para enviar con el request
		-> success - handler para cuando el request regresa con exito
		-> failure - handler para cuando el request regresa con un fallo
		TODO: configuracion avanzada
	Uso:
	Para dibujar una grafica se debe hacer una llamada a la funcion MochiKit.DOM.addLoadEvent() despues de haber creado un objeto AppAdmin
	
	Ejemplo:
	MochiKit.DOM.addLoadEvent(
		appAdmin.addGraph({
				width:400, 
				height:300,
				renderTo: 'content',
				divID: 'graph-1',
				canvasID: 'canvas-1',
				tipo: 'bar',
				data: [[0, 0], [1, 1], [2, 1.414], [3, 1.73], [4, 2]]
				}));
*/
AppAdmin.prototype.addGraph = function(config){

	//Creamos el div contenedor y el canvas
	var d = document;
	var graph = d.createElement("div"); //div contenedor
	var canvas = d.createElement("canvas"); //canvas, donde se dibuja la grafica
	
	canvas.id = config.canvasID;
	canvas.width = config.width;
	canvas.height = config.height;
	
	graph.id = config.divID;
	
	//Agregamos una clase de CSS al div que contiene la grafica
	graph.setAttribute("class", 'graph'); //For Most Browsers
	graph.setAttribute("className", 'graph'); //For IE; harmless to other browsers.
	
	//Selector que nos permite acceder al div a renderear con el id pasado
	var renderToSelector = '#'+config.renderTo;

	//Agregamos div y canvas al div contenedor 
	$(renderToSelector).append(graph);
	graph.appendChild(canvas);
	
	
	if(config.remoteData)
	{
		AppAdmin.request({
			url: config.url,
			data: config.params,
			success: function(msg){
		
				if(msg.success)
				{
					var dataPair = [];
					var options = { xTicks:[] };
					var x;
					var y;
					var v;
					var label;

					for( var i=0; i < msg.datos.length ; i++ )
					{
						x = parseInt(msg.datos[i].x);
						y = parseFloat(msg.datos[i].y);
						dataPair.push([  i, y ]);
						options.xTicks.push({v:i, label :msg.datos[i].label});
					}
		
					if(DEBUG) { console.log(dataPair); }
			
			
					var layout = new PlotKit.Layout(config.tipo, options);
			
					layout.addDataset("sqrt", dataPair);
					layout.evaluate();
					var canvas = MochiKit.DOM.getElement(config.canvasID);
					var plotter = new PlotKit.SweetCanvasRenderer(canvas, layout, options);
	
					MochiKit.DOM.addLoadEvent(plotter.render());
			
					config.success(msg);
				}
				else
				{
					if(DEBUG) { console.error('error de ajax en las graficas'); }
				}
			},
			failure: function(msg){
		
				if(DEBUG) { console.error('error de ajax en las graficas'); }
			}
			});
	
	}
	else
	{
		var layout = new PlotKit.Layout(config.tipo, {});
			
		layout.addDataset("sqrt", config.data);
		layout.evaluate();
		var canvas = MochiKit.DOM.getElement(config.canvasID);
		var plotter = new PlotKit.SweetCanvasRenderer(canvas, layout, {});

		MochiKit.DOM.addLoadEvent(plotter.render());
	}

}


/*
	addGraph Funcion para agregar graficas a un div especifico con un borde y titulo especificado
	
	NOTA: funcion casi igual a la anterior, pero se le agrega en config una variable title que aparecera 
		arriab de la grafica
---------------------------------------------------------------------------------------------------------------
*/
AppAdmin.prototype.addGraphWithTitle = function(config){

	//Creamos el div contenedor y el canvas
	var d = document;
	var graph = d.createElement("div"); //div contenedor
	var canvas = d.createElement("canvas"); //canvas, donde se dibuja la grafica
	var wrapper = d.createElement("div");
	var title = d.createElement("div");
	
	wrapper.id = "wrapper"+"-"+config.divID;
	title.id = "title"+"-"+config.divID;
	wrapper.style.width = config.width+"px";
	title.style.width = config.width+"px";
	
	canvas.id = config.canvasID;
	canvas.width = config.width;
	canvas.height = config.height;
	
	graph.id = config.divID;
	
	//Agregamos una clase de CSS al div que contiene la grafica
	graph.setAttribute("class", 'graph'); //For Most Browsers
	graph.setAttribute("className", 'graph'); //For IE; harmless to other browsers.
	
	//Selector que nos permite acceder al div a renderear con el id pasado
	var renderToSelector = '#'+config.renderTo;
	var wrapperSelector = '#'+wrapper.id;
	var titleSelector = '#'+title.id;
	var graphSelector = '#'+graph.id;

	//Agregamos div y canvas al div contenedor 
	$(renderToSelector).append(wrapper);
	$(wrapperSelector).append(title);
	$(wrapperSelector).append(graph);
	graph.appendChild(canvas);
	
	$(titleSelector).html(config.title);
	
	$(wrapperSelector).addClass('wrapper-graph');
	$(titleSelector).addClass('title-graph');
	
	
	//Si remoteData es verdadero, sacaremos los datos de la grafica haciendo un ajax
	if(config.remoteData)
	{
		AppAdmin.request({
			url: config.url,
			data: config.params,
			success: function(msg){
		
				if(msg.success)
				{
					var dataPair = [];
					var options = { xTicks:[] };
					var x;
					var y;
					var v;
					var label;

					for( var i=0; i < msg.datos.length ; i++ )
					{
						x = parseInt(msg.datos[i].x);
						y = parseFloat(msg.datos[i].y);
						dataPair.push([  i, y ]);
						options.xTicks.push({v:i, label :msg.datos[i].label});
					}
		
					if(DEBUG) { console.log(dataPair); }
			
			
					var layout = new PlotKit.Layout(config.tipo, options);
			
					layout.addDataset("sqrt", dataPair);
					layout.evaluate();
					var canvas = MochiKit.DOM.getElement(config.canvasID);
					var plotter = new PlotKit.SweetCanvasRenderer(canvas, layout, options);
	
					MochiKit.DOM.addLoadEvent(plotter.render());
			
					config.success(msg);
				}
				else
				{
					if(DEBUG) { console.error('error de ajax en las graficas'); }
					$(graphSelector).html("<p><table><tr><td><img src='../media/admin/cross.png' /></td><td>No se pudo cargar la grafica</td></tr></p>");
				}
			},
			failure: function(msg){
		
				if(DEBUG) { console.error('error de ajax en las graficas'); }
				$(graphSelector).html("<p><table><tr><td><img src='../media/admin/cross.png' /></td><td>No se pudo cargar la grafica</td></tr></p>");
			}
			});
	
	}
	else
	{
		var layout = new PlotKit.Layout(config.tipo, {});
			
		layout.addDataset("sqrt", config.data);
		layout.evaluate();
		var canvas = MochiKit.DOM.getElement(config.canvasID);
		var plotter = new PlotKit.SweetCanvasRenderer(canvas, layout, {});

		MochiKit.DOM.addLoadEvent(plotter.render());
	}

}

AppAdmin.prototype.createLogoutMessage = function(){
	
	//Creo un div para usarse como dialogo, este se reciclara
	var d = document;
	var dialogo = d.createElement("div");
	dialogo.id = "dialogo";
	
	
	//Agregamos el div del dialogo reusable
	$('#main').append(dialogo);
	
	//Creamos boton y asignamos el listener
	//$("#boton-salir").button();
	$("#boton-salir").click(function(){
	
		$('#dialogo').html("Esta seguro que desea abandonar el sistema?");
		$('#dialogo').dialog({
					title: 'Desea Salir',
					modal: true,
					dialogClass: 'confirm',
					buttons: {
						'Salir': function() {
							//Hacer algo al salir
							$(this).dialog('close');
						},
						Cancel: function() {
							$(this).dialog('close');
						}
					}
				});
		
	});
}

//Funcion estatica para hacer peticiones
AppAdmin.request = function(config){

	$.ajax({
		type: 'POST',
		url: config.url,
		data: config.data,
		success: function(msg){
		
			var data = eval("("+msg+")");
			
			config.success(data);
		},
		failure: function(msg){
			var data = eval("("+msg+")");
			
			config.failure(data);
		}
	});

}
