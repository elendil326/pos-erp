//for IE
var console = console || { log: function(){}, group : function(){}, groupEnd : function(){} };

//for IE even when cnsole is defined:
console.group =  console.group || function(){};
console.groupEnd =  console.groupEnd || function(){};

Ext.Loader.setConfig({
    enabled: true
});

Ext.Loader.setPath('Ext.ux', 'http://api.caffeina.mx/ext-4.0.0/examples/ux/');



Ext.require([
    'Ext.data.*',
    'Ext.form.*',
    'Ext.grid.*',
    'Ext.util.*',
    'Ext.state.*',
	'Ext.ux.grid.TransformGrid',
    'Ext.window.MessageBox',
	'Ext.tab.*',
	  'Ext.panel.Panel',
	  'Ext.button.Button',
	  'Ext.window.Window',
	 // 'Ext.ux.statusbar.StatusBar',
	  'Ext.toolbar.TextItem',
	  'Ext.menu.Menu',
	  'Ext.toolbar.Spacer',
	  'Ext.button.Split',
	  'Ext.form.field.TextArea'
]);




var main = function ()
{
	
	if(!Ext.isIE) console.log("JS FRWK READY");

	//window.onbeforeunload = function(){}

	var els = Ext.select("input").elements;

	if(!Ext.isIE) console.log("Ataching on before unload events...");
	
	for (var i = els.length - 1; i >= 0; i--){
		Ext.get(els[i]).on(
			"keydown",
			function(){
					window.onbeforeunload = function(){ 
						return 'Usted ha modificado el formulario. Si sale de esta pagina perdera los cambios. Esta seguro que desea salir?';
					}
			});
	};
	
	if( window.store_component !== undefined ){
		store_component.render();	
	}

	if(document.location.search.indexOf("previous_action=ok") > -1)
		Ext.example.msg('Exito', '!!!');


	if((window.TableComponent !== undefined) && (TableComponent.convertToExtJs !== undefined)){
		for (var i = TableComponent.convertToExtJs.length - 1; i >= 0; i--) {

			alanboy = Ext.create('Ext.ux.grid.TransformGrid', TableComponent.convertToExtJs[i], {
	            //stripeRows: true,
	            bodyCls: 'overrideTHTD'
	           
	        }).render();	
		};
		
	}




	if(window.TabPage !== undefined){

		console.log(TabPage.tabs);

		if(window.location.hash.length == 0){
			//no hay tab seleccionado
			//Ext.get('tab_'+TabPage.tabs[0]).setStyle('display', 'block');
			Ext.get('atab_'+TabPage.tabs[0]).toggleCls('selected');
			TabPage.currentTab = TabPage.tabs[0];

		}else{
			//si hay
			TabPage.currentTab = window.location.hash.substr(1);

			//Ext.get('tab_'+TabPage.currentTab).setStyle('display', 'block');

			Ext.get('atab_'+TabPage.currentTab).toggleCls('selected');
			
		}


		//hide the other ones
		for (var t = TabPage.tabs.length - 1; t >= 0; t--) {

			Ext.get('tab_'+TabPage.tabs[t]).setVisibilityMode(Ext.Element.VISIBILITY);

			TabPage.tabsH[ TabPage.tabs.length - t - 1 ] = Ext.get('tab_'+TabPage.tabs[t]).getHeight();


			//Ext.get('tab_'+TabPage.tabs[t]).setVisibilityMode(Ext.Element.DISPLAY);

			if(TabPage.currentTab == TabPage.tabs[t]) { console.log("not hidding " + TabPage.tabs[t]); continue; }
			
			console.log("hidding:" + TabPage.tabs[t]);

			
			Ext.get('tab_'+TabPage.tabs[t]).setHeight(0);//setStyle('display', 'none');
			Ext.get('tab_'+TabPage.tabs[t]).hide();//setStyle('display', 'none');
			
		};

		console.log(TabPage.tabsH);


		if ( 'onhashchange' in window ) {
			//console.log('`onhashchange` available....');

			window.onhashchange = function() {

				if((TabPage.currentTab.length > 0) && (Ext.get('tab_'+TabPage.currentTab) != null)){
					//ocultar la que ya esta
					//Ext.get('tab_'+currentTab).setStyle('display', 'none');
					console.log("ocualtando " +TabPage.currentTab );
					
					//antes de ocultarlo guardar su tamano
					for (var ti = 0; ti < TabPage.tabs.length; ti++) {
						if( TabPage.tabs[ti] == TabPage.currentTab){
							TabPage.tabsH[ti] = Ext.get('tab_'+TabPage.currentTab).getHeight(  );
						}
					};

					Ext.get('tab_'+TabPage.currentTab).hide();
					Ext.get('tab_'+TabPage.currentTab).setHeight(0);
					Ext.get('atab_'+TabPage.currentTab).toggleCls('selected');


				}

				//currentTab = window.location.hash.substr(1);
				TabPage.currentTab = window.location.hash.substr(1);

				Ext.get('tab_'+TabPage.currentTab).show();
				for (var ti = 0; ti < TabPage.tabs.length; ti++) {
					if( TabPage.tabs[ti] == TabPage.currentTab){
						Ext.get('tab_'+TabPage.currentTab).setHeight( TabPage.tabsH[ti] );
					}
				};
				Ext.get('atab_'+TabPage.currentTab).toggleCls('selected');
			}
		}

	}//if(window.TabPage !== undefined)
	

}

