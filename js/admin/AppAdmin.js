/**
*	@fileoverview APLICACION DE ADMINISTRACION, ESTA APP TENDRA FUNCIONES PARA AGREGAR PESTANAS
*	CONFIGURAR MENUS, ETC RELACIONADOS A LA ESTRUCTURA Y ACOMODO DE MINI APPS
*
*	@author	Rene Michel rene@caffeina.mx
*	@version 0.1
*/


AppAdmin = function(){

	AppAdmin.currentInstance = this;
	return this._init();

}

/*
	_init inicializa todo lo que tenga que asignarse al inicio dle arranque de la app
*/
AppAdmin.prototype._init = function(){

	//this.loadStructure();
	this.loadMosaic();

}

/*
	loadStructure carga la estructura inicial de la app de administracion, paneles laterales,
	pesta~nas y todo lo referente a controles. DEBE LLAMARSE EN LA FUNCION _init()
*/
AppAdmin.prototype.loadStructure = function(){

	//Creamos logout Message
	this.createLogoutMessage();
	
	//Metemos contenido al menu 
	
	$("#menu-left").html('<div id="accordion">\
	    <h3><a href="#" id="reportes-boton" >Reportes</a></h3>\
	    <div >Consulte reportes especificos</div>\
	    <h3><a href="#" id="sucursales-boton">Sucursales</a></h3>\
	    <div >Consulte datos de sus sucursales</div>\
	    <h3><a href="#" id="personal-boton">Personal</a></h3>\
	    <div >Administre el personal de sus sucursales</div>\
	</div>');
	
	
	
	//Convertimos a acordion
	$('#accordion').accordion({active: false});
	
	
	 //TODO: Agregamos listeners a los botones
	 $('#reportes-boton').click(function(){
	 
	 				if(DEBUG){ console.log('click en reportes');}
	 				new Reports();
	 				
	 				});
	 				
	 $('#sucursales-boton').click(function(){
	 
	 				if(DEBUG){ console.log('click en sucursales');}
	 				
	 				});
	 				
	 $('#personal-boton').click(function(){
	 
	 				if(DEBUG){ console.log('click en personal');}
	 				new Datos();
	 				});
}

