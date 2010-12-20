
var DEBUG; 
var login;
POS = {U:{G:null}};

if(document.location.search=="?debug")
{
    DEBUG=true;

	console.log("Applicacion en modo de DEBUG !");
	
}else{
	DEBUG = false;
}


function checkCurrentSession(){
	
	Ext.Ajax.request({
		url: 'proxy.php',
		params : {
			action : 2001
		},
		
		success: function(response, opts) {
			Ext.getBody().unmask();	
			
			var results = Ext.util.JSON.decode( response.responseText );
			
			//algo anda mal
			if(!results.success){

  				if(DEBUG){
  					console.error("Basic testing has returned something other than success.");
  				}

				Ext.getBody().mask( results.response );
  				return;
			}
			
			
			if( !results.sesion ){
				//sesion no iniciada o no valida, mostrar la ventana de login
				createLoginForm( results.sucursal ); 
				return;
			}
			
			//hay una sesion activa y valida
			if(DEBUG){
				window.location = "proxy.php?DEBUG=true&action=2005";
				return;
			}
			window.location = "proxy.php?action=2005";
			
		},

		failure: function(response, opts) {
			Ext.getBody().mask( "No se puede alcanzar el servidor. Verifique que tenga conectividad a internet." );
			if(DEBUG){
				console.log('server-side failure with status code ' + response.status);				
			}

		}
	});
}



function parseLoginResults( args ){
	
	
	if(!args.success){
		//login invalido
		

        form.getComponent(0).setInstructions(args.text);
        form.show();
    

		return;
	}
	
	if(DEBUG){
		window.location = "proxy.php?DEBUG=true&action=2005";
		return;
	}
	window.location = "proxy.php?action=2005";

}


var sendLogin = function (){
	form.hide();
	
	Ext.getBody().mask('Revisando...', 'x-mask-loading', true);
	
 	Ext.Ajax.request({
		url: 'proxy.php',
		params : {
			action: 2004,
			u : Ext.getCmp("uid").getValue(),
			p : hex_md5( Ext.getCmp("pswd").getValue() )
		},
		success: function(response, opts) {

			Ext.getBody().unmask();
			parseLoginResults( Ext.util.JSON.decode( response.responseText ) );
			
		}
	});
	
}








Ext.setup({
    icon: 'icon.png',
    tabletStartupScreen: 'tablet_startup.png',
    phoneStartupScreen: 'phone_startup.png',
    glossOnIcon: false,
    onReady: function() {
        
        
        
        Ext.getBody().mask('Revisando...', 'x-mask-loading', false);


		checkCurrentSession();
    }
});








function createLoginForm( sucursal ){

	var formBase = {
        scroll: null,
        standardSubmit : false,
        items: [
            {
                xtype: 'fieldset',
                title: 'Bienvenido a ' + sucursal.descripcion ,
                instructions: 'Porfavor llene la informacion apropiada.',
                defaults: {
                    required: false,
                    labelAlign: 'left'
                },
                items: [
                {
                    xtype: 'textfield',
                    id : 'uid',
                    label: 'Identifiacion',
                    useClearIcon: true,
                    autoCapitalize : false,
					listeners : {
						"focus" : function(){
							kconf = {
								type : 'num',
								submitText : 'Aceptar',
								callback : null
							};
							POS.Keyboard.Keyboard( this, kconf );
						}
					}
                }, {
                    xtype: 'passwordfield',
                    id : 'pswd',
                    label: 'Contrase&ntilde;a',
                    useClearIcon: false,
					listeners : {
						"focus" : function(){
							kconf = {
								type : 'alfanum',
								submitText : 'Aceptar',
								callback : null
							};
							POS.Keyboard.Keyboard( this, kconf );
						}
					}	
                }]
            }
        ],
        listeners : {
            submit : function(form, result){
                //console.log('success', Ext.toArray(arguments));
				return;
            },
            exception : function(form, result){
                //console.log('failure', Ext.toArray(arguments));
            }
        },
    
        dockedItems: [
            {
                xtype: 'toolbar',
                dock: 'bottom',
                items: [
                    {xtype: 'spacer'},
                    {
                        text: 'Vaciar',
                        handler: function() {
                            form.getComponent(0).setInstructions( "Porfavor llene la informacion apropiada." );
                            form.reset();
                        }
                    },
                    {
                        text: 'Entrar',
                        ui: 'confirm',
                        handler: sendLogin
                    }
                ]
            }
        ]
    };
    
    if (Ext.is.AndroidOS) {
        formBase.items.unshift({
            xtype: 'component',
            styleHtmlContent: true,
            html: '<span style="color: red">Forms on Android are currently under development. We are working hard to improve this in upcoming releases.</span>'
        });
    }
    
    if (Ext.is.Phone) {
        formBase.fullscreen = true;
    } else {
        Ext.apply(formBase, {
            autoRender: true,
            floating: true,
            modal: true,
            centered: true,
            hideOnMaskTap: false,
            height: 285,
            width: 480
        });
    }
    
    form = new Ext.form.FormPanel(formBase);
    form.show();
}





