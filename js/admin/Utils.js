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
