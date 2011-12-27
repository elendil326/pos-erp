Ext.require([
    'Ext.data.*',
    'Ext.form.*',
    'Ext.grid.*',
    'Ext.util.*',
    'Ext.state.*'	
]);


var main = function ()
{
	console.log("JS FRWK READY");
	

	//window.onbeforeunload = function(){}
	var els = Ext.select("input").elements;
	console.log("ataching on before unload events...");
	
	for (var i = els.length - 1; i >= 0; i--){
		Ext.get(els[i]).on(
			"keydown",
			function(){
					window.onbeforeunload = function(){ 
						return 'Usted ha modificado el formulario. Si sale de esta pagina perdera los cambios. Esta seguro que desea salir?';
					}
			});
	};
	

}


Ext.onReady(main);


var POS = 
{
	
	Empresas : 
	{
		
		validarNuevaEmpresa : function (   )
		{
				
		},

		agregar : function (   ) 
		{
			
			POS.API.GET("api/sucursal/almacen/entrada/", 
			{ 
				productos : Ext.JSON.encode([{ id_producto : 5, cantidad : 5 }]), 
				id_almacen : 3
			},{
				callback : function( a ){ console.log(a ); }
			});

		}
	}
}













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