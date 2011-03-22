

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
POS.Keyboard.Keyboard = function ( campo, config ){

	if(POS.Keyboard.KeyboardObj){
		var internalConfig = POS.Keyboard.genHTML( config );
		POS.Keyboard.callback = config.callback;

        //POS.Keyboard.KeyboardObj.setCentered(false);
        POS.Keyboard.KeyboardObj.showBy(campo, true, false);

		if( POS.Keyboard.KeyboardObj.getWidth() != internalConfig.width )
			POS.Keyboard.KeyboardObj.setWidth(internalConfig.width);
		
		if( POS.Keyboard.KeyboardObj.getHeight() != internalConfig.height )
			POS.Keyboard.KeyboardObj.setHeight(internalConfig.height);


		POS.Keyboard.KeyboardObj.update( internalConfig.html );

        POS.Keyboard.KeyboardObj.showBy(campo, true, false);
		POS.Keyboard.campo = campo;

		return POS.Keyboard.Keyboard;
	}
	
	POS.Keyboard.KeyboardObj = new Ext.Panel({
        floating: true,
		style : {
			zIndex : '20000 !important'
		},
		ui : "dark",
        modal: false,
        scroll: false,
        width: 100,
        height:100,
		showAnimation : Ext.anims.fade ,
		hideOnMaskTap : true,
		bodyPadding : 0,
		bodyMargin : 0,
        styleHtmlContent: false,
		html : null,
        listeners:{
            'hide' : function(){
                POS.Keyboard.campo.blur();
            }
        }
    });

	//ya creado, volver a llamar a esta funcion para que ponga el contenido correcto
	return POS.Keyboard.Keyboard (campo, config);
	
	
};



POS.Keyboard.hide = function () {

	if(POS.Keyboard.KeyboardObj){
        POS.Keyboard.KeyboardObj.hide( Ext.anims.fade );
        POS.Keyboard.campo.blur();

	}
};



POS.Keyboard.callbackFn = function ( val, isSubmit ) {


	if( isSubmit === true){
		
		POS.Keyboard.hide();
		if(POS.Keyboard.callback){
			
			POS.Keyboard.callback.call( this,  POS.Keyboard.campo );
		}
		POS.Keyboard.campo.blur();
		return;
	}
	
	if(val == "_DEL_"){
		var str = POS.Keyboard.campo.getValue();
		POS.Keyboard.campo.setValue( str.substring(0, str.length -1) );
		return;
	}
	
	if(val == "_SPACE_"){
    	POS.Keyboard.campo.setValue( POS.Keyboard.campo.getValue() + " " );
		return;
	}

	if(val == '_CANCEL_'){
		POS.Keyboard.campo.blur();
		POS.Keyboard.campo.setValue( POS.Keyboard.campo.startValue );
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
			+ "<div class='Keyboard-key' onclick='POS.Keyboard.callbackFn( \"_DEL_\", false )'>&#8592;</div>"				
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
			+ "<div class='Keyboard-key long'  onclick='POS.Keyboard.callbackFn( \"_SPACE_\", false  )'></div>";

	html += "<div class='Keyboard-key long' onclick='POS.Keyboard.callbackFn( null, true)'>" +config.submitText+ "</div>";
	
	return POS.Keyboard._HTMLalfa = {
		html: html,
		w : 690,
		h : 160 
	};	
};

POS.Keyboard._genHTMLalfanum = function (config){
	
	
	var html = "";
	
		html += "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>1</div>"
            + "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>2</div>"
            + "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>3</div>"
            + "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>4</div>"
            + "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>5</div>"
            + "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>6</div>"
            + "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>7</div>"
            + "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>8</div>"
            + "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>9</div>"
            + "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>0</div>"
			+ "<div class='Keyboard-key' onclick='POS.Keyboard.callbackFn( \"_DEL_\", false )'>&#8592;</div>"
            + "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>Q</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>W</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>E</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>R</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>T</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>Y</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>U</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>I</div>"				
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>O</div>"				
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>P</div>"				
            + "<div class='Keyboard-key ' onclick='POS.Keyboard.callbackFn( null, true)'>" +config.submitText+ "</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )' style='margin-left:30px'>A</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>S</div>"				
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>D</div>"				
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>F</div>"				
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>G</div>"				
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>H</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>J</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>K</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>L</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>&Ntilde;</div>"

			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )' style='margin-left:60px'>Z</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>X</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>C</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>V</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>B</div>"				
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>N</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )' >M</div>"
			+ "<div class='Keyboard-key long'  onclick='POS.Keyboard.callbackFn( \"_SPACE_\", false  )'></div>";


	
	return POS.Keyboard._HTMLalfa = {
		html: html,
		w : 677,
		h : 210 
	};	
};

POS.Keyboard._genHTMLnum = function (config){
	
	var html = "";
	
		html += "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>7</div>";
		html += "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>8</div>";
		html += "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>9</div>";
		html += "<div class='Keyboard-key ' onclick='POS.Keyboard.callbackFn(  \"_DEL_\", false )'>&#8592;</div>";	

		html += "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>4</div>";
		html += "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>5</div>";
		html += "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>6</div>";
		html += "<div class='Keyboard-key ' onclick='POS.Keyboard.callbackFn(  \"_CANCEL_\", false )' style=\"padding-left: 2px;\">Cancelar</div>";
		
		html += "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>1</div>";
		html += "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>2</div>";
		html += "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>3</div>";
		html += "<div class='Keyboard-key ' onclick='POS.Keyboard.callbackFn(  null, true )'>" +config.submitText+ "</div>";

		html += "<div class='Keyboard-key ' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>0</div>";
		html += "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>.</div>";			

	return POS.Keyboard._HTMLnum = {
		html: html,
		w : 282,
		h : 205 
	};
};

POS.Keyboard._genHTMLcomplete = function (config){
		
	
	var html = "";
	
		html += "<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>!</div>"
            + "<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>@</div>"
            + "<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>#</div>"
            + "<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>$</div>"
            + "<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>%</div>"
            + "<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>^</div>"
            + "<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>&</div>"
            + "<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>*</div>"
            + "<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>(</div>"
            + "<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>)</div>"
			+ "<div class='Keyboard-key tinyNormal' onclick='POS.Keyboard.callbackFn( \"_DEL_\", false )'>&#8592;</div>"
			+ "<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>1</div>"
            + "<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>2</div>"
            + "<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>3</div>"
            + "<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>4</div>"
            + "<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>5</div>"
            + "<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>6</div>"
            + "<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>7</div>"
            + "<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>8</div>"
            + "<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>9</div>"
            + "<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>0</div>"
			+ "<div class='Keyboard-key tinyNormal' onclick='POS.Keyboard.callbackFn( null, true )'>" +config.submitText+ "</div>"
            + "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )' style='margin-left:15px'>Q</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>W</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>E</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>R</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>T</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>Y</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>U</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>I</div>"				
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>O</div>"				
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )' style='margin-right:50px'>P</div>"				
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )' style='margin-left:30px'>A</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>S</div>"				
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>D</div>"				
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>F</div>"				
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>G</div>"				
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>H</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>J</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>K</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>L</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>&Ntilde;</div>"

			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )' style='margin-left:60px'>Z</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>X</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>C</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>V</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>B</div>"				
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>N</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )' >M</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>,</div>"
			+ "<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )' >.</div>"
			+ "<div class='Keyboard-key tinyLong'  onclick='POS.Keyboard.callbackFn( \"_SPACE_\", false  )' style='margin-left:200px'></div>";


	
	return POS.Keyboard._HTMLalfa = {
		html: html,
		w : 670,
		h : 265
	};	
};

