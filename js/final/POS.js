
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






POS.keyboardAlfa = function (btn) {
	config = {
		html :  POS._genKeyboard("alfa"),
		width : 700,
		height : 400
	}
	POS.keyboard(btn, config);
};

POS.keyboardAlfaNum = function (btn) {
	config = {
		html :  POS._genKeyboard("alfaNum"),
		width : 600,
		height : 400		
	}
	POS.keyboard(btn, config);
};

POS.keyboardNum = function (btn) {
	config = {
		html :  POS._genKeyboard( "num" ),
		width : 450,
		height : 300	
	}
	POS.keyboard(btn, config);
};


POS.keyboard = function ( btn, config ){
	
		//si existe, solo mostrarlo
		if(POS._keyboard){
			POS._keyboard.update(config.html);
			POS._keyboard.setWidth(config.width);
			POS._keyboard.setHeight(config.height);
			POS._keyboard.setCentered(false);
	        POS._keyboard.showBy(btn);
			POS._keyboard_boton = btn;
			return;
		}
	 
	
		we = 450;
		he = 300;
		
 		html = config.html;
	
        POS._keyboard = new Ext.Panel({
            floating: true,
			ui : "dark",
            modal: false,
			showAnimation : Ext.anims.fade ,
            centered: false,
			hideOnMaskTap : false,
			bodyPadding : 0,
			bodyMargin : 0,
            width: we,
            height: he,
            styleHtmlContent: false,
			html : html,
            scroll: 'none'
        });

		return POS.keyboard(btn, config);
};

POS._keyboard_callback = function ( val ){
	POS._keyboard_boton.setValue( POS._keyboard_boton.getValue() + val )
};

POS._keyboard_boton = null;

POS._hideKeyboard = function () {
	
	if(POS._keyboard){
        POS._keyboard.hide(Ext.anims.fade);
		return;
	}
	
	
};

POS._genKeyboard = function ( t ){
	html = "<div class='Keyboard'>";
	
	switch (t){
		
		case "alfa" : 
			html += "<div class='Keyboard-key small' >Q</div>"
				+ "<div class='Keyboard-key small' >W</div>"
				+ "<div class='Keyboard-key small'>E</div>"
				+ "<div class='Keyboard-key small'>R</div>"
				+ "<div class='Keyboard-key small'>T</div>"
				+ "<div class='Keyboard-key small'>Y</div>"
				+ "<div class='Keyboard-key small'>U</div>"
				+ "<div class='Keyboard-key small'>I</div>"				
				+ "<div class='Keyboard-key small'>O</div>"				
				+ "<div class='Keyboard-key small'>P</div>"				
				+ "<div class='Keyboard-key small'>DEL</div>"				
				+ "<div class='Keyboard-key small' style='margin-left:30px'>A</div>"
				+ "<div class='Keyboard-key small'>S</div>"				
				+ "<div class='Keyboard-key small'>D</div>"				
				+ "<div class='Keyboard-key small'>F</div>"				
				+ "<div class='Keyboard-key small'>G</div>"				
				+ "<div class='Keyboard-key small'>H</div>"
				+ "<div class='Keyboard-key small'>J</div>"
				+ "<div class='Keyboard-key small'>K</div>"
				+ "<div class='Keyboard-key small'>L</div>"
				+ "<div class='Keyboard-key small'>&Ntilde;</div>"
				+ "<div class='Keyboard-key small'>Z</div>"
				+ "<div class='Keyboard-key small'>X</div>"
				+ "<div class='Keyboard-key small'>C</div>"
				+ "<div class='Keyboard-key small'>V</div>"
				+ "<div class='Keyboard-key small'>B</div>"				
				+ "<div class='Keyboard-key small'>N</div>"
				+ "<div class='Keyboard-key small'>M</div>"
				+ "<div class='Keyboard-key long'></div>";
			html += "<div class='Keyboard-key long' onclick='POS._hideKeyboard()'>ACEPTAR</div>";
		break;
		
		case "alfaNum" : 
		break;
		
		case "num" : 
			for( a = 0 ; a < 10 ; a++)
				html += "<div class='Keyboard-key' onclick='POS._keyboard_callback( " +a+" )'>" +a+ "</div>";

			html += "<div class='Keyboard-key'>.</div>"

			html += "<div class='Keyboard-key long' onclick='POS._hideKeyboard()'>ACEPTAR</div>";
		break;
		
	}

	html += "</div>";
	
	return html;
	
};