var alanboy;
Ext.onReady(main);


var POS = {};


Ext.Ajax.on('beforerequest', 	function (){ Ext.get("ajax_loader").show(); }, this);
Ext.Ajax.on('requestcomplete', 	function (){ Ext.get("ajax_loader").hide(); }, this);
Ext.Ajax.on('requestexception', function (){ Ext.get("ajax_loader").hide(); }, this);



POS.API = 
{
	ajaxCallBack 	: function (callback, a, b, c)
	{
		var o;
		try{
			o = Ext.JSON.decode( a.responseText );

		}catch(e){
			console.error("JSON NOT DECODABLE:" , a.responseText);
			
			Ext.MessageBox.show({
			           title: 'Error',
			           msg: "Ocurrio un problema con la peticion porfavor intente de nuevo.",
			           buttons: Ext.MessageBox.OK,
			           icon: "error"
			       });
			return;

		}
		callback.call( null, o );
	},

	ajaxFailure 	: function ( callback, a,b,c )
	{
		

		var o;
		try{
			o = Ext.JSON.decode( a.responseText );
			
			console.error( "API SAYS :  " + o.error )
			
			Ext.MessageBox.show({
			           title: 'Error',
			           msg: o.error,
			           buttons: Ext.MessageBox.OK,
			           icon: "error"
			       });
			
		}catch(e){
			console.error("JSON NOT DECODABLE:" , a.responseText);
			Ext.MessageBox.show({
			           title: 'Error',
			           msg: "Ocurrio un problema con la solicitud, porfavor intente de nuevo en un momento.",
			           buttons: Ext.MessageBox.OK,
			           icon: "error"
			       });
			return;

		}
		
		//callback.call( null, o );
	},

	actualAjax 		: function (  method, url, params, callback  )
	{
		params.auth_token = Ext.util.Cookies.get("at");
		
		Ext.Ajax.request({
			method 	: method,
			url 	: "../" + url,
			success : function(a,b,c){ POS.API.ajaxCallBack( callback, a, b, c ); },
			failure : function(a,b,c){ POS.API.ajaxFailure( callback, a, b, c ); },
			params  : params
		});
	},

	GET 			: function( url, params, o)
	{
		POS.API.actualAjax("GET", url, params, o.callback);
	},

	POST 			: function( url, params, o)
	{
		POS.API.actualAjax("POST", url, params, o.callback);
	}

}




