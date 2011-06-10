/**
 * Construir un nuevo objeto de tipo ApplicacionClientes.
 * @class Esta clase se encarga de la creacion de interfacez
 * que intervinen en la manipulación de clientes. 
 * @constructor
 * @throws MemoryException Si se agota la memoria
 * @return Un objeto del tipo ApplicacionClientes
 */
Aplicacion.Clientes = function (  ){

    return this._init();
};
 



Aplicacion.Clientes.prototype._init = function (){
    if(DEBUG){
        console.log("ApplicacionClientes: construyendo");
    }

    //cargar la lista de clientes
    this.listaDeClientesLoad();
	
    //crear el panel de lista de clientes
    this.listaDeClientesPanelCreator();
	
    //crear el panel de detalles de cliente
    this.detallesDeClientesPanelCreator();
	
    //crear el panel de nuevo cliente
    this.nuevoClientePanelCreator();
	
    //cargar el panel que contiene los detalles de las ventas
    this.detallesDeVentaPanelCreator();
	
    //crea el panel donde se embebera el iframe para la impresion
    this.finishedPanelCreator();
    this.finishedPanelCreatorReimpresionTicket();
	
    Aplicacion.Clientes.currentInstance = this;
	
    return this;
};




Aplicacion.Clientes.prototype.getConfig = function (){

    if(POS.U.g === null){
        window.location = "sucursal.html";
    }

    return POS.U.g ? {
        text: 'Clientes',
        cls: 'launchscreen',
        card: this.listaDeClientesPanel,	
        items: [{
            text: 'Nuevo Cliente',
            card: this.nuevoClientePanel,
            leaf: true
        },{
            text: 'Lista de Clientes',
            card: this.listaDeClientesPanel,
            leaf: true
        }]
    } : {
        text: 'Clientes',
        cls: 'launchscreen',
        card: this.listaDeClientesPanel,
        leaf: true
    };

};






/* ********************************************************
	Compras de los Clientes
******************************************************** */



/**
 * Contiene un objeto con la lista de clientes actual, para no estar
 * haciendo peticiones a cada rato
 */
Aplicacion.Clientes.prototype.listaDeCompras = {
    lista : null,
    compraActual : null
};



/**
 * Leer la lista de clientes del servidor mediante AJAX
 */
Aplicacion.Clientes.prototype.listaDeComprasClienteLoad = function ( id_cliente ){

    if(DEBUG){
        console.log("Actualizando lista de compras del cliente " + id_cliente);
    }
	
    Ext.Ajax.request({
        url: '../proxy.php',
        scope : this,
        params : {
            action : 309,
            id_cliente : id_cliente
        },
        success: function(response, opts) {
            try{
                compras = Ext.util.JSON.decode( response.responseText );
            }catch(e){
                POS.error(e);
            }
			
            if( !compras.success ){
                //volver a intentar
                return this.listaDeComprasClienteLoad( id_cliente );
            }

            //refresca la lista de compras
            this.listaDeCompras.lista = compras.datos;

            if(DEBUG){
                console.log("cargando la nueva lista mejorada ajax : ", this.listaDeCompras.lista);
            }                        
            

            //cargamos los detalles del cliente
            Aplicacion.Clientes.currentInstance.detallesDeClientesPanelShow( id_cliente );
            
            //cargamos la lista de ventas a credito de este cliente
            Aplicacion.Clientes.currentInstance.creditoDeClientesPanelUpdater( id_cliente );

            
            
                       
        }
    });
	
};







/* ********************************************************
	Lista de Clientes
******************************************************** */

/**
 * Registra el model para listaDeClientes
 */
Ext.regModel('listaDeClientesModel', {
    fields: [
    {
        name: 'nombre',
        type: 'string'
    }
    ]
});





/**
 * Contiene un objeto con la lista de clientes actual, para no estar
 * haciendo peticiones a cada rato
 */
Aplicacion.Clientes.prototype.listaDeClientes = {
    lista : null,
    lastUpdate : null,
    hash : null
};




/**
 * Leer la lista de clientes del servidor mediante AJAX
 */
Aplicacion.Clientes.prototype.listaDeClientesLoad = function (){
	
    if(DEBUG){
        console.log("Actualizando lista de clientes ....");
    }
	
    Ext.Ajax.request({
        url: '../proxy.php',
        scope : this,
        params : {
            action : 300
        },
        success: function(response, opts) {
            try{
                clientes = Ext.util.JSON.decode( response.responseText );
            }catch(e){
                POS.error(e);
            }
			
            if( !clientes.success ){
                //volver a intentar
                return this.listaDeClientesLoad();
            }
			
            this.listaDeClientes.lista = clientes.datos;
            this.listaDeClientes.lastUpdate = Math.round(new Date().getTime()/1000.0);
            this.listaDeClientes.hash = clientes.hash;
			
            //agregarlo en el store
            this.listaDeClientesStore.loadData( clientes.datos );
			
            if( Aplicacion.Mostrador && ( Aplicacion.Mostrador.currentInstance.buscarClienteForm.getComponent(0).getStore() === null ) ){
                if(DEBUG){
                    console.log("Mostrador existe ya y no tiene el store, se lo cargare dese clientes...");
                }
                Aplicacion.Mostrador.currentInstance.buscarClienteForm.getComponent(0).store = this.listaDeClientesStore;
            }

        }
    });

};




/**
 * Es el Store que contiene la lista de clientes cargada con una peticion al servidor.
 * Recibe como parametros un modelo y una cadena que indica por que se va a sortear (ordenar) 
 * en este caso ese filtro es dado por 
 * @return Ext.data.Store
 */
Aplicacion.Clientes.prototype.listaDeClientesStore = new Ext.data.Store({
    model: 'listaDeClientesModel',
    //sorters: 'nombre',
    sorters: 'razon_social',

    getGroupString : function(record) {
        //return record.get('nombre')[0].toUpperCase();
        return record.get('razon_social')[0].toUpperCase();
    }
});




/**
 * Contiene el panel con la lista de clientes
 */
Aplicacion.Clientes.prototype.listaDeClientesPanel = null;


/**
 * Pone un panel en listaDeClientesPanel
 */
Aplicacion.Clientes.prototype.listaDeClientesPanelCreator = function (){

    this.listaDeClientesPanel =  new Ext.Panel({
        layout: 'fit',
        items: [{
            xtype: 'list',
            store: this.listaDeClientesStore,
            itemTpl: '<div class="listaDeClientesCliente" onClick = "Aplicacion.Clientes.currentInstance.listaDeComprasClienteLoad( {id_cliente} )" ><strong>{razon_social}</strong> {rfc}</div>',
            grouped: true,
            indexBar: true,
            listeners : {
                "beforerender" : function (){
                //Aplicacion.Clientes.currentInstance.listaDeClientesPanel.getComponent(0).setHeight(sink.Main.ui.getSize().height - sink.Main.ui.navigationBar.getSize().height );
                },
                "selectionchange"  : function ( view, nodos, c ){
					
                    if(nodos.length > 0){
					
                        // Aplicacion.Clientes.currentInstance.listaDeComprasClienteLoad( nodos[0] )

                        if(DEBUG){
                            console.log("modos[0]",nodos[0]);
                        }
						
                    }

                    //deseleccinar el cliente
                    view.deselectAll();
                }
            }
			
        }]
    });
};




/* ********************************************************
	Detalles de la venta
******************************************************** */
/*
 * Guarda el panel donde estan los detalles de la venta
 **/
Aplicacion.Clientes.prototype.detallesDeVentaPanel = null;





/*
 * Es la funcion de entrada para mostrar los detalles del cliente
 **/