AppAdmin.prototype.loadMosaic = function(){

	$('#content-2').fadeOut('slow',function(){

	$('#content-2').html('<ul id="source" class="inline">\
				<li data-id="ventas-all" class="reporte-ventas" onclick="Reports.currentInstance.loadVentasTodas()"><img src=\'../media/admin/icons/piggybank.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Ventas Totales</p></li>\
				<li data-id="ventas-credito" class="reporte-ventas" onclick="Reports.currentInstance.loadVentasCreditoReport()"><img src=\'../media/admin/icons/piggybank.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Ventas a Cr&eacute;dito</p></li>\
				<li data-id="ventas-contado" class="reporte-ventas" onclick="Reports.currentInstance.loadVentasContadoReport()"><img src=\'../media/admin/icons/piggybank.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Ventas de Contado</p></li>\
				<li data-id="compras-all" class="reporte-compras" onclick="Reports.currentInstance.loadClientesComprasTodasReport()"><img src=\'../media/admin/icons/cart.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Compras Totales</p></li>\
				<li data-id="compras-credito" class="reporte-compras" onclick="Reports.currentInstance.loadComprasCreditoReport()"><img src=\'../media/admin/icons/cart.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Compras a Cr&eacute;dito</p></li>\
				<li data-id="compras-contado" class="reporte-compras" onclick="Reports.currentInstance.loadComprasContadoReport()"><img src=\'../media/admin/icons/cart.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Compras de Contado</p></li>\
				<li data-id="compras-deudas" class="reporte-compras"><img src=\'../media/admin/icons/cart.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Compras deudas</p></li>\
				<li data-id="compras-pagado" class="reporte-compras"><img src=\'../media/admin/icons/cart.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Compras pagado</p></li>\
				<li data-id="clientes-all" class="reporte-clientes" onclick="Reports.currentInstance.loadClientesReport()"><img src=\'../media/admin/icons/user.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Clientes</p></li>\
				<li data-id="clientes-deudores" class="reporte-clientes" onclick="Reports.currentInstance.loadClientesDebenReport()"><img src=\'../media/admin/icons/user.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Clientes deudores</p></li>\
				<li data-id="gastos" class="reporte-dinero" onclick="Reports.currentInstance.loadGastosReport()" ><img src=\'../media/admin/icons/money.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Gastos</p></li>\
				<li data-id="pagos" class="reporte-dinero" onclick="Reports.currentInstance.loadIngresosReport()"><img src=\'../media/admin/icons/money.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Ingresos</p></li>\
				<li data-id="admin-personal" class="admin-personal" onclick="appAdmin.loadPersonal()"><img src=\'../media/admin/icons/client.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Administrar Personal</p></li>\
				<li data-id="rendimiento-personal" class="rendimiento-personal" onclick="appAdmin.loadRendimientoPersonal()"><img src=\'../media/admin/icons/client.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Rendimiento del Personal</p></li>\
			</ul>\
			<ul id="r-ventas" class="hidden inline">\
				<li data-id="ventas-all" class="reporte-ventas" onclick="Reports.currentInstance.loadVentasTodas()"><img src=\'../media/admin/icons/piggybank.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Ventas Totales</p></li>\
				<li data-id="ventas-credito" class="reporte-ventas" onclick="Reports.currentInstance.loadVentasCreditoReport()"><img src=\'../media/admin/icons/piggybank.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Ventas a Credito</p></li>\
				<li data-id="ventas-contado" class="reporte-ventas" onclick="Reports.currentInstance.loadVentasContadoReport()"><img src=\'../media/admin/icons/piggybank.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Ventas de Contado</p></li>\
			</ul>\
			<ul id="r-compras" class="hidden inline">\
				<li data-id="compras-all" class="reporte-compras" onclick="Reports.currentInstance.loadClientesComprasTodasReport()"><img src=\'../media/admin/icons/cart.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Compras Totales</p></li>\
				<li data-id="compras-credito" class="reporte-compras" onclick="Reports.currentInstance.loadComprasCreditoReport()"><img src=\'../media/admin/icons/cart.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Compras a Cr&eacute;dito</p></li>\
				<li data-id="compras-contado" class="reporte-compras" onclick="Reports.currentInstance.loadComprasContadoReport()"><img src=\'../media/admin/icons/cart.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Compras de Contado</p></li>\
				<li data-id="compras-deudas" class="reporte-compras"><img src=\'../media/admin/icons/cart.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Compras deudas</p></li>\
				<li data-id="compras-pagado" class="reporte-compras"><img src=\'../media/admin/icons/cart.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Compras pagado</p></li>\
			</ul>\
			<ul id="r-clientes" class="hidden inline">\
				<li data-id="clientes-all" class="reporte-clientes" onclick="Reports.currentInstance.loadClientesReport()"><img src=\'../media/admin/icons/user.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Clientes</p></li>\
				<li data-id="clientes-deudores" class="reporte-clientes" onclick="Reports.currentInstance.loadClientesDebenReport()"><img src=\'../media/admin/icons/user.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Clientes deudores</p></li>\
			</ul>\
			<ul id="r-dinero" class="hidden inline">\
				<li data-id="gastos" class="reporte-dinero" onclick="Reports.currentInstance.loadGastosReport()" ><img src=\'../media/admin/icons/money.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Gastos</p></li>\
				<li data-id="pagos" class="reporte-dinero" onclick="Reports.currentInstance.loadIngresosReport()"><img src=\'../media/admin/icons/money.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Ingresos</p></li>\
			</ul>\
			<ul id="source-hidden" class="hidden inline">\
				<li data-id="ventas-all" class="reporte-ventas" onclick="Reports.currentInstance.loadVentasTodas()"><img src=\'../media/admin/icons/piggybank.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Ventas Totales</p></li>\
				<li data-id="ventas-credito" class="reporte-ventas" onclick="Reports.currentInstance.loadVentasCreditoReport()"><img src=\'../media/admin/icons/piggybank.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Ventas a Credito</p></li>\
				<li data-id="ventas-contado" class="reporte-ventas" onclick="Reports.currentInstance.loadVentasContadoReport()"><img src=\'../media/admin/icons/piggybank.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Ventas de Contado</p></li>\
				<li data-id="compras-all" class="reporte-compras" onclick="Reports.currentInstance.loadClientesComprasTodasReport()"><img src=\'../media/admin/icons/cart.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Compras Totales</p></li>\
				<li data-id="compras-credito" class="reporte-compras" onclick="Reports.currentInstance.loadComprasCreditoReport()"><img src=\'../media/admin/icons/cart.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Compras a Cr&eacute;dito</p></li>\
				<li data-id="compras-contado" class="reporte-compras" onclick="Reports.currentInstance.loadComprasContadoReport()"><img src=\'../media/admin/icons/cart.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Compras de Contado</p></li>\
				<li data-id="compras-deudas" class="reporte-compras"><img src=\'../media/admin/icons/cart.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Compras deudas</p></li>\
				<li data-id="compras-pagado" class="reporte-compras"><img src=\'../media/admin/icons/cart.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Compras pagado</p></li>\
				<li data-id="clientes-all" class="reporte-clientes" onclick="Reports.currentInstance.loadClientesReport()"><img src=\'../media/admin/icons/user.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Clientes</p></li>\
				<li data-id="clientes-deudores" class="reporte-clientes" onclick="Reports.currentInstance.loadClientesDebenReport()"><img src=\'../media/admin/icons/user.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Clientes deudores</p></li>\
				<li data-id="gastos" class="reporte-dinero" onclick="Reports.currentInstance.loadGastosReport()" ><img src=\'../media/admin/icons/money.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Gastos</p></li>\
				<li data-id="pagos" class="reporte-dinero" onclick="Reports.currentInstance.loadIngresosReport()"><img src=\'../media/admin/icons/money.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Ingresos</p></li>\
				<li data-id="admin-personal" class="admin-personal" onclick="appAdmin.loadPersonal()"><img src=\'../media/admin/icons/client.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Administrar Personal</p></li>\
				<li data-id="rendimiento-personal" class="rendimiento-personal" onclick="appAdmin.loadRendimientoPersonal()"><img src=\'../media/admin/icons/client.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Rendimiento del Personal</p></li>\
			</ul>');

		$('#content-2').fadeIn('slow');
	});

}