Ext.define('MGW10005', {
    extend: 'Ext.data.Model',
    fields: [
		{name: 'CIDPRODU01', type: 'string' },
		{name: 'CCODIGOP01', type: 'string' },
		{name: 'CNOMBREP01', type: 'string' },
		{name: 'CTIPOPRO01', type: 'string' },
		{name: 'CFECHAAL01', type: 'string' },
		{name: 'CCONTROL01', type: 'string' },
		{name: 'CIDFOTOP01', type: 'string' },
		{name: 'CDESCRIP01', type: 'string' },
		{name: 'CMETODOC01', type: 'string' },
		{name: 'CPESOPRO01', type: 'string' },
		{name: 'CCOMVENT01', type: 'string' },
		{name: 'CCOMCOBR01', type: 'string' },
		{name: 'CCOSTOES01', type: 'string' },
		{name: 'CMARGENU01', type: 'string' },
		{name: 'CSTATUSP01', type: 'string' },
		{name: 'CIDUNIDA01', type: 'string' },
		{name: 'CIDUNIDA02', type: 'string' },
		{name: 'CFECHABAJA', type: 'string' },
		{name: 'CIMPUESTO1', type: 'string' },
		{name: 'CIMPUESTO2', type: 'string' },
		{name: 'CIMPUESTO3', type: 'string' },
		{name: 'CRETENCI01', type: 'string' },
		{name: 'CRETENCI02', type: 'string' },
		{name: 'CIDPADRE01', type: 'string' },
		{name: 'CIDPADRE02', type: 'string' },
		{name: 'CIDPADRE03', type: 'string' },
		{name: 'CIDVALOR01', type: 'string' },
		{name: 'CIDVALOR02', type: 'string' },
		{name: 'CIDVALOR03', type: 'string' },
		{name: 'CIDVALOR04', type: 'string' },
		{name: 'CIDVALOR05', type: 'string' },
		{name: 'CIDVALOR06', type: 'string' },
		{name: 'CSEGCONT01', type: 'string' },
		{name: 'CSEGCONT02', type: 'string' },
		{name: 'CSEGCONT03', type: 'string' },
		{name: 'CTEXTOEX01', type: 'string' },
		{name: 'CTEXTOEX02', type: 'string' },
		{name: 'CTEXTOEX03', type: 'string' },
		{name: 'CFECHAEX01', type: 'string' },
		{name: 'CIMPORTE01', type: 'string' },
		{name: 'CIMPORTE02', type: 'string' },
		{name: 'CIMPORTE03', type: 'string' },
		{name: 'CIMPORTE04', type: 'string' },
		{name: 'CPRECIO1', type: 'string' },
		{name: 'CPRECIO2', type: 'string' },
		{name: 'CPRECIO3', type: 'string' },
		{name: 'CPRECIO4', type: 'string' },
		{name: 'CPRECIO5', type: 'string' },
		{name: 'CPRECIO6', type: 'string' },
		{name: 'CPRECIO7', type: 'string' },
		{name: 'CPRECIO8', type: 'string' },
		{name: 'CPRECIO9', type: 'string' },
		{name: 'CPRECIO10', type: 'string' },
		{name: 'CBANUNID01', type: 'string' },
		{name: 'CBANCARA01', type: 'string' },
		{name: 'CBANMETO01', type: 'string' },
		{name: 'CBANMAXMIN', type: 'string' },
		{name: 'CBANPRECIO', type: 'string' },
		{name: 'CBANIMPU01', type: 'string' },
		{name: 'CBANCODI01', type: 'string' },
		{name: 'CBANCOMP01', type: 'string' },
		{name: 'CTIMESTAMP', type: 'string' },
		{name: 'CERRORCO01', type: 'string' },
		{name: 'CFECHAER01', type: 'string' },
		{name: 'CPRECIOC01', type: 'string' },
		{name: 'CESTADOP01', type: 'string' },
		{name: 'CBANUBIC01', type: 'string' },
		{name: 'CESEXENTO', type: 'string' },
		{name: 'CEXISTEN01', type: 'string' },
		{name: 'CCOSTOEXT1', type: 'string' },
		{name: 'CCOSTOEXT2', type: 'string' },
		{name: 'CCOSTOEXT3', type: 'string' },
		{name: 'CCOSTOEXT4', type: 'string' },
		{name: 'CCOSTOEXT5', type: 'string' },
		{name: 'CFECCOSEX1', type: 'string' },
		{name: 'CFECCOSEX2', type: 'string' },
		{name: 'CFECCOSEX3', type: 'string' },
		{name: 'CFECCOSEX4', type: 'string' },
		{name: 'CFECCOSEX5', type: 'string' },
		{name: 'CMONCOSEX1', type: 'string' },
		{name: 'CMONCOSEX2', type: 'string' },
		{name: 'CMONCOSEX3', type: 'string' },
		{name: 'CMONCOSEX4', type: 'string' },
		{name: 'CMONCOSEX5', type: 'string' },
		{name: 'CBANCOSEX', type: 'string' },
		{name: 'CESCUOTAI2', type: 'string' },
		{name: 'CESCUOTAI3', type: 'string' },
		{name: 'CIDUNICOM', type: 'string' },
		{name: 'CIDUNIVEN', type: 'string' },
		{name: 'CSUBTIPO', type: 'string' },
		{name: 'CCODALTERN', type: 'string' },
		{name: 'CNOMALTERN', type: 'string' },
		{name: 'CDESCCORTA', type: 'string' },
		{name: 'CIDMONEDA', type: 'string' },
		{name: 'CUSABASCU', type: 'string' },
		{name: 'CTIPOPAQUE', type: 'string' },
		{name: 'CPRECSELEC', type: 'string' },
		{name: 'CDESGLOSAI', type: 'string' },
		{name: 'CSEGCONT04', type: 'string' },
		{name: 'CSEGCONT05', type: 'string' },
		{name: 'CSEGCONT06', type: 'string' },
		{name: 'CSEGCONT07', type: 'string' },
		{name: 'CCTAPRED', type: 'string' },
		{name: 'CNODESCOMP', type: 'string' }
    ],
    idProperty: 'CIDPRODU01'
});




