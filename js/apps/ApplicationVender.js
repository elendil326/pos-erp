
/** 
 * @fileoverview Este archivo contiene el modulo Mostrador 
 * del punto de venta. Es accesible por todos los cajeros,
 * y gerentes del sistema.
 *
 * @author Alan Gonzalez alan@caffeina.mx
 * @version 0.1 
 */




/**
 * Construir un nuevo objeto de tipo ApplicationVender.
 * @class Esta clase se encarga de la creacion de interfacez
 * que intervinen el uso del mostrador. 
 * @constructor
 * @throws MemoryException Si se agota la memoria
 * @return Un objeto del tipo ApplicationVender
 */
ApplicationVender = function ()
{
    
    if(DEBUG){
        console.log("ApplicationVender: construyendo");
    }
    
    //nombre de la aplicacion
    this.appName = "Mostrador";
    
    //ayuda sobre esta applicacion
    this.ayuda = "esto es una ayuda sobre este modulo de compras, html es valido <br> :D";
    
    //submenues en el panel de la izquierda
    this.leftMenuItems = null;

    //panel principal    
    this.mainCard = this.venderMainPanel;

    //initialize the tootlbar which is a dock
    this._initToolBar();
    
    //variable auxiliar para referirse a esta instancia del objeto
    //solo funciona al instanciarse una vez, si el constructor
    //se vuelve a ejecutar esta variable contendra el ultimo 
    //objeto construido
    ApplicationVender.currentInstance = this;
    
    return this;
};





/**
 * Contiene el panel principal
 * @type Ext.Panel
 */
ApplicationVender.prototype.mainCard = null;

/**
 * Nombre que se muestra en el menu principal.
 * @type String
 */
ApplicationVender.prototype.appName = null;

/**
 * Items que que colocaran en el menu principal al cargar este modulo.
 * De no requerirse ninguno, hacer igual a null
 * @type Ext.Panel
 */
ApplicationVender.prototype.leftMenuItems = null;

/**
 * Texto de ayuda formateado en HTML para este modulo.
 * @type String
 */
ApplicationVender.prototype.ayuda = null;

/**
 * Items que estan anclados a este panel.
 * @type 
 */
ApplicationVender.prototype.dockedItems = null;

/**
 * Contiene informacion de un cliente seleccionado para facil manipulacion entre esta clase.
 * @type JSON
 */
ApplicationVender.prototype.cliente = null;

/**
 * Contiene el panel que se mostrara al hacer una compra a Contado.
 * @type Ext.Panel
 */
ApplicationVender.prototype.panelContado = null;

/**
 * Contiene el panel que se mostrara al hacer una compra a Credito.
 * @type Ext.Panel
 */
ApplicationVender.prototype.panelCredito = null;

/**
 * Texto a mostrar cuando el Mostrador esta vacio
 * @type String
 */
ApplicationVender.emptyText =  "<div class='no-data'>Mostrador</div>";

/**
 * Contiene un arreglo de objetos JSON que contienen detalles de 
 * los articulos a comprar.
 * @type Array
 */
ApplicationVender.prototype.htmlCart_items = [];




/**
 * ventaTotales
 * @type Object
 */
ApplicationVender.prototype.ventaTotales = null;

/**
 * carritoPendingUpdate
 * @type Int
 */
ApplicationVender.prototype.carritoPendingUpdate = null;





ApplicationVender.prototype.CLIENTE_COMUN = true;
ApplicationVender.prototype.buscarClienteFormSearchtype = 'nombre';
ApplicationVender.prototype.dataForTicket = null;
ApplicationVender.prototype.payingMethod = null;





/**
 * Crea un Ext.Toolbar en cual se guarda en {@link ApplicationVender#dockedItems}
 * y despues se los agrega a {@link ApplicationVender#mainCard}
 * @return void
 */
ApplicationVender.prototype._initToolBar = function (){


    //grupo 1, agregar producto
    var buttonsGroup1 = [{
            xtype: 'textfield',
            id: 'APaddProductByID',
            startValue: 'ID del producto',
            listeners:
                    {
                    'afterrender': function( ){
                        //focus
                        document.getElementById( Ext.get("APaddProductByID").first().id ).focus();
                            
                        //medio feo, pero bueno
                        Ext.get("APaddProductByID").first().dom.setAttribute("onkeyup","ApplicationVender.currentInstance.addProductByIDKeyUp( this, this.value )");
                            
                    }
                }
        },{
            text: 'Agregar producto',
            ui: 'round',
            handler: this.doAddProduct
        }];


    //grupo 2, caja comun o cliente
    var buttonsGroup2 = [{
        xtype: 'splitbutton',
        id:'_cliente_cajacomun_btn',
        activeItem: '0',
        items: [{
            text: 'Caja Comun',
            listeners:{
                render: function (a){
                    Ext.getCmp("_cliente_cajacomun_btn").setActive(0);
                }
            },
            handler : function (){
                    ApplicationVender.currentInstance.swapClienteComun(1);
            }
        }, {
            text: 'Cliente',
            handler : function (){
                    ApplicationVender.currentInstance.swapClienteComun(0);
            }
        }]    
    }];


    //grupo 3, listo para vender
    var buttonsGroup3 = [{
        text: 'Cotizar',
        handler: this.doCotizar
    },{
        text: 'Vender',
        ui: 'action',
        id: 'doVenderButton',
        handler: this.doVender
    }];


    if (!Ext.platform.isPhone) {
        
        buttonsGroup1.push({xtype: 'spacer'});
        buttonsGroup2.push({xtype: 'spacer'});
        
        this.dockedItems = [new Ext.Toolbar({
            ui: 'dark',
            dock: 'bottom',
            items: buttonsGroup1.concat(buttonsGroup2).concat(buttonsGroup3)
        })];

    }else {
        this.dockedItems = [{
            xtype: 'toolbar',
            ui: 'light',
            items: buttonsGroup1,
            dock: 'bottom'
        }, {
            xtype: 'toolbar',
            ui: 'dark',
            items: buttonsGroup2,
            dock: 'bottom'
        }, {
            xtype: 'toolbar',
            ui: 'metal',
            items: buttonsGroup3,
            dock: 'bottom'
        }];
    }

    
    //agregar este dock a el panel principal
    this.mainCard.addDocked( this.dockedItems );

};








