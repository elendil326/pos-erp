ApplicationProveedores= function ()
{
	if(DEBUG){
		console.log("ApplicationProveedores: construyendo");
	}
	
	ApplicationProveedores.currentInstance = this;	

	this._init();

	return this;
	
};

ApplicationProveedores.prototype.mainCard = null;

ApplicationProveedores.prototype.appName = null;

ApplicationProveedores.prototype.leftMenuItems = null;

ApplicationProveedores.prototype.ayuda = null;

ApplicationProveedores.prototype.ProveedoresListStore = null;

ApplicationProveedores.prototype.providers = null;

ApplicationProveedores.prototype.actualizaProveedor=null;

ApplicationProveedores.prototype.updateProviderForm= null;

ApplicationProveedores.prototype.record = null;

ApplicationProveedores.prototype.dockedItems = null;

ApplicationProveedores.prototype.dockedItemsGuardar = null;





ApplicationProveedores.prototype._init = function()
{

	//nombre de la aplicacion
	this.appName = "Proveedores";
	
	//ayuda sobre esta applicacion
	this.ayuda = "Ayuda sobre este modulo de prueba <br>, html es valido <br> :D";

	//submenues en el panel de la izquierda
	this._initToolBar();
	
	//panel principal
	this.mainCard = this.proveedoresWelcome;
	
};






ApplicationProveedores.prototype._initToolBar = function ()
{
	/*	
		Buscar
	*/
	var buscar = [{
		xtype: 'textfield',
		id:'ApplicationProveedores_searchField',
		inputCls: 'caja-buscar',
		listeners:
				{
					'render': function( ){
						//medio feo, pero bueno
						Ext.get("ApplicationProveedores_searchField").first().dom.setAttribute("onkeyup",
						 "ApplicationProveedores.currentInstance.mosaic.doSearch( this.value )");
					}
				}
		}];

		var agregar = [{
			xtype: 'button',
			text: 'Nuevo Proveedor',
			ui: 'action',
			handler: function(){
					console.log("ApplicacionProveedores: agregarProveedor called....");
					sink.Main.ui.setCard( ApplicationProveedores.prototype.agregarProveedor, 'slide' );
				}
			}];		

        this.dockedItems = [ new Ext.Toolbar({
            ui: 'dark',
            dock: 'bottom',
            items: buscar.concat({xtype:'spacer'}).concat(agregar)
        })];
    
	
	//agregar este dock a el panel principal
	this.proveedoresWelcome.addDocked( this.dockedItems );
	

	/*
		DOCKED ITEMS PARA AGREGAR PROVEEDOR
	*/
	
	var guardar= [{
		xtype: 'button',
		id: 'guardarProveedor',
		text: 'Guardar Proveedor',
		ui: 'action',
		handler: function(event,button) {
				ApplicationProveedores.currentInstance.guardarProveedor();
				}//fin handler	
	}];//fin boton guardar
	
	
	var regresar =[{
		xtype: 'button',
		id: 'cancelarGuardarProveedor',
		text: 'Regresar',
		ui: 'back',
		handler: function(event,button) {
				sink.Main.ui.setCard( ApplicationProveedores.currentInstance.proveedoresWelcome, { type: 'slide', direction: 'right'} );		
				}//fin handler cancelar cliente
				
	}];//fin boton cancelar

	this.dockedItems = [ new Ext.Toolbar ({ 
		ui: 'dark',
		dock: 'bottom',
		items: regresar.concat({xtype: 'spacer'}).concat(guardar)
	})];
	
	this.agregarProveedor.addDocked( this.dockedItems );
};



ApplicationProveedores.prototype.provedores = [];

ApplicationProveedores.prototype.comprasObj = new ApplicationCompras();

ApplicationProveedores.prototype.proveedorSelected=null;

ApplicationProveedores.prototype.proveedorSelectedHtml="";


ApplicationProveedores.prototype.renderMosaico = function ()
{

	if(DEBUG){
		console.log("ApplicationProvedores: rendering " +ApplicationProveedores.currentInstance.provedores.length+ " items." );
	}

	var mItems = [];
	
	for(  a = 0; a < ApplicationProveedores.currentInstance.provedores.length; a++){
		mItems.push({
			image : 'media/truck.png',
			title : ApplicationProveedores.currentInstance.provedores[a].nombre,
			provedorId : ApplicationProveedores.currentInstance.provedores[a].id_proveedor,
			provedor : ApplicationProveedores.currentInstance.provedores[a],
			keywords : [ ApplicationProveedores.currentInstance.provedores[a].direccion, ApplicationProveedores.currentInstance.provedores[a].rfc ]
		});
	}
	
	
	ApplicationProveedores.currentInstance.mosaic = new Mosaico({
		renderTo : 'proveedores_mosaico',
		handler : function (item){
			ApplicationProveedores.currentInstance.doVerProvedor( item.provedor );
		},
		items: mItems
	});
	
};