Ext.define('MGW10002', {
    extend: 'Ext.data.Model',
    fields: [
       {name: 'CIDCLIEN01', type: 'string' },
       {name: 'CCODIGOC01', type: 'string' },
       {name: 'CRAZONSO01', type: 'string' },
       {name: 'CFECHAALTA', type: 'string' },
       {name: 'CRFC', type: 'string' },
       {name: 'CCURP', type: 'string' },
       {name: 'CDENCOME01', type: 'string' },
       {name: 'CREPLEGAL', type: 'string' },
       {name: 'CIDMONEDA', type: 'string' },
       {name: 'CLISTAPR01', type: 'string' },
       {name: 'CDESCUEN01', type: 'string' },
       {name: 'CDESCUEN02', type: 'string' },
       {name: 'CBANVENT01', type: 'string' },
       {name: 'CIDVALOR01', type: 'string' },
       {name: 'CIDVALOR02', type: 'string' },
       {name: 'CIDVALOR03', type: 'string' },
       {name: 'CIDVALOR04', type: 'string' },
       {name: 'CIDVALOR05', type: 'string' },
       {name: 'CIDVALOR06', type: 'string' },
       {name: 'CTIPOCLI01', type: 'string' },
       {name: 'CESTATUS', type: 'string' },
       {name: 'CFECHABAJA', type: 'string' },
       {name: 'CFECHAUL01', type: 'string' },
       {name: 'CLIMITEC01', type: 'string' },
       {name: 'CDIASCRE01', type: 'string' },
       {name: 'CBANEXCE01', type: 'string' },
       {name: 'CDESCUEN03', type: 'string' },
       {name: 'CDIASPRO01', type: 'string' },
       {name: 'CINTERES01', type: 'string' },
       {name: 'CDIAPAGO', type: 'string' },
       {name: 'CDIASREV01', type: 'string' },
       {name: 'CMENSAJE01', type: 'string' },
       {name: 'CCUENTAM01', type: 'string' },
       {name: 'CDIASEMB01', type: 'string' },
       {name: 'CIDALMACEN', type: 'string' },
       {name: 'CIDAGENT01', type: 'string' },
       {name: 'CIDAGENT02', type: 'string' },
       {name: 'CRESTRIC01', type: 'string' },
       {name: 'CIMPUESTO1', type: 'string' },
       {name: 'CIMPUESTO2', type: 'string' },
       {name: 'CIMPUESTO3', type: 'string' },
       {name: 'CRETENCI01', type: 'string' },
       {name: 'CRETENCI02', type: 'string' },
       {name: 'CIDVALOR07', type: 'string' },
       {name: 'CIDVALOR08', type: 'string' },
       {name: 'CIDVALOR09', type: 'string' },
       {name: 'CIDVALOR10', type: 'string' },
       {name: 'CIDVALOR11', type: 'string' },
       {name: 'CIDVALOR12', type: 'string' },
       {name: 'CLIMITEC02', type: 'string' },
       {name: 'CDIASCRE02', type: 'string' },
       {name: 'CTIEMPOE01', type: 'string' },
       {name: 'CDIASEMB02', type: 'string' },
       {name: 'CIMPUEST01', type: 'string' },
       {name: 'CIMPUEST02', type: 'string' },
       {name: 'CIMPUEST03', type: 'string' },
       {name: 'CRETENCI03', type: 'string' },
       {name: 'CRETENCI04', type: 'string' },
       {name: 'CBANINTE01', type: 'string' },
       {name: 'CCOMVENT01', type: 'string' },
       {name: 'CCOMCOBR01', type: 'string' },
       {name: 'CBANPROD01', type: 'string' },
       {name: 'CSEGCONT01', type: 'string' },
       {name: 'CSEGCONT02', type: 'string' },
       {name: 'CSEGCONT03', type: 'string' },
       {name: 'CSEGCONT04', type: 'string' },
       {name: 'CSEGCONT05', type: 'string' },
       {name: 'CSEGCONT06', type: 'string' },
       {name: 'CSEGCONT07', type: 'string' },
       {name: 'CSEGCONT08', type: 'string' },
       {name: 'CSEGCONT09', type: 'string' },
       {name: 'CSEGCONT10', type: 'string' },
       {name: 'CSEGCONT11', type: 'string' },
       {name: 'CSEGCONT12', type: 'string' },
       {name: 'CSEGCONT13', type: 'string' },
       {name: 'CSEGCONT14', type: 'string' },
       {name: 'CTEXTOEX01', type: 'string' },
       {name: 'CTEXTOEX02', type: 'string' },
       {name: 'CTEXTOEX03', type: 'string' },
       {name: 'CFECHAEX01', type: 'string' },
       {name: 'CIMPORTE01', type: 'string' },
       {name: 'CIMPORTE02', type: 'string' },
       {name: 'CIMPORTE03', type: 'string' },
       {name: 'CIMPORTE04', type: 'string' },
       {name: 'CBANDOMI01', type: 'string' },
       {name: 'CBANCRED01', type: 'string' },
       {name: 'CBANENVIO', type: 'string' },
       {name: 'CBANAGENTE', type: 'string' },
       {name: 'CBANIMPU01', type: 'string' },
       {name: 'CBANPRECIO', type: 'string' },
       {name: 'CTIMESTAMP', type: 'string' },
       {name: 'CFACTERC01', type: 'string' },
       {name: 'CCOMVENTA', type: 'string' },
       {name: 'CCOMCOBRO', type: 'string' },
       {name: 'CIDMONEDA2', type: 'string' },
       {name: 'CEMAIL1', type: 'string' },
       {name: 'CEMAIL2', type: 'string' },
       {name: 'CEMAIL3', type: 'string' },
       {name: 'CTIPOENTRE', type: 'string' },
       {name: 'CCONCTEEMA', type: 'string' },
       {name: 'CFTOADDEND', type: 'string' },
       {name: 'CIDCERTCTE', type: 'string' },
       {name: 'CENCRIPENT', type: 'string' },
       {name: 'CBANCFD', type: 'string' },
       {name: 'CTEXTOEX04', type: 'string' },
       {name: 'CTEXTOEX05', type: 'string' },
       {name: 'CIMPORTE05', type: 'string' },
       {name: 'CIDADDENDA', type: 'string' },
       {name: 'CCODPROVCO', type: 'string' },
       {name: 'CENVACUSE', type: 'string' },
       {name: 'CCON1NOM', type: 'string' },
       {name: 'CCON1TEL', type: 'string' },
       {name: 'CQUITABLAN', type: 'string' },
       {name: 'CFMTOENTRE', type: 'string' },
       {name: 'CIDCOMPLEM', type: 'string' },
       {name: 'CDESGLOSAI', type: 'string' },
       {name: 'CLIMDOCTOS', type: 'string' },
       {name: 'CSITIOFTP', type: 'string' },
       {name: 'CUSRFTP', type: 'string' }
    ],
    idProperty: 'CCODIGOC01'
});






