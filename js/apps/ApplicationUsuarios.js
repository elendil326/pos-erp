ApplicationUsuarios = function ()
{
	if(DEBUG){
		console.log("ApplicationUsuarios: construyendo");
	}
	
	ApplicationUsuarios.currentInstance = this;
	
	this._init();
	
	return this;
};

//aqui va el panel principal 
ApplicationUsuarios.prototype.mainCard = null;

//aqui va el nombre de la applicacion
ApplicationUsuarios.prototype.appName = null;

//aqui va el nombre de la applicacion
ApplicationUsuarios.prototype.leftMenuItems = null;

//aqui va un texto de ayuda en html
ApplicationUsuarios.prototype.ayuda = null;

//Panel de inicio
ApplicationUsuarios.prototype.homePanel = null;

//ID de sucursal
ApplicationUsuarios.prototype.id_sucursal = null;

//Usuario seleccionado en la lista
ApplicationUsuarios.prototype.user_selected = null;

//Botones del toolbar
ApplicationUsuarios.prototype.toolbar_buttons = null;


/* -------------------------------------------------------------------------------------
			init
   ------------------------------------------------------------------------------------- */
ApplicationUsuarios.prototype._init = function()
{
	//Obtenemos la id de la sucursal
	this.loadSucursalID();

	//nombre de la aplicacion
	this.appName = "Usuarios";
	
	//ayuda sobre esta applicacion
	this.ayuda = 'Se pueden manipular los datos de los usuarios del programa';
	
	//instancia this.homePanel
	this.loadHomePanel();

	//tarjeta principal
	this.mainCard = this.homePanel;
};

//register model
Ext.regModel('appUsuarios_usuarios', {
    fields: ['id_usuario','nombre', 'usuario', 'id_sucursal']
});


ApplicationUsuarios.prototype.loadSucursalID = function(){
	
	
	POS.AJAXandDECODE({
		action: 1607//'obtenerSucursalUsuario'
	}, function(result){
		if (result.success){
			ApplicationUsuarios.currentInstance.id_sucursal = result.id_sucursal;
			//ApplicationInventario.currentInstance.initSucursalPanel(result.datos[0].sucursal_id);
		}
		//ApplicationInventario.currentInstance.sucursal_id 
	}, function(){
		if(DEBUG){
				console.error("ApplicationUsuarios.loadSucursalID: Failed Ajax");
			}
	});
};




/* -------------------------------------------------------------------------------------
			Panel Principal
   ------------------------------------------------------------------------------------- */