ApplicationProveedores.prototype.getProvedores = function ()

{
	
	if(DEBUG){
		console.log( "ApplicationProvedores: refrescando lista de provedores"  );
	}
	
	POS.AJAXandDECODE({
		action: '1105'
		},
		function (datos){
			if(datos.success == true){
				if(DEBUG){
					console.log("Entro success de getProveedores: ", datos);					
				}

				ApplicationProveedores.currentInstance.provedores = datos.datos;
				ApplicationProveedores.currentInstance.renderMosaico();
			}
		},
		function (){
			POS.aviso("Provedores","Algo anda mal con tu conexion.");	
		}
	);

};




ApplicationProveedores.prototype.proveedoresWelcome = new Ext.Panel({
		layout: 'card',
		html: '<div style="width:100%; height:100%" id="proveedores_mosaico"></div>',
		//scroll: 'vertical',
		listeners : {
			'afterrender' : function (){
				ApplicationProveedores.currentInstance.getProvedores();
			}
			
		}
});
















ApplicationProveedores.prototype.doVerProvedor = function ( provedor )
{
	
	if(DEBUG){
		console.log("ApplicationProveedore: viendo proveedor " ,  provedor );
	}

	ApplicationProveedores.currentInstance.proveedorSelected =provedor;
	
	ApplicationProveedores.currentInstance.listarCompras( );
	
	ApplicationProveedores.currentInstance.listarComprasCredito();
	
	var newPanel = this.createPanelForProvedor( provedor );
	
	sink.Main.ui.setCard( newPanel, 'slide' );
	
};







ApplicationProveedores.prototype.renderProvedorDetalles = function ( provedor )
{
	var html = "";
	
	html += "<div class='nombre'>" 		+provedor.nombre 		+ "</div>";
	html += "<div class='direccion'>" 	+provedor.direccion 	+ "</div>";
	html += "<div class='mail'>" 		+provedor.e_mail		+ "</div>";
	html += "<div class='id_provedor'>" +provedor.id_proveedor	+ "</div>";
	html += "<div class='rfc'>"  		+provedor.rfc			+ "</div>";
	html += "<div class='telefono'>"  	+provedor.telefono		+ "</div>";

	return "<div class='ApplicationProveedores-Detalles'>"+html+"</div>";
	
};


ApplicationProveedores.prototype.createPanelForProvedor = function ( provedor )
{

	var carousel = new Ext.Carousel({
		
		defaults: { 
			//cls: 'ApplicationProveedores-detallesProveedor'
			scroll: 'vertical'
		},
		items: [{
			cls: 'ApplicationProveedores-detallesProveedor',
			html: this.renderProvedorDetalles(provedor)
		}, {
			scroll: 'vertical',
			xtype: 'panel',
			title: 'compras',
			id: 'comprasProveedorSucursalPanel',
			items: [ {id: 'comprasProveedorSucursal' }]
			
		}, { 
			scroll: 'vertical',
			xtype: 'panel',
			title: 'creditos',
			id: 'comprasProveedorCreditoPanel',
			items: [{id: 'comprasProveedorCredito'}]
		}]
	});


	var regresar = [{
			xtype: 'button',
			text: 'Regresar',
			ui: 'back',
			handler : function(){
				sink.Main.ui.setCard( 
					ApplicationProveedores.currentInstance.mainCard, {
						type: 'fade',
						duration: 500
					});
			}
		}];		


	var surtir = [{
			xtype: 'button',
			text: 'Surtir',
			ui: 'action',
			handler: function(){
				
				//ApplicationProveedores.currentInstance.comprasObj.idProviderSelected ( provedor );
				
				//sink.Main.ui.setCard( ApplicationProveedores.currentInstance.comprasObj.comprarMainPanel, 'slide' );
				var obj = new ApplicationComprasProveedor ( provedor );
				sink.Main.ui.setCard( obj.surtir, 'slide' );			
				
			}
		}];
		
    var dockedItems = [ new Ext.Toolbar({
        ui: 'dark',
        dock: 'bottom',
        items: regresar.concat({xtype:'spacer'}).concat(surtir)
    })];


	var panel = new Ext.Panel({
		dockedItems : dockedItems,
		layout: {
			type: 'vbox',
			align: 'stretch'
		},
		defaults: {
	      flex: 1
		},
		items: [carousel]
	});
	

	
	return panel;
};




/*------------------------------------------------------------------
			COMPRAS HECHAS A UN PROVEEDOR
------------------------------------------------------------------------*/