var AdminPAQExplorer = function( id_botones ){
	
	
	
	var id_form_botones = id_botones;

	var clientes = Ext.create('Ext.data.ArrayStore', {
		        model: 'MGW10002',
		        data: []
			}),
		productos = Ext.create('Ext.data.ArrayStore', {
			        model: 'MGW10005',
			        data: []
			});		
		
	

	
	var createWindow = function(){
		var tabs = Ext.createWidget('tabpanel', {
	        height: 450,
	        width: 650,
			frame: true,
			closable: true,
			modal:true,
			floating:true,
			autoShow:true,	
	        activeTab: 0,
	        defaults :{
	            bodyPadding: 10
	        },
	        items: [
					createGridForClientes(),
					createGridForProds()
			]
	    });
	}//createWindow
	

		
	
	var getData = function(table){
		console.log(1)
		Ext.data.JsonP.request({
			url : Ext.get( id_form_botones +"url").getValue(),
			params : {
				sql		: "select * from MGW10002",
				path	: Ext.get( id_form_botones +"path").getValue()//"C:\\Compacw\\Empresas\\Caffeina\\"
			},
			callback : function(status, response){
				if(!status){
					return;
				}
				
				clientes.loadData(response.datos);
				
			}
		});

		console.log(2)
		
		Ext.data.JsonP.request({
			url : Ext.get( id_form_botones +"url").getValue(),//'https://192.168.0.15:16001/json/AdminPAQProxy/',
			params : {
				sql		: "select * from MGW10005",
				path	: Ext.get( id_form_botones +"path").getValue()//"C:\\Compacw\\Empresas\\Caffeina\\"
																		//C:\Compacw\Empresas\Caffeina\
			},
			callback : function(status, response){
				
				if(!status){
					return;
				}
				

				
				productos.loadData(response.datos);
				
			}
		});
		console.log(3)		
	}//ajaxtoClient
	
	
	var createGridForProds = function(){
		
		return Ext.create('Ext.grid.Panel', {
	        store: productos, 
			title : "productos",
			frame : false,
	        columns: [
	            { text     : 'Código del Producto',
	                width    : 75,
	                sortable : true,
	                dataIndex: 'CCODIGOP01'
	            },
	            {
	                text     : 'Nombre del producto',
	                width    : 75,
	                sortable : true,
	                dataIndex: 'CNOMBREP01'
	            },
	            {
	                text     : 'Tipo del Producto',
	                width    : 75,
	                sortable : true,
	                dataIndex: 'CTIPOPRO01'
	            },
	            {
	                text     : 'Descripción detallada',
	                width    : 75,
	                sortable : true,
	                dataIndex: 'CDESCRIP01'
	            }										
	        ],

			modal: true,
	        viewConfig: {
	            stripeRows: true,
	            enableTextSelection: true
	        },
			bbar: Ext.create('Ext.ux.StatusBar', {
			            text: 'Mostrando n registros',
			            // any standard Toolbar items:
			            items: [
			                {
			                    xtype: 'button',
			                    text: 'Cancelar',
			                    handler: function (){
									this.close();
			                    }
			                },
			                {
			                    xtype: 'button',
			                    text: 'Importar',
			                    handler: function (){

			                    }
			                }
			            ]
			        })
	    });
	}//createGridForClientes()
	
	var createGridForClientes = function(){
		
		return Ext.create('Ext.grid.Panel', {
	        store: clientes, 
			title : "Clientes",
	        multiSelect: true,
			frame : false,
	        columns: [
	            { text     : 'Identificador del Cliente', dataIndex: 'CCODIGOC01' },
	            { text     : 'Código del Cliente',
	                width    : 75,
	                sortable : true,
	                dataIndex: 'CRAZONSO01'
	            },
	            {
	                text     : 'Razón Social',
	                width    : 75,
	                sortable : true,
	                dataIndex: 'CRAZONSO01'
	            },
	            {
	                text     : 'Fecha de alta',
	                width    : 75,
	                sortable : true,
	                dataIndex: 'CFECHAALTA'
	            },
	            {
	                text     : 'RFC',
	                width    : 75,
	                sortable : true,
	                dataIndex: 'CRFC'
	            },
	            {
	                text     : 'CURP',
	                width    : 75,
	                sortable : true,
	                dataIndex: 'CCURP'
	            },
	            {
	                text     : 'Denominación comercial',
	                width    : 75,
	                sortable : true,
	                dataIndex: 'CDENCOME01'
	            },
	            {
	                text     : 'Representante',
	                width    : 75,
	                sortable : true,
	                dataIndex: 'CREPLEGAL'
	            }										
	        ],

	        viewConfig: {
	            stripeRows: true,
	            enableTextSelection: true
	        },
			bbar: Ext.create('Ext.ux.StatusBar', {
			            text: 'Mostrando n registros',
			            // any standard Toolbar items:
			            items: [
			                {
			                    xtype: 'button',
			                    text: 'Cancelar',
			                    handler: function (){
									//this.close();
			                    }
			                },
			                {
			                    xtype: 'button',
			                    text: 'Importar',
			                    handler: function (){

			                    }
			                }
			            ]
			        })
	    });
	}//createGridForClientes()

	getData( );	
	createWindow(  );

}