ApplicationUsuarios.prototype.loadHomePanel = function()
{

	//Store para la lista de usuarios de la sucursal
	this.UserListStore = new Ext.data.Store({
    	model: 'appUsuarios_usuarios',
    	sorters: 'nombre',
    	getGroupString : function(record) {
        	return record.get('nombre')[0];
    	}
	});
	
	ApplicationUsuarios.currentInstance.toolbar_buttons = [{
				id: 'ApplicationUsuarios-homePanel-modificar',
				xtype: 'button',
				text: 'Modificar usuario',
				disabled: true,
				handler: function(){
					ApplicationUsuarios.currentInstance.modifyUser();
			}
			},{
				id: 'ApplicationUsuarios-homePanel-eliminar',
				xtype: 'button',
				text: 'Eliminar usuario',
				disabled: true,
				ui: 'drastic',
				handler: function(){
					ApplicationUsuarios.currentInstance.removeUser();
				}
			},{ xtype: 'spacer'},
			{
				xtype: 'button',
				ui: 'action',
				text: 'Agregar nuevo usuario',
				handler: function(){
					//alert("showing form..");
					ApplicationUsuarios.currentInstance.addNewUser();
				}
				
			}];

	var homeToolbar = new Ext.Toolbar({
			id: 'ApplicationUsuarios-homePanel-toolbar',
			ui: 'dark',
			dock: 'bottom',
			items: [ ApplicationUsuarios.currentInstance.toolbar_buttons ]
	});

	//console.log(ApplicationUsuarios.currentInstance.UserListStore);
	
	this.homePanel = new Ext.Panel({
		
			id: 'ApplicationUsuarios-homePanel',
			layout: 'card',
			dockedItems: homeToolbar,
			listeners: {
			beforeshow : function(component){
		
					POS.AJAXandDECODE(//Parametros
									{
										action : 2302,
										id_sucursal : ApplicationUsuarios.currentInstance.id_sucursal,
										activo: 1
										
									}, 
							function(datos){
								//responded 
								
								if (datos.success) {
									 
									//si el success trae true
									usuarios = datos.data;
									ApplicationUsuarios.currentInstance.UserListStore.loadData(usuarios, false);
									
								} else {
									ApplicationUsuarios.currentInstance.UserListStore.loadData(0);
									return 0;
								}
					
							}, function(){
								//no responde
								ApplicationUsuarios.currentInstance.UserListStore.loadData(0);
								
								if(DEBUG){
									console.error("ApplicationUsuarios: no server response");
								}
							}
					);

			}//fin before
			
		},
		items: [{
			id: 'ApplicationUsuarios-lista',
        	width: '100%',
        	height: '100%',
        	xtype: 'list',
			baseCls : 'ApplicationUsuarios-homePanel',
			loadingText: 'Cargando datos...',
			emptyText: '<div class="no-data">No se encontraron usuarios para esta sucursal.</div>',
        	store: ApplicationUsuarios.currentInstance.UserListStore,
        	tpl: String.format('<tpl for="."><div class="usuarios">ID: {id_usuario} <strong>{nombre}</strong> &nbsp;Usuario: {usuario} </div></tpl>' ),
			//tplWriteMode: 'insertFirst',
        	itemSelector: 'div.usuarios',
			grouped: true,
        	singleSelect: true,
			listeners: {
				selectionchange: function(){
						
						if ( this.getSelectionCount() > 0)
						{
							//Habilitamos el boton si hay algo seleccionado
							if( Ext.getCmp('ApplicationUsuarios-homePanel-modificar') != undefined && Ext.getCmp('ApplicationUsuarios-homePanel-eliminar') != undefined )
							{
								Ext.getCmp('ApplicationUsuarios-homePanel-modificar').setDisabled(false);
								Ext.getCmp('ApplicationUsuarios-homePanel-eliminar').setDisabled(false);
							}
							
							ApplicationUsuarios.currentInstance.user_selected = this.getSelectedRecords();
						}
						else
						{
							if( Ext.getCmp('ApplicationUsuarios-homePanel-modificar') != undefined && Ext.getCmp('ApplicationUsuarios-homePanel-eliminar') != undefined )
							{
								Ext.getCmp('ApplicationUsuarios-homePanel-modificar').setDisabled(true);
								Ext.getCmp('ApplicationUsuarios-homePanel-eliminar').setDisabled(true);
							}
						}
				}
			}
    	}]
	
		
	
	});


	//Ext.getCmp('ApplicationUsuarios-lista').update();

}

/* -------------------------------------------------------------------------------------
			Agregar nuevo usuario
   ------------------------------------------------------------------------------------- */

