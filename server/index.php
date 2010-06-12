
<html>
<head>
<title>PROBANDO CLASSES</title>
<link rel="stylesheet" type="text/css" href="../www/ext3/resources/css/ext-all.css"/>
<script type="text/javascript" src="../www/ext3/adapter/ext/ext-base.js"></script>
<script type="text/javascript" src="../www/ext3/ext-all-debug.js"></script>
<style>
.milton-icon {
background: url(milton-head-icon.png) no-repeat;
}
</style>


<script type="text/javascript"> 
Ext.onReady(function(){
	Ext.QuickTips.init();

/* --> CLIENTES  <--- */	
	var txt_rfc = new Ext.form.TextField({
		fieldLabel: 'RFC',
		name: 'txt_rfc',
		allowBlank: false,
		}); 

	var txt_nombre = new Ext.form.TextField({
		fieldLabel: 'NOMBRE',
		allowBlank: true,
		name : 'txt_nombre'
    });
	
	var txt_direccion = new Ext.form.TextField({
		fieldLabel: 'DIRECCION',
		allowBlank: true,
		name : 'txt_direccion'
    });

	var txt_telefono = new Ext.form.TextField({
		fieldLabel: 'TELEFONO',
		allowBlank: true,
		name : 'txt_telefono'
    });//txtDireccion
	var txt_e_mail = new Ext.form.TextField({
		fieldLabel: 'E-MAIL',
		allowBlank: true,
		name : 'txt_e_mail'
    });//txtDireccion

	var txt_limite_credito = new Ext.form.TextField({
		fieldLabel: 'LIMITE DE CREDITO',
		allowBlank: true,
		name : 'txt_limite_credito'
    });//txtDireccion


//	var storeClientes = function(){
		var clientes=  new Ext.data.JsonStore({
			url: 'pos/funcionesCliente.php?action=list',
			root: 'datos',
			fields: [{name:'id_cliente',type: 'string'},{name:'nombre',type: 'string'}]
		});//Datos desde la BD con respuesta del servidor php para un combo
		//return clientes;
	//}
	
	
	var combo= new Ext.form.ComboBox({
		fielLabel: 'Combo',
		mode: 'remote',
		triggerAction: 'all',
		lazyRender:true,
		typeAhead: true,
		title: 'CLIENTES',
		store: clientes, //origen de datos (lista)
		displayField: 'nombre', //como el DisplayMember de c#
		valueField:'id_cliente', //como el valueMember en c#
		name: 'combo',
		listeners: { //eventos del combo (listeners)
			select: function(f,r,i){//evento select
				if (i >= 0){
					
						var idCliente=r.get('id_cliente');
						Ext.Ajax.request({ //peticion al servidor 'AJAXSazo'
						url: 'pos/funcionesCliente.php?action=showClient', //script
						success: function(result) //propiedad que regresa el script php (JSON flag)
						{
							var data = Ext.util.JSON.decode( result.responseText ); //se guarda en esta variable local data la decodificacion JSON q regresa el servidor php
				
							if ( data.success ){ //data es la variable local JS y success es la propiedad que devuelve el JSON desde PHP
								txt_rfc.setValue(data.datos[0].rfc); //se devuelve un arreglo con n tuplas, aqui se hace referencia ala variable local data en la propiedad data (que es un arreglo) en su campo estado (que es el campo de la tabla de la BD)
								txt_nombre.setValue(data.datos[0].nombre);
								txt_direccion.setValue(data.datos[0].direccion);
								txt_telefono.setValue(data.datos[0].telefono);
								txt_e_mail.setValue(data.datos[0].e_mail);
								txt_limite_credito.setValue(data.datos[0].limite_credito);
							}
						},
						failure: function()
						{
							Ext.Msg.alert("ERROR!!!","ERROR");
						},
						params: { //parametros que se pasaran por el metodo post al script php
							id: idCliente //nombre del parametro= id, valor = variable local idW
						}
					}); //AJAX REQUEST
				}//if i>0
			}
		}
	});//combo Lista Boys

			
	var btnGuardar = new Ext.Button({
		text: 'ACTUALIZAR',
		handler: function(){
						var idCliente=combo.getValue();
						Ext.Ajax.request({ //peticion al servidor 'AJAXSazo'
						url: 'pos/funcionesCliente.php?action=update', //script
						success: function(result) //propiedad que regresa el script php (JSON flag)
						{
							var data = Ext.util.JSON.decode( result.responseText ); //se guarda en esta variable local data la decodificacion JSON q regresa el servidor php
				
							if ( data.success ){ //data es la variable local JS y success es la propiedad que devuelve el JSON desde PHP								
								Ext.Msg.alert("YEAH!!!","ACTUALIZADO");
								clientes.load();
							}
						},
						failure: function()
						{
							Ext.Msg.alert("ERROR!!!","ERROR");
						},
						params: { //parametros que se pasaran por el metodo post al script php
							id: combo.getValue(), //nombre del parametro= id, valor = variable local id
							rfc: txt_rfc.getValue(),
							nombre: txt_nombre.getValue(),
							direccion: txt_direccion.getValue(),
							telefono: txt_telefono.getValue(),
							e_mail: txt_e_mail.getValue(),
							limite_credito: txt_limite_credito.getValue()
						}
					}); //AJAX REQUEST
					
				}
	});//fin boton guardar
	
	var btnInsertar = new Ext.Button({
		text: 'AGREGAR',
		handler: function(){
						var idCliente=combo.getValue();
						Ext.Ajax.request({ //peticion al servidor 'AJAXSazo'
						url: 'pos/funcionesCliente.php?action=insert', //script
						success: function(result) //propiedad que regresa el script php (JSON flag)
						{
							var data = Ext.util.JSON.decode( result.responseText ); //se guarda en esta variable local data la decodificacion JSON q regresa el servidor php
				
							if ( data.success ){ //data es la variable local JS y success es la propiedad que devuelve el JSON desde PHP								
								Ext.Msg.alert("YEAH!!!","INSERTADO!!!!!!");
								clientes.load();	
							}
						},
						failure: function()
						{
							Ext.Msg.alert("ERROR!!!","ERROR");
						},
						params: { //parametros que se pasaran por el metodo post al script php
							 //nombre del parametro= id, valor = variable local id
							rfc: txt_rfc.getValue(),
							nombre: txt_nombre.getValue(),
							direccion: txt_direccion.getValue(),
							telefono: txt_telefono.getValue(),
							e_mail: txt_e_mail.getValue(),
							limite_credito: txt_limite_credito.getValue()
						}
					}); //AJAX REQUEST
					
				}
	});//fin boton insertar
	
	
	var btnBorrar = new Ext.Button({
		text: 'BORRAR',
		handler: function(){
						var idCliente=combo.getValue();
						Ext.Ajax.request({ //peticion al servidor 'AJAXSazo'
						url: 'pos/funcionesCliente.php?action=delete', //script
						success: function(result) //propiedad que regresa el script php (JSON flag)
						{
							var data = Ext.util.JSON.decode( result.responseText ); //se guarda en esta variable local data la decodificacion JSON q regresa el servidor php
				
							if ( data.success ){ //data es la variable local JS y success es la propiedad que devuelve el JSON desde PHP								
								Ext.Msg.alert("YEAH!!!","BORRADO!!!!!!");
								clientes.load();
								txt_rfc.setValue('');
								txt_nombre.setValue('');
								txt_direccion.setValue('');
								txt_telefono.setValue('');
								txt_e_mail.setValue('');
								txt_limite_credito.setValue('');
							}
						},
						failure: function()
						{
							Ext.Msg.alert("ERROR!!!","ERROR");
						},
						params: { //parametros que se pasaran por el metodo post al script php
							id: combo.getValue()
						}
					}); //AJAX REQUEST
					
				}
	});//fin boton borrar
	
	
	var btnReset = new Ext.Button({
		text: 'RESET',
		handler: function(){
				form1.getForm().reset();	
		}
	});
	
	var form1= new Ext.FormPanel({
		url: '',
		frame: true,
		title: 'Clientes',
		width: 400,
		items: [combo,txt_rfc,txt_nombre,txt_direccion,txt_telefono,txt_e_mail,txt_limite_credito],
		buttons: [btnInsertar,btnGuardar,btnReset,btnBorrar]
	});
	
	var ventana= new Ext.Window({
		title: 'PRUEBA DE CLASES CLIENTES',
		name: 'w1',
		items: [form1]
	});
	ventana.show();

/* --> CLIENTES  <--- */	



/* -->  PROVEEDORES  <--- */	
	var txt_rfcP = new Ext.form.TextField({
		fieldLabel: 'RFC',
		name: 'txt_rfcP',
		allowBlank: false,
		}); 

	var txt_nombreP = new Ext.form.TextField({
		fieldLabel: 'NOMBRE',
		allowBlank: true,
		name : 'txt_nombreP'
    });
	
	var txt_direccionP = new Ext.form.TextField({
		fieldLabel: 'DIRECCION',
		allowBlank: true,
		name : 'txt_direccionP'
    });

	var txt_telefonoP = new Ext.form.TextField({
		fieldLabel: 'TELEFONO',
		allowBlank: true,
		name : 'txt_telefonoP'
    });//txtDireccion
	var txt_e_mailP = new Ext.form.TextField({
		fieldLabel: 'E-MAIL',
		allowBlank: true,
		name : 'txt_e_mailP'
    });//txtDireccion

	var txt_limite_creditoP = new Ext.form.TextField({
		fieldLabel: 'LIMITE DE CREDITO',
		allowBlank: true,
		name : 'txt_limite_creditoP'
    });//txtDireccion


//	var storeClientes = function(){
		var proveedores=  new Ext.data.JsonStore({
			url: 'pos/funcionesProveedor.php?action=list',
			root: 'datos',
			fields: [{name:'id_proveedor',type: 'string'},{name:'nombre',type: 'string'}]
		});//Datos desde la BD con respuesta del servidor php para un combo
		//return clientes;
	//}
	
	
	var comboP= new Ext.form.ComboBox({
		fielLabel: 'ComboP',
		mode: 'remote',
		triggerAction: 'all',
		lazyRender:true,
		typeAhead: true,
		title: 'PROVEEDORES',
		store: proveedores, //origen de datos (lista)
		displayField: 'nombre', //como el DisplayMember de c#
		valueField:'id_proveedor', //como el valueMember en c#
		name: 'combo',
		listeners: { //eventos del combo (listeners)
			select: function(f,r,i){//evento select
				if (i >= 0){
					
						var idProveedor=r.get('id_proveedor');
						Ext.Ajax.request({ //peticion al servidor 'AJAXSazo'
						url: 'pos/funcionesProveedor.php?action=showProveedor', //script
						success: function(result) //propiedad que regresa el script php (JSON flag)
						{
							var data = Ext.util.JSON.decode( result.responseText ); //se guarda en esta variable local data la decodificacion JSON q regresa el servidor php
				
							if ( data.success ){ //data es la variable local JS y success es la propiedad que devuelve el JSON desde PHP
								txt_rfcP.setValue(data.datos[0].rfc);
								txt_nombreP.setValue(data.datos[0].nombre);
								txt_direccionP.setValue(data.datos[0].direccion);
								txt_telefonoP.setValue(data.datos[0].telefono);
								txt_e_mailP.setValue(data.datos[0].e_mail);
								
							}
						},
						failure: function()
						{
							Ext.Msg.alert("ERROR!!!","ERROR");
						},
						params: { //parametros que se pasaran por el metodo post al script php
							idP: idProveedor //nombre del parametro= id, valor = variable local idW
						}
					}); //AJAX REQUEST
				}//if i>0
			}
		}
	});//combo Lista Boys

			
	var btnGuardarP = new Ext.Button({
		text: 'ACTUALIZAR',
		handler: function(){
						var idProveedor=comboP.getValue();
						Ext.Ajax.request({ //peticion al servidor 'AJAXSazo'
						url: 'pos/funcionesProveedor.php?action=update', //script
						success: function(result) //propiedad que regresa el script php (JSON flag)
						{
							var data = Ext.util.JSON.decode( result.responseText ); //se guarda en esta variable local data la decodificacion JSON q regresa el servidor php
				
							if ( data.success ){ //data es la variable local JS y success es la propiedad que devuelve el JSON desde PHP								
								Ext.Msg.alert("YEAH!!!","ACTUALIZADO");
								proveedores.load();
							}
						},
						failure: function()
						{
							Ext.Msg.alert("ERROR!!!","ERROR");
						},
						params: { //parametros que se pasaran por el metodo post al script php
							idP: comboP.getValue(), //nombre del parametro= id, valor = variable local id
							rfcP: txt_rfcP.getValue(),
							nombreP: txt_nombreP.getValue(),
							direccionP: txt_direccionP.getValue(),
							telefonoP: txt_telefonoP.getValue(),
							e_mailP: txt_e_mailP.getValue(),

						}
					}); //AJAX REQUEST
					
				}
	});//fin boton guardar
	
	var btnInsertarP = new Ext.Button({
		text: 'AGREGAR',
		handler: function(){
						var idProveedor=comboP.getValue();
						Ext.Ajax.request({ //peticion al servidor 'AJAXSazo'
						url: 'pos/funcionesProveedor.php?action=insert', //script
						success: function(result) //propiedad que regresa el script php (JSON flag)
						{
							var data = Ext.util.JSON.decode( result.responseText ); //se guarda en esta variable local data la decodificacion JSON q regresa el servidor php
				
							if ( data.success ){ //data es la variable local JS y success es la propiedad que devuelve el JSON desde PHP								
								Ext.Msg.alert("YEAH!!!","INSERTADO!!!!!!");
								proveedores.load();	
							}
						},
						failure: function()
						{
							Ext.Msg.alert("ERROR!!!","ERROR");
						},
						params: { //parametros que se pasaran por el metodo post al script php
							 //nombre del parametro= id, valor = variable local id
							rfcP: txt_rfcP.getValue(),
							nombreP: txt_nombreP.getValue(),
							direccionP: txt_direccionP.getValue(),
							telefonoP: txt_telefonoP.getValue(),
							e_mailP: txt_e_mailP.getValue(),

						}
					}); //AJAX REQUEST
					
				}
	});//fin boton insertar
	
	
	var btnBorrarP = new Ext.Button({
		text: 'BORRAR',
		handler: function(){
						var idProveedor=comboP.getValue();
						Ext.Ajax.request({ //peticion al servidor 'AJAXSazo'
						url: 'pos/funcionesProveedor.php?action=delete', //script
						success: function(result) //propiedad que regresa el script php (JSON flag)
						{
							var data = Ext.util.JSON.decode( result.responseText ); //se guarda en esta variable local data la decodificacion JSON q regresa el servidor php
				
							if ( data.success ){ //data es la variable local JS y success es la propiedad que devuelve el JSON desde PHP								
								Ext.Msg.alert("YEAH!!!","BORRADO!!!!!!");
								proveedores.load();
								txt_rfcP.setValue('');
								txt_nombreP.setValue('');
								txt_direccionP.setValue('');
								txt_telefonoP.setValue('');
								txt_e_mailP.setValue('');
							}
						},
						failure: function()
						{
							Ext.Msg.alert("ERROR!!!","ERROR");
						},
						params: { //parametros que se pasaran por el metodo post al script php
							idP: comboP.getValue()
						}
					}); //AJAX REQUEST
					
				}
	});//fin boton borrar
	
	
	var btnResetP = new Ext.Button({
		text: 'RESET',
		handler: function(){
				formP.getForm().reset();	
		}
	});
	
	var formP= new Ext.FormPanel({
		url: '',
		frame: true,
		title: 'Proveedores',
		width: 400,
		items: [comboP,txt_rfcP,txt_nombreP,txt_direccionP,txt_telefonoP,txt_e_mailP],
		buttons: [btnInsertarP,btnGuardarP,btnResetP,btnBorrarP]
	});
	
	var ventanaP= new Ext.Window({
		title: 'PRUEBA DE CLASES PROVEEDORES',
		name: 'w2',
		items: [formP]
	});
	ventanaP.show();

/* --> PROVEEDORES <--- */	

	
});//fin onready
	
</script>


</head>
<body>

</body>
</html>