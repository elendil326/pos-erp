ApplicacionTest= function ()
{
	if(DEBUG){
		console.log("ApplicacionTest: construyendo");
	}
	ApplicacionTest.currentInstance = this;	
	//ApplicacionTest.prototype.currentInstance=this;
	this._init();

	return this;
	
	
};


//variables de esta clase, NO USEN VARIABLES ESTATICAS A MENOS QUE SEA 100% NECESARIO

//aqui va el panel principal 
ApplicacionTest.prototype.mainCard = null;

ApplicacionTest.currentInstance=87;

//aqui va el nombre de la applicacion
ApplicacionTest.prototype.appName = null;

//aqui va el nombre de la applicacion
ApplicacionTest.prototype.leftMenuItems = null;

//aqui va un texto de ayuda en html
ApplicacionTest.prototype.ayuda = null;

ApplicacionTest.prototype.clientesContainer= null;

ApplicacionTest.prototype.ClientesListStore = null;

ApplicacionTest.prototype.ListaClientes= null;

ApplicacionTest.prototype.customers = null;

ApplicacionTest.prototype._init = function()
{
	
	//iniciar variables
	
	//nombre de la aplicacion
	this.appName = "CLIENTES";
	
	//ayuda sobre esta applicacion
	this.ayuda = "Ayuda sobre este modulo de prueba <br>, html es valido <br> :D";
	
	//submenues en el panel de la izquierda
	this.leftMenuItems = [
	{
        text: 'VER CLIENTES',
        ayuda: 'SE ENLISTAN TODOS LOS CLIENTES REGISTRADOS EN PAPAS SUPREMAS',
		card: this.ClientesList
    },
    {
        text: 'SECOND',
       	card: this.menu2,
        ayuda: 'ayuda en SECOND'
    }
	];
	
	//panel principal
	this.mainCard = new Ext.Panel({
		cls: 'card card1',
		html: 'Entrando a sencha'
	});
	
	
	
	
};//fin CONSTRUCTOR

//------------------------------------------------------------------------------------------------------------------------------------------------
//
//					BUSCAR CLIENTES
//
//------------------------------------------------------------------------------------------------------------------------------------------------
//var modelin = new Ext.regModel('clientesModelo',{fields:['id_cliente','rfc','nombre','direccion','telefono','e_mail','limite_credito']});
//	
//	ApplicacionTest.prototype.Clientesss=  new Ext.data.JsonStore({
//                url: 'serverProxy.php?method=listarClientes',
//                root: 'datos',
//				model: modelin,
//				batchUpdateMode : 'operation'
				//fields: [{name:'id_cliente',type: 'string'},{name:'rfc',type: 'string'},{name:'nombre',type: 'string'},{name:'direccion',type: 'string'},{name:'telefono',type: 'string'},{name:'e_mail',type: 'string'},{name:'limite_credito',type: 'string'}]
				
  //  });

//	console.log(ApplicacionTest.currentInstance.Clientesss.loadData(myData,true));


ApplicacionTest.prototype.ajaxaso = function(){
POS.AJAXandDECODE({
	method: 'listarClientes'
	},
	function (datos){//mientras responda
		POS.aviso("ok","todo salio bien");
		console.log(datos);
//		datos.datos[0].nombre
		console.log("datos.datos[0].nombre = "+datos.datos[0].nombre);
		ApplicacionTest.currentInstance.ListaClientes=datos;
	},
	function (){//no responde
		POS.aviso("ERROR",":(");
	}
);
}//fin ajaxaso

Ext.regModel('Contact', {
    fields: ['nombre', 'direccion']
});



ClientesListStore = new Ext.data.Store({
    model: 'Contact',
    sorters: 'nombre',
    getGroupString : function(record) {
        return record.get('nombre')[0];
    }
});


ApplicacionTest.prototype.ClientesList = new Ext.Panel({
    layout: Ext.platform.isPhone ? 'fit' : {
        type: 'vbox',
        align: 'left',
        pack: 'center'
    },
	
    listeners: {
		beforeshow : function(component){
						
						POS.AJAXandDECODE({
						method: 'listarClientes'
						},
						function (datos){//mientras responda
							POS.aviso("ok","todo salio bien");
							console.log(datos.datos);
							console.log("datos.datos[0].nombre = "+datos.datos[0].nombre);
							this.customers = datos.datos;
							ClientesListStore.loadData(this.customers); 
							
						},
						function (){//no responde
							POS.aviso("ERROR",":(");
						}
					);	
		}//fin before
	},
    items: [{
        width: '100%',
        height: '100%',
        xtype: 'list',
        store: ClientesListStore,
        tpl: '<tpl for="."><div class="contact"><strong>{nombre}</strong> {direccion}</div></tpl>',
        itemSelector: 'div.contact',
        singleSelect: true,
        grouped: true,
        indexBar: true
    }]
	
});




/*ApplicacionTest.prototype.clientesList = function() {
		
        Ext.getBody().mask(false, '<div class="demos-loading">Loading&hellip;</div>');
		//console.log( ApplicacionTest.currentInstance );				
        Ext.Ajax.request({
            url: 'serverProxy.php?method=listarClientes',
            success: function(response, opts) {
				//console.log(":::: SI SUCCESS ::::", this, this.clientesContainer);
				
				ApplicacionTest.currentInstance.clientesContainer.update(response.responseText);
                ApplicacionTest.currentInstance.clientesContainer.scroller.scrollTo({x: 0, y: 0});
				Ext.getBody().unmask();
            },
			failure: function()
            {
            	console.log("ERROR: ");
            }
        });
    };*/
	




ApplicacionTest.prototype.menu2 = new Ext.form.FormPanel({
    scroll: 'vertical',
    items: [{
        xtype: 'fieldset',
        title: 'Personal Info',
        instructions: 'Please enter the information above.',
        items: [{
            xtype: 'textfield',
            name: 'name',
            label: 'Name'
        },
        {
            xtype: 'passwordfield',
            name: 'password',
            label: 'Password'
        },
        {
            xtype: 'emailfield',
            name: 'email',
            label: 'Email',
            placeholder: 'you@domain.com'
        },
        {
            xtype: 'urlfield',
            name: 'url',
            label: 'Url',
            placeholder: 'http://google.com'
        },
        {
            xtype: 'checkbox',
            name: 'cool',
            label: 'Cool'
        },
        {
            xtype: 'select',
            name: 'rank',
            label: 'Rank',
            options: [{
                text: 'Master',
                value: 'master'
            },
            {
                text: 'Student',
                value: 'padawan'
            }]
        },
        {
            xtype: 'hidden',
            name: 'secret',
            value: false
        },
        {
            xtype: 'textarea',
            name: 'bio',
            label: 'Bio'
        }]
    },
    {
        xtype: 'fieldset',
        title: 'Favorite color',
        defaults: {
            xtype: 'radio'
        },
        items: [{
            name: 'color',
            label: 'Red'
        },
        {
            name: 'color',
            label: 'Blue'
        },
        {
            name: 'color',
            label: 'Green'
        },
        {
            name: 'color',
            label: 'Purple'
        }]
    },
    {
        xtype: 'slider',
        name: 'value',
        label: 'Value'
    },
    {
        xtype: 'toggle',
        name: 'enable',
        label: 'Enable'
    }]
});



//autoinstalar esta applicacion
AppInstaller( new ApplicacionTest() );
