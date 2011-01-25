
Aplicacion.Proveedores = function (  ){





	return this._init();
}




Aplicacion.Proveedores.prototype._init = function (){

    if(DEBUG){
		console.log("Proveedores: construyendo");
    }
    
    Aplicacion.Proveedores.currentInstance = this;
	
	return this;
};




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






