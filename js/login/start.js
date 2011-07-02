var DEBUG; 
var login;
POS = {U:{G:null}};

if(document.location.search=="?debug")
{
    DEBUG = true;
	console.log("Applicacion en modo de DEBUG !");
	
}else{
	
	DEBUG = false;
}



function checkForClient()
{
 	Ext.getBody().mask('Iniciando...', 'x-mask-loading', false);

	POS.ajaxToClient({
		module : "handshake",
		args : "",
		success : function (){
			//ok client is there...
			checkCurrentSession();
		},
		failure: function (){
			//client not found !
			displayNoClient();
		},
	});
	
}

function displayNoClient()
{
	var html = "<div onClick='checkForClient()'>No se ha encontrado el cliente de caffeina."
		+ "Toque aqui para reintentar."
		+ "<div>";
		
	Ext.getBody().mask( html, 'x-mask-loading', true );
}


function checkCurrentSession(){
	Ext.getBody().mask('Validando sucursal...', 'x-mask-loading', false);
	Ext.Ajax.request({
		url: '../proxy.php',
		params : {
			action : 2001
		},
		
		success: function(response, opts) {

            if(response.responseText.length == 0){
    			Ext.getBody().mask( "Error." );
                return;
            }

			Ext.getBody().unmask();	
			
          	try{
				var results = Ext.util.JSON.decode( response.responseText );
			}catch(e){
				Ext.getBody().mask( "Error<br>Mostrando error orginal:<br>" + response.responseText );
  				return;				
			}
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
			

			window.location = "../proxy.php?action=2005";

                       
			
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
		window.location = "../proxy.php?DEBUG=true&action=2005";
		return;
	}
	window.location = "../proxy.php?action=2005";

}


var sendLogin = function (){
	form.hide();
	
	Ext.getBody().mask('Revisando...', 'x-mask-loading', true);
	
 	Ext.Ajax.request({
		url: '../proxy.php',
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
    glossOnIcon: false,
    onReady: function() {

       

		checkForClient();
		//checkCurrentSession();
    }
});








function createLoginForm( sucursal ){

	var formBase = {
        scroll: null,
        standardSubmit : false,
        submitOnAction : false,
        items: [
            {
                xtype: 'fieldset',
                title: 'Bienvenido a ' + sucursal ,
                instructions: 'Porfavor llene la informacion apropiada.',
                defaults: {
                    required: false,
                    labelAlign: 'left'
                },
                items: [
                {
                    xtype: 'textfield',
                    id : 'uid',
                    label: 'Identifiaci&oacute;n',
                    useClearIcon: true,
                    autoCapitalize : false,
					listeners : {
						"focus" : function(){
							kconf = {
								type : 'num',
								submitText : 'Aceptar',
								callback : function(){
									//setTimeout('Ext.getCmp("pswd").focus();', 250);
								}
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
								type : 'complete',
								submitText : 'Ingresar',
								callback : function(a,b){
									sendLogin();
								}
							};
							POS.Keyboard.Keyboard( this, kconf );
						}
					}	
                }]
            }
        ],
        listeners : {
            submit : function(form, result){
                if(DEBUG){console.log('success', Ext.toArray(result, form));}
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




