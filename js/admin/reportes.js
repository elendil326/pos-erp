/*
	Caffeina 2010
	
	Javascript para manejar los reportes
*/

Reports = function(){

	//Borramos el contenido del div principal
	$('#content').html("");
	this.loadSettings();
	this.loadResumen();
	this.loadCharts();
	
	Reports.currentInstance = this;
}


/*
	Funcion para cargar los diferentes settings para generar reportes
*/
Reports.prototype.loadSettings = function(){

	
	var d = document;
	
	//div contenedor del selector de fechas
	var divFechas = d.createElement('div');
	divFechas.id = "periodo-reporte";
	
	//input para la fecha de inicio del reporte
	var inputFechaInicio = d.createElement('input');
	inputFechaInicio.id = "fecha-inicio-input";
	inputFechaInicio.name = "fecha-inicio";
	inputFechaInicio.type = "text";
	inputFechaInicio.class = "no-border";
	inputFechaInicio.value = "Inicio";
	
	//input para la fecha de final del reporte
	var inputFechaFinal = d.createElement('input');
	inputFechaFinal.id = "fecha-final-input";
	inputFechaFinal.name = "fecha-final";
	inputFechaFinal.type = "text";
	inputFechaFinal.class = "no-border";
	inputFechaFinal.value = "Fin";

	//boton para actualizar el reporte con las fechas ingresadas
	var botonAplicarFechas = d.createElement('button');
	botonAplicarFechas.id = "boton-aplicar-fechas-reporte";
	
	
	
	
	/*===================fechas inicio==============================================*/
	$('#content').append(divFechas);
	$('#periodo-reporte').append(inputFechaInicio);
	$('#periodo-reporte').append('-');
	$('#periodo-reporte').append(inputFechaFinal);
	$('#periodo-reporte').append(inputFechaFinal);
	$('#periodo-reporte').append(botonAplicarFechas);
	
	//Agregamos clases a los inputs
	$('#fecha-inicio-input').addClass('no-border');
	$('#fecha-final-input').addClass('no-border');
	
	$('#boton-aplicar-fechas-reporte').html("Aplicar");
	$('#boton-aplicar-fechas-reporte').button();
	$('#boton-aplicar-fechas-reporte').click(function(){
							Reports.currentInstance.applyPeriodo();
						});
	
	$('#fecha-inicio-input').datepicker();
	$('#fecha-final-input').datepicker();
	
	/*====================fechas fin==============================================*/

	var divSettingsWrapper = d.createElement('div');
	divSettingsWrapper.id = "configuracion-reportes";
	
	$('#content').append(divSettingsWrapper);

	/*====================inicio radios tipo reporte==============================================*/
	//div contenedor de los radios
	var divRadioTipoReporte = d.createElement('div');
	divRadioTipoReporte.id = "radios-tipo-reporte";
	
	$('#configuracion-reportes').append(divRadioTipoReporte);
	$('#radios-tipo-reporte').html('\
					<input type="radio" id="tipo-reporte-radio-1" name="radio" /><label for="tipo-reporte-radio-1">Ventas</label>\
					<input type="radio" id="tipo-reporte-radio-2" name="radio" /><label for="tipo-reporte-radio-2">Compras</label>\
					<input type="radio" id="tipo-reporte-radio-3" name="radio" /><label for="tipo-reporte-radio-3">Personal</label>\
					<input type="radio" id="tipo-reporte-radio-4" name="radio" /><label for="tipo-reporte-radio-4">Choice 3</label>\
					');
	
	$("#radios-tipo-reporte").buttonset();
	/*====================fin radios tipo reporte==============================================*/
}


/*
	Funcion para cargar graficas
*/
Reports.prototype.loadCharts = function(){
	
	MochiKit.DOM.addLoadEvent(
		appAdmin.addGraphWithTitle({
				title:'Ventas Semanal',
				width:350, 
				height:150,
				renderTo: 'content',
				divID: 'graph-4',
				canvasID: 'canvas-4',
				tipo: 'bar',
				data: [[0, 0], [1, 1], [2, 1.414], [3, 1.73], [4, 2]]
				}));
				
	MochiKit.DOM.addLoadEvent(
		appAdmin.addGraphWithTitle({
				title:'Ventas Mensuales',
				width:350, 
				height:150,
				renderTo: 'content',
				divID: 'graph-5',
				canvasID: 'canvas-5',
				tipo: 'pie',
				data: [[0, 3], [1, 1.3], [2, 1.414], [3, 1.73], [4, 2]]
				}));
				
	MochiKit.DOM.addLoadEvent(
		appAdmin.addGraphWithTitle({
				title:'Compras Mensuales',
				width:350, 
				height:150,
				renderTo: 'content',
				divID: 'graph-6',
				canvasID: 'canvas-6',
				tipo: 'line',
				data: [[0, 3], [1, 1.3], [2, 1.414], [3, 1.73], [4, 2]]
				}));

}

/* Funcion que pretende cargar un ligero resumen de cosas importantes 

	ESTO PODRIA SER UN GRID CON ICONOS MAS FRESON, POR AHORA UNA LISTA SIMPLE...
*/
Reports.prototype.loadResumen = function(){

	var d = document;
	
	var wrapper = d.createElement('div');
	wrapper.id = "wrapper-resumen";
	
	$('#content').append(wrapper);
	
	$('#wrapper-resumen').addClass('borde-gris');
	
	//TODO: cargar aqui con AJAX datos para generar un resumen 'inteligente'
	$('#wrapper-resumen').html("\
					Resumen del periodo 2010/09/06 al 2010/10/06\
					<ul>\
						<li>Vendedor mas productivo: <b>Juan Martinez</b></li>\
						<li>Sucursal mas productiva: <b>Bodega 1 Central de abastos</b></li>\
						<li>Producto mas vendido: <b>Papa Mediana $18.00/kg</b></li>\
						<li>Mejor comprador: <b>Abarrotes La Esquina</b></li>\
					</ul>\
				");

}

// Listener para el boton aplicar en la configuracion de periodo del reporte
Reports.prototype.applyPeriodo = function(){

	alert('click');
}