var importarClientes = function(d){
	

	Ext.data.JsonP.request({
		url : 'http://192.168.1.109:16001/',
		params : {
			"action" : "AdminPAQProxy",
			sql		: "select * from MGW10002",
			path	: "C:\\Compacw\\Empresas\\Caffeina\\"
		},
		callback : function(status, response){
			if(!status){
				return;
			}
			
			console.log( response);
			
			
	   	 	// create the data store
		    var clientesAdminPAQ = Ext.create('Ext.data.ArrayStore', {
		        model: 'MGW10002',
		        data: response.datos
		    });




		    // create the Grid
		    var clientesGrid = Ext.create('Ext.grid.Panel', {
		        store: clientesAdminPAQ,
		        stateful: false,
				title : "Clientes",
		        multiSelect: true,
				frame : false,
		        columns: [
		            {
		                text     : 'Identificador del Cliente',
		                flex     : 1,
		                sortable : false,
		                dataIndex: 'CCODIGOC01'
		            },
		            {
		                text     : 'Código del Cliente',
		                width    : 75,
		                sortable : true,
		                dataIndex: 'CRAZONSO01'
		            },
		            {
		                text     : 'Razón Social',
		                width    : 75,
		                sortable : true,
		                dataIndex: 'CRAZONSO01'
		            },
		            {
		                text     : 'Fecha de alta',
		                width    : 75,
		                sortable : true,
		                dataIndex: 'CFECHAALTA'
		            },
		            {
		                text     : 'RFC',
		                width    : 75,
		                sortable : true,
		                dataIndex: 'CRFC'
		            },
		            {
		                text     : 'CURP',
		                width    : 75,
		                sortable : true,
		                dataIndex: 'CCURP'
		            },
		            {
		                text     : 'Denominación comercial',
		                width    : 75,
		                sortable : true,
		                dataIndex: 'CDENCOME01'
		            },
		            {
		                text     : 'Representante',
		                width    : 75,
		                sortable : true,
		                dataIndex: 'CREPLEGAL'
		            }										
		        ],

				modal: true,
		        viewConfig: {
		            stripeRows: true,
		            enableTextSelection: true
		        },
				bbar: Ext.create('Ext.ux.StatusBar', {
				            text: 'Mostrando n registros',
				            // any standard Toolbar items:
				            items: [
				                {
				                    xtype: 'button',
				                    text: 'Cancelar',
				                    handler: function (){
										this.close();
				                    }
				                },
				                {
				                    xtype: 'button',
				                    text: 'Importar',
				                    handler: function (){

				                    }
				                }
				            ]
				        })
		    });
		
		
			// basic tabs 1, built from existing content
			    var tabs = Ext.createWidget('tabpanel', {
			        height: 450,
			        width: 600,
					modal:true,
					floating:true,
					autoShow:true,	
			        activeTab: 0,
			        defaults :{
			            bodyPadding: 10
			        },
			        items: [
						clientesGrid,
						{
			            title: 'Long Text'
			        }]
			    });
		}//callback
	});
	


}