Aplicacion.Clientes.prototype.detallesDeVentaPanelShow = function ( venta ){

	
    if( this.detallesDeVentaPanel ){
        this.detallesDeVentaPanelUpdater(venta);
    }else{
        this.detallesDeVentaPanelCreator();
        this.detallesDeVentaPanelUpdater(venta);
    }
	
    this.detallesDeVentaPanel.setCentered(true);
    this.detallesDeVentaPanel.show( Ext.anims.fade );

};






Aplicacion.Clientes.prototype.detallesDeVentaPanelUpdater = function ( venta )
{
	
	
    //buscar la venta en la estructura
    ventas = Aplicacion.Clientes.currentInstance.listaDeCompras.lista;
    var detalleVenta;
	
    for (var i = ventas.length - 1; i >= 0; i--){
        if(ventas[i].id_venta == venta){
            detalleVenta = ventas[i].detalle_venta;
            //establecemos la venta que se selecciono para reimprimir
            Aplicacion.Clientes.currentInstance.listaDeCompras.compraActual = ventas[i];
            break;
        }
    }
	

    var html = "";
    html += "<table border = '0' align = 'center'>";
	
    html += "<tr class='top'>";
    html += "<td>Producto</td>";
    html += "<td>Descripcion</td>";
    html += "<td>Cantidad Original Entregada</td>";    
    html += "<td>Descuento</td>";
    html += "<td>Cantidad Original Cobrada</td>";
    html += "<td>Precio</td>";
    html += "<td>Cantidad procesada</td>";
    html += "<td>Precio procesada</td>";
    
    html += "<td>Subtotal</td>";
    html += "</tr>";
	
    for (i=0; i < detalleVenta.length; i++) {

	
        if( i == detalleVenta.length - 1 )
            html += "<tr class='last'>";
        else
            html += "<tr >";
		
        if(DEBUG){
            console.log("El detalle de la venta consiste en : ", detalleVenta[i]);
        }
		
        html += "<td>" + detalleVenta[i].id_producto + "</td>";
        html += "<td>" + detalleVenta[i].descripcion + "</td>";
        html += "<td>" + (parseFloat(detalleVenta[i].cantidad) + parseFloat(detalleVenta[i].descuento)) + "</td>";
        html += "<td style = '" + (detalleVenta[i].descuento > 0 ? "color:red":"") + "'>" + detalleVenta[i].descuento + "</td>";
        html += "<td>" + detalleVenta[i].cantidad + "</td>";
        html += "<td>" + POS.currencyFormat ( detalleVenta[i].precio ) + "</td>";
        html += "<td>" + detalleVenta[i].cantidad_procesada + "</td>";
        html += "<td>" + POS.currencyFormat ( detalleVenta[i].precio_procesada ) + "</td>";
		
        var subtotal = parseFloat( detalleVenta[i].precio ) * parseFloat( detalleVenta[i].cantidad );
        var subtotal_procesada = parseFloat(  detalleVenta[i].precio_procesada) * parseFloat( detalleVenta[i].cantidad_procesada );
		
        subtotal = subtotal + subtotal_procesada;

       

        html += "<td>" + POS.currencyFormat ( subtotal ) + "</td>";
        html += "</tr>";
    }
	
    html += "</table>";
	
    this.detallesDeVentaPanel.update( html );
    this.detallesDeVentaPanel.setWidth( 900 );
    this.detallesDeVentaPanel.setHeight( 400 );
};

Aplicacion.Clientes.prototype.detallesDeVentaPanelCreator = function ()
{


    venta = [{
        text: 'Regresar',
        ui: 'back',
        handler : function( t ){
            Aplicacion.Clientes.currentInstance.detallesDeVentaPanel.hide( Ext.anims.fade );
        }
    },{ 
        xtype: 'spacer'
    },
    /*{
	    text: 'Devoluciones',
	    ui: 'drastic'
	},
    */{
        text: 'Imprimir Ticket',
        ui: 'normal',
        handler : function(){
            if(DEBUG){
                console.log("venta actual es : ", Aplicacion.Clientes.currentInstance.listaDeCompras.compraActual );
            }
            
            Aplicacion.Clientes.currentInstance.detallesDeVentaPanel.hide( Ext.anims.fade );
            
            Aplicacion.Clientes.currentInstance.finishedPanelShowReimpresionTicket(Aplicacion.Clientes.currentInstance.listaDeCompras.compraActual );
            
        }
    }];


    dockedItems = [new Ext.Toolbar({
        ui: 'dark',
        dock: 'bottom',
        items: venta
    })];



    this.detallesDeVentaPanel = new Ext.Panel({
        floating: true,
        ui : "dark",
        modal: true,
        showAnimation : Ext.anims.fade ,
        centered: true,
        hideOnMaskTap : true,
        cls : "Tabla",
        bodyPadding : 0,
        bodyMargin : 0,
        styleHtmlContent: false,
        html : null,
        scroll: 'none',
        dockedItems: dockedItems
    });

};









/* ********************************************************
	Detalles del Cliente
******************************************************** */
/*
 * Guarda el panel donde estan los detalles del cliente
 **/
Aplicacion.Clientes.prototype.detallesDeClientesPanel = null;

/*
 * Es la funcion de entrada para mostrar los detalles del cliente
 **/
Aplicacion.Clientes.prototype.detallesDeClientesPanelShow = function ( id_cliente ){
    if(DEBUG){
        console.log("mostrando detalles", id_cliente);
    }
	
    if( this.detallesDeClientesPanel ){
        this.detallesDeClientesPanelUpdater(id_cliente);
    }else{
        this.detallesDeClientesPanelCreator();
        this.detallesDeClientesPanelUpdater(id_cliente);
    }
	
    //deshabilitamos el boton de editar cliente en caso de que sea una sucursal
    if( id_cliente <= 0 ){
        Ext.getCmp("Clientes-EditarDetalles").setVisible(false);
        Ext.getCmp("Clientes-Estado-Cuenta").setVisible(false);

    }
    else{
        Ext.getCmp("Clientes-EditarDetalles").setVisible(true);
        Ext.getCmp("Clientes-Estado-Cuenta").setVisible(true);
    }
	
    //hacer un setcard manual
    sink.Main.ui.setActiveItem( this.detallesDeClientesPanel , 'slide');
	
    //mostrar la primer pantalla en el carrusel de detalles
    Aplicacion.Clientes.currentInstance.detallesDeClientesPanel.setActiveItem(0);

};


/*
 * Se llama para actualizar el contenido del panel de detalles, cuando ya existe
 **/
Aplicacion.Clientes.prototype.detallesDeClientesPanelUpdater = function ( id_cliente )
{

    if(DEBUG){
        console.log("Detalles del Cliente : ", id_cliente);
    }


    /*
     *
     *detallesPanel.setValues({
        productoID  :   producto.get('productoID'),
        descripcion :   producto.get('descripcion'),
        precioVenta :   POS.currencyFormat( producto.get('precioVenta') ),
        existenciasOriginales : producto.get('existenciasOriginales') + " " + producto.get('medida')+"s",
        existenciasProcesadas : producto.get('existenciasProcesadas') + " " + producto.get('medida')+"s"
    });
     *
     *
     *Aplicacion.Clientes.currentInstance.listaDeClientes.lista
     *
     **/


    var listaClientes = Aplicacion.Clientes.currentInstance.listaDeClientes.lista;
    var record = null;

    for(var i = 0; i < listaClientes.length; i++){
        if( listaClientes[i].data.id_cliente == id_cliente){
            record = listaClientes[i];
        }
    }

    //actualizar los detalles del cliente
    var detallesPanel = Aplicacion.Clientes.currentInstance.detallesDeClientesPanel.getComponent(0).items.items[0];
    detallesPanel.loadRecord( record );
	
    //actualizar las compras del cliente
    Aplicacion.Clientes.currentInstance.comprasDeClientesPanelUpdater( id_cliente );
	
};




