ApplicationProveedores.prototype.listarCompras = function (  ){
	
	var record_proveedor = ApplicationProveedores.currentInstance.proveedorSelected;

	Ext.regModel('comprasProvStore', {
    	fields: ['nombre', 'rfc']
	});
	
	var comprasProveedor = new Ext.data.Store({
    	model: 'comprasProvStore'
 
	});	
	
	
	//cabecera de datos del cliente seleccionado
	
	var proveedorHtml = ""; 	var html = "";

	
		POS.AJAXandDECODE({
			action: '1206',
			id_proveedor: record_proveedor.id_proveedor 
			},
			function (datos){//mientras responda AJAXDECODE LISTAR VENTAS CLIENTE
				if(datos.success === true){
					comprasProveedor.loadData(datos.datos);
					
					
					
					html += "<div class='ApplicationClientes-item' >"
							+ "<div class='trash' ></div>"
							+ "<div class='id'>No. Compra</div>" 
							+ "<div class='tipo'>Tipo Venta</div>" 
							+ "<div class='fecha'>Fecha</div>" 
							+ "<div class='sucursal'>Sucursal</div>"
							+ "<div class='vendedor'>Realizo compra</div>"
							+ "<div class='subtotal'>Subtotal</div>"
							+ "<div class='iva'>IVA</div>"
							+ "<div class='total'>TOTAL</div>"
							+ "</div>";
					
					//renderear el html
					for( a = 0; a < comprasProveedor.getCount(); a++ ){
						
						html += "<div class='ApplicationClientes-item' >" 
						+ "<div class='trash' onclick='ApplicationProveedores.currentInstance.verCompra(" +comprasProveedor.data.items[a].id_compra+ ")'><img height=20 width=20 src='sencha/resources/img/toolbaricons/search.png'></div>"	
							+ "<div class='id'>" + comprasProveedor.data.items[a].id_compra +"</div>" 
							+ "<div class='tipo'>" + comprasProveedor.data.items[a].tipo_compra+"</div>" 
							+ "<div class='fecha'>"+ comprasProveedor.data.items[a].fecha +"</div>" 
							+ "<div class='sucursal'>"+ comprasProveedor.data.items[a].descripcion +"</div>"
							+ "<div class='vendedor'>"+ comprasProveedor.data.items[a].nombre +"</div>"
							+ "<div class='subtotal'>$"+ comprasProveedor.data.items[a].subtotal +"</div>"
							+ "<div class='iva'>$"+ comprasProveedor.data.items[a].iva +"</div>"
							+ "<div class='total'>$"+ comprasProveedor.data.items[a].total +"</div>"
							+ "</div>";
					}//fin for
					
					
					//imprimir el html
					proveedorHtml += html;
					
					//console.log(comprasProveedor.data.items);
				}
				if(datos.success == false){
					//POS.aviso("ERROR","NO SE PUDO CARGAR LA LISTA DE VENTAS PROBABLEMENTE ESTE CLIENTE 'NO' HA COMPRADO");
					
					proveedorHtml += "<div class='ApplicationClientes-itemsBox'><div class='noVentas' align='center'>NO SE LE HAN HECHO COMPRAS A ESTE PROVEEDOR</div> </div>";
				}
				
				Ext.get("comprasProveedorSucursal").update(proveedorHtml);
				
			},
			function (){//no responde AJAXDECODE DE VENTAS CLIENTE
				POS.aviso("ERROR","NO SE PUDO CARGAR LA LISTA DE VENTAS   ERROR EN LA CONEXION :(");      
			}
		);//AJAXandDECODE LISTAR VENTAS CLIENTE
			
	
}
/*-----------------------------------------------------------------
	COMPRAS A CREDITO HECHAS A UN PROVEEDOR
-------------------------------------------------------------------*/
ApplicationProveedores.prototype.listarComprasCredito = function (  ){
	 
	var record_proveedor = ApplicationProveedores.currentInstance.proveedorSelected;
	
	Ext.regModel('comprasCreditoStore', {
    	fields: ['nombre', 'rfc']
	});

	var comprasProveedorCredito = new Ext.data.Store({
    	model: 'comprasCreditoStore'  
	});	
	
	
	
	var html = "";
	
		POS.AJAXandDECODE({
			action: '1209',
			id_proveedor: record_proveedor.id_proveedor//recor[0].id_cliente
			},
			function (datos){//mientras responda AJAXDECODE LISTAR VENTAS CLIENTE
				if(datos.success === true){
					comprasProveedorCredito.loadData(datos.datos);
					
					html += "<div class='ApplicationClientes-item' >"
							+ "<div class='trash' ></div>"
							+ "<div class='id'>No. Compra</div>" 
							+ "<div class='fecha'>Fecha</div>" 
							+ "<div class='sucursal'>Sucursal</div>"
							+ "<div class='vendedor'>Realizo compra</div>"
							+ "<div class='total'>TOTAL</div>"
							+ "<div class='total'>ABONADO</div>"
							+ "<div class='total'>ADEUDO</div>"
							+ "<div class='subtotal'>VER ABONOS</div>"
							+ "<div class='total'>STATUS</div>"
							+ "</div>";
					
					//renderear el html
					for( a = 0; a < comprasProveedorCredito.getCount(); a++ ){
						var compra = comprasProveedorCredito.data.items[a];
						var tot = parseFloat(compra.data.subtotal) + parseFloat(compra.data.iva);
						var adeudo = tot - compra.data.abonado;
						//console.log("-------------------- en la comprata: "+compra.data.id_comprata+" abonado: "+compra.data.abonado);
						var status="";
						if (adeudo <= 0){
							status="<div class='pagado'>PAGADO</div>";
						}else{
							
							status ="<div class='abonar' onclick='ApplicationProveedores.currentInstance.abonarCompra(" + compra.data.id_compra + " , "+ tot +" , "+ adeudo +", "+ compra.data.abonado +")'>ABONAR</div>";
						}
						html+= "<div class='ApplicationClientes-item' >" 
						+ "<div class='trash' onclick='ApplicationProveedores.currentInstance.verCompra(" + compra.data.id_compra+ ")'><img height=20 width=20 src='sencha/resources/img/toolbaricons/search.png'></div>"	
						+ "<div class='id'>" + compra.data.id_compra +"</div>" 
						+ "<div class='fecha'>"+ compra.data.fecha +"</div>" 
						+ "<div class='sucursal'>"+ compra.data.sucursal +"</div>"
						+ "<div class='vendedor'>"+ compra.data.comprador +"</div>"
						+ "<div class='total'>$"+ tot +"</div>"
						+ "<div class='total' id='abonadoCompra_"+compra.data.id_compra+"'>$"+ compra.data.abonado +"</div>"
						+ "<div class='total' id='adeudoCompra_"+compra.data.id_compra+"'>$"+ adeudo +"</div>"
						+ "<div class='subtotal' onclick='ApplicationProveedores.currentInstance.verPagosCompra(" + compra.data.id_compra+ ")'><img height=20 width=20 src='sencha/resources/img/toolbaricons/compose.png'></div>"
						+ status
						+ "</div>";
														
						
					}//fin for comprasProveedorCredito
					
					//imprimir el html
					Ext.get("comprasProveedorCredito").update("<div class='ApplicationClientes-itemsBox'>" + html +"</div>");
					//console.log(ventasCliente.data.items);
				}
				if(datos.success == false){
					//POS.aviso("ERROR","NO SE PUDO CARGAR LA LISTA DE VENTAS PROBABLEMENTE ESTE CLIENTE 'NO' HA COMPRADO");
					Ext.get("comprasProveedorCredito").update("<div class='ApplicationClientes-itemsBox'><div class='noVentas' align='center'>NO SE HAN HECHO COMPRAS A CREDITO A ESTE PROVEEDOR</div> </div>");
				}
			},
			function (){//no responde AJAXDECODE DE VENTAS CLIENTE
				POS.aviso("ERROR","NO SE PUDO CARGAR LA LISTA DE COMPRAS   ERROR EN LA CONEXION :(");      
			}
		);//AJAXandDECODE LISTAR VENTAS CLIENTE
				
		

}

