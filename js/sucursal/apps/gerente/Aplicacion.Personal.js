

Aplicacion.Personal = function (  ){

	return this._init();
}




Aplicacion.Personal.prototype._init = function (){
    if(DEBUG){
		console.log("Personal: construyendo");
    }

	
	//crear el panel de lista de personal
	this.listaDePersonalPanelCreator();
	
	//crear el panel de nuevo personal
	this.nuevoEmpleadoPanelCreator();
	
	//cargar la lista de personal y guardarla en el store
	this.listaDePersonalLoad();

	//crear el panel de detalles del empleado
	this.detallesDeEmpleadoPanelCreator();

	//cargar los tipos de empleados que se pueden crear
	this.nuevoEmpleadoGargarTipos();
	
	
	Aplicacion.Personal.currentInstance = this;
	
	return this;
};




Aplicacion.Personal.prototype.getConfig = function (){
	return {
	    text: 'Personal',
	    cls: 'launchscreen',
	    items: [{
	        text: 'Lista de personal',
	        card: this.listaDePersonalPanel,
	        leaf: true
	    },
	    {
	        text: 'Nuevo empleado',
	        card: this.nuevoEmpleadoPanel,
	        leaf: true
	    }]
	};
};





/* ********************************************************
	Lista de empleados
******************************************************** */
Aplicacion.Personal.prototype.listaDePersonal = {
	lista : [],
	lastUpdate : null
};


/**
 * Registra el model para listaDePersonal
 */
Ext.regModel('listaDePersonalModel', {
	fields: [
		{name: 'nombre',     type: 'string'}
	]
});




/**
 * @return Ext.data.Store
 */
Aplicacion.Personal.prototype.listaDePersonalStore = new Ext.data.Store({
    model: 'listaDePersonalModel',
    sorters: 'nombre',
           
    getGroupString : function(record) {
        return record.get('nombre')[0];
    }
});




Aplicacion.Personal.prototype.listaDePersonalLoad = function ()
{

	if(DEBUG){
		console.log("Cargando la lista de personal");
	}
	
	Ext.Ajax.request({
		url: 'proxy.php',
		scope : this,
		params : {
			action : 501
		},
		success: function(response, opts) {
			try{
				personal = Ext.util.JSON.decode( response.responseText );				
			}catch(e){
				POS.error(e);
			}
			
			
			if( !personal.success ){
				//volver a intentar
				//return this.listaDePersonalLoad();
				return;
			}
			
			this.listaDePersonal.lista = personal.empleados;
			this.listaDePersonal.lastUpdate = Math.round(new Date().getTime()/1000.0);
			
			this.listaDePersonalStore.loadData( personal.empleados );

		},
		failure: function( response ){
			POS.error( response );
		}
	});
	
	
};






/* ********************************************************
	Lista de empleados Panel
******************************************************** */


/**
 * Contiene el panel con la lista de personal
 */
Aplicacion.Personal.prototype.listaDePersonalPanel = null;


/**
 * Pone un panel en listaDePersonalPanel
 */
Aplicacion.Personal.prototype.listaDePersonalPanelCreator = function (){
	this.listaDePersonalPanel = new Ext.Panel({
		layout: Ext.is.Phone ? 'fit' : {
		    type: 'vbox',
		    align: 'center',
		    pack: 'center'
		},		
		items: [{		
			width : '100%',
			height: '100%',
			xtype: 'list',
			store: this.listaDePersonalStore,
			itemTpl: '<div class="listaDePersonal"><strong>{nombre}</strong></div>',
			grouped: true,
			indexBar: true,
			listeners : {
				"selectionchange"  : function ( view, nodos, c ){
								
					if(nodos.length > 0){
						Aplicacion.Personal.currentInstance.detallesDeEmpleadoPanelShow( nodos[0] );
					}

					//deseleccinar el cliente
					view.deselectAll();
					
				}
			}
		
		}]
	});
};















/* ********************************************************
	Detalles de Empleado
******************************************************** */


Aplicacion.Personal.prototype.detallesDeEmpleadoPanelShow = function ( e )
{
	
	if(DEBUG){
		console.log("Mostrando detalles de empleado", e);
	}


	this.detallesDeEmpleadoPanelUpdater( e );


	//hacer un setcard manual
	sink.Main.ui.setActiveItem( this.detallesDeEmpleadoPanel , 'slide');
	
}