/**
 * Crea un Ext.Panel con dos elementos html. Uno con id 'detallesCliente'
 * y otro con id 'carritoCompras' que es donde se generaran los detalles
 * del cliente, y la tabla de productos a comprar respectivamente, que 
 * son html generado a mano.
 * @return Ext.Panel
 * @type void
 */
ApplicationVender.prototype.venderMainPanel = new Ext.Panel({
    scroll: 'none',
    dockedItems: null,
    listeners : {
        'render' : function (){
            if(DEBUG){
                console.log("Rendereo de el mostrador...");
            }
            
            ApplicationVender.currentInstance.doRefreshItemList();
        }
        
    },
    cls: "ApplicationVender-mainPanel",
    
    //items del formpanel
    items: [{
            html: '',
            id : 'detallesCliente'
        },{
            
            html: ApplicationVender.emptyText,
            id : 'carritoDeCompras'
            
        }]
});
















/**
 * Limpia el carrito vaciando el arreglo que contiene los
 * objetos de compra y luego refresca el mostrador.
 * Este metodo siempre cambiara el el estado del cliente a
 * cliente comun.
 * @return void
 */
ApplicationVender.prototype.doLimpiarCarrito = function ( )
{
    
    var items = ApplicationVender.currentInstance.htmlCart_items;
    
    if( items.length !== 0){
        while(items.length !== 0){
            items.pop();
        }
        
        ApplicationVender.currentInstance.doRefreshItemList();
    }

    ApplicationVender.currentInstance.swapClienteComun(1);
    
};





/**
 * Elimina un elemento en el arreglo de articulos a comprar. 
 * Despues de eliminarlo, refresca la lista de articulos en mostrador.
 * @param {int} item La posicion de el articulo a eliminar.
 * @return void
 */
ApplicationVender.prototype.doDeleteItem = function ( item )
{
    
    this.htmlCart_items.splice(item, 1);
    
    this.doRefreshItemList();
    
};





/**
 * Refresca la lista de articulos a comprar.
 * Este metodo toma los objetos en el arreglo htmlCart_items y 
 * crea el codigo HTML para mostrarlo adecuadamente.
 * @return void
 */
ApplicationVender.prototype.doRefreshItemList = function (  )
{
    
    if(DEBUG){
        console.log("Application Mostrador: Refrescando lista del carrito");
    }

    
    if(Ext.get("carritoDeCompras") === null){
        if(DEBUG){
            console.log("Application Mostrador: carritoDeCompras es nulo.");
        }
        return;
    }
    
    if( this.htmlCart_items.length === 0){
        Ext.get("carritoDeCompras").update(ApplicationVender.emptyText);
        return;
    }
    
    
    var html = "";

    // cabezera
    html += "<div class='ApplicationVender-item' style='border:0px;'>" +
    "<div class='trash' >&nbsp;</div>"+
    "<div class='id'>ID</div>" +
    "<div class='name' >Nombre</div>" +
    "<div class='description'>Descripcion</div>" +
    "<div class='cost'>Precio</div>"+
    "<div class='qty_dummy'>&nbsp;</div>"+
    "<div class='cantidad'>Cantidad</div>"+
    "<div class='qty_dummy'>&nbsp;</div>"+
    "<div class='importe'>Importe</div>"+
    "</div>";

    //preparar un html para los totales
    var totals_html = "";

    //valores conocidos
    var subtotal = 0;
    var iva = MOSTRADOR_IVA;
    var descuento = this.cliente ? this.cliente.descuento : 0;
    
    
    
    //calcular subtotal
    for( a = 0; a < this.htmlCart_items.length;  a++){

        if(this.htmlCart_items[a].cantidad < 0.01){
            this.htmlCart_items[a].cantidad = 1;
        }
        //revisar que haya en existencia ese pedido
        var existencias = parseFloat( this.htmlCart_items[a].existencias );
        
        if( this.htmlCart_items[a].cantidad > existencias ){
            if(existencias === 0 ){
                POS.aviso("Mostrador", "No hay mas existencias del producto " + this.htmlCart_items[a].description +".");
                return this.doDeleteItem( a );
                
            }else{
                this.htmlCart_items[a].cantidad = existencias;
                POS.aviso("Mostrador", "Solamente queda en existencia " +existencias+  " productos "+ this.htmlCart_items[a].description + ".");    
            }

        }
        
        //calcular subtotal
        subtotal += parseFloat( this.htmlCart_items[a].cost * this.htmlCart_items[a].cantidad );
    }
    
    
    

    //    rendereo de cada item
    for( a = 0; a < this.htmlCart_items.length; a++ ){

        //size of text for small screen
        nombre = this.htmlCart_items[a].name.length > 7 ? this.htmlCart_items[a].name.substring(0,7) : this.htmlCart_items[a].name ;
        descripcion = this.htmlCart_items[a].description.length > 18 ? this.htmlCart_items[a].description.substring(0,18) + "..." : this.htmlCart_items[a].description  ;

        //actual creation of html
        html += "<div class='ApplicationVender-item' >" +
        "<div class='trash' onclick='ApplicationVender.currentInstance.doDeleteItem(" +a+ ")'><img height=20 width=20 src='sencha/resources/img/toolbaricons/trash.png'></div>" +
        "<div class='id'>" + this.htmlCart_items[a].id +"</div>" +
        "<div class='name'><b>" + nombre +"</b></div>" +
        "<div class='description'>"+ descripcion +"</div>" +
        "<div class='cost'>"+ POS.currencyFormat(this.htmlCart_items[a].cost) +"</div>"+
        "<div class='qty_change' onclick='ApplicationVender.currentInstance.doCambiarCantidad("+a+", -1)'>-</div>"+
        "<div class='cantidad' onclick='ApplicationVender.currentInstance.doCambiarCantidad("+a+")'>"+ this.htmlCart_items[a].cantidad +"</div>"+
        "<div class='qty_change' onclick='ApplicationVender.currentInstance.doCambiarCantidad("+a+", 1)'>+</div>"+
        "<div class='importe'>"+ POS.currencyFormat( this.htmlCart_items[a].cost * this.htmlCart_items[a].cantidad) +"</div>"+
        "</div>";
    }




    totals_html = "<span>Subtotal " +  POS.currencyFormat(subtotal) + "</span> " +
               "<span>IVA " +  POS.currencyFormat(subtotal* (iva/100)) + "</span> ";

    //calculo del total
    total = calcularTotal(subtotal, iva, descuento) ;

    //si tiene descuento
    if(descuento > 0){
        totals_html += "<span>Descuento " +  POS.currencyFormat(total *(descuento / 100)) + "</span> ";
    }

    //total de totales
    totals_html += "<span>Total " +  POS.currencyFormat(total) + "</span> ";

    // wrap divs
    html = "<div class='ApplicationVender-itemsBox' style='overflow: hidden'>" + html +"</div>" ;
    totals_html = "<div class='ApplicationVender-totalesBox' >" + totals_html +"</div>" ;
    
    var endhtml = html + totals_html;

    Ext.get("carritoDeCompras").update("<div >" + endhtml + "</div>");
    
    //actualizar el ventatotales con estos nuevos datos
    this.ventaTotales = {
        subtotal : subtotal,
        iva: iva,
        descuento: descuento,
        total : total,
        efectivo : null,
        cambio : null
    };

};





