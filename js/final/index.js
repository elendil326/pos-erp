Ext.ns('sink', 'demos', 'Ext.ux');

Ext.ux.UniversalUI = Ext.extend(Ext.Panel, {
    fullscreen: true,
    layout: 'card',
    items: [{
        cls: 'launchscreen',
        html: '<div>Papas Supremas<br/><span>caffeina 2010</span><br></div><div class="helper1"></div>'
    }],

    initComponent : function() {
	
        this.backButton = new Ext.Button({
            hidden: true,
            text: 'Atras',
            ui: 'back',
            handler: this.onBackButtonTap,
            scope: this
        });

		//boton de navegacion
        this.navigationButton = new Ext.Button({
            hidden: Ext.platform.isPhone || Ext.orientation == 'landscape',
            text: 'Navegacion',
            handler: this.onNavButtonTap,
            scope: this
        });
        
		//barra de navegacion
        this.navigationBar = new Ext.Toolbar({
            ui: 'dark',
            dock: 'top',
            title: this.title,
            items: [this.backButton, this.navigationButton].concat(this.buttons || [])
        });
        
		

		//	Panel de navegacion izquierdo
        this.navigationPanel = new Ext.NestedList({
            items: this.navigationItems || [],
			cls: 'leftMenu',
            dock: 'left',
            width: 250,
            height: 456,
            hidden: !Ext.platform.isPhone && Ext.orientation == 'portrait',
            toolbar: Ext.platform.isPhone ? this.navigationBar : null,
            listeners: {
                listchange: this.onListChange,
                scope: this
            }
        });
        
        this.dockedItems = this.dockedItems || [];
        this.dockedItems.unshift(this.navigationBar);
        
        if (!Ext.platform.isPhone && Ext.orientation == 'landscape') {
            this.dockedItems.unshift(this.navigationPanel);
        }
        else if (Ext.platform.isPhone) {
            this.items = this.items || [];
            this.items.unshift(this.navigationPanel);
        }
        
        this.addEvents('navigate');
        
        Ext.ux.UniversalUI.superclass.initComponent.call(this);
    },
    
    onListChange : function(list, item) {
		//console.log("list changed");
        if (Ext.orientation == 'portrait' && !Ext.platform.isPhone && !item.items && !item.preventHide) {
            this.navigationPanel.hide();
        }
        
        if (item.card) {
            this.setCard(item.card, item.animation || 'slide');
            this.currentCard = item.card;
            if (item.text) {
                this.navigationBar.setTitle(item.text);
            }
            if (Ext.platform.isPhone) {
                this.backButton.show();
                this.navigationBar.doLayout();
            }
        }     
       
        this.fireEvent('navigate', this, item, list);
    },
    
    onNavButtonTap : function() {
        this.navigationPanel.showBy(this.navigationButton, 'fade');
    },
    
    onBackButtonTap : function() {
		
        this.setCard(this.navigationPanel, {type: 'slide', direction: 'right'});

        this.currentCard = this.navigationPanel;
        if (Ext.platform.isPhone) {
            this.backButton.hide();
            this.navigationBar.setTitle(this.title);
            this.navigationBar.doLayout();
        }
        this.fireEvent('navigate', this, this.navigationPanel.activeItem, this.navigationPanel);
    },
    
    onOrientationChange : function(orientation, w, h) {
        Ext.ux.UniversalUI.superclass.onOrientationChange.call(this, orientation, w, h);
        
        if (!Ext.platform.isPhone) {
            if (orientation == 'portrait') {
                this.removeDocked(this.navigationPanel, false);
                this.navigationPanel.hide();
                this.navigationPanel.setFloating(true);
                this.navigationButton.show();
            }
            else {                
                this.navigationPanel.setFloating(false);
                this.navigationPanel.show();
                this.navigationButton.hide();
                this.insertDocked(0, this.navigationPanel);                
            }

            this.doComponentLayout();
            this.navigationBar.doComponentLayout();
        }
    }
});