/*
 * Se llama para actualizar el contenido del panel de compras de los clientes, que ya estan en una estructura local
 **/
Aplicacion.Clientes.prototype.comprasDeClientesPanelUpdater = function ( id_cliente )
{

    //actualizar los detalles del cliente
    var comprasPanel = Aplicacion.Clientes.currentInstance.detallesDeClientesPanel.getComponent(1);

    if(DEBUG){
        console.log("leyendo la lista mejorada : ", this.listaDeCompras.lista);
    }
	
    //buscar este cliente en la estructura
    var lista = Aplicacion.Clientes.currentInstance.listaDeCompras.lista;
	
    var html = "";
    html += "<table border=0>";
	
    html += "<tr class='top'>";
    html += "<td>ID</td>";
    html += "<td>Fecha</td>";
    html += "<td>Sucursal</td>";
    html += "<td>Tipo</td>";
    html += "<td>Total</td>";
    html += "<td>Saldo</td>";
    html += "</tr>";

    var saldo = null;
    var style = null;

    for (var i = lista.length - 1; i >= 0; i--){
		
        if(lista[i].id_cliente != id_cliente){
            continue;
        }
		
        if( i === 0 )
            html += "<tr class='last' onClick='Aplicacion.Clientes.currentInstance.detallesDeVentaPanelShow(" +lista[i].id_venta+ ");'>";
        else
            html += "<tr onClick='Aplicacion.Clientes.currentInstance.detallesDeVentaPanelShow(" +lista[i].id_venta+ ");'>";
		
        html += "<td>" + lista[i].id_venta + "</td>";
        var fecha = POS.fecha(lista[i].fecha);
        html += "<td>" + fecha + "</td>";
        html += "<td>" + lista[i].sucursal + "</td>";
        html += "<td>" + lista[i].tipo_venta + "</td>";
        html += "<td>" + POS.currencyFormat ( lista[i].total ) + "</td>";

        saldo = lista[i].total - lista[i].pagado;

        if(saldo > 0)
        {
            style = "style = 'color:red'"
        }

        html += "<td " + style + " >" + POS.currencyFormat ( saldo ) + "</td>";		
        html += "</tr>";

        style = null;

    }
	
    html += "</table>";

	
    //actualizar las compras del cliente
    comprasPanel.update(html);
};











Aplicacion.Clientes.prototype.editarClienteCancelarBoton = function (  )
{
    var detallesPanel = Aplicacion.Clientes.currentInstance.detallesDeClientesPanel.getComponent(0).items.items[0];

    //cargar los valores que tenia por default antes de modificar
    var cliente = Aplicacion.Clientes.currentInstance.CLIENTE_EDIT;
    Aplicacion.Clientes.currentInstance.CLIENTE_EDIT = null;
	
    if(DEBUG){
        console.log("cancelando: ", cliente);
    }
	
    detallesPanel.loadRecord( cliente );
    detallesPanel.disable();
    detallesPanel.items.items[0].setInstructions("Todos los campos son obligatorios. Serciorese de que todos los campos sean correctos.");
	
    Ext.getCmp("Clientes-EditarDetalles").setVisible(true);
    Ext.getCmp("Clientes-Estado-Cuenta").setVisible(true);
    Ext.getCmp("Clientes-EditarDetallesGuardar").setVisible(false);
    Ext.getCmp("Clientes-EditarDetallesCancelar").setVisible(false);
    Ext.getCmp("Clientes-credito-restante").show( Ext.anims.slide );
    Ext.getCmp("DetallesCliente_limite_credito").show( Ext.anims.slide );
};


Aplicacion.Clientes.prototype.editarCliente = function ( data )
{
    if(DEBUG){
        console.log("Guardando cliente", data);
    }

    Ext.getBody().mask('Guardando...', 'x-mask-loading', true);

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
                Ext.Msg.alert("Editar cliente", r.reason);
                Aplicacion.Clientes.currentInstance.detallesDeClientesPanel.getComponent(0).items.items[0].items.items[0].setInstructions(r.reason);
                
                return;
            }

            //salio bien, poner las cosas como estaban
            var detallesPanel = Aplicacion.Clientes.currentInstance.detallesDeClientesPanel.getComponent(0).items.items[0];
            detallesPanel.disable();
            Ext.getCmp("Clientes-EditarDetalles").setVisible(true);
            Ext.getCmp("Clientes-Estado-Cuenta").setVisible(true);
            Ext.getCmp("Clientes-EditarDetallesGuardar").setVisible(false);
            Ext.getCmp("Clientes-EditarDetallesCancelar").setVisible(false);

            //mostrar el credito de nuevo
            Ext.getCmp("Clientes-credito-restante").show( Ext.anims.slide );
            Ext.getCmp("DetallesCliente_limite_credito").show( Ext.anims.slide );


            //poner las instrucciones originales
            Aplicacion.Clientes.currentInstance.detallesDeClientesPanel.getComponent(0).items.items[0].items.items[0].setInstructions("Sus cambios se han guardado satisfactoriamente.");
			
			
            //volver a cargar la estructura de los clientes
            Aplicacion.Clientes.currentInstance.listaDeClientesLoad();

        }
    });
	
};








Aplicacion.Clientes.prototype.editarClienteGuardarBoton = function (  )
{

    var detallesPanel = Aplicacion.Clientes.currentInstance.detallesDeClientesPanel.getComponent(0).items.items[0];

    //validar los nuevos datos
    v = detallesPanel.getValues();

    //validar los datos antes de enviar
    campo = Aplicacion.Clientes.currentInstance.detallesDeClientesPanel.getComponent(0).items.items[0].items.items[0];
	
    //nombre
    if(v.razon_social.length < 10){
        Ext.Msg.alert("Nuevo cliente", "La Razon Social del cliente debe ser mayor de diez caracteres.");
        return campo.setInstructions("La Razon Social del cliente debe ser mayor de diez caracteres.");
    }

    //rfc
    if(v.rfc.length < 10){
        Ext.Msg.alert("Nuevo cliente", "El RFC debe ser mayor de diez caracteres.");
        return campo.setInstructions("El RFC debe ser mayor de diez caracteres.");
    }

    //calle
    if(v.calle.length < 3){
        Ext.Msg.alert("Nuevo cliente", "La descripcion de la calle es muy corta.");
        return campo.setInstructions("La descripcion de la calle es muy corta.");
    }

    //numero_exterior
    if(v.numero_exterior.length == 0){
        Ext.Msg.alert("Nuevo cliente", "Indique el numero exterior.");
        return campo.setInstructions("Indique el numero exterior.");
    }

    //colonia
    if(v.colonia.length < 3){
        Ext.Msg.alert("Nuevo cliente", "La descripcion de la colonia es muy corta.");
        return campo.setInstructions("La descripcion de la colonia es muy corta.");
    }

    //municipio
    if(v.municipio.length < 3){
        Ext.Msg.alert("Nuevo cliente", "La descripcion del municipio es muy corta.");
        return campo.setInstructions("La descripcion del municipio es muy corta.");
    }

    //estado
    if(v.estado.length < 3){
        Ext.Msg.alert("Nuevo cliente", "La descripcion del estado es muy corta.");
        return campo.setInstructions("La descripcion del estado es muy corta.");
    }

    //pais
    if(v.pais.length < 3){
        Ext.Msg.alert("Nuevo cliente", "La descripcion del pais es muy corta.");
        return campo.setInstructions("La descripcion del pais es muy corta.");
    }

    //codigo_postal
    if(v.codigo_postal.length < 5 || isNaN(v.codigo_postal)){
        Ext.Msg.alert("Nuevo cliente", "La descripcion del codigo postal es muy corta.");
        return campo.setInstructions("La descripcion del codigo postal es muy corta.");
    }

    //telefono
    if(v.telefono.length < 5){
        Ext.Msg.alert("Nuevo cliente", "La descripcion del telefono es muy corta.");
        return campo.setInstructions("La descripcion del telefono es muy corta.");
    }


    //descuento
    if(  v.descuento.length === 0 || isNaN( v.descuento ) || (v.descuento > 50 && v.descuento < 0) ){
        Ext.Msg.alert("Nuevo cliente", "El descuento debe ser un numero entre 0 y 50.");
        return campo.setInstructions("El descuento debe ser un numero entre 0 y 50.");
    }

    /*/limite_credito
    if( v.limite_credito.length === 0 || isNaN( v.limite_credito ) ){
        Ext.Msg.alert("Nuevo cliente", "El limite de credito debe ser un numero.");
        return campo.setInstructions("El limite de credito debe ser un numero.");
    }

    //limite_credito
    if( v.limite_credito > 20000 ){

        Ext.Msg.alert("Nuevo cliente", "No puede crear un cliente con limite de credito mayor a $20,000. Necesitara pedir una autorizacion.");
        return campo.setInstructions("No puede crear un cliente con limite de credito mayor a $20,000. Necesitara pedir una autorizacion.");
    }*/

    Aplicacion.Clientes.currentInstance.editarCliente ( v );

};