var nuevoClienteAval =  function( nombre, id_usuario, id_este_usuario ){  
    Ext.Msg.confirm("Agregar Nuevo Aval","En realidad desea agregar a " + nombre + " como nuevo aval?", function(btn) {

        if(btn == "yes"){ 

            var tipo_aval = Ext.get("radio_hipoteca").dom.checked ? "hipoteca" : "prendario";

            POS.API.POST(
                "api/cliente/aval/nuevo", 
                {"id_cliente" : id_este_usuario, "avales" : Ext.JSON.encode([{ "id_aval": id_usuario , "tipo_aval" : tipo_aval }])}, 
                {callback : function(a){ window.location = "clientes.ver.php?cid="+id_este_usuario; }}
            ); 

        }
    }, this);  
};





var ExtComponent =  function(component, id){
    this.component = component;
    this.id = id;
};

var storeComponent = function(){  

  this.arrayComponent = [];
  this.arrayIndex = 0;

  this.addExtComponent = function( component, id ){
//    Ext.Array( this.arrayComponent, this.arrayIndex, new ExtComponent( component, id ));               

      this.arrayComponent[this.arrayIndex] = new ExtComponent( component, id );
      this.arrayIndex++;        
  };

  this.render = function(){
    Ext.Array.forEach( this.arrayComponent, function(c){
        c.component.render(c.id);        
    });
  };

};   

