

Ext.ns('sink', 'demos', 'Ext.ux');


var POS_SUCURSAL_NOMBRE;
var POS_CAJERO_NOMBRE;
var POS_CAJERO_TIPO;
var POS_IS_GERENTE;
var MOSTRADOR_IVA;

Ext.regModel('AppModel', {
    fields: [
        {name: 'text',        type: 'string'},
        {name: 'ayuda',      type: 'string'},
        //{name: 'preventHide', type: 'boolean'},
        //{name: 'animation'},
        {name: 'card'}
    ]
});


Ext.ux.UniversalUI = Ext.extend(Ext.Panel, {
    fullscreen: true,
    layout: 'card',
    items: [{
        cls: 'launchscreen',
        html: '<div>Papas Supremas<br/><span>caffeina 2010</span><br></div><div class="helper1"></div>'
    }],
    backText: 'Back',
    useTitleAsBackText: true,
    initComponent : function() {	
		//boton de navegacion
        this.navigationButton = new Ext.Button({
			hidden: Ext.is.Phone || Ext.orientation == 'landscape',
            text: 'Navegacion',
            handler: this.onNavButtonTap,
            scope: this
        });
        

        this.backButton = new Ext.Button({
            text: this.backText,	
            hidden: true,
            text: 'Atras',
            ui: 'back',
            handler: this.onUiBack,
            scope: this
        });

        var btns = [this.navigationButton];
        if (Ext.is.Phone) {
            btns.unshift(this.backButton);
        }

        this.navigationBar = new Ext.Toolbar({
            ui: 'dark',
            dock: 'top',
            title: this.title,
            //items: [this.backButton, this.navigationButton].concat(this.buttons || [])
			items: btns.concat(this.buttons || [])
        });
        
		


		//sencha 0.97
		Estructura = new Ext.data.TreeStore({
		    model: 'AppModel',
		    root: {
		        items: Apps
		    },
		    proxy: {
		        type: 'ajax',
		        reader: {
		            type: 'tree',
		            root: 'items'
		        }
		    }
		});

		//	Panel de navegacion izquierdo
        this.navigationPanel = new Ext.NestedList({
			store: Estructura,
			//cls: 'leftMenu',
          	useToolbar: Ext.is.Phone ? false : true,
            updateTitleText: false,
            dock: 'left',
            hidden: !Ext.is.Phone && Ext.orientation == 'portrait',
            toolbar: Ext.is.Phone ? this.navigationBar : null,
            listeners: {
                itemtap: this.onNavPanelItemTap,
                scope: this
            }
        });
        
		this.navigationPanel.on('back', this.onNavBack, this);

        if (!Ext.is.Phone) {
            this.navigationPanel.setWidth(250);
        }

        this.dockedItems = this.dockedItems || [];
        this.dockedItems.unshift(this.navigationBar);

        if (!Ext.is.Phone && Ext.orientation == 'landscape') {
            this.dockedItems.unshift(this.navigationPanel);
        }
        else if (Ext.is.Phone) {
            this.items = this.items || [];
            this.items.unshift(this.navigationPanel);
        }

        this.addEvents('navigate');

        Ext.ux.UniversalUI.superclass.initComponent.call(this);
    },
	toggleUiBackButton: function() {
        var navPnl = this.navigationPanel;

        if (Ext.is.Phone) {
            if (this.getActiveItem() === navPnl) {

                var currList      = navPnl.getActiveItem(),
                    currIdx       = navPnl.items.indexOf(currList),
                    recordNode    = currList.recordNode,
                    title         = navPnl.renderTitleText(recordNode),
                    parentNode    = recordNode ? recordNode.parentNode : null,
                    backTxt       = (parentNode && !parentNode.isRoot) ? navPnl.renderTitleText(parentNode) : this.title || '';


                if (currIdx <= 0) {
                    this.navigationBar.setTitle(this.title || '');
                    this.backButton.hide();
                } else {
                    this.navigationBar.setTitle(title);
                    if (this.useTitleAsBackText) {
                        this.backButton.setText(backTxt);
                    }

                    this.backButton.show();
                }
            // on a demo
            } else {
                var activeItem = navPnl.getActiveItem(),
                    recordNode = activeItem.recordNode,
                    backTxt    = (recordNode && !recordNode.isRoot) ? navPnl.renderTitleText(recordNode) : this.title || '';

                if (this.useTitleAsBackText) {
                    this.backButton.setText(backTxt);
                }
                this.backButton.show();
            }
            this.navigationBar.doLayout();
        }

    },

    onUiBack: function() {
        var navPnl = this.navigationPanel;

        // if we already in the nested list
        if (this.getActiveItem() === navPnl) {
            navPnl.onBackTap();
        // we were on a demo, slide back into
        // navigation
        } else {
            this.setCard(navPnl, {
                type: 'slide',
                reverse: true
            });
        }
        this.toggleUiBackButton();
        this.fireEvent('navigate', this, {});
    },

    onNavPanelItemTap: function(subList, subIdx, el, e) {


        var store      = subList.getStore(),
            record     = store.getAt(subIdx),
            recordNode = record.node,
            nestedList = this.navigationPanel,
            title      = nestedList.renderTitleText(recordNode),
            card, preventHide, anim;

        if (record) {
            card        = record.get('card');
            anim        = record.get('animation');
            preventHide = record.get('preventHide');
        }

        if (Ext.orientation == 'portrait' && !Ext.is.Phone && !recordNode.childNodes.length && !preventHide) {
            this.navigationPanel.hide();
        }

        if (card) {
            this.setCard(card, anim || 'slide');
            this.currentCard = card;
        }

        if (title) {
            this.navigationBar.setTitle(title);
        }
        this.toggleUiBackButton();
        this.fireEvent('navigate', this, record);
    },

    onNavButtonTap : function() {
        this.navigationPanel.showBy(this.navigationButton, 'fade');
    },

    layoutOrientation : function(orientation, w, h) {
        if (!Ext.is.Phone) {
            if (orientation == 'portrait') {
                this.navigationPanel.hide(false);
                this.removeDocked(this.navigationPanel, false);
                if (this.navigationPanel.rendered) {
                    this.navigationPanel.el.appendTo(document.body);
                }
                this.navigationPanel.setFloating(true);
                this.navigationPanel.setHeight(400);
                this.navigationButton.show(false);
            }
            else {
                this.navigationPanel.setFloating(false);
                this.navigationPanel.show(false);
                this.navigationButton.hide(false);
                this.insertDocked(0, this.navigationPanel);
            }
            this.navigationBar.doComponentLayout();
        }

        Ext.ux.UniversalUI.superclass.layoutOrientation.call(this, orientation, w, h);
    }
	/*

	onNavPanelItemTap: function(subList, subIdx, el, e) {
        var store      = subList.getStore(),
            record     = store.getAt(subIdx),
            recordNode = record.node,
            nestedList = this.navigationPanel,
            title      = nestedList.renderTitleText(recordNode),
            card, preventHide, anim;

        if (record) {
            card        = record.get('card');
            anim        = record.get('animation');
            preventHide = record.get('preventHide');
        }

        if (Ext.orientation == 'portrait' && !Ext.is.Phone && !recordNode.childNodes.length && !preventHide) {
            //this.navigationPanel.hide();
        }

        if (card) {
            this.setCard(card, anim || 'slide');
            this.currentCard = card;
        }

        if (title) {
            //this.navigationBar.setTitle(title);
        }
        //this.toggleUiBackButton();
        this.fireEvent('navigate', this, record);
    },
    
    onListChange : function(list, item) {

		return;
        if (Ext.orientation == 'portrait' && !Ext.is.Phone && !item.items && !item.preventHide) {
            this.navigationPanel.hide();
        }
        
        if (item.card) {
            this.setCard(item.card, item.animation || 'slide');
            this.currentCard = item.card;
            if (item.text) {
                //this.navigationBar.setTitle(item.text);
            }
            if (Ext.is.Phone) {
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
        if (Ext.is.Phone) {
            this.backButton.hide();
            //this.navigationBar.setTitle(this.title);
            this.navigationBar.doLayout();
        }
        this.fireEvent('navigate', this, this.navigationPanel.activeItem, this.navigationPanel);
    },
    
    onOrientationChange : function(orientation, w, h) {
        Ext.ux.UniversalUI.superclass.onOrientationChange.call(this, orientation, w, h);
        
        if (!Ext.is.Phone) {
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
    }*/
});






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
		items : app.leftMenuItems,
	    leaf: true
	};
	
	Apps.push( make_app );
	
	if(DEBUG){
		console.log("AppInstaller: " + app.appName + " instalado !");
	}
	
};






