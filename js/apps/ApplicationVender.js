ApplicationVender = function ()
{
	if(DEBUG){
		console.log("ApplicationVender: construyendo");
	}
	
	this._init();
	
	ApplicationVender.currentInstance = this;
	
	return this;
};








//aqui va el panel principal 
ApplicationVender.prototype.mainCard = null;

//aqui va el nombre de la applicacion
ApplicationVender.prototype.appName = null;

//aqui va el nombre de la applicacion
ApplicationVender.prototype.leftMenuItems = null;

//aqui va un texto de ayuda en html
ApplicationVender.prototype.ayuda = null;

//dockedItems
ApplicationVender.prototype.dockedItems = null;






ApplicationVender.prototype._init = function()
{
	
	//iniciar variables
	
	//nombre de la aplicacion
	this.appName = "Venta en mostrador";
	
	//ayuda sobre esta applicacion
	this.ayuda = "esto es una ayuda sobre este modulo de compras, html es valido <br> :D";
	
	//submenues en el panel de la izquierda
	this.leftMenuItems = [
	{
        text: 'menu1',
       	card: this.menu1,
        ayuda: 'ayuda en menu 1'
    },
    {
        text: 'menu2',
       	card: this.menu2,
        ayuda: 'ayuda en menu2'
    }];


	//initialize the tootlbar which is a dock
	this._initToolBar();
	
	
	//panel principal	
	this.mainCard = this.venderMainPanel;
	
	
	
};






ApplicationVender.prototype.doVender = function ()
{
	
	if(DEBUG){
		console.log("ApplicationVender: doVender called....");
	}
	
	
	console.log("prueba de comunicacion con server");
	
	Ext.Ajax.request({
		
		url: 'serverProxy.php',
		success: function (response){
			alert(response.responseText);
		},
		params:{
			method : 'actualizarCantidadProductoDetCot'
		}
		
	});	
 
};


ApplicationVender.prototype.doCotizar = function ()
{
	
	if(DEBUG){
		console.log("ApplicationVender: doCotizar called....");
	}
	
};




ApplicationVender.prototype.doAddProduct = function (button, event)
{
	
	if(DEBUG){
		console.log("ApplicationVender: doAddProduct called....");
	}
	
};



ApplicationVender.prototype._initToolBar = function (){


	//grupo 1, funciones basicas
	var buttonsGroup1 = [{
        text: 'Agregar producto',
        ui: 'round',
        handler: this.doAddProduct
    }];


	//grupo 2, cualquier otra mamada
	var buttonsGroup2 = [/*{
        xtype: 'splitbutton',
        activeButton: 0,
        items: [{
            text: 'Option 1',
            handler: tapHandler
        }, {
            text: 'Option 2',
            handler: tapHandler
        }, {
            text: 'Option 3',
            handler: tapHandler
        }]    
    }*/];
    



	//grupo 3, listo para vender
    var buttonsGroup3 = [{
        text: 'Cotizar',
        handler: this.doCotizar
    },{
        text: 'Vender',
        ui: 'action',
        handler: this.doVender
    }];


	if (!Ext.platform.isPhone) {
        buttonsGroup1.push({xtype: 'spacer'});
        buttonsGroup2.push({xtype: 'spacer'});
        
        this.dockedItems = [new Ext.Toolbar({
            // dock this toolbar at the bottom
            ui: 'light',
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
	this.venderMainPanel.addDocked( this.dockedItems );
	

};






ApplicationVender.prototype.swapClienteComun = function (val)
{	
	if(val==0){
		ApplicationVender.currentInstance.mainCard.items.items[0].add(  {xtype: 'slider', id: 'asdfclienteComun', value: 1, label: 'Agregar Cliente'}   );
		//ApplicationVender.currentInstance.mainCard.items.items[0].add(  {xtype: 'button', id: 'asdfclienteComun', value: 1, label: 'Agregar Cliente'}   );
		ApplicationVender.currentInstance.mainCard.doLayout();		
	}else{
		ApplicationVender.currentInstance.mainCard.items.items[0].remove(   'asdfclienteComun'  );
		ApplicationVender.currentInstance.mainCard.doLayout();
	}

};








ApplicationVender.prototype.venderMainPanel = new Ext.form.FormPanel({
	//tipo de scroll
    scroll: 'vertical',

	//toolbar
	dockedItems: null,
	
	//items del formpanel
    items: [{

        xtype: 'fieldset',
        title: 'Detalles del cliente',
        //instructions: 'Please enter the information above.',
        items: [{
	        xtype: 'toggle',
	        name: 'clienteComun',
			value: 1,
	        label: 'Cliente Comun',
			listeners:
					{
						change: function( slider, thumb, oldValue, newValue){
							if(oldValue == newValue) { return; }
							ApplicationVender.currentInstance.swapClienteComun(newValue);
						}
					}
        },
        {
            xtype: 'hidden',
            name: 'secret',
            value: false
        }]
    },
    {
        xtype: 'fieldset',
        title: 'Detalles de la Venta',
        defaults: {
            xtype: 'radio',
        },
        items: [{
	        xtype: 'toggle',
	        name: 'enable',
	        label: 'Factura'
        },
        {
	        xtype: 'toggle',
	        name: 'enable',
	        label: 'Nota'
        }]
    }]
});







ApplicationVender.prototype.menu1 = new Ext.Panel({
	cls: 'cards',
	layout: {
		type: 'vbox',
		align: 'strech'
	},
	defaults:{
		flex: 1
	},
	items:[{
		xtype: 'carousel',
		cls: 'card',
		items : [
			{
			            html: '<p>Navigate the carousel on this page by swiping left/right or clicking on one side of the circle indicators below.</p>',
			            cls: 'card1',
			        },
			        {
			            html: 'Card #2',
			            cls: 'card2'
			        },
			        {
			            html: 'Card #3',
			            cls: 'card3'
			        }
		]
	}]
	
	
});


ApplicationVender.prototype.menu2 = new Ext.form.FormPanel({
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
		disabled : true,
        label: 'AJAX '
    }]
});



//autoinstalar esta applicacion
AppInstaller( new ApplicationVender() );
















/*
demos.Animations = new Ext.Panel({

})

demos.Animations.slide = new Ext.Panel({
	html: 'Slides can be used in tandem with <code>direction: "up/down/left/right"</code>.',
	cls: 'card card2'
});

demos.Animations.slidecover = new Ext.Panel({
	html: 'In addition to <code>direction</code>, slide can also accept <code>cover: true</code>',
	cls: 'card card3'
});

demos.Animations.slidereveal = new Ext.Panel({
	html: 'Then there&#8217;s <code>reveal: true</code>, which is opposite to <code>cover</code>',
	cls: 'card card4'
});

demos.Animations.pop = new Ext.Panel({
	html: 'Pop is another 2d animation that should work across iPhone OS &amp; Android.',
	cls: 'card card5'
});

demos.Animations.flip = new Ext.Panel({
	html: 'This one&#8217;s 3d and can also accept <code>direction: "up/down/left/right"</code>',
	cls: 'card card1'
});

demos.Animations.cube = new Ext.Panel({
	html: 'Crazy enough, this one does every <code>direction</code>, too.',
	cls: 'card card2'
});

demos.Animations.fade = new Ext.Panel({
	html: 'This one&#8217;s pretty straight-forward.',
	cls: 'card card3'
});
*/