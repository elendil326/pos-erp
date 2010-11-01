
/* --------------------------------------------
	Application Main Entry Point
-------------------------------------------- */

var adminApp;
var DEBUG;


$(document).ready(function() {
	
	
	DEBUG = true;
	
	//iniciar con el appLogin
	appAdmin = new AppAdmin();

	//convertimos todos los botones a jquery ui
	$("button").button();
	
	new Reports();
	/*
	//ejemplo de cargar grafica con datos hardcoded	
	Graficas.addGraphWithTitle({
			title: 'test',
			width:350, 
			height:150,
			renderTo: 'content',
			divID: 'graph-1',
			canvasID: 'canvas-1',
			tipo: 'bar',
			data: [[0, 1258], [1, 986], [2, 369], [3, 865], [4, 1025]],
			remoteData: false
			});
			
	//ejemplo cargar grafica mediante datos json
	Graficas.addGraphWithTitle({
			title: 'Ventas de Contado por Mes',
			width:350, 
			height:150,
			renderTo: 'content',
			divID: 'graph-2',
			canvasID: 'canvas-2',
			tipo: 'bar',
			remoteData: true,
			url: "../serverProxy.php",
			params: {dateRange : 'year', method: 'graficaVentasContado'},
			success: function(msg){
					
					//alert(msg.success);
				},
			failure: function(msg){
			
			}
			});
		*/

});
//AJAX EJEMPPLO
//test ajax
	/*
	Utils.request({
		url: "../serverProxy.php",
		data: {test : 'bla', method: 'reporteClientesTodos'},
		success: function(msg){
			
			
			alert("Data retrieved: "+msg.success);
		}
	});*/
