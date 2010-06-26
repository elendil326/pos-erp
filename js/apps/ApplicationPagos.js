ApplicationPagos= function ()
{
	if(DEBUG){
		console.log("ApplicationPagos: construyendo");
	}
	ApplicationPagos.currentInstance = this;	
	//ApplicationPagos.prototype.currentInstance=this;
	this._init();

	return this;
	
	
};


//variables de esta clase, NO USEN VARIABLES ESTATICAS A MENOS QUE SEA 100% NECESARIO

//aqui va el panel principal 
ApplicationPagos.prototype.mainCard = null;

ApplicationPagos.currentInstance=87;

//aqui va el nombre de la applicacion
ApplicationPagos.prototype.appName = null;

//aqui va el nombre de la applicacion
ApplicationPagos.prototype.leftMenuItems = null;

//aqui va un texto de ayuda en html
ApplicationPagos.prototype.ayuda = null;

ApplicationPagos.prototype.clientesContainer= null;

ApplicationPagos.prototype.MostrarStore = null;

ApplicationPagos.prototype.ListaClientes= null;

ApplicationPagos.prototype.customers = null;

ApplicationPagos.prototype._init = function()
{
	
	//iniciar variables
	
	//nombre de la aplicacion
	this.appName = "Pagos";
	
	//ayuda sobre esta applicacion
	this.ayuda = "Ayuda sobre este modulo de prueba <br>, html es valido <br> :D";
	
	//submenues en el panel de la izquierda
	this.leftMenuItems = [
	{
        text: 'Listar',
        ayuda: 'SE ENLISTAN TODOS LOS CLIENTES REGISTRADOS EN PAPAS SUPREMAS',
		card: this.mainCard
    },
    {
        text: 'Abonar',
       	card: this.abonar,
        ayuda: 'ayuda en SECOND'
    }
	];
	
	
	
	this._initToolBar();
	
};//fin CONSTRUCTOR

cbClientes=new Ext.form.TextField({
			hidden:'true',
            name: 'id_cliente',
            label: 'Cliente',
            options: [{
                text: 'nombre',
                value: 'id'
            },{
                text: 'name',
                value: 'id_c'
            }]
        });
de =new Ext.form.TextField({
			fieldLabel: 'Del:',
			name: 'fecha_inicio:',
			allowBlank:false
		});
al =new Ext.form.TextField({
			fieldLabel: 'al:',
			name: 'fecha_fin:',
			allowBlank:false
		});
fechas = new Ext.form.FormPanel({
	hidden:'true',
    items: [de,al]
});

formulario=new Ext.form.FormPanel({
		minHeight:80,
    items: [{
        xtype: 'fieldset',
        title: 'Buscar por:',
        instructions: 'Insertelos datos y de click en el boton de buscar.',
        items: [cbClientes,fechas]
    }]
});

//Iniciar el toolbar
ApplicationPagos.prototype._initToolBar = function (){

	if(DEBUG)console.log("iniciando el tool bar");


	//Dejo los demas grupos por si quieres agregar mas botones
	//grupo 1, funciones basicas
	var buttonsGroup1 = [{
        text: 'Buscar',
        ui: 'round',
        handler: clickBuscar
    }];


	//grupo 2, cualquier otra mamada
	var buttonsGroup2 = [{
        xtype: 'splitbutton',
        activeButton: 0,
        items: [{
            text: 'Deudores',
            handler: clickDeudores
        }, {
            text: 'Pagados',
            handler: clickPagados
        }, {
            text: 'Todos',
            handler: clickTodos
        }]    
    }];
    



	//grupo 3, listo para vender
    var buttonsGroup3 = [{
        text: 'Cliente',
        handler: clickCliente
    },{
        text: 'Periodo',
        handler: clickPeriodo
    }];


	if (!Ext.platform.isPhone) {
        buttonsGroup1.push({xtype: 'spacer'});
        buttonsGroup2.push({xtype: 'spacer'});
        
        this.dockedItems = [new Ext.Toolbar({
            // dock this toolbar at the bottom
            ui: 'light',
            dock: 'top',
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
	
	//agregar este dock a el panel que quieras
	this.mainCard.addDocked( this.dockedItems );
	

};

Ext.regModel('modeloVentas', {
    fields: ['id_venta','total', 'pagado', 'debe', 'nombre', 'fecha']
});



MostrarStore = new Ext.data.Store({
    model: 'modeloVentas',
    sorters: 'nombre',
    getGroupString : function(record) {
        return record.get('nombre')[0];
    }
});


ApplicationPagos.prototype.mainCard = new Ext.Panel({
   
    scroll: 'vertical',
	cls: 'cards',
	layout: {
		type: 'vbox',
		align: 'strech'
	},
	
    listeners: {
		beforeshow : function(component){
						
						POS.AJAXandDECODE({
						method: 'reporteClientesComprasCreditoDeben'
						},
						function (datos){
							this.customers = datos.datos;
							MostrarStore.loadData(this.customers); 
						},
						function (){//no responde
							POS.aviso("ERROR",":(");
						}
					);	
		}//fin before
	},
    items: [formulario,
			{
						xtype: 'toolbar',
						dock: 'bottom',
						title: "TOTAL &#09; PAGADO ADEUDA DEBE CLIENTE FECHA"
				},
			{
				width: '100%',
				height: '100%',
				xtype: 'list',
				store: MostrarStore,
				tpl: '<tpl for="."><div class="modeloVentas">total:{total}pagado:{pagado} adeuda:{debe} cliente:{nombre} fecha:{fecha}</div></tpl>',
				itemSelector: 'div.modeloVentas',
				singleSelect: true,
				indexBar: true
			}
			]	
});

ApplicationPagos.prototype.abonar = new Ext.form.FormPanel({
	
});
this.clickBuscar = function ()
{
	if(!cbClientes.isVisible()){
			if(!fechas.isVisible()){
				POS.aviso("Buscar", "Buscar al cliente: "+cbClientes.getValue()+" del:"+de.getValue()+" al "+al.getValue()); 				
			}else{
				POS.aviso("Buscar", "Buscar al cliente: "+cbClientes.getValue()); 			
			}	
	}else{
			if(!fechas.isVisible()){
				POS.aviso("Buscar", "Buscar ventas a credito del: "+de.getValue()+" al: "+al.getValue()); 
			}else{	
				POS.aviso("Imposible", "No se puede buscar ya que no hay ninguna opcion seleccionada"); 
			}
	}
};
this.clickDeudores = function ()
{
		 	POS.aviso("OK", "boton de Deudores"); 
};

this.clickPagados = function ()
{
		 	POS.aviso("OK", "boton de Pagados"); 
};

this.clickTodos = function ()
{
		 	POS.aviso("OK", "boton de Todos"); 
};

this.clickCliente = function ()
{
	if(cbClientes.isVisible()){
			cbClientes.setVisible(true);
			formulario.setHeight(formulario.getHeight()+30)
	}else{
			cbClientes.setVisible(false);
			formulario.setHeight(formulario.getHeight()-30)
	}
};

this.clickPeriodo = function ()
{
	if(fechas.isVisible()){
			fechas.setVisible(true);
			formulario.setHeight(formulario.getHeight()+70)
	}else{
			fechas.setVisible(false);
			formulario.setHeight(formulario.getHeight()-70)
	}
};

//autoinstalar esta applicacion
AppInstaller( new ApplicationPagos() );
