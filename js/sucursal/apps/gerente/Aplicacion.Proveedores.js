
Aplicacion.Proveedores = function(){

	/**
	  *  Fields
	  *
	  **/
	
	/**
	  * Contiene el store con la lista de los proveedores.
	  * @access private
	  **/
	var listaDeProveedores;
	
	/**
	  * Referencia a todas las tarjetas de esta aplicacion.
	  *
	  * 
	  **/
	this.cards = {
		//lista de proveedores
		lista : null,
		
		//detalles del proveedor
		detalles : null
	}
	
	
	/**
	  * Cargar lista de Proveedores
	  *
	  * Cargar la lista de proveedores para agregarlos al store.
	  *
	  * @access private
	  **/
	var cargarListaDeProveedores = function(){

	    Ext.Ajax.request({
	        url: '../proxy.php',
	        scope : this,
	        params : {
	            action : 900
	        },
	        success: function(response, opts) {
	            try{
	                proveedores = Ext.util.JSON.decode( response.responseText );             
	            }catch(e){
	                return POS.error(e);
	            }
	
				if(DEBUG){
					console.log( "Lista de proveedores ha regresado." );
				}
				
				listaDeProveedores = proveedores.payload;

				
	        },
	        failure: function( response ){
	            POS.error( response );
	        }
	    });
	

		
	}




	this.mostrarDetalles = function( id_proveedor){
		console.log("mostrando los detalles del proveeedor " + id_proveedor)
	}




	/*
	 *	Init the application
	 *
	 **/
	
	if(DEBUG){
		console.log("Construyendo aplicacion de proveedores");
	}
	
	

	
	
	/**
	  * Registrar el modelo que utilizara este store
	  **/
	Ext.regModel('listaDeProveedoresModel', {
	    fields: [
	        { name: 'id_autorizacion',     type: 'int'},
	        { name: 'estado',              type: 'int'},
	        { name: 'fecha_peticion',      type: 'date'}
	    ]
	});
	
	//declarar el store
	listaDeProveedores = new Ext.data.Store({
	    model: 'listaDeProveedoresModel' ,
	    sorters: 'fecha_peticion',
	    getGroupString : function(record) {
	        return record.nombre;
	    }
	});
	
	cargarListaDeProveedores();

	//crar la tarjeta de la lista de proveedores
	this.cards.lista = new Ext.Panel({
        layout: 'fit' ,
        items: [{ html : "<div style='height: 100%;' id='MosaicoProveedores'></div>" }],
		listeners : {
			"show" : function (){
				
				items = [];
				
				for( a = 0 ; a < listaDeProveedores.length ; a++ ){
					items.push({
						title : listaDeProveedores[a].nombre,
						image : '../media/LorryGreen128.png'
					});
				};
				
				m = new Mosaico({
					renderTo : 'MosaicoProveedores',
					callBack : this.mostrarDetalles,
					items: items
				});
			}
		}
    });



	//crear la tarjeta de detalles del proveedor
	this.cards.detalles = new Ext.Panel();
}





Aplicacion.Proveedores.prototype.getConfig = function (){
	return {
	    text: 'Proveedores',
        card: this.cards.lista,	
	    items: [{
	        text: 'Lista de proveedores',
	        card: this.cards.lista,
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






