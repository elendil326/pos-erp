
//	--------------------------------------------------------------------------------------
//		Utility functions
//	--------------------------------------------------------------------------------------

POS = {
	
};


POS.AJAXandDECODE = function (params, success, failure)
{
	if(DEBUG){
		console.log("Sending HTTP request ....", "action="+params.action);
		//alert("caller is " + arguments.callee.caller.toString());
		//alert("caller is " + POS.AJAXandDECODE.caller.toString());
	}
	
	Ext.Ajax.request({
		
		url: 'proxy.php',
		
		success: function (response){

			var datos;
			try{				
				eval("datos = " + response.responseText);
				
				if(DEBUG){
					//console.log("HTTP Request returned with code 200", datos);
				}
				
				if((typeof(datos.succes) !== 'undefined') && (datos.succes === false)){
					if((typeof(datos.reason) !== 'undefined') && datos.reason == 31416 ){
						alert("Sesion no valida. Porfavor identifiquese de nuevo.");
						window.location = "index.html";
						return;
					}
				}
				
			}catch(e){
			
				if(DEBUG){
					console.warn("Failed to parse JSON", e);
					console.warn("Full response : " + response.responseText);
				}
				
				
				return;
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











POS.map = function( address, idRender )
{
	
	var mapPanel = new Ext.Panel({
		layout: 'fit',
		renderTo: idRender,
		listeners: {
				afterrender: function(panel){
					
					if(!window.google){
						if(DEBUG){
							console.log("No google ");
						}
						return null;
					}
					
					try{
						var geocoder = new google.maps.Geocoder();
					}
					catch(error)
					{
						//document.getElementById( Ext.get(panel.id).dom.childNodes[1].id).innerHTML = '<div style="text-align:center; padding-top:30%;"> Error al contactar a Google Maps .</div>';
						return null;
					}

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

	POS.pickerSlots = posSlot;
	//console.log(posPicker);

	//return posPicker;	

	extPicker = new Ext.DatePicker();
	extPicker.slots[0].items[0].key = 'Enero';

	//POS.pickerSlots = extPicker.slots;
};


//POS.pickerSlots = null;



POS.doPrintTicket = function ()
{
	
	
	
};




POS.Keyboard = {
	
	//la funcion que se llamara para usar un teclado
	Keyboard : null,
	
	//el objeto que se creara, es un panel
	keyboardObj : null,
	
	//la funcion que se llamara al hacer click en un boton
	callback : null,
	
	//la funcion que ocultara la forma
	hide: null,
	
	//el campo de texto al que se le haran los movimientos
	campo: null, 
	
	//la funcion que generara el html para cada uno de los
	//teclados
	genHTML: null,
	
	//utility
	callbackFn : null,
	_genHTMLalfa: null, _HTMLalfa : null,
	_genHTMLalfanum: null, _HTMLalfanum : null,
	_genHTMLnum: null, _HTMLnum : null,
	_genHTMLcomplete: null, _HTMLcomplete : null
};


POS.Keyboard.Keyboard = function ( campo, config ){
	/*
		campo - Es el campo de text donde se aplicara este teclado
		config - es un objeto con distintas configuraciones como estas
			config = {
				//tipo del teclado a utilizar
				type : 'alfa' || 'num' || 'alfanum' || 'complete',

				//texto que tendra el boton de aceptar
				submitText : 'Aceptar',

				//funcion que se llamara cuando se haga click en aceptar,
				callback : function
			}
	*/
	if(POS.Keyboard.KeyboardObj){
		var internalConfig = POS.Keyboard.genHTML( config );
		POS.Keyboard.callback = config.callback;

		POS.Keyboard.KeyboardObj.update( internalConfig.html );
		POS.Keyboard.KeyboardObj.setWidth(internalConfig.width);
		POS.Keyboard.KeyboardObj.setHeight(internalConfig.height);
		
		POS.Keyboard.KeyboardObj.setCentered(false);
        POS.Keyboard.KeyboardObj.showBy(campo, true, false);

		if( POS.Keyboard.KeyboardObj.getWidth() != internalConfig.width )
			POS.Keyboard.KeyboardObj.setWidth(internalConfig.width);
		
		if( POS.Keyboard.KeyboardObj.getHeight() != internalConfig.height )
			POS.Keyboard.KeyboardObj.setHeight(internalConfig.height);
		
		POS.Keyboard.campo = campo;

		return POS.Keyboard.Keyboard;
	}
	
	POS.Keyboard.KeyboardObj = new Ext.Panel({
        floating: true,
		ui : "dark",
        modal: false,
		showAnimation : Ext.anims.fade ,
        centered: true,
		hideOnMaskTap : false,
		bodyPadding : 0,
		bodyMargin : 0,
        styleHtmlContent: false,
		html : null,
        scroll: 'none'
    });

	//ya creado, volver a llamar a esta funcion para que ponga el contenido correcto
	return POS.Keyboard.Keyboard (campo, config);
	
	
};

POS.Keyboard.hide = function () {

	if(POS.Keyboard.KeyboardObj){
        POS.Keyboard.KeyboardObj.hide( Ext.anims.fade );
	}
};

POS.Keyboard.callbackFn = function ( val, isSubmit ) {


	if( isSubmit === true){

		POS.Keyboard.hide();
		POS.Keyboard.callback.call();
		return;
	}
	
	if(val == "_DEL_"){
		var str = POS.Keyboard.campo.getValue();
		POS.Keyboard.campo.setValue( str.substring(0, str.length -1) );
		return;
	}
	
	if(val == '_CANCEL_'){
		POS.Keyboard.campo.setValue( "" );
		POS.Keyboard.hide();
		return;
	}
	
	POS.Keyboard.campo.setValue( POS.Keyboard.campo.getValue() + val );

};

POS.Keyboard.genHTML = function (config) {
	
	var html = "", w = 100 , h = 100;
	var iConfig;
	
	html += "<div class='Keyboard'>";
	
	switch( config.type ){
		case 'alfa': 
			iConfig = POS.Keyboard._genHTMLalfa(config);
		break;
		
		case 'num':
			iConfig = POS.Keyboard._genHTMLnum(config);		
		break;
		
		case 'alfanum':
			iConfig = POS.Keyboard._genHTMLalfanum(config);		
		break;
		
		case 'complete':
			iConfig = POS.Keyboard._genHTMLcomplete(config);		
		break;
		
		default:
			throw ( "Invalid Keyboard Type");
	}
	
	html += iConfig.html;
	w = iConfig.w;
	h = iConfig.h;
	
	html += "</div>";
	
	
	return {
		html : html,
		width : w,
		height : h
	};
	
	
};

POS.Keyboard._genHTMLalfa = function (config){

	
	
	var html = "";
	
		html += "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>Q</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>W</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>E</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>R</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>T</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>Y</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>U</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>I</div>"				
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>O</div>"				
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>P</div>"				
			+ "<div class='Keyboard-key' onclick='POS.Keyboard.callbackFn( \"_DEL_\", false )'>DEL</div>"				
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )' style='margin-left:30px'>A</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>S</div>"				
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>D</div>"				
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>F</div>"				
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>G</div>"				
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>H</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>J</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>K</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>L</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )' style='margin-right:30px'>&Ntilde;</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>Z</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>X</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>C</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>V</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>B</div>"				
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>N</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>M</div>"
			+ "<div class='Keyboard-key long'></div>";

	html += "<div class='Keyboard-key long' onclick='POS.Keyboard.callbackFn( null, true)'>" +config.submitText+ "</div>";
	
	return POS.Keyboard._HTMLalfa = {
		html: html,
		w : 720,
		h : 300 
	};	
};

POS.Keyboard._genHTMLalfanum = function (config){
	
};

POS.Keyboard._genHTMLnum = function (config){
	
	var html = "";
	
	for( a = 1 ; a < 10 ; a++){
		html += "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>" +a+ "</div>";
		if( a == 3)
			html += "<div id='Keyboard-key-num-submit' class='Keyboard-key long' onclick='POS.Keyboard.callbackFn( null, true)'>" +config.submitText+ "</div>";

		if( a == 6)
			html += "<div id='Keyboard-key-num-submit' class='Keyboard-key long' onclick='POS.Keyboard.callbackFn( \"_CANCEL_\", null)'>Cancelar</div>";
			
		//if( a == 9)
		//	html += "<div id='Keyboard-key-num-submit' class='Keyboard-key long' onclick='POS.Keyboard.callbackFn( '_CANCEL_', null)'>CANCELAR</div>";
	}
	

	html += "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>0</div>";
	html += "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>.</div>";

	return POS.Keyboard._HTMLnum = {
		html: html,
		w : 350,
		h : 300 
	};
};

POS.Keyboard._genHTMLcomplete = function (config){
	
};

