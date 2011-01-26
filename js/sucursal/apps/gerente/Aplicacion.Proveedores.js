
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
	  * Para hacer referencia a este objeto desde fuera
	  *
	  *
	  *
	  **/
	Aplicacion.Proveedores.ci = this;
	
	/**
	  * Referencia a todas las tarjetas de esta aplicacion.
	  *
	  * 
	  **/
	this.cards = {
		//lista de proveedores
		lista : null,
		
		//detalles del proveedor
		detalles : null,
		
		//carrito para surtirse
		carrito : null
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

		if(DEBUG){
			console.log("mostrando los detalles del proveeedor " , id_proveedor);
		}


		thiz = Aplicacion.Proveedores.ci;

		//actualizar los datos en la tarjeta de detalles
		var tarjeta = thiz.cards.detalles;
		
		//buscar este proveedor en el arreglo
		for(var a = 0; a < listaDeProveedores.length; a++){
			if( listaDeProveedores[a].id_proveedor == id_proveedor ){
				break;
			}
		}

		if( a == listaDeProveedores.length ){
			return;
		}
		
		var proveedor = listaDeProveedores[a];
		
		tarjeta.setValues({
			nombre : proveedor.nombre,
			direccion : proveedor.direccion,
			telefono : proveedor.telefono
		});
		
        sink.Main.ui.setActiveItem( tarjeta , 'slide');
	}










	/*
	 *	Init the application
	 *
	 **/
	
	if(DEBUG){
		console.log("Construyendo aplicacion de proveedores");
	}
	
	

	

	
	cargarListaDeProveedores();


	//crear la tarjeta de detalles del proveedor
	this.cards.detalles = new Ext.form.FormPanel({  
			dockedItems: [{
	            dock: 'bottom',
	            xtype: 'toolbar',
	            items: [{
	                text: 'Regresar a lista de proveedores',
	                ui: 'back',
	                handler: function() {
	                   sink.Main.ui.setActiveItem( Aplicacion.Proveedores.ci.cards.lista , 'slide');
	                }
	            },{
	                xtype: 'spacer'
	            },{
		            text: 'Surtirse de este proveedor',
	                ui: 'action',
	                handler: function() {
					   Aplicacion.Inventario.currentInstance.surtirWizardFromProveedor = true;
	                   sink.Main.ui.setActiveItem( Aplicacion.Inventario.currentInstance.surtirWizardPanel , 'slide');
	                }
				}]
	        }],                                                    
            items: [{
                xtype: 'fieldset',
                items: [
                    new Ext.form.Text({ name: 'nombre', 	label: 'Nombre'    }),
                    new Ext.form.Text({ name: 'direccion', 	label: 'Direccion' }),
                    new Ext.form.Text({ name: 'telefono', 	label: 'Telefono'  }), 
            ]}
        ]});





	//crar la tarjeta de la lista de proveedores
	this.cards.lista = new Ext.Panel({
        layout: 'fit' ,
        items: [{ html : "<div style='height: 100%;' id='MosaicoProveedores'></div>" }],
		listeners : {
			"show" : function (){
				
				if(DEBUG){
					console.log("Creando mosaico para proveedores" , this );
				}
				
				items = [];
				
				for( a = 0 ; a < listaDeProveedores.length ; a++ ){
					items.push({
						title : listaDeProveedores[a].nombre,
						id : listaDeProveedores[a].id_proveedor,
						image : '../media/LorryGreen128.png'
					});
				};
				
				new Mosaico({
					renderTo : 'MosaicoProveedores',
					callBack : Aplicacion.Proveedores.ci.mostrarDetalles,
					items    : items
				});
			}
		}
    });




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