AppAdmin.prototype.loadPersonal = function(){

	$('#content-2').fadeOut('slow',function(){
	
		$('#content-2').html('\
			<div style="height:100px;" >\
			<img src="../media/admin/icons/db.png" width="100" height="100" align="left"/>\
			<div id="usuario-title">\
			<h2>Administraci&oacute;n del Personal</h2></div>\
			<p id="usuario-text" >\
				Agregue personal a sucursales. Si en alg&uacute;n momento usted necesita expandir su personal, en &eacute;ste m&oacute;dulo puede realizar todas\
				esas acciones.\
			</p>\
			</div>\
			<div id="usuario-form">\
			<form id="usuario-form-element">\
			      <table>\
			      	<tr>\
			      		<td>Nombre Completo</td><td><input type="text" id="nombre-new" name="nombre" style="width:200px;" /></td>\
			      	</tr>\
			      	<tr>\
			      		<td>Usuario</td><td><input type="text" id="user-new" name="user" style="width:200px;"/></td>\
			      	</tr>\
			      	<tr>\
			      		<td>Contrase&ntilde;a</td><td><input type="password" id="pwd-new" name="password" style="width:200px;"/></td>\
			      	</tr>\
			      	<tr>\
			      		<td>Repita Contrase&ntilde;a</td><td><input type="password" id="pwd2-new" name="password2" style="width:200px;"/></td>\
			      	</tr>\
			      	<tr>\
			      		<td>Sucursal</td><td><select id="select-sucursal" name="sucursal" >\
			      					</select>\
			      		</td>\
			      	</tr>\
			      	<tr>\
			      		<td>Nivel de acceso</td><td><select id="acceso" name="acceso-nombre" >\
			      						<option value="1">Administrador</option>\
			      						<option value="2">Gerente</option>\
			      						<option value="3">Cajero</option>\
			      					</select>\
			      		</td>\
			      	</tr>\
			      </table>\
			</form>\
			<div id="usuario-boton">\
			<button id="usuario-boton-enviar" onclick="appAdmin.sendFormNewUser();">Enviar</button></div>\
			</div>\
		');
		
		//$('form').jqTransform({imgPath:'../jquery/js/jqtransformplugin/img/'});
		
		
		var options = "";
		Utils.request({
		url: "../proxy.php",
		data: {action : "2201"},
		success: function(msg){
		
			if(msg.success)
			{
				for (var i = 0; i < msg.data.length; i++) {
					options += '<option value="' + msg.data[i].value + '">' + msg.data[i].display + '</option>';
				}
				
				$("select#select-sucursal").html(options);
			}
			else
			{
				options += '<option>No se encontraron datos</option>';
			}
			
			$("select, input").uniform();
			$("#usuario-boton-enviar").button();
		}
	});
	
		$('#content-2').fadeIn('slow');
	
	});
}