//imprimir informacion de debug
var DEBUG = true;




//	--------------------------------------------------------------------------------------
//		AppInstaller
//		
// la clase AppInstaller es un singleton, solo sirve para instalar aplicaciones, e iniciarlas
// recibe el objeto de la aplicacion que debe tener los miembros necesarios para instalarse
//	--------------------------------------------------------------------------------------
	
//comenzamos con el vector de aplicaiones vacio, de este
//vector se carga el menu izquierdo, de modo que para instalar
//apps hay que insertar aqui el config
var Apps = [];

AppInstaller = function ( app )
{
	
	
	if(DEBUG){
		console.log("AppInstaller: instalando " + app.appName + "...");
	}
	
	//probar que tenga los campos necesarios para poder instalar correctamente
	
	var make_app = {
		text: app.appName,
	    ayuda: app.ayuda,
	    card: app.mainCard,
		items : app.leftMenuItems
	};
	
	Apps.push( make_app );
	
	if(DEBUG){
		console.log("AppInstaller: " + app.appName + " instalado !");
	}
	
};





//	--------------------------------------------------------------------------------------
//		Utility functions
//	--------------------------------------------------------------------------------------

POS = {
	aviso : null,
	AJAXandDECODE: null
		
};


POS.AJAXandDECODE = function (params, success, failure)
{
	
	Ext.Ajax.request({
		
		url: 'proxy.php',
		
		success: function (response){

			var datos;
			try{				
				eval("datos = " + response.responseText);
			}catch(e){
				console.warn("Error", e);
			}
			 

			success( datos );
			
		},
		failure : failure,
		params: params
		
	});
	
	
};





/*
	Aviso
*/


POS.aviso = function (title, contents) 
{

	if(POS.aviso.box){
		if(DEBUG){
			console.log("Aviso exists... swapping visibility");
		}
		
		//cambiar el titulo
		POS.aviso.box.getDockedItems()[0].setTitle( title );
		
		
		//cambiar el contenido
		POS.aviso.box.body.dom.childNodes[0].innerHTML = "<p>" + contents + "</p>";
		
		
		POS.aviso.box.show('pop');
		
		return;
	}
	
	if(DEBUG){
		console.log("Aviso does not exist... creating one");
	}
	
	POS.aviso.box = new Ext.Panel({
            floating: true,
            modal: true,
            centered: true,
            width: 320,
            height: 300,
            styleHtmlContent: true,
            html: '<p>'+contents+'</p>',
            dockedItems: [{
                dock: 'top',
                xtype: 'toolbar',
                title: title
            }],
            scroll: 'vertical',
			listeners:
					{
						'show': function( )
						{
							POS.aviso.visible = true;
						},
						'hide' : function ()
						{
							POS.aviso.visible = false;
						}
					}
        });
    
    POS.aviso.box.show('pop');
};



POS.aviso.hide = function ()
{
	if(POS.aviso.visible){
		POS.aviso.box.hide();
	}
};

POS.aviso.box = null;

POS.aviso.visible = false;