/*-------------------------------------------------------
	PAGOS HECHOS SOBRE 1 COMPRA EN ESPECIFICO
---------------------------------------------------------*/
ApplicationProveedores.prototype.verPagosCompra = function( idCompra ){

	 var formBase = new Ext.Panel({
		id: 'pagosCompraPanel',
		 scroll: 'vertical',
			//	items
            items: [{
				id: 'pagosCompraProveedor',
		        html: ''
		    }], 
			//	dock		
            dockedItems: [{
                    xtype: 'toolbar',
                    dock: 'bottom',
                    items: [{
						xtype: 'spacer'
						},{
						//-------------------------------------------------------------------------------
						//			cancelar
						//-------------------------------------------------------------------------------
						text: 'X Cerrar',
						handler: function() {
							//regresar el boton de cliente comun a 1
							Ext.getCmp("pagosCompraPanel").destroy();
							 Ext.getBody().unmask();
							//ocultar este form
							//form.hide();							
                            }
						}]
					}]
			});
	
       
	   if (Ext.platform.isAndroidOS) {
            formBase.items.unshift({
                xtype: 'component',
                styleHtmlContent: true,
                html: '<span style="color: red">Forms on Android are currently under development. We are working hard to improve this in upcoming releases.</span>'
            });
        }

	if (Ext.platform.isPhone) {
            formBase.fullscreen = true;
        } else {
            Ext.apply(formBase, {
                autoRender: true,
                floating: true,
                modal: true,
                centered: true,
                hideOnMaskTap: false,
                height: 585,
                width: 680
            });
        }
        
		Ext.regModel('comprasCreditoDetalleStore', {
    	fields: ['nombre', 'rfc']
		});

		var comprasDetalle= new Ext.data.Store({
    	model: 'comprasCreditoDetalleStore'
    	
		});	
		
		POS.AJAXandDECODE({
			action: '1208',
			id_compra: idCompra
			},
			function (datos){//mientras responda AJAXDECODE MOSTRAR CLIENTE
				if(datos.success == true){
					
					comprasDetalle.loadData(datos.datos);
					
					var html = "";
					html += "<div class='ApplicationClientes-item' >" 
					+ "<div class='vendedor'>NUMERO DE COMPRA</div>" 
					+ "<div class='sucursal'>FECHA</div>" 
					+ "<div class='subtotal'>MONTO</div>"
					+ "<div class='subtotal'></div>"
					+ "</div>";
								
					for( a = 0; a < comprasDetalle.getCount(); a++ ){
											
						html += "<div class='ApplicationClientes-item' id='pago_Borrar_"+comprasDetalle.data.items[a].id_pago+"'>" 
						+ "<div class='vendedor'>" + comprasDetalle.data.items[a].id_compra +"</div>" 
						+ "<div class='sucursal'>"+ comprasDetalle.data.items[a].fecha +"</div>" 
						+ "<div class='subtotal'>$ "+ comprasDetalle.data.items[a].monto+"</div>"
						+ "<div class='abonar' onclick='ApplicationProveedores.currentInstance.EliminarabonoCompra(" +  comprasDetalle.data.items[a].id_pago +")'>ELIMINAR PAGO</div>"
						+ "</div>";
					}
								
								//imprimir el html
					Ext.get("pagosCompraProveedor").update("<div class='ApplicationClientes-itemsBox'>" + html +"</div>");
						
				}//FIN DATOS.SUCCES TRUE MOSTRAR CLIENTE
				if(datos.success == false){
					POS.aviso("ERROR","NO SE PUDO CARGAR LA LISTA DE PAGOS, NO SE HA ABONADO A ESTA COMPRA (No. COMPRA: "+idCompra+")");
					return;
				}
				},
			function (){//no responde  AJAXDECODE MOSTRAR CLIENTE     
				POS.aviso("ERROR","NO SE PUDO CARGAR LA LISTA DE PAGOS ERROR EN LA CONEXION :("); 
				return;
			}
		);//AJAXandDECODE MOSTRAR CLIENTE						
	
	formBase.show();
	
}