ApplicationVender.prototype.doCambiarCantidad = function(item, n)
{
    //si n, entonces sumar n a la cantidad del producto localizado en item
    //y salir
    if(n){
        ApplicationVender.currentInstance.htmlCart_items[item].cantidad += n;
        ApplicationVender.currentInstance.doRefreshItemList();
        return;
    }
    
    //sino, mostrar una ventana para ingresar una cantidad especifica
    if (Ext.getCmp('ApplicationVender-doCambiarCantidad-panel') === null ) {

        var cantidadToolbar = new Ext.Toolbar({
            title: 'Cantidad',
            dock: 'top',
            items: [{
                xtype: 'spacer'
            }, {
                xtype: 'button',
                text: 'Aceptar',
                ui: 'action',
                handler: function(){
                    
                    var spinValue = Ext.getCmp('ApplicationVender-doCambiarCantidad-cantidad').getValue();

                    if(spinValue.length > 0){
                    
                        spinValue = parseFloat(spinValue);

                        if(isNaN(spinValue)){
                            cantidadPanel.hide();
                            cantidadPanel.destroy();
                            return;
                        }

                        if(spinValue <= 0){
                            
                            cantidadPanel.hide();
                            cantidadPanel.destroy();
                            return;
                        }
                        

                        ApplicationVender.currentInstance.htmlCart_items[item].cantidad = spinValue;                        
                    }

                    cantidadPanel.hide();
                    cantidadPanel.destroy();
                    
                    ApplicationVender.currentInstance.doRefreshItemList();
                }
            }]
        });
    
        
        var cantidadPanel = new Ext.Panel({
            id: 'ApplicationVender-doCambiarCantidad-panel',
            floating: true,
            modal: true,
            centered: true,
            height: 150,
            width: 400,
            dockedItems: cantidadToolbar,
            items: [new Ext.form.FormPanel({
                items: [{
                    activeItem: 0,
                    xtype: 'fieldset',
                    label: 'Cantidad',
                    items: [{
                        id: 'ApplicationVender-doCambiarCantidad-cantidad',
                        xtype: 'textfield',
                        label: 'Cantidad',
                        name: 'cantidad',
                        defaultValue: 1,
                        minValue: 0.01
                    }]
                }]
            })]
        });
    }
    
    Ext.getCmp('ApplicationVender-doCambiarCantidad-panel').show();
    
    
    
};


ApplicationVender.prototype.htmlCart_addItem = function( item )
{



    var id = item.id;
    var name = item.name;
    var description = item.description;
    var existencias = item.existencias;
    var costo = item.cost;
    var cantidad = item.cantidad;

    //revisar que no este ya en el carrito
    var found = false;
    for( a = 0; a < this.htmlCart_items.length;  a++){
        if( this.htmlCart_items[ a ].id == id ){
            //item already in cart
            found = true;
            break;
        }
    }
    
    
    if(found && !MULTIPLE_SAME_ITEMS){
        POS.aviso("Mostrador", "Ya ha agregado este producto.");
        return;
    }
    
    this.htmlCart_items.push(item);
    
    this.doRefreshItemList();
};