POS.map = function( address )
{
	
	var mapPanel = new Ext.Panel({
		layout: 'fit',
		listeners: {
				afterrender: function(panel){
					
					if(!window.google){
						if(DEBUG){
							console.log("No google ");
						}
						return null;
					}
					
					var geocoder = new google.maps.Geocoder();

					var posMapOptions = {
						      zoom: 15,
						      mapTypeId: google.maps.MapTypeId.ROADMAP
					    };
						
					var map = new google.maps.Map(document.getElementById( Ext.get(panel.id).first().id ), posMapOptions);

					if (geocoder) {
					      geocoder.geocode( { 'address': address}, function(results, status) {
							if (status == google.maps.GeocoderStatus.OK) {
								
								map.setCenter(results[0].geometry.location);
								
								var marker = new google.maps.Marker({
										map: map, 
										position: results[0].geometry.location
						  			});
							} else {
							
								switch(status){
									case 'ERROR': 				errorMsg = 'Error al intentarse conectar con los servidores de Google'; break;
									case 'INVALID_REQUEST': 	errorMsg = 'Solicitud de datos errónea'; break;
									case 'OVER_QUERY_LIMIT': 	errorMsg = 'El servidor de mapas está teniendo sobrecarga. Intente más tarde'; break;
									case 'REQUEST_DENIED': 		errorMsg = 'La página en donde esta no tiene permiso para cargar el mapa'; break;
									case 'UNKNOWN_ERROR': 		errorMsg = 'Hubo un error en el servidor. Intente de nuevo'; break;
									case 'ZERO_RESULTS': 		errorMsg = 'No se encontraron resultados para la dirección ingresada'; break;
									default: 					errorMsg = 'Hubo un error desconocido en el servidor';
		
								}				
			
								if(DEBUG){
									console.warn("Mapas: ", status, errorMsg);
								}

								document.getElementById( Ext.get(panel.id).dom.childNodes[1].id).innerHTML = '<div style="text-align:center; padding-top:30%;">'+ errorMsg +'.</div>';
							
								return null;
							}
						});
				    }
				}
			}
	});
	
	return mapPanel;
};


//Imprime un div, el parametro es el id del div que quieres imprimir
POS.print = function (divID){

	var divToPrint = document.getElementById(divID);

	var popWin = window.open(' ', 'popwindow');
	popWin.document.write( divToPrint.innerHTML );
	popWin.document.close();
	popWin.print( );
        //popWin.close();
};

//Funcion para dar formato de un numero a moneda
POS.currencyFormat = function (num){

	num = num.toString().replace(/\$|\,/g,'');
	if(isNaN(num)){
		num = "0";
	}

	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);
	cents = num%100;
	num = Math.floor(num/100).toString();
	if(cents<10){
		cents = "0" + cents;
	}

	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
	{
	num = num.substring(0,num.length-(4*i+3))+','+num.substring(num.length-(4*i+3));
	}

	return (((sign)?'':'-') + '$' + num + '.' + cents);

};


POS.datePicker = function( pickerConfig ){

	var posPicker = pickerConfig;

	posPicker.xtype = "datepicker";
	
	var months = [
	
		{ key: "Enero"		, value: 0 },
		{ key: "Febrero"	, value: 1 },
		{ key: "Marzo"		, value: 2 },
		{ key: "Abril"		, value: 3 },
		{ key: "Mayo"		, value: 4 },
		{ key: "Junio"		, value: 5 },
		{ key: "Julio"		, value: 6 },
		{ key: "Agosto"		, value: 7 },
		{ key: "Septiembre"	, value: 8 },
		{ key: "Octubre"	, value: 9 },
		{ key: "Noviembre"	, value: 10 },
		{ key: "Diciembre"	, value: 11 }
	];
	
	var days = [];
	
	for(var i=1; i < 32; i++)
	{
		days.push({ key: i , value: i });
	}



	var years = [];

	for(var j=1987; j < 2101; j++)
	{
		years.push({ key: j , value: j });
	}


	/*var posSlot = [
		{
			name : 'month',
			align: 'left',
			items  : months, 
			title : 'Mes'
		},
		{
			
			name : 'day',
			align: 'center',			
			name : 'day',
			title : 'Día'			
		},
		{
			name : 'year',
			align: 'right',
			items  : years,
			title : 'Año'			
		}
	];*/


	var posSlot = [{
            text: 'Month',
            name: 'month',
            align: 'left',
            items: months
        },{
            text: 'Day',
            name: 'day',
            align: 'center',
            items: days
        },{
            text: 'Year',
            name: 'year',
            align: 'right',
            items: years
        }];

	posPicker.slots = posSlot;

	//POS.pickerSlots = posSlot;
	//console.log(posPicker);

	//return posPicker;	

	extPicker = new Ext.DatePicker();
	extPicker.slots[0].items[0].key = 'Enero';

	POS.pickerSlots = extPicker.slots;
};