Aplicacion.Personal.prototype.detallesDeEmpleadoPanelUpdater = function ( empleado )
{
	

	//Aplicacion.Personal.currentInstance.detallesDeEmpleadoPanel.loadRecord(empleado);
	Aplicacion.Personal.currentInstance.detallesDeEmpleadoPanel.getComponent(0).loadRecord(empleado);
};




Aplicacion.Personal.prototype.editarDetallesEmpleadoDespedirBoton = function ()
{
	
	Ext.Msg.confirm( "Prescindir", "&iquest; Esta seguro que desea despedir a este empleado ? ", function (a){
		if(a=="yes"){
			v = Aplicacion.Personal.currentInstance.detallesDeEmpleadoPanel.getComponent(0).getValues();
			v.activo = 0;
			
			Ext.Ajax.request({
				url: 'proxy.php',
				scope : this,
				params : {
					action : 502,
					data : Ext.util.JSON.encode( v )
				},
				success: function(response, opts) {
					try{
						r = Ext.util.JSON.decode( response.responseText );				
					}catch(e){
						Ext.getBody().mask('Error Interno', '', false);
						return POS.error(e);
					}


					

					if( !r.success ){
						return POS.error(r);
					}

					//volver a cargar los datos del personal
					Aplicacion.Personal.currentInstance.listaDePersonalLoad();
					
					setTimeout( "sink.Main.ui.setActiveItem( Aplicacion.Personal.currentInstance.listaDePersonalPanel , 'fade');", 500 );
					

				},
				failure: function( response ){
					POS.error( response );
				}
			});
		}
	});
	
};



/*
 * handler de editar detalles del empleado
 */
Aplicacion.Personal.prototype.editarDetallesEmpleadoBoton = function ()
{

	Aplicacion.Personal.currentInstance.detallesDeEmpleadoPanel.getComponent(0).getComponent(0).setInstructions("Haga los cambios pertinentes.");

	Ext.getCmp("Personal-DetallesEmpleadoID").hide( Ext.anims.slide );
	Ext.getCmp("Personal-DetallesEmpleadoModificar").hide( Ext.anims.fade );
	Ext.getCmp("Personal-DetallesEmpleadoPuesto").hide( Ext.anims.slide );
	Ext.getCmp("Personal-DetallesEmpleadoDespedir").hide(  );
	
	Ext.getCmp("Personal-DetallesEmpleadoGuardar").show(  );
	Ext.getCmp("Personal-DetallesEmpleadoCancelar").show( );

	Aplicacion.Personal.currentInstance.detallesDeEmpleadoPanel.getComponent(0).enable();
	
};



Aplicacion.Personal.prototype.editarDetallesEmpleadoCancelarBoton = function ( override )
{

	if(override)
		Aplicacion.Personal.currentInstance.detallesDeEmpleadoPanel.getComponent(0).getComponent(0).setInstructions("");	
		
	Ext.getCmp("Personal-DetallesEmpleadoID").show( Ext.anims.slide );
	Ext.getCmp("Personal-DetallesEmpleadoModificar").show( Ext.anims.fade );
	Ext.getCmp("Personal-DetallesEmpleadoPuesto").show( Ext.anims.slide );	
	Ext.getCmp("Personal-DetallesEmpleadoDespedir").show(  );	
	Ext.getCmp("Personal-DetallesEmpleadoGuardar").hide( );
	Ext.getCmp("Personal-DetallesEmpleadoCancelar").hide( );

	Aplicacion.Personal.currentInstance.detallesDeEmpleadoPanel.getComponent(0).disable();
	
};


Aplicacion.Personal.prototype.editarDetallesEmpleadoGuardarBoton = function ()
{
	
	Ext.getBody().mask('Modificando datos ...', 'x-mask-loading', true);

	Ext.Ajax.request({
		url: 'proxy.php',
		scope : this,
		params : {
			action : 502,
			data : Ext.util.JSON.encode( Aplicacion.Personal.currentInstance.detallesDeEmpleadoPanel.getComponent(0).getValues() )
		},
		success: function(response, opts) {
			try{
				r = Ext.util.JSON.decode( response.responseText );				
			}catch(e){
				Ext.getBody().mask('Error Interno', '', false);
				return POS.error(e);
			}
			

			Ext.getBody().unmask();	
						
			if( !r.success ){
                
				Aplicacion.Personal.currentInstance.detallesDeEmpleadoPanel.getComponent(0).getComponent(0).setInstructions(r.reason);

				return;
			}

			//hacer como si cancele, para regresar todo a su estado original
			Aplicacion.Personal.currentInstance.editarDetallesEmpleadoCancelarBoton( true );	

			//volver a cargar los datos del personal
			Aplicacion.Personal.currentInstance.listaDePersonalLoad();

			//mostrar un mensaje de que todo ha salido bien
			Aplicacion.Personal.currentInstance.detallesDeEmpleadoPanel.getComponent(0).getComponent(0).setInstructions("Los cambios se han hecho satisfactoriamente !");

		},
		failure: function( response ){
			POS.error( response );
		}
	});
	
};