//	--------------------------------------------------------------------------------------
//		Application Main Entry Point
//	--------------------------------------------------------------------------------------

sink.Main = {
    init : function() {
	
		if(DEBUG){
			console.log("sink.Main init started !");			
		}
	
		//boton de ayuda
        this.ayudaButton = new Ext.Button({
            text: 'Ayuda',
            ui: 'normal',
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
        if (!Ext.is.Phone) {
            Ext.apply(ayudaConfig, {
                width: 500,
                height: 500,
                floating: true
            });
        }

        this.ayudaPanel = new Ext.Panel(ayudaConfig);
        



		if(DEBUG){
			console.log("Create User Interface");			
		}


		//crear el UI
        this.ui = new Ext.ux.UniversalUI({
            title: Ext.is.Phone ? 'POS iPhone' : '<b>Sucursal</b> ' + POS_SUCURSAL_NOMBRE + '   <b>Usuario</b> ' + POS_CAJERO_NOMBRE,
            navigationItems: Apps,
            buttons: [{xtype: 'spacer'}, this.ayudaButton ],
            listeners: {
                navigate : this.onNavigate,
                scope: this
            }
        });
 

    },
    

	//al navegar en cualquier opcion
    onNavigate : function(ui, record) {
		if (record.data && record.data.source) {
            var source = record.get('source');
            if (this.sourceButton.hidden) {
                this.sourceButton.show();
                ui.navigationBar.doComponentLayout();
            }

            /*Ext.Ajax.request({
                url: source,
                success: function(response) {
                    this.codeBox.setValue(Ext.htmlEncode(response.responseText));
                },
                scope: this
            });*/
        }
        else {

            //this.codeBox.setValue('No source for this example.');
            //this.sourceButton.hide();
            //this.sourceActive = false;
            //this.sourceButton.setText('Source');
            ui.navigationBar.doComponentLayout();
        }
		/*
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

        }*/

    },
    




    onAyudaButtonTap : function() {
		
		//console.log("tapped help");
		
        if (!Ext.is.Phone) {
			
            this.ayudaPanel.showBy( this.ayudaButton.el, 'fade');

        }else {
            if (this.ayudaActive) {
                this.ui.setCard(this.ui.currentCard, Ext.platform.isAndroidOS ? false : 'flip');
                this.ayudaActive = false;
                //this.ui.navigationBar.setTitle(this.currentTitle);
                this.ayudaButton.setText('Ayuda');
            }else {
                this.ui.setCard(this.ayudaPanel, Ext.platform.isAndroidOS ? false : 'flip');
                this.ayudaActive = true;
                this.currentTitle = this.ui.navigationBar.title;
                //this.ui.navigationBar.setTitle('Source');
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


		
		POS.AJAXandDECODE({ 
				
			action : "2003" 
			},
		
			function (response){
				if(response.sucess){
					if(DEBUG){
						console.log("Loading config...  ", response);
					}
					MOSTRADOR_IVA = response.payload.iva ;
					POS_SUCURSAL_NOMBRE = response.payload.sucursal;
					POS_CAJERO_NOMBRE = response.payload.cajero_nombre;
					POS_CAJERO_TIPO = response.payload.tipo;

					if(POS_CAJERO_TIPO == "Gerente"){
						AppInstaller( new ApplicationUsuarios() );
						AppInstaller( new ApplicationGastos() );
					}
					if(DEBUG){
						console.log("Done loading config !");			
					}
				}

        		sink.Main.init();
			}, 
			function (response){
				alert(response);
		});
		
    }
});
