

Ext.require([
    'Ext.data.*',
    'Ext.form.*',
    'Ext.grid.*',
    'Ext.util.*',
    'Ext.state.*',
	'Ext.ux.grid.TransformGrid'	
]);



var main = function ()
{
	console.log("JS FRWK READY");

	//window.onbeforeunload = function(){}

	var els = Ext.select("input").elements;

	console.log("Ataching on before unload events...");
	
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
		Ext.example.msg('Exito', 'Your data was saved!');

}


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