var store_component = new storeComponent();




Ext.example = function(){
    var msgCt;

    function createBox(t, s){
       // return ['<div class="msg">',
       //         '<div class="x-box-tl"><div class="x-box-tr"><div class="x-box-tc"></div></div></div>',
       //         '<div class="x-box-ml"><div class="x-box-mr"><div class="x-box-mc"><h3>', t, '</h3>', s, '</div></div></div>',
       //         '<div class="x-box-bl"><div class="x-box-br"><div class="x-box-bc"></div></div></div>',
       //         '</div>'].join('');
       return '<div class="msg"><h3>' + t + '</h3><p>' + s + '</p></div>';
    }
    return {
        msg : function(title, format){
            if(!msgCt){
                msgCt = Ext.core.DomHelper.insertFirst(document.body, {id:'msg-div'}, true);
            }
            var s = Ext.String.format.apply(String, Array.prototype.slice.call(arguments, 1));
            var m = Ext.core.DomHelper.append(msgCt, createBox(title, s), true);
            m.hide();
            m.slideIn('t').ghost("t", { delay: 1000, remove: true});
        },

        init : function(){
//            var t = Ext.get('exttheme');
//            if(!t){ // run locally?
//                return;
//            }
//            var theme = Cookies.get('exttheme') || 'aero';
//            if(theme){
//                t.dom.value = theme;
//                Ext.getBody().addClass('x-'+theme);
//            }
//            t.on('change', function(){
//                Cookies.set('exttheme', t.getValue());
//                setTimeout(function(){
//                    window.location.reload();
//                }, 250);
//            });
//
//            var lb = Ext.get('lib-bar');
//            if(lb){
//                lb.show();
//            }
        }
    };
}();


 var drawMap = function ( result, status ) {


    if(result.length == 0){
        document.getElementById("map_canvas").innerHTML = "<div align='center'> Imposible localizar esta direccion. </div>"; 
        return;
    }

    var myLatlng = result[0].geometry.location;

    var myOptions = {
      zoom: 16,
      center: myLatlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
	  navigationControl : true
    }

	try{
    	var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	}catch(e){
        document.getElementById("map_canvas").innerHTML = "<div align='center'> Imposible crear el mapa.</div>";
        return;
	}
    

    m = new google.maps.Marker({
        map: map,
        position: myLatlng
    });


  };

    function startMap( direccion ){

	    GeocoderRequest = {
		    address : direccion + ", Mexico"
	    };
	    try{

		    gc = new google.maps.Geocoder( );

		    gc.geocode(GeocoderRequest,  drawMap);
		
	    }catch(e){
		    console.log(e)
	    }


    }






function FormatMoney(m){
	return "$" + m;
}