/*
 *  Aqui se guarda una copia del cliente que estoy apunto de editar
 *  por si decido cancelar la edicion entonces sacarlo de aqui y asi
 *  regresar a los valores originales
 */
Aplicacion.Clientes.prototype.CLIENTE_EDIT = null;


Aplicacion.Clientes.prototype.editarClienteBoton = function ( )
{
	
    var detallesPanel = Aplicacion.Clientes.currentInstance.detallesDeClientesPanel.getComponent(0).items.items[0];
    Aplicacion.Clientes.currentInstance.CLIENTE_EDIT = detallesPanel.getRecord();
    detallesPanel.enable();
	

    Ext.getCmp("Clientes-EditarDetalles").hide( Ext.anims.slide );
    Ext.getCmp("Clientes-Estado-Cuenta").hide(Ext.anims.slide);
    Ext.getCmp("Clientes-EditarDetallesGuardar").show( Ext.anims.slide );
    Ext.getCmp("Clientes-EditarDetallesCancelar").show( Ext.anims.slide );

    //quitar el credito restante
    Ext.getCmp("Clientes-credito-restante").hide( Ext.anims.slide );
    Ext.getCmp("DetallesCliente_limite_credito").hide( Ext.anims.slide );
    	

};





Aplicacion.Clientes.prototype.doAbonarValidator = function (  )
{
    
    var monto = Ext.getCmp('Cliente-abonarMonto').getValue();

    //validamos que sea un numero positivo el abono
    if( !( monto && /^\d+(\.\d+)?$/.test(monto + '') ) ){
        Ext.Anim.run(Ext.getCmp('Cliente-abonarMonto'),
            'fade', {
                duration: 250,
                out: true,
                autoClear: true
            });
        return;
    }

    var detalleVenta = Aplicacion.Clientes.currentInstance.detalleVentaCredito;

    //validamos que si el abono excede el saldo, solos e abone a la cuenta lo qeu resta del saldo

    var transaccion = {
        abono : null,
        cambio : null,
        abonado: detalleVenta.pagado,
        saldo : detalleVenta.total - detalleVenta.pagado
    }

    if( monto > transaccion.saldo )
    {
        transaccion.abono = transaccion.saldo;
        transaccion.cambio = monto -transaccion.saldo;
        transaccion.abonado = transaccion.abonado + transaccion.abono;
    }
    else
    {
        transaccion.abono = monto;
        transaccion.cambio = 0;
        transaccion.saldo = transaccion.saldo - monto;
        transaccion.abonado = ( parseFloat( transaccion.abonado ) + parseFloat( monto ) );
    }

    Aplicacion.Clientes.currentInstance.doAbonar( transaccion );

}



/*
 *	hacer un abono
 */
Aplicacion.Clientes.prototype.doAbonar = function ( transaccion )
{
    if(DEBUG){
        console.log("Abonando... a venta : " + Ext.getCmp("Clentes-CreditoVentasLista").getValue());
		
    }
	
    Ext.Ajax.request({
        url: '../proxy.php',
        scope : this,
        params : {
            action : 305,
            data : Ext.util.JSON.encode({
                id_venta : parseFloat( Ext.getCmp("Clentes-CreditoVentasLista").getValue() ),
                monto : parseFloat( transaccion.abono ),
                tipo_pago: Ext.getCmp('Clentes-abonarTipoPago').getValue()
            })
        },
        success: function(response, opts) {

            try{
                r = Ext.util.JSON.decode( response.responseText );
            }catch(e){
                POS.error(e);
            }
						
            if( !r.success ){
                Ext.Msg.alert("Error", r.reason);				                
                return;
            }



            //buscar esta venta especifica en la estructura (MODIFICAMOS LA LISTA DE VENTAS)
            lista = Aplicacion.Clientes.currentInstance.listaDeCompras.lista;
            var venta = null;
	
            for (var i = lista.length - 1; i >= 0; i--){
                if (  lista[i].id_venta  == Ext.getCmp("Clentes-CreditoVentasLista").getValue() ) {
                    venta = lista[i];
                }
            }

            venta.pagado = transaccion.abonado;

            //recargamos el formulario con los detalles de la venta
            Aplicacion.Clientes.currentInstance.creditoDeClientesOptionChange( "", Ext.getCmp("Clentes-CreditoVentasLista").getValue() );

            for( i = 0; i < POS.documentos.length; i++){
                if( POS.documentos[i].documento == 'abono_venta_cliente' ){
                    var impresora = POS.documentos[i].impresora;
                    break;
                }
            }

            var data_abono = {
                ticket : 'abono_venta_cliente',
                id_venta : Ext.getCmp("Clentes-CreditoVentasLista").getValue(),
                empleado : r.empleado,
                saldo_prestamo : transaccion.saldo,
                monto_abono : transaccion.abono,
                sucursal : POS.infoSucursal,
                impresora : impresora,
                leyendasTicket : POS.leyendasTicket
            };
			
            Aplicacion.Clientes.currentInstance.finishedPanelShow( data_abono );

        },
        failure: function( response ){
            return POS.error( response );
        }
    });
};



/**
* Muestra el panel para ingresar la cantidad del abono
*/

Aplicacion.Clientes.prototype.abonarVentaBoton = function (  )
{
    if(DEBUG){
        console.log( "abonando");
    }
	
    Ext.getCmp("Clientes-DetallesVentaAbonarCredito").show();
    Ext.getCmp("Clientes-DetallesVentaCredito").hide();
	
    Ext.getCmp("Clientes-AbonarVentaBotonCancelar").show();
    Ext.getCmp("Clientes-AbonarVentaBotonAceptar").show();
	

    Ext.getCmp("Clientes-AbonarVentaBoton").hide();

    Ext.getCmp("Clientes-SeleccionVentaCredito").hide();
		    

    Ext.getCmp('Clientes-DetallesVentaAbonarCredito-saldo').setValue(
        POS.currencyFormat(
            Aplicacion.Clientes.currentInstance.detalleVentaCredito.total - Aplicacion.Clientes.currentInstance.detalleVentaCredito.pagado
            )
        );
    
    //reseteamos el campo de abono
    Ext.getCmp('Cliente-abonarMonto').setValue("");
	
};