ApplicationUsuarios.prototype.addNewUser = function(){
	
	if ( Ext.getCmp('ApplicationUsuarios-addNewUser-panel') != null)
	{
		Ext.getCmp('ApplicationUsuarios-addNewUser-panel').show();
		return;
	}
	
	var addUserTB = new Ext.Toolbar({
		dock: 'top',
		title: 'Agregar',
		items:[{
			xtype: 'spacer'
		},{
			xtype: 'button',
			text: 'Aceptar',
			ui: 'action',
			handler: function(){
			
				var formData = Ext.getCmp('ApplicationUsuarios-addNewUser-form').getValues();
	
				//Comprobamos que el password sea el mismo
				if ( formData['password'] != formData['password2'] )
				{
					alert( "Los passwords deben ser iguales" );
					return;
				}
			
				if ( formData['user2'] != "" && formData['nombre'] != "" && formData['password'] != "" && formData['password2'] != "" )
				{
					ApplicationUsuarios.currentInstance.addNewUserLogic();
				}
				else
				{
					alert("Debe llenar todos los campos");
					return;
				}
				
			}
		}]
	})
	
	
	var addUserPanel = new Ext.Panel({
		
		id: 'ApplicationUsuarios-addNewUser-panel',
		floating: true,
		centered: true,
		modal: true,
		height: 400,
		width: 450,
		layout: 'fit',
		dockedItems: addUserTB,
		items: [new Ext.form.FormPanel({
			id: 'ApplicationUsuarios-addNewUser-form',
			scroll: 'vertical',
			
			items: [{
				xtype: 'fieldset',
				title: 'Nuevo usuario',
				instructions: '* Campos obligatorios',
				items: [{
					xtype: 'textfield',
					label: 'Nombre',
					name: 'nombre',
					required: true
				}, {
					xtype: 'textfield',
					label: 'Usuario',
					name: 'user2',
					required: true
				}, {
					xtype: 'textfield',
					inputType: 'password',
					label: 'Password',
					name: 'password',
					required: true
				}, {
					xtype: 'textfield',
					inputType: 'password',
					label: 'Confirmar password',
					name: 'password2',
					required: true
				}]
			}]
		})
		
		]
		
		
	});
	
	addUserPanel.show();
	
};


ApplicationUsuarios.prototype.addNewUserLogic = function(){

	var formData = Ext.getCmp('ApplicationUsuarios-addNewUser-form').getValues();
	
	var username = formData['user2'];
	
	var validateUsername = /[^A-Za-z0-9_@\.]|@{2,}|\.{5,}/;
	if ( validateUsername.test(username) )
	{
		alert("Se encontraron caracteres no validos en usuario");
		return;
	}
	POS.AJAXandDECODE(
			//Parametros
			{
				action: 2301,//'insertUser',
				nombre: formData['nombre'],
				user2: formData['user2'],
				password: formData['password'],
				sucursal: ApplicationUsuarios.currentInstance.id_sucursal,
				acceso: 3
			},
			//Responded
			function(result)
			{
				
				if ( result.success )
				{
					Ext.getCmp('ApplicationUsuarios-addNewUser-form').reset();
					Ext.getCmp('ApplicationUsuarios-addNewUser-panel').hide();
					
					//POS.aviso('Éxito', 'Se agrego el nuevo usuario existosamente');
					
					Ext.getCmp('ApplicationUsuarios-homePanel-toolbar').removeAll();
					Ext.getCmp('ApplicationUsuarios-homePanel-toolbar').setTitle("Se agrego el nuevo usuario");
					Ext.getCmp('ApplicationUsuarios-homePanel-toolbar').showTitle();
					setTimeout( "ApplicationUsuarios.currentInstance.restoreToolbar( ApplicationUsuarios.currentInstance.toolbar_buttons, 'ApplicationUsuarios-homePanel-toolbar')", 3000 );
					
					
					//['denominacion', 'existencias', 'precio_venta', 'min']
					/*ApplicationUsuarios.currentInstance.UserListStore.add(
						{
							denominacion: formData['denominacion'],
							existencias: 0,
							precio_venta: formData['precio_venta'],
							min: formData['min']
						}
					
					);*/
					POS.AJAXandDECODE(//Parametros
									{
										action : 2302,
										id_sucursal : ApplicationUsuarios.currentInstance.id_sucursal,
										activo: 1
										
									}, 
							function(datos){
								//responded 
								
								if (datos.success) {
									 
									//si el success trae true
									usuarios = datos.data;
									ApplicationUsuarios.currentInstance.UserListStore.loadData(usuarios);
									
								} else {
									ApplicationUsuarios.currentInstance.UserListStore.loadData(0);
									return 0;
								}
					
							}, function(){
								//no responde
								ApplicationUsuarios.currentInstance.UserListStore.loadData(0);
								
								if(DEBUG){
									console.error("ApplicationUsuarios: no server response");
								}
							}
					);
				}
				else{
					POS.aviso('Error', 'Error al intentar insertar el nuevo usuario');
				}
				
			},
			//Not responded
			function()
			{
				POS.aviso('Error', 'Error en la conexión. Intente nuevamente')
			}
	);
	
	
	
};