/*------------------------------------------------------------
	ABONAR A UNA COMPRA QUE LA SUCURSAL ADEUDA
--------------------------------------------------------------*/

ApplicationProveedores.prototype.abonarCompra = function( idCompra , total , adeudo ,totalAbonado ){
	
	var clienteHtml = "<div class='ApplicationClientes-itemsBox'>";
		clienteHtml += " <div class='ApplicationClientes-clienteBox'> ";
		clienteHtml += " <div class='nombre'>Abono para la compra '"+idCompra+"'</div>";
		clienteHtml += " <div class='nombre'> Total de Compra: " + total + "</div>";
		clienteHtml += " <div class='nombre'> Adeuda: " + adeudo + "</div>";
		clienteHtml += " </div> </div><br>";
		
		
	 var abonaPanel = new Ext.form.FormPanel({
		 id: 'abonarCompraPanel',
		 scroll: 'vertical',
		 //html: clienteHtml,
			//	items
            items: [{
					id: 'abonarCompraProveedor',
					html: clienteHtml
		 			},{                                                       
                        		xtype: 'fieldset',
                                title: 'Detalles del Pago',
								instructions: 'Inserte unicamente numeros no letras',
                                items: [
										montoAbonoCompra = new Ext.form.TextField({
                                			id: 'montoAbonoCompra',
											label: 'Abona $',
											listeners:{
												change: function(){
													if(this.getValue() > adeudo){
														this.setValue(adeudo);	
														Ext.getCmp("restariaCompra").setValue(adeudo - this.getValue());
													}else{
														Ext.getCmp("restariaCompra").setValue(adeudo - this.getValue());
													}
												},
												blur: function(){
													if(this.getValue() > adeudo){
														this.setValue(adeudo);	
														Ext.getCmp("restariaCompra").setValue(adeudo - this.getValue());
													}else{
														Ext.getCmp("restariaCompra").setValue(adeudo - this.getValue());
													}
												}
											}
                                        }),
										pagaAbonoCompra = new Ext.form.TextField({
											id: 'pagaAbonoCompra',
											label: 'Efectivo $',
											listeners:{
												change: function(){
													if(Ext.getCmp("pagaAbonoCompra").getValue() < Ext.getCmp("montoAbonoCompra").getValue()){
														this.setValue(Ext.getCmp("montoAbonoCompra").getValue());
														Ext.getCmp("cambioAbonoCompra").setValue(this.getValue() - montoAbonoCompra.getValue());
														Ext.getCmp("restariaCompra").setValue(adeudo - montoAbonoCompra.getValue());
													}
													if(Ext.getCmp("pagaAbonoCompra").getValue >= Ext.getCmp("montoAbonoCompra").getValue()){
														Ext.getCmp("cambioAbonoCompra").setValue(Ext.getCmp("pagaAbonoCompra").getValue() - montoAbonoCompra.getValue());	
														Ext.getCmp("restariaCompra").setValue(adeudo - montoAbonoCompra.getValue());
													}
												},
												blur: function(){
													if(Ext.getCmp("pagaAbonoCompra").getValue() < Ext.getCmp("montoAbonoCompra").getValue()){
														Ext.getCmp("pagaAbonoCompra").setValue(Ext.getCmp("montoAbonoCompra").getValue());
														Ext.getCmp("cambioAbonoCompra").setValue(this.getValue() - montoAbonoCompra.getValue());
														Ext.getCmp("restariaCompra").setValue(adeudo - montoAbonoCompra.getValue());
													}
													if(Ext.getCmp("pagaAbonoCompra").getValue >= Ext.getCmp("montoAbonoCompra").getValue()){
														Ext.getCmp("cambioAbonoCompra").setValue(Ext.getCmp("pagaAbonoCompra").getValue() - montoAbonoCompra.getValue());	
														Ext.getCmp("restariaCompra").setValue(adeudo - montoAbonoCompra.getValue());
													}
												}
											}
										}),
										cambioAbonoCompra = new Ext.form.TextField({
											id: 'cambioAbonoCompra',
											label: 'Cambio $',
											disabled: true
										}),
										restariaCompra = new Ext.form.TextField({
											id: 'restariaCompra',
											label: 'Restaria $',
											disabled: true
										})
										]
						}
				], 
			//	dock		
            dockedItems: [{
                    xtype: 'toolbar',
                    dock: 'bottom',
                    items: [{text: 'Abonar',
							ui: 'action',
							handler: function(){

								POS.AJAXandDECODE({
									action: '1301',
									id_compra: idCompra,
									monto: Ext.getCmp("montoAbonoCompra").getValue()
									},
									function (datos){
										if(datos.success == true){
											/*abono = parseFloat( Ext.getCmp("montoAbonoCompra").getValue() );
											Ext.get("abonadoCompra_"+idCompra).update("$ "+ (totalAbonado + abono) );
											Ext.get("adeudoCompra_"+idCompra).update("$ "+ (adeudo - abono));
											Ext.getCmp("abonarCompraPanel").destroy();
							 				Ext.getBody().unmask();*/
											ApplicationProveedores.currentInstance.listarComprasCredito();
											Ext.getCmp("abonarCompraPanel").destroy();
							 				Ext.getBody().unmask();
										}else{
											POS.aviso("Abono Compra",""+datos.reason);		
										}
									},
									function (){
										POS.aviso("Provedores","Algo anda mal con tu conexion.");	
									}
								);

								
							}
							},
							{
							xtype: 'spacer'
							},{
						//-------------------------------------------------------------------------------
						//			cancelar
						//-------------------------------------------------------------------------------
						text: 'X Cancelar',
						handler: function() {
							//regresar el boton de cliente comun a 1
							Ext.getCmp("abonarCompraPanel").destroy();
							 Ext.getBody().unmask();
							//ocultar este form
							//form.hide();							
                            }
						}]
					}]
			});
	
       
	   if (Ext.platform.isAndroidOS) {
            abonaPanel.items.unshift({
                xtype: 'component',
                styleHtmlContent: true,
                html: '<span style="color: red">Forms on Android are currently under development. We are working hard to improve this in upcoming releases.</span>'
            });
        }

	if (Ext.platform.isPhone) {
            abonaPanel.fullscreen = true;
        } else {
            Ext.apply(abonaPanel, {
                autoRender: true,
                floating: true,
                modal: true,
                centered: true,
                hideOnMaskTap: false,
                height: 585,
                width: 680
            });
        }
        		
				
	abonaPanel.show();
	
}

