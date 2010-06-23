ApplicationPagos = function ()
{
	if(DEBUG){
		console.log("ApplicationPagos: construyendo");
	}
	return this._init();
};


//variables de esta clase, NO USEN VARIABLES ESTATICAS A MENOS QUE SEA 100% NECESARIO

//aqui va el panel principal 
ApplicationPagos.prototype.mainCard = null;

//aqui va el nombre de la applicacion
ApplicationPagos.prototype.appName = null;

//aqui va el nombre de la applicacion
ApplicationPagos.prototype.leftMenuItems = null;

//aqui va un texto de ayuda en html
ApplicationPagos.prototype.ayuda = null;


ApplicationPagos.prototype.date;
ApplicationPagos.prototype.clientesStore;

ApplicationPagos.prototype._init = function()
{
	
	//iniciar variables
	
	//nombre de la aplicacion
	this.appName = "Pagos";
	
	//ayuda sobre esta applicacion
	this.ayuda = "ayuda sobre este modulo de Pagos";
	
	//submenues en el panel de la izquierda
	this.leftMenuItems = [
	{
        text: 'Listar',
       	card: this.listar,
        ayuda: 'ayuda en listado'
    },
    {
        text: 'Abonar',
       	card: this.abonar,
        ayuda: 'ayuda de abonar'
    }
	];
	
	//panel principal
	this.mainCard = new Ext.Panel({
		cls: 'card card1',
		html: 'PAGOS'
	});
	
		
	//DIEGO: intenta comentar e identar este codigo....
	POS.AJAXandDECODE({
			method: 'listarClientes'
		},
	//success
	function (d){
		this.date=d;
		if(date.success){	
	Ext.regModel('modelo', {		
		fields: ['id_cliente','rfc','nombre','direccion','telefono','e_mail','limite_credito']
	});
			this.clientesStore=new Ext.data.Store({
				model:'modelo',
				idIndex:'id_cliente',
				data:this.date.datos
			});
		}
	},
	//failure
	function (){
		//siempre pon codigo aqui, como un aviso, para saber que algo salio mal
	}
	);



	this._initToolBar();


};



//Iniciar el toolbar
ApplicationPagos.prototype._initToolBar = function (){



	//Dejo los demas grupos por si quieres agregar mas botones
	//grupo 1, funciones basicas
	var buttonsGroup1 = [/*{
        text: 'Agregar producto',
        ui: 'round',
        handler: this.doAddProduct
    }*/];


	//grupo 2, cualquier otra mamada
	var buttonsGroup2 = [{
        xtype: 'splitbutton',
        activeButton: 0,
        items: [{
            text: 'Todos',
            handler: null
        }, {
            text: 'Deudores',
            handler: null
        }, {
            text: 'Pagados',
            handler: null
        }]    
    }];
    



	//grupo 3, listo para vender
    var buttonsGroup3 = [/*{
        text: 'Cotizar',
        handler: null
    },{
        text: 'Vender',
        ui: 'action',
        handler: null
    }*/];


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













ApplicationPagos.prototype.listar = new Ext.Panel({
    scroll: 'vertical',
	cls: 'cards',
	layout: {
		type: 'vbox',
		align: 'top'
	},
	defaults:{
		flex: 1
	},
	items:[
		new Ext.form.FormPanel({
			items: [{
					xtype: 'fieldset',
					title: 'Filtrar por:',
					defaults: {
						xtype: 'radio'
					},
					items: [{
						name: 'filtro',
						label: 'todos'
					},
					{
						name: 'filtro',
						label: 'Adeudan'
					},
					{
						name: 'filtro',
						label: 'Pagados'
					}]
				},
				{
					fieldLabel: 'Cliente:',
					xtype : "textfield",
					name: 'nombre:',
					allowBlank:false
				}
			]
		}),
		{
			html: 'grid con los datos',
			cls: 'card3'
		}
		]
});

ApplicationPagos.prototype.abonar = new Ext.form.FormPanel({
    scroll: 'vertical',
    items: [{
        xtype: 'slider',
        name: 'Fotmulario',
        label: 'form:'
    }]
});


//autoinstalar esta applicacion
AppInstaller( new ApplicationPagos() );