/* -------------------------------------------------------------------------------------
			Modificar usuario existente
   ------------------------------------------------------------------------------------- */

ApplicationUsuarios.prototype.modifyUser = function(){

	if ( Ext.getCmp('ApplicationUsuarios-modifyUser-panel') != null)
	{
		Ext.getCmp('ApplicationUsuarios-modifyUser-panel').show();
		return;
	}
	
	if (DEBUG)
	{
		console.log(ApplicationUsuarios.currentInstance.user_selected[0].data.nombre);
	}
	
	var modifyUserTB = new Ext.Toolbar({
		dock: 'top',
		title: 'Modificar datos',
		items:[{
			xtype: 'spacer'
		},{
			id: 'ApplicationUsuarios-modifyUserTB-aceptar',
			xtype: 'button',
			text: 'Aceptar',
			disabled: true,
			ui: 'action',
			handler: function(){
				
				var formData = Ext.getCmp('ApplicationUsuarios-modifyUser-form').getValues();
	
				//Comprobamos que el password sea el mismo
				if ( formData['password'] != formData['password2'] )
				{
					alert( "Los passwords deben ser iguales" );
					return;
				}
			
				if ( formData['user2'] != "" && formData['nombre'] != "" && formData['password'] != "" && formData['password2'] != "" )
				{
					ApplicationUsuarios.currentInstance.modifyUserLogic();
				}
				else
				{
					alert("Debe llenar todos los campos");
					return;
				}
				
			}
		}]
	})
	

	
	var modifyUserPanel = new Ext.Panel({
		
		id: 'ApplicationUsuarios-modifyUser-panel',
		floating: true,
		centered: true,
		modal: true,
		height: 400,
		width: 450,
		layout: 'fit',
		dockedItems: modifyUserTB,
		items: [new Ext.form.FormPanel({
			id: 'ApplicationUsuarios-modifyUser-form',
			scroll: 'vertical',
			
			items: [{
				xtype: 'fieldset',
				title: 'Modificar datos de usuario',
				items: [{
					id: 'ApplicationUsuarios-modifyUser-form-nombre',
					xtype: 'textfield',
					label: 'Nombre',
					name: 'nombre',
					value: ApplicationUsuarios.currentInstance.user_selected[0].data.nombre,
					listeners: {
						change: function()
						{
							Ext.getCmp('ApplicationUsuarios-modifyUserTB-aceptar').setDisabled(false);
						}
					}
				}, {
					id: 'ApplicationUsuarios-modifyUser-form-usuario',
					xtype: 'textfield',
					label: 'Usuario',
					name: 'user2',
					value: ApplicationUsuarios.currentInstance.user_selected[0].data.usuario,
					listeners: {
						change: function()
						{
							Ext.getCmp('ApplicationUsuarios-modifyUserTB-aceptar').setDisabled(false);
						}
					}
				}, {
					xtype: 'textfield',
					inputType: 'password',
					label: 'Password',
					name: 'password',
					listeners: {
						change: function()
						{
							Ext.getCmp('ApplicationUsuarios-modifyUserTB-aceptar').setDisabled(false);
						}
					}
				}, {
					xtype: 'textfield',
					inputType: 'password',
					label: 'Confirmar password',
					name: 'password2',
					listeners: {
						change: function()
						{
							Ext.getCmp('ApplicationUsuarios-modifyUserTB-aceptar').setDisabled(false);
						}
					}
				}]
			}]
		})
		
		],
			listeners: {
				beforeshow: function(){
					Ext.getCmp('ApplicationUsuarios-modifyUser-form-nombre').setValue(ApplicationUsuarios.currentInstance.user_selected[0].data.nombre);
					Ext.getCmp('ApplicationUsuarios-modifyUser-form-usuario').setValue(ApplicationUsuarios.currentInstance.user_selected[0].data.usuario);
				}
			}
		
		
	});
	
	modifyUserPanel.show();
	


}


