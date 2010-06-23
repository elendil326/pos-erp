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
	
		

	POS.AJAXandDECODE({
			method: 'listarClientes'
		}, 
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
	function (){
	}
	);

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
