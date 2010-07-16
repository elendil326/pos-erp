/*
	Caffeina 2010
	
	Javascript para manejar los datos sin procesar
*/

Datos = function(){

	$('#content').html("");
	this.loadSettings();
	
	
	//testing grids
	/*
	Datos.loadDataGrid2({
			renderTo: 'content',
			title: 'Clientes',
			width: '100%',
			url: '../serverProxy.php',
			data: 'method=reporteClientesTodos_jgrid',
			addNewGrid: false,
			colModel: [
				{display: 'ID', name : 'id', width : 30, sortable : true, align: 'left'},
				{display: 'Nombre', name : 'nombre', width : 150, sortable : true, align: 'left'},
				{display: 'RFC', name : 'rfc', width : 100, sortable : true, align: 'left'},
				{display: 'Direccion', name : 'direccion', width : 250, sortable : true, align: 'left'},
				{display: 'Telefono', name : 'telefono', width : 250, sortable : true, align: 'left'},
				{display: 'E-mail', name : 'e-mail', width : 250, sortable : true, align: 'left'},
				{display: 'Limite credito', name : 'limite de credito', width : 100, sortable : true, align: 'left'}
			],
			searchitems: [
				{display: 'Nombre', name : 'nombre'},
				{display: 'RFC', name : 'rfc', isdefault: true},
				{display: 'Direccion', name : 'direccion'}
			]
			});

	
	
	Datos.loadDataGrid2({
			renderTo: 'content',
			title: 'Clientes Deben',
			width: '100%',
			url: '../serverProxy.php',
			data: 'method=reporteClientesDeben_jgrid',
			addNewGrid: true,
			colModel: [
				{display: 'ID', name : 'id', width : 30, sortable : true, align: 'left'},
				{display: 'Nombre', name : 'nombre', width : 300, sortable : true, align: 'left'},
				{display: 'Saldo', name : 'Saldo', width : 80, sortable : true, align: 'left'},
				{display: 'RFC', name : 'RFC', width : 100, sortable : true, align: 'left'},
				{display: 'Direccion', name : 'Direccion', width : 250, sortable : true, align: 'left'},
				{display: 'Telefono', name : 'Telefono', width : 100, sortable : true, align: 'left'},
				{display: 'E-mail', name : 'E-mail', width : 100, sortable : true, align: 'left'}
			],
			searchitems: [
				{display: 'Nombre', name : 'nombre'},
				{display: 'RFC', name : 'rfc', isdefault: true},
				{display: 'Direccion', name : 'direccion'}
			]
			});
			
	Datos.loadDataGrid2({
			renderTo: 'content',
			title: 'Clientes Compras',
			width: '100%',
			url: '../serverProxy.php',
			data: 'method=reporteClientesCompras_jgrid',
			addNewGrid: true,
			colModel: [
				{display: 'ID', name : 'id', width : 30, sortable : true, align: 'left'},
				{display: 'Nombre', name : 'Cliente', width : 300, sortable : true, align: 'left'},
				{display: 'Total', name : 'Total', width : 80, sortable : true, align: 'left'},
				{display: 'Tipo', name : 'Tipo', width : 100, sortable : true, align: 'left'},
				{display: 'Fecha', name : 'Fecha', width : 250, sortable : true, align: 'left'},
				{display: 'Sucursal', name : 'Sucursal', width : 100, sortable : true, align: 'left'}
			],
			searchitems: [
				{display: 'Nombre', name : 'nombre'},
				{display: 'RFC', name : 'rfc', isdefault: true},
				{display: 'Direccion', name : 'direccion'}
			]
			});
	*/		
	//reporteClientesCompras_jgrid
}