ApplicationUsuarios.prototype.modifyUserLogic = function(){

	var formData = Ext.getCmp('ApplicationUsuarios-modifyUser-form').getValues();
	
	
	POS.AJAXandDECODE(
			//Parametros
			{
				action: 2304,//'modifyUser',
				nombre: formData['nombre'],
				user2: formData['user2'],
				password: formData['password'],
				id_usuario: ApplicationUsuarios.currentInstance.user_selected[0].id_usuario
			},
			//Responded
			function(result)
			{
				
				if ( result.success )
				{
					Ext.getCmp('ApplicationUsuarios-modifyUser-form').reset();
					Ext.getCmp('ApplicationUsuarios-modifyUser-panel').hide();
					ApplicationUsuarios.currentInstance.user_selected[0].nombre = formData['nombre'];
					ApplicationUsuarios.currentInstance.user_selected[0].usuario = formData['user2'];
					//POS.aviso('Éxito', 'Se modificaron los datos correctamente');
					Ext.getCmp('ApplicationUsuarios-homePanel-toolbar').removeAll();
					Ext.getCmp('ApplicationUsuarios-homePanel-toolbar').setTitle("Se modificaron los datos correctamente");
					Ext.getCmp('ApplicationUsuarios-homePanel-toolbar').showTitle();
					setTimeout( "ApplicationUsuarios.currentInstance.restoreToolbar( ApplicationUsuarios.currentInstance.toolbar_buttons, 'ApplicationUsuarios-homePanel-toolbar')", 3000 );
					//['denominacion', 'existencias', 'precio_venta', 'min']
					/*ApplicationUsuarios.currentInstance.UserListStore.add(
						{
							denominacion: formData['denominacion'],
							existencias: 0,
							precio_venta: formData['precio_venta'],
							min: formData['min']
						}
					
					);*/
					POS.AJAXandDECODE(//Parametros
									{
										action : 2302,
										id_sucursal : ApplicationUsuarios.currentInstance.id_sucursal,
										activo: 1
										
									}, 
							function(datos){
								//responded 
								
								if (datos.success) {
									 
									//si el success trae true
									usuarios = datos.data;
									ApplicationUsuarios.currentInstance.UserListStore.loadData(usuarios);
									
								} else {
									ApplicationUsuarios.currentInstance.UserListStore.loadData(0);
									return 0;
								}
					
							}, function(){
								//no responde
								ApplicationUsuarios.currentInstance.UserListStore.loadData(0);
								
								if(DEBUG){
									console.error("ApplicationUsuarios: no server response");
								}
							}
					);
				}
				else{
					POS.aviso('Error', 'Error al intentar actualizar los datos del usuario');
				}
				
			},
			//Not responded
			function()
			{
				POS.aviso('Error', 'Error en la conexión. Intente nuevamente')
			}
	);
	
	

}


/* -------------------------------------------------------------------------------------
			Eliminar usuario
   ------------------------------------------------------------------------------------- */