Aplicacion.Clientes.prototype.abonarVentaCancelarBoton = function ()
{
	
    Ext.getCmp("Clientes-DetallesVentaAbonarCredito").hide();
    Ext.getCmp("Clientes-DetallesVentaCredito").show();
	
    Ext.getCmp("Clientes-AbonarVentaBotonCancelar").hide();
    Ext.getCmp("Clientes-AbonarVentaBotonAceptar").hide();
	


    //ocultamos el boton de abonar a venta si la venta a credito esta liquidada
    var detalleVenta = Aplicacion.Clientes.currentInstance.detalleVentaCredito;

    if( ( detalleVenta.total - detalleVenta.pagado ) > 0 )
    {
        Ext.getCmp("Clientes-AbonarVentaBoton").show();
    }
    else
    {
        Ext.getCmp("Clientes-AbonarVentaBoton").hide();
    }

    Ext.getCmp("Clientes-SeleccionVentaCredito").show();
};


//almacenara los datos del detalle de una venta a credito
//se le data valor en creditoDeClientesOptionChange()
Aplicacion.Clientes.prototype.detalleVentaCredito = null;

//es lo que sucede al dar click al escoger una vent a acredito
Aplicacion.Clientes.prototype.creditoDeClientesOptionChange = function ( a, v )
{

    //el valor de -1 es para el mensaje de seleccionar, todo el que este arriba
    //de eso equivale al id de la venta
	
    if(v == -1){
        Ext.getCmp("Clientes-DetallesVentaCredito").hide();
        Ext.getCmp("Clientes-AbonarVentaBoton").hide();

        return;
    }
	
	
	
    //buscar esta venta especifica en la estructura
    lista = Aplicacion.Clientes.currentInstance.listaDeCompras.lista;
    var venta = null;
	
    for (var i = lista.length - 1; i >= 0; i--){
        if (  lista[i].id_venta  == v ) {
            venta = lista[i];
        }
    }


    //fecha
    Ext.getCmp("Clientes-DetallesVentaCredito").getComponent(0).setValue(venta.fecha);
	
    //sucursal
    Ext.getCmp("Clientes-DetallesVentaCredito").getComponent(1).setValue(venta.sucursal);
	
    //vendedor
    Ext.getCmp("Clientes-DetallesVentaCredito").getComponent(2).setValue(venta.cajero);
	
    //total
    Ext.getCmp("Clientes-DetallesVentaCredito").getComponent(3).setValue( POS.currencyFormat(venta.total));
	
    //abonado
    Ext.getCmp("Clientes-DetallesVentaCredito").getComponent(4).setValue( POS.currencyFormat(venta.pagado));

    //saldo
    Ext.getCmp("Clientes-DetallesVentaCredito").getComponent(5).setValue( POS.currencyFormat(venta.total - venta.pagado));

    //almacenamos el valor de la venta a credito
    Aplicacion.Clientes.currentInstance.detalleVentaCredito = venta;


    Ext.getCmp("Clientes-DetallesVentaCredito").show();

    //ocultamos el boton de abonar a venta si la venta a credito esta liquidada

    if( (venta.total - venta.pagado ) > 0 )
    {
        Ext.getCmp("Clientes-AbonarVentaBoton").show();
    }
    else
    {
        Ext.getCmp("Clientes-AbonarVentaBoton").hide();
    }




};








Aplicacion.Clientes.prototype.creditoDeClientesPanelUpdater = function ( id_cliente  )
{  

    lista = Aplicacion.Clientes.currentInstance.listaDeCompras.lista;

    ventasCredito  = [{
        text : "Seleccione una venta a credito de la lista",
        value : -1
    }];

    //buscar en todas la ventas
    for (var i = lista.length - 1; i >= 0; i--){
        //si la venta es de este cliente, y es a credito y tiene algun saldo, mostrarla en la lista
        if ( lista[i].id_cliente == id_cliente && lista[i].tipo_venta  == "credito" ) {
            
            if(parseFloat(lista[i].total) != parseFloat(lista[i].pagado)){
                ventasCredito.push( {
                    text : "Venta " + lista[i].id_venta + " ( "+lista[i].fecha+" ) ",
                    value : lista[i].id_venta
                });
            }

        }
    }
	
    Ext.getCmp("Clientes-DetallesVentaCredito").hide();
    Ext.getCmp("Clientes-AbonarVentaBoton").hide();

    if(DEBUG){
        console.log('este cliente tien ' + ventasCredito.length + ' ventas a credito');
    }
	
    if( ventasCredito.length == 1){
        //no hay ventas a credito
        Ext.getCmp("Clentes-CreditoVentasLista").hide();
        Aplicacion.Clientes.currentInstance.detallesDeClientesPanel.getTabBar().getComponent(2).hide();
    }else{
        //si hay ventas a credito
        Ext.getCmp("Clentes-CreditoVentasLista").show();
        Ext.getCmp("Clentes-CreditoVentasLista").setOptions( ventasCredito );
        Aplicacion.Clientes.currentInstance.detallesDeClientesPanel.getTabBar().getComponent(2).show();
    }
	
};



Aplicacion.Clientes.prototype.eliminarCliente = function ( respuesta )
{
		
    if(respuesta != 'yes'){
        return;
    }

    cliente = Aplicacion.Clientes.currentInstance.detallesDeClientesPanel.getComponent(0).items.items[0].getRecord().data;

    if(DEBUG){
        console.log("Eliminando al cliente", cliente );
    }

    cliente.activo = 0;

    Ext.Ajax.request({
        url: '../proxy.php',
        scope : this,
        params : {
            action : 302,
            data : Ext.util.JSON.encode( cliente )
        },
        success: function(response, opts) {
            try{
                res = Ext.util.JSON.decode( response.responseText );
            }catch(e){
                return POS.error(e);
            }
			
            if( !res.success ){
                //volver a intentar
                Ext.Msg.alert("Mostrador", res.reason);
                return;

            }
			
            if(DEBUG){
                console.log( "Eliminado cliente OK", res );
            }
			

            sink.Main.ui.setActiveItem( Aplicacion.Clientes.currentInstance.listaDeClientesPanel , 'fade');
			

        }
    });
	
};




/*
 * Se llama para crear por primera vez el panel de detalles de cliente
 **/
