/*
	Caffeina 2010
	
	Javascript para manejar los reportes
*/


/**
*	Constructor, limpia el div de contenido principal "content" y carga los resumenes y settings.
*	Ademas agrega los elementos necesarios al DOM para la estructura basica de la pagina de reportes
*
*/
Reports = function(){

	//Borramos el contenido del div principal
	$('#content').html("");
	this.loadSettings();
	this.loadResumen();
	//this.loadCharts();
	
	var d = document;
	
	var divGraficas = d.createElement('div');
	divGraficas.id = "graficas-render";
	divGraficas.style.width = "100%";
	
	var divDatos = d.createElement('div');
	divDatos.id = "datos-render";
	
	$('#content').append(divDatos);
	$('#content').append(divGraficas);
	
	
	Reports.currentInstance = this;
}


/**
*	Funcion para cargar los diferentes settings para generar reportes
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
						
	$('#boton-aplicar-sucursal-reporte').html("Aplicar");
	$('#boton-aplicar-sucursal-reporte').button();
	$('#boton-aplicar-sucursal-reporte').click(function(){
							Reports.currentInstance.applySucursal();
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
	$('#configuracion-reportes').addClass('configuracion');
	

	// estructura del dropdown menu
	$('#radios-tipo-reporte').html('<ul id="nav">\
						<li><a href="#">Ventas</a>\
							<ul>\
								<li><a href="#" onclick="Reports.currentInstance.loadVentasTodas()">Mostrar todas</a></li>\
								<li><a href="#" onclick="Reports.currentInstance.loadVentasCreditoReport()">Cr&eacute;dito</a></li>\
								<li><a href="#" onclick="Reports.currentInstance.loadVentasContadoReport()">Contado</a></li>\
								<li><a href="#">Por vendedor</a></li>\
								<li><a href="#">Por sucursal</a></li>\
								<li><a href="#">Por producto</a></li>\
							</ul>\
							<div class="clear"></div>\
						</li>\
						<li><a href="#">Compras</a>\
							<ul>\
								<li><a href="#" onclick="Reports.currentInstance.loadClientesComprasTodasReport()">Mostrar todas</a></li>\
								<li><a href="#" >Credito</a></li>\
								<li><a href="#">Credito deudas</a></li>\
								<li><a href="#">Credito pagado</a></li>\
							</ul>\
							<div class="clear"></div>\
						</li>\
						<li><a href="#">Clientes</a>\
						<ul>\
							<li><a href="#" onclick="Reports.currentInstance.loadClientesReport()">Mostrar todos</a></li>\
							<li><a href="#" onclick="Reports.currentInstance.loadClientesDebenReport()">Deudores</a></li>\
						</ul>\
							<div class="clear"></div>\
						</li>\
						<li><a href="#" onclick="Reports.currentInstance.loadGastosReport()">Gastos</a></li>\
						<li><a href="#" onclick="Reports.currentInstance.loadIngresosReport()">Ingresos</a></li>\
						<li><a href="#">Personal</a></li>\
					</ul>\
					<div class="clear"></div>\
				');
				
	$('#nav li').hover(
		function () {
			//show its submenu
			$('ul', this).slideDown(200);

		}, 
		function () {
			//hide its submenu
			$('ul', this).slideUp(200);			
		}
	);
}


/**
*	Funcion para cargar graficas de ejemplo, no deberia ser llamada.
*/
Reports.prototype.loadCharts = function(){
	
	var d = document;
	
	var graphWrapper = d.createElement('div');
	graphWrapper.id = "graph-wrapper-reporte";
	graphWrapper.style.width = "100%";
	graphWrapper.style.height = "auto";
	
	$('#content').append(graphWrapper);
	
	MochiKit.DOM.addLoadEvent(
		appAdmin.addGraphWithTitle({
				title:'Ventas Semanal',
				width:350, 
				height:150,
				//renderTo: 'content',
				renderTo: graphWrapper.id,
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
				renderTo: graphWrapper.id,
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
				renderTo: graphWrapper.id,
				divID: 'graph-6',
				canvasID: 'canvas-6',
				tipo: 'line',
				data: [[0, 3], [1, 1.3], [2, 1.414], [3, 1.73], [4, 2]]
				}));


	var selectorWrapper = '#'.graphWrapper.id;
	var divclear = d.createElement('div');
	divclear.style.clear = 'both';
	$(selectorWrapper).append(divclear);
}