ApplicationUsuarios.prototype.removeUser = function(){


	var user = ApplicationUsuarios.currentInstance.user_selected;
	
	Ext.getCmp('ApplicationUsuarios-homePanel-toolbar').removeAll();
	
	Ext.getCmp('ApplicationUsuarios-homePanel-toolbar').setTitle("&iquest;Esta seguro?");
	Ext.getCmp('ApplicationUsuarios-homePanel-toolbar').showTitle();
	
	var toolbarContent = [{
		xtype: 'button',
		text: 'Cancelar',
		handler: function(){
		
			Ext.getCmp('ApplicationUsuarios-homePanel-toolbar').removeAll();
	
			Ext.getCmp('ApplicationUsuarios-homePanel-toolbar').hideTitle();
		
			Ext.getCmp('ApplicationUsuarios-homePanel-toolbar').add(	[{
				id: 'ApplicationUsuarios-homePanel-modificar',
				xtype: 'button',
				text: 'Modificar usuario',
				disabled: true,
				handler: function(){
					ApplicationUsuarios.currentInstance.modifyUser();
			}
			},{
				id: 'ApplicationUsuarios-homePanel-eliminar' ,
				xtype: 'button',
				text: 'Eliminar usuario',
				ui: 'drastic',
				disabled: true,
				handler: function(){
					ApplicationUsuarios.currentInstance.removeUser();
				}
			},{ xtype: 'spacer'},
			{
				xtype: 'button',
				ui: 'action',
				text: 'Agregar nuevo usuario',
				handler: function(){
					//alert("showing form..");
					ApplicationUsuarios.currentInstance.addNewUser();
				}
				
			}] );
			
			Ext.getCmp('ApplicationUsuarios-homePanel-toolbar').doLayout();
			}
		},
		{
			xtype: 'spacer'
		},{
			xtype: 'button',
			text: 'OK',
			ui: 'action',
			handler: function(){
				ApplicationUsuarios.currentInstance.removeUserLogic();
			}
		}
	];
	
	Ext.getCmp('ApplicationUsuarios-homePanel-toolbar').add( toolbarContent );
	Ext.getCmp('ApplicationUsuarios-homePanel-toolbar').doLayout();

}

ApplicationUsuarios.prototype.removeUserLogic = function ()
{
	
	var user = ApplicationUsuarios.currentInstance.user_selected;
	
	
	POS.AJAXandDECODE(
			//Parametros
			{
				action: 2305,//'desactivarUsuario',
				id_usuario: user[0].data.id_usuario
			},
			//Responded
			function(result)
			{
				
				if ( result.success )
				{

					//POS.aviso('Éxito', 'Se modificaron los datos correctamente');
					Ext.getCmp('ApplicationUsuarios-homePanel-toolbar').removeAll();
					Ext.getCmp('ApplicationUsuarios-homePanel-toolbar').setTitle("Se borraron los datos correctamente");
					Ext.getCmp('ApplicationUsuarios-homePanel-toolbar').showTitle();
					setTimeout( "ApplicationUsuarios.currentInstance.restoreToolbar( ApplicationUsuarios.currentInstance.toolbar_buttons, 'ApplicationUsuarios-homePanel-toolbar')", 3000 );
					//['denominacion', 'existencias', 'precio_venta', 'min']
					/*ApplicationUsuarios.currentInstance.UserListStore.add(
						{
							denominacion: formData['denominacion'],
							existencias: 0,
							precio_venta: formData['precio_venta'],
							min: formData['min']
						}
					
					);*/
					POS.AJAXandDECODE(//Parametros
									{
										action : 2302,
										id_sucursal : ApplicationUsuarios.currentInstance.id_sucursal,
										activo: 1
										
										
									}, 
							function(datos){
								//responded 
								
								if (datos.success) {
									 
									//si el success trae true
									usuarios = datos.data;
									ApplicationUsuarios.currentInstance.UserListStore.loadData(usuarios);
									
								} else {
									ApplicationUsuarios.currentInstance.UserListStore.loadData(0);
									return 0;
								}
					
							}, function(){
								//no responde
								ApplicationUsuarios.currentInstance.UserListStore.loadData(0);
								
								if(DEBUG){
									console.error("ApplicationUsuarios: no server response");
								}
							}
					);
				}
				else{
					POS.aviso('Error', 'Error al intentar borrar los datos del usuario');
				}
				
			},
			//Not responded
			function()
			{
				POS.aviso('Error', 'Error en la conexión. Intente nuevamente')
			}
	);
	
	
	
}

ApplicationUsuarios.prototype.restoreToolbar = function ( buttons, toolbar_id )
{
	var toolbar = Ext.getCmp( toolbar_id );
	
	toolbar.hideTitle();
	toolbar.add( buttons );
	toolbar.doLayout();

}


/*POS.AJAXandDECODE(
				//Parametros
				{action: 2303},
				//Server responded
				function( datos ) {
				
					if ( datos.success )
					{
						
						if ( datos.datos != 3 )
						{
							//autoinstalar esta applicacion
							AppInstaller( new ApplicationUsuarios() );
						}
					}
				
				}
) ;*/
