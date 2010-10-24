/** 
 * @fileoverview Este archivo contiene el modulo Mostrador 
 * del punto de venta. Es accesible por todos los cajeros,
 * y gerentes del sistema.
 *
 * @author 
 * @version 0.1 
 */




/**
 * Construir un nuevo objeto de tipo ApplicationProveedores.
 * @class Esta clase se encarga de la creacion de interfacez
 * que intervinen en la manipulación de proveedores. 
 * @constructor
 * @throws MemoryException Si se agota la memoria
 * @return Un objeto del tipo ApplicationProveedores
 */
ApplicationProveedores= function ()
{
	if(DEBUG){
		console.log("ApplicationProveedores: construyendo");
	}
	
	//nombre de la aplicacion
	this.appName = "Proveedores";
	
	//ayuda sobre esta applicacion
	this.ayuda = "Ayuda sobre este modulo de prueba <br>, html es valido <br> :D";

    //submenues en el panel de la izquierda
    this.leftMenuItems = null;

    //panel principal
	this.mainCard = this.proveedoresWelcome;

	//submenues en el panel de la izquierda
	this._initToolBar();
			
	//variable auxiliar para referirse a esta instancia del objeto
    //solo funciona al instanciarse una vez, si el constructor
    //se vuelve a ejecutar esta variable contendra el ultimo 
    //objeto construido	
	ApplicationProveedores.currentInstance = this;	

	return this;
	
};

/**
 * Contiene el panel principal
 * @type Ext.Panel
 */
ApplicationProveedores.prototype.mainCard = null;

/**
 * Nombre que se muestra en el menu principal.
 * @type String
 */
ApplicationProveedores.prototype.appName = null;

/**
 * Items que que colocaran en el menu principal al cargar este modulo.
 * De no requerirse ninguno, hacer igual a null
 * @type Ext.Panel
 */
ApplicationProveedores.prototype.leftMenuItems = null;

/**
 * Texto de ayuda formateado en HTML para este modulo.
 * @type String
 */
ApplicationProveedores.prototype.ayuda = null;

/**
 * Items que estan anclados a este panel.
 * @type 
 */
ApplicationProveedores.prototype.dockedItems = null;






ApplicationProveedores.prototype.ProveedoresListStore = null;

ApplicationProveedores.prototype.providers = null;

ApplicationProveedores.prototype.actualizaProveedor=null;

ApplicationProveedores.prototype.updateProviderForm= null;

ApplicationProveedores.prototype.record = null;

ApplicationProveedores.prototype.dockedItemsGuardar = null;

ApplicationProveedores.prototype.proveedorSelectedHtml="";





/**
 * Crea un Ext.Panel
 * @return Ext.Panel
 * @type void
 */
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


/**
 * Crea un Ext.Panel que contiene un formulario para agregar un nuevo proveedor.
 * @type Ext.Panel
 * @return void
 */
ApplicationProveedores.prototype.agregarProveedor =  new Ext.form.FormPanel({
		scroll: 'vertical',
		id:'formAgregarProveedor',
		baseCls :'formAgregarProveedor',
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
			
		},
		{
			xtype: 'button',
			text: 'Guardar',
			ui: 'action',
			maxWidth: 150,
			handler: function(){
				ApplicationProveedores.currentInstance.guardarProveedor();
			}
		}
			
	]//,//fin items formpanel
});//fin agregar proveedor




/**
 * Guarda Objetos con los datos de cada proveedor.
 * @type Array
 */
ApplicationProveedores.prototype.provedores = [];


ApplicationProveedores.prototype.proveedorSelected=null;





/**
 * Crea un Ext.Toolbar en cual se guarda en {@link ApplicationProveedores#dockedItems}
 * y despues se los agrega a {@link ApplicationVender#mainCard}
 * @return void
 */
ApplicationProveedores.prototype._initToolBar = function ()
{
	/*	
	 *	DOCKED ITEMS PARA EL PANEL "proveedoresWelcome"
	 */
	var buscar = [{
		xtype: 'textfield',
		id:'ApplicationProveedores_searchField',
		inputCls: 'caja-buscar',
		listeners:{
		    'render': function( ){
			    //medio feo, pero bueno
				Ext.get("ApplicationProveedores_searchField").first().dom.setAttribute("onkeyup","ApplicationProveedores.currentInstance.mosaic.doSearch( this.value )");
			}
		}
	}];

	var agregar = [{
	    xtype: 'button',
		text: 'Nuevo Proveedor',
		ui: 'action',
		handler: function(){
	    	console.log("ApplicacionProveedores: agregarProveedor called....");
			sink.Main.ui.setCard( ApplicationProveedores.currentInstance.agregarProveedor, 'slide' );
		}
	}];		

    this.dockedItems = [new Ext.Toolbar({
        ui: 'dark',
        dock: 'bottom',
        items: buscar.concat({xtype:'spacer'}).concat(agregar)
    })];
    
	
	//agregar este dock a el panel principal
	this.proveedoresWelcome.addDocked( this.dockedItems );
	

	/*
	 *	DOCKED ITEMS PARA EL PANEL "agregarProveedor"
	 */
		
	var regresar =[{
	    xtype: 'button',
		id: 'cancelarGuardarProveedor',
		text: 'Regresar',
		ui: 'back',
		handler: function(event,button) {
	    	sink.Main.ui.setCard( ApplicationProveedores.currentInstance.proveedoresWelcome, { type: 'slide', direction: 'right'} );		
	    }//fin handler cancelar cliente			
	}];//fin boton cancelar

	
	this.agregarProveedor.addDocked(  
	    new Ext.Toolbar ({ 
		    ui: 'dark',
		    dock: 'bottom',
		    items: regresar.concat({xtype: 'spacer'})
	    }) 
	);//addDocked
};


