
var Database=function(){this.db=openDatabase("pos","1.0","Point of Sale",200000);this.db.transaction(function(tx){tx.executeSql(""
+"CREATE TABLE IF NOT EXISTS `actualizacion_de_precio` ("
+"  `id_actualizacion` int(12) NOT NULL  , "
+"  `id_producto` int(11) NOT NULL,"
+"  `id_usuario` int(11) NOT NULL,"
+"  `precio_venta` float NOT NULL,"
+"  `precio_venta_procesado` float NOT NULL,"
+"  `precio_intersucursal` float NOT NULL,"
+"  `precio_intersucursal_procesado` float NOT NULL,"
+"  `precio_compra` float NOT NULL DEFAULT '0' , "
+"  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,"
+" PRIMARY KEY (`id_actualizacion`)"
+");",[],sqlWin,sqlFail);tx.executeSql(""
+"CREATE TABLE IF NOT EXISTS `autorizacion` ("
+"  `id_autorizacion` int(11)  NOT NULL ,"
+"  `id_usuario` int(11) NOT NULL , "
+"  `id_sucursal` int(11) NOT NULL , "
+"  `fecha_peticion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP , "
+"  `fecha_respuesta` timestamp NULL DEFAULT NULL , "
+"  `estado` int(11) NOT NULL , "
+"  `parametros` varchar(2048) NOT NULL , "
+"`tipo` varchar(32),"
+" PRIMARY KEY (`id_autorizacion`)"
+");",[],sqlWin,sqlFail);tx.executeSql(""
+"CREATE TABLE IF NOT EXISTS `cliente` ("
+"  `id_cliente` int(11) NOT NULL  , "
+"  `rfc` varchar(20)  NOT NULL , "
+"  `razon_social` varchar(100)  NOT NULL , "
+"  `calle` varchar(300)  NOT NULL , "
+"  `numero_exterior` varchar(20)  NOT NULL , "
+"  `numero_interior` varchar(20)  DEFAULT NULL , "
+"  `colonia` varchar(50)  NOT NULL , "
+"  `referencia` varchar(100)  DEFAULT NULL , "
+"  `localidad` varchar(50)  DEFAULT NULL , "
+"  `municipio` varchar(55)  NOT NULL , "
+"  `estado` varchar(50)  NOT NULL , "
+"  `pais` varchar(50)  NOT NULL , "
+"  `codigo_postal` varchar(15)  NOT NULL , "
+"  `telefono` varchar(25)  DEFAULT NULL , "
+"  `e_mail` varchar(60)  DEFAULT NULL , "
+"  `limite_credito` float NOT NULL DEFAULT '0' , "
+"  `descuento` float NOT NULL DEFAULT '0' , "
+"  `activo` tinyint(2) NOT NULL DEFAULT '1' , "
+"  `id_usuario` int(11) NOT NULL , "
+"  `id_sucursal` int(11) NOT NULL , "
+"  `fecha_ingreso` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP , "
+"  `password` varchar(64)  DEFAULT NULL , "
+"  `last_login` timestamp NULL DEFAULT NULL,"
+"  `grant_changes` tinyint(1) DEFAULT '0' , "
+" PRIMARY KEY (`id_cliente`)"
+");",[],sqlWin,sqlFail);tx.executeSql(""
+"CREATE TABLE IF NOT EXISTS `compra_cliente` ("
+"  `id_compra` int(11) NOT NULL  , "
+"  `id_cliente` int(11) NOT NULL , "
+"`tipo_compra` varchar(32),"
+"`tipo_pago` varchar(32),"
+"  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP , "
+"  `subtotal` float DEFAULT NULL , "
+"  `impuesto` float DEFAULT '0' , "
+"  `descuento` float NOT NULL DEFAULT '0' , "
+"  `total` float NOT NULL DEFAULT '0' , "
+"  `id_sucursal` int(11) NOT NULL , "
+"  `id_usuario` int(11) NOT NULL , "
+"  `pagado` float NOT NULL DEFAULT '0' , "
+"  `cancelada` tinyint(1) NOT NULL DEFAULT '0' , "
+"  `ip` varchar(16)  NOT NULL DEFAULT '0.0.0.0' , "
+"  `liquidada` tinyint(1) NOT NULL DEFAULT '0' , "
+"  PRIMARY KEY (`id_compra`)"
+");",[],sqlWin,sqlFail);tx.executeSql(""
+"CREATE TABLE IF NOT EXISTS `detalle_compra_cliente` ("
+"  `id_compra` int(11) NOT NULL , "
+"  `id_producto` int(11) NOT NULL , "
+"  `cantidad` float NOT NULL , "
+"  `precio` float NOT NULL , "
+"  `descuento` float  DEFAULT '0' , "
+" PRIMARY KEY (`id_compra`,`id_producto`)"
+");",[],sqlWin,sqlFail);tx.executeSql(""
+"CREATE TABLE IF NOT EXISTS `detalle_inventario` ("
+"  `id_producto` int(11) NOT NULL , "
+"  `id_sucursal` int(11) NOT NULL , "
+"  `precio_venta` float NOT NULL , "
+"  `precio_venta_procesado` float NOT NULL DEFAULT '0' , "
+"  `existencias` float NOT NULL DEFAULT '0' , "
+"  `existencias_procesadas` float NOT NULL , "
+"  `precio_compra` float NOT NULL DEFAULT '0' , "
+" PRIMARY KEY (`id_producto`,`id_sucursal`)"
+");",[],sqlWin,sqlFail);tx.executeSql(""
+"CREATE TABLE IF NOT EXISTS `detalle_venta` ("
+"  `id_venta` int(11) NOT NULL , "
+"  `id_producto` int(11) NOT NULL , "
+"  `cantidad` float NOT NULL , "
+"  `cantidad_procesada` float NOT NULL,"
+"  `precio` float NOT NULL , "
+"  `precio_procesada` float NOT NULL , "
+"  `descuento` float  DEFAULT '0' , "
+" PRIMARY KEY (`id_venta`,`id_producto`)"
+");",[],sqlWin,sqlFail);tx.executeSql(""
+"CREATE TABLE IF NOT EXISTS `equipo` ("
+"  `id_equipo` int(6) NOT NULL  , "
+"  `token` varchar(128) DEFAULT NULL , "
+"  `full_ua` varchar(256) NOT NULL , "
+"  `descripcion` varchar(254) NOT NULL , "
+"  `locked` tinyint(1) NOT NULL DEFAULT '0' , "
+" PRIMARY KEY (`id_equipo`)"
+");",[],sqlWin,sqlFail);tx.executeSql(""
+"CREATE TABLE IF NOT EXISTS `factura_compra` ("
+"  `folio` varchar(15)  NOT NULL,"
+"  `id_compra` int(11) NOT NULL , "
+" PRIMARY KEY (`folio`)"
+");",[],sqlWin,sqlFail);tx.executeSql(""
+"CREATE TABLE IF NOT EXISTS `factura_venta` ("
+"  `id_folio` int(11)  NOT NULL  , "
+"  `id_venta` int(11) NOT NULL , "
+"  `id_usuario` int(10) NOT NULL , "
+"  `xml` text  NOT NULL , "
+"  `lugar_emision` int(11) NOT NULL , "
+"`tipo_comprobante` varchar(32),"
+"  `activa` tinyint(1) NOT NULL DEFAULT '1' , "
+"  `sellada` tinyint(1) NOT NULL DEFAULT '0' , "
+"  `forma_pago` varchar(100)  NOT NULL,"
+"  `fecha_emision` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',"
+"  `version_tfd` varchar(10)  NOT NULL,"
+"  `folio_fiscal` varchar(128)  NOT NULL,"
+"  `fecha_certificacion` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',"
+"  `numero_certificado_sat` varchar(128)  NOT NULL,"
+"  `sello_digital_emisor` varchar(512)  NOT NULL,"
+"  `sello_digital_sat` varchar(512)  NOT NULL,"
+"  `cadena_original` varchar(2048)  NOT NULL,"
+" PRIMARY KEY (`id_folio`)"
+");",[],sqlWin,sqlFail);tx.executeSql(""
+"CREATE TABLE IF NOT EXISTS `gastos` ("
+"  `id_gasto` int(11) NOT NULL  , "
+"  `folio` varchar(22) NOT NULL , "
+"  `concepto` varchar(100) NOT NULL , "
+"  `monto` float  NOT NULL , "
+"  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP , "
+"  `fecha_ingreso` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' , "
+"  `id_sucursal` int(11) NOT NULL , "
+"  `id_usuario` int(11) NOT NULL , "
+"  `nota` varchar(512) NOT NULL , "
+" PRIMARY KEY (`id_gasto`)"
+");",[],sqlWin,sqlFail);tx.executeSql(""
+"CREATE TABLE IF NOT EXISTS `impresora` ("
+"  `id_impresora` int(11) NOT NULL  , "
+"  `id_sucursal` int(11) NOT NULL , "
+"  `descripcion` varchar(256)  NOT NULL , "
+"  `identificador` varchar(128)  NOT NULL , "
+" PRIMARY KEY (`id_impresora`)"
+");",[],sqlWin,sqlFail);tx.executeSql(""
+"CREATE TABLE IF NOT EXISTS `ingresos` ("
+"  `id_ingreso` int(11) NOT NULL  , "
+"  `concepto` varchar(100) NOT NULL , "
+"  `monto` float NOT NULL , "
+"  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,"
+"  `fecha_ingreso` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' , "
+"  `id_sucursal` int(11) NOT NULL , "
+"  `id_usuario` int(11) NOT NULL , "
+"  `nota` varchar(512) NOT NULL , "
+" PRIMARY KEY (`id_ingreso`)"
+");",[],sqlWin,sqlFail);tx.executeSql(""
+"CREATE TABLE IF NOT EXISTS `inventario` ("
+"  `id_producto` int(11) NOT NULL  , "
+"  `descripcion` varchar(30)  NOT NULL , "
+"`escala` varchar(32),"
+"`tratamiento` varchar(32),"
+"  `agrupacion` varchar(8)  DEFAULT NULL , "
+"  `agrupacionTam` float DEFAULT NULL , "
+"  `activo` tinyint(1) NOT NULL DEFAULT '1' , "
+"  `precio_por_agrupacion` tinyint(1) NOT NULL DEFAULT '0',"
+"  PRIMARY KEY (`id_producto`)"
+");",[],sqlWin,sqlFail);tx.executeSql(""
+"CREATE TABLE IF NOT EXISTS `pagos_venta` ("
+"  `id_pago` int(11) NOT NULL  , "
+"  `id_venta` int(11) NOT NULL , "
+"  `id_sucursal` int(11) NOT NULL , "
+"  `id_usuario` int(11) NOT NULL , "
+"  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP , "
+"  `monto` float NOT NULL , "
+"`tipo_pago` varchar(32),"
+" PRIMARY KEY (`id_pago`)"
+");",[],sqlWin,sqlFail);tx.executeSql(""
+"CREATE TABLE IF NOT EXISTS `sucursal` ("
+"  `id_sucursal` int(11) NOT NULL  , "
+"  `gerente` int(11) DEFAULT NULL , "
+"  `descripcion` varchar(100)  NOT NULL , "
+"  `razon_social` varchar(100)  NOT NULL , "
+"  `rfc` varchar(20)  NOT NULL , "
+"  `calle` varchar(50)  NOT NULL , "
+"  `numero_exterior` varchar(10)  NOT NULL , "
+"  `numero_interior` varchar(10)  DEFAULT NULL , "
+"  `colonia` varchar(50)  NOT NULL , "
+"  `localidad` varchar(50)  DEFAULT NULL , "
+"  `referencia` varchar(200)  DEFAULT NULL , "
+"  `municipio` varchar(100)  NOT NULL , "
+"  `estado` varchar(50)  NOT NULL , "
+"  `pais` varchar(50)  NOT NULL , "
+"  `codigo_postal` varchar(15)  NOT NULL , "
+"  `telefono` varchar(20)  DEFAULT NULL , "
+"  `token` varchar(512)  DEFAULT NULL , "
+"  `letras_factura` char(1)  NOT NULL,"
+"  `activo` tinyint(1) NOT NULL DEFAULT '1',"
+"  `fecha_apertura` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP , "
+"  `saldo_a_favor` float NOT NULL DEFAULT '0' , "
+" PRIMARY KEY (`id_sucursal`)"
+");",[],sqlWin,sqlFail);tx.executeSql(""
+"CREATE TABLE IF NOT EXISTS `ventas` ("
+"  `id_venta` int(11) NOT NULL  , "
+"  `id_venta_equipo` int(11) NOT NULL,"
+"  `id_equipo` int(11) DEFAULT NULL,"
+"  `id_cliente` int(11) NOT NULL , "
+"`tipo_venta` varchar(32),"
+"`tipo_pago` varchar(32),"
+"  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP , "
+"  `subtotal` float DEFAULT NULL , "
+"  `iva` float DEFAULT '0' , "
+"  `descuento` float NOT NULL DEFAULT '0' , "
+"  `total` float NOT NULL DEFAULT '0' , "
+"  `id_sucursal` int(11) NOT NULL , "
+"  `id_usuario` int(11) NOT NULL , "
+"  `pagado` float NOT NULL DEFAULT '0' , "
+"  `cancelada` tinyint(1) NOT NULL DEFAULT '0' , "
+"  `ip` varchar(16)  NOT NULL DEFAULT '0.0.0.0' , "
+"  `liquidada` tinyint(1) NOT NULL DEFAULT '0' , "
+" PRIMARY KEY (`id_venta`)"
+");",[],sqlWin,sqlFail);},txFail,txWin);this.query=function(query,params,fn){this.db.transaction(function(tx){tx.executeSql(query,params,fn,sqlFail);},txFail,txWin);}
var txFail=function(err){console.error("TX failed: "+err.message);}
var txWin=function(tx){}
var sqlFail=function(err){console.error("SQL failed: "+err.message,err);}
var sqlWin=function(tx,response){}};var db=new Database();var ActualizacionDePrecio=function(config)
{var _id_actualizacion=config===undefined?'':config.id_actualizacion||'',_id_producto=config===undefined?'':config.id_producto||'',_id_usuario=config===undefined?'':config.id_usuario||'',_precio_venta=config===undefined?'':config.precio_venta||'',_precio_venta_procesado=config===undefined?'':config.precio_venta_procesado||'',_precio_intersucursal=config===undefined?'':config.precio_intersucursal||'',_precio_intersucursal_procesado=config===undefined?'':config.precio_intersucursal_procesado||'',_precio_compra=config===undefined?'':config.precio_compra||'',_fecha=config===undefined?'':config.fecha||'';this.getIdActualizacion=function()
{return _id_actualizacion;};this.setIdActualizacion=function(id_actualizacion)
{_id_actualizacion=id_actualizacion;};this.getIdProducto=function()
{return _id_producto;};this.setIdProducto=function(id_producto)
{_id_producto=id_producto;};this.getIdUsuario=function()
{return _id_usuario;};this.setIdUsuario=function(id_usuario)
{_id_usuario=id_usuario;};this.getPrecioVenta=function()
{return _precio_venta;};this.setPrecioVenta=function(precio_venta)
{_precio_venta=precio_venta;};this.getPrecioVentaProcesado=function()
{return _precio_venta_procesado;};this.setPrecioVentaProcesado=function(precio_venta_procesado)
{_precio_venta_procesado=precio_venta_procesado;};this.getPrecioIntersucursal=function()
{return _precio_intersucursal;};this.setPrecioIntersucursal=function(precio_intersucursal)
{_precio_intersucursal=precio_intersucursal;};this.getPrecioIntersucursalProcesado=function()
{return _precio_intersucursal_procesado;};this.setPrecioIntersucursalProcesado=function(precio_intersucursal_procesado)
{_precio_intersucursal_procesado=precio_intersucursal_procesado;};this.getPrecioCompra=function()
{return _precio_compra;};this.setPrecioCompra=function(precio_compra)
{_precio_compra=precio_compra;};this.getFecha=function()
{return _fecha;};this.setFecha=function(fecha)
{_fecha=fecha;};this.json={id_actualizacion:_id_actualizacion,id_producto:_id_producto,id_usuario:_id_usuario,precio_venta:_precio_venta,precio_venta_procesado:_precio_venta_procesado,precio_intersucursal:_precio_intersucursal,precio_intersucursal_procesado:_precio_intersucursal_procesado,precio_compra:_precio_compra,fecha:_fecha};var _callback_stack=[];this.pushCallback=function(fn,context){_callback_stack.push({f:fn,c:context});}
this.callCallback=function(params){var t=_callback_stack.pop();t.f.call(t.c,params);}
this.save=function(callback)
{if(DEBUG)console.log('estoy en save()');this.pushCallback(callback,this);var cb=function(res){if(DEBUG)console.log('estoy de regreso en save(',res,') con el contexto:',this);if(res==null){create.call(this,null);}else{update.call(this,null);}};ActualizacionDePrecio.getByPK(this.getIdActualizacion(),{context:this,callback:cb})};this.search=function($orderBy,$orden)
{$sql="SELECT * from actualizacion_de_precio WHERE (";$val=[];if(this.getIdActualizacion()!=null){$sql+=" id_actualizacion = ? AND";$val.push(this.getIdActualizacion());}
if(this.getIdProducto()!=null){$sql+=" id_producto = ? AND";$val.push(this.getIdProducto());}
if(this.getIdUsuario()!=null){$sql+=" id_usuario = ? AND";$val.push(this.getIdUsuario());}
if(this.getPrecioVenta()!=null){$sql+=" precio_venta = ? AND";$val.push(this.getPrecioVenta());}
if(this.getPrecioVentaProcesado()!=null){$sql+=" precio_venta_procesado = ? AND";$val.push(this.getPrecioVentaProcesado());}
if(this.getPrecioIntersucursal()!=null){$sql+=" precio_intersucursal = ? AND";$val.push(this.getPrecioIntersucursal());}
if(this.getPrecioIntersucursalProcesado()!=null){$sql+=" precio_intersucursal_procesado = ? AND";$val.push(this.getPrecioIntersucursalProcesado());}
if(this.getPrecioCompra()!=null){$sql+=" precio_compra = ? AND";$val.push(this.getPrecioCompra());}
if(this.getFecha()!=null){$sql+=" fecha = ? AND";$val.push(this.getFecha());}
if($val.length==0){return[];}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};var create=function()
{if(DEBUG)console.log('estoy en create(this)');$sql="INSERT INTO actualizacion_de_precio ( id_actualizacion, id_producto, id_usuario, precio_venta, precio_venta_procesado, precio_intersucursal, precio_intersucursal_procesado, precio_compra, fecha ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?);";$params=[this.getIdActualizacion(),this.getIdProducto(),this.getIdUsuario(),this.getPrecioVenta(),this.getPrecioVentaProcesado(),this.getPrecioIntersucursal(),this.getPrecioIntersucursalProcesado(),this.getPrecioCompra(),this.getFecha(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de insert():',tx,results);old_this.callCallback(old_this);});return;};var update=function()
{if(DEBUG)console.log('estoy en update(',this,')');$sql="UPDATE actualizacion_de_precio SET  id_producto = ?, id_usuario = ?, precio_venta = ?, precio_venta_procesado = ?, precio_intersucursal = ?, precio_intersucursal_procesado = ?, precio_compra = ?, fecha = ? WHERE  id_actualizacion = ?;";$params=[this.getIdProducto(),this.getIdUsuario(),this.getPrecioVenta(),this.getPrecioVentaProcesado(),this.getPrecioIntersucursal(),this.getPrecioIntersucursalProcesado(),this.getPrecioCompra(),this.getFecha(),this.getIdActualizacion(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de update():',tx,results,this);old_this.callCallback(old_this);});return;};this.destruct=function(config)
{$sql="DELETE FROM actualizacion_de_precio WHERE  id_actualizacion = ?;";$params=[this.getIdActualizacion()];if(DEBUG){console.log($sql,$params);}
db.query($sql,$params,function(tx,results){config.callback.call(config.context||null,results);});return;};this.byRange=function($actualizacion_de_precio,$orderBy,$orden)
{$sql="SELECT * from actualizacion_de_precio WHERE (";$val=[];if((($a=this.getIdActualizacion())!=null)&(($b=$actualizacion_de_precio.getIdActualizacion())!=null)){$sql+=" id_actualizacion >= ? AND id_actualizacion <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_actualizacion = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdProducto())!=null)&(($b=$actualizacion_de_precio.getIdProducto())!=null)){$sql+=" id_producto >= ? AND id_producto <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_producto = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdUsuario())!=null)&(($b=$actualizacion_de_precio.getIdUsuario())!=null)){$sql+=" id_usuario >= ? AND id_usuario <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_usuario = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getPrecioVenta())!=null)&(($b=$actualizacion_de_precio.getPrecioVenta())!=null)){$sql+=" precio_venta >= ? AND precio_venta <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" precio_venta = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getPrecioVentaProcesado())!=null)&(($b=$actualizacion_de_precio.getPrecioVentaProcesado())!=null)){$sql+=" precio_venta_procesado >= ? AND precio_venta_procesado <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" precio_venta_procesado = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getPrecioIntersucursal())!=null)&(($b=$actualizacion_de_precio.getPrecioIntersucursal())!=null)){$sql+=" precio_intersucursal >= ? AND precio_intersucursal <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" precio_intersucursal = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getPrecioIntersucursalProcesado())!=null)&(($b=$actualizacion_de_precio.getPrecioIntersucursalProcesado())!=null)){$sql+=" precio_intersucursal_procesado >= ? AND precio_intersucursal_procesado <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" precio_intersucursal_procesado = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getPrecioCompra())!=null)&(($b=$actualizacion_de_precio.getPrecioCompra())!=null)){$sql+=" precio_compra >= ? AND precio_compra <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" precio_compra = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getFecha())!=null)&(($b=$actualizacion_de_precio.getFecha())!=null)){$sql+=" fecha >= ? AND fecha <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" fecha = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};}
ActualizacionDePrecio.getAll=function(config)
{$sql="SELECT * from actualizacion_de_precio";if(config.orden!==undefined)
$sql+=" ORDER BY "+config.orden+" "+config.tipo_de_orden;if(config.pagina!==undefined)
$sql+=" LIMIT "+((config.pagina-1)*config.columnas_por_pagina)+","+config.columnas_por_pagina;db.query($sql,[],function(tx,results){fres=[];for(i=0;i<results.rows.length;i++){fres.push(new ActualizacionDePrecio(results.rows.item(i)))}
config.callback.call(config.context||null,fres);});return;};ActualizacionDePrecio.getByPK=function($id_actualizacion,config)
{if(DEBUG)console.log('estoy en getbypk()');$sql="SELECT * FROM actualizacion_de_precio WHERE (id_actualizacion = ? ) LIMIT 1;";db.query($sql,[$id_actualizacion],function(tx,results){if(DEBUG)console.log('ya termine el query de getByPK():',tx,results);if(results.rows.length==0)fres=null;else fres=new ActualizacionDePrecio(results.rows.item(0));config.callback.call(config.context||null,fres);});return;};var Autorizacion=function(config)
{var _id_autorizacion=config===undefined?'':config.id_autorizacion||'',_id_usuario=config===undefined?'':config.id_usuario||'',_id_sucursal=config===undefined?'':config.id_sucursal||'',_fecha_peticion=config===undefined?'':config.fecha_peticion||'',_fecha_respuesta=config===undefined?'':config.fecha_respuesta||'',_estado=config===undefined?'':config.estado||'',_parametros=config===undefined?'':config.parametros||'',_tipo=config===undefined?'':config.tipo||'';this.getIdAutorizacion=function()
{return _id_autorizacion;};this.setIdAutorizacion=function(id_autorizacion)
{_id_autorizacion=id_autorizacion;};this.getIdUsuario=function()
{return _id_usuario;};this.setIdUsuario=function(id_usuario)
{_id_usuario=id_usuario;};this.getIdSucursal=function()
{return _id_sucursal;};this.setIdSucursal=function(id_sucursal)
{_id_sucursal=id_sucursal;};this.getFechaPeticion=function()
{return _fecha_peticion;};this.setFechaPeticion=function(fecha_peticion)
{_fecha_peticion=fecha_peticion;};this.getFechaRespuesta=function()
{return _fecha_respuesta;};this.setFechaRespuesta=function(fecha_respuesta)
{_fecha_respuesta=fecha_respuesta;};this.getEstado=function()
{return _estado;};this.setEstado=function(estado)
{_estado=estado;};this.getParametros=function()
{return _parametros;};this.setParametros=function(parametros)
{_parametros=parametros;};this.getTipo=function()
{return _tipo;};this.setTipo=function(tipo)
{_tipo=tipo;};this.json={id_autorizacion:_id_autorizacion,id_usuario:_id_usuario,id_sucursal:_id_sucursal,fecha_peticion:_fecha_peticion,fecha_respuesta:_fecha_respuesta,estado:_estado,parametros:_parametros,tipo:_tipo};var _callback_stack=[];this.pushCallback=function(fn,context){_callback_stack.push({f:fn,c:context});}
this.callCallback=function(params){var t=_callback_stack.pop();t.f.call(t.c,params);}
this.save=function(callback)
{if(DEBUG)console.log('estoy en save()');this.pushCallback(callback,this);var cb=function(res){if(DEBUG)console.log('estoy de regreso en save(',res,') con el contexto:',this);if(res==null){create.call(this,null);}else{update.call(this,null);}};Autorizacion.getByPK(this.getIdAutorizacion(),{context:this,callback:cb})};this.search=function($orderBy,$orden)
{$sql="SELECT * from autorizacion WHERE (";$val=[];if(this.getIdAutorizacion()!=null){$sql+=" id_autorizacion = ? AND";$val.push(this.getIdAutorizacion());}
if(this.getIdUsuario()!=null){$sql+=" id_usuario = ? AND";$val.push(this.getIdUsuario());}
if(this.getIdSucursal()!=null){$sql+=" id_sucursal = ? AND";$val.push(this.getIdSucursal());}
if(this.getFechaPeticion()!=null){$sql+=" fecha_peticion = ? AND";$val.push(this.getFechaPeticion());}
if(this.getFechaRespuesta()!=null){$sql+=" fecha_respuesta = ? AND";$val.push(this.getFechaRespuesta());}
if(this.getEstado()!=null){$sql+=" estado = ? AND";$val.push(this.getEstado());}
if(this.getParametros()!=null){$sql+=" parametros = ? AND";$val.push(this.getParametros());}
if(this.getTipo()!=null){$sql+=" tipo = ? AND";$val.push(this.getTipo());}
if($val.length==0){return[];}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};var create=function()
{if(DEBUG)console.log('estoy en create(this)');$sql="INSERT INTO autorizacion ( id_autorizacion, id_usuario, id_sucursal, fecha_peticion, fecha_respuesta, estado, parametros, tipo ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?);";$params=[this.getIdAutorizacion(),this.getIdUsuario(),this.getIdSucursal(),this.getFechaPeticion(),this.getFechaRespuesta(),this.getEstado(),this.getParametros(),this.getTipo(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de insert():',tx,results);old_this.callCallback(old_this);});return;};var update=function()
{if(DEBUG)console.log('estoy en update(',this,')');$sql="UPDATE autorizacion SET  id_usuario = ?, id_sucursal = ?, fecha_peticion = ?, fecha_respuesta = ?, estado = ?, parametros = ?, tipo = ? WHERE  id_autorizacion = ?;";$params=[this.getIdUsuario(),this.getIdSucursal(),this.getFechaPeticion(),this.getFechaRespuesta(),this.getEstado(),this.getParametros(),this.getTipo(),this.getIdAutorizacion(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de update():',tx,results,this);old_this.callCallback(old_this);});return;};this.destruct=function(config)
{$sql="DELETE FROM autorizacion WHERE  id_autorizacion = ?;";$params=[this.getIdAutorizacion()];if(DEBUG){console.log($sql,$params);}
db.query($sql,$params,function(tx,results){config.callback.call(config.context||null,results);});return;};this.byRange=function($autorizacion,$orderBy,$orden)
{$sql="SELECT * from autorizacion WHERE (";$val=[];if((($a=this.getIdAutorizacion())!=null)&(($b=$autorizacion.getIdAutorizacion())!=null)){$sql+=" id_autorizacion >= ? AND id_autorizacion <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_autorizacion = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdUsuario())!=null)&(($b=$autorizacion.getIdUsuario())!=null)){$sql+=" id_usuario >= ? AND id_usuario <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_usuario = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdSucursal())!=null)&(($b=$autorizacion.getIdSucursal())!=null)){$sql+=" id_sucursal >= ? AND id_sucursal <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_sucursal = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getFechaPeticion())!=null)&(($b=$autorizacion.getFechaPeticion())!=null)){$sql+=" fecha_peticion >= ? AND fecha_peticion <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" fecha_peticion = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getFechaRespuesta())!=null)&(($b=$autorizacion.getFechaRespuesta())!=null)){$sql+=" fecha_respuesta >= ? AND fecha_respuesta <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" fecha_respuesta = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getEstado())!=null)&(($b=$autorizacion.getEstado())!=null)){$sql+=" estado >= ? AND estado <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" estado = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getParametros())!=null)&(($b=$autorizacion.getParametros())!=null)){$sql+=" parametros >= ? AND parametros <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" parametros = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getTipo())!=null)&(($b=$autorizacion.getTipo())!=null)){$sql+=" tipo >= ? AND tipo <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" tipo = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};}
Autorizacion.getAll=function(config)
{$sql="SELECT * from autorizacion";if(config.orden!==undefined)
$sql+=" ORDER BY "+config.orden+" "+config.tipo_de_orden;if(config.pagina!==undefined)
$sql+=" LIMIT "+((config.pagina-1)*config.columnas_por_pagina)+","+config.columnas_por_pagina;db.query($sql,[],function(tx,results){fres=[];for(i=0;i<results.rows.length;i++){fres.push(new Autorizacion(results.rows.item(i)))}
config.callback.call(config.context||null,fres);});return;};Autorizacion.getByPK=function($id_autorizacion,config)
{if(DEBUG)console.log('estoy en getbypk()');$sql="SELECT * FROM autorizacion WHERE (id_autorizacion = ? ) LIMIT 1;";db.query($sql,[$id_autorizacion],function(tx,results){if(DEBUG)console.log('ya termine el query de getByPK():',tx,results);if(results.rows.length==0)fres=null;else fres=new Autorizacion(results.rows.item(0));config.callback.call(config.context||null,fres);});return;};var Cliente=function(config)
{var _id_cliente=config===undefined?'':config.id_cliente||'',_rfc=config===undefined?'':config.rfc||'',_razon_social=config===undefined?'':config.razon_social||'',_calle=config===undefined?'':config.calle||'',_numero_exterior=config===undefined?'':config.numero_exterior||'',_numero_interior=config===undefined?'':config.numero_interior||'',_colonia=config===undefined?'':config.colonia||'',_referencia=config===undefined?'':config.referencia||'',_localidad=config===undefined?'':config.localidad||'',_municipio=config===undefined?'':config.municipio||'',_estado=config===undefined?'':config.estado||'',_pais=config===undefined?'':config.pais||'',_codigo_postal=config===undefined?'':config.codigo_postal||'',_telefono=config===undefined?'':config.telefono||'',_e_mail=config===undefined?'':config.e_mail||'',_limite_credito=config===undefined?'':config.limite_credito||'',_descuento=config===undefined?'':config.descuento||'',_activo=config===undefined?'':config.activo||'',_id_usuario=config===undefined?'':config.id_usuario||'',_id_sucursal=config===undefined?'':config.id_sucursal||'',_fecha_ingreso=config===undefined?'':config.fecha_ingreso||'',_password=config===undefined?'':config.password||'',_last_login=config===undefined?'':config.last_login||'',_grant_changes=config===undefined?'':config.grant_changes||'';this.getIdCliente=function()
{return _id_cliente;};this.setIdCliente=function(id_cliente)
{_id_cliente=id_cliente;};this.getRfc=function()
{return _rfc;};this.setRfc=function(rfc)
{_rfc=rfc;};this.getRazonSocial=function()
{return _razon_social;};this.setRazonSocial=function(razon_social)
{_razon_social=razon_social;};this.getCalle=function()
{return _calle;};this.setCalle=function(calle)
{_calle=calle;};this.getNumeroExterior=function()
{return _numero_exterior;};this.setNumeroExterior=function(numero_exterior)
{_numero_exterior=numero_exterior;};this.getNumeroInterior=function()
{return _numero_interior;};this.setNumeroInterior=function(numero_interior)
{_numero_interior=numero_interior;};this.getColonia=function()
{return _colonia;};this.setColonia=function(colonia)
{_colonia=colonia;};this.getReferencia=function()
{return _referencia;};this.setReferencia=function(referencia)
{_referencia=referencia;};this.getLocalidad=function()
{return _localidad;};this.setLocalidad=function(localidad)
{_localidad=localidad;};this.getMunicipio=function()
{return _municipio;};this.setMunicipio=function(municipio)
{_municipio=municipio;};this.getEstado=function()
{return _estado;};this.setEstado=function(estado)
{_estado=estado;};this.getPais=function()
{return _pais;};this.setPais=function(pais)
{_pais=pais;};this.getCodigoPostal=function()
{return _codigo_postal;};this.setCodigoPostal=function(codigo_postal)
{_codigo_postal=codigo_postal;};this.getTelefono=function()
{return _telefono;};this.setTelefono=function(telefono)
{_telefono=telefono;};this.getEMail=function()
{return _e_mail;};this.setEMail=function(e_mail)
{_e_mail=e_mail;};this.getLimiteCredito=function()
{return _limite_credito;};this.setLimiteCredito=function(limite_credito)
{_limite_credito=limite_credito;};this.getDescuento=function()
{return _descuento;};this.setDescuento=function(descuento)
{_descuento=descuento;};this.getActivo=function()
{return _activo;};this.setActivo=function(activo)
{_activo=activo;};this.getIdUsuario=function()
{return _id_usuario;};this.setIdUsuario=function(id_usuario)
{_id_usuario=id_usuario;};this.getIdSucursal=function()
{return _id_sucursal;};this.setIdSucursal=function(id_sucursal)
{_id_sucursal=id_sucursal;};this.getFechaIngreso=function()
{return _fecha_ingreso;};this.setFechaIngreso=function(fecha_ingreso)
{_fecha_ingreso=fecha_ingreso;};this.getPassword=function()
{return _password;};this.setPassword=function(password)
{_password=password;};this.getLastLogin=function()
{return _last_login;};this.setLastLogin=function(last_login)
{_last_login=last_login;};this.getGrantChanges=function()
{return _grant_changes;};this.setGrantChanges=function(grant_changes)
{_grant_changes=grant_changes;};this.json={id_cliente:_id_cliente,rfc:_rfc,razon_social:_razon_social,calle:_calle,numero_exterior:_numero_exterior,numero_interior:_numero_interior,colonia:_colonia,referencia:_referencia,localidad:_localidad,municipio:_municipio,estado:_estado,pais:_pais,codigo_postal:_codigo_postal,telefono:_telefono,e_mail:_e_mail,limite_credito:_limite_credito,descuento:_descuento,activo:_activo,id_usuario:_id_usuario,id_sucursal:_id_sucursal,fecha_ingreso:_fecha_ingreso,password:_password,last_login:_last_login,grant_changes:_grant_changes};var _callback_stack=[];this.pushCallback=function(fn,context){_callback_stack.push({f:fn,c:context});}
this.callCallback=function(params){var t=_callback_stack.pop();t.f.call(t.c,params);}
this.save=function(callback)
{if(DEBUG)console.log('estoy en save()');this.pushCallback(callback,this);var cb=function(res){if(DEBUG)console.log('estoy de regreso en save(',res,') con el contexto:',this);if(res==null){create.call(this,null);}else{update.call(this,null);}};Cliente.getByPK(this.getIdCliente(),{context:this,callback:cb})};this.search=function($orderBy,$orden)
{$sql="SELECT * from cliente WHERE (";$val=[];if(this.getIdCliente()!=null){$sql+=" id_cliente = ? AND";$val.push(this.getIdCliente());}
if(this.getRfc()!=null){$sql+=" rfc = ? AND";$val.push(this.getRfc());}
if(this.getRazonSocial()!=null){$sql+=" razon_social = ? AND";$val.push(this.getRazonSocial());}
if(this.getCalle()!=null){$sql+=" calle = ? AND";$val.push(this.getCalle());}
if(this.getNumeroExterior()!=null){$sql+=" numero_exterior = ? AND";$val.push(this.getNumeroExterior());}
if(this.getNumeroInterior()!=null){$sql+=" numero_interior = ? AND";$val.push(this.getNumeroInterior());}
if(this.getColonia()!=null){$sql+=" colonia = ? AND";$val.push(this.getColonia());}
if(this.getReferencia()!=null){$sql+=" referencia = ? AND";$val.push(this.getReferencia());}
if(this.getLocalidad()!=null){$sql+=" localidad = ? AND";$val.push(this.getLocalidad());}
if(this.getMunicipio()!=null){$sql+=" municipio = ? AND";$val.push(this.getMunicipio());}
if(this.getEstado()!=null){$sql+=" estado = ? AND";$val.push(this.getEstado());}
if(this.getPais()!=null){$sql+=" pais = ? AND";$val.push(this.getPais());}
if(this.getCodigoPostal()!=null){$sql+=" codigo_postal = ? AND";$val.push(this.getCodigoPostal());}
if(this.getTelefono()!=null){$sql+=" telefono = ? AND";$val.push(this.getTelefono());}
if(this.getEMail()!=null){$sql+=" e_mail = ? AND";$val.push(this.getEMail());}
if(this.getLimiteCredito()!=null){$sql+=" limite_credito = ? AND";$val.push(this.getLimiteCredito());}
if(this.getDescuento()!=null){$sql+=" descuento = ? AND";$val.push(this.getDescuento());}
if(this.getActivo()!=null){$sql+=" activo = ? AND";$val.push(this.getActivo());}
if(this.getIdUsuario()!=null){$sql+=" id_usuario = ? AND";$val.push(this.getIdUsuario());}
if(this.getIdSucursal()!=null){$sql+=" id_sucursal = ? AND";$val.push(this.getIdSucursal());}
if(this.getFechaIngreso()!=null){$sql+=" fecha_ingreso = ? AND";$val.push(this.getFechaIngreso());}
if(this.getPassword()!=null){$sql+=" password = ? AND";$val.push(this.getPassword());}
if(this.getLastLogin()!=null){$sql+=" last_login = ? AND";$val.push(this.getLastLogin());}
if(this.getGrantChanges()!=null){$sql+=" grant_changes = ? AND";$val.push(this.getGrantChanges());}
if($val.length==0){return[];}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};var create=function()
{if(DEBUG)console.log('estoy en create(this)');$sql="INSERT INTO cliente ( id_cliente, rfc, razon_social, calle, numero_exterior, numero_interior, colonia, referencia, localidad, municipio, estado, pais, codigo_postal, telefono, e_mail, limite_credito, descuento, activo, id_usuario, id_sucursal, fecha_ingreso, password, last_login, grant_changes ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";$params=[this.getIdCliente(),this.getRfc(),this.getRazonSocial(),this.getCalle(),this.getNumeroExterior(),this.getNumeroInterior(),this.getColonia(),this.getReferencia(),this.getLocalidad(),this.getMunicipio(),this.getEstado(),this.getPais(),this.getCodigoPostal(),this.getTelefono(),this.getEMail(),this.getLimiteCredito(),this.getDescuento(),this.getActivo(),this.getIdUsuario(),this.getIdSucursal(),this.getFechaIngreso(),this.getPassword(),this.getLastLogin(),this.getGrantChanges(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de insert():',tx,results);old_this.callCallback(old_this);});return;};var update=function()
{if(DEBUG)console.log('estoy en update(',this,')');$sql="UPDATE cliente SET  rfc = ?, razon_social = ?, calle = ?, numero_exterior = ?, numero_interior = ?, colonia = ?, referencia = ?, localidad = ?, municipio = ?, estado = ?, pais = ?, codigo_postal = ?, telefono = ?, e_mail = ?, limite_credito = ?, descuento = ?, activo = ?, id_usuario = ?, id_sucursal = ?, fecha_ingreso = ?, password = ?, last_login = ?, grant_changes = ? WHERE  id_cliente = ?;";$params=[this.getRfc(),this.getRazonSocial(),this.getCalle(),this.getNumeroExterior(),this.getNumeroInterior(),this.getColonia(),this.getReferencia(),this.getLocalidad(),this.getMunicipio(),this.getEstado(),this.getPais(),this.getCodigoPostal(),this.getTelefono(),this.getEMail(),this.getLimiteCredito(),this.getDescuento(),this.getActivo(),this.getIdUsuario(),this.getIdSucursal(),this.getFechaIngreso(),this.getPassword(),this.getLastLogin(),this.getGrantChanges(),this.getIdCliente(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de update():',tx,results,this);old_this.callCallback(old_this);});return;};this.destruct=function(config)
{$sql="DELETE FROM cliente WHERE  id_cliente = ?;";$params=[this.getIdCliente()];if(DEBUG){console.log($sql,$params);}
db.query($sql,$params,function(tx,results){config.callback.call(config.context||null,results);});return;};this.byRange=function($cliente,$orderBy,$orden)
{$sql="SELECT * from cliente WHERE (";$val=[];if((($a=this.getIdCliente())!=null)&(($b=$cliente.getIdCliente())!=null)){$sql+=" id_cliente >= ? AND id_cliente <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_cliente = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getRfc())!=null)&(($b=$cliente.getRfc())!=null)){$sql+=" rfc >= ? AND rfc <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" rfc = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getRazonSocial())!=null)&(($b=$cliente.getRazonSocial())!=null)){$sql+=" razon_social >= ? AND razon_social <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" razon_social = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getCalle())!=null)&(($b=$cliente.getCalle())!=null)){$sql+=" calle >= ? AND calle <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" calle = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getNumeroExterior())!=null)&(($b=$cliente.getNumeroExterior())!=null)){$sql+=" numero_exterior >= ? AND numero_exterior <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" numero_exterior = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getNumeroInterior())!=null)&(($b=$cliente.getNumeroInterior())!=null)){$sql+=" numero_interior >= ? AND numero_interior <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" numero_interior = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getColonia())!=null)&(($b=$cliente.getColonia())!=null)){$sql+=" colonia >= ? AND colonia <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" colonia = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getReferencia())!=null)&(($b=$cliente.getReferencia())!=null)){$sql+=" referencia >= ? AND referencia <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" referencia = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getLocalidad())!=null)&(($b=$cliente.getLocalidad())!=null)){$sql+=" localidad >= ? AND localidad <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" localidad = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getMunicipio())!=null)&(($b=$cliente.getMunicipio())!=null)){$sql+=" municipio >= ? AND municipio <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" municipio = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getEstado())!=null)&(($b=$cliente.getEstado())!=null)){$sql+=" estado >= ? AND estado <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" estado = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getPais())!=null)&(($b=$cliente.getPais())!=null)){$sql+=" pais >= ? AND pais <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" pais = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getCodigoPostal())!=null)&(($b=$cliente.getCodigoPostal())!=null)){$sql+=" codigo_postal >= ? AND codigo_postal <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" codigo_postal = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getTelefono())!=null)&(($b=$cliente.getTelefono())!=null)){$sql+=" telefono >= ? AND telefono <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" telefono = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getEMail())!=null)&(($b=$cliente.getEMail())!=null)){$sql+=" e_mail >= ? AND e_mail <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" e_mail = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getLimiteCredito())!=null)&(($b=$cliente.getLimiteCredito())!=null)){$sql+=" limite_credito >= ? AND limite_credito <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" limite_credito = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getDescuento())!=null)&(($b=$cliente.getDescuento())!=null)){$sql+=" descuento >= ? AND descuento <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" descuento = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getActivo())!=null)&(($b=$cliente.getActivo())!=null)){$sql+=" activo >= ? AND activo <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" activo = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdUsuario())!=null)&(($b=$cliente.getIdUsuario())!=null)){$sql+=" id_usuario >= ? AND id_usuario <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_usuario = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdSucursal())!=null)&(($b=$cliente.getIdSucursal())!=null)){$sql+=" id_sucursal >= ? AND id_sucursal <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_sucursal = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getFechaIngreso())!=null)&(($b=$cliente.getFechaIngreso())!=null)){$sql+=" fecha_ingreso >= ? AND fecha_ingreso <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" fecha_ingreso = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getPassword())!=null)&(($b=$cliente.getPassword())!=null)){$sql+=" password >= ? AND password <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" password = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getLastLogin())!=null)&(($b=$cliente.getLastLogin())!=null)){$sql+=" last_login >= ? AND last_login <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" last_login = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getGrantChanges())!=null)&(($b=$cliente.getGrantChanges())!=null)){$sql+=" grant_changes >= ? AND grant_changes <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" grant_changes = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};}
Cliente.getAll=function(config)
{$sql="SELECT * from cliente";if(config.orden!==undefined)
$sql+=" ORDER BY "+config.orden+" "+config.tipo_de_orden;if(config.pagina!==undefined)
$sql+=" LIMIT "+((config.pagina-1)*config.columnas_por_pagina)+","+config.columnas_por_pagina;db.query($sql,[],function(tx,results){fres=[];for(i=0;i<results.rows.length;i++){fres.push(new Cliente(results.rows.item(i)))}
config.callback.call(config.context||null,fres);});return;};Cliente.getByPK=function($id_cliente,config)
{if(DEBUG)console.log('estoy en getbypk()');$sql="SELECT * FROM cliente WHERE (id_cliente = ? ) LIMIT 1;";db.query($sql,[$id_cliente],function(tx,results){if(DEBUG)console.log('ya termine el query de getByPK():',tx,results);if(results.rows.length==0)fres=null;else fres=new Cliente(results.rows.item(0));config.callback.call(config.context||null,fres);});return;};var CompraCliente=function(config)
{var _id_compra=config===undefined?'':config.id_compra||'',_id_cliente=config===undefined?'':config.id_cliente||'',_tipo_compra=config===undefined?'':config.tipo_compra||'',_tipo_pago=config===undefined?'':config.tipo_pago||'',_fecha=config===undefined?'':config.fecha||'',_subtotal=config===undefined?'':config.subtotal||'',_impuesto=config===undefined?'':config.impuesto||'',_descuento=config===undefined?'':config.descuento||'',_total=config===undefined?'':config.total||'',_id_sucursal=config===undefined?'':config.id_sucursal||'',_id_usuario=config===undefined?'':config.id_usuario||'',_pagado=config===undefined?'':config.pagado||'',_cancelada=config===undefined?'':config.cancelada||'',_ip=config===undefined?'':config.ip||'',_liquidada=config===undefined?'':config.liquidada||'';this.getIdCompra=function()
{return _id_compra;};this.setIdCompra=function(id_compra)
{_id_compra=id_compra;};this.getIdCliente=function()
{return _id_cliente;};this.setIdCliente=function(id_cliente)
{_id_cliente=id_cliente;};this.getTipoCompra=function()
{return _tipo_compra;};this.setTipoCompra=function(tipo_compra)
{_tipo_compra=tipo_compra;};this.getTipoPago=function()
{return _tipo_pago;};this.setTipoPago=function(tipo_pago)
{_tipo_pago=tipo_pago;};this.getFecha=function()
{return _fecha;};this.setFecha=function(fecha)
{_fecha=fecha;};this.getSubtotal=function()
{return _subtotal;};this.setSubtotal=function(subtotal)
{_subtotal=subtotal;};this.getImpuesto=function()
{return _impuesto;};this.setImpuesto=function(impuesto)
{_impuesto=impuesto;};this.getDescuento=function()
{return _descuento;};this.setDescuento=function(descuento)
{_descuento=descuento;};this.getTotal=function()
{return _total;};this.setTotal=function(total)
{_total=total;};this.getIdSucursal=function()
{return _id_sucursal;};this.setIdSucursal=function(id_sucursal)
{_id_sucursal=id_sucursal;};this.getIdUsuario=function()
{return _id_usuario;};this.setIdUsuario=function(id_usuario)
{_id_usuario=id_usuario;};this.getPagado=function()
{return _pagado;};this.setPagado=function(pagado)
{_pagado=pagado;};this.getCancelada=function()
{return _cancelada;};this.setCancelada=function(cancelada)
{_cancelada=cancelada;};this.getIp=function()
{return _ip;};this.setIp=function(ip)
{_ip=ip;};this.getLiquidada=function()
{return _liquidada;};this.setLiquidada=function(liquidada)
{_liquidada=liquidada;};this.json={id_compra:_id_compra,id_cliente:_id_cliente,tipo_compra:_tipo_compra,tipo_pago:_tipo_pago,fecha:_fecha,subtotal:_subtotal,impuesto:_impuesto,descuento:_descuento,total:_total,id_sucursal:_id_sucursal,id_usuario:_id_usuario,pagado:_pagado,cancelada:_cancelada,ip:_ip,liquidada:_liquidada};var _callback_stack=[];this.pushCallback=function(fn,context){_callback_stack.push({f:fn,c:context});}
this.callCallback=function(params){var t=_callback_stack.pop();t.f.call(t.c,params);}
this.save=function(callback)
{if(DEBUG)console.log('estoy en save()');this.pushCallback(callback,this);var cb=function(res){if(DEBUG)console.log('estoy de regreso en save(',res,') con el contexto:',this);if(res==null){create.call(this,null);}else{update.call(this,null);}};CompraCliente.getByPK(this.getIdCompra(),{context:this,callback:cb})};this.search=function($orderBy,$orden)
{$sql="SELECT * from compra_cliente WHERE (";$val=[];if(this.getIdCompra()!=null){$sql+=" id_compra = ? AND";$val.push(this.getIdCompra());}
if(this.getIdCliente()!=null){$sql+=" id_cliente = ? AND";$val.push(this.getIdCliente());}
if(this.getTipoCompra()!=null){$sql+=" tipo_compra = ? AND";$val.push(this.getTipoCompra());}
if(this.getTipoPago()!=null){$sql+=" tipo_pago = ? AND";$val.push(this.getTipoPago());}
if(this.getFecha()!=null){$sql+=" fecha = ? AND";$val.push(this.getFecha());}
if(this.getSubtotal()!=null){$sql+=" subtotal = ? AND";$val.push(this.getSubtotal());}
if(this.getImpuesto()!=null){$sql+=" impuesto = ? AND";$val.push(this.getImpuesto());}
if(this.getDescuento()!=null){$sql+=" descuento = ? AND";$val.push(this.getDescuento());}
if(this.getTotal()!=null){$sql+=" total = ? AND";$val.push(this.getTotal());}
if(this.getIdSucursal()!=null){$sql+=" id_sucursal = ? AND";$val.push(this.getIdSucursal());}
if(this.getIdUsuario()!=null){$sql+=" id_usuario = ? AND";$val.push(this.getIdUsuario());}
if(this.getPagado()!=null){$sql+=" pagado = ? AND";$val.push(this.getPagado());}
if(this.getCancelada()!=null){$sql+=" cancelada = ? AND";$val.push(this.getCancelada());}
if(this.getIp()!=null){$sql+=" ip = ? AND";$val.push(this.getIp());}
if(this.getLiquidada()!=null){$sql+=" liquidada = ? AND";$val.push(this.getLiquidada());}
if($val.length==0){return[];}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};var create=function()
{if(DEBUG)console.log('estoy en create(this)');$sql="INSERT INTO compra_cliente ( id_compra, id_cliente, tipo_compra, tipo_pago, fecha, subtotal, impuesto, descuento, total, id_sucursal, id_usuario, pagado, cancelada, ip, liquidada ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";$params=[this.getIdCompra(),this.getIdCliente(),this.getTipoCompra(),this.getTipoPago(),this.getFecha(),this.getSubtotal(),this.getImpuesto(),this.getDescuento(),this.getTotal(),this.getIdSucursal(),this.getIdUsuario(),this.getPagado(),this.getCancelada(),this.getIp(),this.getLiquidada(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de insert():',tx,results);old_this.callCallback(old_this);});return;};var update=function()
{if(DEBUG)console.log('estoy en update(',this,')');$sql="UPDATE compra_cliente SET  id_cliente = ?, tipo_compra = ?, tipo_pago = ?, fecha = ?, subtotal = ?, impuesto = ?, descuento = ?, total = ?, id_sucursal = ?, id_usuario = ?, pagado = ?, cancelada = ?, ip = ?, liquidada = ? WHERE  id_compra = ?;";$params=[this.getIdCliente(),this.getTipoCompra(),this.getTipoPago(),this.getFecha(),this.getSubtotal(),this.getImpuesto(),this.getDescuento(),this.getTotal(),this.getIdSucursal(),this.getIdUsuario(),this.getPagado(),this.getCancelada(),this.getIp(),this.getLiquidada(),this.getIdCompra(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de update():',tx,results,this);old_this.callCallback(old_this);});return;};this.destruct=function(config)
{$sql="DELETE FROM compra_cliente WHERE  id_compra = ?;";$params=[this.getIdCompra()];if(DEBUG){console.log($sql,$params);}
db.query($sql,$params,function(tx,results){config.callback.call(config.context||null,results);});return;};this.byRange=function($compra_cliente,$orderBy,$orden)
{$sql="SELECT * from compra_cliente WHERE (";$val=[];if((($a=this.getIdCompra())!=null)&(($b=$compra_cliente.getIdCompra())!=null)){$sql+=" id_compra >= ? AND id_compra <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_compra = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdCliente())!=null)&(($b=$compra_cliente.getIdCliente())!=null)){$sql+=" id_cliente >= ? AND id_cliente <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_cliente = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getTipoCompra())!=null)&(($b=$compra_cliente.getTipoCompra())!=null)){$sql+=" tipo_compra >= ? AND tipo_compra <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" tipo_compra = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getTipoPago())!=null)&(($b=$compra_cliente.getTipoPago())!=null)){$sql+=" tipo_pago >= ? AND tipo_pago <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" tipo_pago = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getFecha())!=null)&(($b=$compra_cliente.getFecha())!=null)){$sql+=" fecha >= ? AND fecha <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" fecha = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getSubtotal())!=null)&(($b=$compra_cliente.getSubtotal())!=null)){$sql+=" subtotal >= ? AND subtotal <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" subtotal = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getImpuesto())!=null)&(($b=$compra_cliente.getImpuesto())!=null)){$sql+=" impuesto >= ? AND impuesto <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" impuesto = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getDescuento())!=null)&(($b=$compra_cliente.getDescuento())!=null)){$sql+=" descuento >= ? AND descuento <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" descuento = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getTotal())!=null)&(($b=$compra_cliente.getTotal())!=null)){$sql+=" total >= ? AND total <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" total = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdSucursal())!=null)&(($b=$compra_cliente.getIdSucursal())!=null)){$sql+=" id_sucursal >= ? AND id_sucursal <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_sucursal = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdUsuario())!=null)&(($b=$compra_cliente.getIdUsuario())!=null)){$sql+=" id_usuario >= ? AND id_usuario <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_usuario = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getPagado())!=null)&(($b=$compra_cliente.getPagado())!=null)){$sql+=" pagado >= ? AND pagado <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" pagado = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getCancelada())!=null)&(($b=$compra_cliente.getCancelada())!=null)){$sql+=" cancelada >= ? AND cancelada <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" cancelada = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIp())!=null)&(($b=$compra_cliente.getIp())!=null)){$sql+=" ip >= ? AND ip <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" ip = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getLiquidada())!=null)&(($b=$compra_cliente.getLiquidada())!=null)){$sql+=" liquidada >= ? AND liquidada <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" liquidada = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};}
CompraCliente.getAll=function(config)
{$sql="SELECT * from compra_cliente";if(config.orden!==undefined)
$sql+=" ORDER BY "+config.orden+" "+config.tipo_de_orden;if(config.pagina!==undefined)
$sql+=" LIMIT "+((config.pagina-1)*config.columnas_por_pagina)+","+config.columnas_por_pagina;db.query($sql,[],function(tx,results){fres=[];for(i=0;i<results.rows.length;i++){fres.push(new CompraCliente(results.rows.item(i)))}
config.callback.call(config.context||null,fres);});return;};CompraCliente.getByPK=function($id_compra,config)
{if(DEBUG)console.log('estoy en getbypk()');$sql="SELECT * FROM compra_cliente WHERE (id_compra = ? ) LIMIT 1;";db.query($sql,[$id_compra],function(tx,results){if(DEBUG)console.log('ya termine el query de getByPK():',tx,results);if(results.rows.length==0)fres=null;else fres=new CompraCliente(results.rows.item(0));config.callback.call(config.context||null,fres);});return;};var DetalleCompraCliente=function(config)
{var _id_compra=config===undefined?'':config.id_compra||'',_id_producto=config===undefined?'':config.id_producto||'',_cantidad=config===undefined?'':config.cantidad||'',_precio=config===undefined?'':config.precio||'',_descuento=config===undefined?'':config.descuento||'';this.getIdCompra=function()
{return _id_compra;};this.setIdCompra=function(id_compra)
{_id_compra=id_compra;};this.getIdProducto=function()
{return _id_producto;};this.setIdProducto=function(id_producto)
{_id_producto=id_producto;};this.getCantidad=function()
{return _cantidad;};this.setCantidad=function(cantidad)
{_cantidad=cantidad;};this.getPrecio=function()
{return _precio;};this.setPrecio=function(precio)
{_precio=precio;};this.getDescuento=function()
{return _descuento;};this.setDescuento=function(descuento)
{_descuento=descuento;};this.json={id_compra:_id_compra,id_producto:_id_producto,cantidad:_cantidad,precio:_precio,descuento:_descuento};var _callback_stack=[];this.pushCallback=function(fn,context){_callback_stack.push({f:fn,c:context});}
this.callCallback=function(params){var t=_callback_stack.pop();t.f.call(t.c,params);}
this.save=function(callback)
{if(DEBUG)console.log('estoy en save()');this.pushCallback(callback,this);var cb=function(res){if(DEBUG)console.log('estoy de regreso en save(',res,') con el contexto:',this);if(res==null){create.call(this,null);}else{update.call(this,null);}};DetalleCompraCliente.getByPK(this.getIdCompra(),this.getIdProducto(),{context:this,callback:cb})};this.search=function($orderBy,$orden)
{$sql="SELECT * from detalle_compra_cliente WHERE (";$val=[];if(this.getIdCompra()!=null){$sql+=" id_compra = ? AND";$val.push(this.getIdCompra());}
if(this.getIdProducto()!=null){$sql+=" id_producto = ? AND";$val.push(this.getIdProducto());}
if(this.getCantidad()!=null){$sql+=" cantidad = ? AND";$val.push(this.getCantidad());}
if(this.getPrecio()!=null){$sql+=" precio = ? AND";$val.push(this.getPrecio());}
if(this.getDescuento()!=null){$sql+=" descuento = ? AND";$val.push(this.getDescuento());}
if($val.length==0){return[];}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};var create=function()
{if(DEBUG)console.log('estoy en create(this)');$sql="INSERT INTO detalle_compra_cliente ( id_compra, id_producto, cantidad, precio, descuento ) VALUES ( ?, ?, ?, ?, ?);";$params=[this.getIdCompra(),this.getIdProducto(),this.getCantidad(),this.getPrecio(),this.getDescuento(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de insert():',tx,results);old_this.callCallback(old_this);});return;};var update=function()
{if(DEBUG)console.log('estoy en update(',this,')');$sql="UPDATE detalle_compra_cliente SET  cantidad = ?, precio = ?, descuento = ? WHERE  id_compra = ? AND id_producto = ?;";$params=[this.getCantidad(),this.getPrecio(),this.getDescuento(),this.getIdCompra(),this.getIdProducto(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de update():',tx,results,this);old_this.callCallback(old_this);});return;};this.destruct=function(config)
{$sql="DELETE FROM detalle_compra_cliente WHERE  id_compra = ? AND id_producto = ?;";$params=[this.getIdCompra(),this.getIdProducto()];if(DEBUG){console.log($sql,$params);}
db.query($sql,$params,function(tx,results){config.callback.call(config.context||null,results);});return;};this.byRange=function($detalle_compra_cliente,$orderBy,$orden)
{$sql="SELECT * from detalle_compra_cliente WHERE (";$val=[];if((($a=this.getIdCompra())!=null)&(($b=$detalle_compra_cliente.getIdCompra())!=null)){$sql+=" id_compra >= ? AND id_compra <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_compra = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdProducto())!=null)&(($b=$detalle_compra_cliente.getIdProducto())!=null)){$sql+=" id_producto >= ? AND id_producto <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_producto = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getCantidad())!=null)&(($b=$detalle_compra_cliente.getCantidad())!=null)){$sql+=" cantidad >= ? AND cantidad <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" cantidad = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getPrecio())!=null)&(($b=$detalle_compra_cliente.getPrecio())!=null)){$sql+=" precio >= ? AND precio <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" precio = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getDescuento())!=null)&(($b=$detalle_compra_cliente.getDescuento())!=null)){$sql+=" descuento >= ? AND descuento <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" descuento = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};}
DetalleCompraCliente.getAll=function(config)
{$sql="SELECT * from detalle_compra_cliente";if(config.orden!==undefined)
$sql+=" ORDER BY "+config.orden+" "+config.tipo_de_orden;if(config.pagina!==undefined)
$sql+=" LIMIT "+((config.pagina-1)*config.columnas_por_pagina)+","+config.columnas_por_pagina;db.query($sql,[],function(tx,results){fres=[];for(i=0;i<results.rows.length;i++){fres.push(new DetalleCompraCliente(results.rows.item(i)))}
config.callback.call(config.context||null,fres);});return;};DetalleCompraCliente.getByPK=function($id_compra,$id_producto,config)
{if(DEBUG)console.log('estoy en getbypk()');$sql="SELECT * FROM detalle_compra_cliente WHERE (id_compra = ? AND id_producto = ? ) LIMIT 1;";db.query($sql,[$id_compra,$id_producto],function(tx,results){if(DEBUG)console.log('ya termine el query de getByPK():',tx,results);if(results.rows.length==0)fres=null;else fres=new DetalleCompraCliente(results.rows.item(0));config.callback.call(config.context||null,fres);});return;};var DetalleInventario=function(config)
{var _id_producto=config===undefined?'':config.id_producto||'',_id_sucursal=config===undefined?'':config.id_sucursal||'',_precio_venta=config===undefined?'':config.precio_venta||'',_precio_venta_procesado=config===undefined?'':config.precio_venta_procesado||'',_existencias=config===undefined?'':config.existencias||'',_existencias_procesadas=config===undefined?'':config.existencias_procesadas||'',_precio_compra=config===undefined?'':config.precio_compra||'';this.getIdProducto=function()
{return _id_producto;};this.setIdProducto=function(id_producto)
{_id_producto=id_producto;};this.getIdSucursal=function()
{return _id_sucursal;};this.setIdSucursal=function(id_sucursal)
{_id_sucursal=id_sucursal;};this.getPrecioVenta=function()
{return _precio_venta;};this.setPrecioVenta=function(precio_venta)
{_precio_venta=precio_venta;};this.getPrecioVentaProcesado=function()
{return _precio_venta_procesado;};this.setPrecioVentaProcesado=function(precio_venta_procesado)
{_precio_venta_procesado=precio_venta_procesado;};this.getExistencias=function()
{return _existencias;};this.setExistencias=function(existencias)
{_existencias=existencias;};this.getExistenciasProcesadas=function()
{return _existencias_procesadas;};this.setExistenciasProcesadas=function(existencias_procesadas)
{_existencias_procesadas=existencias_procesadas;};this.getPrecioCompra=function()
{return _precio_compra;};this.setPrecioCompra=function(precio_compra)
{_precio_compra=precio_compra;};this.json={id_producto:_id_producto,id_sucursal:_id_sucursal,precio_venta:_precio_venta,precio_venta_procesado:_precio_venta_procesado,existencias:_existencias,existencias_procesadas:_existencias_procesadas,precio_compra:_precio_compra};var _callback_stack=[];this.pushCallback=function(fn,context){_callback_stack.push({f:fn,c:context});}
this.callCallback=function(params){var t=_callback_stack.pop();t.f.call(t.c,params);}
this.save=function(callback)
{if(DEBUG)console.log('estoy en save()');this.pushCallback(callback,this);var cb=function(res){if(DEBUG)console.log('estoy de regreso en save(',res,') con el contexto:',this);if(res==null){create.call(this,null);}else{update.call(this,null);}};DetalleInventario.getByPK(this.getIdProducto(),this.getIdSucursal(),{context:this,callback:cb})};this.search=function($orderBy,$orden)
{$sql="SELECT * from detalle_inventario WHERE (";$val=[];if(this.getIdProducto()!=null){$sql+=" id_producto = ? AND";$val.push(this.getIdProducto());}
if(this.getIdSucursal()!=null){$sql+=" id_sucursal = ? AND";$val.push(this.getIdSucursal());}
if(this.getPrecioVenta()!=null){$sql+=" precio_venta = ? AND";$val.push(this.getPrecioVenta());}
if(this.getPrecioVentaProcesado()!=null){$sql+=" precio_venta_procesado = ? AND";$val.push(this.getPrecioVentaProcesado());}
if(this.getExistencias()!=null){$sql+=" existencias = ? AND";$val.push(this.getExistencias());}
if(this.getExistenciasProcesadas()!=null){$sql+=" existencias_procesadas = ? AND";$val.push(this.getExistenciasProcesadas());}
if(this.getPrecioCompra()!=null){$sql+=" precio_compra = ? AND";$val.push(this.getPrecioCompra());}
if($val.length==0){return[];}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};var create=function()
{if(DEBUG)console.log('estoy en create(this)');$sql="INSERT INTO detalle_inventario ( id_producto, id_sucursal, precio_venta, precio_venta_procesado, existencias, existencias_procesadas, precio_compra ) VALUES ( ?, ?, ?, ?, ?, ?, ?);";$params=[this.getIdProducto(),this.getIdSucursal(),this.getPrecioVenta(),this.getPrecioVentaProcesado(),this.getExistencias(),this.getExistenciasProcesadas(),this.getPrecioCompra(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de insert():',tx,results);old_this.callCallback(old_this);});return;};var update=function()
{if(DEBUG)console.log('estoy en update(',this,')');$sql="UPDATE detalle_inventario SET  precio_venta = ?, precio_venta_procesado = ?, existencias = ?, existencias_procesadas = ?, precio_compra = ? WHERE  id_producto = ? AND id_sucursal = ?;";$params=[this.getPrecioVenta(),this.getPrecioVentaProcesado(),this.getExistencias(),this.getExistenciasProcesadas(),this.getPrecioCompra(),this.getIdProducto(),this.getIdSucursal(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de update():',tx,results,this);old_this.callCallback(old_this);});return;};this.destruct=function(config)
{$sql="DELETE FROM detalle_inventario WHERE  id_producto = ? AND id_sucursal = ?;";$params=[this.getIdProducto(),this.getIdSucursal()];if(DEBUG){console.log($sql,$params);}
db.query($sql,$params,function(tx,results){config.callback.call(config.context||null,results);});return;};this.byRange=function($detalle_inventario,$orderBy,$orden)
{$sql="SELECT * from detalle_inventario WHERE (";$val=[];if((($a=this.getIdProducto())!=null)&(($b=$detalle_inventario.getIdProducto())!=null)){$sql+=" id_producto >= ? AND id_producto <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_producto = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdSucursal())!=null)&(($b=$detalle_inventario.getIdSucursal())!=null)){$sql+=" id_sucursal >= ? AND id_sucursal <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_sucursal = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getPrecioVenta())!=null)&(($b=$detalle_inventario.getPrecioVenta())!=null)){$sql+=" precio_venta >= ? AND precio_venta <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" precio_venta = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getPrecioVentaProcesado())!=null)&(($b=$detalle_inventario.getPrecioVentaProcesado())!=null)){$sql+=" precio_venta_procesado >= ? AND precio_venta_procesado <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" precio_venta_procesado = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getExistencias())!=null)&(($b=$detalle_inventario.getExistencias())!=null)){$sql+=" existencias >= ? AND existencias <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" existencias = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getExistenciasProcesadas())!=null)&(($b=$detalle_inventario.getExistenciasProcesadas())!=null)){$sql+=" existencias_procesadas >= ? AND existencias_procesadas <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" existencias_procesadas = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getPrecioCompra())!=null)&(($b=$detalle_inventario.getPrecioCompra())!=null)){$sql+=" precio_compra >= ? AND precio_compra <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" precio_compra = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};}
DetalleInventario.getAll=function(config)
{$sql="SELECT * from detalle_inventario";if(config.orden!==undefined)
$sql+=" ORDER BY "+config.orden+" "+config.tipo_de_orden;if(config.pagina!==undefined)
$sql+=" LIMIT "+((config.pagina-1)*config.columnas_por_pagina)+","+config.columnas_por_pagina;db.query($sql,[],function(tx,results){fres=[];for(i=0;i<results.rows.length;i++){fres.push(new DetalleInventario(results.rows.item(i)))}
config.callback.call(config.context||null,fres);});return;};DetalleInventario.getByPK=function($id_producto,$id_sucursal,config)
{if(DEBUG)console.log('estoy en getbypk()');$sql="SELECT * FROM detalle_inventario WHERE (id_producto = ? AND id_sucursal = ? ) LIMIT 1;";db.query($sql,[$id_producto,$id_sucursal],function(tx,results){if(DEBUG)console.log('ya termine el query de getByPK():',tx,results);if(results.rows.length==0)fres=null;else fres=new DetalleInventario(results.rows.item(0));config.callback.call(config.context||null,fres);});return;};var DetalleVenta=function(config)
{var _id_venta=config===undefined?'':config.id_venta||'',_id_producto=config===undefined?'':config.id_producto||'',_cantidad=config===undefined?'':config.cantidad||'',_cantidad_procesada=config===undefined?'':config.cantidad_procesada||'',_precio=config===undefined?'':config.precio||'',_precio_procesada=config===undefined?'':config.precio_procesada||'',_descuento=config===undefined?'':config.descuento||'';this.getIdVenta=function()
{return _id_venta;};this.setIdVenta=function(id_venta)
{_id_venta=id_venta;};this.getIdProducto=function()
{return _id_producto;};this.setIdProducto=function(id_producto)
{_id_producto=id_producto;};this.getCantidad=function()
{return _cantidad;};this.setCantidad=function(cantidad)
{_cantidad=cantidad;};this.getCantidadProcesada=function()
{return _cantidad_procesada;};this.setCantidadProcesada=function(cantidad_procesada)
{_cantidad_procesada=cantidad_procesada;};this.getPrecio=function()
{return _precio;};this.setPrecio=function(precio)
{_precio=precio;};this.getPrecioProcesada=function()
{return _precio_procesada;};this.setPrecioProcesada=function(precio_procesada)
{_precio_procesada=precio_procesada;};this.getDescuento=function()
{return _descuento;};this.setDescuento=function(descuento)
{_descuento=descuento;};this.json={id_venta:_id_venta,id_producto:_id_producto,cantidad:_cantidad,cantidad_procesada:_cantidad_procesada,precio:_precio,precio_procesada:_precio_procesada,descuento:_descuento};var _callback_stack=[];this.pushCallback=function(fn,context){_callback_stack.push({f:fn,c:context});}
this.callCallback=function(params){var t=_callback_stack.pop();t.f.call(t.c,params);}
this.save=function(callback)
{if(DEBUG)console.log('estoy en save()');this.pushCallback(callback,this);var cb=function(res){if(DEBUG)console.log('estoy de regreso en save(',res,') con el contexto:',this);if(res==null){create.call(this,null);}else{update.call(this,null);}};DetalleVenta.getByPK(this.getIdVenta(),this.getIdProducto(),{context:this,callback:cb})};this.search=function($orderBy,$orden)
{$sql="SELECT * from detalle_venta WHERE (";$val=[];if(this.getIdVenta()!=null){$sql+=" id_venta = ? AND";$val.push(this.getIdVenta());}
if(this.getIdProducto()!=null){$sql+=" id_producto = ? AND";$val.push(this.getIdProducto());}
if(this.getCantidad()!=null){$sql+=" cantidad = ? AND";$val.push(this.getCantidad());}
if(this.getCantidadProcesada()!=null){$sql+=" cantidad_procesada = ? AND";$val.push(this.getCantidadProcesada());}
if(this.getPrecio()!=null){$sql+=" precio = ? AND";$val.push(this.getPrecio());}
if(this.getPrecioProcesada()!=null){$sql+=" precio_procesada = ? AND";$val.push(this.getPrecioProcesada());}
if(this.getDescuento()!=null){$sql+=" descuento = ? AND";$val.push(this.getDescuento());}
if($val.length==0){return[];}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};var create=function()
{if(DEBUG)console.log('estoy en create(this)');$sql="INSERT INTO detalle_venta ( id_venta, id_producto, cantidad, cantidad_procesada, precio, precio_procesada, descuento ) VALUES ( ?, ?, ?, ?, ?, ?, ?);";$params=[this.getIdVenta(),this.getIdProducto(),this.getCantidad(),this.getCantidadProcesada(),this.getPrecio(),this.getPrecioProcesada(),this.getDescuento(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de insert():',tx,results);old_this.callCallback(old_this);});return;};var update=function()
{if(DEBUG)console.log('estoy en update(',this,')');$sql="UPDATE detalle_venta SET  cantidad = ?, cantidad_procesada = ?, precio = ?, precio_procesada = ?, descuento = ? WHERE  id_venta = ? AND id_producto = ?;";$params=[this.getCantidad(),this.getCantidadProcesada(),this.getPrecio(),this.getPrecioProcesada(),this.getDescuento(),this.getIdVenta(),this.getIdProducto(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de update():',tx,results,this);old_this.callCallback(old_this);});return;};this.destruct=function(config)
{$sql="DELETE FROM detalle_venta WHERE  id_venta = ? AND id_producto = ?;";$params=[this.getIdVenta(),this.getIdProducto()];if(DEBUG){console.log($sql,$params);}
db.query($sql,$params,function(tx,results){config.callback.call(config.context||null,results);});return;};this.byRange=function($detalle_venta,$orderBy,$orden)
{$sql="SELECT * from detalle_venta WHERE (";$val=[];if((($a=this.getIdVenta())!=null)&(($b=$detalle_venta.getIdVenta())!=null)){$sql+=" id_venta >= ? AND id_venta <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_venta = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdProducto())!=null)&(($b=$detalle_venta.getIdProducto())!=null)){$sql+=" id_producto >= ? AND id_producto <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_producto = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getCantidad())!=null)&(($b=$detalle_venta.getCantidad())!=null)){$sql+=" cantidad >= ? AND cantidad <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" cantidad = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getCantidadProcesada())!=null)&(($b=$detalle_venta.getCantidadProcesada())!=null)){$sql+=" cantidad_procesada >= ? AND cantidad_procesada <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" cantidad_procesada = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getPrecio())!=null)&(($b=$detalle_venta.getPrecio())!=null)){$sql+=" precio >= ? AND precio <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" precio = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getPrecioProcesada())!=null)&(($b=$detalle_venta.getPrecioProcesada())!=null)){$sql+=" precio_procesada >= ? AND precio_procesada <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" precio_procesada = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getDescuento())!=null)&(($b=$detalle_venta.getDescuento())!=null)){$sql+=" descuento >= ? AND descuento <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" descuento = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};}
DetalleVenta.getAll=function(config)
{$sql="SELECT * from detalle_venta";if(config.orden!==undefined)
$sql+=" ORDER BY "+config.orden+" "+config.tipo_de_orden;if(config.pagina!==undefined)
$sql+=" LIMIT "+((config.pagina-1)*config.columnas_por_pagina)+","+config.columnas_por_pagina;db.query($sql,[],function(tx,results){fres=[];for(i=0;i<results.rows.length;i++){fres.push(new DetalleVenta(results.rows.item(i)))}
config.callback.call(config.context||null,fres);});return;};DetalleVenta.getByPK=function($id_venta,$id_producto,config)
{if(DEBUG)console.log('estoy en getbypk()');$sql="SELECT * FROM detalle_venta WHERE (id_venta = ? AND id_producto = ? ) LIMIT 1;";db.query($sql,[$id_venta,$id_producto],function(tx,results){if(DEBUG)console.log('ya termine el query de getByPK():',tx,results);if(results.rows.length==0)fres=null;else fres=new DetalleVenta(results.rows.item(0));config.callback.call(config.context||null,fres);});return;};var Equipo=function(config)
{var _id_equipo=config===undefined?'':config.id_equipo||'',_token=config===undefined?'':config.token||'',_full_ua=config===undefined?'':config.full_ua||'',_descripcion=config===undefined?'':config.descripcion||'',_locked=config===undefined?'':config.locked||'';this.getIdEquipo=function()
{return _id_equipo;};this.setIdEquipo=function(id_equipo)
{_id_equipo=id_equipo;};this.getToken=function()
{return _token;};this.setToken=function(token)
{_token=token;};this.getFullUa=function()
{return _full_ua;};this.setFullUa=function(full_ua)
{_full_ua=full_ua;};this.getDescripcion=function()
{return _descripcion;};this.setDescripcion=function(descripcion)
{_descripcion=descripcion;};this.getLocked=function()
{return _locked;};this.setLocked=function(locked)
{_locked=locked;};this.json={id_equipo:_id_equipo,token:_token,full_ua:_full_ua,descripcion:_descripcion,locked:_locked};var _callback_stack=[];this.pushCallback=function(fn,context){_callback_stack.push({f:fn,c:context});}
this.callCallback=function(params){var t=_callback_stack.pop();t.f.call(t.c,params);}
this.save=function(callback)
{if(DEBUG)console.log('estoy en save()');this.pushCallback(callback,this);var cb=function(res){if(DEBUG)console.log('estoy de regreso en save(',res,') con el contexto:',this);if(res==null){create.call(this,null);}else{update.call(this,null);}};Equipo.getByPK(this.getIdEquipo(),{context:this,callback:cb})};this.search=function($orderBy,$orden)
{$sql="SELECT * from equipo WHERE (";$val=[];if(this.getIdEquipo()!=null){$sql+=" id_equipo = ? AND";$val.push(this.getIdEquipo());}
if(this.getToken()!=null){$sql+=" token = ? AND";$val.push(this.getToken());}
if(this.getFullUa()!=null){$sql+=" full_ua = ? AND";$val.push(this.getFullUa());}
if(this.getDescripcion()!=null){$sql+=" descripcion = ? AND";$val.push(this.getDescripcion());}
if(this.getLocked()!=null){$sql+=" locked = ? AND";$val.push(this.getLocked());}
if($val.length==0){return[];}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};var create=function()
{if(DEBUG)console.log('estoy en create(this)');$sql="INSERT INTO equipo ( id_equipo, token, full_ua, descripcion, locked ) VALUES ( ?, ?, ?, ?, ?);";$params=[this.getIdEquipo(),this.getToken(),this.getFullUa(),this.getDescripcion(),this.getLocked(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de insert():',tx,results);old_this.callCallback(old_this);});return;};var update=function()
{if(DEBUG)console.log('estoy en update(',this,')');$sql="UPDATE equipo SET  token = ?, full_ua = ?, descripcion = ?, locked = ? WHERE  id_equipo = ?;";$params=[this.getToken(),this.getFullUa(),this.getDescripcion(),this.getLocked(),this.getIdEquipo(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de update():',tx,results,this);old_this.callCallback(old_this);});return;};this.destruct=function(config)
{$sql="DELETE FROM equipo WHERE  id_equipo = ?;";$params=[this.getIdEquipo()];if(DEBUG){console.log($sql,$params);}
db.query($sql,$params,function(tx,results){config.callback.call(config.context||null,results);});return;};this.byRange=function($equipo,$orderBy,$orden)
{$sql="SELECT * from equipo WHERE (";$val=[];if((($a=this.getIdEquipo())!=null)&(($b=$equipo.getIdEquipo())!=null)){$sql+=" id_equipo >= ? AND id_equipo <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_equipo = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getToken())!=null)&(($b=$equipo.getToken())!=null)){$sql+=" token >= ? AND token <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" token = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getFullUa())!=null)&(($b=$equipo.getFullUa())!=null)){$sql+=" full_ua >= ? AND full_ua <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" full_ua = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getDescripcion())!=null)&(($b=$equipo.getDescripcion())!=null)){$sql+=" descripcion >= ? AND descripcion <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" descripcion = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getLocked())!=null)&(($b=$equipo.getLocked())!=null)){$sql+=" locked >= ? AND locked <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" locked = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};}
Equipo.getAll=function(config)
{$sql="SELECT * from equipo";if(config.orden!==undefined)
$sql+=" ORDER BY "+config.orden+" "+config.tipo_de_orden;if(config.pagina!==undefined)
$sql+=" LIMIT "+((config.pagina-1)*config.columnas_por_pagina)+","+config.columnas_por_pagina;db.query($sql,[],function(tx,results){fres=[];for(i=0;i<results.rows.length;i++){fres.push(new Equipo(results.rows.item(i)))}
config.callback.call(config.context||null,fres);});return;};Equipo.getByPK=function($id_equipo,config)
{if(DEBUG)console.log('estoy en getbypk()');$sql="SELECT * FROM equipo WHERE (id_equipo = ? ) LIMIT 1;";db.query($sql,[$id_equipo],function(tx,results){if(DEBUG)console.log('ya termine el query de getByPK():',tx,results);if(results.rows.length==0)fres=null;else fres=new Equipo(results.rows.item(0));config.callback.call(config.context||null,fres);});return;};var FacturaCompra=function(config)
{var _folio=config===undefined?'':config.folio||'',_id_compra=config===undefined?'':config.id_compra||'';this.getFolio=function()
{return _folio;};this.setFolio=function(folio)
{_folio=folio;};this.getIdCompra=function()
{return _id_compra;};this.setIdCompra=function(id_compra)
{_id_compra=id_compra;};this.json={folio:_folio,id_compra:_id_compra};var _callback_stack=[];this.pushCallback=function(fn,context){_callback_stack.push({f:fn,c:context});}
this.callCallback=function(params){var t=_callback_stack.pop();t.f.call(t.c,params);}
this.save=function(callback)
{if(DEBUG)console.log('estoy en save()');this.pushCallback(callback,this);var cb=function(res){if(DEBUG)console.log('estoy de regreso en save(',res,') con el contexto:',this);if(res==null){create.call(this,null);}else{update.call(this,null);}};FacturaCompra.getByPK(this.getFolio(),{context:this,callback:cb})};this.search=function($orderBy,$orden)
{$sql="SELECT * from factura_compra WHERE (";$val=[];if(this.getFolio()!=null){$sql+=" folio = ? AND";$val.push(this.getFolio());}
if(this.getIdCompra()!=null){$sql+=" id_compra = ? AND";$val.push(this.getIdCompra());}
if($val.length==0){return[];}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};var create=function()
{if(DEBUG)console.log('estoy en create(this)');$sql="INSERT INTO factura_compra ( folio, id_compra ) VALUES ( ?, ?);";$params=[this.getFolio(),this.getIdCompra(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de insert():',tx,results);old_this.callCallback(old_this);});return;};var update=function()
{if(DEBUG)console.log('estoy en update(',this,')');$sql="UPDATE factura_compra SET  id_compra = ? WHERE  folio = ?;";$params=[this.getIdCompra(),this.getFolio(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de update():',tx,results,this);old_this.callCallback(old_this);});return;};this.destruct=function(config)
{$sql="DELETE FROM factura_compra WHERE  folio = ?;";$params=[this.getFolio()];if(DEBUG){console.log($sql,$params);}
db.query($sql,$params,function(tx,results){config.callback.call(config.context||null,results);});return;};this.byRange=function($factura_compra,$orderBy,$orden)
{$sql="SELECT * from factura_compra WHERE (";$val=[];if((($a=this.getFolio())!=null)&(($b=$factura_compra.getFolio())!=null)){$sql+=" folio >= ? AND folio <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" folio = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdCompra())!=null)&(($b=$factura_compra.getIdCompra())!=null)){$sql+=" id_compra >= ? AND id_compra <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_compra = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};}
FacturaCompra.getAll=function(config)
{$sql="SELECT * from factura_compra";if(config.orden!==undefined)
$sql+=" ORDER BY "+config.orden+" "+config.tipo_de_orden;if(config.pagina!==undefined)
$sql+=" LIMIT "+((config.pagina-1)*config.columnas_por_pagina)+","+config.columnas_por_pagina;db.query($sql,[],function(tx,results){fres=[];for(i=0;i<results.rows.length;i++){fres.push(new FacturaCompra(results.rows.item(i)))}
config.callback.call(config.context||null,fres);});return;};FacturaCompra.getByPK=function($folio,config)
{if(DEBUG)console.log('estoy en getbypk()');$sql="SELECT * FROM factura_compra WHERE (folio = ? ) LIMIT 1;";db.query($sql,[$folio],function(tx,results){if(DEBUG)console.log('ya termine el query de getByPK():',tx,results);if(results.rows.length==0)fres=null;else fres=new FacturaCompra(results.rows.item(0));config.callback.call(config.context||null,fres);});return;};var FacturaVenta=function(config)
{var _id_folio=config===undefined?'':config.id_folio||'',_id_venta=config===undefined?'':config.id_venta||'',_id_usuario=config===undefined?'':config.id_usuario||'',_xml=config===undefined?'':config.xml||'',_lugar_emision=config===undefined?'':config.lugar_emision||'',_tipo_comprobante=config===undefined?'':config.tipo_comprobante||'',_activa=config===undefined?'':config.activa||'',_sellada=config===undefined?'':config.sellada||'',_forma_pago=config===undefined?'':config.forma_pago||'',_fecha_emision=config===undefined?'':config.fecha_emision||'',_version_tfd=config===undefined?'':config.version_tfd||'',_folio_fiscal=config===undefined?'':config.folio_fiscal||'',_fecha_certificacion=config===undefined?'':config.fecha_certificacion||'',_numero_certificado_sat=config===undefined?'':config.numero_certificado_sat||'',_sello_digital_emisor=config===undefined?'':config.sello_digital_emisor||'',_sello_digital_sat=config===undefined?'':config.sello_digital_sat||'',_cadena_original=config===undefined?'':config.cadena_original||'';this.getIdFolio=function()
{return _id_folio;};this.setIdFolio=function(id_folio)
{_id_folio=id_folio;};this.getIdVenta=function()
{return _id_venta;};this.setIdVenta=function(id_venta)
{_id_venta=id_venta;};this.getIdUsuario=function()
{return _id_usuario;};this.setIdUsuario=function(id_usuario)
{_id_usuario=id_usuario;};this.getXml=function()
{return _xml;};this.setXml=function(xml)
{_xml=xml;};this.getLugarEmision=function()
{return _lugar_emision;};this.setLugarEmision=function(lugar_emision)
{_lugar_emision=lugar_emision;};this.getTipoComprobante=function()
{return _tipo_comprobante;};this.setTipoComprobante=function(tipo_comprobante)
{_tipo_comprobante=tipo_comprobante;};this.getActiva=function()
{return _activa;};this.setActiva=function(activa)
{_activa=activa;};this.getSellada=function()
{return _sellada;};this.setSellada=function(sellada)
{_sellada=sellada;};this.getFormaPago=function()
{return _forma_pago;};this.setFormaPago=function(forma_pago)
{_forma_pago=forma_pago;};this.getFechaEmision=function()
{return _fecha_emision;};this.setFechaEmision=function(fecha_emision)
{_fecha_emision=fecha_emision;};this.getVersionTfd=function()
{return _version_tfd;};this.setVersionTfd=function(version_tfd)
{_version_tfd=version_tfd;};this.getFolioFiscal=function()
{return _folio_fiscal;};this.setFolioFiscal=function(folio_fiscal)
{_folio_fiscal=folio_fiscal;};this.getFechaCertificacion=function()
{return _fecha_certificacion;};this.setFechaCertificacion=function(fecha_certificacion)
{_fecha_certificacion=fecha_certificacion;};this.getNumeroCertificadoSat=function()
{return _numero_certificado_sat;};this.setNumeroCertificadoSat=function(numero_certificado_sat)
{_numero_certificado_sat=numero_certificado_sat;};this.getSelloDigitalEmisor=function()
{return _sello_digital_emisor;};this.setSelloDigitalEmisor=function(sello_digital_emisor)
{_sello_digital_emisor=sello_digital_emisor;};this.getSelloDigitalSat=function()
{return _sello_digital_sat;};this.setSelloDigitalSat=function(sello_digital_sat)
{_sello_digital_sat=sello_digital_sat;};this.getCadenaOriginal=function()
{return _cadena_original;};this.setCadenaOriginal=function(cadena_original)
{_cadena_original=cadena_original;};this.json={id_folio:_id_folio,id_venta:_id_venta,id_usuario:_id_usuario,xml:_xml,lugar_emision:_lugar_emision,tipo_comprobante:_tipo_comprobante,activa:_activa,sellada:_sellada,forma_pago:_forma_pago,fecha_emision:_fecha_emision,version_tfd:_version_tfd,folio_fiscal:_folio_fiscal,fecha_certificacion:_fecha_certificacion,numero_certificado_sat:_numero_certificado_sat,sello_digital_emisor:_sello_digital_emisor,sello_digital_sat:_sello_digital_sat,cadena_original:_cadena_original};var _callback_stack=[];this.pushCallback=function(fn,context){_callback_stack.push({f:fn,c:context});}
this.callCallback=function(params){var t=_callback_stack.pop();t.f.call(t.c,params);}
this.save=function(callback)
{if(DEBUG)console.log('estoy en save()');this.pushCallback(callback,this);var cb=function(res){if(DEBUG)console.log('estoy de regreso en save(',res,') con el contexto:',this);if(res==null){create.call(this,null);}else{update.call(this,null);}};FacturaVenta.getByPK(this.getIdFolio(),{context:this,callback:cb})};this.search=function($orderBy,$orden)
{$sql="SELECT * from factura_venta WHERE (";$val=[];if(this.getIdFolio()!=null){$sql+=" id_folio = ? AND";$val.push(this.getIdFolio());}
if(this.getIdVenta()!=null){$sql+=" id_venta = ? AND";$val.push(this.getIdVenta());}
if(this.getIdUsuario()!=null){$sql+=" id_usuario = ? AND";$val.push(this.getIdUsuario());}
if(this.getXml()!=null){$sql+=" xml = ? AND";$val.push(this.getXml());}
if(this.getLugarEmision()!=null){$sql+=" lugar_emision = ? AND";$val.push(this.getLugarEmision());}
if(this.getTipoComprobante()!=null){$sql+=" tipo_comprobante = ? AND";$val.push(this.getTipoComprobante());}
if(this.getActiva()!=null){$sql+=" activa = ? AND";$val.push(this.getActiva());}
if(this.getSellada()!=null){$sql+=" sellada = ? AND";$val.push(this.getSellada());}
if(this.getFormaPago()!=null){$sql+=" forma_pago = ? AND";$val.push(this.getFormaPago());}
if(this.getFechaEmision()!=null){$sql+=" fecha_emision = ? AND";$val.push(this.getFechaEmision());}
if(this.getVersionTfd()!=null){$sql+=" version_tfd = ? AND";$val.push(this.getVersionTfd());}
if(this.getFolioFiscal()!=null){$sql+=" folio_fiscal = ? AND";$val.push(this.getFolioFiscal());}
if(this.getFechaCertificacion()!=null){$sql+=" fecha_certificacion = ? AND";$val.push(this.getFechaCertificacion());}
if(this.getNumeroCertificadoSat()!=null){$sql+=" numero_certificado_sat = ? AND";$val.push(this.getNumeroCertificadoSat());}
if(this.getSelloDigitalEmisor()!=null){$sql+=" sello_digital_emisor = ? AND";$val.push(this.getSelloDigitalEmisor());}
if(this.getSelloDigitalSat()!=null){$sql+=" sello_digital_sat = ? AND";$val.push(this.getSelloDigitalSat());}
if(this.getCadenaOriginal()!=null){$sql+=" cadena_original = ? AND";$val.push(this.getCadenaOriginal());}
if($val.length==0){return[];}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};var create=function()
{if(DEBUG)console.log('estoy en create(this)');$sql="INSERT INTO factura_venta ( id_folio, id_venta, id_usuario, xml, lugar_emision, tipo_comprobante, activa, sellada, forma_pago, fecha_emision, version_tfd, folio_fiscal, fecha_certificacion, numero_certificado_sat, sello_digital_emisor, sello_digital_sat, cadena_original ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";$params=[this.getIdFolio(),this.getIdVenta(),this.getIdUsuario(),this.getXml(),this.getLugarEmision(),this.getTipoComprobante(),this.getActiva(),this.getSellada(),this.getFormaPago(),this.getFechaEmision(),this.getVersionTfd(),this.getFolioFiscal(),this.getFechaCertificacion(),this.getNumeroCertificadoSat(),this.getSelloDigitalEmisor(),this.getSelloDigitalSat(),this.getCadenaOriginal(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de insert():',tx,results);old_this.callCallback(old_this);});return;};var update=function()
{if(DEBUG)console.log('estoy en update(',this,')');$sql="UPDATE factura_venta SET  id_venta = ?, id_usuario = ?, xml = ?, lugar_emision = ?, tipo_comprobante = ?, activa = ?, sellada = ?, forma_pago = ?, fecha_emision = ?, version_tfd = ?, folio_fiscal = ?, fecha_certificacion = ?, numero_certificado_sat = ?, sello_digital_emisor = ?, sello_digital_sat = ?, cadena_original = ? WHERE  id_folio = ?;";$params=[this.getIdVenta(),this.getIdUsuario(),this.getXml(),this.getLugarEmision(),this.getTipoComprobante(),this.getActiva(),this.getSellada(),this.getFormaPago(),this.getFechaEmision(),this.getVersionTfd(),this.getFolioFiscal(),this.getFechaCertificacion(),this.getNumeroCertificadoSat(),this.getSelloDigitalEmisor(),this.getSelloDigitalSat(),this.getCadenaOriginal(),this.getIdFolio(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de update():',tx,results,this);old_this.callCallback(old_this);});return;};this.destruct=function(config)
{$sql="DELETE FROM factura_venta WHERE  id_folio = ?;";$params=[this.getIdFolio()];if(DEBUG){console.log($sql,$params);}
db.query($sql,$params,function(tx,results){config.callback.call(config.context||null,results);});return;};this.byRange=function($factura_venta,$orderBy,$orden)
{$sql="SELECT * from factura_venta WHERE (";$val=[];if((($a=this.getIdFolio())!=null)&(($b=$factura_venta.getIdFolio())!=null)){$sql+=" id_folio >= ? AND id_folio <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_folio = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdVenta())!=null)&(($b=$factura_venta.getIdVenta())!=null)){$sql+=" id_venta >= ? AND id_venta <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_venta = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdUsuario())!=null)&(($b=$factura_venta.getIdUsuario())!=null)){$sql+=" id_usuario >= ? AND id_usuario <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_usuario = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getXml())!=null)&(($b=$factura_venta.getXml())!=null)){$sql+=" xml >= ? AND xml <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" xml = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getLugarEmision())!=null)&(($b=$factura_venta.getLugarEmision())!=null)){$sql+=" lugar_emision >= ? AND lugar_emision <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" lugar_emision = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getTipoComprobante())!=null)&(($b=$factura_venta.getTipoComprobante())!=null)){$sql+=" tipo_comprobante >= ? AND tipo_comprobante <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" tipo_comprobante = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getActiva())!=null)&(($b=$factura_venta.getActiva())!=null)){$sql+=" activa >= ? AND activa <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" activa = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getSellada())!=null)&(($b=$factura_venta.getSellada())!=null)){$sql+=" sellada >= ? AND sellada <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" sellada = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getFormaPago())!=null)&(($b=$factura_venta.getFormaPago())!=null)){$sql+=" forma_pago >= ? AND forma_pago <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" forma_pago = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getFechaEmision())!=null)&(($b=$factura_venta.getFechaEmision())!=null)){$sql+=" fecha_emision >= ? AND fecha_emision <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" fecha_emision = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getVersionTfd())!=null)&(($b=$factura_venta.getVersionTfd())!=null)){$sql+=" version_tfd >= ? AND version_tfd <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" version_tfd = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getFolioFiscal())!=null)&(($b=$factura_venta.getFolioFiscal())!=null)){$sql+=" folio_fiscal >= ? AND folio_fiscal <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" folio_fiscal = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getFechaCertificacion())!=null)&(($b=$factura_venta.getFechaCertificacion())!=null)){$sql+=" fecha_certificacion >= ? AND fecha_certificacion <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" fecha_certificacion = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getNumeroCertificadoSat())!=null)&(($b=$factura_venta.getNumeroCertificadoSat())!=null)){$sql+=" numero_certificado_sat >= ? AND numero_certificado_sat <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" numero_certificado_sat = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getSelloDigitalEmisor())!=null)&(($b=$factura_venta.getSelloDigitalEmisor())!=null)){$sql+=" sello_digital_emisor >= ? AND sello_digital_emisor <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" sello_digital_emisor = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getSelloDigitalSat())!=null)&(($b=$factura_venta.getSelloDigitalSat())!=null)){$sql+=" sello_digital_sat >= ? AND sello_digital_sat <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" sello_digital_sat = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getCadenaOriginal())!=null)&(($b=$factura_venta.getCadenaOriginal())!=null)){$sql+=" cadena_original >= ? AND cadena_original <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" cadena_original = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};}
FacturaVenta.getAll=function(config)
{$sql="SELECT * from factura_venta";if(config.orden!==undefined)
$sql+=" ORDER BY "+config.orden+" "+config.tipo_de_orden;if(config.pagina!==undefined)
$sql+=" LIMIT "+((config.pagina-1)*config.columnas_por_pagina)+","+config.columnas_por_pagina;db.query($sql,[],function(tx,results){fres=[];for(i=0;i<results.rows.length;i++){fres.push(new FacturaVenta(results.rows.item(i)))}
config.callback.call(config.context||null,fres);});return;};FacturaVenta.getByPK=function($id_folio,config)
{if(DEBUG)console.log('estoy en getbypk()');$sql="SELECT * FROM factura_venta WHERE (id_folio = ? ) LIMIT 1;";db.query($sql,[$id_folio],function(tx,results){if(DEBUG)console.log('ya termine el query de getByPK():',tx,results);if(results.rows.length==0)fres=null;else fres=new FacturaVenta(results.rows.item(0));config.callback.call(config.context||null,fres);});return;};var Gastos=function(config)
{var _id_gasto=config===undefined?'':config.id_gasto||'',_folio=config===undefined?'':config.folio||'',_concepto=config===undefined?'':config.concepto||'',_monto=config===undefined?'':config.monto||'',_fecha=config===undefined?'':config.fecha||'',_fecha_ingreso=config===undefined?'':config.fecha_ingreso||'',_id_sucursal=config===undefined?'':config.id_sucursal||'',_id_usuario=config===undefined?'':config.id_usuario||'',_nota=config===undefined?'':config.nota||'';this.getIdGasto=function()
{return _id_gasto;};this.setIdGasto=function(id_gasto)
{_id_gasto=id_gasto;};this.getFolio=function()
{return _folio;};this.setFolio=function(folio)
{_folio=folio;};this.getConcepto=function()
{return _concepto;};this.setConcepto=function(concepto)
{_concepto=concepto;};this.getMonto=function()
{return _monto;};this.setMonto=function(monto)
{_monto=monto;};this.getFecha=function()
{return _fecha;};this.setFecha=function(fecha)
{_fecha=fecha;};this.getFechaIngreso=function()
{return _fecha_ingreso;};this.setFechaIngreso=function(fecha_ingreso)
{_fecha_ingreso=fecha_ingreso;};this.getIdSucursal=function()
{return _id_sucursal;};this.setIdSucursal=function(id_sucursal)
{_id_sucursal=id_sucursal;};this.getIdUsuario=function()
{return _id_usuario;};this.setIdUsuario=function(id_usuario)
{_id_usuario=id_usuario;};this.getNota=function()
{return _nota;};this.setNota=function(nota)
{_nota=nota;};this.json={id_gasto:_id_gasto,folio:_folio,concepto:_concepto,monto:_monto,fecha:_fecha,fecha_ingreso:_fecha_ingreso,id_sucursal:_id_sucursal,id_usuario:_id_usuario,nota:_nota};var _callback_stack=[];this.pushCallback=function(fn,context){_callback_stack.push({f:fn,c:context});}
this.callCallback=function(params){var t=_callback_stack.pop();t.f.call(t.c,params);}
this.save=function(callback)
{if(DEBUG)console.log('estoy en save()');this.pushCallback(callback,this);var cb=function(res){if(DEBUG)console.log('estoy de regreso en save(',res,') con el contexto:',this);if(res==null){create.call(this,null);}else{update.call(this,null);}};Gastos.getByPK(this.getIdGasto(),{context:this,callback:cb})};this.search=function($orderBy,$orden)
{$sql="SELECT * from gastos WHERE (";$val=[];if(this.getIdGasto()!=null){$sql+=" id_gasto = ? AND";$val.push(this.getIdGasto());}
if(this.getFolio()!=null){$sql+=" folio = ? AND";$val.push(this.getFolio());}
if(this.getConcepto()!=null){$sql+=" concepto = ? AND";$val.push(this.getConcepto());}
if(this.getMonto()!=null){$sql+=" monto = ? AND";$val.push(this.getMonto());}
if(this.getFecha()!=null){$sql+=" fecha = ? AND";$val.push(this.getFecha());}
if(this.getFechaIngreso()!=null){$sql+=" fecha_ingreso = ? AND";$val.push(this.getFechaIngreso());}
if(this.getIdSucursal()!=null){$sql+=" id_sucursal = ? AND";$val.push(this.getIdSucursal());}
if(this.getIdUsuario()!=null){$sql+=" id_usuario = ? AND";$val.push(this.getIdUsuario());}
if(this.getNota()!=null){$sql+=" nota = ? AND";$val.push(this.getNota());}
if($val.length==0){return[];}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};var create=function()
{if(DEBUG)console.log('estoy en create(this)');$sql="INSERT INTO gastos ( id_gasto, folio, concepto, monto, fecha, fecha_ingreso, id_sucursal, id_usuario, nota ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?);";$params=[this.getIdGasto(),this.getFolio(),this.getConcepto(),this.getMonto(),this.getFecha(),this.getFechaIngreso(),this.getIdSucursal(),this.getIdUsuario(),this.getNota(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de insert():',tx,results);old_this.callCallback(old_this);});return;};var update=function()
{if(DEBUG)console.log('estoy en update(',this,')');$sql="UPDATE gastos SET  folio = ?, concepto = ?, monto = ?, fecha = ?, fecha_ingreso = ?, id_sucursal = ?, id_usuario = ?, nota = ? WHERE  id_gasto = ?;";$params=[this.getFolio(),this.getConcepto(),this.getMonto(),this.getFecha(),this.getFechaIngreso(),this.getIdSucursal(),this.getIdUsuario(),this.getNota(),this.getIdGasto(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de update():',tx,results,this);old_this.callCallback(old_this);});return;};this.destruct=function(config)
{$sql="DELETE FROM gastos WHERE  id_gasto = ?;";$params=[this.getIdGasto()];if(DEBUG){console.log($sql,$params);}
db.query($sql,$params,function(tx,results){config.callback.call(config.context||null,results);});return;};this.byRange=function($gastos,$orderBy,$orden)
{$sql="SELECT * from gastos WHERE (";$val=[];if((($a=this.getIdGasto())!=null)&(($b=$gastos.getIdGasto())!=null)){$sql+=" id_gasto >= ? AND id_gasto <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_gasto = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getFolio())!=null)&(($b=$gastos.getFolio())!=null)){$sql+=" folio >= ? AND folio <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" folio = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getConcepto())!=null)&(($b=$gastos.getConcepto())!=null)){$sql+=" concepto >= ? AND concepto <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" concepto = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getMonto())!=null)&(($b=$gastos.getMonto())!=null)){$sql+=" monto >= ? AND monto <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" monto = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getFecha())!=null)&(($b=$gastos.getFecha())!=null)){$sql+=" fecha >= ? AND fecha <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" fecha = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getFechaIngreso())!=null)&(($b=$gastos.getFechaIngreso())!=null)){$sql+=" fecha_ingreso >= ? AND fecha_ingreso <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" fecha_ingreso = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdSucursal())!=null)&(($b=$gastos.getIdSucursal())!=null)){$sql+=" id_sucursal >= ? AND id_sucursal <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_sucursal = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdUsuario())!=null)&(($b=$gastos.getIdUsuario())!=null)){$sql+=" id_usuario >= ? AND id_usuario <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_usuario = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getNota())!=null)&(($b=$gastos.getNota())!=null)){$sql+=" nota >= ? AND nota <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" nota = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};}
Gastos.getAll=function(config)
{$sql="SELECT * from gastos";if(config.orden!==undefined)
$sql+=" ORDER BY "+config.orden+" "+config.tipo_de_orden;if(config.pagina!==undefined)
$sql+=" LIMIT "+((config.pagina-1)*config.columnas_por_pagina)+","+config.columnas_por_pagina;db.query($sql,[],function(tx,results){fres=[];for(i=0;i<results.rows.length;i++){fres.push(new Gastos(results.rows.item(i)))}
config.callback.call(config.context||null,fres);});return;};Gastos.getByPK=function($id_gasto,config)
{if(DEBUG)console.log('estoy en getbypk()');$sql="SELECT * FROM gastos WHERE (id_gasto = ? ) LIMIT 1;";db.query($sql,[$id_gasto],function(tx,results){if(DEBUG)console.log('ya termine el query de getByPK():',tx,results);if(results.rows.length==0)fres=null;else fres=new Gastos(results.rows.item(0));config.callback.call(config.context||null,fres);});return;};var Impresora=function(config)
{var _id_impresora=config===undefined?'':config.id_impresora||'',_id_sucursal=config===undefined?'':config.id_sucursal||'',_descripcion=config===undefined?'':config.descripcion||'',_identificador=config===undefined?'':config.identificador||'';this.getIdImpresora=function()
{return _id_impresora;};this.setIdImpresora=function(id_impresora)
{_id_impresora=id_impresora;};this.getIdSucursal=function()
{return _id_sucursal;};this.setIdSucursal=function(id_sucursal)
{_id_sucursal=id_sucursal;};this.getDescripcion=function()
{return _descripcion;};this.setDescripcion=function(descripcion)
{_descripcion=descripcion;};this.getIdentificador=function()
{return _identificador;};this.setIdentificador=function(identificador)
{_identificador=identificador;};this.json={id_impresora:_id_impresora,id_sucursal:_id_sucursal,descripcion:_descripcion,identificador:_identificador};var _callback_stack=[];this.pushCallback=function(fn,context){_callback_stack.push({f:fn,c:context});}
this.callCallback=function(params){var t=_callback_stack.pop();t.f.call(t.c,params);}
this.save=function(callback)
{if(DEBUG)console.log('estoy en save()');this.pushCallback(callback,this);var cb=function(res){if(DEBUG)console.log('estoy de regreso en save(',res,') con el contexto:',this);if(res==null){create.call(this,null);}else{update.call(this,null);}};Impresora.getByPK(this.getIdImpresora(),{context:this,callback:cb})};this.search=function($orderBy,$orden)
{$sql="SELECT * from impresora WHERE (";$val=[];if(this.getIdImpresora()!=null){$sql+=" id_impresora = ? AND";$val.push(this.getIdImpresora());}
if(this.getIdSucursal()!=null){$sql+=" id_sucursal = ? AND";$val.push(this.getIdSucursal());}
if(this.getDescripcion()!=null){$sql+=" descripcion = ? AND";$val.push(this.getDescripcion());}
if(this.getIdentificador()!=null){$sql+=" identificador = ? AND";$val.push(this.getIdentificador());}
if($val.length==0){return[];}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};var create=function()
{if(DEBUG)console.log('estoy en create(this)');$sql="INSERT INTO impresora ( id_impresora, id_sucursal, descripcion, identificador ) VALUES ( ?, ?, ?, ?);";$params=[this.getIdImpresora(),this.getIdSucursal(),this.getDescripcion(),this.getIdentificador(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de insert():',tx,results);old_this.callCallback(old_this);});return;};var update=function()
{if(DEBUG)console.log('estoy en update(',this,')');$sql="UPDATE impresora SET  id_sucursal = ?, descripcion = ?, identificador = ? WHERE  id_impresora = ?;";$params=[this.getIdSucursal(),this.getDescripcion(),this.getIdentificador(),this.getIdImpresora(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de update():',tx,results,this);old_this.callCallback(old_this);});return;};this.destruct=function(config)
{$sql="DELETE FROM impresora WHERE  id_impresora = ?;";$params=[this.getIdImpresora()];if(DEBUG){console.log($sql,$params);}
db.query($sql,$params,function(tx,results){config.callback.call(config.context||null,results);});return;};this.byRange=function($impresora,$orderBy,$orden)
{$sql="SELECT * from impresora WHERE (";$val=[];if((($a=this.getIdImpresora())!=null)&(($b=$impresora.getIdImpresora())!=null)){$sql+=" id_impresora >= ? AND id_impresora <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_impresora = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdSucursal())!=null)&(($b=$impresora.getIdSucursal())!=null)){$sql+=" id_sucursal >= ? AND id_sucursal <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_sucursal = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getDescripcion())!=null)&(($b=$impresora.getDescripcion())!=null)){$sql+=" descripcion >= ? AND descripcion <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" descripcion = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdentificador())!=null)&(($b=$impresora.getIdentificador())!=null)){$sql+=" identificador >= ? AND identificador <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" identificador = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};}
Impresora.getAll=function(config)
{$sql="SELECT * from impresora";if(config.orden!==undefined)
$sql+=" ORDER BY "+config.orden+" "+config.tipo_de_orden;if(config.pagina!==undefined)
$sql+=" LIMIT "+((config.pagina-1)*config.columnas_por_pagina)+","+config.columnas_por_pagina;db.query($sql,[],function(tx,results){fres=[];for(i=0;i<results.rows.length;i++){fres.push(new Impresora(results.rows.item(i)))}
config.callback.call(config.context||null,fres);});return;};Impresora.getByPK=function($id_impresora,config)
{if(DEBUG)console.log('estoy en getbypk()');$sql="SELECT * FROM impresora WHERE (id_impresora = ? ) LIMIT 1;";db.query($sql,[$id_impresora],function(tx,results){if(DEBUG)console.log('ya termine el query de getByPK():',tx,results);if(results.rows.length==0)fres=null;else fres=new Impresora(results.rows.item(0));config.callback.call(config.context||null,fres);});return;};var Ingresos=function(config)
{var _id_ingreso=config===undefined?'':config.id_ingreso||'',_concepto=config===undefined?'':config.concepto||'',_monto=config===undefined?'':config.monto||'',_fecha=config===undefined?'':config.fecha||'',_fecha_ingreso=config===undefined?'':config.fecha_ingreso||'',_id_sucursal=config===undefined?'':config.id_sucursal||'',_id_usuario=config===undefined?'':config.id_usuario||'',_nota=config===undefined?'':config.nota||'';this.getIdIngreso=function()
{return _id_ingreso;};this.setIdIngreso=function(id_ingreso)
{_id_ingreso=id_ingreso;};this.getConcepto=function()
{return _concepto;};this.setConcepto=function(concepto)
{_concepto=concepto;};this.getMonto=function()
{return _monto;};this.setMonto=function(monto)
{_monto=monto;};this.getFecha=function()
{return _fecha;};this.setFecha=function(fecha)
{_fecha=fecha;};this.getFechaIngreso=function()
{return _fecha_ingreso;};this.setFechaIngreso=function(fecha_ingreso)
{_fecha_ingreso=fecha_ingreso;};this.getIdSucursal=function()
{return _id_sucursal;};this.setIdSucursal=function(id_sucursal)
{_id_sucursal=id_sucursal;};this.getIdUsuario=function()
{return _id_usuario;};this.setIdUsuario=function(id_usuario)
{_id_usuario=id_usuario;};this.getNota=function()
{return _nota;};this.setNota=function(nota)
{_nota=nota;};this.json={id_ingreso:_id_ingreso,concepto:_concepto,monto:_monto,fecha:_fecha,fecha_ingreso:_fecha_ingreso,id_sucursal:_id_sucursal,id_usuario:_id_usuario,nota:_nota};var _callback_stack=[];this.pushCallback=function(fn,context){_callback_stack.push({f:fn,c:context});}
this.callCallback=function(params){var t=_callback_stack.pop();t.f.call(t.c,params);}
this.save=function(callback)
{if(DEBUG)console.log('estoy en save()');this.pushCallback(callback,this);var cb=function(res){if(DEBUG)console.log('estoy de regreso en save(',res,') con el contexto:',this);if(res==null){create.call(this,null);}else{update.call(this,null);}};Ingresos.getByPK(this.getIdIngreso(),{context:this,callback:cb})};this.search=function($orderBy,$orden)
{$sql="SELECT * from ingresos WHERE (";$val=[];if(this.getIdIngreso()!=null){$sql+=" id_ingreso = ? AND";$val.push(this.getIdIngreso());}
if(this.getConcepto()!=null){$sql+=" concepto = ? AND";$val.push(this.getConcepto());}
if(this.getMonto()!=null){$sql+=" monto = ? AND";$val.push(this.getMonto());}
if(this.getFecha()!=null){$sql+=" fecha = ? AND";$val.push(this.getFecha());}
if(this.getFechaIngreso()!=null){$sql+=" fecha_ingreso = ? AND";$val.push(this.getFechaIngreso());}
if(this.getIdSucursal()!=null){$sql+=" id_sucursal = ? AND";$val.push(this.getIdSucursal());}
if(this.getIdUsuario()!=null){$sql+=" id_usuario = ? AND";$val.push(this.getIdUsuario());}
if(this.getNota()!=null){$sql+=" nota = ? AND";$val.push(this.getNota());}
if($val.length==0){return[];}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};var create=function()
{if(DEBUG)console.log('estoy en create(this)');$sql="INSERT INTO ingresos ( id_ingreso, concepto, monto, fecha, fecha_ingreso, id_sucursal, id_usuario, nota ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?);";$params=[this.getIdIngreso(),this.getConcepto(),this.getMonto(),this.getFecha(),this.getFechaIngreso(),this.getIdSucursal(),this.getIdUsuario(),this.getNota(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de insert():',tx,results);old_this.callCallback(old_this);});return;};var update=function()
{if(DEBUG)console.log('estoy en update(',this,')');$sql="UPDATE ingresos SET  concepto = ?, monto = ?, fecha = ?, fecha_ingreso = ?, id_sucursal = ?, id_usuario = ?, nota = ? WHERE  id_ingreso = ?;";$params=[this.getConcepto(),this.getMonto(),this.getFecha(),this.getFechaIngreso(),this.getIdSucursal(),this.getIdUsuario(),this.getNota(),this.getIdIngreso(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de update():',tx,results,this);old_this.callCallback(old_this);});return;};this.destruct=function(config)
{$sql="DELETE FROM ingresos WHERE  id_ingreso = ?;";$params=[this.getIdIngreso()];if(DEBUG){console.log($sql,$params);}
db.query($sql,$params,function(tx,results){config.callback.call(config.context||null,results);});return;};this.byRange=function($ingresos,$orderBy,$orden)
{$sql="SELECT * from ingresos WHERE (";$val=[];if((($a=this.getIdIngreso())!=null)&(($b=$ingresos.getIdIngreso())!=null)){$sql+=" id_ingreso >= ? AND id_ingreso <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_ingreso = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getConcepto())!=null)&(($b=$ingresos.getConcepto())!=null)){$sql+=" concepto >= ? AND concepto <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" concepto = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getMonto())!=null)&(($b=$ingresos.getMonto())!=null)){$sql+=" monto >= ? AND monto <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" monto = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getFecha())!=null)&(($b=$ingresos.getFecha())!=null)){$sql+=" fecha >= ? AND fecha <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" fecha = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getFechaIngreso())!=null)&(($b=$ingresos.getFechaIngreso())!=null)){$sql+=" fecha_ingreso >= ? AND fecha_ingreso <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" fecha_ingreso = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdSucursal())!=null)&(($b=$ingresos.getIdSucursal())!=null)){$sql+=" id_sucursal >= ? AND id_sucursal <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_sucursal = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdUsuario())!=null)&(($b=$ingresos.getIdUsuario())!=null)){$sql+=" id_usuario >= ? AND id_usuario <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_usuario = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getNota())!=null)&(($b=$ingresos.getNota())!=null)){$sql+=" nota >= ? AND nota <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" nota = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};}
Ingresos.getAll=function(config)
{$sql="SELECT * from ingresos";if(config.orden!==undefined)
$sql+=" ORDER BY "+config.orden+" "+config.tipo_de_orden;if(config.pagina!==undefined)
$sql+=" LIMIT "+((config.pagina-1)*config.columnas_por_pagina)+","+config.columnas_por_pagina;db.query($sql,[],function(tx,results){fres=[];for(i=0;i<results.rows.length;i++){fres.push(new Ingresos(results.rows.item(i)))}
config.callback.call(config.context||null,fres);});return;};Ingresos.getByPK=function($id_ingreso,config)
{if(DEBUG)console.log('estoy en getbypk()');$sql="SELECT * FROM ingresos WHERE (id_ingreso = ? ) LIMIT 1;";db.query($sql,[$id_ingreso],function(tx,results){if(DEBUG)console.log('ya termine el query de getByPK():',tx,results);if(results.rows.length==0)fres=null;else fres=new Ingresos(results.rows.item(0));config.callback.call(config.context||null,fres);});return;};var Inventario=function(config)
{var _id_producto=config===undefined?'':config.id_producto||'',_descripcion=config===undefined?'':config.descripcion||'',_escala=config===undefined?'':config.escala||'',_tratamiento=config===undefined?'':config.tratamiento||'',_agrupacion=config===undefined?'':config.agrupacion||'',_agrupacionTam=config===undefined?'':config.agrupacionTam||'',_activo=config===undefined?'':config.activo||'',_precio_por_agrupacion=config===undefined?'':config.precio_por_agrupacion||'';this.getIdProducto=function()
{return _id_producto;};this.setIdProducto=function(id_producto)
{_id_producto=id_producto;};this.getDescripcion=function()
{return _descripcion;};this.setDescripcion=function(descripcion)
{_descripcion=descripcion;};this.getEscala=function()
{return _escala;};this.setEscala=function(escala)
{_escala=escala;};this.getTratamiento=function()
{return _tratamiento;};this.setTratamiento=function(tratamiento)
{_tratamiento=tratamiento;};this.getAgrupacion=function()
{return _agrupacion;};this.setAgrupacion=function(agrupacion)
{_agrupacion=agrupacion;};this.getAgrupacionTam=function()
{return _agrupacionTam;};this.setAgrupacionTam=function(agrupacionTam)
{_agrupacionTam=agrupacionTam;};this.getActivo=function()
{return _activo;};this.setActivo=function(activo)
{_activo=activo;};this.getPrecioPorAgrupacion=function()
{return _precio_por_agrupacion;};this.setPrecioPorAgrupacion=function(precio_por_agrupacion)
{_precio_por_agrupacion=precio_por_agrupacion;};this.json={id_producto:_id_producto,descripcion:_descripcion,escala:_escala,tratamiento:_tratamiento,agrupacion:_agrupacion,agrupacionTam:_agrupacionTam,activo:_activo,precio_por_agrupacion:_precio_por_agrupacion};var _callback_stack=[];this.pushCallback=function(fn,context){_callback_stack.push({f:fn,c:context});}
this.callCallback=function(params){var t=_callback_stack.pop();t.f.call(t.c,params);}
this.save=function(callback)
{if(DEBUG)console.log('estoy en save()');this.pushCallback(callback,this);var cb=function(res){if(DEBUG)console.log('estoy de regreso en save(',res,') con el contexto:',this);if(res==null){create.call(this,null);}else{update.call(this,null);}};Inventario.getByPK(this.getIdProducto(),{context:this,callback:cb})};this.search=function($orderBy,$orden)
{$sql="SELECT * from inventario WHERE (";$val=[];if(this.getIdProducto()!=null){$sql+=" id_producto = ? AND";$val.push(this.getIdProducto());}
if(this.getDescripcion()!=null){$sql+=" descripcion = ? AND";$val.push(this.getDescripcion());}
if(this.getEscala()!=null){$sql+=" escala = ? AND";$val.push(this.getEscala());}
if(this.getTratamiento()!=null){$sql+=" tratamiento = ? AND";$val.push(this.getTratamiento());}
if(this.getAgrupacion()!=null){$sql+=" agrupacion = ? AND";$val.push(this.getAgrupacion());}
if(this.getAgrupacionTam()!=null){$sql+=" agrupacionTam = ? AND";$val.push(this.getAgrupacionTam());}
if(this.getActivo()!=null){$sql+=" activo = ? AND";$val.push(this.getActivo());}
if(this.getPrecioPorAgrupacion()!=null){$sql+=" precio_por_agrupacion = ? AND";$val.push(this.getPrecioPorAgrupacion());}
if($val.length==0){return[];}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};var create=function()
{if(DEBUG)console.log('estoy en create(this)');$sql="INSERT INTO inventario ( id_producto, descripcion, escala, tratamiento, agrupacion, agrupacionTam, activo, precio_por_agrupacion ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?);";$params=[this.getIdProducto(),this.getDescripcion(),this.getEscala(),this.getTratamiento(),this.getAgrupacion(),this.getAgrupacionTam(),this.getActivo(),this.getPrecioPorAgrupacion(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de insert():',tx,results);old_this.callCallback(old_this);});return;};var update=function()
{if(DEBUG)console.log('estoy en update(',this,')');$sql="UPDATE inventario SET  descripcion = ?, escala = ?, tratamiento = ?, agrupacion = ?, agrupacionTam = ?, activo = ?, precio_por_agrupacion = ? WHERE  id_producto = ?;";$params=[this.getDescripcion(),this.getEscala(),this.getTratamiento(),this.getAgrupacion(),this.getAgrupacionTam(),this.getActivo(),this.getPrecioPorAgrupacion(),this.getIdProducto(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de update():',tx,results,this);old_this.callCallback(old_this);});return;};this.destruct=function(config)
{$sql="DELETE FROM inventario WHERE  id_producto = ?;";$params=[this.getIdProducto()];if(DEBUG){console.log($sql,$params);}
db.query($sql,$params,function(tx,results){config.callback.call(config.context||null,results);});return;};this.byRange=function($inventario,$orderBy,$orden)
{$sql="SELECT * from inventario WHERE (";$val=[];if((($a=this.getIdProducto())!=null)&(($b=$inventario.getIdProducto())!=null)){$sql+=" id_producto >= ? AND id_producto <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_producto = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getDescripcion())!=null)&(($b=$inventario.getDescripcion())!=null)){$sql+=" descripcion >= ? AND descripcion <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" descripcion = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getEscala())!=null)&(($b=$inventario.getEscala())!=null)){$sql+=" escala >= ? AND escala <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" escala = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getTratamiento())!=null)&(($b=$inventario.getTratamiento())!=null)){$sql+=" tratamiento >= ? AND tratamiento <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" tratamiento = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getAgrupacion())!=null)&(($b=$inventario.getAgrupacion())!=null)){$sql+=" agrupacion >= ? AND agrupacion <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" agrupacion = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getAgrupacionTam())!=null)&(($b=$inventario.getAgrupacionTam())!=null)){$sql+=" agrupacionTam >= ? AND agrupacionTam <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" agrupacionTam = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getActivo())!=null)&(($b=$inventario.getActivo())!=null)){$sql+=" activo >= ? AND activo <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" activo = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getPrecioPorAgrupacion())!=null)&(($b=$inventario.getPrecioPorAgrupacion())!=null)){$sql+=" precio_por_agrupacion >= ? AND precio_por_agrupacion <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" precio_por_agrupacion = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};}
Inventario.getAll=function(config)
{$sql="SELECT * from inventario";if(config.orden!==undefined)
$sql+=" ORDER BY "+config.orden+" "+config.tipo_de_orden;if(config.pagina!==undefined)
$sql+=" LIMIT "+((config.pagina-1)*config.columnas_por_pagina)+","+config.columnas_por_pagina;db.query($sql,[],function(tx,results){fres=[];for(i=0;i<results.rows.length;i++){fres.push(new Inventario(results.rows.item(i)))}
config.callback.call(config.context||null,fres);});return;};Inventario.getByPK=function($id_producto,config)
{if(DEBUG)console.log('estoy en getbypk()');$sql="SELECT * FROM inventario WHERE (id_producto = ? ) LIMIT 1;";db.query($sql,[$id_producto],function(tx,results){if(DEBUG)console.log('ya termine el query de getByPK():',tx,results);if(results.rows.length==0)fres=null;else fres=new Inventario(results.rows.item(0));config.callback.call(config.context||null,fres);});return;};var PagosVenta=function(config)
{var _id_pago=config===undefined?'':config.id_pago||'',_id_venta=config===undefined?'':config.id_venta||'',_id_sucursal=config===undefined?'':config.id_sucursal||'',_id_usuario=config===undefined?'':config.id_usuario||'',_fecha=config===undefined?'':config.fecha||'',_monto=config===undefined?'':config.monto||'',_tipo_pago=config===undefined?'':config.tipo_pago||'';this.getIdPago=function()
{return _id_pago;};this.setIdPago=function(id_pago)
{_id_pago=id_pago;};this.getIdVenta=function()
{return _id_venta;};this.setIdVenta=function(id_venta)
{_id_venta=id_venta;};this.getIdSucursal=function()
{return _id_sucursal;};this.setIdSucursal=function(id_sucursal)
{_id_sucursal=id_sucursal;};this.getIdUsuario=function()
{return _id_usuario;};this.setIdUsuario=function(id_usuario)
{_id_usuario=id_usuario;};this.getFecha=function()
{return _fecha;};this.setFecha=function(fecha)
{_fecha=fecha;};this.getMonto=function()
{return _monto;};this.setMonto=function(monto)
{_monto=monto;};this.getTipoPago=function()
{return _tipo_pago;};this.setTipoPago=function(tipo_pago)
{_tipo_pago=tipo_pago;};this.json={id_pago:_id_pago,id_venta:_id_venta,id_sucursal:_id_sucursal,id_usuario:_id_usuario,fecha:_fecha,monto:_monto,tipo_pago:_tipo_pago};var _callback_stack=[];this.pushCallback=function(fn,context){_callback_stack.push({f:fn,c:context});}
this.callCallback=function(params){var t=_callback_stack.pop();t.f.call(t.c,params);}
this.save=function(callback)
{if(DEBUG)console.log('estoy en save()');this.pushCallback(callback,this);var cb=function(res){if(DEBUG)console.log('estoy de regreso en save(',res,') con el contexto:',this);if(res==null){create.call(this,null);}else{update.call(this,null);}};PagosVenta.getByPK(this.getIdPago(),{context:this,callback:cb})};this.search=function($orderBy,$orden)
{$sql="SELECT * from pagos_venta WHERE (";$val=[];if(this.getIdPago()!=null){$sql+=" id_pago = ? AND";$val.push(this.getIdPago());}
if(this.getIdVenta()!=null){$sql+=" id_venta = ? AND";$val.push(this.getIdVenta());}
if(this.getIdSucursal()!=null){$sql+=" id_sucursal = ? AND";$val.push(this.getIdSucursal());}
if(this.getIdUsuario()!=null){$sql+=" id_usuario = ? AND";$val.push(this.getIdUsuario());}
if(this.getFecha()!=null){$sql+=" fecha = ? AND";$val.push(this.getFecha());}
if(this.getMonto()!=null){$sql+=" monto = ? AND";$val.push(this.getMonto());}
if(this.getTipoPago()!=null){$sql+=" tipo_pago = ? AND";$val.push(this.getTipoPago());}
if($val.length==0){return[];}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};var create=function()
{if(DEBUG)console.log('estoy en create(this)');$sql="INSERT INTO pagos_venta ( id_pago, id_venta, id_sucursal, id_usuario, fecha, monto, tipo_pago ) VALUES ( ?, ?, ?, ?, ?, ?, ?);";$params=[this.getIdPago(),this.getIdVenta(),this.getIdSucursal(),this.getIdUsuario(),this.getFecha(),this.getMonto(),this.getTipoPago(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de insert():',tx,results);old_this.callCallback(old_this);});return;};var update=function()
{if(DEBUG)console.log('estoy en update(',this,')');$sql="UPDATE pagos_venta SET  id_venta = ?, id_sucursal = ?, id_usuario = ?, fecha = ?, monto = ?, tipo_pago = ? WHERE  id_pago = ?;";$params=[this.getIdVenta(),this.getIdSucursal(),this.getIdUsuario(),this.getFecha(),this.getMonto(),this.getTipoPago(),this.getIdPago(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de update():',tx,results,this);old_this.callCallback(old_this);});return;};this.destruct=function(config)
{$sql="DELETE FROM pagos_venta WHERE  id_pago = ?;";$params=[this.getIdPago()];if(DEBUG){console.log($sql,$params);}
db.query($sql,$params,function(tx,results){config.callback.call(config.context||null,results);});return;};this.byRange=function($pagos_venta,$orderBy,$orden)
{$sql="SELECT * from pagos_venta WHERE (";$val=[];if((($a=this.getIdPago())!=null)&(($b=$pagos_venta.getIdPago())!=null)){$sql+=" id_pago >= ? AND id_pago <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_pago = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdVenta())!=null)&(($b=$pagos_venta.getIdVenta())!=null)){$sql+=" id_venta >= ? AND id_venta <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_venta = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdSucursal())!=null)&(($b=$pagos_venta.getIdSucursal())!=null)){$sql+=" id_sucursal >= ? AND id_sucursal <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_sucursal = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdUsuario())!=null)&(($b=$pagos_venta.getIdUsuario())!=null)){$sql+=" id_usuario >= ? AND id_usuario <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_usuario = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getFecha())!=null)&(($b=$pagos_venta.getFecha())!=null)){$sql+=" fecha >= ? AND fecha <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" fecha = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getMonto())!=null)&(($b=$pagos_venta.getMonto())!=null)){$sql+=" monto >= ? AND monto <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" monto = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getTipoPago())!=null)&(($b=$pagos_venta.getTipoPago())!=null)){$sql+=" tipo_pago >= ? AND tipo_pago <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" tipo_pago = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};}
PagosVenta.getAll=function(config)
{$sql="SELECT * from pagos_venta";if(config.orden!==undefined)
$sql+=" ORDER BY "+config.orden+" "+config.tipo_de_orden;if(config.pagina!==undefined)
$sql+=" LIMIT "+((config.pagina-1)*config.columnas_por_pagina)+","+config.columnas_por_pagina;db.query($sql,[],function(tx,results){fres=[];for(i=0;i<results.rows.length;i++){fres.push(new PagosVenta(results.rows.item(i)))}
config.callback.call(config.context||null,fres);});return;};PagosVenta.getByPK=function($id_pago,config)
{if(DEBUG)console.log('estoy en getbypk()');$sql="SELECT * FROM pagos_venta WHERE (id_pago = ? ) LIMIT 1;";db.query($sql,[$id_pago],function(tx,results){if(DEBUG)console.log('ya termine el query de getByPK():',tx,results);if(results.rows.length==0)fres=null;else fres=new PagosVenta(results.rows.item(0));config.callback.call(config.context||null,fres);});return;};var Sucursal=function(config)
{var _id_sucursal=config===undefined?'':config.id_sucursal||'',_gerente=config===undefined?'':config.gerente||'',_descripcion=config===undefined?'':config.descripcion||'',_razon_social=config===undefined?'':config.razon_social||'',_rfc=config===undefined?'':config.rfc||'',_calle=config===undefined?'':config.calle||'',_numero_exterior=config===undefined?'':config.numero_exterior||'',_numero_interior=config===undefined?'':config.numero_interior||'',_colonia=config===undefined?'':config.colonia||'',_localidad=config===undefined?'':config.localidad||'',_referencia=config===undefined?'':config.referencia||'',_municipio=config===undefined?'':config.municipio||'',_estado=config===undefined?'':config.estado||'',_pais=config===undefined?'':config.pais||'',_codigo_postal=config===undefined?'':config.codigo_postal||'',_telefono=config===undefined?'':config.telefono||'',_token=config===undefined?'':config.token||'',_letras_factura=config===undefined?'':config.letras_factura||'',_activo=config===undefined?'':config.activo||'',_fecha_apertura=config===undefined?'':config.fecha_apertura||'',_saldo_a_favor=config===undefined?'':config.saldo_a_favor||'';this.getIdSucursal=function()
{return _id_sucursal;};this.setIdSucursal=function(id_sucursal)
{_id_sucursal=id_sucursal;};this.getGerente=function()
{return _gerente;};this.setGerente=function(gerente)
{_gerente=gerente;};this.getDescripcion=function()
{return _descripcion;};this.setDescripcion=function(descripcion)
{_descripcion=descripcion;};this.getRazonSocial=function()
{return _razon_social;};this.setRazonSocial=function(razon_social)
{_razon_social=razon_social;};this.getRfc=function()
{return _rfc;};this.setRfc=function(rfc)
{_rfc=rfc;};this.getCalle=function()
{return _calle;};this.setCalle=function(calle)
{_calle=calle;};this.getNumeroExterior=function()
{return _numero_exterior;};this.setNumeroExterior=function(numero_exterior)
{_numero_exterior=numero_exterior;};this.getNumeroInterior=function()
{return _numero_interior;};this.setNumeroInterior=function(numero_interior)
{_numero_interior=numero_interior;};this.getColonia=function()
{return _colonia;};this.setColonia=function(colonia)
{_colonia=colonia;};this.getLocalidad=function()
{return _localidad;};this.setLocalidad=function(localidad)
{_localidad=localidad;};this.getReferencia=function()
{return _referencia;};this.setReferencia=function(referencia)
{_referencia=referencia;};this.getMunicipio=function()
{return _municipio;};this.setMunicipio=function(municipio)
{_municipio=municipio;};this.getEstado=function()
{return _estado;};this.setEstado=function(estado)
{_estado=estado;};this.getPais=function()
{return _pais;};this.setPais=function(pais)
{_pais=pais;};this.getCodigoPostal=function()
{return _codigo_postal;};this.setCodigoPostal=function(codigo_postal)
{_codigo_postal=codigo_postal;};this.getTelefono=function()
{return _telefono;};this.setTelefono=function(telefono)
{_telefono=telefono;};this.getToken=function()
{return _token;};this.setToken=function(token)
{_token=token;};this.getLetrasFactura=function()
{return _letras_factura;};this.setLetrasFactura=function(letras_factura)
{_letras_factura=letras_factura;};this.getActivo=function()
{return _activo;};this.setActivo=function(activo)
{_activo=activo;};this.getFechaApertura=function()
{return _fecha_apertura;};this.setFechaApertura=function(fecha_apertura)
{_fecha_apertura=fecha_apertura;};this.getSaldoAFavor=function()
{return _saldo_a_favor;};this.setSaldoAFavor=function(saldo_a_favor)
{_saldo_a_favor=saldo_a_favor;};this.json={id_sucursal:_id_sucursal,gerente:_gerente,descripcion:_descripcion,razon_social:_razon_social,rfc:_rfc,calle:_calle,numero_exterior:_numero_exterior,numero_interior:_numero_interior,colonia:_colonia,localidad:_localidad,referencia:_referencia,municipio:_municipio,estado:_estado,pais:_pais,codigo_postal:_codigo_postal,telefono:_telefono,token:_token,letras_factura:_letras_factura,activo:_activo,fecha_apertura:_fecha_apertura,saldo_a_favor:_saldo_a_favor};var _callback_stack=[];this.pushCallback=function(fn,context){_callback_stack.push({f:fn,c:context});}
this.callCallback=function(params){var t=_callback_stack.pop();t.f.call(t.c,params);}
this.save=function(callback)
{if(DEBUG)console.log('estoy en save()');this.pushCallback(callback,this);var cb=function(res){if(DEBUG)console.log('estoy de regreso en save(',res,') con el contexto:',this);if(res==null){create.call(this,null);}else{update.call(this,null);}};Sucursal.getByPK(this.getIdSucursal(),{context:this,callback:cb})};this.search=function($orderBy,$orden)
{$sql="SELECT * from sucursal WHERE (";$val=[];if(this.getIdSucursal()!=null){$sql+=" id_sucursal = ? AND";$val.push(this.getIdSucursal());}
if(this.getGerente()!=null){$sql+=" gerente = ? AND";$val.push(this.getGerente());}
if(this.getDescripcion()!=null){$sql+=" descripcion = ? AND";$val.push(this.getDescripcion());}
if(this.getRazonSocial()!=null){$sql+=" razon_social = ? AND";$val.push(this.getRazonSocial());}
if(this.getRfc()!=null){$sql+=" rfc = ? AND";$val.push(this.getRfc());}
if(this.getCalle()!=null){$sql+=" calle = ? AND";$val.push(this.getCalle());}
if(this.getNumeroExterior()!=null){$sql+=" numero_exterior = ? AND";$val.push(this.getNumeroExterior());}
if(this.getNumeroInterior()!=null){$sql+=" numero_interior = ? AND";$val.push(this.getNumeroInterior());}
if(this.getColonia()!=null){$sql+=" colonia = ? AND";$val.push(this.getColonia());}
if(this.getLocalidad()!=null){$sql+=" localidad = ? AND";$val.push(this.getLocalidad());}
if(this.getReferencia()!=null){$sql+=" referencia = ? AND";$val.push(this.getReferencia());}
if(this.getMunicipio()!=null){$sql+=" municipio = ? AND";$val.push(this.getMunicipio());}
if(this.getEstado()!=null){$sql+=" estado = ? AND";$val.push(this.getEstado());}
if(this.getPais()!=null){$sql+=" pais = ? AND";$val.push(this.getPais());}
if(this.getCodigoPostal()!=null){$sql+=" codigo_postal = ? AND";$val.push(this.getCodigoPostal());}
if(this.getTelefono()!=null){$sql+=" telefono = ? AND";$val.push(this.getTelefono());}
if(this.getToken()!=null){$sql+=" token = ? AND";$val.push(this.getToken());}
if(this.getLetrasFactura()!=null){$sql+=" letras_factura = ? AND";$val.push(this.getLetrasFactura());}
if(this.getActivo()!=null){$sql+=" activo = ? AND";$val.push(this.getActivo());}
if(this.getFechaApertura()!=null){$sql+=" fecha_apertura = ? AND";$val.push(this.getFechaApertura());}
if(this.getSaldoAFavor()!=null){$sql+=" saldo_a_favor = ? AND";$val.push(this.getSaldoAFavor());}
if($val.length==0){return[];}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};var create=function()
{if(DEBUG)console.log('estoy en create(this)');$sql="INSERT INTO sucursal ( id_sucursal, gerente, descripcion, razon_social, rfc, calle, numero_exterior, numero_interior, colonia, localidad, referencia, municipio, estado, pais, codigo_postal, telefono, token, letras_factura, activo, fecha_apertura, saldo_a_favor ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";$params=[this.getIdSucursal(),this.getGerente(),this.getDescripcion(),this.getRazonSocial(),this.getRfc(),this.getCalle(),this.getNumeroExterior(),this.getNumeroInterior(),this.getColonia(),this.getLocalidad(),this.getReferencia(),this.getMunicipio(),this.getEstado(),this.getPais(),this.getCodigoPostal(),this.getTelefono(),this.getToken(),this.getLetrasFactura(),this.getActivo(),this.getFechaApertura(),this.getSaldoAFavor(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de insert():',tx,results);old_this.callCallback(old_this);});return;};var update=function()
{if(DEBUG)console.log('estoy en update(',this,')');$sql="UPDATE sucursal SET  gerente = ?, descripcion = ?, razon_social = ?, rfc = ?, calle = ?, numero_exterior = ?, numero_interior = ?, colonia = ?, localidad = ?, referencia = ?, municipio = ?, estado = ?, pais = ?, codigo_postal = ?, telefono = ?, token = ?, letras_factura = ?, activo = ?, fecha_apertura = ?, saldo_a_favor = ? WHERE  id_sucursal = ?;";$params=[this.getGerente(),this.getDescripcion(),this.getRazonSocial(),this.getRfc(),this.getCalle(),this.getNumeroExterior(),this.getNumeroInterior(),this.getColonia(),this.getLocalidad(),this.getReferencia(),this.getMunicipio(),this.getEstado(),this.getPais(),this.getCodigoPostal(),this.getTelefono(),this.getToken(),this.getLetrasFactura(),this.getActivo(),this.getFechaApertura(),this.getSaldoAFavor(),this.getIdSucursal(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de update():',tx,results,this);old_this.callCallback(old_this);});return;};this.destruct=function(config)
{$sql="DELETE FROM sucursal WHERE  id_sucursal = ?;";$params=[this.getIdSucursal()];if(DEBUG){console.log($sql,$params);}
db.query($sql,$params,function(tx,results){config.callback.call(config.context||null,results);});return;};this.byRange=function($sucursal,$orderBy,$orden)
{$sql="SELECT * from sucursal WHERE (";$val=[];if((($a=this.getIdSucursal())!=null)&(($b=$sucursal.getIdSucursal())!=null)){$sql+=" id_sucursal >= ? AND id_sucursal <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_sucursal = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getGerente())!=null)&(($b=$sucursal.getGerente())!=null)){$sql+=" gerente >= ? AND gerente <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" gerente = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getDescripcion())!=null)&(($b=$sucursal.getDescripcion())!=null)){$sql+=" descripcion >= ? AND descripcion <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" descripcion = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getRazonSocial())!=null)&(($b=$sucursal.getRazonSocial())!=null)){$sql+=" razon_social >= ? AND razon_social <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" razon_social = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getRfc())!=null)&(($b=$sucursal.getRfc())!=null)){$sql+=" rfc >= ? AND rfc <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" rfc = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getCalle())!=null)&(($b=$sucursal.getCalle())!=null)){$sql+=" calle >= ? AND calle <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" calle = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getNumeroExterior())!=null)&(($b=$sucursal.getNumeroExterior())!=null)){$sql+=" numero_exterior >= ? AND numero_exterior <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" numero_exterior = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getNumeroInterior())!=null)&(($b=$sucursal.getNumeroInterior())!=null)){$sql+=" numero_interior >= ? AND numero_interior <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" numero_interior = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getColonia())!=null)&(($b=$sucursal.getColonia())!=null)){$sql+=" colonia >= ? AND colonia <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" colonia = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getLocalidad())!=null)&(($b=$sucursal.getLocalidad())!=null)){$sql+=" localidad >= ? AND localidad <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" localidad = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getReferencia())!=null)&(($b=$sucursal.getReferencia())!=null)){$sql+=" referencia >= ? AND referencia <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" referencia = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getMunicipio())!=null)&(($b=$sucursal.getMunicipio())!=null)){$sql+=" municipio >= ? AND municipio <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" municipio = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getEstado())!=null)&(($b=$sucursal.getEstado())!=null)){$sql+=" estado >= ? AND estado <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" estado = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getPais())!=null)&(($b=$sucursal.getPais())!=null)){$sql+=" pais >= ? AND pais <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" pais = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getCodigoPostal())!=null)&(($b=$sucursal.getCodigoPostal())!=null)){$sql+=" codigo_postal >= ? AND codigo_postal <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" codigo_postal = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getTelefono())!=null)&(($b=$sucursal.getTelefono())!=null)){$sql+=" telefono >= ? AND telefono <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" telefono = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getToken())!=null)&(($b=$sucursal.getToken())!=null)){$sql+=" token >= ? AND token <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" token = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getLetrasFactura())!=null)&(($b=$sucursal.getLetrasFactura())!=null)){$sql+=" letras_factura >= ? AND letras_factura <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" letras_factura = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getActivo())!=null)&(($b=$sucursal.getActivo())!=null)){$sql+=" activo >= ? AND activo <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" activo = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getFechaApertura())!=null)&(($b=$sucursal.getFechaApertura())!=null)){$sql+=" fecha_apertura >= ? AND fecha_apertura <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" fecha_apertura = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getSaldoAFavor())!=null)&(($b=$sucursal.getSaldoAFavor())!=null)){$sql+=" saldo_a_favor >= ? AND saldo_a_favor <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" saldo_a_favor = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};}
Sucursal.getAll=function(config)
{$sql="SELECT * from sucursal";if(config.orden!==undefined)
$sql+=" ORDER BY "+config.orden+" "+config.tipo_de_orden;if(config.pagina!==undefined)
$sql+=" LIMIT "+((config.pagina-1)*config.columnas_por_pagina)+","+config.columnas_por_pagina;db.query($sql,[],function(tx,results){fres=[];for(i=0;i<results.rows.length;i++){fres.push(new Sucursal(results.rows.item(i)))}
config.callback.call(config.context||null,fres);});return;};Sucursal.getByPK=function($id_sucursal,config)
{if(DEBUG)console.log('estoy en getbypk()');$sql="SELECT * FROM sucursal WHERE (id_sucursal = ? ) LIMIT 1;";db.query($sql,[$id_sucursal],function(tx,results){if(DEBUG)console.log('ya termine el query de getByPK():',tx,results);if(results.rows.length==0)fres=null;else fres=new Sucursal(results.rows.item(0));config.callback.call(config.context||null,fres);});return;};var Ventas=function(config)
{var _id_venta=config===undefined?'':config.id_venta||'',_id_venta_equipo=config===undefined?'':config.id_venta_equipo||'',_id_equipo=config===undefined?'':config.id_equipo||'',_id_cliente=config===undefined?'':config.id_cliente||'',_tipo_venta=config===undefined?'':config.tipo_venta||'',_tipo_pago=config===undefined?'':config.tipo_pago||'',_fecha=config===undefined?'':config.fecha||'',_subtotal=config===undefined?'':config.subtotal||'',_iva=config===undefined?'':config.iva||'',_descuento=config===undefined?'':config.descuento||'',_total=config===undefined?'':config.total||'',_id_sucursal=config===undefined?'':config.id_sucursal||'',_id_usuario=config===undefined?'':config.id_usuario||'',_pagado=config===undefined?'':config.pagado||'',_cancelada=config===undefined?'':config.cancelada||'',_ip=config===undefined?'':config.ip||'',_liquidada=config===undefined?'':config.liquidada||'';this.getIdVenta=function()
{return _id_venta;};this.setIdVenta=function(id_venta)
{_id_venta=id_venta;};this.getIdVentaEquipo=function()
{return _id_venta_equipo;};this.setIdVentaEquipo=function(id_venta_equipo)
{_id_venta_equipo=id_venta_equipo;};this.getIdEquipo=function()
{return _id_equipo;};this.setIdEquipo=function(id_equipo)
{_id_equipo=id_equipo;};this.getIdCliente=function()
{return _id_cliente;};this.setIdCliente=function(id_cliente)
{_id_cliente=id_cliente;};this.getTipoVenta=function()
{return _tipo_venta;};this.setTipoVenta=function(tipo_venta)
{_tipo_venta=tipo_venta;};this.getTipoPago=function()
{return _tipo_pago;};this.setTipoPago=function(tipo_pago)
{_tipo_pago=tipo_pago;};this.getFecha=function()
{return _fecha;};this.setFecha=function(fecha)
{_fecha=fecha;};this.getSubtotal=function()
{return _subtotal;};this.setSubtotal=function(subtotal)
{_subtotal=subtotal;};this.getIva=function()
{return _iva;};this.setIva=function(iva)
{_iva=iva;};this.getDescuento=function()
{return _descuento;};this.setDescuento=function(descuento)
{_descuento=descuento;};this.getTotal=function()
{return _total;};this.setTotal=function(total)
{_total=total;};this.getIdSucursal=function()
{return _id_sucursal;};this.setIdSucursal=function(id_sucursal)
{_id_sucursal=id_sucursal;};this.getIdUsuario=function()
{return _id_usuario;};this.setIdUsuario=function(id_usuario)
{_id_usuario=id_usuario;};this.getPagado=function()
{return _pagado;};this.setPagado=function(pagado)
{_pagado=pagado;};this.getCancelada=function()
{return _cancelada;};this.setCancelada=function(cancelada)
{_cancelada=cancelada;};this.getIp=function()
{return _ip;};this.setIp=function(ip)
{_ip=ip;};this.getLiquidada=function()
{return _liquidada;};this.setLiquidada=function(liquidada)
{_liquidada=liquidada;};this.json={id_venta:_id_venta,id_venta_equipo:_id_venta_equipo,id_equipo:_id_equipo,id_cliente:_id_cliente,tipo_venta:_tipo_venta,tipo_pago:_tipo_pago,fecha:_fecha,subtotal:_subtotal,iva:_iva,descuento:_descuento,total:_total,id_sucursal:_id_sucursal,id_usuario:_id_usuario,pagado:_pagado,cancelada:_cancelada,ip:_ip,liquidada:_liquidada};var _callback_stack=[];this.pushCallback=function(fn,context){_callback_stack.push({f:fn,c:context});}
this.callCallback=function(params){var t=_callback_stack.pop();t.f.call(t.c,params);}
this.save=function(callback)
{if(DEBUG)console.log('estoy en save()');this.pushCallback(callback,this);var cb=function(res){if(DEBUG)console.log('estoy de regreso en save(',res,') con el contexto:',this);if(res==null){create.call(this,null);}else{update.call(this,null);}};Ventas.getByPK(this.getIdVenta(),{context:this,callback:cb})};this.search=function($orderBy,$orden)
{$sql="SELECT * from ventas WHERE (";$val=[];if(this.getIdVenta()!=null){$sql+=" id_venta = ? AND";$val.push(this.getIdVenta());}
if(this.getIdVentaEquipo()!=null){$sql+=" id_venta_equipo = ? AND";$val.push(this.getIdVentaEquipo());}
if(this.getIdEquipo()!=null){$sql+=" id_equipo = ? AND";$val.push(this.getIdEquipo());}
if(this.getIdCliente()!=null){$sql+=" id_cliente = ? AND";$val.push(this.getIdCliente());}
if(this.getTipoVenta()!=null){$sql+=" tipo_venta = ? AND";$val.push(this.getTipoVenta());}
if(this.getTipoPago()!=null){$sql+=" tipo_pago = ? AND";$val.push(this.getTipoPago());}
if(this.getFecha()!=null){$sql+=" fecha = ? AND";$val.push(this.getFecha());}
if(this.getSubtotal()!=null){$sql+=" subtotal = ? AND";$val.push(this.getSubtotal());}
if(this.getIva()!=null){$sql+=" iva = ? AND";$val.push(this.getIva());}
if(this.getDescuento()!=null){$sql+=" descuento = ? AND";$val.push(this.getDescuento());}
if(this.getTotal()!=null){$sql+=" total = ? AND";$val.push(this.getTotal());}
if(this.getIdSucursal()!=null){$sql+=" id_sucursal = ? AND";$val.push(this.getIdSucursal());}
if(this.getIdUsuario()!=null){$sql+=" id_usuario = ? AND";$val.push(this.getIdUsuario());}
if(this.getPagado()!=null){$sql+=" pagado = ? AND";$val.push(this.getPagado());}
if(this.getCancelada()!=null){$sql+=" cancelada = ? AND";$val.push(this.getCancelada());}
if(this.getIp()!=null){$sql+=" ip = ? AND";$val.push(this.getIp());}
if(this.getLiquidada()!=null){$sql+=" liquidada = ? AND";$val.push(this.getLiquidada());}
if($val.length==0){return[];}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};var create=function()
{if(DEBUG)console.log('estoy en create(this)');$sql="INSERT INTO ventas ( id_venta, id_venta_equipo, id_equipo, id_cliente, tipo_venta, tipo_pago, fecha, subtotal, iva, descuento, total, id_sucursal, id_usuario, pagado, cancelada, ip, liquidada ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";$params=[this.getIdVenta(),this.getIdVentaEquipo(),this.getIdEquipo(),this.getIdCliente(),this.getTipoVenta(),this.getTipoPago(),this.getFecha(),this.getSubtotal(),this.getIva(),this.getDescuento(),this.getTotal(),this.getIdSucursal(),this.getIdUsuario(),this.getPagado(),this.getCancelada(),this.getIp(),this.getLiquidada(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de insert():',tx,results);old_this.callCallback(old_this);});return;};var update=function()
{if(DEBUG)console.log('estoy en update(',this,')');$sql="UPDATE ventas SET  id_venta_equipo = ?, id_equipo = ?, id_cliente = ?, tipo_venta = ?, tipo_pago = ?, fecha = ?, subtotal = ?, iva = ?, descuento = ?, total = ?, id_sucursal = ?, id_usuario = ?, pagado = ?, cancelada = ?, ip = ?, liquidada = ? WHERE  id_venta = ?;";$params=[this.getIdVentaEquipo(),this.getIdEquipo(),this.getIdCliente(),this.getTipoVenta(),this.getTipoPago(),this.getFecha(),this.getSubtotal(),this.getIva(),this.getDescuento(),this.getTotal(),this.getIdSucursal(),this.getIdUsuario(),this.getPagado(),this.getCancelada(),this.getIp(),this.getLiquidada(),this.getIdVenta(),];var old_this=this;db.query($sql,$params,function(tx,results){if(DEBUG)console.log('ya termine el query de update():',tx,results,this);old_this.callCallback(old_this);});return;};this.destruct=function(config)
{$sql="DELETE FROM ventas WHERE  id_venta = ?;";$params=[this.getIdVenta()];if(DEBUG){console.log($sql,$params);}
db.query($sql,$params,function(tx,results){config.callback.call(config.context||null,results);});return;};this.byRange=function($ventas,$orderBy,$orden)
{$sql="SELECT * from ventas WHERE (";$val=[];if((($a=this.getIdVenta())!=null)&(($b=$ventas.getIdVenta())!=null)){$sql+=" id_venta >= ? AND id_venta <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_venta = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdVentaEquipo())!=null)&(($b=$ventas.getIdVentaEquipo())!=null)){$sql+=" id_venta_equipo >= ? AND id_venta_equipo <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_venta_equipo = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdEquipo())!=null)&(($b=$ventas.getIdEquipo())!=null)){$sql+=" id_equipo >= ? AND id_equipo <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_equipo = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdCliente())!=null)&(($b=$ventas.getIdCliente())!=null)){$sql+=" id_cliente >= ? AND id_cliente <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_cliente = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getTipoVenta())!=null)&(($b=$ventas.getTipoVenta())!=null)){$sql+=" tipo_venta >= ? AND tipo_venta <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" tipo_venta = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getTipoPago())!=null)&(($b=$ventas.getTipoPago())!=null)){$sql+=" tipo_pago >= ? AND tipo_pago <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" tipo_pago = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getFecha())!=null)&(($b=$ventas.getFecha())!=null)){$sql+=" fecha >= ? AND fecha <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" fecha = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getSubtotal())!=null)&(($b=$ventas.getSubtotal())!=null)){$sql+=" subtotal >= ? AND subtotal <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" subtotal = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIva())!=null)&(($b=$ventas.getIva())!=null)){$sql+=" iva >= ? AND iva <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" iva = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getDescuento())!=null)&(($b=$ventas.getDescuento())!=null)){$sql+=" descuento >= ? AND descuento <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" descuento = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getTotal())!=null)&(($b=$ventas.getTotal())!=null)){$sql+=" total >= ? AND total <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" total = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdSucursal())!=null)&(($b=$ventas.getIdSucursal())!=null)){$sql+=" id_sucursal >= ? AND id_sucursal <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_sucursal = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIdUsuario())!=null)&(($b=$ventas.getIdUsuario())!=null)){$sql+=" id_usuario >= ? AND id_usuario <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" id_usuario = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getPagado())!=null)&(($b=$ventas.getPagado())!=null)){$sql+=" pagado >= ? AND pagado <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" pagado = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getCancelada())!=null)&(($b=$ventas.getCancelada())!=null)){$sql+=" cancelada >= ? AND cancelada <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" cancelada = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getIp())!=null)&(($b=$ventas.getIp())!=null)){$sql+=" ip >= ? AND ip <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" ip = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
if((($a=this.getLiquidada())!=null)&(($b=$ventas.getLiquidada())!=null)){$sql+=" liquidada >= ? AND liquidada <= ? AND";$val.push(Math.min($a,$b));$val.push(Math.max($a,$b));}else{if($a||$b){$sql+=" liquidada = ? AND";$a=$a==null?$b:$a;$val.push($a);}}
$sql=$sql.substr(0,$sql.length-3)+" )";if($orderBy!==null){$sql+=" order by "+$orderBy+" "+$orden;}
return $sql;};}
Ventas.getAll=function(config)
{$sql="SELECT * from ventas";if(config.orden!==undefined)
$sql+=" ORDER BY "+config.orden+" "+config.tipo_de_orden;if(config.pagina!==undefined)
$sql+=" LIMIT "+((config.pagina-1)*config.columnas_por_pagina)+","+config.columnas_por_pagina;db.query($sql,[],function(tx,results){fres=[];for(i=0;i<results.rows.length;i++){fres.push(new Ventas(results.rows.item(i)))}
config.callback.call(config.context||null,fres);});return;};Ventas.getByPK=function($id_venta,config)
{if(DEBUG)console.log('estoy en getbypk()');$sql="SELECT * FROM ventas WHERE (id_venta = ? ) LIMIT 1;";db.query($sql,[$id_venta],function(tx,results){if(DEBUG)console.log('ya termine el query de getByPK():',tx,results);if(results.rows.length==0)fres=null;else fres=new Ventas(results.rows.item(0));config.callback.call(config.context||null,fres);});return;};String.prototype.startsWith=function(str){return(this.match("^"+str)==str)}
Array.prototype.has=function(v){for(i=0;i<this.length;i++){if(this[i]==v)return true;}return false;}
Mosaico=function(config)
{Mosaico.currentInstance=this;this.uniqueID="mosaic-"+parseInt(Math.random()*1000);this.config=config;this.createHtml();return this;};Mosaico.prototype.uniqueID=null;Mosaico.prototype.config=null;Mosaico.prototype.destroy=function()
{document.getElementById(this.uniqueID).parentNode.removeChild(document.getElementById(this.uniqueID));};Mosaico.prototype.createHtml=function()
{var wrapper=document.createElement('div');wrapper.setAttribute('id',this.uniqueID);wrapper.setAttribute('class','mosaico-wrapper');var element=document.getElementById(this.config.renderTo);while(element.firstChild){element.removeChild(element.firstChild);}
element.appendChild(wrapper);var item,title,image;for(a=0;a<this.config.items.length;a++){item=document.createElement('div');item.setAttribute('id','mosaico-item-'+a);item.setAttribute('name',Mosaico.currentInstance.config.items[a].id);item.setAttribute('class','mosaico-item');item.onclick=function(){Mosaico.currentInstance.config.callBack(this.getAttribute("name"));}
wrapper.appendChild(item);image=document.createElement('div');image.setAttribute('style','background: url('+this.config.items[a].image+') no-repeat;');image.setAttribute('class','mosaico-image');item.appendChild(image);title=document.createElement('div');title.innerHTML=this.config.items[a].title;title.setAttribute('class','mosaico-title');item.appendChild(title);}};POS.currencyFormat=function(num){num=num.toString().replace(/\$|\,/g,'');if(isNaN(num)){num="0";}
sign=(num==(num=Math.abs(num)));num=Math.floor(num*100+0.50000000001);cents=num%100;num=Math.floor(num/100).toString();if(cents<10){cents="0"+cents;}
for(var i=0;i<Math.floor((num.length-(1+i))/3);i++)
{num=num.substring(0,num.length-(4*i+3))+','+num.substring(num.length-(4*i+3));}
return(((sign)?'':'-')+'$'+num+'.'+cents);};POS.infoSucursal=null;POS.documentos=null;POS.fecha=function(f){var fecha=new Date(f.replace(/-/g,"/"));var mes;switch(fecha.getMonth()){case 0:mes="Enero";break;case 1:mes="Febrero";break;case 2:mes="Marzo";break;case 3:mes="Abril";break;case 4:mes="Mayo";break;case 5:mes="Junio";break;case 6:mes="Julio";break;case 7:mes="Agosto";break;case 8:mes="Septiembre";break;case 9:mes="Octubre";break;case 10:mes="Noviembre";break;case 11:mes="Diciembre";break;}
var min=fecha.getMinutes()<10?"0"+fecha.getMinutes():fecha.getMinutes();var hours=Math.abs(fecha.getHours()-12);var meridiano=fecha.getHours()>12?"pm":"am";return fecha.getDate()+" de "+mes+" a las "+hours+":"+min+" "+meridiano;}
POS.leyendasTicket=null;Ext.MessageBox.YESNO[1].text="Si";Ext.Ajax.timeout=10000;POS.CHECK_DB_TIMEOUT=15000;POS.A={failure:false,sendHeart:true};POS.U={g:null};var POS_COMPRA_A_CLIENTES;Ext.Ajax.on("requestexception",function(a,b,c){if(!POS.A.failure){POS.A.failure=true;setTimeout("dummyRequest()",500);if(DEBUG){console.warn("SERVER UNREACHABLE");}}});Ext.Ajax.on("beforerequest",function(conn,options){if(POS.A.failure&&options.params.action!="dummy"){if(DEBUG){console.warn("--- ---- conection is lost !!!! --------- ---");console.warn("server request on unreachable server");console.log("parametros:",options.params)}}});Ext.Ajax.on("requestcomplete",function(a,b,c){if(POS.A.failure){POS.A.failure=false;if(DEBUG){console.warn("--- ---- conection is back !!!! --------- ---");}
Aplicacion.Mostrador.currentInstance.checkForOfflineSales();}});POS.fillWithSpaces=function(cad,spaces,multiline)
{cad=cad.toString();if(cad.length<spaces){for(var s=cad.length;s<spaces;s++){cad+=" ";};return cad;}
if(multiline){}else{return cad.substring(0,spaces);}}
function dummyRequest(){Ext.Ajax.request({url:'../proxy.php',params:{action:1105},success:function(r){if(DEBUG){console.log("dummy request completed !!!");}
try{r=Ext.util.JSON.decode(response.responseText);if((r.reboot!==undefined)){if(DEBUG){console.error("reboot !");}
window.location=".";}}catch(e){return;}},failure:function(){setTimeout("dummyRequest()",500);}});}
function task(){Ext.Ajax.request({url:'../proxy.php',scope:this,params:{action:1101,hash:heartHash},success:function(response,opts){if(DEBUG)console.log("heartbeat returned")
setTimeout("task()",POS.CHECK_DB_TIMEOUT);if(response.responseText.length==0){return;}
try{r=Ext.util.JSON.decode(response.responseText);}catch(e){return;}
if((r.reboot!==undefined)){if(DEBUG){console.error("reboot !");}
window.location=".";}
if(r.success&&r.hash!=heartHash){if(DEBUG){console.log("Main hash has changed, reloading !");}
heartHash=r.hash;reload();}}});}
function reload(){Ext.Ajax.request({url:'../proxy.php',scope:this,params:{action:400,hashCheck:Aplicacion.Inventario.currentInstance.Inventario.hash},success:function(response,opts){try{inventario=Ext.util.JSON.decode(response.responseText);}catch(e){return;}
if(!inventario.success){return;}
Aplicacion.Inventario.currentInstance.Inventario.productos=inventario.datos;Aplicacion.Inventario.currentInstance.Inventario.lastUpdate=Math.round(new Date().getTime()/1000.0);Aplicacion.Inventario.currentInstance.Inventario.hash=inventario.hash;Aplicacion.Inventario.currentInstance.inventarioListaStore.loadData(inventario.datos);}});Ext.Ajax.request({url:'../proxy.php',scope:this,params:{action:300,hashCheck:Aplicacion.Clientes.currentInstance.listaDeClientes.hash},success:function(response,opts){try{clientes=Ext.util.JSON.decode(response.responseText);}catch(e){return;}
if(!clientes.success){return;}
Aplicacion.Clientes.currentInstance.listaDeClientes.lista=clientes.datos;Aplicacion.Clientes.currentInstance.listaDeClientes.lastUpdate=Math.round(new Date().getTime()/1000.0);Aplicacion.Clientes.currentInstance.listaDeClientes.hash=clientes.hash;Aplicacion.Clientes.currentInstance.listaDeClientesStore.loadData(clientes.datos);}});if(POS.U.g){Ext.Ajax.request({url:'../proxy.php',scope:this,params:{action:207,hashCheck:Aplicacion.Autorizaciones.currentInstance.listaDeAutorizaciones.hash},success:function(response,opts){try{autorizaciones=Ext.util.JSON.decode(response.responseText);}catch(e){return;}
if(!autorizaciones.success){return;}
Aplicacion.Autorizaciones.currentInstance.listaDeAutorizaciones.lista=autorizaciones.payload;Aplicacion.Autorizaciones.currentInstance.listaDeAutorizaciones.lastUpdate=Math.round(new Date().getTime()/1000.0);Aplicacion.Autorizaciones.currentInstance.listaDeAutorizaciones.hash=autorizaciones.hash;Aplicacion.Autorizaciones.currentInstance.listaDeAutorizacionesStore.loadData(autorizaciones.payload);Aplicacion.Autorizaciones.currentInstance.updateListaAutorizaciones();Ext.Msg.confirm("Autorizaciones","Tiene autorizaciones por atender. &iquest; Desea verlas ahora ?<br><br>",function(a){if(a=="yes"){sink.Main.ui.setActiveItem(Aplicacion.Autorizaciones.currentInstance.listaDeAutorizacionesPanel,'fade');}});}});}}
var heartHash=null;if(POS.A.sendHeart)
{setTimeout("task( )",POS.CHECK_DB_TIMEOUT);}
POS.error=function(ajaxResponse,catchedError)
{console.error("POS.ERROR() : ",ajaxResponse,catchedError);if(ajaxResponse!==undefined&&ajaxResponse.reboot!==undefined&&ajaxResponse.reboot===true){window.location=".";}};POS.RETRIVE_CONFIG=function(){if(DEBUG)
{console.log("Loading sucursal config ....");}
Ext.Ajax.request({url:'../proxy.php',scope:this,params:{action:1106},success:function(response,opts){try{data=Ext.util.JSON.decode(response.responseText);}catch(e){return POS.error(response,e);}
console.group("CONFIG");console.log("RAW_CONFIG",data);POS.leyendasTicket=data.POS_LEYENDAS_TICKET;if(DEBUG)console.log("POS.leyendasTicket = ",POS.leyendasTicket);POS.documentos=data.POS_DOCUMENTOS;if(DEBUG)console.log("POS.documentos = ",POS.documentos);POS.infoSucursal=data.POS_INFO_SUCURSAL;if(DEBUG)console.log("POS.infoSucursal = ",POS.infoSucursal);Ext.Ajax.timeout=data.EXT_AJAX_TIMEOUT;if(DEBUG)console.log("Ext.Ajax.timeout = ",Ext.Ajax.timeout);POS.CHECK_DB_TIMEOUT=data.CHECK_DB_TIMEOUT;if(DEBUG)console.log("POS.CHECK_DB_TIMEOUT = ",POS.CHECK_DB_TIMEOUT);POS_COMPRA_A_CLIENTES=data.POS_COMPRA_A_CLIENTES;if(DEBUG)console.log("POS_COMPRA_A_CLIENTES = ",POS_COMPRA_A_CLIENTES);console.groupEnd();imReadyToStart();},failure:function(response){POS.error(response);}});};POS.RETRIVE_CONFIG();Ext.ns('POS','sink','Ext.ux');Ext.ux.UniversalUI=Ext.extend(Ext.Panel,{fullscreen:true,layout:'card',items:[{cls:'launchscreen',html:'<div align=center><img src="../media/cash_register.png"><br><div id="space_for_suc_name"></div><img src="../media/logo_pos.png"></div>'}],backText:'Regresar',useTitleAsBackText:false,initComponent:function(){this.navigationButton=new Ext.Button({hidden:Ext.is.Phone||Ext.Viewport.orientation=='landscape',text:'Navegacion',handler:this.onNavButtonTap,scope:this});this.backButton=new Ext.Button({text:this.backText,ui:'back',handler:this.onUiBack,hidden:true,scope:this});var btns=[this.navigationButton];if(Ext.is.Phone){btns.unshift(this.backButton);}
this.navigationBar=new Ext.Toolbar({ui:'dark',dock:'top',title:this.title,items:btns.concat(this.buttons||[])});this.navigationPanel=new Ext.NestedList({store:sink.StructureStore,useToolbar:Ext.is.Phone?false:true,updateTitleText:false,dock:'left',hidden:!Ext.is.Phone&&Ext.Viewport.orientation=='portrait',toolbar:Ext.is.Phone?this.navigationBar:null,listeners:{itemtap:this.onNavPanelItemTap,scope:this}});this.navigationPanel.on('back',this.onNavBack,this);if(!Ext.is.Phone){this.navigationPanel.setWidth(190);}
this.dockedItems=this.dockedItems||[];this.dockedItems.unshift(this.navigationBar);if(!Ext.is.Phone&&Ext.Viewport.orientation=='landscape'){this.dockedItems.unshift(this.navigationPanel);}
else if(Ext.is.Phone){this.items=this.items||[];this.items.unshift(this.navigationPanel);}
this.addEvents('navigate');Ext.ux.UniversalUI.superclass.initComponent.call(this);},toggleUiBackButton:function(){var navPnl=this.navigationPanel;if(Ext.is.Phone){if(this.getActiveItem()===navPnl){var currList=navPnl.getActiveItem(),currIdx=navPnl.items.indexOf(currList),recordNode=currList.recordNode,title=navPnl.renderTitleText(recordNode),parentNode=recordNode?recordNode.parentNode:null,backTxt=(parentNode&&!parentNode.isRoot)?navPnl.renderTitleText(parentNode):this.title||'';if(currIdx<=0){this.navigationBar.setTitle(this.title||'');this.backButton.hide();}else{this.navigationBar.setTitle(title);if(this.useTitleAsBackText){this.backButton.setText(backTxt);}
this.backButton.show();}}else{var activeItem=navPnl.getActiveItem(),recordNode=activeItem.recordNode,backTxt=(recordNode&&!recordNode.isRoot)?navPnl.renderTitleText(recordNode):this.title||'';if(this.useTitleAsBackText){this.backButton.setText(backTxt);}
this.backButton.show();}
this.navigationBar.doLayout();}},onUiBack:function(){var navPnl=this.navigationPanel;if(this.getActiveItem()===navPnl){navPnl.onBackTap();}else{this.setActiveItem(navPnl,{type:'slide',reverse:true});}
this.toggleUiBackButton();this.fireEvent('navigate',this,{});},onNavPanelItemTap:function(subList,subIdx,el,e){var store=subList.getStore(),record=store.getAt(subIdx),recordNode=record.node,nestedList=this.navigationPanel,title=nestedList.renderTitleText(recordNode),card,preventHide,anim;if(record){card=record.get('card');anim=record.get('cardSwitchAnimation');preventHide=record.get('preventHide');}
if(Ext.Viewport.orientation=='portrait'&&!Ext.is.Phone&&!recordNode.childNodes.length&&!preventHide){this.navigationPanel.hide();}
if(card){this.setActiveItem(card,anim||'slide');this.currentCard=card;}
if(title){this.navigationBar.setTitle(title);}
this.toggleUiBackButton();this.fireEvent('navigate',this,record);},onNavButtonTap:function(){this.navigationPanel.showBy(this.navigationButton,'fade');},layoutOrientation:function(orientation,w,h){if(!Ext.is.Phone){if(orientation=='portrait'){this.navigationPanel.hide(false);this.removeDocked(this.navigationPanel,false);if(this.navigationPanel.rendered){this.navigationPanel.el.appendTo(document.body);}
this.navigationPanel.setFloating(true);this.navigationPanel.setHeight(400);this.navigationButton.show(false);}else{this.navigationPanel.setFloating(false);this.navigationPanel.show(false);this.navigationButton.hide(false);this.insertDocked(0,this.navigationPanel);}
this.navigationBar.doComponentLayout();}
Ext.ux.UniversalUI.superclass.layoutOrientation.call(this,orientation,w,h);}});sink.Main={init:function(){this.ui=new Ext.ux.UniversalUI({title:'POS',useTitleAsBackText:true,navigationItems:sink.Structure,buttons:[{xtype:'spacer'}],listeners:{navigate:this.onNavigate,scope:this}});},onNavigate:function(ui,record){if(DEBUG){console.log("Navegando !");}
Aplicacion.Inventario.currentInstance.inventarioListaStore.clearFilter();}};Ext.setup({glossOnIcon:true,onReady:function()
{imReadyToStart();}});var SYSTEMS_NEEDED=2;var systemsLoaded=0;function imReadyToStart(){Ext.getBody().mask("cargando...");if(++systemsLoaded==SYSTEMS_NEEDED){sink.Main.init();Ext.getBody().unmask();Ext.get("space_for_suc_name").update(POS.infoSucursal.descripcion);}}
sink.Structure=[];POS.Apps=[];Ext.ns("Aplicacion");