/*-------------------------------------------------------------------
	ELIMINAR UN PAGO DE UNA COMPRA
---------------------------------------------------------------------*/

ApplicationProveedores.prototype.EliminarabonoCompra = function ( id_Pago ){
	var overlayTb = new Ext.Toolbar({
            dock: 'top'
        });
	var btns = new Ext.Toolbar({
			items :[{
					text: 'Eliminar',
					ui: 'action',
					handler: function(){
						POS.AJAXandDECODE({
							action: '1302',
							id_pago: id_Pago
							},
							function (datos){//mientras responda
								if(!datos.success){
									POS.aviso("ERROR",""+datos.reason);
								}
								ApplicationProveedores.currentInstance.listarComprasCredito();
								Ext.get("pago_Borrar_"+id_Pago).remove();
								Ext.getCmp("confirmaBorrarPago").destroy();
							},
							function (){//no responde
								POS.aviso("ERROR","NO SE PUDO ELIMINAR PAGO  ERROR EN LA CONEXION :(");	
							}
						);//AJAXandDECODE 1105
						
					}
					},
					{
					xtype: 'spacer'
					},
					{
					text: 'Cancelar',
					ui: 'action',
					handler: function(){
						Ext.getCmp("confirmaBorrarPago").destroy();
					}
					}],
            dock: 'bottom'
        });
	
	var overlay = new Ext.Panel({
			id: 'confirmaBorrarPago',
            floating: true,
            modal: true,
            centered: true,
            width: Ext.platform.isPhone ? 260 : 400,
            height: Ext.platform.isPhone ? 115 : 210,
            styleHtmlContent: true,
			dockedItems: [overlayTb, btns],
            scroll: 'vertical',
			html: '- Si elimina este pago es porque nunca dio el dinero <br>- Por que le estan regresando ese dinero<br>- Eliminar este pago incrementara la deuda con esta compra.'
        });

	overlayTb.setTitle('CONFIRMAR ELIMINACION DE PAGO');
	overlay.show();
}


