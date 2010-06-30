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
		
		url: 'serverProxy.php',
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

POS.aviso = function (title, contents) 
{
	this.popup = new Ext.Panel({
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
            scroll: 'vertical'
        });
    
    this.popup.show('pop');
};




//	--------------------------------------------------------------------------------------
//		Application Main Entry Point
//	--------------------------------------------------------------------------------------

sink.Main = {
    init : function() {
		
		
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