/**
*	Funcion que pretende cargar un ligero resumen de informacion importante, con el formato de MVX (Most Valuable [something])
*
*/
Reports.prototype.loadResumen = function(){

	var d = document;
	
	var wrapper = d.createElement('div');
	wrapper.id = "wrapper-resumen";
	
	$('#content').append(wrapper);
	
	$('#wrapper-resumen').addClass('borde-gris');
	
	//TODO: cargar aqui con AJAX datos para generar un resumen 'inteligente'
	$('#wrapper-resumen').html("<ul id='lista-cuadros'>\
	<li><div class='cuadro-resumen'><img src='../media/admin/icons/user.png' width='100' height='100' /><p>Vendedor m&aacute;s productivo</p><p id='top-vendedor' class='resumen-text'>Juan Martinez</p></div></li>\
	<li><div class='cuadro-resumen'><img src='../media/admin/icons/cart.png' width='100' height='100' /><p>Producto m&aacute;s vendido</p><p id='top-producto' class='resumen-text'>Papa Grande</p></div></li>\
	<li><div class='cuadro-resumen'><img src='../media/admin/icons/piggybank.png' width='100' height='100' /><p>Sucursal con m&aacute;s ventas</p><p id='top-sucursal' class='resumen-text'>Central de Abastos</p></div></li>\
	<li><div class='cuadro-resumen'><img src='../media/admin/icons/client.png' width='100' height='100' /><p>Cliente con m&aacute;s compras</p><p id='top-cliente' class='resumen-text'>Oscar Hernandez</p></div></li>\
	</ul>\
	<div style='clear:both'></div>\
	");

	$('.resumen-text').html('<img src="../media/admin/load.gif" />');


	//sacamos el top seller
	AppAdmin.request({
			url: '../proxy.php',
			data: "method=vendedorMasProductivo",
			success: function(data){
			
				//alert("nombre "+data.datos[0].nombre);
				if(DEBUG) { console.log(data);}
				
				
				if(data.success)
				{
					$('#top-vendedor').fadeOut('slow', function(){
										
										$('#top-vendedor').html("<b>"+data.datos[0].nombre+"</b>");
										$('#top-vendedor').fadeIn();
									});
				}
				else
				{
					$('#top-vendedor').html("<b>No hay datos</b>");
				}
			},
			failure: function(data){
				$('#top-vendedor').html("<b>Error: No hay datos</b>");
			}
		});
		
	//sacamos el top product
	AppAdmin.request({
			url: '../proxy.php',
			data: "method=productoMasVendido",
			success: function(data){
			
				//alert("nombre "+data.datos[0].nombre);
				if(data.success)
				{
					$('#top-producto').fadeOut('slow', function(){
										
											$('#top-producto').html("<b>"+data.datos[0].nombre+"</b>");
											$('#top-producto').fadeIn();
										});
				}
				else
				{
					$('#top-producto').html("<b>No hay datos</b>");
				}
			},
			failure: function(data){
				$('#top-producto').html("<b>Error: No hay datos</b>");
			}
		});
		
	//sacamos el top sucursal
	AppAdmin.request({
			url: '../proxy.php',
			data: "method=sucursalVentasTop",
			success: function(data){
			
				//alert("nombre "+data.datos[0].nombre);
				if(data.success)
				{
					$('#top-sucursal').fadeOut('slow', function(){
									$('#top-sucursal').html("<b>"+data.datos[0].nombre+"</b>");
									$('#top-sucursal').fadeIn();
									});
				}
				else
				{
					$('#top-sucursal').html("<b>No hay datos</b>");
				}
			},
			failure: function(data){
				$('#top-sucursal').html("<b>Error: No hay datos</b>");
			}
		});
		
	//sacamos el top sucursal
	AppAdmin.request({
			url: '../proxy.php',
			data: "method=clienteComprasTop",
			success: function(data){
			
				//alert("nombre "+data.datos[0].nombre);
				if(data.success)
				{
					$('#top-cliente').fadeOut('slow', function(){
										
										$('#top-cliente').html("<b>"+data.datos[0].nombre+"</b>").fadeIn('slow');
										$('#top-cliente').fadeIn();
									});
					
				}
				else
				{
					$('#top-cliente').html("<b>No hay datos</b>");
				}
			},
			failure: function(data){
				$('#top-cliente').html("<b>Error: No hay datos</b>");
			}
		});
	

}

// Listener para el boton aplicar en la configuracion de periodo del reporte
Reports.prototype.applyPeriodo = function(){

	alert('click');
}