ApplicationVender.prototype.doAddProductById = function ( prodID )
{
    if(DEBUG){
        console.log("ApplicationVender: Agregando producto " + id);
    }
    
    //buscar si este producto existe
    POS.AJAXandDECODE({
            action: '2101',
            id_producto : prodID
        }, 
        function (datos){
            
            //ya llego el request con los datos si existe o no    
            if(typeof(datos.success) !== 'undefined'){
                
                POS.aviso("Mostrador", datos.reason );
                
                //clear the textbox
                Ext.get("APaddProductByID").first().dom.value = "";
                
                return;
            }

            //crear el item
            var item = {
                id			: datos.id_producto,
                name		: datos.nombre,
                description : datos.denominacion,
                cost		: datos.precio_venta,
                existencias : datos.existencias,
                cantidad    : 1
            };
                
            //agregarlo al carrito
            ApplicationVender.currentInstance.htmlCart_addItem( item );


            if(Ext.get("APaddProductByID") !== null){
                //clear the textbox
                Ext.get("APaddProductByID").first().dom.value = "";

                //give focus again
                document.getElementById( Ext.get("APaddProductByID").first().id ).focus();                
            }


        },
        function (e){
            POS.aviso("Error", "Algo anda mal, porfavor intente de nuevo." + e);
            if(DEBUG){
                console.log(e);
            }
        }
    );
};



/**
 * Accion para el boton: Agregar Producto, esta funcion solamente
 * toma el valor de la caja de texto y se la envia a la funcion
 * doAddProductById que es la que contiene la logica de agregar el
 * producto al carrito
 * @return void
 */
ApplicationVender.prototype.doAddProduct = function (button, event)
{
    //obtener el id del producto y enviarselo a doAddProductById
    ApplicationVender.currentInstance.doAddProductById(
		Ext.get("APaddProductByID").first().getValue()
	);
	
};



/**
 * Esta funcion atrapa el evento de KeyUp en la caja
 * de texto de agregar producto. Ya que atrapa todas
 * las teclas que se tecleen en el, es util para hacer
 * busquedas rapidas.
 * Actualmente la funcion addProductByIDKeyUp tiene 3 
 * funciones basadas solamente en la tecla ENTER:
 * 1) Si la caja de texto contiene alguna cadena, intentara buscar ese producto.
 * 2) Si la caja de texto esta vacia y hay mas de un item en el carrito, iniciara el proceso de Vender.
 * 3) Si hay un aviso visible en el mostrador, lo cerrara.
 * 
 * producto al carrito
 * @return void
 */
ApplicationVender.prototype.addProductByIDKeyUp = function (a, b)
{

    if(event.keyCode == 13)
    {
        //si teclea enter, pero hay un pop up visible, ocultarlo con este enter
        if(POS.aviso.visible){
			
            POS.aviso.hide();
            return;
        }
        
        //si no hay nada escrito y hay mas de un item, intentar vender
        if( (Ext.get("APaddProductByID").first().getValue().length === 0) &&
            	(ApplicationVender.currentInstance.htmlCart_items.length > 0))
        {

            ApplicationVender.currentInstance.doVender();
            return;
        }
        

        if(Ext.get("APaddProductByID").first().getValue().length !== 0){
	        //Agregar el producto en la caja de texto.
	        ApplicationVender.currentInstance.doAddProduct();	
		}

    }
};







/* ------------------------------------------------------------------------------------
                    vender
   ------------------------------------------------------------------------------------ */


ApplicationVender.prototype.doVender = function ()
{
    
    if(DEBUG){
        console.log("ApplicationVender: doVender called....");
    }
    
    
    items = ApplicationVender.currentInstance.htmlCart_items;
    
    //revisar que exista por lo menos un item
    if(items.length == 0){
        POS.aviso("Mostrador", "Agregue al menos un artículo para poder vender.");
        return;
    }
    
    
    /*
        listo para hacer la venta
    */
    ApplicationVender.currentInstance.doVentaForms();


};


/**
 * 
 */
ApplicationVender.prototype.doVentaForms = function()
{
	
	try{
	    if(this.panelContado === null){
	        this.panelContado = this.doVentaContadoPanel();
	    }else{
	        this.panelContado.destroy();
	        this.panelContado = this.doVentaContadoPanel();
	    }

	    if(!this.CLIENTE_COMUN && this.panelCredito === null){
	        this.panelCredito = this.doVentaCreditoPanel();            
	    }else{
	        this.panelCredito.destroy();
	        this.panelCredito = this.doVentaCreditoPanel();        
	    }		
	}catch( e){
		console.log(e);
	}

    
    this.payingMethod = 'contado';

    sink.Main.ui.setCard( this.panelContado, 'slide' );
};


ApplicationVender.prototype.swapPayingMethod = function ( tipo )
{
    if(DEBUG){
        console.log("Mostrador: cambiando modo de pago a ", tipo.tipo);
    }
    
    var app = ApplicationVender.currentInstance;
    
    switch (tipo.tipo){
        case 'credito' : 
            sink.Main.ui.setCard( app.panelCredito, {type:'fade', duration: 600} );                                  
            break;
            
        case 'contado' : 
            sink.Main.ui.setCard( app.panelContado, {type:'fade', duration: 600} );
            break;
    }
};