/**
 * Crea un objeto de tipo Mosaico y lo guarda en {@link ApplicationProveedores#mosaic}.
 * @see Mosaico
 * @return void
 */
ApplicationProveedores.prototype.renderMosaico = function ()
{

	if(DEBUG)
	{
		console.log("ApplicationProvedores: rendering " +ApplicationProveedores.currentInstance.provedores.length+ " items." );
	}

	var mItems = [];
	
	for(a = 0; a < ApplicationProveedores.currentInstance.provedores.length; a++){
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

/**
 * Obtiene una lista con los datos de todos los proveedores y la almacena en
 * {@link ApplicationProveedores#provedores}, posteriormente manda llamar al
 * metodo {@link ApplicationProveedores#renderMosaico } que se encarga de crear
 * un arreglo de objetos con los datos de los proveedores que sera usado para
 * crear el mosaico.
 * @see ApplicationProveedores#renderMosaico
 * @return void
 */
ApplicationProveedores.prototype.getProvedores = function ()

{
	
	if(DEBUG){
		console.log( "ApplicationProvedores: refrescando lista de provedores"  );
	}
	
	POS.AJAXandDECODE({
		action: '1105'
		},
		function (datos)
		{
			if(datos.success == true)
			{
				if(DEBUG){
					console.log("Entro success de getProveedores: ", datos);					
				}
                
                //carga los datos de los proveedores
				ApplicationProveedores.currentInstance.provedores = datos.datos;
				//manda construir el mosaico que contiene los proveedores
				ApplicationProveedores.currentInstance.renderMosaico();
			}
		},
		function ()
		{
			POS.aviso("Provedores","Algo anda mal con tu conexion.");	
		}
	);

};


/**
 *Establece el proveedor actual y lo guarda en {@link ApplicationProveedores#proveedorSelected}
 *
 *@param (Object)
 *@see ApplicationProveedores#listarCompras
 *@see ApplicationProveedores#listarComprasCredito
 *@return void
 */
ApplicationProveedores.prototype.doVerProvedor = function (provedor)
{
	
	if(DEBUG){
		console.log("ApplicationProveedore: viendo proveedor " ,  provedor);
	}

    //Establece el proveedor acual
	ApplicationProveedores.currentInstance.proveedorSelected = provedor;
	
	//crea el carrusel con los datos de ese proveedor
	var newPanel = this.createPanelForProvedor(provedor);
	
	ApplicationProveedores.currentInstance.listarCompras();
	
	ApplicationProveedores.currentInstance.listarComprasCredito();
	
	
	sink.Main.ui.setCard( newPanel, 'slide' );
	
};

/**
 *Establece el proveedor actual y lo guarda en {@link ApplicationProveedores#proveedorSelected}
 *
 *@param (Object)
 *@see ApplicationProveedores#listarCompras
 *@see ApplicationProveedores#listarComprasCredito
 *@return void
 */
ApplicationProveedores.prototype.createPanelForProvedor = function ( provedor )
{
	if(!this.carousel)
	{

		this.carousel = new Ext.Carousel({
			
			defaults: { 
				//cls: 'ApplicationProveedores-detallesProveedor'
				scroll: 'vertical'
			},
			items: [
			    {
				    cls: 'ApplicationProveedores-detallesProveedor',
				    html: '<div id="detalles-proveedor">'+this.renderProvedorDetalles(provedor)+'</div>'
			    }, 
			    {
				    scroll: 'vertical',
				    xtype: 'panel',
				    title: 'compras',
				    id: 'comprasProveedorSucursalPanel',
				    html:'<div id = "comprasProveedorSucursal" style = "width:100%"; height:100%"></div>'
			    }, 
			    { 
				    scroll: 'vertical',
				    xtype: 'panel',
				    title: 'creditos',
				    id: 'comprasProveedorCreditoPanel',
				    html:'<div id = "comprasProveedorCredito" style = "width:100%"; height:100%"></div>'
			    }
		    ]
		});
	}
	else
	{
		
		Ext.get("detalles-proveedor").update(this.renderProvedorDetalles(provedor));
		
	}

	var regresar = [{
	    xtype: 'button',
		text: 'Regresar',
		ui: 'back',
		handler : function(){
	    	sink.Main.ui.setCard( 
	    		ApplicationProveedores.currentInstance.mainCard, {
	    			type: 'fade',
					duration: 500
				}
			);
		}
	}];		


	var surtir = [{
	    xtype: 'button',
		text: 'Surtir',
		ui: 'action',
		handler: function(){
				
			var appComProv = new ApplicationComprasProveedor();
				
			appComProv.providerId = provedor.id_proveedor;
			appComProv.nombreProv = provedor.nombre;
			appComProv.comprarPanel( provedor.id_proveedor);
			sink.Main.ui.setCard( appComProv.surtir, 'slide' );
				
			Ext.get("CarruselSurtirProductosSucursal").setStyle({
				'background-image':'url("media/g3.png")'								   
			});
				
		}
	}];
	
		
    var dockedItems = [new Ext.Toolbar({
        ui: 'dark',
        dock: 'bottom',
        items: regresar.concat({xtype:'spacer'}).concat(surtir)
    })];


	var panel = new Ext.Panel({
		dockedItems : dockedItems,
		cls:'ApplicationProveedores-CarrouselBackgroud',
		layout: {
			type: 'vbox',
			align: 'stretch'
		},
		defaults: {
	      flex: 1
		},
		items: [this.carousel]
	});
		
	return panel;
};



/**
 *Raliza una consulta a la BD y obtiene una lista de todas las 
 *compras realizadas (a credito y en efectivo) al proveedor actual y las muestra en el panel
 *'comprasProveedorSucursal'.
 *@see ApplicationProveedores#proveedorSelected
 *@return void
 */
ApplicationProveedores.prototype.listarCompras = function (){
	
	//guarda en record_proveedor los datos del proveedor actual
	var record_proveedor = ApplicationProveedores.currentInstance.proveedorSelected;

    //registra el modelo para compras proveedor
	Ext.regModel('comprasProvStore', {
    	fields: ['nombre', 'rfc']
	});
	
	//indica el modelo a seguir para el store de compras proveedor
	var comprasProveedor = new Ext.data.Store({
    	model: 'comprasProvStore' 
	});	
	
	
	//cabecera de datos del cliente seleccionado
	
	var proveedorHtml = ""; 	
	var html = "";

    //obtiene las compras hechas a un proveedor en especifico en esa sucursal
	POS.AJAXandDECODE({
    	action: '1206',
		id_proveedor: record_proveedor.id_proveedor 
    	},
		function (datos){
		    //mientras responda AJAXDECODE LISTAR VENTAS CLIENTE
    		if(datos.success === true)
    		{
                
                Ext.get("comprasProveedorSucursal").update("");
                
                //carga los datos al store
			    comprasProveedor.loadData(datos.datos);
					
				html += "<div class = 'ApplicationProveedores-Title'> Todas las Compras </div>"	
					
				html += "<div class='ApplicationProveedores-Item'>";
			    html += "   <div class='trash'><div style = 'width:20px; height:20px;'></div></div>";
			    html += "   <div class='id'>No.</div>";
				html += "   <div class='tipo'>Tipo Venta</div>";
				html += "   <div class='fecha'>Fecha</div>";
				html += "   <div class='sucursal'>Sucursal</div>";
				html += "   <div class='vendedor'>Realizo compra</div>";
				html += "   <div class='subtotal'>Subtotal</div>";
				html += "   <div class='iva'>IVA</div>";
				html += "   <div class='total'>TOTAL</div>";
				html += "   <div class='total'>SALDO</div>";
				html += "</div>";
					
				//renderear el html

				for( a = 0; a < comprasProveedor.getCount(); a++ )
				{
				    
					html += "<div class='ApplicationProveedores-Item' >";
					html += "   <div class='trash' onclick='ApplicationProveedores.currentInstance.verCompra(" + comprasProveedor.data.items[a].data.id_compra +  ")'>";
					html += "       <img height=20 width=20 src='media/themes/default/icons/search.png' />";
					html +=     "</div>";
				    html += "   <div class='id'>" + comprasProveedor.data.items[a].data.id_compra +"</div>";
				    html += "   <div class='tipo'>" + comprasProveedor.data.items[a].data.tipo_compra+"</div>";
					html += "   <div class='fecha'>"+ comprasProveedor.data.items[a].data.fecha +"</div>";
					html += "   <div class='sucursal'>"+ comprasProveedor.data.items[a].data.descripcion +"</div>";
					html += "   <div class='vendedor'>"+ comprasProveedor.data.items[a].data.nombre +"</div>";
					html += "   <div class='subtotal'>$"+ comprasProveedor.data.items[a].data.subtotal +"</div>";
					html += "   <div class='iva'>$"+ comprasProveedor.data.items[a].data.iva +"</div>";
					html += "   <div class='total'>$"+ comprasProveedor.data.items[a].data.total +"</div>";
					
					if(comprasProveedor.data.items[a].data.tipo_compra == "credito")
					{
					    html += comprasProveedor.data.items[a].data.adeudo > 0 ? "<div class='saldo'>$"+ comprasProveedor.data.items[a].data.adeudo +"</div>" : "";
					}
					
					html += "</div>";
				}//fin for
										
				//imprimir el html
				proveedorHtml += html;
					
					//console.log(comprasProveedor.data.items);
			}//if
			
			if(datos.success == false)
			{							
			    proveedorHtml += "<div class='ApplicationClientes-itemsBox' id='no_ComprasProv' ><div class='no-data'>"+datos.reason+"</div></div>"
			}
				
			Ext.get("comprasProveedorSucursal").update(proveedorHtml);
				
		},
		function ()
		{
		    //no responde AJAXDECODE DE VENTAS CLIENTE
			POS.aviso("ERROR","NO SE PUDO CARGAR LA LISTA DE VENTAS   ERROR EN LA CONEXION :(");      
		}
	);//AJAXandDECODE LISTAR VENTAS CLIENTE
			
	
};

/**
 *Raliza una consulta a la BD y obtiene una lista de todas las 
 *compras a credito (que no se han liquidado) al proveedor actual y las muestra en el panel
 *'comprasProveedorCredito'.
 *@see ApplicationProveedores#proveedorSelected
 *@return void
 */
ApplicationProveedores.prototype.listarComprasCredito = function (){
	
	//guarda en record_proveedor los datos del proveedor actual 
	var record_proveedor = ApplicationProveedores.currentInstance.proveedorSelected;
	
	//registra el modelo para compras proveedor a credito
	Ext.regModel('comprasCreditoStore', {
    	fields: ['nombre', 'rfc']
	});

    //indica el modelo a seguir para el store de compras proveedor a credito
	var comprasProveedorCredito = new Ext.data.Store({
    	model: 'comprasCreditoStore'  
	});	
	
		
	var html = "";
	
	POS.AJAXandDECODE({
		action: '1209',
		id_proveedor: record_proveedor.id_proveedor//recor[0].id_cliente
		},
		function (datos){//mientras responda AJAXDECODE LISTAR VENTAS CLIENTE
			if(datos.success === true)
			{
				comprasProveedorCredito.loadData(datos.datos);
				
				html += "<div class = 'ApplicationProveedores-Title'> Compras a Credito</div>"	
					
				html += "<div class='ApplicationProveedores-Item' >";
				html += "   <div class='trash'><div style = 'width:20px; height:20px;'></div></div>";
			    html += "   <div class='id'>No.</div>";
				html += "   <div class='fecha'>Fecha</div>";
				html += "   <div class='sucursal'>Sucursal</div>";
				html += "   <div class='vendedor'>Realizo compra</div>";
				html += "   <div class='total'>TOTAL</div>";
				html += "   <div class='total'>ABONADO</div>";
				html += "   <div class='total'>ADEUDO</div>";
				html += "   <div class='subtotal'>VER ABONOS</div>";
				html += "   <div class='total'>STATUS</div>";
				html += "</div>";
					
				//renderear el html
				for( a = 0; a < comprasProveedorCredito.getCount(); a++ )
				{
					var compra = comprasProveedorCredito.data.items[a];
					var tot = parseFloat(compra.data.subtotal) + parseFloat(compra.data.iva);
					var adeudo = tot - compra.data.abonado;
					//console.log("-------------------- en la comprata: "+compra.data.id_comprata+" abonado: "+compra.data.abonado);
					var status="";
					if (adeudo <= 0)
					{
						status="<div class='pagado'>PAGADO</div>";
					}
					else
					{
							
					    status ="<div class='abonar' onclick='ApplicationProveedores.currentInstance.abonarCompra(" + compra.data.id_compra + " , "+ tot +" , "+ adeudo +", "+ compra.data.abonado +")'>ABONAR</div>";
					}
					
					html += "<div class='ApplicationProveedores-Item'>";
					html +=	"   <div class='trash' onclick='ApplicationProveedores.currentInstance.verCompra(" + compra.data.id_compra+ ")'>";
					html += "       <img height=20 width=20 src='media/themes/default/icons/search.png' />";
					html += "   </div>";
					html += "   <div class='id'>" + compra.data.id_compra +"</div>";
					html += "   <div class='fecha'>"+ compra.data.fecha +"</div>";
					html += "   <div class='sucursal'>"+ compra.data.sucursal +"</div>";
					html += "   <div class='vendedor'>"+ compra.data.comprador +"</div>";
					html += "   <div class='total'>$"+ tot +"</div>";
					html += "   <div class='total' id='abonadoCompra_"+compra.data.id_compra+"'>$"+ compra.data.abonado +"</div>";
					html += "   <div class='total' id='adeudoCompra_"+compra.data.id_compra+"'>$"+ adeudo +"</div>";
					html += "   <div class='subtotal' onclick='ApplicationProveedores.currentInstance.verPagosCompra(" + compra.data.id_compra+ ")'>";
					html += "       <img style = 'margin-top:-2px;' height=20 width=20 src='media/themes/default/icons/compose.png' />";
					html += "   </div>";
					html +=     status;
					html += "</div>";
														
						
				}//fin for comprasProveedorCredito
					
				//imprimir el html
				Ext.get("comprasProveedorCredito").update("<div class='ApplicationClientes-itemsBox'>" + html +"</div>");
				//console.log(ventasCliente.data.items);
			}//if
				
			if(datos.success == false)
			{
					
			    Ext.get("comprasProveedorCredito").update("<div class='ApplicationClientes-itemsBox' id='no_ComprasCreditoProv' ><div class='no-data'>"+datos.reason+"</div></div>");
			    
			}
		},
		function ()
		{//no responde AJAXDECODE DE VENTAS CLIENTE
	
				POS.aviso("ERROR","NO SE PUDO CARGAR LA LISTA DE COMPRAS   ERROR EN LA CONEXION :(");      
				
		}
	);//AJAXandDECODE LISTAR VENTAS CLIENTE
				
};




/**
 *Construye bloque de código formateado que muestra los datos del proveedor solicitado.
 *@param {Object} Objeto con los datos del proveedor
 *@type String
 *@return Un bloque de código HTML
 */
ApplicationProveedores.prototype.renderProvedorDetalles = function ( provedor )
{

	var html = "";
	
	html = "<div class='ApplicationProveedores-Detalles'>";
	html += "   <div class='nombre'>" + provedor.nombre + "</div>";
	html += "   <div class='direccion'>" + provedor.direccion + "</div>";
	html += "   <div class='mail'>" + provedor.e_mail + "</div>";
	html += "   <div class='id_provedor'>" + provedor.id_proveedor + "</div>";
	html += "   <div class='rfc'>" + provedor.rfc + "</div>";
	html += "   <div class='telefono'>" + provedor.telefono + "</div>";
    html += "</div>";
    
	return html;
	
};



/*-------------------------------------------------------
	PAGOS HECHOS SOBRE 1 COMPRA EN ESPECIFICO
---------------------------------------------------------*/
ApplicationProveedores.prototype.verPagosCompra = function( idCompra ){

    var formBase = new Ext.Panel({
	    id: 'pagosCompraPanel',
	    scroll: 'vertical',		
        items: [{
		    id: 'pagosCompraProveedor',
		    html: ''
		}], 
		dockedItems: [{
            xtype: 'toolbar',
            dock: 'bottom',
            items: [
                {
                    xtype: 'spacer'
                },
                {
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
				}
			]//items
		}]//dockedItems
	});//formBase
	
    

	if (Ext.is.Phone) 
	{
        formBase.fullscreen = true;
    } 
    else 
    {
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
	    function (datos)
	    {
	        //mientras responda AJAXDECODE MOSTRAR CLIENTE
            if(datos.success == true)
            {
					
		        comprasDetalle.loadData(datos.datos);
					
			    var html = "";
		
			    html += "<div class='ApplicationClientes-Item'>"; 
			    html += "   <div class='vendedor'># COMPRA</div>"; 
			    html += "   <div class='sucursal'>FECHA</div>"; 
			    html += "   <div class='subtotal'>MONTO</div>";
			    html += "   <div class='subtotal'></div>";
			    html += "</div>";
								
			    for( a = 0; a < comprasDetalle.getCount(); a++ )
			    {
											
			        html += "<div class='ApplicationClientes-Item' id='pago_Borrar_"+comprasDetalle.data.items[a].data.id_pago+"'>";
				    html += "<div class='vendedor'>" + comprasDetalle.data.items[a].data.id_compra +"</div>"; 
				    html += "<div class='sucursal'>"+ comprasDetalle.data.items[a].data.fecha +"</div>"; 
				    html += "<div class='subtotal'>$ "+ comprasDetalle.data.items[a].data.monto+"</div>";
				    html += "<div class='abonar' onclick='ApplicationProveedores.currentInstance.EliminarabonoCompra(" +  comprasDetalle.data.items[a].data.id_pago +")'>ELIMINAR</div>";
				    html += "</div>";
			    }
								
			    //imprimir el html
			    Ext.get("pagosCompraProveedor").update("<div class='ApplicationClientes-itemsBox'>" + html +"</div>");
						
		    }//FIN DATOS.SUCCES TRUE MOSTRAR CLIENTE
		    else
		    {
					
		        html = "<div class='ApplicationClientes-itemsBox' id='no_pagosComprarProv' ><div class='no-data'>"+datos.reason+"</div></div>";
					
			    Ext.get("pagosCompraProveedor").update(html);
					
			    return;
		    }
	    },
	    function ()
	    {//no responde  AJAXDECODE MOSTRAR CLIENTE     
	        POS.aviso("ERROR","NO SE PUDO CARGAR LA LISTA DE PAGOS ERROR EN LA CONEXION :("); 
		    return;
	    }
	);//AJAXandDECODE MOSTRAR CLIENTE						
	
	formBase.show();
	
	
	//Se le da fondo al panel padre del panel generado
	
	Ext.get("pagosCompraProveedor").parent().parent().setStyle({
	    'background-image':'url("media/g3.png")'								   
	});
	
};


/*------------------------------------------------------------
	ABONAR A UNA COMPRA QUE LA SUCURSAL ADEUDA
--------------------------------------------------------------*/

ApplicationProveedores.prototype.abonarCompra = function( idCompra , total , adeudo ,totalAbonado ){
	
	var clienteHtml = "";
	
	clienteHtml += "<div class='ApplicationProveedores-itemsBox'>";
	clienteHtml += "    <div class='no-data' id='abonar-compraP'>";
	clienteHtml += "        <div class='nombre'>Abono para la compra '"+idCompra+"'</div>";
	clienteHtml += "        <div class='nombre'> Total de Compra: " + total + "</div>";
	clienteHtml += "        <div class='nombre'> Adeuda: " + adeudo + "</div>";
	clienteHtml += "    </div>";
	clienteHtml += "</div>";
	clienteHtml += "<br>";
		
		
    var abonaPanel = new Ext.form.FormPanel({
        id: 'abonarCompraPanel',
        scroll: 'vertical',        
        items: [
            {
		        id: 'abonarCompraProveedor',
		        html: clienteHtml
		    },
            {                                                       
                xtype: 'fieldset',
                title: 'Detalles del Pago',
	    		instructions: 'Inserte unicamente numeros no letras',
                items:[
			        montoAbonoCompra = new Ext.form.TextField({
                        id: 'montoAbonoCompra',
					    label: 'Abona $',
					    listeners:{
				        	change: function(){
				        		if(this.getValue() > adeudo)
				        		{
							        this.setValue(adeudo);	
								    Ext.getCmp("restariaCompra").setValue(adeudo - this.getValue());
							    }
							    else
							    {
							        Ext.getCmp("restariaCompra").setValue(adeudo - this.getValue());
							    }
						    },
						    blur: function(){
						        if(this.getValue() > adeudo)
						        {
							        this.setValue(adeudo);	
							        Ext.getCmp("restariaCompra").setValue(adeudo - this.getValue());
							    }
							    else
							    {
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
						            
					            if(Ext.getCmp("pagaAbonoCompra").getValue() < Ext.getCmp("montoAbonoCompra").getValue())
					            {
							        this.setValue(Ext.getCmp("montoAbonoCompra").getValue());
								    Ext.getCmp("cambioAbonoCompra").setValue(this.getValue() - montoAbonoCompra.getValue());
								    Ext.getCmp("restariaCompra").setValue(adeudo - montoAbonoCompra.getValue());
							    }
							
							    if(Ext.getCmp("pagaAbonoCompra").getValue >= Ext.getCmp("montoAbonoCompra").getValue())
							    {
							        Ext.getCmp("cambioAbonoCompra").setValue(Ext.getCmp("pagaAbonoCompra").getValue() - montoAbonoCompra.getValue());	
								    Ext.getCmp("restariaCompra").setValue(adeudo - montoAbonoCompra.getValue());
							    }
						    },
						    blur: function(){
							
						        if(Ext.getCmp("pagaAbonoCompra").getValue() < Ext.getCmp("montoAbonoCompra").getValue())
						        {
							        Ext.getCmp("pagaAbonoCompra").setValue(Ext.getCmp("montoAbonoCompra").getValue());
								    Ext.getCmp("cambioAbonoCompra").setValue(this.getValue() - montoAbonoCompra.getValue());
								    Ext.getCmp("restariaCompra").setValue(adeudo - montoAbonoCompra.getValue());
							    }
							
							    if(Ext.getCmp("pagaAbonoCompra").getValue >= Ext.getCmp("montoAbonoCompra").getValue())
							    {
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
                ]//items
			}//fieldset		                
        ], 
        dockedItems: [{
            xtype: 'toolbar',
            dock: 'bottom',
            items: [
                {
                   text: 'Abonar',
			        ui: 'action',
			        handler: function(){				            
			            POS.AJAXandDECODE({
			                	action: '1301',
					            id_compra: idCompra,
				        	    monto: Ext.getCmp("montoAbonoCompra").getValue()
				            },
   						    function (datos)
					        {
					            if(datos.success == true)
					            {										
						            ApplicationProveedores.currentInstance.listarComprasCredito();
							        Ext.getCmp("abonarCompraPanel").destroy();
						         	Ext.getBody().unmask();
						        }
						        else
						        {
						            POS.aviso("Abono Compra",""+datos.reason);		
						        }
					        },
					        function ()
					        {
					            POS.aviso("Provedores","Algo anda mal con tu conexion.");	
					        }
				        );								
			        }
		        },
				{
				    xtype: 'spacer'
				},
				{
				    //-------------------------------------------------------------------------------
					//			cancelar
					//-------------------------------------------------------------------------------
					text: 'X Cancelar',
					handler: function() 
					{
						//regresar el boton de cliente comun a 1
						Ext.getCmp("abonarCompraPanel").destroy();
						 Ext.getBody().unmask();
						//ocultar este form
						//form.hide();							
                       }
				}
			]//items
    	}]//dockedItems
    });//abonaPanel
	
   

    if (Ext.is.Phone) 
    {
        abonaPanel.fullscreen = true;
    } 
    else 
    {
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
        
	/*
		Se le da un fono al panel contenedor
	*/
	
	Ext.get("abonar-compraP").setStyle({
					'background-image':'url("media/g3.png")'								   
	});
	
	Ext.get("abonar-compraP").parent().parent().setStyle({
					'background-image':'url("media/g2.png")'								   
	});
	
	Ext.get("abonarCompraProveedor").parent().parent().setStyle({
					'background-image':'url("media/g2.png")'								   
	});
};

/*-------------------------------------------------------------------
	ELIMINAR UN PAGO DE UNA COMPRA
---------------------------------------------------------------------*/

ApplicationProveedores.prototype.EliminarabonoCompra = function (id_Pago){
	
    var overlayTb = new Ext.Toolbar({
        dock: 'top'
    });

	var btns = new Ext.Toolbar({
    	items :[
    	    {
			    text: 'Eliminar',
				ui: 'action',
				handler: function(){
			
			    	POS.AJAXandDECODE({
			       		action: '1302',
					    id_pago: id_Pago
					},
					function (datos)
					{//mientras responda
					    if(!datos.success)
					    {
						    POS.aviso("ERROR",""+datos.reason);
						}
						ApplicationProveedores.currentInstance.listarComprasCredito();
						Ext.get("pago_Borrar_"+id_Pago).remove();
						Ext.getCmp("confirmaBorrarPago").destroy();
					},
					function ()
					{//no responde
					    POS.aviso("ERROR","NO SE PUDO ELIMINAR PAGO  ERROR EN LA CONEXION :(");	
					});//AJAXandDECODE 1105
						
				}//handler
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
			}
		],
        dock: 'bottom'
    });//btns
	
	var overlay = new Ext.Panel({
	    id: 'confirmaBorrarPago',
        floating: true,
        modal: true,
        centered: true,
        width: Ext.is.Phone ? 260 : 400,
        height: Ext.is.Phone ? 115 : 210,
        styleHtmlContent: true,
	    dockedItems: [overlayTb, btns],
        scroll: 'vertical',
		html: '- Si elimina este pago es porque nunca dio el dinero <br>- Por que le estan regresando ese dinero<br>- Eliminar este pago incrementara la deuda con esta compra.'
    });

	overlayTb.setTitle('CONFIRMAR ELIMINACION DE PAGO');
	overlay.show();
	
};


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
        dockedItems:[{
            xtype: 'toolbar',
            dock: 'bottom',
            items: [
                {
		    	    xtype: 'spacer'
				},
				{
				    text: 'X Cerrar',
				    handler: function() {
				        //regresar el boton de cliente comun a 1
						Ext.getCmp("detalleCompraPanel").destroy();
				        Ext.getBody().unmask();							
                    }
				}
			]//items
		}]
	});//formBase	    

	if (Ext.is.Phone)   
	{
        formBase.fullscreen = true;
    } 
    else 
    {
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
		
    POS.AJAXandDECODE(
        {
            action: '1207',
		    id_compra: idCompra
	    },
	    function (datos)
	    {
	        //mientras responda AJAXDECODE MOSTRAR CLIENTE
	        if(datos.success == true)
	        {					
		     
		        comprasDetalle.loadData(datos.datos);
					
			    var html = "";
			
			    html += "<div class='ApplicationClientes-Item'>"; 
			    html += "   <div class='vendedor'>PRODUCTO</div>"; 
			    html += "   <div class='sucursal'>CANTIDAD</div>"; 
			    html += "   <div class='subtotal'>PRECIO</div>"; 
			    html += "   <div class='subtotal'>SUBTOTAL</div>";
			    html += "</div>";
								
			    for( a = 0; a < comprasDetalle.getCount(); a++ )
			    {
											
			        html += "<div class='ApplicationClientes-Item'>";
				    html += "   <div class='vendedor'>" + comprasDetalle.data.items[a].data.denominacion +"</div>";
				    html += "   <div class='sucursal'>"+ comprasDetalle.data.items[a].data.cantidad +"</div>";
				    html += "   <div class='subtotal'>$ "+ comprasDetalle.data.items[a].data.precio+"</div>";
				    html += "   <div class='subtotal'>$ "+ comprasDetalle.data.items[a].data.subtotal +"</div>";
				    html += "</div>";
			    }
								
			    //imprimir el html
			    Ext.get("detalleCompraProveedor").update("<div class='ApplicationClientes-itemsBox'>" + html +"</div>");
						
		    }//FIN DATOS.SUCCES TRUE MOSTRAR CLIENTE
		    else
		    {

		        html = "<div class='ApplicationClientes-itemsBox' id='no_detalleCompraProv' ><div class='no-data'>"+datos.reason+"</div></div>";
					
			    Ext.get("detalleCompraProveedor").update(html);
			
			    return;
		    }
	    },
	    function ()
	    {
	        //no responde  AJAXDECODE MOSTRAR CLIENTE     
		    POS.aviso("ERROR","NO SE PUDO CARGAR LA LISTA DE COMPRAS   ERROR EN LA CONEXION :("); 
		    return;
	    }
	);//AJAXandDECODE MOSTRAR CLIENTE						
	
	formBase.show();
	
	/*
		Se le da el estilo al formulario q se desplega con los detalles de la venta
		a un fondo obscuro (g3.png)
	*/
	Ext.get("detalleCompraProveedor").parent().parent().setStyle({
	    'background-image':'url("media/g3.png")'								   
	});
};




ApplicationProveedores.prototype.guardarProveedor = function(){

    if( nombreProveedor.getValue() =='' || rfcProveedor.getValue() =='' || direccionProveedor.getValue() =='')
    {
	    Ext.getBody().mask(false, '<div class="demos-loading">Loading&hellip;</div>');
		POS.aviso("ERROR!!","LLENAR ALMENOS LOS CAMPOS CON  *");					
	}
	else
	{
	    POS.AJAXandDECODE(
	        {
			    action: '1101',
				rfc: rfcProveedor.getValue(),
				nombre: nombreProveedor.getValue(),
				direccion: direccionProveedor.getValue(),
				telefono: telefonoProveedor.getValue(),
			    e_mail: emailProveedor.getValue(),
				ubicacion: ubicacionProveedor.getValue()
			},
			function (datos)
			{
			    //mientras responda
				if(datos.success == true)
				{//
										
				    rfcProveedor.setValue('');
					nombreProveedor.setValue('');
					direccionProveedor.setValue('');
					telefonoProveedor.setValue('');
					emailProveedor.setValue('');
					ubicacionProveedor.setValue('');
									
					if(DEBUG){
					    console.log("entro a success de insertar");
					}
											
					POS.AJAXandDECODE({
					    action: '1105'
					},
					function (datos)
					{
					    if(datos.success == true)
					    {
						    ApplicationProveedores.currentInstance.mosaic.destroy();
							ApplicationProveedores.currentInstance.provedores = datos.datos;
							ApplicationProveedores.currentInstance.renderMosaico();
						    sink.Main.ui.setCard( ApplicationProveedores.currentInstance.finalPanel(), { type: 'slide', direction: 'right'} );
						}
					},
					function ()
					{
					    POS.aviso("Provedores","Algo anda mal con tu conexion.");	
					}
					);				
				}
				else
				{
				    POS.aviso("ERROR!!","LOS DATOS DEL 	PROVEEDOR NO SE MODIFICARON :(");
				}
								
			},
			function ()
			{
			    //no responde	
				POS.aviso("ERROR","NO SE PUDO INSERTAR PROVEEDOR, ERROR EN LA CONEXION :(");	
			}
		);//AJAXandDECODE insertar proveedor
						
						
	}//else de validar vacios
};


/*-----------------------------------------------------------
	SE REALIZÓ LA INSERCION Y MUESTRA PANEL FINAL
-------------------------------------------------------------*/
ApplicationProveedores.prototype.finalPanel = function (){
	
	return new Ext.form.FormPanel({

        scroll: 'none',
	    baseCls: "ApplicationCompras-mainPanel",
	    //toolbar
	    dockedItems:[new Ext.Toolbar({
            ui: 'dark',
            dock: 'bottom',
            items: [
                {
				    xtype: 'button',
				    ui: 'back',
				    text: 'Ver Proveedores',
				    handler: function(){
					    sink.Main.ui.setCard( ApplicationProveedores.currentInstance.proveedoresWelcome, { type: 'slide', direction: 'right'} );
				    }
				},
				{
				    xtype:'spacer'
			    },
			    {
				    xtype:'button', 
				    ui: 'action',
				    text:'Agregar Otro Proveedor',
				    handler: function(){					
					    sink.Main.ui.setCard( ApplicationProveedores.currentInstance.agregarProveedor, { type: 'slide', direction: 'right'} );					
				    }
			    }
			]
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