//POS.pickerSlots = null;



POS.doPrintTicket = function ()
{
	
	
	
};


//	--------------------------------------------------------------------------------------
//		Application Main Entry Point
//	--------------------------------------------------------------------------------------

sink.Main = {
    init : function() {
	
	//En el mero inicio del sistema, se hace un AJAX para comprobar que la variable de session este fija
	//Esto por dos caso: que si se efectuo un login correcto, o que el usuario esta regresando a una sesion valida anterior
	/*
	POS.AJAXandDECODE(
		//Parametros
		{method: 'estaLoggeado'},
		function(result){
		
			if(!result.success) { window.location = "index.html";}
		
		},
		function(result){
			
			//No se pudo comprobar que se esta loggueado asi que se redirecciona al index
			window.location = "index.html";
		}
	);*/
		
		//boton de cancelar
        this.ayudaButton = new Ext.Button({
            text: 'Ayuda',
            ui: 'action',
            hidden: true,
            handler: this.onAyudaButtonTap,
            scope: this
        });
        
        this.codeBox = new Ext.ux.CodeBox({scroll: false});
        
        var ayudaConfig = {
            items: [this.codeBox],
            scroll: 'vertical'
        };


		//que no sea iphone
        if (!Ext.platform.isPhone) {

            Ext.apply(ayudaConfig, {
                width: 500,
                height: 500,
                floating: true
            });
        }
                
        this.ayudaPanel = new Ext.Panel(ayudaConfig);
        


		//crear el UI
        this.ui = new Ext.ux.UniversalUI({
            title: Ext.platform.isPhone ? 'POS iPhone' : 'Punto de Venta',
            navigationItems: Apps,
            buttons: [{xtype: 'spacer'}, this.ayudaButton],
            listeners: {
                navigate : this.onNavigate,
                scope: this
            }
        });
    },
    

	//al navegar en cualquier opcion
    onNavigate : function(ui, item) {
        if (item.ayuda) {


            if (this.ayudaButton.hidden) {
                this.ayudaButton.show();
                ui.navigationBar.doComponentLayout();
            }
            
            this.codeBox.setValue(Ext.htmlEncode( item.ayuda ));
                
        }else{
			//this item does not provide any help
            this.codeBox.setValue('No ayuda for this example.');
            this.ayudaButton.hide();
            this.ayudaActive = false;
            this.ayudaButton.setText('Ayuda');
            ui.navigationBar.doComponentLayout();

        }

    },
    




    onAyudaButtonTap : function() {
		
		//console.log("tapped help");
		
        if (!Ext.platform.isPhone) {
			
            this.ayudaPanel.showBy( this.ayudaButton.el, 'fade');

        }else {
            if (this.ayudaActive) {
                this.ui.setCard(this.ui.currentCard, Ext.platform.isAndroidOS ? false : 'flip');
                this.ayudaActive = false;
                this.ui.navigationBar.setTitle(this.currentTitle);
                this.ayudaButton.setText('Ayuda');
            }else {
                this.ui.setCard(this.ayudaPanel, Ext.platform.isAndroidOS ? false : 'flip');
                this.ayudaActive = true;
                this.currentTitle = this.ui.navigationBar.title;
                this.ui.navigationBar.setTitle('Source');
                this.ayudaButton.setText('Regresar');
            }
            this.ui.navigationBar.doLayout();
        }//end else

		
        this.ayudaPanel.scroller.scrollTo({x: 0, y: 0});
    }
};





Ext.setup({
    tabletStartupScreen: 'resources/img/tablet_startup.png',
    phoneStartupScreen: 'resources/img/phone_startup.png',
    icon: 'resources/img/icon.png',
    glossOnIcon: false,
    
    onReady: function() {
		if(DEBUG){
			console.log("DOM listo, iniciando aplicacion...");			
		}

	
        sink.Main.init();
	
    }
});
