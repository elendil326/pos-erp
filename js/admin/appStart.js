
/* --------------------------------------------
	Application Main Entry Point
-------------------------------------------- */

var adminApp;
var DEBUG;


$(document).ready(function() {
	DEBUG = true;
	
	//iniciar con el appLogin
	appAdmin = new AppAdmin();

	//testeo de graphs
	MochiKit.DOM.addLoadEvent(
		appAdmin.addGraph({
				width:350, 
				height:150,
				renderTo: 'content',
				divID: 'graph-1',
				canvasID: 'canvas-1',
				tipo: 'bar',
				data: [[0, 1258], [1, 986], [2, 369], [3, 865], [4, 1025]]
				}));
						
	MochiKit.DOM.addLoadEvent(
		appAdmin.addGraph({
				width:350, 
				height:150,
				renderTo: 'content',
				divID: 'graph-2',
				canvasID: 'canvas-2',
				tipo: 'line',
				data: [[0, 300], [1, 259], [2, 452], [3, 126], [4, 352], [5, 258], [6, 154], [7, 356], [8, 486]]
				}));
				
	MochiKit.DOM.addLoadEvent(
		appAdmin.addGraph({
				width:350, 
				height:150,
				renderTo: 'content',
				divID: 'graph-3',
				canvasID: 'canvas-3',
				tipo: 'pie',
				data: [[0, 0], [1, 1], [2, 1.414], [3, 1.73], [4, 2]]
				}));
				
	MochiKit.DOM.addLoadEvent(
		appAdmin.addGraph({
				width:350, 
				height:150,
				renderTo: 'content',
				divID: 'graph-4',
				canvasID: 'canvas-4',
				tipo: 'bar',
				data: [[0, 0], [1, 1], [2, 1.414], [3, 1.73], [4, 2]]
				}));

});	