Aplicacion.Clientes.prototype.detallesDeClientesPanelCreator = function (  ){
	
    if(DEBUG){
        console.log("creando panel de detalles de cliente por primera vez");
    }

    opciones = [
    //no se pueden eliminar clientes
    /*		new Ext.Button({ id : 'Clientes-EliminarCliente', ui  : 'action', text: 'Eliminar cliente',  handler : function(){
			cliente = Aplicacion.Clientes.currentInstance.detallesDeClientesPanel.getComponent(0).items.items[0].getRecord().data;
			if(DEBUG){
				console.log("intentando eliminar el cliente" , cliente);
			}
			
			//revisar que el cliente no deba dinero
			if( parseFloat(cliente.credito_restante) != parseFloat(cliente.limite_credito) ){
				Ext.Msg.alert("Mostrador", "Este cliente aun tiene un saldo pendiente. No puede eliminarlo.");
			}else{
				Ext.Msg.confirm("Mostrador","&iquest; Esta completamente seguro de que desea desactivar a este cliente ?", Aplicacion.Clientes.currentInstance.eliminarCliente );
			}
			

		}}),	
		*/
    new Ext.Button({
        id : 'Clientes-Estado-Cuenta',
        ui  : 'action',
        text: 'Imprimir Estado de Cuenta',
         handler : function(){
             window.open("http://pos.caffeina.mx/proxy.php?action=1307&id_cliente=" + Aplicacion.Clientes.currentInstance.detallesDeClientesPanel.getComponent(0).items.items[0].getValues().id_cliente);
         }
    }),
    new Ext.Button({
        id : 'Clientes-EditarDetalles',
        ui  : 'action',
        text: 'Editar Detalles del Cliente',
        handler : this.editarClienteBoton
    }),    
    new Ext.Button({
        id : 'Clientes-EditarDetallesCancelar',
        ui  : 'decline',
        text: 'Cancelar',
        handler : this.editarClienteCancelarBoton,
        hidden : true
    }),
    new Ext.Button({
        id : 'Clientes-EditarDetallesGuardar',
        ui  : 'confirm',
        text: 'Guardar',
        handler : this.editarClienteGuardarBoton,
        hidden : true
    })

    ];


    var dockedItems = [new Ext.Toolbar({
        ui: 'light',
        dock: 'top',
        items: [{
            xtype:"spacer"
        }].concat( opciones )
    })];


    detallesDelCliente = new Ext.form.FormPanel({
        title: 'Detalles del Cliente',
        //dockedItems : POS.U.g ? dockedItems : null,
        //scroll: 'vertical',
        items: [{
            xtype: 'fieldset',
            instructions: 'Los campos marcados con asterisco son obligatorios.',
            defaults : {
                disabled : true
            },
            items: [
            new Ext.form.Text({
                name: 'razon_social',
                label: 'Razon Social',
                required:true,
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'alfa',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                name: 'id_cliente',
                label: 'ID'	,
                hidden : true
            }),
            new Ext.form.Text({
                name : 'activo',
                hidden: true
            }),
            new Ext.form.Text({
                name : 'id_usuario',
                hidden: true
            }),
            new Ext.form.Text({
                name : 'id_sucursal',
                hidden: true
            }),
            new Ext.form.Text({
                name: 'rfc',
                label: 'RFC',
                required:true,
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'alfanum',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),           
            new Ext.form.Text({
                name : 'calle',
                label: 'Calle',
                required:true,
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'complete',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                name : 'numero_exterior',
                label: 'Numero Exterior',
                required:true,
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'complete',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                name : 'numero_interior',
                label: 'Numero Interior',
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'complete',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                name : 'colonia',
                label: 'Colonia',
                required:true,
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'complete',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                name : 'referencia',
                label: 'Referencia',
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'complete',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                name : 'localidad',
                label: 'Localidad',
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'complete',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                name : 'municipio',
                label: 'Municipio',
                required:true,
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'alfa',
                            submitText : 'Aceptar'
                        }; 
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                name : 'estado',
                label: 'Estado',
                value:'GUANAJUATO',
                required:true,
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'complete',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                name : 'pais',
                label: 'Pais',
                required:true,
                value:'MEXICO',
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'complete',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                name : 'codigo_postal',
                label: 'Codigo Postal',
                required:true,
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'complete',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                name : 'telefono',
                label: 'Telefono',
                required:true,
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'num',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                name : 'e_mail',
                label: 'E-mail',
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'complete',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                name : 'descuento',
                label: 'Descuento',
                required: false,
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'num',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                id : 'Clientes-credito-restante',
                name : 'credito_restante',
                label: 'Restante',
                required: false
            }),
            new Ext.form.Text({
                id : 'DetallesCliente_limite_credito',
                name : 'limite_credito',
                label: 'Lim. Credito',
                required: false,
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'num',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            })
            ]
        }
        ]
    });







    //abonar a una compra a credito
    abonar = [ new Ext.form.FormPanel({
        items: [{
            xtype: 'fieldset',
            title: 'Abonar a venta',
            id : 'Clientes-SeleccionVentaCredito',
            instructions: 'Seleccione una venta para ver sus detalles.',
            items: [{
                id : "Clentes-CreditoVentasLista",
                xtype: 'selectfield',
                name: 'options',
                label : "Venta",
                options: [  ],
                listeners : {
                    "change" : function(a,b) {
                        Aplicacion.Clientes.currentInstance.creditoDeClientesOptionChange(a,b);
                    }
                }
            }]
        },{
            xtype: 'fieldset',
            id : 'Clientes-DetallesVentaCredito',
            items: [
            new Ext.form.Text({
                name: 'fecha',
                label: 'Fecha'
            }),
            new Ext.form.Text({
                name: 'sucursal',
                label: 'Sucursal'
            }),
            new Ext.form.Text({
                name: 'user_id',
                label: 'Vendedor'
            }),
            new Ext.form.Text({
                name: 'total',
                label: 'Total'
            }),
            new Ext.form.Text({
                name: 'abonado',
                label: 'Abonado',
                id:'Clientes-DetallesVentaCredito-abonado'
            }),
            new Ext.form.Text({
                name: 'saldo',
                label: 'Saldo',
                id:'Clientes-DetallesVentaCredito-saldo'
            })
            ]
        },{
            xtype: 'fieldset',
            title: 'Detalles del abono',
            id : 'Clientes-DetallesVentaAbonarCredito',
            hidden : true,
            items: [
            new Ext.form.Text({
                name: 'saldo',
                label: 'Saldo',
                id: 'Clientes-DetallesVentaAbonarCredito-saldo'
            }),{
                id : "Clentes-abonarTipoPago",
                xtype: 'selectfield',
                name: 'tipo_pago',
                label : "Tipo de pago",
                options: [
                {
                    text:'Efectivo',
                    value:'efectivo'
                },{
                    text:'Cheque',
                    value:'cheque'
                }
                ]
            },
            new Ext.form.Text({
                id: 'Cliente-abonarMonto',
                name: 'monto',
                label: 'Monto',
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'num',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            })
            ]
        },

        new Ext.Button({
            id : 'Clientes-AbonarVentaBoton',
            ui  : 'action',
            text: 'Abonar',
            margin : 15,
            handler : this.abonarVentaBoton,
            hidden : true
        }),
        new Ext.Button({
            id : 'Clientes-AbonarVentaBotonAceptar',
            ui  : 'action',
            text: 'Abonar',
            margin : 15,
            handler : this.doAbonarValidator,
            hidden : true
        }),
        new Ext.Button({
            id : 'Clientes-AbonarVentaBotonCancelar',
            ui  : 'drastic',
            text: 'Cancelar',
            margin : 15,
            handler : this.abonarVentaCancelarBoton,
            hidden : true
        })


        ]
    }
    )];
	

    //crear el panel, y asignarselo a detallesDeClientesPanel
    this.detallesDeClientesPanel = new Ext.TabPanel({
        //NO MOVER EL ORDEN DEL MENU !!
        id : 'detallesDeClientesPanel',
        items: [{
            iconCls: 'user',
            title: 'Detalles',
            scroll: 'vertical',
            dockedItems : POS.U.g ? dockedItems : null,
            items : detallesDelCliente
        },{
            iconCls: 'bookmarks',
            title: 'Ventas',
            scroll: 'vertical',
            cls : "Tabla",
            html: 'Lista de compras'
        },{
            iconCls: 'download',
            title: 'Credito',
            items : abonar
        }],
        tabBar: {
            dock: 'bottom',
            layout: {
                pack: 'center'
            }
        }
    });
	
	
};



















/* ********************************************************
	Nuevo Cliente
******************************************************** */


/*
 * Guarda el panel donde estan la forma de nuevo cliente
 **/
Aplicacion.Clientes.prototype.nuevoClientePanel = null;

/*
 * Es la funcion de entrada para mostrar el panel de nuevo cliente
 **/
