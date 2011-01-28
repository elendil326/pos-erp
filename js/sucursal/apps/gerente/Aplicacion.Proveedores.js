
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
	};
	
	
	/**
	  * Refereincias a los pop-ups
	  *	
   	  *	
   	  *
	  **/		
	this.popups = {
		listaDeProductos : null
	};
	
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
				var proveedores;
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
	

		
	};




	this.mostrarDetalles = function( id_proveedor){

		if(DEBUG){
			console.log("mostrando los detalles del proveeedor " , id_proveedor);
		}


		thiz = Aplicacion.Proveedores.ci;

		//actualizar los datos en la tarjeta de detalles
		var tarjeta = thiz.cards.detalles;
		var a;
		
		//buscar este proveedor en el arreglo
		for(a = 0; a < listaDeProveedores.length; a++){
			if( listaDeProveedores[a].id_proveedor === id_proveedor ){
				break;
			}
		}

		if( a === listaDeProveedores.length ){
			return;
		}
		
		var proveedor = listaDeProveedores[a];
		
		tarjeta.setValues({
			nombre : proveedor.nombre,
			direccion : proveedor.direccion,
			telefono : proveedor.telefono
		});
		
        sink.Main.ui.setActiveItem( tarjeta , 'slide');
	};




	this.addToCart = function( item ){
		if(DEBUG){
			console.log("agregnaod item" , item);
		}
		
		var pid = item.get("productoID");
		var i;
		
		for (i = carritoItems.length - 1; i >= 0; i--){
			if( carritoItems[i].productoID == item.productoID ){
				if(DEBUG){
					console.log( "este producto ya existe en el carrito para surtir" );
				}
				refreshSurtir();
				return ;
			}
		}
		
		item.cantidad = 1;
		
		if(DEBUG){
			console.log("Agregando nuevo", item)
		}
		
		carritoItems.push( item );
	
		refreshSurtir();		
		
	};

	var carritoItems = [];

	this.cancelarPedido = function (){
		
		carritoItems =  [];
		refreshSurtir();
		
	};

	this.quitarDelCarrito = function ( id_producto ) {
		if(DEBUG){
	        console.log("Removiendo del carrito.");
	    }


	    for (var i = carritoItems.length - 1; i >= 0; i--){
	        if( carritoItems[i].get("productoID") == id_producto ){
	            carritoItems.splice( i ,1 );
	            break;
	        }
	    }
	
	    refreshSurtir();
	};


	var refreshSurtir = function (){

		if(DEBUG){
			console.log("Refrescanco el carrito para surtir de proveedor externo...");
		}
		


	    if(carritoItems.length > 0){
	    	Ext.getCmp("Proveedores-confirmarPedido").show( Ext.anims.slide );
	    	Ext.getCmp("Proveedores-cancelarPedido").show( Ext.anims.slide );	
	    }else{
	    	Ext.getCmp("Proveedores-confirmarPedido").hide( Ext.anims.slide );
	    	Ext.getCmp("Proveedores-cancelarPedido").hide( Ext.anims.slide );	
	    }


		var html = "<table border=0>";

		html += "<tr class='top'>";
		html += "	<td>Descripcion</td>";
	    html += "	<td></td>";
		html += "	<td colspan=2>Cantidad</td>";
		html += "	<td>Costo</td>";
		//html += "<td>Total</td>";

		html += "</tr>";

		for (var i=0; i < carritoItems.length; i++){

			if(carritoItems[i].cantidad === null){
				carritoItems[i].cantidad = 1;
			}

			if( i == carritoItems.length - 1 )
				html += "<tr class='last'>";
			else
				html += "<tr >";		

			html += "	<td>" + carritoItems[i].get("productoID") + " " + carritoItems[i].get("descripcion") + "</td>";
			html += "	<td > <span class = 'boton' onClick = 'Aplicacion.Proveedores.ci.quitarDelCarrito(" + carritoItems[i].get("productoID") + ")'>Quitar</span> </td>";
			html += "	<td colspan=2 > <div id='Proveedores-carritoCantidad"+ carritoItems[i].get("productoID") +"'></div></td>";
			html += "	<td > <div id='Proveedores-carritoCosto"+ carritoItems[i].get("productoID") +"'></div></td>";
			//html += "<td > </td>";
			//html += "<td> <div style='color: green'>"+ POS.currencyFormat(carrito.items[i].precioIntersucursal) +"</div></td>";
			//html += "<td>" + POS.currencyFormat( carrito.items[i].cantidad * carrito.items[i].precioIntersucursal )+"</td>";

			html += "</tr>";
		}

		html += "</table>";

		Ext.getCmp("Proveedores-surtirTabla").update(html);


		for (i=0; i < carritoItems.length; i++){


			a = new Ext.form.Text({
				renderTo : "Proveedores-carritoCantidad"+ carritoItems[i].get("productoID") ,
				id : "Proveedores-carritoCantidad"+ carritoItems[i].get("productoID") + "Text",
				value : carritoItems[i].cantidad,
				prodID : carritoItems[i].get("productoID"),
				width: 150,
				placeHolder : "Cantidad",
				listeners : {
					'focus' : function (){

						kconf = {
							type : 'num',
							submitText : 'Aceptar',
							callback : function ( campo ){
									//refrescar el carrito
							}
						};

						POS.Keyboard.Keyboard( this, kconf );
					}
				}

			});

			a = new Ext.form.Text({
				renderTo : "Proveedores-carritoCosto"+ carritoItems[i].get("productoID") ,
				id : "Proveedores-carritoCosto"+ carritoItems[i].get("productoID") + "Text",
				value : carritoItems[i].cantidad,
				prodID : carritoItems[i].get("productoID"),
				width: 150,
				placeHolder : "Costo",
				listeners : {
					'focus' : function (){

						kconf = {
							type : 'num',
							submitText : 'Aceptar',
							callback : function ( campo ){
									//refrescar el carrito
							}
						};

						POS.Keyboard.Keyboard( this, kconf );
					}
				}

			});

			
		}// for de cada elemento

	};







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

	                   sink.Main.ui.setActiveItem( Aplicacion.Proveedores.ci.cards.carrito , 'slide');
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




	//carrito para surtir
	this.cards.carrito = new Ext.Panel({
			dockedItems : new Ext.Toolbar({
					ui: 'dark',
					dock: 'bottom',
					items: [{
						text: 'Agregar producto',
						ui: 'normal',
						handler : function( t ){
							Aplicacion.Proveedores.ci.popups.listaDeProductos.showBy(this);
						}
					},{
						xtype : 'spacer'
					},{
						text: 'Cancelar pedido',
						ui: 'normal',
						id : 'Proveedores-cancelarPedido'
						handler : function( t ){
								Aplicacion.Proveedores.ci.cancelarPedido();
						}		
					},{
						text: 'Confirmar pedido',
						ui: 'action',
				        hidden: true,
				        id : 'Proveedores-confirmarPedido',
						handler : function( t ){
				            	Aplicacion.Proveedores.ci.confirmar();
						}		
					}]
				}),
			html : "",
			id : "Proveedores-surtirTabla",
			cls : "Tabla"
		});



		this.popups.listaDeProductos = new Ext.Panel({
			floating: true,
			ui : "dark",
	        modal: false,
	        scroll: false,
	        width: 300,
	        height: 500,
			showAnimation : Ext.anims.fade ,
			hideOnMaskTap : true,
			bodyPadding : 0,
			bodyMargin : 0,
	        styleHtmlContent: false,
			items : [{
				multiSelect : true,
				xtype: 'list',
				ui: 'dark',
				store: Aplicacion.Inventario.currentInstance.inventarioListaStore,
				itemTpl: '<div class=""><b>{productoID}</b> {descripcion}</div>',
				grouped: true,
				indexBar: false,
				listeners : {
					"selectionchange"  : function ( view, nodos, c ){

						if(nodos.length > 0){
							Aplicacion.Proveedores.ci.popups.listaDeProductos.hide()
							Aplicacion.Proveedores.ci.addToCart( nodos[0] );
						}

						//deseleccinar
						view.deselectAll();
					}
				}
			}]
		});

}





Aplicacion.Proveedores.prototype.getConfig = function (){
	return {
	    text: 'Proveedores',
        card: this.cards.lista,
		leaf : true,
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

