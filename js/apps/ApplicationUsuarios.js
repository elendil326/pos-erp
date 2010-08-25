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
        	return record.get('id_usuario')[0];
    	}
	});
	
	

	var homeToolbar = new Ext.Toolbar({
	
			ui: 'dark',
			dock: 'bottom',
			items: [{ xtype: 'spacer'},
			{
				xtype: 'button',
				ui: 'action',
				text: 'Agregar nuevo usuario',
				handler: function(){
					//alert("showing form..");
					ApplicationUsuarios.currentInstance.addNewUser();
				}
				
			}]
	});

	this.homePanel = new Ext.Panel({
		
			id: 'ApplicationUsuarios-homePanel',
			layout: 'card',
			dockedItems: homeToolbar,
			listeners: {
			beforeshow : function(component){
		
					POS.AJAXandDECODE(//Parametros
									{
										action : 2302,
										id_sucursal : ApplicationUsuarios.currentInstance.id_sucursal
										
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

			}//fin before
			
		},
		items: [{
        	width: '100%',
        	height: '100%',
        	xtype: 'list',
			baseCls : 'ApplicationInventario-mainPanel',
			loadingText: 'Cargando datos...',
			emptyText: '<div class="no-data">No se encontraron usuarios para esta sucursal.</div>',
        	store: ApplicationUsuarios.currentInstance.UserListStore,
        	tpl: String.format('<tpl for="."><div class="products">ID: {id_usuario} <strong>{nombre}</strong> &nbsp;Usuario: {usuario} </div></tpl>' ),
        	itemSelector: 'div.products',
        	singleSelect: true
    	}]
	
		
	
	});




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
					
					POS.aviso('Éxito', 'Se agrego el nuevo usuario existosamente');
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
										id_sucursal : ApplicationUsuarios.currentInstance.id_sucursal
										
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


POS.AJAXandDECODE(
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
) ;