ApplicationVender.prototype.doVentaContadoPanel = function (  )
{


    //docked items
    var dockedItems = [{
            xtype:'button', 
            text:'Regresar',
            ui: 'back',
            handler : function () {
                sink.Main.ui.setCard( ApplicationVender.currentInstance.venderMainPanel, {type: 'slide', direction: 'right'} );
            }
        },{
            xtype:'button', 
            text:'Cancelar Venta',
            handler : function (){
                ApplicationVender.currentInstance.doLimpiarCarrito();
                sink.Main.ui.setCard( ApplicationVender.currentInstance.venderMainPanel, 'fade' );
            }
        },{
            xtype:'spacer'
        }];
    
    //si no es cliente comun, y no ha sobrepasado el limite de credito .... agregar la opcion para pagar con credito
    if(!this.CLIENTE_COMUN){
        
        //revisar el limite de credito y creditos pasados
        var limite = this.cliente.limite_credito;
        
        dockedItems.push({
                xtype:'button',
                tipo: 'credito',
                ui: 'action',
                text:'Venta a Credito',
                handler : ApplicationVender.currentInstance.swapPayingMethod
            });
    }
    
    //cobrar
    dockedItems.push({
            xtype:'button', 
            ui: 'action',
            text:'Cobrar',
            handler: ApplicationVender.currentInstance.doVentaLogic
        });
    
    return new Ext.Panel({

    scroll: 'none',
    id : 'doVentaContadoPanel',
    cls: "ApplicationVender-mainPanel",
    html : '<div class="helperMostrador"></div>',

    //toolbar
    dockedItems: [new Ext.Toolbar({
        ui: 'dark',
        dock: 'bottom',
        items: dockedItems
        })],
    
    //items del formpanel
    items: [{

        xtype: 'fieldset',
        title: 'Venta a Contado',
        baseCls: "ApplicationVender-ventaListaPanel",
        defaults: {
            disabledClass: ''
        },
        items: [{
                xtype: 'textfield',
                label: 'Subtotal',
                value : POS.currencyFormat(this.ventaTotales.subtotal),
                disabled: true
               },{
    
                xtype: 'textfield',
                label: 'IVA',
                value : (this.ventaTotales.iva) + "%" ,
                disabled: true
              },{
                xtype: 'textfield',
                label: 'Descuento',
                value : (this.ventaTotales.descuento)+"%",
                disabled: true
            },{
                xtype: 'textfield',
                label: 'Total',
                value : POS.currencyFormat(this.ventaTotales.total),
                disabled: true
            },{
                xtype: 'textfield',
                label: 'Pago',
                id : 'mostrador_pago_id',
                value: '',
                disabled: false,
                listeners : {
                    'render' : function ( a ){
                        
                        //darle el atributo de keyup
                        this.el.dom.childNodes[1].setAttribute("onkeyup", "ApplicationVender.currentInstance.doPayContadoKeyUp()" );

                        //Focus a cantidad por pagar
                        document.getElementById( this.el.dom.childNodes[1].id ).focus();
                    }
                }
            },{
                xtype: 'textfield',
                label: 'Cambio',
                id: 'mostrador_cambio_id',
                value: '',
                disabled: true
            }]
        }]
    });
};





ApplicationVender.prototype.doPayContadoKeyUp = function (  )
{
    if(event.keyCode == 13 )
    {
        var val = Ext.getCmp("mostrador_cambio_id").getValue( );
        
        if( (val.length > 0) &&  ( val != "Dinero insuficiente." ) ){
            sink.Main.ui.setCard( ApplicationVender.currentInstance.venderMainPanel, 'fade' );
        }else{
            ApplicationVender.currentInstance.doVentaLogic(  );
        }
        

    }
};


ApplicationVender.prototype.doVentaCreditoPanel = function ( cantidadPago )
{
    
    
    //revisar si puedo pagar a contado
    var canDoCredit = true;
    if( this.ventaTotales.total > ApplicationVender.currentInstance.cliente.credito_restante ){
        canDoCredit = false;
    }
    
    

    return new Ext.Panel({

    scroll: 'none',

    id : 'doVentaCreditoPanel',
    
    cls: "ApplicationVender-mainPanel",

    //toolbar
    dockedItems: [new Ext.Toolbar({
        ui: 'dark',
        dock: 'bottom',
        items: [{
                xtype:'button', 
                text:'Regresar',
                ui: 'back',
                handler : function () {
                    sink.Main.ui.setCard( ApplicationVender.currentInstance.venderMainPanel, {type: 'slide', direction: 'right'} );
                }
            },{
                xtype:'button', 
                text:'Cancelar Venta',
                handler : function (){
                    
                    ApplicationVender.currentInstance.doLimpiarCarrito();
                    sink.Main.ui.setCard( ApplicationVender.currentInstance.venderMainPanel, 'fade' );
                }
            },{
                xtype:'spacer'
            },{
                xtype:'button', 
                ui: 'action',
                text:'Venta a Contado',
                tipo : 'contado',
                handler : ApplicationVender.currentInstance.swapPayingMethod
                
            },{
                xtype:'button', 
                ui: 'action',
                text:'Vender',
                hidden: !canDoCredit,
                handler: ApplicationVender.currentInstance.doVentaLogicCredito

            }]
        })],
    
    //items del formpanel
    items: [{

        xtype: 'fieldset',
        title: 'Venta a Credito',
        baseCls: "ApplicationVender-ventaListaPanel",
        ui: 'green',
        defaults: {
            disabledClass: ''
        },
        items: [{
                xtype: 'textfield',
                label: 'Subtotal',
                value : POS.currencyFormat(this.ventaTotales.subtotal),
                disabled: true
               },{
    
                xtype: 'textfield',
                label: 'IVA',
                value : (this.ventaTotales.iva*100) + "%" ,
                disabled: true
              },{
                xtype: 'textfield',
                label: 'Descuento',
                value : POS.currencyFormat(this.ventaTotales.descuento),
                disabled: true
            },{
                xtype: 'textfield',
                label: 'Total',
                value : POS.currencyFormat(this.ventaTotales.total),
                disabled: true
            },{
                xtype: 'textfield',
                label: 'Credito Max',
                value: POS.currencyFormat(ApplicationVender.currentInstance.cliente.limite_credito),
                disabled: true
            },{
                xtype: 'textfield',
                label: 'Credito Res',
                value: POS.currencyFormat(ApplicationVender.currentInstance.cliente.credito_restante),
                disabled: true
            }]
        }    ,{
                    html: '<div align=center>El limite de credito es mas alto que el total actual.</div>',
                    hidden: canDoCredit
            }]
    });
};