Aplicacion.Clientes.prototype.nuevoClientePanelShow = function ( ){
    if(DEBUG){
        console.log("mostrando nuevo cliente");
    }
	
    //hacer un setcard manual
    sink.Main.ui.setActiveItem( this.nuevoClientePanel , 'slide');
};







/*
 * Se llama para crear por primera vez el panel de nuevo cliente
 **/
Aplicacion.Clientes.prototype.nuevoClientePanelCreator = function (  ){

    if(DEBUG){
        console.log("creando panel de nuevo cliente");
    }
	
	
    this.nuevoClientePanel = new Ext.form.FormPanel({
        scroll:'vertical',
        items: [{
            xtype: 'fieldset',
            title: 'Ingrese los detalles del nuevo cliente',
            instructions: 'Si desea ofrecer un limite de credito que exceda los $20,000.00 debera pedir una autorizacion.',
            items: [
            new Ext.form.Text({
                name: 'razon_social',
                label: 'Razon Social',
                required:true,
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'alfa',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                name: 'rfc',
                label: 'RFC',
                required:true,
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'alfanum',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                name : 'calle',
                label: 'Calle',
                required:true,
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'complete',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                name : 'numero_exterior',
                label: 'Numero Exterior',
                required:true,
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'complete',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                name : 'numero_interior',
                label: 'Numero Interior',
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'complete',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                name : 'colonia',
                label: 'Colonia',
                required:true,
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'complete',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                name : 'referencia',
                label: 'Referencia',
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'complete',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                name : 'localidad',
                label: 'Localidad',
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'complete',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                name : 'municipio',
                label: 'Municipio',
                required:true,
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'alfa',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                name : 'estado',
                label: 'Estado',                
                value:'GUANAJUATO',
                required:true,
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'complete',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                name : 'pais',
                label: 'Pais',
                required:true,
                value:'MEXICO',
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'complete',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                name : 'codigo_postal',
                label: 'Codigo Postal',
                required:true,
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'complete',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                name : 'telefono',
                label: 'Telefono',
                required:true,
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'num',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                name : 'e_mail',
                label: 'E-mail',
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'complete',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                name : 'descuento',
                label: 'Descuento',
                required: false,
                value : '0',
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'num',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            }),
            new Ext.form.Text({
                name : 'limite_credito',
                label: 'Lim. Credito',
                value : '0',
                required: true,
                listeners : {
                    'focus' : function (){
                        kconf = {
                            type : 'num',
                            submitText : 'Aceptar'
                        };
                        POS.Keyboard.Keyboard( this, kconf );
                    }
                }
            })
            ]
        },
			
        new Ext.Button({
            id : 'Clientes-CrearCliente',
            ui  : 'action',
            text: 'Crear Cliente',
            margin : 5,
            handler : this.crearClienteValidator,
            disabled : false
        })
        ]
    });


	
};



Aplicacion.Clientes.prototype.crearCliente = function ( data )
{
    Ext.getBody().mask('Creando cliente ...', 'x-mask-loading', true);

    Ext.Ajax.request({
        url: '../proxy.php',
        scope : this,
        params : {
            action : 301,
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
                Aplicacion.Clientes.currentInstance.nuevoClientePanel.items.items[0].setInstructions(r.reason);
                return;
            }

            Ext.Msg.alert("Clientes","Se creo correctamente el nuevo cliente")

            //actualizar la lista de los clientes
            Aplicacion.Clientes.currentInstance.listaDeClientesLoad();
			
            //limpiar la forma
            Aplicacion.Clientes.currentInstance.nuevoClientePanel.reset();
			
            //poner las instrucciones originales
            Aplicacion.Clientes.currentInstance.nuevoClientePanel.items.items[0].setInstructions("Si desea ofrecer un limite de credito que exceda los $20,000.00 debera pedir una autorizacion.");
			
            //hacer un setcard manual hacia la lista de clientes
            sink.Main.ui.setActiveItem( this.listaDeClientesPanel , 'slide');

        }
    });
};

//Accion a realizar al presionar el boton de crear cliente
Aplicacion.Clientes.prototype.crearClienteValidator = function ()
{

    //validar los nuevos datos
    v = Aplicacion.Clientes.currentInstance.nuevoClientePanel.getValues();
    campo = Aplicacion.Clientes.currentInstance.nuevoClientePanel.items.items[0];
	
    //nombre
    if(v.razon_social.length < 10){
        Ext.Msg.alert("Nuevo cliente", "La Razon Social del cliente debe ser mayor de diez caracteres.");
        return campo.setInstructions("La Razon Social del cliente debe ser mayor de diez caracteres.");
    }
	
    //rfc
    if(v.rfc.length < 10){
        Ext.Msg.alert("Nuevo cliente", "El RFC debe ser mayor de diez caracteres.");
        return campo.setInstructions("El RFC debe ser mayor de diez caracteres.");
    }
	
    //calle
    if(v.calle.length < 3){
        Ext.Msg.alert("Nuevo cliente", "La descripcion de la calle es muy corta.");
        return campo.setInstructions("La descripcion de la calle es muy corta.");
    }

    //numero_exterior
    if(v.numero_exterior.length == 0){
        Ext.Msg.alert("Nuevo cliente", "Indique el numero exterior.");
        return campo.setInstructions("Indique el numero exterior.");
    }    

    //colonia
    if(v.colonia.length < 3){
        Ext.Msg.alert("Nuevo cliente", "La descripcion de la colonia es muy corta.");
        return campo.setInstructions("La descripcion de la colonia es muy corta.");
    }

    //municipio
    if(v.municipio.length < 3){
        Ext.Msg.alert("Nuevo cliente", "La descripcion del municipio es muy corta.");
        return campo.setInstructions("La descripcion del municipio es muy corta.");
    }
	
    //estado
    if(v.estado.length < 3){
        Ext.Msg.alert("Nuevo cliente", "La descripcion del estado es muy corta.");
        return campo.setInstructions("La descripcion del estado es muy corta.");
    }

    //pais
    if(v.pais.length < 3){
        Ext.Msg.alert("Nuevo cliente", "La descripcion del pais es muy corta.");
        return campo.setInstructions("La descripcion del pais es muy corta.");
    }
	
    //codigo_postal
    if(v.codigo_postal.length < 5 || isNaN(v.codigo_postal)){
        Ext.Msg.alert("Nuevo cliente", "La descripcion del codigo postal es muy corta.");
        return campo.setInstructions("La descripcion del codigo postal es muy corta.");
    }

    //telefono
    if(v.telefono.length < 10){
        Ext.Msg.alert("Nuevo cliente", "La descripcion del telefono es muy corta.");
        return campo.setInstructions("La descripcion del telefono es muy corta.");
    }

	
    //descuento
    if(  v.descuento.length === 0 || isNaN( v.descuento ) || (v.descuento > 50 && v.descuento < 0) ){
        Ext.Msg.alert("Nuevo cliente", "El descuento debe ser un numero entre 0 y 50.");
        return campo.setInstructions("El descuento debe ser un numero entre 0 y 50.");
    }
	
    //limite_credito
    if( v.limite_credito.length === 0 || isNaN( v.limite_credito ) ){
        Ext.Msg.alert("Nuevo cliente", "El limite de credito debe ser un numero.");
        return campo.setInstructions("El limite de credito debe ser un numero.");
    }
	
    //limite_credito
    if( v.limite_credito > 20000 ){

        Ext.Msg.alert("Nuevo cliente", "No puede crear un cliente con limite de credito mayor a $20,000. Necesitara pedir una autorizacion.");
        return campo.setInstructions("No puede crear un cliente con limite de credito mayor a $20,000. Necesitara pedir una autorizacion.");
    }

    Aplicacion.Clientes.currentInstance.crearCliente( v );
};


