
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
		carrito : null,
		
		//nuevo proveedor
		nuevo : null
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
	var cargarListaDeProveedores = function( showPanel ){

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

                if( showPanel ){
                    sink.Main.ui.setActiveItem( Aplicacion.Proveedores.ci.cards.lista , 'slide');
                }
				
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

		if(id_proveedor == -1){
			//se ha seleccionado al centro de distribuicion
        	sink.Main.ui.setActiveItem( Aplicacion.Inventario.currentInstance.surtirWizardPanel , 'slide');
			return;
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
		
		Aplicacion.Proveedores.ci.currentProveedor = id_proveedor;
		
        sink.Main.ui.setActiveItem( tarjeta , 'slide');
	};

    /**
    * id del proveedor seleccionado
    */
    this.currentProveedor = null;

	this.addToCart = function( item ){
	
		if(DEBUG){
			console.log("agregnaod item" , item.data);			
		}
				
		
		for (var i = carritoItems.length - 1; i >= 0; i--){		
		
			if( carritoItems[i].get("productoID") == item.get("productoID") ){
				if(DEBUG){
					console.log( "este producto ya existe en el carrito para surtir" );
				}
				refreshSurtir();
				return ;
			}
		}

		item.cantidad = 1;		
		
		carritoItems.push( item );
		
		if(DEBUG){
			console.log("Agregando nuevo", item);
		}
	
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

    this.carritoCambiarCantidad = function( id, qty, forceNewValue ){
    
        
	
	    for (var i = carritoItems.length - 1; i >= 0; i--){
	
	
		    if( carritoItems[i].get("productoID") == id ){
			
			    if(forceNewValue){
				    carritoItems[i].cantidad = qty;
			    }else{
				    carritoItems[i].cantidad += qty;
			    }
			
			    if(carritoItems[i].cantidad <= 0){
				    carritoItems[i].cantidad = 1;
			    }
			
			    refreshSurtir();
			    break;
		    }
	    }
    
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
		
		html += "   <td>Descripcion</td>";
	    html += "   <td>&nbsp;</td>";
		html += "   <td colspan=4 align = center>Cantidad</td>";
		html += "   <td >Costo</td>";
		html += "   <td>Sub Total</td>";

		html += "</tr>";

		for (var i=0; i < carritoItems.length; i++){

			if(carritoItems[i].cantidad === null){
				carritoItems[i].cantidad = 1;
			}

			if( i == carritoItems.length - 1 )
			{
				html += "<tr class='last'>";
			}
			else
			{
				html += "<tr >";		
			}

            var m ;
		    switch(carritoItems[i].get("medida")){
			    case "kilogramo": m = "kgs"; break;
			    case "pieza": m = "pzas"; break;
			    case "litro": m = "lts"; break;						
		    }

			html += "   <td style='width: 17%;' ><b>" + carritoItems[i].get("productoID") + "</b> &nbsp; " + carritoItems[i].get("descripcion") + "</td>";
			html += "   <td style='width: 12%;'> <span class = 'boton' onClick = 'Aplicacion.Proveedores.ci.quitarDelCarrito(" + carritoItems[i].get("productoID") + ")'><img src='../media/icons/close_16.png'></span></span> </td>";
			html += "   <td style='width: 8.1%;'> <span class='boton' onClick=\"Aplicacion.Proveedores.ci.carritoCambiarCantidad('"+ carritoItems[i].get("productoID") + "', -1, false)\">&nbsp;-&nbsp;<img src='../media/icons/arrow_down_16.png'></span></td>";
			html += "   <td style='width: 8.1%;' > <div id='Proveedores-carritoCantidad"+ carritoItems[i].get("productoID") +"'></div> </td>";
			html += "   <td style='width: 6%;'>" + m +  "</td>";
			html += "   <td style='width: 8.1%;'> <span class='boton' onClick=\"Aplicacion.Proveedores.ci.carritoCambiarCantidad('"+ carritoItems[i].get("productoID") +"', 1, false)\"><img src='../media/icons/arrow_up_16.png'>&nbsp;+&nbsp;</span></td>";
			html += "   <td style='width: 10.4%;'> <div id='Proveedores-carritoCosto"+ carritoItems[i].get("productoID") +"'> " + POS.currencyFormat( carritoItems[i].get("precioIntersucursal") ) + " </div></td>";
			html += "   <td style='width: 12.3%;'>" +  POS.currencyFormat( carritoItems[i].cantidad * carritoItems[i].get("precioVenta") ) + "</td>";

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
				//width: 150,
				placeHolder : "Cantidad",
				listeners : {
					'focus' : function (){

						kconf = {
							type : 'num',
							submitText : 'Aceptar',
							callback : function ( campo ){
									//refrescar el carrito
									
									//buscar el producto en la estructura y ponerle esa nueva cantidad
							        for (i=0; i < carritoItems.length; i++){
							
								        if(carritoItems[i].get("productoID")  == campo.prodID ){
									        carritoItems[i].cantidad = parseFloat( campo.getValue() );
									        
									        if( isNaN( campo.getValue() ) || campo.getValue() <= 0 )
									        {
									            carritoItems[i].cantidad = 1;									            
									        }
									        
									        break;
								        }
								        
							        }
							
							        refreshSurtir();
									
							}
						};

						POS.Keyboard.Keyboard( this, kconf );
					}
				}

			});

			/*b = new Ext.form.Text({
				renderTo : "Proveedores-carritoCosto"+ carritoItems[i].get("productoID") ,
				id : "Proveedores-carritoCosto"+ carritoItems[i].get("productoID") + "Text",
				value : POS.currencyFormat( carritoItems[i].get("precioIntersucursal") ),
				prodID : carritoItems[i].get("productoID"),
				width: 150,
				placeHolder : "Costo"
			});*/
						

			
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
	    dockedItems: [{
	            dock: 'bottom',
	            xtype: 'toolbar',
	            items: [{
		            text: 'Agregar Nuevo Proveedor',
	                ui: 'action',
	                handler: function() {

	                  sink.Main.ui.setActiveItem( Aplicacion.Proveedores.ci.cards.nuevo , 'slide');
	                   
	                }
				}]
	        }],      
        layout: 'fit' ,
        items: [{ html : "<div style='height: 100%;' id='MosaicoProveedores'></div>" }],
		listeners : {
			"show" : function (){
				
				if(DEBUG){
					console.log("Creando mosaico para proveedores" , this );
				}
				
				items = [];								
				
				//empujar el proveedor de centro de distribucion
				items.push({
					title : "Centro de distribucion",
					id : -1,
					image : '../media/TruckYellow128.png'
				});				
				
				if(DEBUG){
				    console.log( "Hay " + listaDeProveedores.length + " proveedores." );
				}
				
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


//panel para crear un nuevo proveedor
	this.cards.nuevo  = new Ext.form.FormPanel({      
	    dockedItems: [{
	            dock: 'bottom',
	            xtype: 'toolbar',
	            items: [{
		            text: 'Regresar',
	                ui: 'back',
	                handler: function() {

	                  sink.Main.ui.setActiveItem( Aplicacion.Proveedores.ci.cards.lista , 'slide');
	                   
	                }
				}]
	        }],                                                       
            items: [{
                xtype: 'fieldset',
                title: 'Detalles del nuevo proveedor',
                instructions: 'Ingrese los detalles del nuevo proveedor.',
                items: [                                       
                    new Ext.form.Text({ id: 'Proveedores-nombre', label: 'Nombre', name : 'nombre', required:true,
                    listeners : {
                        'focus' : function (){
                                kconf = {
                                type : 'alfa',
                                submitText : 'Aceptar'
                            };
                        POS.Keyboard.Keyboard( this, kconf );
                        }
                    } }),
                    new Ext.form.Text({ id: 'Proveedores-rfc', name:'rfc',  label: 'RFC', required:true,
                    listeners : {
                        'focus' : function (){
                                kconf = {
                                type : 'alfanum',
                                submitText : 'Aceptar'
                            };
                        POS.Keyboard.Keyboard( this, kconf );
                        }
                    } }), 
                    new Ext.form.Text({ id: 'Proveedores-direccion', label: 'Direccion', name : 'direccion', required:true,
                    listeners : {
                        'focus' : function (){
                                kconf = {
                                type : 'alfanum',
                                submitText : 'Aceptar'
                            };
                        POS.Keyboard.Keyboard( this, kconf );
                        }
                    } }),
                    new Ext.form.Text({ id: 'Proveedores-email', label: 'Email', name : 'e_mail',
                    listeners : {
                        'focus' : function (){
                                kconf = {
                                type : 'alfanum',
                                submitText : 'Aceptar'
                            };
                        POS.Keyboard.Keyboard( this, kconf );
                        }
                    } }),  
                    new Ext.form.Text({ id: 'Proveedores-telefono', label: 'Telefono', name:'telefono',  required:true,
                    listeners : {
                        'focus' : function (){
                                kconf = {
                                type : 'num',
                                submitText : 'Aceptar'
                            };
                        POS.Keyboard.Keyboard( this, kconf );
                        }
                    } }),
                    new Ext.form.Select({	     
                        name : "tipo_proveedor",      
                        label : "Tipo", 
			            value : "sucursal",			            
                        options : [
                            {
                                text : "Sucursal",
                                value : "sucursal"
                            },{
                                text : "Ambos",
                                value : "ambos"
                            }
                        ]
                    })
            ]},
            new Ext.Button({ ui  : 'action', text: 'Registrar Proveedor', margin : 5,handler : function(){
                Aplicacion.Proveedores.ci.registrarNuevoProveedorValidator();
            } })
        ]});


    this.registrarNuevoProveedorValidator = function (){
    
        var values = Aplicacion.Proveedores.ci.cards.nuevo.getValues();

        if( values.nombre.length < 10 ){

            Ext.Anim.run(Ext.getCmp( 'Proveedores-nombre' ), 
                'fade', {duration: 250,
                out: true,
                autoClear: true
            });

            return;
        }
        
        if( values.rfc.length < 10 ){

            Ext.Anim.run(Ext.getCmp( 'Proveedores-rfc' ), 
                'fade', {duration: 250,
                out: true,
                autoClear: true
            });

            return;
        }
        
        if( values.direccion.length < 10 ){

            Ext.Anim.run(Ext.getCmp( 'Proveedores-direccion' ), 
                'fade', {duration: 250,
                out: true,
                autoClear: true
            });

            return;
        }

        this.registrarNuevoProveedor( values );
    
    };

    
    //registra el nuevo proveedor en la BD
    this.registrarNuevoProveedor = function( values ){
    
        Ext.Ajax.request({
		    url: '../proxy.php',
		    scope : this,
		    params : {
			    action : 901,
			    data : Ext.util.JSON.encode( values )
		    },
		    success: function(response, opts) {           

                try{
				    r = Ext.util.JSON.decode( response.responseText );				
			    }catch(e){
				    POS.error(e);
			    }
						
			    if( !r.success ){
                    Ext.Msg.alert("Nuevo Proveedor", r.reason);				                
				    return;
			    }

                Ext.Msg.alert("Nuevo Proveedor", "Proveedor Registrado Correctramente");
                
                //recargamos la lista de proveedores
                cargarListaDeProveedores( true );                 
		    },
		    failure: function( response ){
			    return POS.error( response );
		    }
	    });
    
    };

	//carrito para surtir
	this.cards.carrito = new Ext.Panel({
			dockedItems : new Ext.Toolbar({
					ui: 'dark',
					dock: 'bottom',
					items: [{
						text: 'Agregar  producto',
						ui: 'normal',
						handler : function( t ){
							Aplicacion.Proveedores.ci.popups.listaDeProductos.showBy(this);
						}
					},{
						xtype : 'spacer'
					},{
						text: 'Cancelar pedido',
						ui: 'normal',
						id : 'Proveedores-cancelarPedido',
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
				//grouped: true,
				indexBar: false,
				listeners : {
					"selectionchange"  : function ( view, nodos, c ){

						if(nodos.length > 0){
							Aplicacion.Proveedores.ci.popups.listaDeProductos.hide()
							Aplicacion.Proveedores.ci.addToCart( nodos[0] );
						}

						//deseleccionar
						view.deselectAll();
						
					}
				}
			}]
		});
		
		
		this.confirmar = function(){					  
		    
		    var carrito = {
		        id_proveedor : Aplicacion.Proveedores.ci.currentProveedor,
		        productos : []
		    };
		    
		    
		    for( var i = 0; i < carritoItems.length; i++ ){
		        
		        carrito.productos.push({
		            id_producto : carritoItems[i].get("productoID"),
		            cantidad : carritoItems[i].cantidad
		        });
		        
		    }
		    if(DEBUG){
		        console.log("El JSON de productos a comprar es : ",carrito );
		    }
		    
		/*
		    Ext.getBody().mask('Registrando Compra...', 'x-mask-loading', true);
		    		    		    
		    Ext.Ajax.request({
		        url: '../proxy.php',
		        scope : this,
		        params : {
			        action : 302,
			        data : Ext.util.JSON.encode( data )
		        },
		        success: function(response, opts) {
			        try{
				        r = Ext.util.JSON.decode( response.responseText );				
			        }catch(e){
				        POS.error(e);
			        }
			

			        Ext.getBody().unmask();	
						
			        if( !r.success ){
                        
				        return;
			        }

                   

		        }
	        });	*/
		    
		    
		};

};





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