ApplicationVender.prototype.doVentaLogicCredito = function ()
{
    
    //hacer la venta en el lado del servidor
    var jsonItems = Ext.util.JSON.encode(ApplicationVender.currentInstance.htmlCart_items);
    
    var cliente = ApplicationVender.currentInstance.cliente.iden;
    
    POS.AJAXandDECODE({
            action: '2103',
            id_cliente: cliente,
            tipo_venta: 'credito',
            jsonItems: jsonItems
        }, function(result){

                if (result.success)
                {

                        if(DEBUG){
                            console.log("Mostrador: Venta a credito Exitosa !", result);    
                        }
                        
                        ApplicationVender.currentInstance.ventaCreditoExitosa();
                        
                    }else{
                        if(DEBUG){
                            console.warn("Mostrador: Venta a credito no exitosa ", result);    
                        }
                    }
                },
        function(){
            if(DEBUG){
                console.warn("ApplicationVender: Error al realizar la venta");    
            }
                        
        });
};





ApplicationVender.prototype.ventaCreditoExitosa = function ()
{
    
    items =  copy(ApplicationVender.currentInstance.htmlCart_items);
    cliente = ApplicationVender.currentInstance.cliente;
    
    appImpresora.ImprimirTicket( cliente, items, this.ventaTotales );
    
    //quitar el menu de cancelar venta y eso
    Ext.getCmp("doVentaCreditoPanel").getDockedItems()[0].hide();
    
    Ext.getCmp("doVentaCreditoPanel").add({ 
            html : '<div align="center">Venta a credito exitosa !</div>'
    });
        

    
    Ext.getCmp("doVentaCreditoPanel").add({ 
            xtype:'button', 
            text:'Nueva venta',
            style: "margin-left: 45%; margin-top: 20px; width: 150px;",
            ui: 'back',
            handler: function (){
                sink.Main.ui.setCard( ApplicationVender.currentInstance.venderMainPanel, 'fade' );
            }
        });
    
    /*
    Ext.getCmp("doVentaCreditoPanel").add({ 
            xtype:'button', 
            text:'Abonar a esta compra',
            style: "margin-left: 45%; margin-top: 20px; width: 200px;",
            ui: 'action',
            handler : function (){
                if(DEBUG){
                    console.log("Abonar a esta compra!");
                    
                }
                
                try{
                    ApplicacionClientes.currentInstance.abonarVenta(9,9,9); 
                }catch(e){
                    if(DEBUG){
                        console.log("Error al abonar a esta venta");
                    }
                }
                
            }
        });
    */
    
    Ext.getCmp("doVentaCreditoPanel").doLayout();
    
    //limpiar el carrito
    this.doLimpiarCarrito();
    
};









/*
    solo para ventas a contado
*/





ApplicationVender.prototype.doVentaLogic = function ()
{
    
    total = this.ventaTotales.total;
    
    
    var pago = Ext.getCmp("mostrador_pago_id").getValue();
    
    if( pago != parseFloat(pago) ){
        Ext.getCmp("mostrador_cambio_id").setValue(  );
        return;
    }
    
    if( pago < total ){
        if(DEBUG){
            console.log("Necesito:" +total, " Pagado:" + pago);
        }
        Ext.getCmp("mostrador_cambio_id").setValue( "Dinero insuficiente."  );
        return;
    }

    var cambio =  -1 * ( parseFloat(pago) - total );
    
    cambio = POS.currencyFormat(cambio);

    Ext.getCmp("mostrador_cambio_id").setValue( cambio );
    
    
    //disable the pago 
    Ext.getCmp("mostrador_pago_id").disable();

    //hacer la venta en el lado del servidor
    
    var jsonItems = Ext.util.JSON.encode(ApplicationVender.currentInstance.htmlCart_items);
    
    var cliente = ApplicationVender.currentInstance.cliente === null ? 'caja_comun' : ApplicationVender.currentInstance.cliente.iden;
    
    POS.AJAXandDECODE(
                    //Parametros
                    {
                        action: '2103',
                        id_cliente: cliente,
                        tipo_venta: 'contado',
                        jsonItems: jsonItems
                    },
                    //Funcion success
                    function(result){
                        
                        if (result.success)
                        {

                            if(DEBUG){
                                console.log("Mostrador: Venta Exitosa !", result);    
                            }

                            ApplicationVender.currentInstance.ventaContadoExitosa();
                            
                        }else{
                            if(DEBUG){
                                console.warn("Mostrador: Venta no exitosa ", result);
                            }
                            
                            POS.aviso( "Mostrador", "Venta no exitosa<br>" + result.reason );
                        }
                    },
                    //Funcion failure
                    function(){
                        if(DEBUG){
                            console.warn("ApplicationVender: Error al realizar la venta");
                        }
                        
                    }
    );
    

    
};





/**
* regresa una copia de lo que le manden 
*/
function copy (o) {
    if (typeof o != "object" || o === null){ return o; }
    var r = o.constructor == Array ? [] : {};
    for (var i in o) {
        r[i] = copy(o[i]);
    }
    return r;
};

