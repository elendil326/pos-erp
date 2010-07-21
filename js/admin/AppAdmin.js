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