Datos.gridTable = "tabla-grid-generico"; // Tabla generica, accesible desde la clase Datos estaticamente

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
	
	/*
	var divSettingsWrapper = d.createElement('div');
	divSettingsWrapper.id = "configuracion-datos";
	
	$('#content').append(divSettingsWrapper);
	
	
	//div contenedor de los radios
	var divRadioTipoDatos = d.createElement('div');
	divRadioTipoDatos.id = "radios-tipo-datos";
	
	$('#configuracion-datos').append(divRadioTipoDatos);
	$('#configuracion-datos').addClass('configuracion');
	$('#radios-tipo-datos').html('\
					<input type="radio" id="tipo-datos-radio-1" name="radio" /><label for="tipo-datos-radio-1">Ventas</label>\
					<input type="radio" id="tipo-datos-radio-2" name="radio" /><label for="tipo-datos-radio-2">Compras</label>\
					<input type="radio" id="tipo-datos-radio-3" name="radio" /><label for="tipo-datos-radio-3">Personal</label>\
					<input type="radio" id="tipo-datos-radio-4" name="radio" /><label for="tipo-datos-radio-4">Clientes</label>\
					<input type="radio" id="tipo-datos-radio-5" name="radio" /><label for="tipo-datos-radio-5">Proveedores</label>\
					');
	
	$("#radios-tipo-datos").buttonset();
	*/
	
}



/*
	***DEPRECATED*** No usar esta, ya cambie el grid a uno mejor y mas flexible
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
Datos.loadDataGrid = function(config){

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
		-> addNewGrid : true para usar un div default y de esta manera siempre tener la tabla en el mismo lugar, 
				   false si queremos agregar una nueva tabla
		
	TODO: work in progress

*/
Datos.loadDataGrid2 = function(config){

	//Creamos divs y tabla donde estara contenida la tabla
	var d = document;
	var tableGrid = d.createElement('table');
	var breaktag = d.createElement('br');
	//var wrapperPager = d.createElement('div');
	
	var randomNum = Math.round(Math.random()*10000);
	
	if(config.addNewGrid){ 
		tableGrid.id = "table-"+randomNum;
	}
	else{
		tableGrid.id = Datos.gridTable;
	}	
	//wrapperPager.id = "pager-"+randomNum;

	var selectorRenderTo = '#'+config.renderTo;
	
	var selectorTableGrid = '#'+tableGrid.id;
	//var selectorWrapperPager = '#'+wrapperPager.id;
	

	//Si ya existe la tabla borramos la existente y creamos una nueva en la misma posicion
	// si la variable addNewGrid es true, se agregara una tabla despues de la ultima creada
	if(!($(selectorTableGrid).length > 0))
	{	
		$(selectorRenderTo).append(breaktag);
		
		//Los agregamos al div especificado en renderTo
		$(selectorRenderTo).append(tableGrid);
		
		$(selectorTableGrid).flexigrid(
			{
				url: config.url+'?'+config.data,
				dataType: 'json',
				colModel : config.colModel,/*
				buttons : [
				{name: 'Edit', bclass: 'edit', onpress : function(){ alert(); }},
				{name: 'Delete', bclass: 'delete', onpress : function(){ alert(); }},
				{separator: true}
				],*/
				searchitems : config.searchitems,
				sortname: config.sortname,
				sortorder: "asc",
				usepager: true,
				title: config.title,
				useRp: true,
				rp: 10,
				showTableToggleBtn: true,
				resizable: false,
				width: config.width,
				height: 370,
				singleSelect: true
			}
		);
	}
	else
	{
		
		$(".flexigrid").fadeOut('slow', function(){
					
						$(".flexigrid").remove();
						
						$(selectorTableGrid).remove();
						
						//Los agregamos al div especificado en renderTo
						$(selectorRenderTo).append(tableGrid);
						
						
						$(selectorTableGrid).flexigrid(
							{
								url: config.url+'?'+config.data,
								dataType: 'json',
								colModel : config.colModel,/*
								buttons : [
								{name: 'Edit', bclass: 'edit', onpress : function(){ alert(); }},
								{name: 'Delete', bclass: 'delete', onpress : function(){ alert(); }},
								{separator: true}
								],*/
								searchitems : config.searchitems,
								sortname: config.sortname,
								sortorder: "asc",
								usepager: true,
								title: config.title,
								useRp: true,
								rp: 10,
								showTableToggleBtn: true,
								resizable: false,
								width: config.width,
								height: 370,
								singleSelect: true
							}
						);
					});
					
	}
				
	
}
