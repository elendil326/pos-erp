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

AppAdmin.prototype.sizeSucursalesUtilidades = 0;

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
				<li data-id="agregar-sucursal" class="agregar-sucursal" onclick="appAdmin.addSucursal()"><img src=\'../media/admin/icons/db.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Agregar Sucursal</p></li>\
				<li data-id="calcular-utilidades" class="calcular-utilidades" onclick="appAdmin.loadUtilidades()"><img src=\'../media/admin/icons/calc.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Calcular Utilidades</p></li>\
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
				<li data-id="agregar-sucursal" class="agregar-sucursal" onclick="appAdmin.addSucursal()"><img src=\'../media/admin/icons/db.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Agregar Sucursal</p></li>\
				<li data-id="calcular-utilidades" class="calcular-utilidades" onclick="appAdmin.loadUtilidades()"><img src=\'../media/admin/icons/calc.png\' width=\'150\' height=\'150\' /><p class="mosaico-text">Calcular Utilidades</p></li>\
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
		
		Utils.grid({
			url: "../proxy.php",
			data: {action: "2306", page: "1", rp: "10", sortname: "id_usuario", sortorder: "asc"},
			renderTo: "content-2",
			deleteAction: "2307",
			columns: ["ID", "Nombre", "Usuario", "Sucursal"],
			success: function(msg){
			
				//alert(msg.success);
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
				
					$("#content-2").html(options+"</fieldset><div id='mvp-results'></div><div id='mvp-summary'></div>");
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
			
				var html_result = "<h2>"+descripcion+"<br>Personal m&aacute;s productivo en el &uacute;ltimo mes</h2>";
			
				
				for(var i=0; i < msg.datos.length ; i++)
				{
					if(msg.datos[i].usuario === undefined)
					{
						html_result += "<div class='mvp-row'>No se encontraron datos</div>";
						break;
					}
					else
					{
						html_result += "<div class='mvp-row'><img src='../media/iconos/user.png' align='left' /><div class='mvp-row-text'>"+msg.datos[i].usuario+" vendi&oacute; un total de  $ "+msg.datos[i].cantidad+"</div></div>";
					}
				}
			
				//console.log(html);
				$('#mvp-results').html(html_result);
				$('#mvp-results').fadeIn('slow');
				
				
				//obtenemos ventas totales de la succursal
				/*
				Utils.request({
					url: "../proxy.php",
					data: {action: "402", id_sucursal: sucursal, dateRange: "mes"},
					success: function(msg){
					
						var html_ventas = "<h2>Ventas Totales en el &uacute;ltimo mes</h2>";
						
						html_ventas += "<p></p>";
					
					}
				
				});*/
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

AppAdmin.prototype.addSucursal = function(){
	
	$('#content-2').fadeOut('slow',function(){
	
		$('#content-2').html('\
			<div style="height:100px;" >\
			<img src="../media/admin/icons/db.png" width="100" height="100" align="left"/>\
			<div id="usuario-title">\
			<h2>Agregar Sucursal</h2></div>\
			<p id="usuario-text" >\
				Modulo para agregar sucursales nuevas. Si en alg&uacute;n momento usted necesita expandir su empresa, en &eacute;ste m&oacute;dulo puede realizar todas\
				esas acciones.<br>El token es un identificador &uacute;nico para determinar que equipo pertenece a que sucursal; si no esta seguro que poner aqu&iacute; consulte a un administrador\
				del sistema.\
			</p>\
			</div>\
			<div id="usuario-form">\
			<form id="usuario-form-element">\
			      <table>\
			      	<tr>\
			      		<td>Descripci&oacute;n</td><td><input type="text" id="descripcion-new" name="descripcion" style="width:200px;" /></td>\
			      	</tr>\
			      	<tr>\
			      		<td>Direcci&oacute;n</td><td><input type="text" id="direccion-new" name="direccion" style="width:200px;"/></td>\
			      	</tr>\
			      	<tr>\
			      		<td>Identificador de facturas</td><td><input type="text" id="letras-factura-new" name="letras_factura" style="width:200px;"/></td>\
			      	</tr>\
			      	<tr>\
			      		<td>Token</td><td><input type="text" id="token-new" name="token" style="width:200px;"/></td>\
			      	</tr>\
			      </table>\
			</form>\
			<div id="usuario-boton">\
			<button id="usuario-boton-enviar" onclick="appAdmin.sendFormNewSucursal();">Enviar</button></div>\
			</div>\
		');
		
		//$('form').jqTransform({imgPath:'../jquery/js/jqtransformplugin/img/'});
		
		
		
		$("select, input").uniform();
		$("#usuario-boton-enviar").button();
		$('#content-2').fadeIn('slow');
	
	});
}


AppAdmin.prototype.sendFormNewSucursal = function(){

	var _descripcion = $("#descripcion-new").val();
	var _direccion = $("#direccion-new").val();
	var _token = $("#token-new").val();
	var _letras_factura = $("#letras-factura-new").val();
	
	var error = false;
	
	if(_descripcion == "" || _descripcion == null)
	{
		error = true;
		//console.log("nombre");
	}
	
	if(_direccion == "" || _direccion == null)
	{
		error = true;
		//console.log("user");
	}
	
	if(_token == "" || _token  == null)
	{
		error = true;
		//console.log("pwd");
	}
	
	if(error)
	{
		//TODO: mejorar despliegue de info
		alert("Alguno de los campos tiene errores");
	}
	else
	{
		
		Utils.request({
			url: "../proxy.php",
			data: {action : "2202", descripcion: _descripcion, direccion: _direccion, token: _token, letras_factura: _letras_factura},
			success: function(msg){
		
				if(msg.success)
				{
					alert("Sucursal agregada correctamente");
					
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


AppAdmin.prototype.loadUtilidades = function(){

	$('#content-2').fadeOut('slow',function(){
	
		//html inicial
		var options = '\
			<div style="height:100px;" >\
			<img src="../media/admin/icons/calc.png" width="100" height="100" align="left"/>\
			<div id="usuario-title">\
			<h2>Calcular Utilidades</h2></div>\
			<p id="usuario-text" >\
				M&oacute;dulo para calcular utilidades. &Eacute;ste m&oacute;dulo permite calcular y repartir utilidades entre representates de cada sucursal.\
				Dicho c&aacute;lculo se efect&uacute;a consultando las ventas totales, abonos y gastos de cada sucursal. La herramienta tambi&eacute;n permite asignar porcentajes\
				de repartici&oacute;n a cada representante.\
			</p>\
			</div>\
			<p>Seleccione un rango de fechas\
			<div class="rangePicker"></div><button id="actualiza-button-utilidades" onclick="appAdmin.updateDate()">Actualizar</button><br><br>\
			</p>\
		';
	
	
		options += '<div id="flujo-efectivo"><p><b>Flujo de efectivo</b></p><br>\
				<div class="flujo-efectivo-image"><img width="100" height="100" src="../media/admin/icons/money.png" /><p>Utilidades <br><span id="utilidades"></span></p></div>\
				<div class="flujo-efectivo-image"><img width="100" height="100" src="../media/admin/icons/up.png" /><p>Abonos <br><span id="abonos-utilidades"></span></p></div>\
				<div class="flujo-efectivo-image"><img width="100" height="100" src="../media/admin/icons/down.png" /><p>Gastos <br><span id="gastos-utilidades"></span></p></div>\
				<div class="flujo-efectivo-image"><img width="100" height="100" src="../media/admin/icons/piggybank.png" /><p>Ventas de Contado<br><span id="ventas-utilidades"></span></p></div>\
				</div>';
	
		/*
		var date_start = $('#start_date').val();
		var date_end = $('#end_date').val()
	
		var date_start_array = date_start.split("/");
		var date_end_array = date_end.split("/");
		
		var date_start_format = date_start_array[2]+"-"+date_start_array[0]+"-"+date_start_array[1];
		var date_end_format = date_end_array[2]+"-"+date_end_array[0]+"-"+date_end_array[1];
		
		//consultamos datos de ventas, gastos, ingresos, abonos (flujo de efectivo)
		Utils.request({
		url: "../proxy.php",
		data: {action : "412", fecha_inicio: date_start_format, fecha_final: date_end_format},
		success: function(msg){
		
				console.log(msg);
			}
		});*/
		
		var ventas = 0;
		var gastos = 0;
		var ingresos = 0;
		var abonos = 0;
		var utilidad = 0;
		var size = 0;
		
		//sacamos todas las sucursales
		Utils.request({
		url: "../proxy.php",
		data: {action : "2201"},
		success: function(msg){
		
				size = msg.data.length;
				//cargamos date picker
				enhancedDomReady(function(){
					$('.toggleRPpos').click(function(){
						if($('div.rangePicker').css('float') == 'left') { 
							$('div.rangePicker').css('float', 'right');
							$('.toggleRPpos').html('Alinear a la izquierda');
						}
						else { 
							$('div.rangePicker').css('float', 'left'); 
							$('.toggleRPpos').html('Alinear a la derecha');
						}
						return false;
					});
			
			
					// create date picker by replacing out the inputs
					$('.rangePicker').html('<a href="#" class="range_prev"><span>Previous</span></a><a href="#" class="rangeDisplay"><span>Pick a Date</span></a><a href="#" class="range_next"><span>Next</span></a>').dateRangePicker({menuSet: 'pastRange'});
			
			
			
					var date_start = $('#start_date').val();
					var date_end = $('#end_date').val();
	
					var date_start_array = date_start.split("/");
					var date_end_array = date_end.split("/");
		
					var date_start_format = date_start_array[2]+"-"+date_start_array[0]+"-"+date_start_array[1];
					var date_end_format = date_end_array[2]+"-"+date_end_array[0]+"-"+date_end_array[1];
		
					//consultamos datos de ventas, gastos, ingresos, abonos (flujo de efectivo)
					Utils.request({
					url: "../proxy.php",
					data: "action=412&fecha-inicio="+date_start_format+"&fecha-final="+date_end_format,
					success: function(msg){
		
							if(msg.success)
							{
								ventas = msg.datos[0].venta_total;
								gastos = msg.datos[0].gasto_total;
								abonos = msg.datos[0].abono_total;
								
								utilidad = ventas + abonos - gastos;
								
								//console.log(utilidad);
								
								$("#utilidades").html("<b>$"+utilidad+".00</b>");
								$("#ventas-utilidades").html("<b>$"+ventas+".00</b>");
								$("#gastos-utilidades").html("<b>$"+gastos+".00</b>");
								$("#abonos-utilidades").html("<b>$"+abonos+".00</b>");
								
								//el ultimo cierre de div es del div de la tabla de sucursales
								$("#content-2").append('<div id="button-calc-wrapper"><button id="button-calcular-reparticion" onclick="appAdmin.doCalc('+utilidad+','+size+')">Calcular Repartici&oacute;n</button></div></div>');
							}
							else
							{
								$("#ventas-utilidades").html("<b>No hay datos</b>");
								$("#gastos-utilidades").html("<b>No hay datos</b>");
								$("#abonos-utilidades").html("<b>No hay datos</b>");
							}
						}
					});
				});
		
		
				options += '<p>El porcentaje de repartici&oacute;n se calcula equitativamente por defecto, si usted desea modificar dichos valores por favor use los campos siguientes</p>';
		
				if(msg.success)
				{
					
					options += '<div id="tabla-sucursales"><table style="color:white !important"><tr><td><b>Sucursal</b></td><td><b>Porcentaje</b></td><td><b>Dinero repartido</b></td></tr>';
					
					var porcentaje = 100 / msg.data.length;
					
					appAdmin.sizeSucursalesUtilidades = msg.data.length;
					
					for (var i = 0; i < msg.data.length; i++) {
						options += '<tr><td id="renglon-sucursales-'+ i +'">' + msg.data[i].display + '</td>\
								<td><input type="text" class="campo-sucursal" id="campo-sucursal-'+ i +'" value="'+Math.round(porcentaje*1000)/1000+'" /></td>\
								<td id="total-sucursal-repartido-'+ i +'"></td></tr>';
					}
				
					
					/*options += 	'<tr><td>\
							<button id="button-calcular-reparticion" onclick="appAdmin.doCalc('+utilidad+','+msg.data.length+')">Calcular Repartici&oacute;n</button>\
							</td><td></td></tr>';*/
							
					
					options += '</table>';
				}
				else
				{
					options += '<tr><td>No hay datos</td><td></td></tr></table>';
				}
			
			
				$('#content-2').html(options);
				$("input").uniform();
				$('#button-calcular-reparticion').button();
				$('#actualiza-button-utilidades').button();
				
				
				
				
				
				$('#content-2').fadeIn('slow');	
				
			}
		});
	
		
		
	});

}

AppAdmin.prototype.doCalc = function(utilidad, size){

	var value = 0;
	var sum = 0;
	
	//console.log(utilidad);
	
	for(var i = 0; i < size; i++)
	{
		value = $("#campo-sucursal-"+i).val();
		
		sum += parseFloat(value);
		
		
	}
	
	console.log(sum);
	
	if(sum > 100)
	{
		alert("La suma de los porcentajes debe ser por mucho 100%");
		
		return;
	}
	
	for(var i = 0; i < size; i++)
	{
		value = $("#campo-sucursal-"+i).val();
		
		$("#total-sucursal-repartido-"+i).html("<b>$"+Math.round(((value/100)*utilidad)*100)/100+"</b>");
	}
}

AppAdmin.prototype.updateDate = function(){

	var date_start = $('#start_date').val();
	var date_end = $('#end_date').val();

	var date_start_array = date_start.split("/");
	var date_end_array = date_end.split("/");

	var date_start_format = date_start_array[2]+"-"+date_start_array[0]+"-"+date_start_array[1];
	var date_end_format = date_end_array[2]+"-"+date_end_array[0]+"-"+date_end_array[1];
	
	var size = this.sizeSucursalesUtilidades;

	//consultamos datos de ventas, gastos, ingresos, abonos (flujo de efectivo)
	Utils.request({
	url: "../proxy.php",
	data: "action=412&fecha-inicio="+date_start_format+"&fecha-final="+date_end_format,
	success: function(msg){

			if(msg.success)
			{
				ventas = msg.datos[0].venta_total;
				gastos = msg.datos[0].gasto_total;
				abonos = msg.datos[0].abono_total;
				
				utilidad = ventas + abonos - gastos;
				
				//console.log(utilidad);
				
				$("#utilidades").html("<b>$"+utilidad+".00</b>");
				$("#ventas-utilidades").html("<b>$"+ventas+".00</b>");
				$("#gastos-utilidades").html("<b>$"+gastos+".00</b>");
				$("#abonos-utilidades").html("<b>$"+abonos+".00</b>");
				
				$("#button-calc-wrapper").html('<button id="button-calcular-reparticion" onclick="appAdmin.doCalc('+utilidad+','+size+')">Calcular Repartici&oacute;n</button>');
			}
			else
			{
				$("#ventas-utilidades").html("<b>No hay datos</b>");
				$("#gastos-utilidades").html("<b>No hay datos</b>");
				$("#abonos-utilidades").html("<b>No hay datos</b>");
			}
		}
	});

}

AppAdmin.prototype.logout = function(){
	
	//Creo un div para usarse como dialogo, este se reciclara
	var d = document;
	var dialogo = d.createElement("div");
	dialogo.id = "dialogo";
	
	
	//Agregamos el div del dialogo reusable
	$('#content-2').append(dialogo);
	
	//Creamos boton y asignamos el listener
	//$("#boton-salir").button();
	//$("#boton-salir").click(function(){
	
		$('#dialogo').html("Esta seguro que desea abandonar el sistema?");
		$('#dialogo').dialog({
					title: 'Desea Salir',
					modal: true,
					dialogClass: 'confirm',
					buttons: {
						'Salir': function() {
							//Hacer algo al salir
							
							Utils.request({
								url:"../proxy.php",
								data: {action:"2002"},
								success: function(){
								
									$(this).dialog('close');
									window.location = "../";
								}
							
							});
						},
						Cancel: function() {
							$(this).dialog('close');
						}
					}
				});
		
	//});
}