ApplicationVender.prototype.ventaContadoExitosa = function ()
{

    items =  copy(ApplicationVender.currentInstance.htmlCart_items);
    cliente = ApplicationVender.currentInstance.cliente;


    //print this shit
    appImpresora.ImprimirTicket( cliente, items, this.ventaTotales );

    
    //quitar el menu de cancelar venta y eso
    Ext.getCmp("doVentaContadoPanel").getDockedItems()[0].hide();
    
    
    if( this.cliente ){
        Ext.getCmp("doVentaContadoPanel").add({ 
                xtype:'button', 
                text:'Requerir Factura',
                style: "margin-left: 45%; margin-top: 20px; width: 150px;",
                ui: 'action'
            });

            
    }
    
    Ext.getCmp("doVentaContadoPanel").add({ 
            xtype:'button', 
            text:'Nueva venta',
            style: "margin-left: 45%; margin-top: 20px; width: 150px;",
            ui: 'back',
            handler: function (){
                sink.Main.ui.setCard( ApplicationVender.currentInstance.venderMainPanel, 'fade' );
            }
        });
        
    Ext.getCmp("doVentaContadoPanel").doLayout();
    
    //limpiar el carrito
    this.doLimpiarCarrito();
    
};







/**
 * Accion de boton cotizar, verifica que exista
 * por lo menos un item en el carrito. De ser
 * asi, imprime el ticket con la informacion
 * global del mostrador.
 * @return void
 */
ApplicationVender.prototype.doCotizar = function ()
{
    
    if(DEBUG){
        console.log("ApplicationVender: doCotizar called....");
    }
    
    items = ApplicationVender.currentInstance.htmlCart_items;
    
    //revisar que exista por lo menos un item
    if(items.length === 0){
        POS.aviso("Mostrador", "Agregue primero al menos un arituclo para poder cotizar.");
        return;
    }
    
	//listo para imprimir un ticket
	
	//hacer una copia del arreglo que hay en htmlCart_items
    items =  copy( ApplicationVender.currentInstance.htmlCart_items );

	//seleccionar al cliente, puede ser nulo para denotar una caja comun
    cliente = ApplicationVender.currentInstance.cliente;

    //imprimir el ticket, ojo, la accion de la computadora es abrir
 	//la caja de dinero al imprimir algo, por consiguiente, la caja
	//de dinero se abrira al imprimir una cotizacion
    appImpresora.ImprimirTicket( cliente, items, this.ventaTotales );
};









/* ------------------------------------------------------------------------------------
                    buscar cliente
   ------------------------------------------------------------------------------------ */


/**
 * Cambia el estado de la variable CLIENTE_COMUN, recibe un valor
 * entero que denota si se cambiara por una caja comun o un cliente
 * de ser un cliente, llama a la funcion buscarCliente que muestra 
 * la caja de busqueda. De ser caja comun, borra los detalles del 
 * cliente anterior si es que los hay... hace this.cliente = null
 * refresca la lista de items en el carrito
 * 
 * @param int valor de swap, 0 para cliente y 1 para caja comun
 * @return void
 */
ApplicationVender.prototype.swapClienteComun = function (val)
{        
    if(val===0){
        //buscar cliente
        ApplicationVender.currentInstance.buscarCliente();

        ApplicationVender.currentInstance.CLIENTE_COMUN = false;

    }else{
        Ext.getCmp("_cliente_cajacomun_btn").setActive(0);
        Ext.get("detallesCliente").update("");
        this.cliente = null;
        ApplicationVender.currentInstance.CLIENTE_COMUN = true;
        this.doRefreshItemList();
    }
};





/**
 * Funcion que recibe un cliente que ha sido seleccionado
 * de la lista de clientes, y genera contenido html para 
 * mostrarlo en la parte de detallescliente, tambien 
 * actualiza el carrito de compras para calcular los totales
 * ya que cada cliente tiene un valor de descuento
 * @params Cliente
 * @return void
 */
ApplicationVender.prototype.actualizarDetallesCliente = function ( cliente )
{
    
    //mostrar los detalles del cliente
    this.cliente = cliente;
    
	//crear el contenido html
    var html = "";
    html += " <div class='ApplicationVender-clienteBox'> ";
        html += " <div class='nombre'>" + cliente.nombre + "</div>";

        html += "<table border='0' class='tabla_detalles_cliente'>";
        html +=     "<tr><td style='text-align: right'>RFC</td><td>" + cliente.rfc + "</td></tr>";
        html +=     "<tr><td style='text-align: right'>Direccion</td><td>" + cliente.direccion + "</td></tr>";
        html +=     "<tr><td style='text-align: right'>Telefono</td><td>" + cliente.telefono + "</td></tr>";
        html +=     "<tr><td style='text-align: right'>Correo Electronico</td><td>" + cliente.e_mail + "</td></tr>";
        html +=     "<tr><td style='text-align: right'>Descuento</td><td>" + cliente.descuento + " %</td></tr>";
        html +=     "<tr><td style='text-align: right'>Limite de Credito</td><td>" + POS.currencyFormat(cliente.limite_credito) + "</td></tr>";
        html +=     "<tr><td style='text-align: right'>Credito Restante</td><td>" + POS.currencyFormat(cliente.credito_restante) + "</td></tr>";
        html += "</table>";
    html += " </div> ";
    
	//cambiar el contenido de detallesCliente
    Ext.get("detallesCliente").update( html );
    
    //actualizar tambien la lista de productos, ya que se deben actualizar
	//los descuentos que cada cliente trae consigo
    this.doRefreshItemList();
    
};