Aplicacion.Personal.prototype.detallesDeEmpleadoPanel = null;


/*
 * Se llama para crear por primera vez el panel de detalles de cliente
 **/
Aplicacion.Personal.prototype.detallesDeEmpleadoPanelCreator = function (  ){
	
	if(DEBUG){ console.log ("creando panel de detalles de empleado por primera vez"); }
	
	
	this.detallesDeEmpleadoPanel = new Ext.Panel({                                                       
		scroll : "vertical",
		items: [
			new Ext.form.FormPanel({
				title: 'Detalles de Empleado',
				listeners : {
					"beforeshow" : function (){
						//cargar los tipos
						Ext.getCmp("Personal-DetallesEmpleadoTipoSelector").setOptions( Aplicacion.Personal.currentInstance.nuevoEmpleadoTipos );
					}
				},
				items: [{
					xtype: 'fieldset',
				    title: 'Detalles de Cliente',
				    instructions: '',
					defaults : {
						disabled : true
					},
					items: [
						new Ext.form.Text({ name: 'nombre', label: 'Nombre' }),
						new Ext.form.Text({ name : 'activo', label : "activo", hidden : true }),
						new Ext.form.Text({ name : 'id_sucursal', label: 'Sucursal', hidden : true }),
						new Ext.form.Text({ name : 'finger_token', label: 'Token', hidden : true }),
						new Ext.form.Text({ name : 'tipo',     label: 'tipo', hidden : true }),
						new Ext.form.Text({ id: "Personal-DetallesEmpleadoID", name: 'id_usuario', label: 'ID'	}),
						new Ext.form.Text({ id: "Personal-DetallesEmpleadoPuesto", name : 'puesto',     label: 'Puesto' }),
						new Ext.form.Text({ name: 'RFC', label: 'RFC' }),
						new Ext.form.Text({ name : 'direccion', label: 'Direccion' }),
						new Ext.form.Text({ name : 'telefono', label: 'Telefono' }),
						new Ext.form.Text({ name : 'salario',     label: 'Salario' })

					]
				}]
			}),
			new Ext.Button({  id: "Personal-DetallesEmpleadoModificar", ui  : 'drastic', text: 'Modificar Detalles', margin : 15, handler : this.editarDetallesEmpleadoBoton }),
			new Ext.Button({  id: "Personal-DetallesEmpleadoGuardar", ui  : 'confirm', text: 'Guardar', margin : 15, handler : this.editarDetallesEmpleadoGuardarBoton, hidden : true }),
			new Ext.Button({  id: "Personal-DetallesEmpleadoCancelar", ui  : 'decline', text: 'Cancelar', margin : 15, handler : this.editarDetallesEmpleadoCancelarBoton, hidden : true }),
			new Ext.Button({  id: "Personal-DetallesEmpleadoDespedir", ui  : 'decline', text: 'Despedir', margin : 15, handler : this.editarDetallesEmpleadoDespedirBoton })
	]});


};






/* ********************************************************
	Nuevo empleado
******************************************************** */


/*
 * Guarda el panel donde estan la forma de nuevo empleado
 **/
Aplicacion.Personal.prototype.nuevoEmpleadoPanel = null;