/* ***************************************************************************
   * Panel de impresion de ticket de recepcion de producto
   *************************************************************************** */

Aplicacion.Clientes.prototype.finishedPanel = null;

Aplicacion.Clientes.prototype.finishedPanelCreator = function()
{

    this.finishedPanel = new Ext.Panel({
        html : ""
    });
	
};

Aplicacion.Clientes.prototype.finishedPanelShow = function( data_abono )
{

    //update panel
    this.finishedPanelUpdater( data_abono );
	
    Aplicacion.Clientes.currentInstance.abonarVentaCancelarBoton();
	
    //mostramos el panel del inventario
    action = "sink.Main.ui.setActiveItem( Aplicacion.Clientes.currentInstance.detallesDeClientesPanel , 'slide');";
                    
    setTimeout(action, 4000);
	
};



Aplicacion.Clientes.prototype.finishedPanelUpdater = function( data_abono )
{   
	
    if(DEBUG){
        console.log( "se mando a imprimir : ", Ext.util.JSON.encode( data_abono  ) );
    }
	
    json = encodeURI( Ext.util.JSON.encode( data_abono ) );
	
    do
    {
        json = json.replace('#','%23');
    }
    while(json.indexOf('#') >= 0);
	
	
    html = "";
	
    html += "<table class='Mostrador-ThankYou' style = 'margin : 0 !important;' >";
	
    html += "	<tr>";
    html += "		<td ><img width = 200px height = 200px src='../media/Receipt128.png'></td>";
    html += "	</tr>";
	
    html += "	<tr>";	
    html += "		<td align = center> El abono ha sido registrado.. </td>";
    html += "	</tr>";

    html += "</table>";

	
    //html += "<iframe src ='PRINTER/src/impresion.php?json=" + json + "' width='0px' height='0px'></iframe> ";
    hora = new Date()
    var dia = hora.getDate();
    var mes = hora.getMonth();
    var anio = hora.getFullYear();
    horas = hora.getHours()
    minutos = hora.getMinutes()
    segundos = hora.getSeconds()
    if (mes <= 9) mes = "0" + mes
    if (horas >= 12) tiempo = " p.m."
    else tiempo = " a.m."
    if (horas > 12) horas -= 12
    if (horas == 0) horas = 12
    if (minutos <= 9) minutos = "0" + minutos
    if (segundos <= 9) segundos = "0" + segundos

    html += ''
    +'<applet code="printer.Main" archive="PRINTER/dist/PRINTER.jar" WIDTH=0 HEIGHT=0>'
    +'     <param name="json" value="'+ json +'">'
    +'     <param name="hora" value="' + horas + ":" + minutos + ":" + segundos + tiempo + '">'
    +'     <param name="fecha" value="' + dia +"/"+ (hora.getMonth() + 1) +"/"+ anio + '">'
    +' </applet>';
	
    //actualiza el panel de la impresion
    this.finishedPanel.update(html);
	
    //muestra el panel donde se embebe el iframe para la impresion
    sink.Main.ui.setActiveItem( this.finishedPanel , 'fade');		

};



/* ********************************************************
	Panel de reimpresion de ticket
******************************************************** */
Aplicacion.Clientes.prototype.finishedPanelReimpresionTicket = null;

Aplicacion.Clientes.prototype.finishedPanelShowReimpresionTicket = function( venta )
{

    //update panel
    this.finishedPanelReimpresionTicketUpdater( venta );
	
    action = "sink.Main.ui.setActiveItem( Aplicacion.Clientes.currentInstance.detallesDeClientesPanel , 'slide');";
    setTimeout(action, 4000);
	
};


//
Aplicacion.Clientes.prototype.finishedPanelReimpresionTicketUpdater = function( venta )
{

    var items = [];

    for( var i = 0; i < venta.detalle_venta.length; i++){
            
        //verificamos si se vendio producto original
        if( venta.detalle_venta[i].cantidad > 0 ){
            items.push(
            {
                cantidad : venta.detalle_venta[i].cantidad,
                descripcion : venta.detalle_venta[i].descripcion,
                precio : venta.detalle_venta[i].precio
            }
            );
        }
            
        //verificamos si se vendio producto procesado
        if( venta.detalle_venta[i].cantidad_procesada > 0 ){
            items.push(
            {
                cantidad : venta.detalle_venta[i].cantidad_procesada - venta.detalle_venta[i].descuento,
                descripcion : venta.detalle_venta[i].descripcion,
                precio : venta.detalle_venta[i].precio_procesada
            }
            );
        }
            
    }

    for( i = 0; i < POS.documentos.length; i++){
        if( POS.documentos[i].documento == 'venta_cliente' ){
            var impresora = POS.documentos[i].impresora;
            break;
        }
    }
   

    var reimprimirVenta = {
        tipo_venta : venta.tipo_venta,
        id_venta : venta.id_venta,
        cliente : {
            razon_social : venta.razon_social
        },
        items : items,
        tipo_pago : venta.tipo_pago,
        subtotal : venta.subtotal,
        empleado : venta.cajero, 
        sucursal : POS.infoSucursal,
        total : venta.total,
        fecha_venta : venta.fecha,
        ticket : 'venta_cliente',
        leyendasTicket : POS.leyendasTicket,
        reimpresion : true,
        impresora : impresora
    }
	

    if(DEBUG){
        console.log("reimpresion de venta : ", reimprimirVenta);
    }

    json = encodeURI( Ext.util.JSON.encode( reimprimirVenta ) );
	
    do
    {
        json = json.replace('#','%23');
    }
    while(json.indexOf('#') >= 0);
	
    html = "";
	
    html += "<table class='Mostrador-ThankYou'>";
    html += "	<tr>";
    html += "		<td ><img width = 200px height = 200px src='../media/Receipt128.png'></td>";
    html += "		<td></td>";
    html += "	</tr>";
    html += "	<tr>";	
    html += "		<td align center >Reimprimendo ticket de venta...</td>";
    html += "		<td></td>";
    html += "	</tr>";

    html += "</table>";
		
    //html += "<iframe src ='PRINTER/src/impresion.php?json=" + json + "' width='0px' height='0px'></iframe> ";

    hora = new Date()
    var dia = hora.getDate();
    var mes = hora.getMonth();
    var anio = hora.getFullYear();
    horas = hora.getHours()
    minutos = hora.getMinutes()
    segundos = hora.getSeconds()
    if (mes <= 9) mes = "0" + mes
    if (horas >= 12) tiempo = " p.m."
    else tiempo = " a.m."
    if (horas > 12) horas -= 12
    if (horas == 0) horas = 12
    if (minutos <= 9) minutos = "0" + minutos
    if (segundos <= 9) segundos = "0" + segundos

    html += ''
    +'<applet code="printer.Main" archive="PRINTER/dist/PRINTER.jar" WIDTH=0 HEIGHT=0>'
    +'     <param name="json" value="'+ json +'">'
    +'     <param name="hora" value="' + horas + ":" + minutos + ":" + segundos + tiempo + '">'
    +'     <param name="fecha" value="' + dia +"/"+ (hora.getMonth() + 1) +"/"+ anio + '">'
    +' </applet>';

	
	
    this.finishedPanelReimpresionTicket.update(html);

    sink.Main.ui.setActiveItem( this.finishedPanelReimpresionTicket , 'fade');

};

Aplicacion.Clientes.prototype.finishedPanelCreatorReimpresionTicket = function()
{

    this.finishedPanelReimpresionTicket = new Ext.Panel({
        html : ""
    });
	
};






POS.Apps.push( new Aplicacion.Clientes() );
