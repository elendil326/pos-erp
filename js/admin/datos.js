/*
	Caffeina 2010
	
	Javascript para manejar los datos sin procesar
*/

Datos = function(){

	$('#content').html("");
	this.loadSettings();
	
	
	//testing grids
	this.loadDataGrid({
			renderTo: 'content',
			title: 'Ventas Agosto',
			width: '800'
			});


	this.loadDataGrid({
			renderTo: 'content',
			title: 'Ventas Octubre',
			width: 800
			});
}

Datos.prototype.loadSettings = function(){

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
	
	$('#fecha-inicio-input').datepicker();
	$('#fecha-final-input').datepicker();
	
	/*====================fechas fin==============================================*/

}


/*
	loadDataGrid funcion para crear una tabla con datos sin procesar provenientes de la base de datos
	-----------------------------------------------------------------------------------------------------
	@param config objeto javascript con la configuracion del del grid
		-> id : identificador del grid
		-> renderTo : div id valido donde se agregara la tabla
		-> columns: array con los titulos de las columnas
		-> data:   datos de las columnas, deben contener los nombres de columans como los identificadores de los valores. 
			Ej. columns: ['id', 'nombre'], data: [{id: 1, nombre: 'juan'}, {{id: 2, nombre: 'jose'}}]
		-> title: titulo del grid
		-> width: ancho de la tabla
		-> url : de donde sacar los datos
		
	TODO: work in progress

*/
Datos.prototype.loadDataGrid = function(config){

	//Creamos divs y tabla donde estara contenida la tabla
	var d = document;
	var tableGrid = d.createElement('table');
	var wrapperPager = d.createElement('div');
	
	var randomNum = Math.round(Math.random()*10000);
	tableGrid.id = "table-"+randomNum;
	wrapperPager.id = "pager-"+randomNum;

	var selectorRenderTo = '#'+config.renderTo;
	var selectorTableGrid = '#'+tableGrid.id;
	var selectorWrapperPager = '#'+wrapperPager.id;
	
	//Los agregamos al div especificado en renderTo
	$(selectorRenderTo).append(tableGrid);
	$(selectorRenderTo).append(wrapperPager);
		
	// Creamos grid con la libreria
	// documentacion http://www.trirand.com/jqgridwiki/doku.php?id=wiki:options
	//demos http://trirand.com/blog/jqgrid/jqgrid.html
	jQuery(selectorTableGrid).jqGrid({
	   	url:config.url,
		datatype: "json",
	   	colNames:['id','Nombre', 'Direccion', 'Compras','Pagadas','Saldo',],
	   	colModel:[
	   		/*
	   		{name:'id',index:'id', width:55},
	   		{name:'invdate',index:'invdate', width:90},
	   		{name:'name',index:'name asc, invdate', width:100},
	   		{name:'amount',index:'amount', width:80, align:"right"},
	   		{name:'tax',index:'tax', width:80, align:"right"},		
	   		{name:'total',index:'total', width:80,align:"right"},		
	   		{name:'note',index:'note', width:150, sortable:false}
	   		*/
	   		{name: 'id', index: 'id'},
	   		{name: 'Nombre', index: 'Nombre'},
	   		{name: 'Direccion', index: 'Direccion'},
	   		{name: 'Compras', index: 'Compras'},
	   		{name: 'Pagadas', index: 'Pagadas'},
	   		{name: 'Saldo', index: 'Saldo'}
	   	],
	   	rowNum:10,
	   	rowList:[10,20,30],
	   	pager: selectorWrapperPager,
	   	sortname: 'id',
	    viewrecords: true,
	    sortorder: "desc",
	    caption: config.title,
	    width : config.width,
	    shrinkToFit : true,
	    altRows: true
	});
	jQuery(selectorTableGrid).jqGrid('navGrid',selectorWrapperPager,{edit:false,add:false,del:false});
}
