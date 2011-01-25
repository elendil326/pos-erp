
Aplicacion.Operaciones = function (  ){





	return this._init();
}




Aplicacion.Operaciones.prototype._init = function (){

    if(DEBUG){
		console.log("Operaciones: construyendo");
    }
    
    Aplicacion.Operaciones.currentInstance = this;
	
	return this;
};




Aplicacion.Operaciones.prototype.getConfig = function (){
	return {
	    text: 'Operaciones',
	    cls: 'launchscreen',
	    items: [{
	        text: 'Vender a sucursal',
	        card: null,
	        leaf: true
	    },
	    {
	        text: 'Cobrar a sucursal',
	        card: null,
	        leaf: true
	    },
		{
	        text: 'Prestar efectivo',
	        card: null,
	        leaf: true
	    }]
	};
};



























POS.Apps.push( new Aplicacion.Operaciones() );