Aplicacion.Personal.prototype.crearEmpleadoValidar = function (  )
{
	
	v = Aplicacion.Personal.currentInstance.nuevoEmpleadoPanel.getValues();
	
	
	if( isNaN(v.grupo) || v.grupo.length == 0 ){
		Aplicacion.Personal.currentInstance.nuevoEmpleadoPanel.getComponent(0).setInstructions("Seleccione el rol de este nuevo empleado.");
		return false;		
	}
	
	if(v.nombre.length < 5){
		Aplicacion.Personal.currentInstance.nuevoEmpleadoPanel.getComponent(0).setInstructions("El nombre es muy corto.");		
		return false;
	}
	
	if(v.RFC.length < 10){
		Aplicacion.Personal.currentInstance.nuevoEmpleadoPanel.getComponent(0).setInstructions("El RFC es muy corto.");
		return false;
	}
	
	if(v.grupo == 3){
		
		if(v.contrasena.length < 5){
			Aplicacion.Personal.currentInstance.nuevoEmpleadoPanel.getComponent(0).setInstructions("La contrase&ntilde;a debe ser mayor a 5 caracteres.");	
			return false;
		}
		
		if(v.contrasena != v.contrasena2){
			Aplicacion.Personal.currentInstance.nuevoEmpleadoPanel.getComponent(0).setInstructions("Las contrase&ntilde;as no coinciden.");		
			return false;
		}

		
	}

	
	if( isNaN(v.salario) ){
		Aplicacion.Personal.currentInstance.nuevoEmpleadoPanel.getComponent(0).setInstructions("El salario debe ser un numero.");
		return false;
	}

	Aplicacion.Personal.currentInstance.nuevoEmpleadoPanel.getComponent(0).setInstructions("");	
	return true;
	
}



Aplicacion.Personal.prototype.crearEmpleadoBoton = function (  )
{
	if(DEBUG){
		console.log("Creando empleado");
	}
	
	if(!Aplicacion.Personal.currentInstance.crearEmpleadoValidar()){
		//no paso la validacion

		return;
	}
	
	
	//crear el empleado
	if(DEBUG){
		console.log("Enviando peticion");
	}
	
	
	Ext.getBody().mask('Creando empleado ...', 'x-mask-loading', true);
	v = Aplicacion.Personal.currentInstance.nuevoEmpleadoPanel.getValues();
	v.contrasena = hex_md5( v.contrasena );
	json = Ext.util.JSON.encode( v );
	
	Ext.Ajax.request({
		url: 'proxy.php',
		scope : this,
		params : {
			action : 500,
			data : json
		},
		success: function(response, opts) {
			try{
				r = Ext.util.JSON.decode( response.responseText );				
			}catch(e){
				return POS.error(e);
			}
			

			Ext.getBody().unmask();	
						
			if( !r.success ){
                //aqui entra si el usuario qeu se quiere dar de alta ya habia sido contratado en algun momento
				Aplicacion.Personal.currentInstance.nuevoEmpleadoPanel.getComponent(0).setInstructions(r.reason);
               
                Aplicacion.Personal.currentInstance.reincorporarEmpleado( v, r.id );
				return;
			}
			

		
			//limpiar
			Aplicacion.Personal.currentInstance.nuevoEmpleadoPanel.reset();

			//volver a cargar la lista del personal
			Aplicacion.Personal.currentInstance.listaDePersonalLoad();
			
			//mostrar un mensaje de alegria
			Ext.Msg.alert( "Nueva contratacion", "El nuevo usuario se ha creado satisfactoriamente con el ID <b>"+ r.id_usuario + "</b>" );
			Aplicacion.Personal.currentInstance.nuevoEmpleadoPanel.getComponent(0).setInstructions("El nuevo empleado se ha creado satisfactoriamente con el ID <b>"+ r.id_usuario + "</b>.");	



		},
		failure: function( response ){
			POS.error( response );
		}
	});
	
}

Aplicacion.Personal.prototype.reincorporarEmpleado = function ( v, id_usuario )
{

    Ext.Msg.confirm( "Nuevo Empleado", "&iquest; Ya se tiene registro de este empleado, desea reincorporarlo nuevamente? ", function (a){
        if(a=="yes"){

            v.id_usuario = id_usuario;
            v.activo = 1;

            Ext.Ajax.request({
                url: 'proxy.php',
                scope : this,
                params : {
                    action : 502,
                    data : Ext.util.JSON.encode( v )
                },
                success: function(response, opts) {
                    try{
                        r = Ext.util.JSON.decode( response.responseText );              
                    }catch(e){
                        Ext.getBody().mask('Error Interno', '', false);
                        return POS.error(e);
                    }

                    if( !r.success ){
                        return POS.error(r);
                    }

                    //limpiar
                    Aplicacion.Personal.currentInstance.nuevoEmpleadoPanel.reset();

                    //volver a cargar los datos del personal
                    Aplicacion.Personal.currentInstance.listaDePersonalLoad();
                    
                    setTimeout( "sink.Main.ui.setActiveItem( Aplicacion.Personal.currentInstance.listaDePersonalPanel , 'fade');", 500 );
                    

                },
                failure: function( response ){
                    POS.error( response );
                }
            });
        }
    });
    
};