AppAdmin.prototype.loadRendimientoPersonal = function(){

	$('#content-2').fadeOut('slow',function(){
	
	/*
		Utils.request({
				url: "../proxy.php",
				data: {action: "401", showAll: true},
				success: function(msg){
				
					var html_result = "";
				
					
					for(var i=0; i < msg.datos.length ; i++)
					{
					
						html_result += "<div class='mvp-row'>"+msg.datos[i].usuario+" "+msg.datos[i].cantidad+"</div>";
					}
				
					//console.log(html);
					$('#content-2').html(html_result);
					$('#content-2').fadeIn('slow');
				}
			});
			
	*/
	
		var options = '<div style="height:100px;" >\
			<img src="../media/admin/icons/report.png" width="100" height="100" align="left"/>\
			<div id="usuario-title">\
			<h2>Rendimiento del Personal</h2></div>\
			<p id="usuario-text" >\
				En esta secci&oacute;n podr&aacute; consultar el n&uacute;mero de ventas de cada\
				miembro de su personal as&iacute; como el de sus sucursales. De tal manera que pueda\
				obtener informaci&oacute;n confiable para tomar decisiones al evaluar las primas.\
			</p>\
			</div><fieldset style="text-align:center">';
		
		
	
		Utils.request({
		
			url: "../proxy.php",
			data: {action: "2201"},
			success: function(msg){
			
				
			
				if(msg.success)
				{
					for (var i = 0; i < msg.data.length; i++) {
						options += '<a class="sucursal-link" href="#" onclick="appAdmin.loadMVPSucursal('+msg.data[i].value+',\''+msg.data[i].display+'\')" ">' + msg.data[i].display + '</a>&nbsp;|&nbsp;';
					}
				
					$("#content-2").html(options+"</fieldset><div id='mvp-results'></div> ");
					$('#content-2').fadeIn('slow');
				
				}
				else
				{
					options += 'No se encontraron sucursales';
				}
			
			}
	
		});	
	
	});

}

AppAdmin.prototype.loadMVPSucursal = function(sucursal,descripcion){

	Utils.request({
			url: "../proxy.php",
			data: {action: "404", showAll: true, id_sucursal: sucursal, dateRange: "mes"},
			success: function(msg){
			
				var html_result = "<h2>Personal m&aacute;s productivo Sucursal "+descripcion+"</h2>Ventas por usuario";
			
				
				for(var i=0; i < msg.datos.length ; i++)
				{
					if(msg.datos[i].usuario === undefined)
					{
						html_result += "<div class='mvp-row'>No se encontraron datos</div>";
						break;
					}
					else
					{
						html_result += "<div class='mvp-row'><img src='../media/iconos/user.png' />"+msg.datos[i].usuario+" vendi&oacute; un total de  $ "+msg.datos[i].cantidad+"</div>";
					}
				}
			
				//console.log(html);
				$('#mvp-results').html(html_result);
				$('#mvp-results').fadeIn('slow');
			}
		});

}

AppAdmin.prototype.sendFormNewUser = function(){

	var _nombre = $("#nombre-new").val();
	var _user = $("#user-new").val();
	var _pwd = $("#pwd-new").val();
	var _pwd2 = $("#pwd2-new").val();
	var _sucursal = $("#select-sucursal").val();
	var _acceso = $("#acceso").val();
	
	var error = false;
	
	if(_nombre == "" || _nombre == null)
	{
		error = true;
		//console.log("nombre");
	}
	
	if(_user == "" || _user == null)
	{
		error = true;
		//console.log("user");
	}
	
	if(_pwd == "" || _pwd  == null)
	{
		error = true;
		//console.log("pwd");
	}
	
	if(_pwd2 == "" || _pwd2 == null)
	{
		error = true;
		//console.log(_pwd2);
	}
	
	if(_pwd2 != _pwd)
	{
		error = true;
		//console.log("pwd2!=pwd"+_pwd+" "+_pwd2);
	}
	
	if(_sucursal == "" || _sucursal == null)
	{
		error = true;
		//console.log("sucursal");
	}
	
	if(_acceso == "" || _acceso == null)
	{
		error = true;
		//console.log("sucursal");
	}
	
	if(error)
	{
		alert("Alguno de los campos tiene errores");
	}
	else
	{

		Utils.request({
			url: "../proxy.php",
			data: {action : "2301", nombre: _nombre, user2: _user, password: _pwd, sucursal : _sucursal, acceso: _acceso},
			success: function(msg){
		
				if(msg.success)
				{
					alert("Usuario agregado correctamente");
					
					var form = document.getElementById("usuario-form-element");
					form.reset();
				}
				else
				{
					alert(msg.error);
				}
			}
		});
	}
}

AppAdmin.prototype.createLogoutMessage = function(){
	
	//Creo un div para usarse como dialogo, este se reciclara
	var d = document;
	var dialogo = d.createElement("div");
	dialogo.id = "dialogo";
	
	
	//Agregamos el div del dialogo reusable
	$('#main').append(dialogo);
	
	//Creamos boton y asignamos el listener
	//$("#boton-salir").button();
	$("#boton-salir").click(function(){
	
		$('#dialogo').html("Esta seguro que desea abandonar el sistema?");
		$('#dialogo').dialog({
					title: 'Desea Salir',
					modal: true,
					dialogClass: 'confirm',
					buttons: {
						'Salir': function() {
							//Hacer algo al salir
							$(this).dialog('close');
						},
						Cancel: function() {
							$(this).dialog('close');
						}
					}
				});
		
	});
}