/*--------------------------------------------------------------------
		VER DETALLES DE LA COMPRA A UN PROVEEDOR
----------------------------------------------------------------------*/
ApplicationProveedores.prototype.verCompra = function( idCompra ){

	 var formBase = new Ext.Panel({
		id: 'detalleCompraPanel',
		 scroll: 'vertical',
			//	items
            items: [{
				id: 'detalleCompraProveedor',
		        html: ''
		    }], 
			//	dock		
            dockedItems: [{
                    xtype: 'toolbar',
                    dock: 'bottom',
                    items: [{
						xtype: 'spacer'
						},{
						text: 'X Cerrar',
						handler: function() {
							//regresar el boton de cliente comun a 1
							Ext.getCmp("detalleCompraPanel").destroy();
							 Ext.getBody().unmask();							
                            }
						}]
					}]
			});
	
       
	   if (Ext.platform.isAndroidOS) {
            formBase.items.unshift({
                xtype: 'component',
                styleHtmlContent: true,
                html: '<span style="color: red">Forms on Android are currently under development. We are working hard to improve this in upcoming releases.</span>'
            });
        }

	if (Ext.platform.isPhone) {
            formBase.fullscreen = true;
        } else {
            Ext.apply(formBase, {
                autoRender: true,
                floating: true,
                modal: true,
                centered: true,
                hideOnMaskTap: false,
                height: 585,
                width: 680
            });
        }
        
		Ext.regModel('comprasDetalleStore', {
    	fields: ['nombre', 'rfc']
		});

		var comprasDetalle= new Ext.data.Store({
    	model: 'comprasDetalleStore'
    	
		});	
		
		POS.AJAXandDECODE({
			action: '1207',
			id_compra: idCompra
			},
			function (datos){//mientras responda AJAXDECODE MOSTRAR CLIENTE
				if(datos.success == true){
					
					comprasDetalle.loadData(datos.datos);
					
					var html = "";
					html += "<div class='ApplicationClientes-item' >" 
					+ "<div class='vendedor'>PRODUCTO</div>" 
					+ "<div class='sucursal'>CANTIDAD</div>" 
					+ "<div class='subtotal'>PRECIO</div>" 
					+ "<div class='subtotal'>SUBTOTAL</div>"
					+ "</div>";
								
					for( a = 0; a < comprasDetalle.getCount(); a++ ){
											
						html += "<div class='ApplicationClientes-item' >" 
						+ "<div class='vendedor'>" + comprasDetalle.data.items[a].denominacion +"</div>" 
						+ "<div class='sucursal'>"+ comprasDetalle.data.items[a].cantidad +"</div>" 
						+ "<div class='subtotal'>$ "+ comprasDetalle.data.items[a].precio+"</div>"
						+ "<div class='subtotal'>$ "+ comprasDetalle.data.items[a].subtotal +"</div>"
						+ "</div>";
					}
								
								//imprimir el html
					Ext.get("detalleCompraProveedor").update("<div class='ApplicationClientes-itemsBox'>" + html +"</div>");
						
				}//FIN DATOS.SUCCES TRUE MOSTRAR CLIENTE
				if(datos.success == false){
					POS.aviso("ERROR","NO SE PUDO CARGAR LA LISTA DE COMPRAS PROBABLEMENTE A ESTE PROVEEDOR NO SE LE HA COMPRADO");
					return;
				}
				},
			function (){//no responde  AJAXDECODE MOSTRAR CLIENTE     
				POS.aviso("ERROR","NO SE PUDO CARGAR LA LISTA DE COMPRAS   ERROR EN LA CONEXION :("); 
				return;
			}
		);//AJAXandDECODE MOSTRAR CLIENTE						
	
	formBase.show();
	
}