/*
 * Gurada una estructura con los tipos de empleado que hay en el sistema
 */
Aplicacion.Personal.prototype.nuevoEmpleadoTipos = [];




/*
 * Cargar los tipos de empleo que se pueden hacer para mostrarlos en el la lista
 *  
 **/
Aplicacion.Personal.prototype.nuevoEmpleadoGargarTipos = function ( )
{
	
	Ext.Ajax.request({
		url: 'proxy.php',
		scope : this,
		params : {
			action : 504
		},
		success: function(response, opts) {
			try{
				r = Ext.util.JSON.decode( response.responseText );				
			}catch(e){
				return POS.error(e);
			}
	
						
			if( !r.success ){
				return;
			}

			Aplicacion.Personal.currentInstance.nuevoEmpleadoTipos = r.datos;

		},
		failure: function( response ){
			POS.error( response );
		}
	});
	
	
};

Aplicacion.Personal.prototype.nuevoEmpleadoCambiarTipo = function ( a, v )
{
	

	if( v == 3 ){
		Ext.getCmp("Personal-NuevoEmpleadoPass").show(Ext.anims.slide);
		Ext.getCmp("Personal-NuevoEmpleadoPass2").show(Ext.anims.slide);
	}else{
		Ext.getCmp("Personal-NuevoEmpleadoPass").hide(Ext.anims.slide);
		Ext.getCmp("Personal-NuevoEmpleadoPass2").hide(Ext.anims.slide);
	}
	
};





/*
 * Se llama para crear por primera vez el panel de nuevo cliente
 **/
Aplicacion.Personal.prototype.nuevoEmpleadoPanelCreator = function (  ){
	if(DEBUG){ console.log ("creando panel de nuevo cliente"); }
	
	
	this.nuevoEmpleadoPanel = new Ext.form.FormPanel({                                                       
		listeners : {
			"show" : function (){
				//cargar los tipos
				Ext.getCmp("Personal-NuevoEmpleadoTipoSelector").setOptions( Aplicacion.Personal.currentInstance.nuevoEmpleadoTipos );
				Ext.getCmp("Personal-NuevoEmpleadoTipoSelector").setOptions( Aplicacion.Personal.currentInstance.nuevoEmpleadoTipos );
			}
		},
		items: [{
			xtype: 'fieldset',
		    title: 'Contratacion de un nuevo empleado',
		    instructions: 'Introdusca los detalles del nuevo empleado.',
			items: [
				{
					id : "Personal-NuevoEmpleadoTipoSelector",
					xtype: 'selectfield',
					name: 'grupo',
					label : "Tipo", 
					options: [ {text : "Seleccione el nuevo rol que cumplira este empleado", value : null } ],
					listeners : {
						"change" : function(a,b) {Aplicacion.Personal.currentInstance.nuevoEmpleadoCambiarTipo(a,b);}
					}
				},
				new Ext.form.Text({ name: 'nombre', label: 'Nombre' }),
				new Ext.form.Text({ name: 'RFC', label: 'RFC' }),
				new Ext.form.Text({ id : "Personal-NuevoEmpleadoPass", name : 'contrasena', label: 'Contrase&ntilde;a' , hidden : true }),
				new Ext.form.Text({ id : "Personal-NuevoEmpleadoPass2", name : 'contrasena2', label: 'Repetir contrase&ntilde;a', hidden : true }),				
				new Ext.form.Text({ name : 'salario',     label: 'Salario' }),
				new Ext.form.Text({ name : 'direccion',     label: 'direccion' }),
				new Ext.form.Text({ name : 'telefono',     label: 'telefono' })


			]},
			
			new Ext.Button({ id : 'Personal-CrearEmpleado', ui  : 'action', text: 'Contratar', margin : 15,  handler : this.crearEmpleadoBoton, disabled : false }),
	]});


	
};

















POS.Apps.push( new Aplicacion.Personal() );






