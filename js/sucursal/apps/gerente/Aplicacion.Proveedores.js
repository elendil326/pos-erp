
Aplicacion.Proveedores = function (  ){

	
	this.constructor = function (){
		cargarListaDeProveedores();
	}
	
	
	var listaDeProveedores;
	
	var cargarListaDeProveedores = function(){
		
		this.listaDeProveedores = 8;
		
	}
	


	return this;
}







Aplicacion.Proveedores.prototype.getConfig = function (){
	return {
	    text: 'Proveedores',
	    items: [{
	        text: 'Lista de proveedores',
	        card: null,
	        leaf: true
	    },
	    {
	        text: 'Comprar a proveedor',
	        card: null,
	        leaf: true
	    }]
	};
};



























POS.Apps.push( new Aplicacion.Proveedores() );






