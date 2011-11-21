
var main = function ()
{
	console.log("JS FRWK READY");

	//POS.API.POST("api/personal/rol/nuevo/", { descripcion :"a", nombre : "asdf" }, function(a ){ console.log(a ); });



}

Ext.onReady(main);





var POS = 
{
	
	Empresas : 
	{
		
		validarNuevaEmpresa : function (   )
		{
				
		},

		nueva : function (   ) 
		{
			
			POS.API.POST("api/empresa/nuevo/", 
			{ 
				codigo_postal :"a", 
				colonia :"a", 
				curp :"a", 
				numero_exterior: "123",
				razon_social : "123",
				rfc: "123",
				ciudad : "334",
				calle : "Galena",
				
				
			},{
				callback : function( a ){ console.log(a ); }
			});

		}
	}
}













Ext.Ajax.on('beforerequest', function (){ Ext.get("ajax_loader").show(); }, this);
Ext.Ajax.on('requestcomplete', function (){ Ext.get("ajax_loader").hide(); }, this);
Ext.Ajax.on('requestexception', function (){ Ext.get("ajax_loader").hide(); }, this);

POS.API = 
{
	ajaxCallBack : function (callback, a, b, c)
	{
		var o;
		try{
			o = Ext.util.JSON.decode( a.responseText );

		}catch(e){
			console.error("JSON NOT DECODABLE:" , a.responseText);
			return;

		}
		callback.call( null, o );
	},

	ajaxFailure : function ( callback, a,b,c )
	{
		

		var o;
		try{
			o = Ext.util.JSON.decode( a.responseText );
			
			console.error( "API SAYS :  " + o.error )
			
			Ext.MessageBox.show({
			           title: 'Error',
			           msg: o.error,
			           buttons: Ext.MessageBox.OK,
			           icon: "error"
			       });
			
		}catch(e){
			console.error("JSON NOT DECODABLE:" , a.responseText);
			return;

		}
		callback.call( null, o );
	},

	actualAjax : function (  method, url, params, callback  )
	{
		Ext.Ajax.request({
			method 	: method,
			url 	: "../" + url,
			success : function(a,b,c){ POS.API.ajaxCallBack( callback, a, b, c ); },
			failure : function(a,b,c){ POS.API.ajaxFailure( callback, a, b, c ); },
			params  : params
		});
	},


	GET : function( url, params, o)
	{
		POS.API.actualAjax("GET", url, params, o.callback);
	},


	POST : function( url, params, o)
	{
		POS.API.actualAjax("POST", url, params, o.callback);
	}

}