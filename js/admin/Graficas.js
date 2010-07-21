/**
*	@fileoverview	Javascript que contiene funciones para generar graficas usando PlotKit
*	{@link http://media.liquidx.net/js/plotkit-doc/PlotKit.QuickStart.html } PlotKit QuickStart	
*
*	@author	Rene Michel rene@caffeina.mx
*	@version 0.1
*/


/**
*	No hace nada, no es necesario instanciar, los metodos son estaticos
*
*/
Graficas = function(){

}


/**
*	addGraph Funcion para agregar graficas a un div especifico
*
*	@param config Object Objeto para cambiar caracteristicas de la grafica, configuracion inicial:
*		-> width -ancho de la grafica
*		-> height - alto de la grafica
*		-> renderTo - un div donde queremos que se renderee la grafica
*		-> divID -el id del div donde se rendereara.. se necesita <div id="algo"><canvas id="nombre"></canvas></div>
*		-> canvasName -nombre del canvas
*		-> tipo - el tipo de grafica [pie|line|bar]
*		-> data - un array con arreglos en el formato [ [x1,y1], [x2,y2]]
*		-> remoteData - bool que nos indica si cargaremos datos desde un ajax
*		-> url - request url
*		-> params - parametros para enviar con el request
*		-> success - handler para cuando el request regresa con exito
*		-> failure - handler para cuando el request regresa con un fallo
*		TODO: configuracion avanzada
*		
*	Ejemplo:
*	MochiKit.DOM.addLoadEvent(
*		appAdmin.addGraph({
*				width:400, 
*				height:300,
*				renderTo: 'content',
*				divID: 'graph-1',
*				canvasID: 'canvas-1',
*				tipo: 'bar',
*				data: [[0, 0], [1, 1], [2, 1.414], [3, 1.73], [4, 2]]
*				}));
*/
Graficas.addGraph = function(config){

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
		Utils.request({
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

/**
*	addGraphWithTitle Funcion para agregar graficas a un div especifico con un borde y titulo especificado
*	
*	NOTA: funcion casi igual a la anterior, pero se le agrega en config una variable title que aparecera 
*		arriab de la grafica
*
*	@param	config Object Objecto javascript que contiene los diferentes parametros para configurar la graficas
*	@see	#addGraph
*--------------------------------------------------------------------------------------------------------------
*/
Graficas.addGraphWithTitle = function(config){
	
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
		Utils.request({
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