ApplicationVender.prototype.buscarCliente = function ()
{
    //retrive client list from server
    POS.AJAXandDECODE({
            action : "1005"
        },
        function(response){
            
            //success
            if((typeof(response.success) !== 'undefined') && (response.success == false)){
                POS.aviso("Mostrador", "Error al traer la lista de clintes.");
                return;
            }


            //createArray for client data
            var clientesData = [];

            var datos = response.datos;
            
            //fill array
            for(a = 0; a < datos.length ; a++){

                clientesData.push( {
                    
                    iden:         datos[a].id_cliente, 
                    nombre:     datos[a].nombre, 
                    rfc:         datos[a].rfc,
                    direccion:     datos[a].direccion,
                    telefono:     datos[a].telefono,
                    e_mail:     datos[a].e_mail,
                    descuento:             datos[a].descuento,
                    limite_credito:     datos[a].limite_credito,
                    credito_restante:     datos[a].credito_restante
                    
                    });    
            }




            //create regmodel para busqueda por nombre
            Ext.regModel('ApplicationVender_nombre', {
                            fields: [ 'nombre']
            });
        
            //create regmodel para busqueda por rfc
            Ext.regModel('ApplicationVender_rfc', {
                            fields: [ 'rfc', 'nombre']
            });
        
            //create regmodel para busqueda por direccion
            Ext.regModel('ApplicationVender_direccion', {
                            fields: [ 'direccion' ]
            });

            //create the actual store
            var clientesStore = new Ext.data.Store({
                model: 'ApplicationVender_' + ApplicationVender.currentInstance.buscarClienteFormSearchtype,
                sorters: ApplicationVender.currentInstance.buscarClienteFormSearchtype,
                getGroupString : function(record) {
                    return record.get( ApplicationVender.currentInstance.buscarClienteFormSearchtype )[0];
                },
                data: clientesData
            });

             //send the store to the client searching form
            ApplicationVender.currentInstance.buscarClienteShowForm( clientesStore );
            
        },
        function(){
            //failure
        });

};







//thi shit wont work

ApplicationVender.prototype.buscarClienteFormSearchTemplate = function ()
{
    
    switch(ApplicationVender.currentInstance.buscarClienteFormSearchtype){
        case 'nombre':         return '<tpl for="."><div class="contact"><strong>{nombre}</strong>&nbsp;{rfc},&nbsp;{direccion}</div></tpl>';
        case 'rfc':         return '<tpl for="."><div class="contact"><strong>{rfc}</strong> {nombre}</div></tpl>';        
        case 'direccion':     return '<tpl for="."><div class="contact">{direccion}<strong> {nombre}</strong></div></tpl>';
    }
};



var alanboy = '<tpl for="."><div class="contact"><strong>{nombre}</strong> {rfc} {direccion}</div></tpl>';


ApplicationVender.prototype.buscarClienteShowForm = function ( clientesStore )
{
    
        var formBase = {
            //    items
            items: [{
                loadingText: 'Cargando datos...',
                width: "100%",
                height: "100%",
                id: 'buscarClientesLista',
                xtype: 'list',
                store: clientesStore,
                tpl: ApplicationVender.currentInstance.buscarClienteFormSearchTemplate() , //al hacer refresh() la lista no actualiza el tpl, creo que lo tendre que hacer con CSS
                itemSelector: 'div.contact',
                singleSelect: true,
                grouped: true,
                indexBar: true
            }],
        
        
            //    dock        
            dockedItems: [{
                    xtype: 'toolbar',
                    dock: 'bottom',
                    items: [{
                        xtype: 'splitbutton',
                        activeButton: "1",
                        items: [{
                            text: 'Nombre',
                            handler: function (){
                                //Ext.getCmp("buscarClientesLista")
                                ApplicationVender.currentInstance.buscarClienteFormSearchtype = "nombre";
                                Ext.getCmp("buscarClientesLista").refresh();
                            }
                        }, {
                            text: 'RFC',
                            handler: function (){

                                ApplicationVender.currentInstance.buscarClienteFormSearchtype = "rfc";
                                Ext.getCmp("buscarClientesLista").refresh();
                            }
                        }, {
                            text: 'Direccion',
                            handler: function (){

                                ApplicationVender.currentInstance.buscarClienteFormSearchtype = "direccion";
                                Ext.getCmp("buscarClientesLista").refresh();
                            }
                        }]    
                    },{
                        xtype: 'spacer'
                    },{
                        //-------------------------------------------------------------------------------
                        //            cancelar
                        //-------------------------------------------------------------------------------
                        text: 'Cancelar',
                        handler: function() {

                            //ocultar esta tabla
                            form.hide();                            
                            //cambiar el boton
                            ApplicationVender.currentInstance.swapClienteComun(1);
                            
                            //destruir la lista
                            if( Ext.getCmp('buscarClientesLista') ){
                                    Ext.getCmp('buscarClientesLista').store = null;
                                    Ext.getCmp('buscarClientesLista').destroy();
                                }
                                
                            }
                    },{
                        //-------------------------------------------------------------------------------
                        //            seleccionar    
                        //-------------------------------------------------------------------------------
                        text: 'Seleccionar',
                        ui: 'action',
                        handler: function() {

                        
                            if(Ext.getCmp("buscarClientesLista").selected.elements.length == 0){
                                //no haseleccionado a nadie
                                return;
                            
                            }
                        
                            //imprimir los detalles del cliente en la forma principal
                            ApplicationVender.currentInstance.actualizarDetallesCliente( Ext.getCmp("buscarClientesLista").getSelectedRecords()[0].data );
                        
                            //hide the form
                            form.hide();
                        
                        
                            //destruir la lista
                            if( Ext.getCmp('buscarClientesLista') ){
                                    Ext.getCmp('buscarClientesLista').store = null;
                                    Ext.getCmp('buscarClientesLista').destroy();
                                }
                       }
                    
                    }]
                }
            ]
        };





        
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
        


        var form = new Ext.Panel(formBase);

        form.show();
    

};




ApplicationVender.prototype.ventaLista = new Ext.Panel({
    
});



//autoinstalar esta applicacion
AppInstaller( new ApplicationVender() );



