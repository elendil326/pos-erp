
/* --------------------------------------------
	Application Main Entry Point
-------------------------------------------- */

var adminApp;
var DEBUG;


$(document).ready(function() {
	DEBUG = true;
	
	//iniciar con el appLogin
	appAdmin = new AppAdmin();

	//ejemplo de cargar grafica con datos hardcoded	
	appAdmin.addGraph({
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
	appAdmin.addGraph({
			width:350, 
			height:150,
			renderTo: 'content',
			divID: 'graph-2',
			canvasID: 'canvas-2',
			tipo: 'pie',
			remoteData: true,
			url: "../serverProxy.php",
			params: {dateRange : 'mes', method: 'graficaVentasContado'},
			success: function(msg){
					
					alert(msg.success);
				},
			failure: function(msg){
			
			}
			});

});
//AJAX EJEMPPLO
//test ajax
	/*
	AppAdmin.request({
		url: "../serverProxy.php",
		data: {test : 'bla', method: 'reporteClientesTodos'},
		success: function(msg){
			
			
			alert("Data retrieved: "+msg.success);
		}
	});*/