Reports.prototype.applySucursal = function(){

	alert('click aplicar sucursal');
}


//	==================	VENTAS =======================================
/**
*	Carga los datos y graficas de todas las ventas
*
*/
Reports.prototype.loadVentasTodas = function(){

	this.cleanDivs();

	

	//ejemplo cargar grafica mediante datos json
	appAdmin.addGraphWithTitle({
			title: 'Ventas de la &uacute;ltima Semana',
			width:324, 
			height:150,
			renderTo: 'graficas-render',
			divID: 'graph-ventas-0',
			canvasID: 'canvas-ventas-0',
			tipo: 'bar',
			remoteData: true,
			url: "../proxy.php",
			params: {dateRange : 'semana', method: 'graficaVentas'},
			success: function(msg){
					
					//alert(msg.success);
				},
			failure: function(msg){
			
			}
			});
			
	appAdmin.addGraphWithTitle({
			title: 'Ventas del &uacute;ltimo Mes',
			width:325, 
			height:150,
			renderTo: 'graficas-render',
			divID: 'graph-ventas-1',
			canvasID: 'canvas-ventas-1',
			tipo: 'bar',
			remoteData: true,
			url: "../proxy.php",
			params: {dateRange : 'mes', method: 'graficaVentas'},
			success: function(msg){
					
					//alert(msg.success);
				},
			failure: function(msg){
			
			}
			});
			
	appAdmin.addGraphWithTitle({
			title: 'Ventas del &uacute;ltimo A&ntilde;o',
			width:325, 
			height:150,
			renderTo: 'graficas-render',
			divID: 'graph-ventas-2',
			canvasID: 'canvas-ventas-2',
			tipo: 'bar',
			remoteData: true,
			url: "../proxy.php",
			params: {dateRange : 'year', method: 'graficaVentas'},
			success: function(msg){
					
					//alert(msg.success);
				},
			failure: function(msg){
			
			}
			});

	Datos.loadDataGrid2({
			renderTo: 'datos-render',
			title: 'Ventas',
			width: '100%',
			url: '../proxy.php',
			data: 'method=reporteClientesCompras_jgrid',
			addNewGrid: false,
			sortname: 'id',
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

}



// ======================	CLIENTES 	========================================
Reports.prototype.loadClientesDebenReport = function(){

	this.cleanDivs();

	Datos.loadDataGrid2({
			renderTo: 'datos-render',
			title: 'Clientes Deben',
			width: '100%',
			url: '../proxy.php',
			data: 'method=reporteClientesDeben_jgrid',
			addNewGrid: false,
			sortname: 'id',
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

}

Reports.prototype.loadClientesReport = function(){

	this.cleanDivs();

	Datos.loadDataGrid2({
			renderTo: 'datos-render',
			title: 'Clientes',
			width: '100%',
			url: '../proxy.php',
			data: 'method=reporteClientesTodos_jgrid',
			addNewGrid: false,
			sortname: 'id',
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
}



Reports.prototype.loadClientesComprasTodasReport = function(){

	this.cleanDivs();
	
	//ejemplo cargar grafica mediante datos json
	appAdmin.addGraphWithTitle({
			title: 'Compras de la &uacute;ltima Semana',
			width:325, 
			height:150,
			renderTo: 'graficas-render',
			divID: 'graph-0',
			canvasID: 'canvas-0',
			tipo: 'bar',
			remoteData: true,
			url: "../proxy.php",
			params: {dateRange : 'semana', method: 'graficaCompras'},
			success: function(msg){
					
					//alert(msg.success);
				},
			failure: function(msg){
			
			}
			});


	//ejemplo cargar grafica mediante datos json
	appAdmin.addGraphWithTitle({
			title: 'Compras del &uacute;ltimo Mes',
			width:325, 
			height:150,
			renderTo: 'graficas-render',
			divID: 'graph-1',
			canvasID: 'canvas-1',
			tipo: 'bar',
			remoteData: true,
			url: "../proxy.php",
			params: {dateRange : 'mes', method: 'graficaCompras'},
			success: function(msg){
					
					//alert(msg.success);
				},
			failure: function(msg){
			
			}
			});
			
	appAdmin.addGraphWithTitle({
			title: 'Compras del &uacute;ltimo A&ntilde;o',
			width:324, 
			height:150,
			renderTo: 'graficas-render',
			divID: 'graph-2',
			canvasID: 'canvas-2',
			tipo: 'bar',
			remoteData: true,
			url: "../proxy.php",
			params: {dateRange : 'year', method: 'graficaCompras'},
			success: function(msg){
					
					//alert(msg.success);
				},
			failure: function(msg){
			
			}
			});


}

Reports.prototype.loadVentasCreditoReport = function(config){

	this.cleanDivs();

	//reporteClientesComprasCredito_jgrid
	Datos.loadDataGrid2({
			renderTo: 'datos-render',
			title: 'Ventas a cr&eacute;dito',
			width: '100%',
			url: '../proxy.php',
			data: 'method=reporteClientesComprasCredito_jgrid',
			addNewGrid: false,
			sortname: 'v.fecha',
			colModel: [
				{display: 'Venta', name : 'id_venta', width : 50, sortable : true, align: 'left'},
				{display: 'Total', name : 'Total', width : 100, sortable : true, align: 'left'},
				{display: 'Pagado', name : 'Pagado', width : 80, sortable : true, align: 'left'},
				{display: 'Debe', name : 'Debe', width : 100, sortable : true, align: 'left'},
				{display: 'Nombre', name : 'Nombre', width : 300, sortable : true, align: 'left'},
				{display: 'Fecha', name : 'Fecha', width : 100, sortable : true, align: 'left'}
			],
			searchitems: [
				{display: 'Nombre', name : 'Nombre', isdefault: true},
				{display: 'Venta', name : 'id_venta'},
				{display: 'Fecha', name : 'Fecha'}
			]
			});
			
	appAdmin.addGraphWithTitle({
			title: 'Ventas a cr&eacute;dito de la &uacute;ltima Semana',
			width:325, 
			height:150,
			renderTo: 'graficas-render',
			divID: 'graph-0',
			canvasID: 'canvas-0',
			tipo: 'bar',
			remoteData: true,
			url: "../proxy.php",
			params: {dateRange : 'semana', method: 'graficaVentasCredito'},
			success: function(msg){
					
					//alert(msg.success);
				},
			failure: function(msg){
			
			}
			});
			
	appAdmin.addGraphWithTitle({
			title: 'Ventas a cr&eacute;dito del &uacute;ltimo Mes',
			width:325, 
			height:150,
			renderTo: 'graficas-render',
			divID: 'graph-1',
			canvasID: 'canvas-1',
			tipo: 'bar',
			remoteData: true,
			url: "../proxy.php",
			params: {dateRange : 'mes', method: 'graficaVentasCredito'},
			success: function(msg){
					
					//alert(msg.success);
				},
			failure: function(msg){
			
			}
			});


	appAdmin.addGraphWithTitle({
			title: 'Ventas a cr&eacute;dito del &uacute;ltimo A&ntilde;o',
			width:324, 
			height:150,
			renderTo: 'graficas-render',
			divID: 'graph-2',
			canvasID: 'canvas-2',
			tipo: 'bar',
			remoteData: true,
			url: "../proxy.php",
			params: {dateRange : 'year', method: 'graficaVentasCredito'},
			success: function(msg){
					
					//alert(msg.success);
				},
			failure: function(msg){
			
			}
			});

}

Reports.prototype.loadVentasContadoReport = function(config){

	this.cleanDivs();

	//reporteClientesComprasCredito_jgrid
	Datos.loadDataGrid2({
			renderTo: 'datos-render',
			title: 'Ventas de contado',
			width: '100%',
			url: '../proxy.php',
			data: 'method=reporteClientesComprasContado_jgrid',
			addNewGrid: false,
			sortname: 'v.fecha',
			colModel: [
				{display: 'Venta', name : 'id_venta', width : 50, sortable : true, align: 'left'},
				{display: 'Total', name : 'Total', width : 100, sortable : true, align: 'left'},
				{display: 'Nombre', name : 'Nombre', width : 300, sortable : true, align: 'left'},
				{display: 'Fecha', name : 'Fecha', width : 100, sortable : true, align: 'left'}
			],
			searchitems: [
				{display: 'Nombre', name : 'Nombre', isdefault: true},
				{display: 'Venta', name : 'id_venta'},
				{display: 'Fecha', name : 'Fecha'}
			]
			});
			
	appAdmin.addGraphWithTitle({
			title: 'Ventas de contado de la &uacute;ltima Semana',
			width:325, 
			height:150,
			renderTo: 'graficas-render',
			divID: 'graph-0',
			canvasID: 'canvas-0',
			tipo: 'bar',
			remoteData: true,
			url: "../proxy.php",
			params: {dateRange : 'semana', method: 'graficaVentasContado'},
			success: function(msg){
					
					//alert(msg.success);
				},
			failure: function(msg){
			
			}
			});
			
	appAdmin.addGraphWithTitle({
			title: 'Ventas de contado del &uacute;ltimo Mes',
			width:325, 
			height:150,
			renderTo: 'graficas-render',
			divID: 'graph-1',
			canvasID: 'canvas-1',
			tipo: 'bar',
			remoteData: true,
			url: "../proxy.php",
			params: {dateRange : 'mes', method: 'graficaVentasContado'},
			success: function(msg){
					
					//alert(msg.success);
				},
			failure: function(msg){
			
			}
			});


	appAdmin.addGraphWithTitle({
			title: 'Ventas de contado del &uacute;ltimo A&ntilde;o',
			width:324, 
			height:150,
			renderTo: 'graficas-render',
			divID: 'graph-2',
			canvasID: 'canvas-2',
			tipo: 'bar',
			remoteData: true,
			url: "../proxy.php",
			params: {dateRange : 'year', method: 'graficaVentasContado'},
			success: function(msg){
					
					//alert(msg.success);
				},
			failure: function(msg){
			
			}
			});

}

Reports.prototype.loadGastosReport = function(){

	this.cleanDivs();
	
	appAdmin.addGraphWithTitle({
			title: 'Gastos de la &uacute;ltima semana',
			width:325, 
			height:150,
			renderTo: 'graficas-render',
			divID: 'graph-0',
			canvasID: 'canvas-0',
			tipo: 'bar',
			remoteData: true,
			url: "../proxy.php",
			params: {dateRange : 'semana', method: 'graficaGastos'},
			success: function(msg){
					
					//alert(msg.success);
				},
			failure: function(msg){
			
			}
			});
	
	appAdmin.addGraphWithTitle({
			title: 'Gastos del &uacute;ltimo mes',
			width:325, 
			height:150,
			renderTo: 'graficas-render',
			divID: 'graph-1',
			canvasID: 'canvas-1',
			tipo: 'bar',
			remoteData: true,
			url: "../proxy.php",
			params: {dateRange : 'mes', method: 'graficaGastos'},
			success: function(msg){
					
					//alert(msg.success);
				},
			failure: function(msg){
			
			}
			});
	
	
	appAdmin.addGraphWithTitle({
			title: 'Gastos del &uacute;ltimo A&ntilde;o',
			width:324, 
			height:150,
			renderTo: 'graficas-render',
			divID: 'graph-2',
			canvasID: 'canvas-2',
			tipo: 'bar',
			remoteData: true,
			url: "../proxy.php",
			params: {dateRange : 'year', method: 'graficaGastos'},
			success: function(msg){
					
					//alert(msg.success);
				},
			failure: function(msg){
			
			}
			});

}


Reports.prototype.loadIngresosReport = function(){

	this.cleanDivs();
	
	appAdmin.addGraphWithTitle({
			title: 'Ingresos de la &uacute;ltima semana',
			width:325, 
			height:150,
			renderTo: 'graficas-render',
			divID: 'graph-0',
			canvasID: 'canvas-0',
			tipo: 'bar',
			remoteData: true,
			url: "../proxy.php",
			params: {dateRange : 'semana', method: 'graficaIngresos'},
			success: function(msg){
					
					//alert(msg.success);
				},
			failure: function(msg){
			
			}
			});
	
	appAdmin.addGraphWithTitle({
			title: 'Ingresos del &uacute;ltimo mes',
			width:325, 
			height:150,
			renderTo: 'graficas-render',
			divID: 'graph-1',
			canvasID: 'canvas-1',
			tipo: 'bar',
			remoteData: true,
			url: "../proxy.php",
			params: {dateRange : 'mes', method: 'graficaIngresos'},
			success: function(msg){
					
					//alert(msg.success);
				},
			failure: function(msg){
			
			}
			});
	
	
	appAdmin.addGraphWithTitle({
			title: 'Ingresos del &uacute;ltimo A&ntilde;o',
			width:324, 
			height:150,
			renderTo: 'graficas-render',
			divID: 'graph-2',
			canvasID: 'canvas-2',
			tipo: 'bar',
			remoteData: true,
			url: "../proxy.php",
			params: {dateRange : 'year', method: 'graficaIngresos'},
			success: function(msg){
					
					//alert(msg.success);
				},
			failure: function(msg){
			
			}
			});

}


Reports.prototype.cleanDivs = function(){

	$('#graficas-render').html("");
	$('#datos-render').html("");

}