/*----------------------------------------------------------
	AGREGAR UN PROVEEDOR
------------------------------------------------------------*/

ApplicationProveedores.prototype.agregarProveedor =  new Ext.form.FormPanel({
		scroll: 'vertical',
		id:'formAgregarProveedor',
		items: [{
			
			xtype: 'fieldset',
			title: 'Proveedor Info',
			instructions: 'Los campos que contienen * son obligatorios',
			items: [
					nombreProveedor = new Ext.form.TextField({
						id: 'nombreProveedor',
						label: '*Nombre'
					})
			,
					rfcProveedor = new Ext.form.TextField({
						id: 'rfcProveedor',
						label: '*RFC'
					})
			,
					direccionProveedor = new Ext.form.TextField({
						id: 'direccionProveedor',
						label: '*Direccion'
					})
			,
					emailProveedor = new Ext.form.TextField({
						id: 'emailProveedor',
						label: 'E-mail'
					})
			,
					telefonoProveedor = new Ext.form.TextField({
						id: 'telefonoProveedor',
						label: 'Telefono'
					})
		   
			]//fin items form
			
		}//,
		
			
		]//,//fin items formpanel
});//fin agregar proveedor



ApplicationProveedores.prototype.guardarProveedor = function(){
						if( nombreProveedor.getValue() =='' || rfcProveedor.getValue() =='' || direccionProveedor.getValue() ==''){
						Ext.getBody().mask(false, '<div class="demos-loading">Loading&hellip;</div>');
						POS.aviso("ERROR!!","LLENAR ALMENOS LOS CAMPOS CON  *");					
						}else{
							POS.AJAXandDECODE({
							action: '1101',
							rfc: rfcProveedor.getValue(),
							nombre: nombreProveedor.getValue(),
							direccion: direccionProveedor.getValue(),
							telefono: telefonoProveedor.getValue(),
							e_mail: emailProveedor.getValue(),
							ubicacion: ubicacionProveedor.getValue()
							},
							function (datos){//mientras responda
								if(datos.success == true){//
										
									rfcProveedor.setValue('');
									nombreProveedor.setValue('');
									direccionProveedor.setValue('');
									telefonoProveedor.setValue('');
									emailProveedor.setValue('');
									ubicacionProveedor.setValue('');
									
									console.log("entro a success de insertar");
											
									POS.AJAXandDECODE({
										action: '1105'
										},
										function (datos){
											if(datos.success == true){
												ApplicationProveedores.currentInstance.mosaic.destroy();
												ApplicationProveedores.currentInstance.provedores = datos.datos;
												ApplicationProveedores.currentInstance.renderMosaico();
												sink.Main.ui.setCard( ApplicationProveedores.currentInstance.finalPanel(), { type: 'slide', direction: 'right'} );
											}
										},
										function (){
											POS.aviso("Provedores","Algo anda mal con tu conexion.");	
										}
									);
									
									
								}else{
									POS.aviso("ERROR!!","LOS DATOS DEL 	PROVEEDOR NO SE MODIFICARON :(");
								}
								
							},
							function (){//no responde	
								POS.aviso("ERROR","NO SE PUDO INSERTAR PROVEEDOR, ERROR EN LA CONEXION :(");	
							}
						);//AJAXandDECODE insertar proveedor
						
						
						}//else de validar vacios
}


/*-----------------------------------------------------------
	SE REALIZÓ LA INSERCION Y MUESTRA PANEL FINAL
-------------------------------------------------------------*/
ApplicationProveedores.prototype.finalPanel = function ()
{
	
	
	return new Ext.form.FormPanel({
	//tipo de scroll
    scroll: 'none',

	baseCls: "ApplicationCompras-mainPanel",

	//toolbar
	dockedItems: [new Ext.Toolbar({
        ui: 'dark',
        dock: 'bottom',
        items: [{
				xtype: 'button',
				ui: 'back',
				text: 'Ver Proveedores',
				handler: function(){
					sink.Main.ui.setCard( ApplicationProveedores.currentInstance.proveedoresWelcome, { type: 'slide', direction: 'right'} );
				}
				},{
				xtype:'spacer'
			},{
				xtype:'button', 
				ui: 'action',
				text:'Agregar Otro Proveedor',
				handler: function(){
					sink.Main.ui.setCard( ApplicationProveedores.currentInstance.agregarProveedor, { type: 'slide', direction: 'right'} );
				}
			}]
    })],
	
	//items del formpanel
    items: [{
			html : 'PROVEEDOR GUARDADO!',
			cls  : 'gracias',
			baseCls: "ApplicationCompras-ventaListaPanel",
		}]
	});
};

//autoinstalar esta applicacion
AppInstaller( new ApplicationProveedores() );
