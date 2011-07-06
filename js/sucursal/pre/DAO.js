var Database = function (){
	this.db = openDatabase("pos", "1.0", "Point of Sale", 200000);
	// Crear la esctructura de la base de datos
	this.db.transaction( function(tx) {
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `actualizacion_de_precio` ("
			+ "  `id_actualizacion` int(12) NOT NULL  , "
			+ "  `id_producto` int(11) NOT NULL,"
			+ "  `id_usuario` int(11) NOT NULL,"
			+ "  `precio_venta` float NOT NULL,"
			+ "  `precio_venta_procesado` float NOT NULL,"
			+ "  `precio_intersucursal` float NOT NULL,"
			+ "  `precio_intersucursal_procesado` float NOT NULL,"
			+ "  `precio_compra` float NOT NULL DEFAULT '0' , "
			+ "  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,"
			+ " PRIMARY KEY (`id_actualizacion`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `autorizacion` ("
			+ "  `id_autorizacion` int(11)  NOT NULL ,"
			+ "  `id_usuario` int(11) NOT NULL , "
			+ "  `id_sucursal` int(11) NOT NULL , "
			+ "  `fecha_peticion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP , "
			+ "  `fecha_respuesta` timestamp NULL DEFAULT NULL , "
			+ "  `estado` int(11) NOT NULL , "
			+ "  `parametros` varchar(2048) NOT NULL , "
			+ " PRIMARY KEY (`id_autorizacion`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `cliente` ("
			+ "  `id_cliente` int(11) NOT NULL  , "
			+ "  `rfc` varchar(20)  NOT NULL , "
			+ "  `razon_social` varchar(100)  NOT NULL , "
			+ "  `calle` varchar(300)  NOT NULL , "
			+ "  `numero_exterior` varchar(20)  NOT NULL , "
			+ "  `numero_interior` varchar(20)  DEFAULT NULL , "
			+ "  `colonia` varchar(50)  NOT NULL , "
			+ "  `referencia` varchar(100)  DEFAULT NULL , "
			+ "  `localidad` varchar(50)  DEFAULT NULL , "
			+ "  `municipio` varchar(55)  NOT NULL , "
			+ "  `estado` varchar(50)  NOT NULL , "
			+ "  `pais` varchar(50)  NOT NULL , "
			+ "  `codigo_postal` varchar(15)  NOT NULL , "
			+ "  `telefono` varchar(25)  DEFAULT NULL , "
			+ "  `e_mail` varchar(60)  DEFAULT NULL , "
			+ "  `limite_credito` float NOT NULL DEFAULT '0' , "
			+ "  `descuento` float NOT NULL DEFAULT '0' , "
			+ "  `activo` tinyint(2) NOT NULL DEFAULT '1' , "
			+ "  `id_usuario` int(11) NOT NULL , "
			+ "  `id_sucursal` int(11) NOT NULL , "
			+ "  `fecha_ingreso` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP , "
			+ "  `password` varchar(64)  DEFAULT NULL , "
			+ "  `last_login` timestamp NULL DEFAULT NULL,"
			+ "  `grant_changes` tinyint(1) DEFAULT '0' , "
			+ " PRIMARY KEY (`id_cliente`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `compra_cliente` ("
			+ "  `id_compra` int(11) NOT NULL  , "
			+ "  `id_cliente` int(11) NOT NULL , "
			+ "  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP , "
			+ "  `subtotal` float DEFAULT NULL , "
			+ "  `impuesto` float DEFAULT '0' , "
			+ "  `descuento` float NOT NULL DEFAULT '0' , "
			+ "  `total` float NOT NULL DEFAULT '0' , "
			+ "  `id_sucursal` int(11) NOT NULL , "
			+ "  `id_usuario` int(11) NOT NULL , "
			+ "  `pagado` float NOT NULL DEFAULT '0' , "
			+ "  `cancelada` tinyint(1) NOT NULL DEFAULT '0' , "
			+ "  `ip` varchar(16)  NOT NULL DEFAULT '0.0.0.0' , "
			+ "  `liquidada` tinyint(1) NOT NULL DEFAULT '0' , "
			+ "  PRIMARY KEY (`id_compra`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `detalle_compra_cliente` ("
			+ "  `id_compra` int(11) NOT NULL , "
			+ "  `id_producto` int(11) NOT NULL , "
			+ "  `cantidad` float NOT NULL , "
			+ "  `precio` float NOT NULL , "
			+ "  `descuento` float  DEFAULT '0' , "
			+ " PRIMARY KEY (`id_compra`,`id_producto`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `detalle_inventario` ("
			+ "  `id_producto` int(11) NOT NULL , "
			+ "  `id_sucursal` int(11) NOT NULL , "
			+ "  `precio_venta` float NOT NULL , "
			+ "  `precio_venta_procesado` float NOT NULL DEFAULT '0' , "
			+ "  `existencias` float NOT NULL DEFAULT '0' , "
			+ "  `existencias_procesadas` float NOT NULL , "
			+ "  `precio_compra` float NOT NULL DEFAULT '0' , "
			+ " PRIMARY KEY (`id_producto`,`id_sucursal`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `detalle_venta` ("
			+ "  `id_venta` int(11) NOT NULL , "
			+ "  `id_producto` int(11) NOT NULL , "
			+ "  `cantidad` float NOT NULL , "
			+ "  `cantidad_procesada` float NOT NULL,"
			+ "  `precio` float NOT NULL , "
			+ "  `precio_procesada` float NOT NULL , "
			+ "  `descuento` float  DEFAULT '0' , "
			+ " PRIMARY KEY (`id_venta`,`id_producto`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `equipo` ("
			+ "  `id_equipo` int(6) NOT NULL  , "
			+ "  `token` varchar(128) DEFAULT NULL , "
			+ "  `full_ua` varchar(256) NOT NULL , "
			+ "  `descripcion` varchar(254) NOT NULL , "
			+ "  `locked` tinyint(1) NOT NULL DEFAULT '0' , "
			+ " PRIMARY KEY (`id_equipo`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `factura_compra` ("
			+ "  `folio` varchar(15)  NOT NULL,"
			+ "  `id_compra` int(11) NOT NULL , "
			+ " PRIMARY KEY (`folio`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `factura_venta` ("
			+ "  `id_folio` int(11)  NOT NULL  , "
			+ "  `id_venta` int(11) NOT NULL , "
			+ "  `id_usuario` int(10) NOT NULL , "
			+ "  `xml` text  NOT NULL , "
			+ "  `lugar_emision` int(11) NOT NULL , "
			+ "  `activa` tinyint(1) NOT NULL DEFAULT '1' , "
			+ "  `sellada` tinyint(1) NOT NULL DEFAULT '0' , "
			+ "  `forma_pago` varchar(100)  NOT NULL,"
			+ "  `fecha_emision` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',"
			+ "  `version_tfd` varchar(10)  NOT NULL,"
			+ "  `folio_fiscal` varchar(128)  NOT NULL,"
			+ "  `fecha_certificacion` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',"
			+ "  `numero_certificado_sat` varchar(128)  NOT NULL,"
			+ "  `sello_digital_emisor` varchar(512)  NOT NULL,"
			+ "  `sello_digital_sat` varchar(512)  NOT NULL,"
			+ "  `cadena_original` varchar(2048)  NOT NULL,"
			+ " PRIMARY KEY (`id_folio`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `gastos` ("
			+ "  `id_gasto` int(11) NOT NULL  , "
			+ "  `folio` varchar(22) NOT NULL , "
			+ "  `concepto` varchar(100) NOT NULL , "
			+ "  `monto` float  NOT NULL , "
			+ "  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP , "
			+ "  `fecha_ingreso` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' , "
			+ "  `id_sucursal` int(11) NOT NULL , "
			+ "  `id_usuario` int(11) NOT NULL , "
			+ "  `nota` varchar(512) NOT NULL , "
			+ " PRIMARY KEY (`id_gasto`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `impresora` ("
			+ "  `id_impresora` int(11) NOT NULL  , "
			+ "  `id_sucursal` int(11) NOT NULL , "
			+ "  `descripcion` varchar(256)  NOT NULL , "
			+ "  `identificador` varchar(128)  NOT NULL , "
			+ " PRIMARY KEY (`id_impresora`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `ingresos` ("
			+ "  `id_ingreso` int(11) NOT NULL  , "
			+ "  `concepto` varchar(100) NOT NULL , "
			+ "  `monto` float NOT NULL , "
			+ "  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,"
			+ "  `fecha_ingreso` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' , "
			+ "  `id_sucursal` int(11) NOT NULL , "
			+ "  `id_usuario` int(11) NOT NULL , "
			+ "  `nota` varchar(512) NOT NULL , "
			+ " PRIMARY KEY (`id_ingreso`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `inventario` ("
			+ "  `id_producto` int(11) NOT NULL  , "
			+ "  `descripcion` varchar(30)  NOT NULL , "
			+ "  `agrupacion` varchar(8)  DEFAULT NULL , "
			+ "  `agrupacionTam` float DEFAULT NULL , "
			+ "  `activo` tinyint(1) NOT NULL DEFAULT '1' , "
			+ "  `precio_por_agrupacion` tinyint(1) NOT NULL DEFAULT '0',"
			+ "  PRIMARY KEY (`id_producto`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `pagos_venta` ("
			+ "  `id_pago` int(11) NOT NULL  , "
			+ "  `id_venta` int(11) NOT NULL , "
			+ "  `id_sucursal` int(11) NOT NULL , "
			+ "  `id_usuario` int(11) NOT NULL , "
			+ "  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP , "
			+ "  `monto` float NOT NULL , "
			+ " PRIMARY KEY (`id_pago`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `sucursal` ("
			+ "  `id_sucursal` int(11) NOT NULL  , "
			+ "  `gerente` int(11) DEFAULT NULL , "
			+ "  `descripcion` varchar(100)  NOT NULL , "
			+ "  `razon_social` varchar(100)  NOT NULL , "
			+ "  `rfc` varchar(20)  NOT NULL , "
			+ "  `calle` varchar(50)  NOT NULL , "
			+ "  `numero_exterior` varchar(10)  NOT NULL , "
			+ "  `numero_interior` varchar(10)  DEFAULT NULL , "
			+ "  `colonia` varchar(50)  NOT NULL , "
			+ "  `localidad` varchar(50)  DEFAULT NULL , "
			+ "  `referencia` varchar(200)  DEFAULT NULL , "
			+ "  `municipio` varchar(100)  NOT NULL , "
			+ "  `estado` varchar(50)  NOT NULL , "
			+ "  `pais` varchar(50)  NOT NULL , "
			+ "  `codigo_postal` varchar(15)  NOT NULL , "
			+ "  `telefono` varchar(20)  DEFAULT NULL , "
			+ "  `token` varchar(512)  DEFAULT NULL , "
			+ "  `letras_factura` char(1)  NOT NULL,"
			+ "  `activo` tinyint(1) NOT NULL DEFAULT '1',"
			+ "  `fecha_apertura` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP , "
			+ "  `saldo_a_favor` float NOT NULL DEFAULT '0' , "
			+ " PRIMARY KEY (`id_sucursal`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			+ "CREATE TABLE IF NOT EXISTS `ventas` ("
			+ "  `id_venta` int(11) NOT NULL  , "
			+ "  `id_venta_equipo` int(11) NOT NULL,"
			+ "  `id_equipo` int(11) DEFAULT NULL,"
			+ "  `id_cliente` int(11) NOT NULL , "
			+ "  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP , "
			+ "  `subtotal` float DEFAULT NULL , "
			+ "  `iva` float DEFAULT '0' , "
			+ "  `descuento` float NOT NULL DEFAULT '0' , "
			+ "  `total` float NOT NULL DEFAULT '0' , "
			+ "  `id_sucursal` int(11) NOT NULL , "
			+ "  `id_usuario` int(11) NOT NULL , "
			+ "  `pagado` float NOT NULL DEFAULT '0' , "
			+ "  `cancelada` tinyint(1) NOT NULL DEFAULT '0' , "
			+ "  `ip` varchar(16)  NOT NULL DEFAULT '0.0.0.0' , "
			+ "  `liquidada` tinyint(1) NOT NULL DEFAULT '0' , "
			+ " PRIMARY KEY (`id_venta`)"
			+ ");"
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
	    tx.executeSql(
			""
			,
	        [],
	        sqlWin,
	        sqlFail
	    	);
		}, txFail, txWin);

		this.query = function( query, params, fn ){
			this.db.transaction( function(tx) {
			    tx.executeSql(
			        query,
			        params,
			        fn,
			        sqlFail
			    );
			}, txFail, txWin);
		}
	var txFail = function (err) { console.error("TX failed: " + err.message); }
	var txWin = function(tx){  }	
	var sqlFail = function(err) { console.error("SQL failed: " + err.message, err); }
	var sqlWin = function(tx, response) { /*console.log("SQL succeeded: " + response.rows.length + " rows.");*/ }
};
var db = new Database();
/** Value Object file for table actualizacion_de_precio.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var ActualizacionDePrecio = function ( config )
{
 /**
	* id_actualizacion
	* 
	* id de actualizacion de precio
	* <b>Llave Primaria</b>
	* @access private
	* @var int(12)
	*/
	var _id_actualizacion = config === undefined ? '' : config.id_actualizacion || '',

 /**
	* id_producto
	* 
	*  [Campo no documentado]
	* @access private
	* @var int(11)
	*/
	_id_producto = config === undefined ? '' : config.id_producto || '',

 /**
	* id_usuario
	* 
	*  [Campo no documentado]
	* @access private
	* @var int(11)
	*/
	_id_usuario = config === undefined ? '' : config.id_usuario || '',

 /**
	* precio_venta
	* 
	*  [Campo no documentado]
	* @access private
	* @var float
	*/
	_precio_venta = config === undefined ? '' : config.precio_venta || '',

 /**
	* precio_venta_procesado
	* 
	*  [Campo no documentado]
	* @access private
	* @var float
	*/
	_precio_venta_procesado = config === undefined ? '' : config.precio_venta_procesado || '',

 /**
	* precio_intersucursal
	* 
	*  [Campo no documentado]
	* @access private
	* @var float
	*/
	_precio_intersucursal = config === undefined ? '' : config.precio_intersucursal || '',

 /**
	* precio_intersucursal_procesado
	* 
	*  [Campo no documentado]
	* @access private
	* @var float
	*/
	_precio_intersucursal_procesado = config === undefined ? '' : config.precio_intersucursal_procesado || '',

 /**
	* precio_compra
	* 
	* precio al que se le compra al publico este producto en caso de ser POS_COMPRA_A_CLIENTES
	* @access private
	* @var float
	*/
	_precio_compra = config === undefined ? '' : config.precio_compra || '',

 /**
	* fecha
	* 
	*  [Campo no documentado]
	* @access private
	* @var timestamp
	*/
	_fecha = config === undefined ? '' : config.fecha || '';

	/**
	  * getIdActualizacion
	  * 
	  * Get the <i>id_actualizacion</i> property for this object. Donde <i>id_actualizacion</i> es id de actualizacion de precio
	  * @return int(12)
	  */
	this.getIdActualizacion = function ()
	{
		return _id_actualizacion;
	};

	/**
	  * setIdActualizacion( $id_actualizacion )
	  * 
	  * Set the <i>id_actualizacion</i> property for this object. Donde <i>id_actualizacion</i> es id de actualizacion de precio.
	  * Una validacion basica se hara aqui para comprobar que <i>id_actualizacion</i> es de tipo <i>int(12)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdActualizacion( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(12)
	  */
	this.setIdActualizacion  = function ( id_actualizacion )
	{
		_id_actualizacion = id_actualizacion;
	};

	/**
	  * getIdProducto
	  * 
	  * Get the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	this.getIdProducto = function ()
	{
		return _id_producto;
	};

	/**
	  * setIdProducto( $id_producto )
	  * 
	  * Set the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_producto</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdProducto  = function ( id_producto )
	{
		_id_producto = id_producto;
	};

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	this.getIdUsuario = function ()
	{
		return _id_usuario;
	};

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdUsuario  = function ( id_usuario )
	{
		_id_usuario = id_usuario;
	};

	/**
	  * getPrecioVenta
	  * 
	  * Get the <i>precio_venta</i> property for this object. Donde <i>precio_venta</i> es  [Campo no documentado]
	  * @return float
	  */
	this.getPrecioVenta = function ()
	{
		return _precio_venta;
	};

	/**
	  * setPrecioVenta( $precio_venta )
	  * 
	  * Set the <i>precio_venta</i> property for this object. Donde <i>precio_venta</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>precio_venta</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPrecioVenta  = function ( precio_venta )
	{
		_precio_venta = precio_venta;
	};

	/**
	  * getPrecioVentaProcesado
	  * 
	  * Get the <i>precio_venta_procesado</i> property for this object. Donde <i>precio_venta_procesado</i> es  [Campo no documentado]
	  * @return float
	  */
	this.getPrecioVentaProcesado = function ()
	{
		return _precio_venta_procesado;
	};

	/**
	  * setPrecioVentaProcesado( $precio_venta_procesado )
	  * 
	  * Set the <i>precio_venta_procesado</i> property for this object. Donde <i>precio_venta_procesado</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>precio_venta_procesado</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPrecioVentaProcesado  = function ( precio_venta_procesado )
	{
		_precio_venta_procesado = precio_venta_procesado;
	};

	/**
	  * getPrecioIntersucursal
	  * 
	  * Get the <i>precio_intersucursal</i> property for this object. Donde <i>precio_intersucursal</i> es  [Campo no documentado]
	  * @return float
	  */
	this.getPrecioIntersucursal = function ()
	{
		return _precio_intersucursal;
	};

	/**
	  * setPrecioIntersucursal( $precio_intersucursal )
	  * 
	  * Set the <i>precio_intersucursal</i> property for this object. Donde <i>precio_intersucursal</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>precio_intersucursal</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPrecioIntersucursal  = function ( precio_intersucursal )
	{
		_precio_intersucursal = precio_intersucursal;
	};

	/**
	  * getPrecioIntersucursalProcesado
	  * 
	  * Get the <i>precio_intersucursal_procesado</i> property for this object. Donde <i>precio_intersucursal_procesado</i> es  [Campo no documentado]
	  * @return float
	  */
	this.getPrecioIntersucursalProcesado = function ()
	{
		return _precio_intersucursal_procesado;
	};

	/**
	  * setPrecioIntersucursalProcesado( $precio_intersucursal_procesado )
	  * 
	  * Set the <i>precio_intersucursal_procesado</i> property for this object. Donde <i>precio_intersucursal_procesado</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>precio_intersucursal_procesado</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPrecioIntersucursalProcesado  = function ( precio_intersucursal_procesado )
	{
		_precio_intersucursal_procesado = precio_intersucursal_procesado;
	};

	/**
	  * getPrecioCompra
	  * 
	  * Get the <i>precio_compra</i> property for this object. Donde <i>precio_compra</i> es precio al que se le compra al publico este producto en caso de ser POS_COMPRA_A_CLIENTES
	  * @return float
	  */
	this.getPrecioCompra = function ()
	{
		return _precio_compra;
	};

	/**
	  * setPrecioCompra( $precio_compra )
	  * 
	  * Set the <i>precio_compra</i> property for this object. Donde <i>precio_compra</i> es precio al que se le compra al publico este producto en caso de ser POS_COMPRA_A_CLIENTES.
	  * Una validacion basica se hara aqui para comprobar que <i>precio_compra</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPrecioCompra  = function ( precio_compra )
	{
		_precio_compra = precio_compra;
	};

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es  [Campo no documentado]
	  * @return timestamp
	  */
	this.getFecha = function ()
	{
		return _fecha;
	};

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFecha  = function ( fecha )
	{
		_fecha = fecha;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link ActualizacionDePrecio} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		ActualizacionDePrecio._callback_stack.push( _original_callback  );
		ActualizacionDePrecio._callback_stack.push( function(res){ 
						console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create.call(this, this);
						}else{
							update.call(res, res);
						}
	 			});
		ActualizacionDePrecio.getByPK(  this.getIdActualizacion() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link ActualizacionDePrecio} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param ActualizacionDePrecio [$actualizacion_de_precio] El objeto de tipo ActualizacionDePrecio
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from actualizacion_de_precio WHERE ("; 
		$val = [];
		if( this.getIdActualizacion() != null){
			$sql += " id_actualizacion = ? AND";
			$val.push( this.getIdActualizacion() );
		}

		if( this.getIdProducto() != null){
			$sql += " id_producto = ? AND";
			$val.push( this.getIdProducto() );
		}

		if( this.getIdUsuario() != null){
			$sql += " id_usuario = ? AND";
			$val.push( this.getIdUsuario() );
		}

		if( this.getPrecioVenta() != null){
			$sql += " precio_venta = ? AND";
			$val.push( this.getPrecioVenta() );
		}

		if( this.getPrecioVentaProcesado() != null){
			$sql += " precio_venta_procesado = ? AND";
			$val.push( this.getPrecioVentaProcesado() );
		}

		if( this.getPrecioIntersucursal() != null){
			$sql += " precio_intersucursal = ? AND";
			$val.push( this.getPrecioIntersucursal() );
		}

		if( this.getPrecioIntersucursalProcesado() != null){
			$sql += " precio_intersucursal_procesado = ? AND";
			$val.push( this.getPrecioIntersucursalProcesado() );
		}

		if( this.getPrecioCompra() != null){
			$sql += " precio_compra = ? AND";
			$val.push( this.getPrecioCompra() );
		}

		if( this.getFecha() != null){
			$sql += " fecha = ? AND";
			$val.push( this.getFecha() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new ActualizacionDePrecio($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto ActualizacionDePrecio suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto ActualizacionDePrecio dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param ActualizacionDePrecio [$actualizacion_de_precio] El objeto de tipo ActualizacionDePrecio a crear.
	  **/
	var create = function( actualizacion_de_precio )
	{
		console.log('estoy en create(this)');
		$sql = "INSERT INTO actualizacion_de_precio ( id_actualizacion, id_producto, id_usuario, precio_venta, precio_venta_procesado, precio_intersucursal, precio_intersucursal_procesado, precio_compra, fecha ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			actualizacion_de_precio.getIdActualizacion(), 
			actualizacion_de_precio.getIdProducto(), 
			actualizacion_de_precio.getIdUsuario(), 
			actualizacion_de_precio.getPrecioVenta(), 
			actualizacion_de_precio.getPrecioVentaProcesado(), 
			actualizacion_de_precio.getPrecioIntersucursal(), 
			actualizacion_de_precio.getPrecioIntersucursalProcesado(), 
			actualizacion_de_precio.getPrecioCompra(), 
			actualizacion_de_precio.getFecha(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				ActualizacionDePrecio._callback_stack.pop().call(null, actualizacion_de_precio);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param ActualizacionDePrecio [$actualizacion_de_precio] El objeto de tipo ActualizacionDePrecio a actualizar.
	  **/
	var update = function( $actualizacion_de_precio )
	{
		$sql = "UPDATE actualizacion_de_precio SET  id_producto = ?, id_usuario = ?, precio_venta = ?, precio_venta_procesado = ?, precio_intersucursal = ?, precio_intersucursal_procesado = ?, precio_compra = ?, fecha = ? WHERE  id_actualizacion = ?;";
		$params = [ 
			$actualizacion_de_precio.getIdProducto(), 
			$actualizacion_de_precio.getIdUsuario(), 
			$actualizacion_de_precio.getPrecioVenta(), 
			$actualizacion_de_precio.getPrecioVentaProcesado(), 
			$actualizacion_de_precio.getPrecioIntersucursal(), 
			$actualizacion_de_precio.getPrecioIntersucursalProcesado(), 
			$actualizacion_de_precio.getPrecioCompra(), 
			$actualizacion_de_precio.getFecha(), 
			$actualizacion_de_precio.getIdActualizacion(),  ] ;
		console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				ActualizacionDePrecio._callback_stack.pop().call(null, $actualizacion_de_precio);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto ActualizacionDePrecio suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( ActualizacionDePrecio.getByPK(this.getIdActualizacion()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM actualizacion_de_precio WHERE  id_actualizacion = ?;";
		$params = [ this.getIdActualizacion() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link ActualizacionDePrecio} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link ActualizacionDePrecio}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param ActualizacionDePrecio [$actualizacion_de_precio] El objeto de tipo ActualizacionDePrecio
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $actualizacion_de_precio , $orderBy , $orden )
	{
		$sql = "SELECT * from actualizacion_de_precio WHERE ("; 
		$val = [];
		if( (($a = this.getIdActualizacion()) != null) & ( ($b = $actualizacion_de_precio.getIdActualizacion()) != null) ){
				$sql += " id_actualizacion >= ? AND id_actualizacion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_actualizacion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdProducto()) != null) & ( ($b = $actualizacion_de_precio.getIdProducto()) != null) ){
				$sql += " id_producto >= ? AND id_producto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_producto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdUsuario()) != null) & ( ($b = $actualizacion_de_precio.getIdUsuario()) != null) ){
				$sql += " id_usuario >= ? AND id_usuario <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_usuario = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPrecioVenta()) != null) & ( ($b = $actualizacion_de_precio.getPrecioVenta()) != null) ){
				$sql += " precio_venta >= ? AND precio_venta <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " precio_venta = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPrecioVentaProcesado()) != null) & ( ($b = $actualizacion_de_precio.getPrecioVentaProcesado()) != null) ){
				$sql += " precio_venta_procesado >= ? AND precio_venta_procesado <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " precio_venta_procesado = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPrecioIntersucursal()) != null) & ( ($b = $actualizacion_de_precio.getPrecioIntersucursal()) != null) ){
				$sql += " precio_intersucursal >= ? AND precio_intersucursal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " precio_intersucursal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPrecioIntersucursalProcesado()) != null) & ( ($b = $actualizacion_de_precio.getPrecioIntersucursalProcesado()) != null) ){
				$sql += " precio_intersucursal_procesado >= ? AND precio_intersucursal_procesado <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " precio_intersucursal_procesado = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPrecioCompra()) != null) & ( ($b = $actualizacion_de_precio.getPrecioCompra()) != null) ){
				$sql += " precio_compra >= ? AND precio_compra <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " precio_compra = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFecha()) != null) & ( ($b = $actualizacion_de_precio.getFecha()) != null) ){
				$sql += " fecha >= ? AND fecha <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new ActualizacionDePrecio($foo));
		//}
		return $sql;
	};


}
	ActualizacionDePrecio._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link ActualizacionDePrecio}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	ActualizacionDePrecio.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from actualizacion_de_precio";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new ActualizacionDePrecio( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link ActualizacionDePrecio} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link ActualizacionDePrecio} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link ActualizacionDePrecio Un objeto del tipo {@link ActualizacionDePrecio}. NULL si no hay tal registro.
	  **/
	ActualizacionDePrecio.getByPK = function(  $id_actualizacion, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM actualizacion_de_precio WHERE (id_actualizacion = ? ) LIMIT 1;";
		db.query($sql, [ $id_actualizacion] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new ActualizacionDePrecio(results.rows.item(0)); 
				ActualizacionDePrecio._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : ActualizacionDePrecio._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table autorizacion.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var Autorizacion = function ( config )
{
 /**
	* id_autorizacion
	* 
	*  [Campo no documentado]
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_autorizacion = config === undefined ? '' : config.id_autorizacion || '',

 /**
	* id_usuario
	* 
	* Usuario que solicito esta autorizacion
	* @access private
	* @var int(11)
	*/
	_id_usuario = config === undefined ? '' : config.id_usuario || '',

 /**
	* id_sucursal
	* 
	* Sucursal de donde proviene esta autorizacion
	* @access private
	* @var int(11)
	*/
	_id_sucursal = config === undefined ? '' : config.id_sucursal || '',

 /**
	* fecha_peticion
	* 
	* Fecha cuando se genero esta autorizacion
	* @access private
	* @var timestamp
	*/
	_fecha_peticion = config === undefined ? '' : config.fecha_peticion || '',

 /**
	* fecha_respuesta
	* 
	* Fecha de cuando se resolvio esta aclaracion
	* @access private
	* @var timestamp
	*/
	_fecha_respuesta = config === undefined ? '' : config.fecha_respuesta || '',

 /**
	* estado
	* 
	* Estado actual de esta aclaracion
	* @access private
	* @var int(11)
	*/
	_estado = config === undefined ? '' : config.estado || '',

 /**
	* parametros
	* 
	* Parametros en formato JSON que describen esta autorizacion
	* @access private
	* @var varchar(2048)
	*/
	_parametros = config === undefined ? '' : config.parametros || '',

 /**
	* tipo
	* 
	* El tipo de autorizacion
	* @access private
	* @var enum('envioDeProductosASucursal','solicitudDeProductos','solicitudDeMerma','solicitudDeCambioPrecio','solicitudDeDevolucion','solicitudDeCambioLimiteDeCredito','solicitudDeGasto')
	*/
	_tipo = config === undefined ? '' : config.tipo || '';

	/**
	  * getIdAutorizacion
	  * 
	  * Get the <i>id_autorizacion</i> property for this object. Donde <i>id_autorizacion</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	this.getIdAutorizacion = function ()
	{
		return _id_autorizacion;
	};

	/**
	  * setIdAutorizacion( $id_autorizacion )
	  * 
	  * Set the <i>id_autorizacion</i> property for this object. Donde <i>id_autorizacion</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_autorizacion</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdAutorizacion( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdAutorizacion  = function ( id_autorizacion )
	{
		_id_autorizacion = id_autorizacion;
	};

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Usuario que solicito esta autorizacion
	  * @return int(11)
	  */
	this.getIdUsuario = function ()
	{
		return _id_usuario;
	};

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Usuario que solicito esta autorizacion.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdUsuario  = function ( id_usuario )
	{
		_id_usuario = id_usuario;
	};

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Sucursal de donde proviene esta autorizacion
	  * @return int(11)
	  */
	this.getIdSucursal = function ()
	{
		return _id_sucursal;
	};

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Sucursal de donde proviene esta autorizacion.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdSucursal  = function ( id_sucursal )
	{
		_id_sucursal = id_sucursal;
	};

	/**
	  * getFechaPeticion
	  * 
	  * Get the <i>fecha_peticion</i> property for this object. Donde <i>fecha_peticion</i> es Fecha cuando se genero esta autorizacion
	  * @return timestamp
	  */
	this.getFechaPeticion = function ()
	{
		return _fecha_peticion;
	};

	/**
	  * setFechaPeticion( $fecha_peticion )
	  * 
	  * Set the <i>fecha_peticion</i> property for this object. Donde <i>fecha_peticion</i> es Fecha cuando se genero esta autorizacion.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_peticion</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFechaPeticion  = function ( fecha_peticion )
	{
		_fecha_peticion = fecha_peticion;
	};

	/**
	  * getFechaRespuesta
	  * 
	  * Get the <i>fecha_respuesta</i> property for this object. Donde <i>fecha_respuesta</i> es Fecha de cuando se resolvio esta aclaracion
	  * @return timestamp
	  */
	this.getFechaRespuesta = function ()
	{
		return _fecha_respuesta;
	};

	/**
	  * setFechaRespuesta( $fecha_respuesta )
	  * 
	  * Set the <i>fecha_respuesta</i> property for this object. Donde <i>fecha_respuesta</i> es Fecha de cuando se resolvio esta aclaracion.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_respuesta</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFechaRespuesta  = function ( fecha_respuesta )
	{
		_fecha_respuesta = fecha_respuesta;
	};

	/**
	  * getEstado
	  * 
	  * Get the <i>estado</i> property for this object. Donde <i>estado</i> es Estado actual de esta aclaracion
	  * @return int(11)
	  */
	this.getEstado = function ()
	{
		return _estado;
	};

	/**
	  * setEstado( $estado )
	  * 
	  * Set the <i>estado</i> property for this object. Donde <i>estado</i> es Estado actual de esta aclaracion.
	  * Una validacion basica se hara aqui para comprobar que <i>estado</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setEstado  = function ( estado )
	{
		_estado = estado;
	};

	/**
	  * getParametros
	  * 
	  * Get the <i>parametros</i> property for this object. Donde <i>parametros</i> es Parametros en formato JSON que describen esta autorizacion
	  * @return varchar(2048)
	  */
	this.getParametros = function ()
	{
		return _parametros;
	};

	/**
	  * setParametros( $parametros )
	  * 
	  * Set the <i>parametros</i> property for this object. Donde <i>parametros</i> es Parametros en formato JSON que describen esta autorizacion.
	  * Una validacion basica se hara aqui para comprobar que <i>parametros</i> es de tipo <i>varchar(2048)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(2048)
	  */
	this.setParametros  = function ( parametros )
	{
		_parametros = parametros;
	};

	/**
	  * getTipo
	  * 
	  * Get the <i>tipo</i> property for this object. Donde <i>tipo</i> es El tipo de autorizacion
	  * @return enum('envioDeProductosASucursal','solicitudDeProductos','solicitudDeMerma','solicitudDeCambioPrecio','solicitudDeDevolucion','solicitudDeCambioLimiteDeCredito','solicitudDeGasto')
	  */
	this.getTipo = function ()
	{
		return _tipo;
	};

	/**
	  * setTipo( $tipo )
	  * 
	  * Set the <i>tipo</i> property for this object. Donde <i>tipo</i> es El tipo de autorizacion.
	  * Una validacion basica se hara aqui para comprobar que <i>tipo</i> es de tipo <i>enum('envioDeProductosASucursal','solicitudDeProductos','solicitudDeMerma','solicitudDeCambioPrecio','solicitudDeDevolucion','solicitudDeCambioLimiteDeCredito','solicitudDeGasto')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('envioDeProductosASucursal','solicitudDeProductos','solicitudDeMerma','solicitudDeCambioPrecio','solicitudDeDevolucion','solicitudDeCambioLimiteDeCredito','solicitudDeGasto')
	  */
	this.setTipo  = function ( tipo )
	{
		_tipo = tipo;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Autorizacion} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		Autorizacion._callback_stack.push( _original_callback  );
		Autorizacion._callback_stack.push( function(res){ 
						console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create.call(this, this);
						}else{
							update.call(res, res);
						}
	 			});
		Autorizacion.getByPK(  this.getIdAutorizacion() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Autorizacion} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param Autorizacion [$autorizacion] El objeto de tipo Autorizacion
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from autorizacion WHERE ("; 
		$val = [];
		if( this.getIdAutorizacion() != null){
			$sql += " id_autorizacion = ? AND";
			$val.push( this.getIdAutorizacion() );
		}

		if( this.getIdUsuario() != null){
			$sql += " id_usuario = ? AND";
			$val.push( this.getIdUsuario() );
		}

		if( this.getIdSucursal() != null){
			$sql += " id_sucursal = ? AND";
			$val.push( this.getIdSucursal() );
		}

		if( this.getFechaPeticion() != null){
			$sql += " fecha_peticion = ? AND";
			$val.push( this.getFechaPeticion() );
		}

		if( this.getFechaRespuesta() != null){
			$sql += " fecha_respuesta = ? AND";
			$val.push( this.getFechaRespuesta() );
		}

		if( this.getEstado() != null){
			$sql += " estado = ? AND";
			$val.push( this.getEstado() );
		}

		if( this.getParametros() != null){
			$sql += " parametros = ? AND";
			$val.push( this.getParametros() );
		}

		if( this.getTipo() != null){
			$sql += " tipo = ? AND";
			$val.push( this.getTipo() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Autorizacion($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Autorizacion suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Autorizacion dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Autorizacion [$autorizacion] El objeto de tipo Autorizacion a crear.
	  **/
	var create = function( autorizacion )
	{
		console.log('estoy en create(this)');
		$sql = "INSERT INTO autorizacion ( id_autorizacion, id_usuario, id_sucursal, fecha_peticion, fecha_respuesta, estado, parametros, tipo ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			autorizacion.getIdAutorizacion(), 
			autorizacion.getIdUsuario(), 
			autorizacion.getIdSucursal(), 
			autorizacion.getFechaPeticion(), 
			autorizacion.getFechaRespuesta(), 
			autorizacion.getEstado(), 
			autorizacion.getParametros(), 
			autorizacion.getTipo(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				Autorizacion._callback_stack.pop().call(null, autorizacion);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param Autorizacion [$autorizacion] El objeto de tipo Autorizacion a actualizar.
	  **/
	var update = function( $autorizacion )
	{
		$sql = "UPDATE autorizacion SET  id_usuario = ?, id_sucursal = ?, fecha_peticion = ?, fecha_respuesta = ?, estado = ?, parametros = ?, tipo = ? WHERE  id_autorizacion = ?;";
		$params = [ 
			$autorizacion.getIdUsuario(), 
			$autorizacion.getIdSucursal(), 
			$autorizacion.getFechaPeticion(), 
			$autorizacion.getFechaRespuesta(), 
			$autorizacion.getEstado(), 
			$autorizacion.getParametros(), 
			$autorizacion.getTipo(), 
			$autorizacion.getIdAutorizacion(),  ] ;
		console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				Autorizacion._callback_stack.pop().call(null, $autorizacion);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Autorizacion suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( Autorizacion.getByPK(this.getIdAutorizacion()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM autorizacion WHERE  id_autorizacion = ?;";
		$params = [ this.getIdAutorizacion() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Autorizacion} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Autorizacion}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param Autorizacion [$autorizacion] El objeto de tipo Autorizacion
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $autorizacion , $orderBy , $orden )
	{
		$sql = "SELECT * from autorizacion WHERE ("; 
		$val = [];
		if( (($a = this.getIdAutorizacion()) != null) & ( ($b = $autorizacion.getIdAutorizacion()) != null) ){
				$sql += " id_autorizacion >= ? AND id_autorizacion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_autorizacion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdUsuario()) != null) & ( ($b = $autorizacion.getIdUsuario()) != null) ){
				$sql += " id_usuario >= ? AND id_usuario <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_usuario = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdSucursal()) != null) & ( ($b = $autorizacion.getIdSucursal()) != null) ){
				$sql += " id_sucursal >= ? AND id_sucursal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_sucursal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFechaPeticion()) != null) & ( ($b = $autorizacion.getFechaPeticion()) != null) ){
				$sql += " fecha_peticion >= ? AND fecha_peticion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha_peticion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFechaRespuesta()) != null) & ( ($b = $autorizacion.getFechaRespuesta()) != null) ){
				$sql += " fecha_respuesta >= ? AND fecha_respuesta <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha_respuesta = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getEstado()) != null) & ( ($b = $autorizacion.getEstado()) != null) ){
				$sql += " estado >= ? AND estado <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " estado = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getParametros()) != null) & ( ($b = $autorizacion.getParametros()) != null) ){
				$sql += " parametros >= ? AND parametros <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " parametros = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTipo()) != null) & ( ($b = $autorizacion.getTipo()) != null) ){
				$sql += " tipo >= ? AND tipo <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " tipo = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Autorizacion($foo));
		//}
		return $sql;
	};


}
	Autorizacion._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Autorizacion}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	Autorizacion.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from autorizacion";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new Autorizacion( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link Autorizacion} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Autorizacion} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Autorizacion Un objeto del tipo {@link Autorizacion}. NULL si no hay tal registro.
	  **/
	Autorizacion.getByPK = function(  $id_autorizacion, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM autorizacion WHERE (id_autorizacion = ? ) LIMIT 1;";
		db.query($sql, [ $id_autorizacion] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new Autorizacion(results.rows.item(0)); 
				Autorizacion._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : Autorizacion._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table cliente.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var Cliente = function ( config )
{
 /**
	* id_cliente
	* 
	* identificador del cliente
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_cliente = config === undefined ? '' : config.id_cliente || '',

 /**
	* rfc
	* 
	* rfc del cliente si es que tiene
	* @access private
	* @var varchar(20)
	*/
	_rfc = config === undefined ? '' : config.rfc || '',

 /**
	* razon_social
	* 
	* razon social del cliente
	* @access private
	* @var varchar(100)
	*/
	_razon_social = config === undefined ? '' : config.razon_social || '',

 /**
	* calle
	* 
	* calle del domicilio fiscal del cliente
	* @access private
	* @var varchar(300)
	*/
	_calle = config === undefined ? '' : config.calle || '',

 /**
	* numero_exterior
	* 
	* numero exteriror del domicilio fiscal del cliente
	* @access private
	* @var varchar(20)
	*/
	_numero_exterior = config === undefined ? '' : config.numero_exterior || '',

 /**
	* numero_interior
	* 
	* numero interior del domicilio fiscal del cliente
	* @access private
	* @var varchar(20)
	*/
	_numero_interior = config === undefined ? '' : config.numero_interior || '',

 /**
	* colonia
	* 
	* colonia del domicilio fiscal del cliente
	* @access private
	* @var varchar(50)
	*/
	_colonia = config === undefined ? '' : config.colonia || '',

 /**
	* referencia
	* 
	* referencia del domicilio fiscal del cliente
	* @access private
	* @var varchar(100)
	*/
	_referencia = config === undefined ? '' : config.referencia || '',

 /**
	* localidad
	* 
	* Localidad del domicilio fiscal
	* @access private
	* @var varchar(50)
	*/
	_localidad = config === undefined ? '' : config.localidad || '',

 /**
	* municipio
	* 
	* Municipio de este cliente
	* @access private
	* @var varchar(55)
	*/
	_municipio = config === undefined ? '' : config.municipio || '',

 /**
	* estado
	* 
	* Estado del domicilio fiscal del cliente
	* @access private
	* @var varchar(50)
	*/
	_estado = config === undefined ? '' : config.estado || '',

 /**
	* pais
	* 
	* Pais del domicilio fiscal del cliente
	* @access private
	* @var varchar(50)
	*/
	_pais = config === undefined ? '' : config.pais || '',

 /**
	* codigo_postal
	* 
	* Codigo postal del domicilio fiscal del cliente
	* @access private
	* @var varchar(15)
	*/
	_codigo_postal = config === undefined ? '' : config.codigo_postal || '',

 /**
	* telefono
	* 
	* Telefono del cliete
	* @access private
	* @var varchar(25)
	*/
	_telefono = config === undefined ? '' : config.telefono || '',

 /**
	* e_mail
	* 
	* dias de credito para que pague el cliente
	* @access private
	* @var varchar(60)
	*/
	_e_mail = config === undefined ? '' : config.e_mail || '',

 /**
	* limite_credito
	* 
	* Limite de credito otorgado al cliente
	* @access private
	* @var float
	*/
	_limite_credito = config === undefined ? '' : config.limite_credito || '',

 /**
	* descuento
	* 
	* Taza porcentual de descuento de 0.0 a 100.0
	* @access private
	* @var float
	*/
	_descuento = config === undefined ? '' : config.descuento || '',

 /**
	* activo
	* 
	* Indica si la cuenta esta activada o desactivada
	* @access private
	* @var tinyint(2)
	*/
	_activo = config === undefined ? '' : config.activo || '',

 /**
	* id_usuario
	* 
	* Identificador del usuario que dio de alta a este cliente
	* @access private
	* @var int(11)
	*/
	_id_usuario = config === undefined ? '' : config.id_usuario || '',

 /**
	* id_sucursal
	* 
	* Identificador de la sucursal donde se dio de alta este cliente
	* @access private
	* @var int(11)
	*/
	_id_sucursal = config === undefined ? '' : config.id_sucursal || '',

 /**
	* fecha_ingreso
	* 
	* Fecha cuando este cliente se registro en una sucursal
	* @access private
	* @var timestamp
	*/
	_fecha_ingreso = config === undefined ? '' : config.fecha_ingreso || '',

 /**
	* password
	* 
	* el pass para que este cliente entre a descargar sus facturas
	* @access private
	* @var varchar(64)
	*/
	_password = config === undefined ? '' : config.password || '',

 /**
	* last_login
	* 
	*  [Campo no documentado]
	* @access private
	* @var timestamp
	*/
	_last_login = config === undefined ? '' : config.last_login || '',

 /**
	* grant_changes
	* 
	* verdadero cuando el cliente ha cambiado su contrasena y puede hacer cosas
	* @access private
	* @var tinyint(1)
	*/
	_grant_changes = config === undefined ? '' : config.grant_changes || '';

	/**
	  * getIdCliente
	  * 
	  * Get the <i>id_cliente</i> property for this object. Donde <i>id_cliente</i> es identificador del cliente
	  * @return int(11)
	  */
	this.getIdCliente = function ()
	{
		return _id_cliente;
	};

	/**
	  * setIdCliente( $id_cliente )
	  * 
	  * Set the <i>id_cliente</i> property for this object. Donde <i>id_cliente</i> es identificador del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>id_cliente</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdCliente( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdCliente  = function ( id_cliente )
	{
		_id_cliente = id_cliente;
	};

	/**
	  * getRfc
	  * 
	  * Get the <i>rfc</i> property for this object. Donde <i>rfc</i> es rfc del cliente si es que tiene
	  * @return varchar(20)
	  */
	this.getRfc = function ()
	{
		return _rfc;
	};

	/**
	  * setRfc( $rfc )
	  * 
	  * Set the <i>rfc</i> property for this object. Donde <i>rfc</i> es rfc del cliente si es que tiene.
	  * Una validacion basica se hara aqui para comprobar que <i>rfc</i> es de tipo <i>varchar(20)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(20)
	  */
	this.setRfc  = function ( rfc )
	{
		_rfc = rfc;
	};

	/**
	  * getRazonSocial
	  * 
	  * Get the <i>razon_social</i> property for this object. Donde <i>razon_social</i> es razon social del cliente
	  * @return varchar(100)
	  */
	this.getRazonSocial = function ()
	{
		return _razon_social;
	};

	/**
	  * setRazonSocial( $razon_social )
	  * 
	  * Set the <i>razon_social</i> property for this object. Donde <i>razon_social</i> es razon social del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>razon_social</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	this.setRazonSocial  = function ( razon_social )
	{
		_razon_social = razon_social;
	};

	/**
	  * getCalle
	  * 
	  * Get the <i>calle</i> property for this object. Donde <i>calle</i> es calle del domicilio fiscal del cliente
	  * @return varchar(300)
	  */
	this.getCalle = function ()
	{
		return _calle;
	};

	/**
	  * setCalle( $calle )
	  * 
	  * Set the <i>calle</i> property for this object. Donde <i>calle</i> es calle del domicilio fiscal del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>calle</i> es de tipo <i>varchar(300)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(300)
	  */
	this.setCalle  = function ( calle )
	{
		_calle = calle;
	};

	/**
	  * getNumeroExterior
	  * 
	  * Get the <i>numero_exterior</i> property for this object. Donde <i>numero_exterior</i> es numero exteriror del domicilio fiscal del cliente
	  * @return varchar(20)
	  */
	this.getNumeroExterior = function ()
	{
		return _numero_exterior;
	};

	/**
	  * setNumeroExterior( $numero_exterior )
	  * 
	  * Set the <i>numero_exterior</i> property for this object. Donde <i>numero_exterior</i> es numero exteriror del domicilio fiscal del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>numero_exterior</i> es de tipo <i>varchar(20)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(20)
	  */
	this.setNumeroExterior  = function ( numero_exterior )
	{
		_numero_exterior = numero_exterior;
	};

	/**
	  * getNumeroInterior
	  * 
	  * Get the <i>numero_interior</i> property for this object. Donde <i>numero_interior</i> es numero interior del domicilio fiscal del cliente
	  * @return varchar(20)
	  */
	this.getNumeroInterior = function ()
	{
		return _numero_interior;
	};

	/**
	  * setNumeroInterior( $numero_interior )
	  * 
	  * Set the <i>numero_interior</i> property for this object. Donde <i>numero_interior</i> es numero interior del domicilio fiscal del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>numero_interior</i> es de tipo <i>varchar(20)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(20)
	  */
	this.setNumeroInterior  = function ( numero_interior )
	{
		_numero_interior = numero_interior;
	};

	/**
	  * getColonia
	  * 
	  * Get the <i>colonia</i> property for this object. Donde <i>colonia</i> es colonia del domicilio fiscal del cliente
	  * @return varchar(50)
	  */
	this.getColonia = function ()
	{
		return _colonia;
	};

	/**
	  * setColonia( $colonia )
	  * 
	  * Set the <i>colonia</i> property for this object. Donde <i>colonia</i> es colonia del domicilio fiscal del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>colonia</i> es de tipo <i>varchar(50)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(50)
	  */
	this.setColonia  = function ( colonia )
	{
		_colonia = colonia;
	};

	/**
	  * getReferencia
	  * 
	  * Get the <i>referencia</i> property for this object. Donde <i>referencia</i> es referencia del domicilio fiscal del cliente
	  * @return varchar(100)
	  */
	this.getReferencia = function ()
	{
		return _referencia;
	};

	/**
	  * setReferencia( $referencia )
	  * 
	  * Set the <i>referencia</i> property for this object. Donde <i>referencia</i> es referencia del domicilio fiscal del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>referencia</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	this.setReferencia  = function ( referencia )
	{
		_referencia = referencia;
	};

	/**
	  * getLocalidad
	  * 
	  * Get the <i>localidad</i> property for this object. Donde <i>localidad</i> es Localidad del domicilio fiscal
	  * @return varchar(50)
	  */
	this.getLocalidad = function ()
	{
		return _localidad;
	};

	/**
	  * setLocalidad( $localidad )
	  * 
	  * Set the <i>localidad</i> property for this object. Donde <i>localidad</i> es Localidad del domicilio fiscal.
	  * Una validacion basica se hara aqui para comprobar que <i>localidad</i> es de tipo <i>varchar(50)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(50)
	  */
	this.setLocalidad  = function ( localidad )
	{
		_localidad = localidad;
	};

	/**
	  * getMunicipio
	  * 
	  * Get the <i>municipio</i> property for this object. Donde <i>municipio</i> es Municipio de este cliente
	  * @return varchar(55)
	  */
	this.getMunicipio = function ()
	{
		return _municipio;
	};

	/**
	  * setMunicipio( $municipio )
	  * 
	  * Set the <i>municipio</i> property for this object. Donde <i>municipio</i> es Municipio de este cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>municipio</i> es de tipo <i>varchar(55)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(55)
	  */
	this.setMunicipio  = function ( municipio )
	{
		_municipio = municipio;
	};

	/**
	  * getEstado
	  * 
	  * Get the <i>estado</i> property for this object. Donde <i>estado</i> es Estado del domicilio fiscal del cliente
	  * @return varchar(50)
	  */
	this.getEstado = function ()
	{
		return _estado;
	};

	/**
	  * setEstado( $estado )
	  * 
	  * Set the <i>estado</i> property for this object. Donde <i>estado</i> es Estado del domicilio fiscal del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>estado</i> es de tipo <i>varchar(50)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(50)
	  */
	this.setEstado  = function ( estado )
	{
		_estado = estado;
	};

	/**
	  * getPais
	  * 
	  * Get the <i>pais</i> property for this object. Donde <i>pais</i> es Pais del domicilio fiscal del cliente
	  * @return varchar(50)
	  */
	this.getPais = function ()
	{
		return _pais;
	};

	/**
	  * setPais( $pais )
	  * 
	  * Set the <i>pais</i> property for this object. Donde <i>pais</i> es Pais del domicilio fiscal del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>pais</i> es de tipo <i>varchar(50)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(50)
	  */
	this.setPais  = function ( pais )
	{
		_pais = pais;
	};

	/**
	  * getCodigoPostal
	  * 
	  * Get the <i>codigo_postal</i> property for this object. Donde <i>codigo_postal</i> es Codigo postal del domicilio fiscal del cliente
	  * @return varchar(15)
	  */
	this.getCodigoPostal = function ()
	{
		return _codigo_postal;
	};

	/**
	  * setCodigoPostal( $codigo_postal )
	  * 
	  * Set the <i>codigo_postal</i> property for this object. Donde <i>codigo_postal</i> es Codigo postal del domicilio fiscal del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>codigo_postal</i> es de tipo <i>varchar(15)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(15)
	  */
	this.setCodigoPostal  = function ( codigo_postal )
	{
		_codigo_postal = codigo_postal;
	};

	/**
	  * getTelefono
	  * 
	  * Get the <i>telefono</i> property for this object. Donde <i>telefono</i> es Telefono del cliete
	  * @return varchar(25)
	  */
	this.getTelefono = function ()
	{
		return _telefono;
	};

	/**
	  * setTelefono( $telefono )
	  * 
	  * Set the <i>telefono</i> property for this object. Donde <i>telefono</i> es Telefono del cliete.
	  * Una validacion basica se hara aqui para comprobar que <i>telefono</i> es de tipo <i>varchar(25)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(25)
	  */
	this.setTelefono  = function ( telefono )
	{
		_telefono = telefono;
	};

	/**
	  * getEMail
	  * 
	  * Get the <i>e_mail</i> property for this object. Donde <i>e_mail</i> es dias de credito para que pague el cliente
	  * @return varchar(60)
	  */
	this.getEMail = function ()
	{
		return _e_mail;
	};

	/**
	  * setEMail( $e_mail )
	  * 
	  * Set the <i>e_mail</i> property for this object. Donde <i>e_mail</i> es dias de credito para que pague el cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>e_mail</i> es de tipo <i>varchar(60)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(60)
	  */
	this.setEMail  = function ( e_mail )
	{
		_e_mail = e_mail;
	};

	/**
	  * getLimiteCredito
	  * 
	  * Get the <i>limite_credito</i> property for this object. Donde <i>limite_credito</i> es Limite de credito otorgado al cliente
	  * @return float
	  */
	this.getLimiteCredito = function ()
	{
		return _limite_credito;
	};

	/**
	  * setLimiteCredito( $limite_credito )
	  * 
	  * Set the <i>limite_credito</i> property for this object. Donde <i>limite_credito</i> es Limite de credito otorgado al cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>limite_credito</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setLimiteCredito  = function ( limite_credito )
	{
		_limite_credito = limite_credito;
	};

	/**
	  * getDescuento
	  * 
	  * Get the <i>descuento</i> property for this object. Donde <i>descuento</i> es Taza porcentual de descuento de 0.0 a 100.0
	  * @return float
	  */
	this.getDescuento = function ()
	{
		return _descuento;
	};

	/**
	  * setDescuento( $descuento )
	  * 
	  * Set the <i>descuento</i> property for this object. Donde <i>descuento</i> es Taza porcentual de descuento de 0.0 a 100.0.
	  * Una validacion basica se hara aqui para comprobar que <i>descuento</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setDescuento  = function ( descuento )
	{
		_descuento = descuento;
	};

	/**
	  * getActivo
	  * 
	  * Get the <i>activo</i> property for this object. Donde <i>activo</i> es Indica si la cuenta esta activada o desactivada
	  * @return tinyint(2)
	  */
	this.getActivo = function ()
	{
		return _activo;
	};

	/**
	  * setActivo( $activo )
	  * 
	  * Set the <i>activo</i> property for this object. Donde <i>activo</i> es Indica si la cuenta esta activada o desactivada.
	  * Una validacion basica se hara aqui para comprobar que <i>activo</i> es de tipo <i>tinyint(2)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(2)
	  */
	this.setActivo  = function ( activo )
	{
		_activo = activo;
	};

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Identificador del usuario que dio de alta a este cliente
	  * @return int(11)
	  */
	this.getIdUsuario = function ()
	{
		return _id_usuario;
	};

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Identificador del usuario que dio de alta a este cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdUsuario  = function ( id_usuario )
	{
		_id_usuario = id_usuario;
	};

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Identificador de la sucursal donde se dio de alta este cliente
	  * @return int(11)
	  */
	this.getIdSucursal = function ()
	{
		return _id_sucursal;
	};

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Identificador de la sucursal donde se dio de alta este cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdSucursal  = function ( id_sucursal )
	{
		_id_sucursal = id_sucursal;
	};

	/**
	  * getFechaIngreso
	  * 
	  * Get the <i>fecha_ingreso</i> property for this object. Donde <i>fecha_ingreso</i> es Fecha cuando este cliente se registro en una sucursal
	  * @return timestamp
	  */
	this.getFechaIngreso = function ()
	{
		return _fecha_ingreso;
	};

	/**
	  * setFechaIngreso( $fecha_ingreso )
	  * 
	  * Set the <i>fecha_ingreso</i> property for this object. Donde <i>fecha_ingreso</i> es Fecha cuando este cliente se registro en una sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_ingreso</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFechaIngreso  = function ( fecha_ingreso )
	{
		_fecha_ingreso = fecha_ingreso;
	};

	/**
	  * getPassword
	  * 
	  * Get the <i>password</i> property for this object. Donde <i>password</i> es el pass para que este cliente entre a descargar sus facturas
	  * @return varchar(64)
	  */
	this.getPassword = function ()
	{
		return _password;
	};

	/**
	  * setPassword( $password )
	  * 
	  * Set the <i>password</i> property for this object. Donde <i>password</i> es el pass para que este cliente entre a descargar sus facturas.
	  * Una validacion basica se hara aqui para comprobar que <i>password</i> es de tipo <i>varchar(64)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(64)
	  */
	this.setPassword  = function ( password )
	{
		_password = password;
	};

	/**
	  * getLastLogin
	  * 
	  * Get the <i>last_login</i> property for this object. Donde <i>last_login</i> es  [Campo no documentado]
	  * @return timestamp
	  */
	this.getLastLogin = function ()
	{
		return _last_login;
	};

	/**
	  * setLastLogin( $last_login )
	  * 
	  * Set the <i>last_login</i> property for this object. Donde <i>last_login</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>last_login</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setLastLogin  = function ( last_login )
	{
		_last_login = last_login;
	};

	/**
	  * getGrantChanges
	  * 
	  * Get the <i>grant_changes</i> property for this object. Donde <i>grant_changes</i> es verdadero cuando el cliente ha cambiado su contrasena y puede hacer cosas
	  * @return tinyint(1)
	  */
	this.getGrantChanges = function ()
	{
		return _grant_changes;
	};

	/**
	  * setGrantChanges( $grant_changes )
	  * 
	  * Set the <i>grant_changes</i> property for this object. Donde <i>grant_changes</i> es verdadero cuando el cliente ha cambiado su contrasena y puede hacer cosas.
	  * Una validacion basica se hara aqui para comprobar que <i>grant_changes</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	this.setGrantChanges  = function ( grant_changes )
	{
		_grant_changes = grant_changes;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Cliente} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		Cliente._callback_stack.push( _original_callback  );
		Cliente._callback_stack.push( function(res){ 
						console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create.call(this, this);
						}else{
							update.call(res, res);
						}
	 			});
		Cliente.getByPK(  this.getIdCliente() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Cliente} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param Cliente [$cliente] El objeto de tipo Cliente
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from cliente WHERE ("; 
		$val = [];
		if( this.getIdCliente() != null){
			$sql += " id_cliente = ? AND";
			$val.push( this.getIdCliente() );
		}

		if( this.getRfc() != null){
			$sql += " rfc = ? AND";
			$val.push( this.getRfc() );
		}

		if( this.getRazonSocial() != null){
			$sql += " razon_social = ? AND";
			$val.push( this.getRazonSocial() );
		}

		if( this.getCalle() != null){
			$sql += " calle = ? AND";
			$val.push( this.getCalle() );
		}

		if( this.getNumeroExterior() != null){
			$sql += " numero_exterior = ? AND";
			$val.push( this.getNumeroExterior() );
		}

		if( this.getNumeroInterior() != null){
			$sql += " numero_interior = ? AND";
			$val.push( this.getNumeroInterior() );
		}

		if( this.getColonia() != null){
			$sql += " colonia = ? AND";
			$val.push( this.getColonia() );
		}

		if( this.getReferencia() != null){
			$sql += " referencia = ? AND";
			$val.push( this.getReferencia() );
		}

		if( this.getLocalidad() != null){
			$sql += " localidad = ? AND";
			$val.push( this.getLocalidad() );
		}

		if( this.getMunicipio() != null){
			$sql += " municipio = ? AND";
			$val.push( this.getMunicipio() );
		}

		if( this.getEstado() != null){
			$sql += " estado = ? AND";
			$val.push( this.getEstado() );
		}

		if( this.getPais() != null){
			$sql += " pais = ? AND";
			$val.push( this.getPais() );
		}

		if( this.getCodigoPostal() != null){
			$sql += " codigo_postal = ? AND";
			$val.push( this.getCodigoPostal() );
		}

		if( this.getTelefono() != null){
			$sql += " telefono = ? AND";
			$val.push( this.getTelefono() );
		}

		if( this.getEMail() != null){
			$sql += " e_mail = ? AND";
			$val.push( this.getEMail() );
		}

		if( this.getLimiteCredito() != null){
			$sql += " limite_credito = ? AND";
			$val.push( this.getLimiteCredito() );
		}

		if( this.getDescuento() != null){
			$sql += " descuento = ? AND";
			$val.push( this.getDescuento() );
		}

		if( this.getActivo() != null){
			$sql += " activo = ? AND";
			$val.push( this.getActivo() );
		}

		if( this.getIdUsuario() != null){
			$sql += " id_usuario = ? AND";
			$val.push( this.getIdUsuario() );
		}

		if( this.getIdSucursal() != null){
			$sql += " id_sucursal = ? AND";
			$val.push( this.getIdSucursal() );
		}

		if( this.getFechaIngreso() != null){
			$sql += " fecha_ingreso = ? AND";
			$val.push( this.getFechaIngreso() );
		}

		if( this.getPassword() != null){
			$sql += " password = ? AND";
			$val.push( this.getPassword() );
		}

		if( this.getLastLogin() != null){
			$sql += " last_login = ? AND";
			$val.push( this.getLastLogin() );
		}

		if( this.getGrantChanges() != null){
			$sql += " grant_changes = ? AND";
			$val.push( this.getGrantChanges() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Cliente($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Cliente suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Cliente dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Cliente [$cliente] El objeto de tipo Cliente a crear.
	  **/
	var create = function( cliente )
	{
		console.log('estoy en create(this)');
		$sql = "INSERT INTO cliente ( id_cliente, rfc, razon_social, calle, numero_exterior, numero_interior, colonia, referencia, localidad, municipio, estado, pais, codigo_postal, telefono, e_mail, limite_credito, descuento, activo, id_usuario, id_sucursal, fecha_ingreso, password, last_login, grant_changes ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			cliente.getIdCliente(), 
			cliente.getRfc(), 
			cliente.getRazonSocial(), 
			cliente.getCalle(), 
			cliente.getNumeroExterior(), 
			cliente.getNumeroInterior(), 
			cliente.getColonia(), 
			cliente.getReferencia(), 
			cliente.getLocalidad(), 
			cliente.getMunicipio(), 
			cliente.getEstado(), 
			cliente.getPais(), 
			cliente.getCodigoPostal(), 
			cliente.getTelefono(), 
			cliente.getEMail(), 
			cliente.getLimiteCredito(), 
			cliente.getDescuento(), 
			cliente.getActivo(), 
			cliente.getIdUsuario(), 
			cliente.getIdSucursal(), 
			cliente.getFechaIngreso(), 
			cliente.getPassword(), 
			cliente.getLastLogin(), 
			cliente.getGrantChanges(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				Cliente._callback_stack.pop().call(null, cliente);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param Cliente [$cliente] El objeto de tipo Cliente a actualizar.
	  **/
	var update = function( $cliente )
	{
		$sql = "UPDATE cliente SET  rfc = ?, razon_social = ?, calle = ?, numero_exterior = ?, numero_interior = ?, colonia = ?, referencia = ?, localidad = ?, municipio = ?, estado = ?, pais = ?, codigo_postal = ?, telefono = ?, e_mail = ?, limite_credito = ?, descuento = ?, activo = ?, id_usuario = ?, id_sucursal = ?, fecha_ingreso = ?, password = ?, last_login = ?, grant_changes = ? WHERE  id_cliente = ?;";
		$params = [ 
			$cliente.getRfc(), 
			$cliente.getRazonSocial(), 
			$cliente.getCalle(), 
			$cliente.getNumeroExterior(), 
			$cliente.getNumeroInterior(), 
			$cliente.getColonia(), 
			$cliente.getReferencia(), 
			$cliente.getLocalidad(), 
			$cliente.getMunicipio(), 
			$cliente.getEstado(), 
			$cliente.getPais(), 
			$cliente.getCodigoPostal(), 
			$cliente.getTelefono(), 
			$cliente.getEMail(), 
			$cliente.getLimiteCredito(), 
			$cliente.getDescuento(), 
			$cliente.getActivo(), 
			$cliente.getIdUsuario(), 
			$cliente.getIdSucursal(), 
			$cliente.getFechaIngreso(), 
			$cliente.getPassword(), 
			$cliente.getLastLogin(), 
			$cliente.getGrantChanges(), 
			$cliente.getIdCliente(),  ] ;
		console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				Cliente._callback_stack.pop().call(null, $cliente);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Cliente suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( Cliente.getByPK(this.getIdCliente()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM cliente WHERE  id_cliente = ?;";
		$params = [ this.getIdCliente() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Cliente} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Cliente}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param Cliente [$cliente] El objeto de tipo Cliente
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $cliente , $orderBy , $orden )
	{
		$sql = "SELECT * from cliente WHERE ("; 
		$val = [];
		if( (($a = this.getIdCliente()) != null) & ( ($b = $cliente.getIdCliente()) != null) ){
				$sql += " id_cliente >= ? AND id_cliente <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_cliente = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getRfc()) != null) & ( ($b = $cliente.getRfc()) != null) ){
				$sql += " rfc >= ? AND rfc <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " rfc = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getRazonSocial()) != null) & ( ($b = $cliente.getRazonSocial()) != null) ){
				$sql += " razon_social >= ? AND razon_social <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " razon_social = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getCalle()) != null) & ( ($b = $cliente.getCalle()) != null) ){
				$sql += " calle >= ? AND calle <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " calle = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getNumeroExterior()) != null) & ( ($b = $cliente.getNumeroExterior()) != null) ){
				$sql += " numero_exterior >= ? AND numero_exterior <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " numero_exterior = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getNumeroInterior()) != null) & ( ($b = $cliente.getNumeroInterior()) != null) ){
				$sql += " numero_interior >= ? AND numero_interior <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " numero_interior = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getColonia()) != null) & ( ($b = $cliente.getColonia()) != null) ){
				$sql += " colonia >= ? AND colonia <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " colonia = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getReferencia()) != null) & ( ($b = $cliente.getReferencia()) != null) ){
				$sql += " referencia >= ? AND referencia <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " referencia = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getLocalidad()) != null) & ( ($b = $cliente.getLocalidad()) != null) ){
				$sql += " localidad >= ? AND localidad <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " localidad = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getMunicipio()) != null) & ( ($b = $cliente.getMunicipio()) != null) ){
				$sql += " municipio >= ? AND municipio <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " municipio = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getEstado()) != null) & ( ($b = $cliente.getEstado()) != null) ){
				$sql += " estado >= ? AND estado <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " estado = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPais()) != null) & ( ($b = $cliente.getPais()) != null) ){
				$sql += " pais >= ? AND pais <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " pais = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getCodigoPostal()) != null) & ( ($b = $cliente.getCodigoPostal()) != null) ){
				$sql += " codigo_postal >= ? AND codigo_postal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " codigo_postal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTelefono()) != null) & ( ($b = $cliente.getTelefono()) != null) ){
				$sql += " telefono >= ? AND telefono <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " telefono = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getEMail()) != null) & ( ($b = $cliente.getEMail()) != null) ){
				$sql += " e_mail >= ? AND e_mail <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " e_mail = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getLimiteCredito()) != null) & ( ($b = $cliente.getLimiteCredito()) != null) ){
				$sql += " limite_credito >= ? AND limite_credito <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " limite_credito = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getDescuento()) != null) & ( ($b = $cliente.getDescuento()) != null) ){
				$sql += " descuento >= ? AND descuento <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " descuento = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getActivo()) != null) & ( ($b = $cliente.getActivo()) != null) ){
				$sql += " activo >= ? AND activo <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " activo = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdUsuario()) != null) & ( ($b = $cliente.getIdUsuario()) != null) ){
				$sql += " id_usuario >= ? AND id_usuario <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_usuario = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdSucursal()) != null) & ( ($b = $cliente.getIdSucursal()) != null) ){
				$sql += " id_sucursal >= ? AND id_sucursal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_sucursal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFechaIngreso()) != null) & ( ($b = $cliente.getFechaIngreso()) != null) ){
				$sql += " fecha_ingreso >= ? AND fecha_ingreso <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha_ingreso = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPassword()) != null) & ( ($b = $cliente.getPassword()) != null) ){
				$sql += " password >= ? AND password <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " password = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getLastLogin()) != null) & ( ($b = $cliente.getLastLogin()) != null) ){
				$sql += " last_login >= ? AND last_login <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " last_login = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getGrantChanges()) != null) & ( ($b = $cliente.getGrantChanges()) != null) ){
				$sql += " grant_changes >= ? AND grant_changes <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " grant_changes = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Cliente($foo));
		//}
		return $sql;
	};


}
	Cliente._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Cliente}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	Cliente.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from cliente";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new Cliente( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link Cliente} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Cliente} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Cliente Un objeto del tipo {@link Cliente}. NULL si no hay tal registro.
	  **/
	Cliente.getByPK = function(  $id_cliente, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM cliente WHERE (id_cliente = ? ) LIMIT 1;";
		db.query($sql, [ $id_cliente] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new Cliente(results.rows.item(0)); 
				Cliente._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : Cliente._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table compra_cliente.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var CompraCliente = function ( config )
{
 /**
	* id_compra
	* 
	* id de compra
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_compra = config === undefined ? '' : config.id_compra || '',

 /**
	* id_cliente
	* 
	* cliente al que se le compro
	* @access private
	* @var int(11)
	*/
	_id_cliente = config === undefined ? '' : config.id_cliente || '',

 /**
	* tipo_compra
	* 
	* tipo de compra, contado o credito
	* @access private
	* @var enum('credito','contado')
	*/
	_tipo_compra = config === undefined ? '' : config.tipo_compra || '',

 /**
	* tipo_pago
	* 
	* tipo de pago para esta compra en caso de ser a contado
	* @access private
	* @var enum('efectivo','cheque','tarjeta')
	*/
	_tipo_pago = config === undefined ? '' : config.tipo_pago || '',

 /**
	* fecha
	* 
	* fecha de compra
	* @access private
	* @var timestamp
	*/
	_fecha = config === undefined ? '' : config.fecha || '',

 /**
	* subtotal
	* 
	* subtotal de la compra, puede ser nulo
	* @access private
	* @var float
	*/
	_subtotal = config === undefined ? '' : config.subtotal || '',

 /**
	* impuesto
	* 
	* impuesto agregado por la compra, depende de cada sucursal
	* @access private
	* @var float
	*/
	_impuesto = config === undefined ? '' : config.impuesto || '',

 /**
	* descuento
	* 
	* descuento aplicado a esta compra
	* @access private
	* @var float
	*/
	_descuento = config === undefined ? '' : config.descuento || '',

 /**
	* total
	* 
	* total de esta compra
	* @access private
	* @var float
	*/
	_total = config === undefined ? '' : config.total || '',

 /**
	* id_sucursal
	* 
	* sucursal de la compra
	* @access private
	* @var int(11)
	*/
	_id_sucursal = config === undefined ? '' : config.id_sucursal || '',

 /**
	* id_usuario
	* 
	* empleado que lo vendio
	* @access private
	* @var int(11)
	*/
	_id_usuario = config === undefined ? '' : config.id_usuario || '',

 /**
	* pagado
	* 
	* porcentaje pagado de esta compra
	* @access private
	* @var float
	*/
	_pagado = config === undefined ? '' : config.pagado || '',

 /**
	* cancelada
	* 
	* verdadero si esta compra ha sido cancelada, falso si no
	* @access private
	* @var tinyint(1)
	*/
	_cancelada = config === undefined ? '' : config.cancelada || '',

 /**
	* ip
	* 
	* ip de donde provino esta compra
	* @access private
	* @var varchar(16)
	*/
	_ip = config === undefined ? '' : config.ip || '',

 /**
	* liquidada
	* 
	* Verdadero si esta compra ha sido liquidada, falso si hay un saldo pendiente
	* @access private
	* @var tinyint(1)
	*/
	_liquidada = config === undefined ? '' : config.liquidada || '';

	/**
	  * getIdCompra
	  * 
	  * Get the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es id de compra
	  * @return int(11)
	  */
	this.getIdCompra = function ()
	{
		return _id_compra;
	};

	/**
	  * setIdCompra( $id_compra )
	  * 
	  * Set the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es id de compra.
	  * Una validacion basica se hara aqui para comprobar que <i>id_compra</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdCompra( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdCompra  = function ( id_compra )
	{
		_id_compra = id_compra;
	};

	/**
	  * getIdCliente
	  * 
	  * Get the <i>id_cliente</i> property for this object. Donde <i>id_cliente</i> es cliente al que se le compro
	  * @return int(11)
	  */
	this.getIdCliente = function ()
	{
		return _id_cliente;
	};

	/**
	  * setIdCliente( $id_cliente )
	  * 
	  * Set the <i>id_cliente</i> property for this object. Donde <i>id_cliente</i> es cliente al que se le compro.
	  * Una validacion basica se hara aqui para comprobar que <i>id_cliente</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdCliente  = function ( id_cliente )
	{
		_id_cliente = id_cliente;
	};

	/**
	  * getTipoCompra
	  * 
	  * Get the <i>tipo_compra</i> property for this object. Donde <i>tipo_compra</i> es tipo de compra, contado o credito
	  * @return enum('credito','contado')
	  */
	this.getTipoCompra = function ()
	{
		return _tipo_compra;
	};

	/**
	  * setTipoCompra( $tipo_compra )
	  * 
	  * Set the <i>tipo_compra</i> property for this object. Donde <i>tipo_compra</i> es tipo de compra, contado o credito.
	  * Una validacion basica se hara aqui para comprobar que <i>tipo_compra</i> es de tipo <i>enum('credito','contado')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('credito','contado')
	  */
	this.setTipoCompra  = function ( tipo_compra )
	{
		_tipo_compra = tipo_compra;
	};

	/**
	  * getTipoPago
	  * 
	  * Get the <i>tipo_pago</i> property for this object. Donde <i>tipo_pago</i> es tipo de pago para esta compra en caso de ser a contado
	  * @return enum('efectivo','cheque','tarjeta')
	  */
	this.getTipoPago = function ()
	{
		return _tipo_pago;
	};

	/**
	  * setTipoPago( $tipo_pago )
	  * 
	  * Set the <i>tipo_pago</i> property for this object. Donde <i>tipo_pago</i> es tipo de pago para esta compra en caso de ser a contado.
	  * Una validacion basica se hara aqui para comprobar que <i>tipo_pago</i> es de tipo <i>enum('efectivo','cheque','tarjeta')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('efectivo','cheque','tarjeta')
	  */
	this.setTipoPago  = function ( tipo_pago )
	{
		_tipo_pago = tipo_pago;
	};

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha de compra
	  * @return timestamp
	  */
	this.getFecha = function ()
	{
		return _fecha;
	};

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha de compra.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFecha  = function ( fecha )
	{
		_fecha = fecha;
	};

	/**
	  * getSubtotal
	  * 
	  * Get the <i>subtotal</i> property for this object. Donde <i>subtotal</i> es subtotal de la compra, puede ser nulo
	  * @return float
	  */
	this.getSubtotal = function ()
	{
		return _subtotal;
	};

	/**
	  * setSubtotal( $subtotal )
	  * 
	  * Set the <i>subtotal</i> property for this object. Donde <i>subtotal</i> es subtotal de la compra, puede ser nulo.
	  * Una validacion basica se hara aqui para comprobar que <i>subtotal</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setSubtotal  = function ( subtotal )
	{
		_subtotal = subtotal;
	};

	/**
	  * getImpuesto
	  * 
	  * Get the <i>impuesto</i> property for this object. Donde <i>impuesto</i> es impuesto agregado por la compra, depende de cada sucursal
	  * @return float
	  */
	this.getImpuesto = function ()
	{
		return _impuesto;
	};

	/**
	  * setImpuesto( $impuesto )
	  * 
	  * Set the <i>impuesto</i> property for this object. Donde <i>impuesto</i> es impuesto agregado por la compra, depende de cada sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>impuesto</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setImpuesto  = function ( impuesto )
	{
		_impuesto = impuesto;
	};

	/**
	  * getDescuento
	  * 
	  * Get the <i>descuento</i> property for this object. Donde <i>descuento</i> es descuento aplicado a esta compra
	  * @return float
	  */
	this.getDescuento = function ()
	{
		return _descuento;
	};

	/**
	  * setDescuento( $descuento )
	  * 
	  * Set the <i>descuento</i> property for this object. Donde <i>descuento</i> es descuento aplicado a esta compra.
	  * Una validacion basica se hara aqui para comprobar que <i>descuento</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setDescuento  = function ( descuento )
	{
		_descuento = descuento;
	};

	/**
	  * getTotal
	  * 
	  * Get the <i>total</i> property for this object. Donde <i>total</i> es total de esta compra
	  * @return float
	  */
	this.getTotal = function ()
	{
		return _total;
	};

	/**
	  * setTotal( $total )
	  * 
	  * Set the <i>total</i> property for this object. Donde <i>total</i> es total de esta compra.
	  * Una validacion basica se hara aqui para comprobar que <i>total</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setTotal  = function ( total )
	{
		_total = total;
	};

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal de la compra
	  * @return int(11)
	  */
	this.getIdSucursal = function ()
	{
		return _id_sucursal;
	};

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal de la compra.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdSucursal  = function ( id_sucursal )
	{
		_id_sucursal = id_sucursal;
	};

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es empleado que lo vendio
	  * @return int(11)
	  */
	this.getIdUsuario = function ()
	{
		return _id_usuario;
	};

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es empleado que lo vendio.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdUsuario  = function ( id_usuario )
	{
		_id_usuario = id_usuario;
	};

	/**
	  * getPagado
	  * 
	  * Get the <i>pagado</i> property for this object. Donde <i>pagado</i> es porcentaje pagado de esta compra
	  * @return float
	  */
	this.getPagado = function ()
	{
		return _pagado;
	};

	/**
	  * setPagado( $pagado )
	  * 
	  * Set the <i>pagado</i> property for this object. Donde <i>pagado</i> es porcentaje pagado de esta compra.
	  * Una validacion basica se hara aqui para comprobar que <i>pagado</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPagado  = function ( pagado )
	{
		_pagado = pagado;
	};

	/**
	  * getCancelada
	  * 
	  * Get the <i>cancelada</i> property for this object. Donde <i>cancelada</i> es verdadero si esta compra ha sido cancelada, falso si no
	  * @return tinyint(1)
	  */
	this.getCancelada = function ()
	{
		return _cancelada;
	};

	/**
	  * setCancelada( $cancelada )
	  * 
	  * Set the <i>cancelada</i> property for this object. Donde <i>cancelada</i> es verdadero si esta compra ha sido cancelada, falso si no.
	  * Una validacion basica se hara aqui para comprobar que <i>cancelada</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	this.setCancelada  = function ( cancelada )
	{
		_cancelada = cancelada;
	};

	/**
	  * getIp
	  * 
	  * Get the <i>ip</i> property for this object. Donde <i>ip</i> es ip de donde provino esta compra
	  * @return varchar(16)
	  */
	this.getIp = function ()
	{
		return _ip;
	};

	/**
	  * setIp( $ip )
	  * 
	  * Set the <i>ip</i> property for this object. Donde <i>ip</i> es ip de donde provino esta compra.
	  * Una validacion basica se hara aqui para comprobar que <i>ip</i> es de tipo <i>varchar(16)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(16)
	  */
	this.setIp  = function ( ip )
	{
		_ip = ip;
	};

	/**
	  * getLiquidada
	  * 
	  * Get the <i>liquidada</i> property for this object. Donde <i>liquidada</i> es Verdadero si esta compra ha sido liquidada, falso si hay un saldo pendiente
	  * @return tinyint(1)
	  */
	this.getLiquidada = function ()
	{
		return _liquidada;
	};

	/**
	  * setLiquidada( $liquidada )
	  * 
	  * Set the <i>liquidada</i> property for this object. Donde <i>liquidada</i> es Verdadero si esta compra ha sido liquidada, falso si hay un saldo pendiente.
	  * Una validacion basica se hara aqui para comprobar que <i>liquidada</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	this.setLiquidada  = function ( liquidada )
	{
		_liquidada = liquidada;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link CompraCliente} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		CompraCliente._callback_stack.push( _original_callback  );
		CompraCliente._callback_stack.push( function(res){ 
						console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create.call(this, this);
						}else{
							update.call(res, res);
						}
	 			});
		CompraCliente.getByPK(  this.getIdCompra() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link CompraCliente} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param CompraCliente [$compra_cliente] El objeto de tipo CompraCliente
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from compra_cliente WHERE ("; 
		$val = [];
		if( this.getIdCompra() != null){
			$sql += " id_compra = ? AND";
			$val.push( this.getIdCompra() );
		}

		if( this.getIdCliente() != null){
			$sql += " id_cliente = ? AND";
			$val.push( this.getIdCliente() );
		}

		if( this.getTipoCompra() != null){
			$sql += " tipo_compra = ? AND";
			$val.push( this.getTipoCompra() );
		}

		if( this.getTipoPago() != null){
			$sql += " tipo_pago = ? AND";
			$val.push( this.getTipoPago() );
		}

		if( this.getFecha() != null){
			$sql += " fecha = ? AND";
			$val.push( this.getFecha() );
		}

		if( this.getSubtotal() != null){
			$sql += " subtotal = ? AND";
			$val.push( this.getSubtotal() );
		}

		if( this.getImpuesto() != null){
			$sql += " impuesto = ? AND";
			$val.push( this.getImpuesto() );
		}

		if( this.getDescuento() != null){
			$sql += " descuento = ? AND";
			$val.push( this.getDescuento() );
		}

		if( this.getTotal() != null){
			$sql += " total = ? AND";
			$val.push( this.getTotal() );
		}

		if( this.getIdSucursal() != null){
			$sql += " id_sucursal = ? AND";
			$val.push( this.getIdSucursal() );
		}

		if( this.getIdUsuario() != null){
			$sql += " id_usuario = ? AND";
			$val.push( this.getIdUsuario() );
		}

		if( this.getPagado() != null){
			$sql += " pagado = ? AND";
			$val.push( this.getPagado() );
		}

		if( this.getCancelada() != null){
			$sql += " cancelada = ? AND";
			$val.push( this.getCancelada() );
		}

		if( this.getIp() != null){
			$sql += " ip = ? AND";
			$val.push( this.getIp() );
		}

		if( this.getLiquidada() != null){
			$sql += " liquidada = ? AND";
			$val.push( this.getLiquidada() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new CompraCliente($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto CompraCliente suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto CompraCliente dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param CompraCliente [$compra_cliente] El objeto de tipo CompraCliente a crear.
	  **/
	var create = function( compra_cliente )
	{
		console.log('estoy en create(this)');
		$sql = "INSERT INTO compra_cliente ( id_compra, id_cliente, tipo_compra, tipo_pago, fecha, subtotal, impuesto, descuento, total, id_sucursal, id_usuario, pagado, cancelada, ip, liquidada ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			compra_cliente.getIdCompra(), 
			compra_cliente.getIdCliente(), 
			compra_cliente.getTipoCompra(), 
			compra_cliente.getTipoPago(), 
			compra_cliente.getFecha(), 
			compra_cliente.getSubtotal(), 
			compra_cliente.getImpuesto(), 
			compra_cliente.getDescuento(), 
			compra_cliente.getTotal(), 
			compra_cliente.getIdSucursal(), 
			compra_cliente.getIdUsuario(), 
			compra_cliente.getPagado(), 
			compra_cliente.getCancelada(), 
			compra_cliente.getIp(), 
			compra_cliente.getLiquidada(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				CompraCliente._callback_stack.pop().call(null, compra_cliente);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param CompraCliente [$compra_cliente] El objeto de tipo CompraCliente a actualizar.
	  **/
	var update = function( $compra_cliente )
	{
		$sql = "UPDATE compra_cliente SET  id_cliente = ?, tipo_compra = ?, tipo_pago = ?, fecha = ?, subtotal = ?, impuesto = ?, descuento = ?, total = ?, id_sucursal = ?, id_usuario = ?, pagado = ?, cancelada = ?, ip = ?, liquidada = ? WHERE  id_compra = ?;";
		$params = [ 
			$compra_cliente.getIdCliente(), 
			$compra_cliente.getTipoCompra(), 
			$compra_cliente.getTipoPago(), 
			$compra_cliente.getFecha(), 
			$compra_cliente.getSubtotal(), 
			$compra_cliente.getImpuesto(), 
			$compra_cliente.getDescuento(), 
			$compra_cliente.getTotal(), 
			$compra_cliente.getIdSucursal(), 
			$compra_cliente.getIdUsuario(), 
			$compra_cliente.getPagado(), 
			$compra_cliente.getCancelada(), 
			$compra_cliente.getIp(), 
			$compra_cliente.getLiquidada(), 
			$compra_cliente.getIdCompra(),  ] ;
		console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				CompraCliente._callback_stack.pop().call(null, $compra_cliente);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto CompraCliente suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( CompraCliente.getByPK(this.getIdCompra()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM compra_cliente WHERE  id_compra = ?;";
		$params = [ this.getIdCompra() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link CompraCliente} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link CompraCliente}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param CompraCliente [$compra_cliente] El objeto de tipo CompraCliente
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $compra_cliente , $orderBy , $orden )
	{
		$sql = "SELECT * from compra_cliente WHERE ("; 
		$val = [];
		if( (($a = this.getIdCompra()) != null) & ( ($b = $compra_cliente.getIdCompra()) != null) ){
				$sql += " id_compra >= ? AND id_compra <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_compra = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdCliente()) != null) & ( ($b = $compra_cliente.getIdCliente()) != null) ){
				$sql += " id_cliente >= ? AND id_cliente <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_cliente = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTipoCompra()) != null) & ( ($b = $compra_cliente.getTipoCompra()) != null) ){
				$sql += " tipo_compra >= ? AND tipo_compra <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " tipo_compra = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTipoPago()) != null) & ( ($b = $compra_cliente.getTipoPago()) != null) ){
				$sql += " tipo_pago >= ? AND tipo_pago <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " tipo_pago = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFecha()) != null) & ( ($b = $compra_cliente.getFecha()) != null) ){
				$sql += " fecha >= ? AND fecha <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getSubtotal()) != null) & ( ($b = $compra_cliente.getSubtotal()) != null) ){
				$sql += " subtotal >= ? AND subtotal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " subtotal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getImpuesto()) != null) & ( ($b = $compra_cliente.getImpuesto()) != null) ){
				$sql += " impuesto >= ? AND impuesto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " impuesto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getDescuento()) != null) & ( ($b = $compra_cliente.getDescuento()) != null) ){
				$sql += " descuento >= ? AND descuento <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " descuento = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTotal()) != null) & ( ($b = $compra_cliente.getTotal()) != null) ){
				$sql += " total >= ? AND total <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " total = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdSucursal()) != null) & ( ($b = $compra_cliente.getIdSucursal()) != null) ){
				$sql += " id_sucursal >= ? AND id_sucursal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_sucursal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdUsuario()) != null) & ( ($b = $compra_cliente.getIdUsuario()) != null) ){
				$sql += " id_usuario >= ? AND id_usuario <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_usuario = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPagado()) != null) & ( ($b = $compra_cliente.getPagado()) != null) ){
				$sql += " pagado >= ? AND pagado <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " pagado = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getCancelada()) != null) & ( ($b = $compra_cliente.getCancelada()) != null) ){
				$sql += " cancelada >= ? AND cancelada <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " cancelada = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIp()) != null) & ( ($b = $compra_cliente.getIp()) != null) ){
				$sql += " ip >= ? AND ip <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " ip = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getLiquidada()) != null) & ( ($b = $compra_cliente.getLiquidada()) != null) ){
				$sql += " liquidada >= ? AND liquidada <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " liquidada = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new CompraCliente($foo));
		//}
		return $sql;
	};


}
	CompraCliente._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link CompraCliente}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	CompraCliente.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from compra_cliente";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new CompraCliente( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link CompraCliente} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link CompraCliente} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link CompraCliente Un objeto del tipo {@link CompraCliente}. NULL si no hay tal registro.
	  **/
	CompraCliente.getByPK = function(  $id_compra, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM compra_cliente WHERE (id_compra = ? ) LIMIT 1;";
		db.query($sql, [ $id_compra] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new CompraCliente(results.rows.item(0)); 
				CompraCliente._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : CompraCliente._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table detalle_compra_cliente.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var DetalleCompraCliente = function ( config )
{
 /**
	* id_compra
	* 
	* compra a que se referencia
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_compra = config === undefined ? '' : config.id_compra || '',

 /**
	* id_producto
	* 
	* producto de la compra
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	_id_producto = config === undefined ? '' : config.id_producto || '',

 /**
	* cantidad
	* 
	* cantidad que se compro
	* @access private
	* @var float
	*/
	_cantidad = config === undefined ? '' : config.cantidad || '',

 /**
	* precio
	* 
	* precio al que se compro
	* @access private
	* @var float
	*/
	_precio = config === undefined ? '' : config.precio || '',

 /**
	* descuento
	* 
	* indica cuanto producto original se va a descontar de ese producto en esa compra
	* @access private
	* @var float
	*/
	_descuento = config === undefined ? '' : config.descuento || '';

	/**
	  * getIdCompra
	  * 
	  * Get the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es compra a que se referencia
	  * @return int(11)
	  */
	this.getIdCompra = function ()
	{
		return _id_compra;
	};

	/**
	  * setIdCompra( $id_compra )
	  * 
	  * Set the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es compra a que se referencia.
	  * Una validacion basica se hara aqui para comprobar que <i>id_compra</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdCompra( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdCompra  = function ( id_compra )
	{
		_id_compra = id_compra;
	};

	/**
	  * getIdProducto
	  * 
	  * Get the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es producto de la compra
	  * @return int(11)
	  */
	this.getIdProducto = function ()
	{
		return _id_producto;
	};

	/**
	  * setIdProducto( $id_producto )
	  * 
	  * Set the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es producto de la compra.
	  * Una validacion basica se hara aqui para comprobar que <i>id_producto</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdProducto( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdProducto  = function ( id_producto )
	{
		_id_producto = id_producto;
	};

	/**
	  * getCantidad
	  * 
	  * Get the <i>cantidad</i> property for this object. Donde <i>cantidad</i> es cantidad que se compro
	  * @return float
	  */
	this.getCantidad = function ()
	{
		return _cantidad;
	};

	/**
	  * setCantidad( $cantidad )
	  * 
	  * Set the <i>cantidad</i> property for this object. Donde <i>cantidad</i> es cantidad que se compro.
	  * Una validacion basica se hara aqui para comprobar que <i>cantidad</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setCantidad  = function ( cantidad )
	{
		_cantidad = cantidad;
	};

	/**
	  * getPrecio
	  * 
	  * Get the <i>precio</i> property for this object. Donde <i>precio</i> es precio al que se compro
	  * @return float
	  */
	this.getPrecio = function ()
	{
		return _precio;
	};

	/**
	  * setPrecio( $precio )
	  * 
	  * Set the <i>precio</i> property for this object. Donde <i>precio</i> es precio al que se compro.
	  * Una validacion basica se hara aqui para comprobar que <i>precio</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPrecio  = function ( precio )
	{
		_precio = precio;
	};

	/**
	  * getDescuento
	  * 
	  * Get the <i>descuento</i> property for this object. Donde <i>descuento</i> es indica cuanto producto original se va a descontar de ese producto en esa compra
	  * @return float
	  */
	this.getDescuento = function ()
	{
		return _descuento;
	};

	/**
	  * setDescuento( $descuento )
	  * 
	  * Set the <i>descuento</i> property for this object. Donde <i>descuento</i> es indica cuanto producto original se va a descontar de ese producto en esa compra.
	  * Una validacion basica se hara aqui para comprobar que <i>descuento</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setDescuento  = function ( descuento )
	{
		_descuento = descuento;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link DetalleCompraCliente} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		DetalleCompraCliente._callback_stack.push( _original_callback  );
		DetalleCompraCliente._callback_stack.push( function(res){ 
						console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create.call(this, this);
						}else{
							update.call(res, res);
						}
	 			});
		DetalleCompraCliente.getByPK(  this.getIdCompra() , this.getIdProducto() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleCompraCliente} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param DetalleCompraCliente [$detalle_compra_cliente] El objeto de tipo DetalleCompraCliente
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from detalle_compra_cliente WHERE ("; 
		$val = [];
		if( this.getIdCompra() != null){
			$sql += " id_compra = ? AND";
			$val.push( this.getIdCompra() );
		}

		if( this.getIdProducto() != null){
			$sql += " id_producto = ? AND";
			$val.push( this.getIdProducto() );
		}

		if( this.getCantidad() != null){
			$sql += " cantidad = ? AND";
			$val.push( this.getCantidad() );
		}

		if( this.getPrecio() != null){
			$sql += " precio = ? AND";
			$val.push( this.getPrecio() );
		}

		if( this.getDescuento() != null){
			$sql += " descuento = ? AND";
			$val.push( this.getDescuento() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new DetalleCompraCliente($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto DetalleCompraCliente suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto DetalleCompraCliente dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param DetalleCompraCliente [$detalle_compra_cliente] El objeto de tipo DetalleCompraCliente a crear.
	  **/
	var create = function( detalle_compra_cliente )
	{
		console.log('estoy en create(this)');
		$sql = "INSERT INTO detalle_compra_cliente ( id_compra, id_producto, cantidad, precio, descuento ) VALUES ( ?, ?, ?, ?, ?);";
		$params = [
			detalle_compra_cliente.getIdCompra(), 
			detalle_compra_cliente.getIdProducto(), 
			detalle_compra_cliente.getCantidad(), 
			detalle_compra_cliente.getPrecio(), 
			detalle_compra_cliente.getDescuento(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				DetalleCompraCliente._callback_stack.pop().call(null, detalle_compra_cliente);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param DetalleCompraCliente [$detalle_compra_cliente] El objeto de tipo DetalleCompraCliente a actualizar.
	  **/
	var update = function( $detalle_compra_cliente )
	{
		$sql = "UPDATE detalle_compra_cliente SET  cantidad = ?, precio = ?, descuento = ? WHERE  id_compra = ? AND id_producto = ?;";
		$params = [ 
			$detalle_compra_cliente.getCantidad(), 
			$detalle_compra_cliente.getPrecio(), 
			$detalle_compra_cliente.getDescuento(), 
			$detalle_compra_cliente.getIdCompra(),$detalle_compra_cliente.getIdProducto(),  ] ;
		console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				DetalleCompraCliente._callback_stack.pop().call(null, $detalle_compra_cliente);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto DetalleCompraCliente suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( DetalleCompraCliente.getByPK(this.getIdCompra(), this.getIdProducto()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM detalle_compra_cliente WHERE  id_compra = ? AND id_producto = ?;";
		$params = [ this.getIdCompra(), this.getIdProducto() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleCompraCliente} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link DetalleCompraCliente}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param DetalleCompraCliente [$detalle_compra_cliente] El objeto de tipo DetalleCompraCliente
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $detalle_compra_cliente , $orderBy , $orden )
	{
		$sql = "SELECT * from detalle_compra_cliente WHERE ("; 
		$val = [];
		if( (($a = this.getIdCompra()) != null) & ( ($b = $detalle_compra_cliente.getIdCompra()) != null) ){
				$sql += " id_compra >= ? AND id_compra <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_compra = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdProducto()) != null) & ( ($b = $detalle_compra_cliente.getIdProducto()) != null) ){
				$sql += " id_producto >= ? AND id_producto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_producto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getCantidad()) != null) & ( ($b = $detalle_compra_cliente.getCantidad()) != null) ){
				$sql += " cantidad >= ? AND cantidad <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " cantidad = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPrecio()) != null) & ( ($b = $detalle_compra_cliente.getPrecio()) != null) ){
				$sql += " precio >= ? AND precio <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " precio = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getDescuento()) != null) & ( ($b = $detalle_compra_cliente.getDescuento()) != null) ){
				$sql += " descuento >= ? AND descuento <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " descuento = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new DetalleCompraCliente($foo));
		//}
		return $sql;
	};


}
	DetalleCompraCliente._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link DetalleCompraCliente}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	DetalleCompraCliente.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from detalle_compra_cliente";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new DetalleCompraCliente( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link DetalleCompraCliente} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link DetalleCompraCliente} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link DetalleCompraCliente Un objeto del tipo {@link DetalleCompraCliente}. NULL si no hay tal registro.
	  **/
	DetalleCompraCliente.getByPK = function(  $id_compra, $id_producto, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM detalle_compra_cliente WHERE (id_compra = ? AND id_producto = ? ) LIMIT 1;";
		db.query($sql, [ $id_compra, $id_producto] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new DetalleCompraCliente(results.rows.item(0)); 
				DetalleCompraCliente._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : DetalleCompraCliente._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table detalle_inventario.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var DetalleInventario = function ( config )
{
 /**
	* id_producto
	* 
	* identificador del proudcto en inventario
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_producto = config === undefined ? '' : config.id_producto || '',

 /**
	* id_sucursal
	* 
	* id de la sucursal
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	_id_sucursal = config === undefined ? '' : config.id_sucursal || '',

 /**
	* precio_venta
	* 
	* precio al que se vendera al publico
	* @access private
	* @var float
	*/
	_precio_venta = config === undefined ? '' : config.precio_venta || '',

 /**
	* precio_venta_procesado
	* 
	* cuando este producto tiene tratamiento este sera su precio de venta al estar procesado
	* @access private
	* @var float
	*/
	_precio_venta_procesado = config === undefined ? '' : config.precio_venta_procesado || '',

 /**
	* existencias
	* 
	* cantidad de producto que hay actualmente en almacen de esta sucursal (originales+procesadas)
	* @access private
	* @var float
	*/
	_existencias = config === undefined ? '' : config.existencias || '',

 /**
	* existencias_procesadas
	* 
	* cantidad de producto solamente procesado !
	* @access private
	* @var float
	*/
	_existencias_procesadas = config === undefined ? '' : config.existencias_procesadas || '',

 /**
	* precio_compra
	* 
	* El precio de compra para este producto en esta sucursal, siempre y cuando este punto de venta tenga el modulo POS_COMPRA_A_CLIENTES
	* @access private
	* @var float
	*/
	_precio_compra = config === undefined ? '' : config.precio_compra || '';

	/**
	  * getIdProducto
	  * 
	  * Get the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es identificador del proudcto en inventario
	  * @return int(11)
	  */
	this.getIdProducto = function ()
	{
		return _id_producto;
	};

	/**
	  * setIdProducto( $id_producto )
	  * 
	  * Set the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es identificador del proudcto en inventario.
	  * Una validacion basica se hara aqui para comprobar que <i>id_producto</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdProducto( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdProducto  = function ( id_producto )
	{
		_id_producto = id_producto;
	};

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es id de la sucursal
	  * @return int(11)
	  */
	this.getIdSucursal = function ()
	{
		return _id_sucursal;
	};

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es id de la sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdSucursal( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdSucursal  = function ( id_sucursal )
	{
		_id_sucursal = id_sucursal;
	};

	/**
	  * getPrecioVenta
	  * 
	  * Get the <i>precio_venta</i> property for this object. Donde <i>precio_venta</i> es precio al que se vendera al publico
	  * @return float
	  */
	this.getPrecioVenta = function ()
	{
		return _precio_venta;
	};

	/**
	  * setPrecioVenta( $precio_venta )
	  * 
	  * Set the <i>precio_venta</i> property for this object. Donde <i>precio_venta</i> es precio al que se vendera al publico.
	  * Una validacion basica se hara aqui para comprobar que <i>precio_venta</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPrecioVenta  = function ( precio_venta )
	{
		_precio_venta = precio_venta;
	};

	/**
	  * getPrecioVentaProcesado
	  * 
	  * Get the <i>precio_venta_procesado</i> property for this object. Donde <i>precio_venta_procesado</i> es cuando este producto tiene tratamiento este sera su precio de venta al estar procesado
	  * @return float
	  */
	this.getPrecioVentaProcesado = function ()
	{
		return _precio_venta_procesado;
	};

	/**
	  * setPrecioVentaProcesado( $precio_venta_procesado )
	  * 
	  * Set the <i>precio_venta_procesado</i> property for this object. Donde <i>precio_venta_procesado</i> es cuando este producto tiene tratamiento este sera su precio de venta al estar procesado.
	  * Una validacion basica se hara aqui para comprobar que <i>precio_venta_procesado</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPrecioVentaProcesado  = function ( precio_venta_procesado )
	{
		_precio_venta_procesado = precio_venta_procesado;
	};

	/**
	  * getExistencias
	  * 
	  * Get the <i>existencias</i> property for this object. Donde <i>existencias</i> es cantidad de producto que hay actualmente en almacen de esta sucursal (originales+procesadas)
	  * @return float
	  */
	this.getExistencias = function ()
	{
		return _existencias;
	};

	/**
	  * setExistencias( $existencias )
	  * 
	  * Set the <i>existencias</i> property for this object. Donde <i>existencias</i> es cantidad de producto que hay actualmente en almacen de esta sucursal (originales+procesadas).
	  * Una validacion basica se hara aqui para comprobar que <i>existencias</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setExistencias  = function ( existencias )
	{
		_existencias = existencias;
	};

	/**
	  * getExistenciasProcesadas
	  * 
	  * Get the <i>existencias_procesadas</i> property for this object. Donde <i>existencias_procesadas</i> es cantidad de producto solamente procesado !
	  * @return float
	  */
	this.getExistenciasProcesadas = function ()
	{
		return _existencias_procesadas;
	};

	/**
	  * setExistenciasProcesadas( $existencias_procesadas )
	  * 
	  * Set the <i>existencias_procesadas</i> property for this object. Donde <i>existencias_procesadas</i> es cantidad de producto solamente procesado !.
	  * Una validacion basica se hara aqui para comprobar que <i>existencias_procesadas</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setExistenciasProcesadas  = function ( existencias_procesadas )
	{
		_existencias_procesadas = existencias_procesadas;
	};

	/**
	  * getPrecioCompra
	  * 
	  * Get the <i>precio_compra</i> property for this object. Donde <i>precio_compra</i> es El precio de compra para este producto en esta sucursal, siempre y cuando este punto de venta tenga el modulo POS_COMPRA_A_CLIENTES
	  * @return float
	  */
	this.getPrecioCompra = function ()
	{
		return _precio_compra;
	};

	/**
	  * setPrecioCompra( $precio_compra )
	  * 
	  * Set the <i>precio_compra</i> property for this object. Donde <i>precio_compra</i> es El precio de compra para este producto en esta sucursal, siempre y cuando este punto de venta tenga el modulo POS_COMPRA_A_CLIENTES.
	  * Una validacion basica se hara aqui para comprobar que <i>precio_compra</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPrecioCompra  = function ( precio_compra )
	{
		_precio_compra = precio_compra;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link DetalleInventario} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		DetalleInventario._callback_stack.push( _original_callback  );
		DetalleInventario._callback_stack.push( function(res){ 
						console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create.call(this, this);
						}else{
							update.call(res, res);
						}
	 			});
		DetalleInventario.getByPK(  this.getIdProducto() , this.getIdSucursal() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleInventario} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param DetalleInventario [$detalle_inventario] El objeto de tipo DetalleInventario
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from detalle_inventario WHERE ("; 
		$val = [];
		if( this.getIdProducto() != null){
			$sql += " id_producto = ? AND";
			$val.push( this.getIdProducto() );
		}

		if( this.getIdSucursal() != null){
			$sql += " id_sucursal = ? AND";
			$val.push( this.getIdSucursal() );
		}

		if( this.getPrecioVenta() != null){
			$sql += " precio_venta = ? AND";
			$val.push( this.getPrecioVenta() );
		}

		if( this.getPrecioVentaProcesado() != null){
			$sql += " precio_venta_procesado = ? AND";
			$val.push( this.getPrecioVentaProcesado() );
		}

		if( this.getExistencias() != null){
			$sql += " existencias = ? AND";
			$val.push( this.getExistencias() );
		}

		if( this.getExistenciasProcesadas() != null){
			$sql += " existencias_procesadas = ? AND";
			$val.push( this.getExistenciasProcesadas() );
		}

		if( this.getPrecioCompra() != null){
			$sql += " precio_compra = ? AND";
			$val.push( this.getPrecioCompra() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new DetalleInventario($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto DetalleInventario suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto DetalleInventario dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param DetalleInventario [$detalle_inventario] El objeto de tipo DetalleInventario a crear.
	  **/
	var create = function( detalle_inventario )
	{
		console.log('estoy en create(this)');
		$sql = "INSERT INTO detalle_inventario ( id_producto, id_sucursal, precio_venta, precio_venta_procesado, existencias, existencias_procesadas, precio_compra ) VALUES ( ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			detalle_inventario.getIdProducto(), 
			detalle_inventario.getIdSucursal(), 
			detalle_inventario.getPrecioVenta(), 
			detalle_inventario.getPrecioVentaProcesado(), 
			detalle_inventario.getExistencias(), 
			detalle_inventario.getExistenciasProcesadas(), 
			detalle_inventario.getPrecioCompra(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				DetalleInventario._callback_stack.pop().call(null, detalle_inventario);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param DetalleInventario [$detalle_inventario] El objeto de tipo DetalleInventario a actualizar.
	  **/
	var update = function( $detalle_inventario )
	{
		$sql = "UPDATE detalle_inventario SET  precio_venta = ?, precio_venta_procesado = ?, existencias = ?, existencias_procesadas = ?, precio_compra = ? WHERE  id_producto = ? AND id_sucursal = ?;";
		$params = [ 
			$detalle_inventario.getPrecioVenta(), 
			$detalle_inventario.getPrecioVentaProcesado(), 
			$detalle_inventario.getExistencias(), 
			$detalle_inventario.getExistenciasProcesadas(), 
			$detalle_inventario.getPrecioCompra(), 
			$detalle_inventario.getIdProducto(),$detalle_inventario.getIdSucursal(),  ] ;
		console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				DetalleInventario._callback_stack.pop().call(null, $detalle_inventario);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto DetalleInventario suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( DetalleInventario.getByPK(this.getIdProducto(), this.getIdSucursal()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM detalle_inventario WHERE  id_producto = ? AND id_sucursal = ?;";
		$params = [ this.getIdProducto(), this.getIdSucursal() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleInventario} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link DetalleInventario}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param DetalleInventario [$detalle_inventario] El objeto de tipo DetalleInventario
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $detalle_inventario , $orderBy , $orden )
	{
		$sql = "SELECT * from detalle_inventario WHERE ("; 
		$val = [];
		if( (($a = this.getIdProducto()) != null) & ( ($b = $detalle_inventario.getIdProducto()) != null) ){
				$sql += " id_producto >= ? AND id_producto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_producto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdSucursal()) != null) & ( ($b = $detalle_inventario.getIdSucursal()) != null) ){
				$sql += " id_sucursal >= ? AND id_sucursal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_sucursal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPrecioVenta()) != null) & ( ($b = $detalle_inventario.getPrecioVenta()) != null) ){
				$sql += " precio_venta >= ? AND precio_venta <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " precio_venta = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPrecioVentaProcesado()) != null) & ( ($b = $detalle_inventario.getPrecioVentaProcesado()) != null) ){
				$sql += " precio_venta_procesado >= ? AND precio_venta_procesado <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " precio_venta_procesado = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getExistencias()) != null) & ( ($b = $detalle_inventario.getExistencias()) != null) ){
				$sql += " existencias >= ? AND existencias <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " existencias = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getExistenciasProcesadas()) != null) & ( ($b = $detalle_inventario.getExistenciasProcesadas()) != null) ){
				$sql += " existencias_procesadas >= ? AND existencias_procesadas <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " existencias_procesadas = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPrecioCompra()) != null) & ( ($b = $detalle_inventario.getPrecioCompra()) != null) ){
				$sql += " precio_compra >= ? AND precio_compra <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " precio_compra = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new DetalleInventario($foo));
		//}
		return $sql;
	};


}
	DetalleInventario._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link DetalleInventario}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	DetalleInventario.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from detalle_inventario";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new DetalleInventario( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link DetalleInventario} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link DetalleInventario} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link DetalleInventario Un objeto del tipo {@link DetalleInventario}. NULL si no hay tal registro.
	  **/
	DetalleInventario.getByPK = function(  $id_producto, $id_sucursal, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM detalle_inventario WHERE (id_producto = ? AND id_sucursal = ? ) LIMIT 1;";
		db.query($sql, [ $id_producto, $id_sucursal] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new DetalleInventario(results.rows.item(0)); 
				DetalleInventario._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : DetalleInventario._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table detalle_venta.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var DetalleVenta = function ( config )
{
 /**
	* id_venta
	* 
	* venta a que se referencia
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_venta = config === undefined ? '' : config.id_venta || '',

 /**
	* id_producto
	* 
	* producto de la venta
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	_id_producto = config === undefined ? '' : config.id_producto || '',

 /**
	* cantidad
	* 
	* cantidad que se vendio
	* @access private
	* @var float
	*/
	_cantidad = config === undefined ? '' : config.cantidad || '',

 /**
	* cantidad_procesada
	* 
	*  [Campo no documentado]
	* @access private
	* @var float
	*/
	_cantidad_procesada = config === undefined ? '' : config.cantidad_procesada || '',

 /**
	* precio
	* 
	* precio al que se vendio
	* @access private
	* @var float
	*/
	_precio = config === undefined ? '' : config.precio || '',

 /**
	* precio_procesada
	* 
	* el precio de los articulos procesados en esta venta
	* @access private
	* @var float
	*/
	_precio_procesada = config === undefined ? '' : config.precio_procesada || '',

 /**
	* descuento
	* 
	* indica cuanto producto original se va a descontar de ese producto en esa venta
	* @access private
	* @var float
	*/
	_descuento = config === undefined ? '' : config.descuento || '';

	/**
	  * getIdVenta
	  * 
	  * Get the <i>id_venta</i> property for this object. Donde <i>id_venta</i> es venta a que se referencia
	  * @return int(11)
	  */
	this.getIdVenta = function ()
	{
		return _id_venta;
	};

	/**
	  * setIdVenta( $id_venta )
	  * 
	  * Set the <i>id_venta</i> property for this object. Donde <i>id_venta</i> es venta a que se referencia.
	  * Una validacion basica se hara aqui para comprobar que <i>id_venta</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdVenta( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdVenta  = function ( id_venta )
	{
		_id_venta = id_venta;
	};

	/**
	  * getIdProducto
	  * 
	  * Get the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es producto de la venta
	  * @return int(11)
	  */
	this.getIdProducto = function ()
	{
		return _id_producto;
	};

	/**
	  * setIdProducto( $id_producto )
	  * 
	  * Set the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es producto de la venta.
	  * Una validacion basica se hara aqui para comprobar que <i>id_producto</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdProducto( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdProducto  = function ( id_producto )
	{
		_id_producto = id_producto;
	};

	/**
	  * getCantidad
	  * 
	  * Get the <i>cantidad</i> property for this object. Donde <i>cantidad</i> es cantidad que se vendio
	  * @return float
	  */
	this.getCantidad = function ()
	{
		return _cantidad;
	};

	/**
	  * setCantidad( $cantidad )
	  * 
	  * Set the <i>cantidad</i> property for this object. Donde <i>cantidad</i> es cantidad que se vendio.
	  * Una validacion basica se hara aqui para comprobar que <i>cantidad</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setCantidad  = function ( cantidad )
	{
		_cantidad = cantidad;
	};

	/**
	  * getCantidadProcesada
	  * 
	  * Get the <i>cantidad_procesada</i> property for this object. Donde <i>cantidad_procesada</i> es  [Campo no documentado]
	  * @return float
	  */
	this.getCantidadProcesada = function ()
	{
		return _cantidad_procesada;
	};

	/**
	  * setCantidadProcesada( $cantidad_procesada )
	  * 
	  * Set the <i>cantidad_procesada</i> property for this object. Donde <i>cantidad_procesada</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>cantidad_procesada</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setCantidadProcesada  = function ( cantidad_procesada )
	{
		_cantidad_procesada = cantidad_procesada;
	};

	/**
	  * getPrecio
	  * 
	  * Get the <i>precio</i> property for this object. Donde <i>precio</i> es precio al que se vendio
	  * @return float
	  */
	this.getPrecio = function ()
	{
		return _precio;
	};

	/**
	  * setPrecio( $precio )
	  * 
	  * Set the <i>precio</i> property for this object. Donde <i>precio</i> es precio al que se vendio.
	  * Una validacion basica se hara aqui para comprobar que <i>precio</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPrecio  = function ( precio )
	{
		_precio = precio;
	};

	/**
	  * getPrecioProcesada
	  * 
	  * Get the <i>precio_procesada</i> property for this object. Donde <i>precio_procesada</i> es el precio de los articulos procesados en esta venta
	  * @return float
	  */
	this.getPrecioProcesada = function ()
	{
		return _precio_procesada;
	};

	/**
	  * setPrecioProcesada( $precio_procesada )
	  * 
	  * Set the <i>precio_procesada</i> property for this object. Donde <i>precio_procesada</i> es el precio de los articulos procesados en esta venta.
	  * Una validacion basica se hara aqui para comprobar que <i>precio_procesada</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPrecioProcesada  = function ( precio_procesada )
	{
		_precio_procesada = precio_procesada;
	};

	/**
	  * getDescuento
	  * 
	  * Get the <i>descuento</i> property for this object. Donde <i>descuento</i> es indica cuanto producto original se va a descontar de ese producto en esa venta
	  * @return float
	  */
	this.getDescuento = function ()
	{
		return _descuento;
	};

	/**
	  * setDescuento( $descuento )
	  * 
	  * Set the <i>descuento</i> property for this object. Donde <i>descuento</i> es indica cuanto producto original se va a descontar de ese producto en esa venta.
	  * Una validacion basica se hara aqui para comprobar que <i>descuento</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setDescuento  = function ( descuento )
	{
		_descuento = descuento;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link DetalleVenta} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		DetalleVenta._callback_stack.push( _original_callback  );
		DetalleVenta._callback_stack.push( function(res){ 
						console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create.call(this, this);
						}else{
							update.call(res, res);
						}
	 			});
		DetalleVenta.getByPK(  this.getIdVenta() , this.getIdProducto() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleVenta} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param DetalleVenta [$detalle_venta] El objeto de tipo DetalleVenta
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from detalle_venta WHERE ("; 
		$val = [];
		if( this.getIdVenta() != null){
			$sql += " id_venta = ? AND";
			$val.push( this.getIdVenta() );
		}

		if( this.getIdProducto() != null){
			$sql += " id_producto = ? AND";
			$val.push( this.getIdProducto() );
		}

		if( this.getCantidad() != null){
			$sql += " cantidad = ? AND";
			$val.push( this.getCantidad() );
		}

		if( this.getCantidadProcesada() != null){
			$sql += " cantidad_procesada = ? AND";
			$val.push( this.getCantidadProcesada() );
		}

		if( this.getPrecio() != null){
			$sql += " precio = ? AND";
			$val.push( this.getPrecio() );
		}

		if( this.getPrecioProcesada() != null){
			$sql += " precio_procesada = ? AND";
			$val.push( this.getPrecioProcesada() );
		}

		if( this.getDescuento() != null){
			$sql += " descuento = ? AND";
			$val.push( this.getDescuento() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new DetalleVenta($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto DetalleVenta suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto DetalleVenta dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param DetalleVenta [$detalle_venta] El objeto de tipo DetalleVenta a crear.
	  **/
	var create = function( detalle_venta )
	{
		console.log('estoy en create(this)');
		$sql = "INSERT INTO detalle_venta ( id_venta, id_producto, cantidad, cantidad_procesada, precio, precio_procesada, descuento ) VALUES ( ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			detalle_venta.getIdVenta(), 
			detalle_venta.getIdProducto(), 
			detalle_venta.getCantidad(), 
			detalle_venta.getCantidadProcesada(), 
			detalle_venta.getPrecio(), 
			detalle_venta.getPrecioProcesada(), 
			detalle_venta.getDescuento(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				DetalleVenta._callback_stack.pop().call(null, detalle_venta);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param DetalleVenta [$detalle_venta] El objeto de tipo DetalleVenta a actualizar.
	  **/
	var update = function( $detalle_venta )
	{
		$sql = "UPDATE detalle_venta SET  cantidad = ?, cantidad_procesada = ?, precio = ?, precio_procesada = ?, descuento = ? WHERE  id_venta = ? AND id_producto = ?;";
		$params = [ 
			$detalle_venta.getCantidad(), 
			$detalle_venta.getCantidadProcesada(), 
			$detalle_venta.getPrecio(), 
			$detalle_venta.getPrecioProcesada(), 
			$detalle_venta.getDescuento(), 
			$detalle_venta.getIdVenta(),$detalle_venta.getIdProducto(),  ] ;
		console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				DetalleVenta._callback_stack.pop().call(null, $detalle_venta);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto DetalleVenta suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( DetalleVenta.getByPK(this.getIdVenta(), this.getIdProducto()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM detalle_venta WHERE  id_venta = ? AND id_producto = ?;";
		$params = [ this.getIdVenta(), this.getIdProducto() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link DetalleVenta} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link DetalleVenta}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param DetalleVenta [$detalle_venta] El objeto de tipo DetalleVenta
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $detalle_venta , $orderBy , $orden )
	{
		$sql = "SELECT * from detalle_venta WHERE ("; 
		$val = [];
		if( (($a = this.getIdVenta()) != null) & ( ($b = $detalle_venta.getIdVenta()) != null) ){
				$sql += " id_venta >= ? AND id_venta <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_venta = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdProducto()) != null) & ( ($b = $detalle_venta.getIdProducto()) != null) ){
				$sql += " id_producto >= ? AND id_producto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_producto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getCantidad()) != null) & ( ($b = $detalle_venta.getCantidad()) != null) ){
				$sql += " cantidad >= ? AND cantidad <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " cantidad = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getCantidadProcesada()) != null) & ( ($b = $detalle_venta.getCantidadProcesada()) != null) ){
				$sql += " cantidad_procesada >= ? AND cantidad_procesada <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " cantidad_procesada = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPrecio()) != null) & ( ($b = $detalle_venta.getPrecio()) != null) ){
				$sql += " precio >= ? AND precio <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " precio = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPrecioProcesada()) != null) & ( ($b = $detalle_venta.getPrecioProcesada()) != null) ){
				$sql += " precio_procesada >= ? AND precio_procesada <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " precio_procesada = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getDescuento()) != null) & ( ($b = $detalle_venta.getDescuento()) != null) ){
				$sql += " descuento >= ? AND descuento <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " descuento = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new DetalleVenta($foo));
		//}
		return $sql;
	};


}
	DetalleVenta._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link DetalleVenta}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	DetalleVenta.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from detalle_venta";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new DetalleVenta( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link DetalleVenta} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link DetalleVenta} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link DetalleVenta Un objeto del tipo {@link DetalleVenta}. NULL si no hay tal registro.
	  **/
	DetalleVenta.getByPK = function(  $id_venta, $id_producto, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM detalle_venta WHERE (id_venta = ? AND id_producto = ? ) LIMIT 1;";
		db.query($sql, [ $id_venta, $id_producto] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new DetalleVenta(results.rows.item(0)); 
				DetalleVenta._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : DetalleVenta._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table equipo.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var Equipo = function ( config )
{
 /**
	* id_equipo
	* 
	* el identificador de este equipo
	* <b>Llave Primaria</b>
	* @access private
	* @var int(6)
	*/
	var _id_equipo = config === undefined ? '' : config.id_equipo || '',

 /**
	* token
	* 
	* el token de seguridad que identifica a este equipo unicamente, representado generalmente por un user-agent modificado
	* @access private
	* @var varchar(128)
	*/
	_token = config === undefined ? '' : config.token || '',

 /**
	* full_ua
	* 
	* String de user-agent para este cliente
	* @access private
	* @var varchar(256)
	*/
	_full_ua = config === undefined ? '' : config.full_ua || '',

 /**
	* descripcion
	* 
	* descripcion de este equipo
	* @access private
	* @var varchar(254)
	*/
	_descripcion = config === undefined ? '' : config.descripcion || '',

 /**
	* locked
	* 
	* si este equipo esta lockeado para prevenir los cambios
	* @access private
	* @var tinyint(1)
	*/
	_locked = config === undefined ? '' : config.locked || '';

	/**
	  * getIdEquipo
	  * 
	  * Get the <i>id_equipo</i> property for this object. Donde <i>id_equipo</i> es el identificador de este equipo
	  * @return int(6)
	  */
	this.getIdEquipo = function ()
	{
		return _id_equipo;
	};

	/**
	  * setIdEquipo( $id_equipo )
	  * 
	  * Set the <i>id_equipo</i> property for this object. Donde <i>id_equipo</i> es el identificador de este equipo.
	  * Una validacion basica se hara aqui para comprobar que <i>id_equipo</i> es de tipo <i>int(6)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdEquipo( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(6)
	  */
	this.setIdEquipo  = function ( id_equipo )
	{
		_id_equipo = id_equipo;
	};

	/**
	  * getToken
	  * 
	  * Get the <i>token</i> property for this object. Donde <i>token</i> es el token de seguridad que identifica a este equipo unicamente, representado generalmente por un user-agent modificado
	  * @return varchar(128)
	  */
	this.getToken = function ()
	{
		return _token;
	};

	/**
	  * setToken( $token )
	  * 
	  * Set the <i>token</i> property for this object. Donde <i>token</i> es el token de seguridad que identifica a este equipo unicamente, representado generalmente por un user-agent modificado.
	  * Una validacion basica se hara aqui para comprobar que <i>token</i> es de tipo <i>varchar(128)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(128)
	  */
	this.setToken  = function ( token )
	{
		_token = token;
	};

	/**
	  * getFullUa
	  * 
	  * Get the <i>full_ua</i> property for this object. Donde <i>full_ua</i> es String de user-agent para este cliente
	  * @return varchar(256)
	  */
	this.getFullUa = function ()
	{
		return _full_ua;
	};

	/**
	  * setFullUa( $full_ua )
	  * 
	  * Set the <i>full_ua</i> property for this object. Donde <i>full_ua</i> es String de user-agent para este cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>full_ua</i> es de tipo <i>varchar(256)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(256)
	  */
	this.setFullUa  = function ( full_ua )
	{
		_full_ua = full_ua;
	};

	/**
	  * getDescripcion
	  * 
	  * Get the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es descripcion de este equipo
	  * @return varchar(254)
	  */
	this.getDescripcion = function ()
	{
		return _descripcion;
	};

	/**
	  * setDescripcion( $descripcion )
	  * 
	  * Set the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es descripcion de este equipo.
	  * Una validacion basica se hara aqui para comprobar que <i>descripcion</i> es de tipo <i>varchar(254)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(254)
	  */
	this.setDescripcion  = function ( descripcion )
	{
		_descripcion = descripcion;
	};

	/**
	  * getLocked
	  * 
	  * Get the <i>locked</i> property for this object. Donde <i>locked</i> es si este equipo esta lockeado para prevenir los cambios
	  * @return tinyint(1)
	  */
	this.getLocked = function ()
	{
		return _locked;
	};

	/**
	  * setLocked( $locked )
	  * 
	  * Set the <i>locked</i> property for this object. Donde <i>locked</i> es si este equipo esta lockeado para prevenir los cambios.
	  * Una validacion basica se hara aqui para comprobar que <i>locked</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	this.setLocked  = function ( locked )
	{
		_locked = locked;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Equipo} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		Equipo._callback_stack.push( _original_callback  );
		Equipo._callback_stack.push( function(res){ 
						console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create.call(this, this);
						}else{
							update.call(res, res);
						}
	 			});
		Equipo.getByPK(  this.getIdEquipo() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Equipo} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param Equipo [$equipo] El objeto de tipo Equipo
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from equipo WHERE ("; 
		$val = [];
		if( this.getIdEquipo() != null){
			$sql += " id_equipo = ? AND";
			$val.push( this.getIdEquipo() );
		}

		if( this.getToken() != null){
			$sql += " token = ? AND";
			$val.push( this.getToken() );
		}

		if( this.getFullUa() != null){
			$sql += " full_ua = ? AND";
			$val.push( this.getFullUa() );
		}

		if( this.getDescripcion() != null){
			$sql += " descripcion = ? AND";
			$val.push( this.getDescripcion() );
		}

		if( this.getLocked() != null){
			$sql += " locked = ? AND";
			$val.push( this.getLocked() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Equipo($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Equipo suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Equipo dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Equipo [$equipo] El objeto de tipo Equipo a crear.
	  **/
	var create = function( equipo )
	{
		console.log('estoy en create(this)');
		$sql = "INSERT INTO equipo ( id_equipo, token, full_ua, descripcion, locked ) VALUES ( ?, ?, ?, ?, ?);";
		$params = [
			equipo.getIdEquipo(), 
			equipo.getToken(), 
			equipo.getFullUa(), 
			equipo.getDescripcion(), 
			equipo.getLocked(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				Equipo._callback_stack.pop().call(null, equipo);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param Equipo [$equipo] El objeto de tipo Equipo a actualizar.
	  **/
	var update = function( $equipo )
	{
		$sql = "UPDATE equipo SET  token = ?, full_ua = ?, descripcion = ?, locked = ? WHERE  id_equipo = ?;";
		$params = [ 
			$equipo.getToken(), 
			$equipo.getFullUa(), 
			$equipo.getDescripcion(), 
			$equipo.getLocked(), 
			$equipo.getIdEquipo(),  ] ;
		console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				Equipo._callback_stack.pop().call(null, $equipo);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Equipo suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( Equipo.getByPK(this.getIdEquipo()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM equipo WHERE  id_equipo = ?;";
		$params = [ this.getIdEquipo() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Equipo} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Equipo}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param Equipo [$equipo] El objeto de tipo Equipo
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $equipo , $orderBy , $orden )
	{
		$sql = "SELECT * from equipo WHERE ("; 
		$val = [];
		if( (($a = this.getIdEquipo()) != null) & ( ($b = $equipo.getIdEquipo()) != null) ){
				$sql += " id_equipo >= ? AND id_equipo <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_equipo = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getToken()) != null) & ( ($b = $equipo.getToken()) != null) ){
				$sql += " token >= ? AND token <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " token = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFullUa()) != null) & ( ($b = $equipo.getFullUa()) != null) ){
				$sql += " full_ua >= ? AND full_ua <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " full_ua = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getDescripcion()) != null) & ( ($b = $equipo.getDescripcion()) != null) ){
				$sql += " descripcion >= ? AND descripcion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " descripcion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getLocked()) != null) & ( ($b = $equipo.getLocked()) != null) ){
				$sql += " locked >= ? AND locked <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " locked = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Equipo($foo));
		//}
		return $sql;
	};


}
	Equipo._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Equipo}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	Equipo.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from equipo";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new Equipo( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link Equipo} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Equipo} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Equipo Un objeto del tipo {@link Equipo}. NULL si no hay tal registro.
	  **/
	Equipo.getByPK = function(  $id_equipo, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM equipo WHERE (id_equipo = ? ) LIMIT 1;";
		db.query($sql, [ $id_equipo] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new Equipo(results.rows.item(0)); 
				Equipo._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : Equipo._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table factura_compra.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var FacturaCompra = function ( config )
{
 /**
	* folio
	* 
	*  [Campo no documentado]
	* <b>Llave Primaria</b>
	* @access private
	* @var varchar(15)
	*/
	var _folio = config === undefined ? '' : config.folio || '',

 /**
	* id_compra
	* 
	* COMPRA A LA QUE CORRESPONDE LA FACTURA
	* @access private
	* @var int(11)
	*/
	_id_compra = config === undefined ? '' : config.id_compra || '';

	/**
	  * getFolio
	  * 
	  * Get the <i>folio</i> property for this object. Donde <i>folio</i> es  [Campo no documentado]
	  * @return varchar(15)
	  */
	this.getFolio = function ()
	{
		return _folio;
	};

	/**
	  * setFolio( $folio )
	  * 
	  * Set the <i>folio</i> property for this object. Donde <i>folio</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>folio</i> es de tipo <i>varchar(15)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setFolio( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param varchar(15)
	  */
	this.setFolio  = function ( folio )
	{
		_folio = folio;
	};

	/**
	  * getIdCompra
	  * 
	  * Get the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es COMPRA A LA QUE CORRESPONDE LA FACTURA
	  * @return int(11)
	  */
	this.getIdCompra = function ()
	{
		return _id_compra;
	};

	/**
	  * setIdCompra( $id_compra )
	  * 
	  * Set the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es COMPRA A LA QUE CORRESPONDE LA FACTURA.
	  * Una validacion basica se hara aqui para comprobar que <i>id_compra</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdCompra  = function ( id_compra )
	{
		_id_compra = id_compra;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link FacturaCompra} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		FacturaCompra._callback_stack.push( _original_callback  );
		FacturaCompra._callback_stack.push( function(res){ 
						console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create.call(this, this);
						}else{
							update.call(res, res);
						}
	 			});
		FacturaCompra.getByPK(  this.getFolio() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link FacturaCompra} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param FacturaCompra [$factura_compra] El objeto de tipo FacturaCompra
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from factura_compra WHERE ("; 
		$val = [];
		if( this.getFolio() != null){
			$sql += " folio = ? AND";
			$val.push( this.getFolio() );
		}

		if( this.getIdCompra() != null){
			$sql += " id_compra = ? AND";
			$val.push( this.getIdCompra() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new FacturaCompra($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto FacturaCompra suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto FacturaCompra dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param FacturaCompra [$factura_compra] El objeto de tipo FacturaCompra a crear.
	  **/
	var create = function( factura_compra )
	{
		console.log('estoy en create(this)');
		$sql = "INSERT INTO factura_compra ( folio, id_compra ) VALUES ( ?, ?);";
		$params = [
			factura_compra.getFolio(), 
			factura_compra.getIdCompra(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				FacturaCompra._callback_stack.pop().call(null, factura_compra);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param FacturaCompra [$factura_compra] El objeto de tipo FacturaCompra a actualizar.
	  **/
	var update = function( $factura_compra )
	{
		$sql = "UPDATE factura_compra SET  id_compra = ? WHERE  folio = ?;";
		$params = [ 
			$factura_compra.getIdCompra(), 
			$factura_compra.getFolio(),  ] ;
		console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				FacturaCompra._callback_stack.pop().call(null, $factura_compra);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto FacturaCompra suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( FacturaCompra.getByPK(this.getFolio()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM factura_compra WHERE  folio = ?;";
		$params = [ this.getFolio() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link FacturaCompra} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link FacturaCompra}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param FacturaCompra [$factura_compra] El objeto de tipo FacturaCompra
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $factura_compra , $orderBy , $orden )
	{
		$sql = "SELECT * from factura_compra WHERE ("; 
		$val = [];
		if( (($a = this.getFolio()) != null) & ( ($b = $factura_compra.getFolio()) != null) ){
				$sql += " folio >= ? AND folio <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " folio = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdCompra()) != null) & ( ($b = $factura_compra.getIdCompra()) != null) ){
				$sql += " id_compra >= ? AND id_compra <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_compra = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new FacturaCompra($foo));
		//}
		return $sql;
	};


}
	FacturaCompra._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link FacturaCompra}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	FacturaCompra.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from factura_compra";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new FacturaCompra( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link FacturaCompra} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link FacturaCompra} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link FacturaCompra Un objeto del tipo {@link FacturaCompra}. NULL si no hay tal registro.
	  **/
	FacturaCompra.getByPK = function(  $folio, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM factura_compra WHERE (folio = ? ) LIMIT 1;";
		db.query($sql, [ $folio] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new FacturaCompra(results.rows.item(0)); 
				FacturaCompra._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : FacturaCompra._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table factura_venta.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var FacturaVenta = function ( config )
{
 /**
	* id_folio
	* 
	* folio que tiene la factura
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_folio = config === undefined ? '' : config.id_folio || '',

 /**
	* id_venta
	* 
	* venta a la cual corresponde la factura
	* @access private
	* @var int(11)
	*/
	_id_venta = config === undefined ? '' : config.id_venta || '',

 /**
	* id_usuario
	* 
	* Id del usuario que hiso al ultima modificacion a la factura
	* @access private
	* @var int(10)
	*/
	_id_usuario = config === undefined ? '' : config.id_usuario || '',

 /**
	* xml
	* 
	* xml en bruto
	* @access private
	* @var text
	*/
	_xml = config === undefined ? '' : config.xml || '',

 /**
	* lugar_emision
	* 
	* id de la sucursal donde se emitio la factura
	* @access private
	* @var int(11)
	*/
	_lugar_emision = config === undefined ? '' : config.lugar_emision || '',

 /**
	* tipo_comprobante
	* 
	*  [Campo no documentado]
	* @access private
	* @var enum('ingreso','egreso')
	*/
	_tipo_comprobante = config === undefined ? '' : config.tipo_comprobante || '',

 /**
	* activa
	* 
	* 1 indica que la factura fue emitida y esta activa, 0 que la factura fue emitida y posteriormente fue cancelada
	* @access private
	* @var tinyint(1)
	*/
	_activa = config === undefined ? '' : config.activa || '',

 /**
	* sellada
	* 
	* Indica si el WS ha timbrado la factura
	* @access private
	* @var tinyint(1)
	*/
	_sellada = config === undefined ? '' : config.sellada || '',

 /**
	* forma_pago
	* 
	*  [Campo no documentado]
	* @access private
	* @var varchar(100)
	*/
	_forma_pago = config === undefined ? '' : config.forma_pago || '',

 /**
	* fecha_emision
	* 
	*  [Campo no documentado]
	* @access private
	* @var timestamp
	*/
	_fecha_emision = config === undefined ? '' : config.fecha_emision || '',

 /**
	* version_tfd
	* 
	*  [Campo no documentado]
	* @access private
	* @var varchar(10)
	*/
	_version_tfd = config === undefined ? '' : config.version_tfd || '',

 /**
	* folio_fiscal
	* 
	*  [Campo no documentado]
	* @access private
	* @var varchar(128)
	*/
	_folio_fiscal = config === undefined ? '' : config.folio_fiscal || '',

 /**
	* fecha_certificacion
	* 
	*  [Campo no documentado]
	* @access private
	* @var timestamp
	*/
	_fecha_certificacion = config === undefined ? '' : config.fecha_certificacion || '',

 /**
	* numero_certificado_sat
	* 
	*  [Campo no documentado]
	* @access private
	* @var varchar(128)
	*/
	_numero_certificado_sat = config === undefined ? '' : config.numero_certificado_sat || '',

 /**
	* sello_digital_emisor
	* 
	*  [Campo no documentado]
	* @access private
	* @var varchar(512)
	*/
	_sello_digital_emisor = config === undefined ? '' : config.sello_digital_emisor || '',

 /**
	* sello_digital_sat
	* 
	*  [Campo no documentado]
	* @access private
	* @var varchar(512)
	*/
	_sello_digital_sat = config === undefined ? '' : config.sello_digital_sat || '',

 /**
	* cadena_original
	* 
	*  [Campo no documentado]
	* @access private
	* @var varchar(2048)
	*/
	_cadena_original = config === undefined ? '' : config.cadena_original || '';

	/**
	  * getIdFolio
	  * 
	  * Get the <i>id_folio</i> property for this object. Donde <i>id_folio</i> es folio que tiene la factura
	  * @return int(11)
	  */
	this.getIdFolio = function ()
	{
		return _id_folio;
	};

	/**
	  * setIdFolio( $id_folio )
	  * 
	  * Set the <i>id_folio</i> property for this object. Donde <i>id_folio</i> es folio que tiene la factura.
	  * Una validacion basica se hara aqui para comprobar que <i>id_folio</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdFolio( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdFolio  = function ( id_folio )
	{
		_id_folio = id_folio;
	};

	/**
	  * getIdVenta
	  * 
	  * Get the <i>id_venta</i> property for this object. Donde <i>id_venta</i> es venta a la cual corresponde la factura
	  * @return int(11)
	  */
	this.getIdVenta = function ()
	{
		return _id_venta;
	};

	/**
	  * setIdVenta( $id_venta )
	  * 
	  * Set the <i>id_venta</i> property for this object. Donde <i>id_venta</i> es venta a la cual corresponde la factura.
	  * Una validacion basica se hara aqui para comprobar que <i>id_venta</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdVenta  = function ( id_venta )
	{
		_id_venta = id_venta;
	};

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Id del usuario que hiso al ultima modificacion a la factura
	  * @return int(10)
	  */
	this.getIdUsuario = function ()
	{
		return _id_usuario;
	};

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Id del usuario que hiso al ultima modificacion a la factura.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(10)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(10)
	  */
	this.setIdUsuario  = function ( id_usuario )
	{
		_id_usuario = id_usuario;
	};

	/**
	  * getXml
	  * 
	  * Get the <i>xml</i> property for this object. Donde <i>xml</i> es xml en bruto
	  * @return text
	  */
	this.getXml = function ()
	{
		return _xml;
	};

	/**
	  * setXml( $xml )
	  * 
	  * Set the <i>xml</i> property for this object. Donde <i>xml</i> es xml en bruto.
	  * Una validacion basica se hara aqui para comprobar que <i>xml</i> es de tipo <i>text</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param text
	  */
	this.setXml  = function ( xml )
	{
		_xml = xml;
	};

	/**
	  * getLugarEmision
	  * 
	  * Get the <i>lugar_emision</i> property for this object. Donde <i>lugar_emision</i> es id de la sucursal donde se emitio la factura
	  * @return int(11)
	  */
	this.getLugarEmision = function ()
	{
		return _lugar_emision;
	};

	/**
	  * setLugarEmision( $lugar_emision )
	  * 
	  * Set the <i>lugar_emision</i> property for this object. Donde <i>lugar_emision</i> es id de la sucursal donde se emitio la factura.
	  * Una validacion basica se hara aqui para comprobar que <i>lugar_emision</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setLugarEmision  = function ( lugar_emision )
	{
		_lugar_emision = lugar_emision;
	};

	/**
	  * getTipoComprobante
	  * 
	  * Get the <i>tipo_comprobante</i> property for this object. Donde <i>tipo_comprobante</i> es  [Campo no documentado]
	  * @return enum('ingreso','egreso')
	  */
	this.getTipoComprobante = function ()
	{
		return _tipo_comprobante;
	};

	/**
	  * setTipoComprobante( $tipo_comprobante )
	  * 
	  * Set the <i>tipo_comprobante</i> property for this object. Donde <i>tipo_comprobante</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>tipo_comprobante</i> es de tipo <i>enum('ingreso','egreso')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('ingreso','egreso')
	  */
	this.setTipoComprobante  = function ( tipo_comprobante )
	{
		_tipo_comprobante = tipo_comprobante;
	};

	/**
	  * getActiva
	  * 
	  * Get the <i>activa</i> property for this object. Donde <i>activa</i> es 1 indica que la factura fue emitida y esta activa, 0 que la factura fue emitida y posteriormente fue cancelada
	  * @return tinyint(1)
	  */
	this.getActiva = function ()
	{
		return _activa;
	};

	/**
	  * setActiva( $activa )
	  * 
	  * Set the <i>activa</i> property for this object. Donde <i>activa</i> es 1 indica que la factura fue emitida y esta activa, 0 que la factura fue emitida y posteriormente fue cancelada.
	  * Una validacion basica se hara aqui para comprobar que <i>activa</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	this.setActiva  = function ( activa )
	{
		_activa = activa;
	};

	/**
	  * getSellada
	  * 
	  * Get the <i>sellada</i> property for this object. Donde <i>sellada</i> es Indica si el WS ha timbrado la factura
	  * @return tinyint(1)
	  */
	this.getSellada = function ()
	{
		return _sellada;
	};

	/**
	  * setSellada( $sellada )
	  * 
	  * Set the <i>sellada</i> property for this object. Donde <i>sellada</i> es Indica si el WS ha timbrado la factura.
	  * Una validacion basica se hara aqui para comprobar que <i>sellada</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	this.setSellada  = function ( sellada )
	{
		_sellada = sellada;
	};

	/**
	  * getFormaPago
	  * 
	  * Get the <i>forma_pago</i> property for this object. Donde <i>forma_pago</i> es  [Campo no documentado]
	  * @return varchar(100)
	  */
	this.getFormaPago = function ()
	{
		return _forma_pago;
	};

	/**
	  * setFormaPago( $forma_pago )
	  * 
	  * Set the <i>forma_pago</i> property for this object. Donde <i>forma_pago</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>forma_pago</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	this.setFormaPago  = function ( forma_pago )
	{
		_forma_pago = forma_pago;
	};

	/**
	  * getFechaEmision
	  * 
	  * Get the <i>fecha_emision</i> property for this object. Donde <i>fecha_emision</i> es  [Campo no documentado]
	  * @return timestamp
	  */
	this.getFechaEmision = function ()
	{
		return _fecha_emision;
	};

	/**
	  * setFechaEmision( $fecha_emision )
	  * 
	  * Set the <i>fecha_emision</i> property for this object. Donde <i>fecha_emision</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_emision</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFechaEmision  = function ( fecha_emision )
	{
		_fecha_emision = fecha_emision;
	};

	/**
	  * getVersionTfd
	  * 
	  * Get the <i>version_tfd</i> property for this object. Donde <i>version_tfd</i> es  [Campo no documentado]
	  * @return varchar(10)
	  */
	this.getVersionTfd = function ()
	{
		return _version_tfd;
	};

	/**
	  * setVersionTfd( $version_tfd )
	  * 
	  * Set the <i>version_tfd</i> property for this object. Donde <i>version_tfd</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>version_tfd</i> es de tipo <i>varchar(10)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(10)
	  */
	this.setVersionTfd  = function ( version_tfd )
	{
		_version_tfd = version_tfd;
	};

	/**
	  * getFolioFiscal
	  * 
	  * Get the <i>folio_fiscal</i> property for this object. Donde <i>folio_fiscal</i> es  [Campo no documentado]
	  * @return varchar(128)
	  */
	this.getFolioFiscal = function ()
	{
		return _folio_fiscal;
	};

	/**
	  * setFolioFiscal( $folio_fiscal )
	  * 
	  * Set the <i>folio_fiscal</i> property for this object. Donde <i>folio_fiscal</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>folio_fiscal</i> es de tipo <i>varchar(128)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(128)
	  */
	this.setFolioFiscal  = function ( folio_fiscal )
	{
		_folio_fiscal = folio_fiscal;
	};

	/**
	  * getFechaCertificacion
	  * 
	  * Get the <i>fecha_certificacion</i> property for this object. Donde <i>fecha_certificacion</i> es  [Campo no documentado]
	  * @return timestamp
	  */
	this.getFechaCertificacion = function ()
	{
		return _fecha_certificacion;
	};

	/**
	  * setFechaCertificacion( $fecha_certificacion )
	  * 
	  * Set the <i>fecha_certificacion</i> property for this object. Donde <i>fecha_certificacion</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_certificacion</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFechaCertificacion  = function ( fecha_certificacion )
	{
		_fecha_certificacion = fecha_certificacion;
	};

	/**
	  * getNumeroCertificadoSat
	  * 
	  * Get the <i>numero_certificado_sat</i> property for this object. Donde <i>numero_certificado_sat</i> es  [Campo no documentado]
	  * @return varchar(128)
	  */
	this.getNumeroCertificadoSat = function ()
	{
		return _numero_certificado_sat;
	};

	/**
	  * setNumeroCertificadoSat( $numero_certificado_sat )
	  * 
	  * Set the <i>numero_certificado_sat</i> property for this object. Donde <i>numero_certificado_sat</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>numero_certificado_sat</i> es de tipo <i>varchar(128)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(128)
	  */
	this.setNumeroCertificadoSat  = function ( numero_certificado_sat )
	{
		_numero_certificado_sat = numero_certificado_sat;
	};

	/**
	  * getSelloDigitalEmisor
	  * 
	  * Get the <i>sello_digital_emisor</i> property for this object. Donde <i>sello_digital_emisor</i> es  [Campo no documentado]
	  * @return varchar(512)
	  */
	this.getSelloDigitalEmisor = function ()
	{
		return _sello_digital_emisor;
	};

	/**
	  * setSelloDigitalEmisor( $sello_digital_emisor )
	  * 
	  * Set the <i>sello_digital_emisor</i> property for this object. Donde <i>sello_digital_emisor</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>sello_digital_emisor</i> es de tipo <i>varchar(512)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(512)
	  */
	this.setSelloDigitalEmisor  = function ( sello_digital_emisor )
	{
		_sello_digital_emisor = sello_digital_emisor;
	};

	/**
	  * getSelloDigitalSat
	  * 
	  * Get the <i>sello_digital_sat</i> property for this object. Donde <i>sello_digital_sat</i> es  [Campo no documentado]
	  * @return varchar(512)
	  */
	this.getSelloDigitalSat = function ()
	{
		return _sello_digital_sat;
	};

	/**
	  * setSelloDigitalSat( $sello_digital_sat )
	  * 
	  * Set the <i>sello_digital_sat</i> property for this object. Donde <i>sello_digital_sat</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>sello_digital_sat</i> es de tipo <i>varchar(512)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(512)
	  */
	this.setSelloDigitalSat  = function ( sello_digital_sat )
	{
		_sello_digital_sat = sello_digital_sat;
	};

	/**
	  * getCadenaOriginal
	  * 
	  * Get the <i>cadena_original</i> property for this object. Donde <i>cadena_original</i> es  [Campo no documentado]
	  * @return varchar(2048)
	  */
	this.getCadenaOriginal = function ()
	{
		return _cadena_original;
	};

	/**
	  * setCadenaOriginal( $cadena_original )
	  * 
	  * Set the <i>cadena_original</i> property for this object. Donde <i>cadena_original</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>cadena_original</i> es de tipo <i>varchar(2048)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(2048)
	  */
	this.setCadenaOriginal  = function ( cadena_original )
	{
		_cadena_original = cadena_original;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link FacturaVenta} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		FacturaVenta._callback_stack.push( _original_callback  );
		FacturaVenta._callback_stack.push( function(res){ 
						console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create.call(this, this);
						}else{
							update.call(res, res);
						}
	 			});
		FacturaVenta.getByPK(  this.getIdFolio() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link FacturaVenta} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param FacturaVenta [$factura_venta] El objeto de tipo FacturaVenta
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from factura_venta WHERE ("; 
		$val = [];
		if( this.getIdFolio() != null){
			$sql += " id_folio = ? AND";
			$val.push( this.getIdFolio() );
		}

		if( this.getIdVenta() != null){
			$sql += " id_venta = ? AND";
			$val.push( this.getIdVenta() );
		}

		if( this.getIdUsuario() != null){
			$sql += " id_usuario = ? AND";
			$val.push( this.getIdUsuario() );
		}

		if( this.getXml() != null){
			$sql += " xml = ? AND";
			$val.push( this.getXml() );
		}

		if( this.getLugarEmision() != null){
			$sql += " lugar_emision = ? AND";
			$val.push( this.getLugarEmision() );
		}

		if( this.getTipoComprobante() != null){
			$sql += " tipo_comprobante = ? AND";
			$val.push( this.getTipoComprobante() );
		}

		if( this.getActiva() != null){
			$sql += " activa = ? AND";
			$val.push( this.getActiva() );
		}

		if( this.getSellada() != null){
			$sql += " sellada = ? AND";
			$val.push( this.getSellada() );
		}

		if( this.getFormaPago() != null){
			$sql += " forma_pago = ? AND";
			$val.push( this.getFormaPago() );
		}

		if( this.getFechaEmision() != null){
			$sql += " fecha_emision = ? AND";
			$val.push( this.getFechaEmision() );
		}

		if( this.getVersionTfd() != null){
			$sql += " version_tfd = ? AND";
			$val.push( this.getVersionTfd() );
		}

		if( this.getFolioFiscal() != null){
			$sql += " folio_fiscal = ? AND";
			$val.push( this.getFolioFiscal() );
		}

		if( this.getFechaCertificacion() != null){
			$sql += " fecha_certificacion = ? AND";
			$val.push( this.getFechaCertificacion() );
		}

		if( this.getNumeroCertificadoSat() != null){
			$sql += " numero_certificado_sat = ? AND";
			$val.push( this.getNumeroCertificadoSat() );
		}

		if( this.getSelloDigitalEmisor() != null){
			$sql += " sello_digital_emisor = ? AND";
			$val.push( this.getSelloDigitalEmisor() );
		}

		if( this.getSelloDigitalSat() != null){
			$sql += " sello_digital_sat = ? AND";
			$val.push( this.getSelloDigitalSat() );
		}

		if( this.getCadenaOriginal() != null){
			$sql += " cadena_original = ? AND";
			$val.push( this.getCadenaOriginal() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new FacturaVenta($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto FacturaVenta suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto FacturaVenta dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param FacturaVenta [$factura_venta] El objeto de tipo FacturaVenta a crear.
	  **/
	var create = function( factura_venta )
	{
		console.log('estoy en create(this)');
		$sql = "INSERT INTO factura_venta ( id_folio, id_venta, id_usuario, xml, lugar_emision, tipo_comprobante, activa, sellada, forma_pago, fecha_emision, version_tfd, folio_fiscal, fecha_certificacion, numero_certificado_sat, sello_digital_emisor, sello_digital_sat, cadena_original ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			factura_venta.getIdFolio(), 
			factura_venta.getIdVenta(), 
			factura_venta.getIdUsuario(), 
			factura_venta.getXml(), 
			factura_venta.getLugarEmision(), 
			factura_venta.getTipoComprobante(), 
			factura_venta.getActiva(), 
			factura_venta.getSellada(), 
			factura_venta.getFormaPago(), 
			factura_venta.getFechaEmision(), 
			factura_venta.getVersionTfd(), 
			factura_venta.getFolioFiscal(), 
			factura_venta.getFechaCertificacion(), 
			factura_venta.getNumeroCertificadoSat(), 
			factura_venta.getSelloDigitalEmisor(), 
			factura_venta.getSelloDigitalSat(), 
			factura_venta.getCadenaOriginal(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				FacturaVenta._callback_stack.pop().call(null, factura_venta);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param FacturaVenta [$factura_venta] El objeto de tipo FacturaVenta a actualizar.
	  **/
	var update = function( $factura_venta )
	{
		$sql = "UPDATE factura_venta SET  id_venta = ?, id_usuario = ?, xml = ?, lugar_emision = ?, tipo_comprobante = ?, activa = ?, sellada = ?, forma_pago = ?, fecha_emision = ?, version_tfd = ?, folio_fiscal = ?, fecha_certificacion = ?, numero_certificado_sat = ?, sello_digital_emisor = ?, sello_digital_sat = ?, cadena_original = ? WHERE  id_folio = ?;";
		$params = [ 
			$factura_venta.getIdVenta(), 
			$factura_venta.getIdUsuario(), 
			$factura_venta.getXml(), 
			$factura_venta.getLugarEmision(), 
			$factura_venta.getTipoComprobante(), 
			$factura_venta.getActiva(), 
			$factura_venta.getSellada(), 
			$factura_venta.getFormaPago(), 
			$factura_venta.getFechaEmision(), 
			$factura_venta.getVersionTfd(), 
			$factura_venta.getFolioFiscal(), 
			$factura_venta.getFechaCertificacion(), 
			$factura_venta.getNumeroCertificadoSat(), 
			$factura_venta.getSelloDigitalEmisor(), 
			$factura_venta.getSelloDigitalSat(), 
			$factura_venta.getCadenaOriginal(), 
			$factura_venta.getIdFolio(),  ] ;
		console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				FacturaVenta._callback_stack.pop().call(null, $factura_venta);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto FacturaVenta suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( FacturaVenta.getByPK(this.getIdFolio()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM factura_venta WHERE  id_folio = ?;";
		$params = [ this.getIdFolio() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link FacturaVenta} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link FacturaVenta}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param FacturaVenta [$factura_venta] El objeto de tipo FacturaVenta
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $factura_venta , $orderBy , $orden )
	{
		$sql = "SELECT * from factura_venta WHERE ("; 
		$val = [];
		if( (($a = this.getIdFolio()) != null) & ( ($b = $factura_venta.getIdFolio()) != null) ){
				$sql += " id_folio >= ? AND id_folio <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_folio = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdVenta()) != null) & ( ($b = $factura_venta.getIdVenta()) != null) ){
				$sql += " id_venta >= ? AND id_venta <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_venta = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdUsuario()) != null) & ( ($b = $factura_venta.getIdUsuario()) != null) ){
				$sql += " id_usuario >= ? AND id_usuario <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_usuario = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getXml()) != null) & ( ($b = $factura_venta.getXml()) != null) ){
				$sql += " xml >= ? AND xml <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " xml = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getLugarEmision()) != null) & ( ($b = $factura_venta.getLugarEmision()) != null) ){
				$sql += " lugar_emision >= ? AND lugar_emision <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " lugar_emision = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTipoComprobante()) != null) & ( ($b = $factura_venta.getTipoComprobante()) != null) ){
				$sql += " tipo_comprobante >= ? AND tipo_comprobante <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " tipo_comprobante = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getActiva()) != null) & ( ($b = $factura_venta.getActiva()) != null) ){
				$sql += " activa >= ? AND activa <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " activa = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getSellada()) != null) & ( ($b = $factura_venta.getSellada()) != null) ){
				$sql += " sellada >= ? AND sellada <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " sellada = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFormaPago()) != null) & ( ($b = $factura_venta.getFormaPago()) != null) ){
				$sql += " forma_pago >= ? AND forma_pago <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " forma_pago = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFechaEmision()) != null) & ( ($b = $factura_venta.getFechaEmision()) != null) ){
				$sql += " fecha_emision >= ? AND fecha_emision <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha_emision = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getVersionTfd()) != null) & ( ($b = $factura_venta.getVersionTfd()) != null) ){
				$sql += " version_tfd >= ? AND version_tfd <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " version_tfd = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFolioFiscal()) != null) & ( ($b = $factura_venta.getFolioFiscal()) != null) ){
				$sql += " folio_fiscal >= ? AND folio_fiscal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " folio_fiscal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFechaCertificacion()) != null) & ( ($b = $factura_venta.getFechaCertificacion()) != null) ){
				$sql += " fecha_certificacion >= ? AND fecha_certificacion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha_certificacion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getNumeroCertificadoSat()) != null) & ( ($b = $factura_venta.getNumeroCertificadoSat()) != null) ){
				$sql += " numero_certificado_sat >= ? AND numero_certificado_sat <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " numero_certificado_sat = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getSelloDigitalEmisor()) != null) & ( ($b = $factura_venta.getSelloDigitalEmisor()) != null) ){
				$sql += " sello_digital_emisor >= ? AND sello_digital_emisor <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " sello_digital_emisor = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getSelloDigitalSat()) != null) & ( ($b = $factura_venta.getSelloDigitalSat()) != null) ){
				$sql += " sello_digital_sat >= ? AND sello_digital_sat <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " sello_digital_sat = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getCadenaOriginal()) != null) & ( ($b = $factura_venta.getCadenaOriginal()) != null) ){
				$sql += " cadena_original >= ? AND cadena_original <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " cadena_original = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new FacturaVenta($foo));
		//}
		return $sql;
	};


}
	FacturaVenta._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link FacturaVenta}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	FacturaVenta.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from factura_venta";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new FacturaVenta( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link FacturaVenta} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link FacturaVenta} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link FacturaVenta Un objeto del tipo {@link FacturaVenta}. NULL si no hay tal registro.
	  **/
	FacturaVenta.getByPK = function(  $id_folio, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM factura_venta WHERE (id_folio = ? ) LIMIT 1;";
		db.query($sql, [ $id_folio] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new FacturaVenta(results.rows.item(0)); 
				FacturaVenta._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : FacturaVenta._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table gastos.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var Gastos = function ( config )
{
 /**
	* id_gasto
	* 
	* id para identificar el gasto
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_gasto = config === undefined ? '' : config.id_gasto || '',

 /**
	* folio
	* 
	* El folio de la factura para este gasto
	* @access private
	* @var varchar(22)
	*/
	_folio = config === undefined ? '' : config.folio || '',

 /**
	* concepto
	* 
	* concepto en lo que se gasto
	* @access private
	* @var varchar(100)
	*/
	_concepto = config === undefined ? '' : config.concepto || '',

 /**
	* monto
	* 
	* lo que costo este gasto
	* @access private
	* @var float
	*/
	_monto = config === undefined ? '' : config.monto || '',

 /**
	* fecha
	* 
	* fecha del gasto
	* @access private
	* @var timestamp
	*/
	_fecha = config === undefined ? '' : config.fecha || '',

 /**
	* fecha_ingreso
	* 
	* Fecha que selecciono el empleado en el sistema
	* @access private
	* @var timestamp
	*/
	_fecha_ingreso = config === undefined ? '' : config.fecha_ingreso || '',

 /**
	* id_sucursal
	* 
	* sucursal en la que se hizo el gasto
	* @access private
	* @var int(11)
	*/
	_id_sucursal = config === undefined ? '' : config.id_sucursal || '',

 /**
	* id_usuario
	* 
	* usuario que registro el gasto
	* @access private
	* @var int(11)
	*/
	_id_usuario = config === undefined ? '' : config.id_usuario || '',

 /**
	* nota
	* 
	* nota adicional para complementar la descripcion del gasto
	* @access private
	* @var varchar(512)
	*/
	_nota = config === undefined ? '' : config.nota || '';

	/**
	  * getIdGasto
	  * 
	  * Get the <i>id_gasto</i> property for this object. Donde <i>id_gasto</i> es id para identificar el gasto
	  * @return int(11)
	  */
	this.getIdGasto = function ()
	{
		return _id_gasto;
	};

	/**
	  * setIdGasto( $id_gasto )
	  * 
	  * Set the <i>id_gasto</i> property for this object. Donde <i>id_gasto</i> es id para identificar el gasto.
	  * Una validacion basica se hara aqui para comprobar que <i>id_gasto</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdGasto( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdGasto  = function ( id_gasto )
	{
		_id_gasto = id_gasto;
	};

	/**
	  * getFolio
	  * 
	  * Get the <i>folio</i> property for this object. Donde <i>folio</i> es El folio de la factura para este gasto
	  * @return varchar(22)
	  */
	this.getFolio = function ()
	{
		return _folio;
	};

	/**
	  * setFolio( $folio )
	  * 
	  * Set the <i>folio</i> property for this object. Donde <i>folio</i> es El folio de la factura para este gasto.
	  * Una validacion basica se hara aqui para comprobar que <i>folio</i> es de tipo <i>varchar(22)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(22)
	  */
	this.setFolio  = function ( folio )
	{
		_folio = folio;
	};

	/**
	  * getConcepto
	  * 
	  * Get the <i>concepto</i> property for this object. Donde <i>concepto</i> es concepto en lo que se gasto
	  * @return varchar(100)
	  */
	this.getConcepto = function ()
	{
		return _concepto;
	};

	/**
	  * setConcepto( $concepto )
	  * 
	  * Set the <i>concepto</i> property for this object. Donde <i>concepto</i> es concepto en lo que se gasto.
	  * Una validacion basica se hara aqui para comprobar que <i>concepto</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	this.setConcepto  = function ( concepto )
	{
		_concepto = concepto;
	};

	/**
	  * getMonto
	  * 
	  * Get the <i>monto</i> property for this object. Donde <i>monto</i> es lo que costo este gasto
	  * @return float
	  */
	this.getMonto = function ()
	{
		return _monto;
	};

	/**
	  * setMonto( $monto )
	  * 
	  * Set the <i>monto</i> property for this object. Donde <i>monto</i> es lo que costo este gasto.
	  * Una validacion basica se hara aqui para comprobar que <i>monto</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setMonto  = function ( monto )
	{
		_monto = monto;
	};

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha del gasto
	  * @return timestamp
	  */
	this.getFecha = function ()
	{
		return _fecha;
	};

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha del gasto.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFecha  = function ( fecha )
	{
		_fecha = fecha;
	};

	/**
	  * getFechaIngreso
	  * 
	  * Get the <i>fecha_ingreso</i> property for this object. Donde <i>fecha_ingreso</i> es Fecha que selecciono el empleado en el sistema
	  * @return timestamp
	  */
	this.getFechaIngreso = function ()
	{
		return _fecha_ingreso;
	};

	/**
	  * setFechaIngreso( $fecha_ingreso )
	  * 
	  * Set the <i>fecha_ingreso</i> property for this object. Donde <i>fecha_ingreso</i> es Fecha que selecciono el empleado en el sistema.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_ingreso</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFechaIngreso  = function ( fecha_ingreso )
	{
		_fecha_ingreso = fecha_ingreso;
	};

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal en la que se hizo el gasto
	  * @return int(11)
	  */
	this.getIdSucursal = function ()
	{
		return _id_sucursal;
	};

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal en la que se hizo el gasto.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdSucursal  = function ( id_sucursal )
	{
		_id_sucursal = id_sucursal;
	};

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es usuario que registro el gasto
	  * @return int(11)
	  */
	this.getIdUsuario = function ()
	{
		return _id_usuario;
	};

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es usuario que registro el gasto.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdUsuario  = function ( id_usuario )
	{
		_id_usuario = id_usuario;
	};

	/**
	  * getNota
	  * 
	  * Get the <i>nota</i> property for this object. Donde <i>nota</i> es nota adicional para complementar la descripcion del gasto
	  * @return varchar(512)
	  */
	this.getNota = function ()
	{
		return _nota;
	};

	/**
	  * setNota( $nota )
	  * 
	  * Set the <i>nota</i> property for this object. Donde <i>nota</i> es nota adicional para complementar la descripcion del gasto.
	  * Una validacion basica se hara aqui para comprobar que <i>nota</i> es de tipo <i>varchar(512)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(512)
	  */
	this.setNota  = function ( nota )
	{
		_nota = nota;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Gastos} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		Gastos._callback_stack.push( _original_callback  );
		Gastos._callback_stack.push( function(res){ 
						console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create.call(this, this);
						}else{
							update.call(res, res);
						}
	 			});
		Gastos.getByPK(  this.getIdGasto() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Gastos} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param Gastos [$gastos] El objeto de tipo Gastos
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from gastos WHERE ("; 
		$val = [];
		if( this.getIdGasto() != null){
			$sql += " id_gasto = ? AND";
			$val.push( this.getIdGasto() );
		}

		if( this.getFolio() != null){
			$sql += " folio = ? AND";
			$val.push( this.getFolio() );
		}

		if( this.getConcepto() != null){
			$sql += " concepto = ? AND";
			$val.push( this.getConcepto() );
		}

		if( this.getMonto() != null){
			$sql += " monto = ? AND";
			$val.push( this.getMonto() );
		}

		if( this.getFecha() != null){
			$sql += " fecha = ? AND";
			$val.push( this.getFecha() );
		}

		if( this.getFechaIngreso() != null){
			$sql += " fecha_ingreso = ? AND";
			$val.push( this.getFechaIngreso() );
		}

		if( this.getIdSucursal() != null){
			$sql += " id_sucursal = ? AND";
			$val.push( this.getIdSucursal() );
		}

		if( this.getIdUsuario() != null){
			$sql += " id_usuario = ? AND";
			$val.push( this.getIdUsuario() );
		}

		if( this.getNota() != null){
			$sql += " nota = ? AND";
			$val.push( this.getNota() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Gastos($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Gastos suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Gastos dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Gastos [$gastos] El objeto de tipo Gastos a crear.
	  **/
	var create = function( gastos )
	{
		console.log('estoy en create(this)');
		$sql = "INSERT INTO gastos ( id_gasto, folio, concepto, monto, fecha, fecha_ingreso, id_sucursal, id_usuario, nota ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			gastos.getIdGasto(), 
			gastos.getFolio(), 
			gastos.getConcepto(), 
			gastos.getMonto(), 
			gastos.getFecha(), 
			gastos.getFechaIngreso(), 
			gastos.getIdSucursal(), 
			gastos.getIdUsuario(), 
			gastos.getNota(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				Gastos._callback_stack.pop().call(null, gastos);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param Gastos [$gastos] El objeto de tipo Gastos a actualizar.
	  **/
	var update = function( $gastos )
	{
		$sql = "UPDATE gastos SET  folio = ?, concepto = ?, monto = ?, fecha = ?, fecha_ingreso = ?, id_sucursal = ?, id_usuario = ?, nota = ? WHERE  id_gasto = ?;";
		$params = [ 
			$gastos.getFolio(), 
			$gastos.getConcepto(), 
			$gastos.getMonto(), 
			$gastos.getFecha(), 
			$gastos.getFechaIngreso(), 
			$gastos.getIdSucursal(), 
			$gastos.getIdUsuario(), 
			$gastos.getNota(), 
			$gastos.getIdGasto(),  ] ;
		console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				Gastos._callback_stack.pop().call(null, $gastos);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Gastos suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( Gastos.getByPK(this.getIdGasto()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM gastos WHERE  id_gasto = ?;";
		$params = [ this.getIdGasto() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Gastos} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Gastos}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param Gastos [$gastos] El objeto de tipo Gastos
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $gastos , $orderBy , $orden )
	{
		$sql = "SELECT * from gastos WHERE ("; 
		$val = [];
		if( (($a = this.getIdGasto()) != null) & ( ($b = $gastos.getIdGasto()) != null) ){
				$sql += " id_gasto >= ? AND id_gasto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_gasto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFolio()) != null) & ( ($b = $gastos.getFolio()) != null) ){
				$sql += " folio >= ? AND folio <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " folio = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getConcepto()) != null) & ( ($b = $gastos.getConcepto()) != null) ){
				$sql += " concepto >= ? AND concepto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " concepto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getMonto()) != null) & ( ($b = $gastos.getMonto()) != null) ){
				$sql += " monto >= ? AND monto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " monto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFecha()) != null) & ( ($b = $gastos.getFecha()) != null) ){
				$sql += " fecha >= ? AND fecha <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFechaIngreso()) != null) & ( ($b = $gastos.getFechaIngreso()) != null) ){
				$sql += " fecha_ingreso >= ? AND fecha_ingreso <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha_ingreso = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdSucursal()) != null) & ( ($b = $gastos.getIdSucursal()) != null) ){
				$sql += " id_sucursal >= ? AND id_sucursal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_sucursal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdUsuario()) != null) & ( ($b = $gastos.getIdUsuario()) != null) ){
				$sql += " id_usuario >= ? AND id_usuario <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_usuario = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getNota()) != null) & ( ($b = $gastos.getNota()) != null) ){
				$sql += " nota >= ? AND nota <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " nota = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Gastos($foo));
		//}
		return $sql;
	};


}
	Gastos._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Gastos}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	Gastos.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from gastos";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new Gastos( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link Gastos} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Gastos} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Gastos Un objeto del tipo {@link Gastos}. NULL si no hay tal registro.
	  **/
	Gastos.getByPK = function(  $id_gasto, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM gastos WHERE (id_gasto = ? ) LIMIT 1;";
		db.query($sql, [ $id_gasto] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new Gastos(results.rows.item(0)); 
				Gastos._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : Gastos._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table impresora.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var Impresora = function ( config )
{
 /**
	* id_impresora
	* 
	* id de la impresora
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_impresora = config === undefined ? '' : config.id_impresora || '',

 /**
	* id_sucursal
	* 
	* id de la sucursal donde se encuentra esta impresora
	* @access private
	* @var int(11)
	*/
	_id_sucursal = config === undefined ? '' : config.id_sucursal || '',

 /**
	* descripcion
	* 
	* descripcion breve de la impresora
	* @access private
	* @var varchar(256)
	*/
	_descripcion = config === undefined ? '' : config.descripcion || '',

 /**
	* identificador
	* 
	* es el nombre de como esta dada de alta la impresora en la sucursal
	* @access private
	* @var varchar(128)
	*/
	_identificador = config === undefined ? '' : config.identificador || '';

	/**
	  * getIdImpresora
	  * 
	  * Get the <i>id_impresora</i> property for this object. Donde <i>id_impresora</i> es id de la impresora
	  * @return int(11)
	  */
	this.getIdImpresora = function ()
	{
		return _id_impresora;
	};

	/**
	  * setIdImpresora( $id_impresora )
	  * 
	  * Set the <i>id_impresora</i> property for this object. Donde <i>id_impresora</i> es id de la impresora.
	  * Una validacion basica se hara aqui para comprobar que <i>id_impresora</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdImpresora( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdImpresora  = function ( id_impresora )
	{
		_id_impresora = id_impresora;
	};

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es id de la sucursal donde se encuentra esta impresora
	  * @return int(11)
	  */
	this.getIdSucursal = function ()
	{
		return _id_sucursal;
	};

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es id de la sucursal donde se encuentra esta impresora.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdSucursal  = function ( id_sucursal )
	{
		_id_sucursal = id_sucursal;
	};

	/**
	  * getDescripcion
	  * 
	  * Get the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es descripcion breve de la impresora
	  * @return varchar(256)
	  */
	this.getDescripcion = function ()
	{
		return _descripcion;
	};

	/**
	  * setDescripcion( $descripcion )
	  * 
	  * Set the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es descripcion breve de la impresora.
	  * Una validacion basica se hara aqui para comprobar que <i>descripcion</i> es de tipo <i>varchar(256)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(256)
	  */
	this.setDescripcion  = function ( descripcion )
	{
		_descripcion = descripcion;
	};

	/**
	  * getIdentificador
	  * 
	  * Get the <i>identificador</i> property for this object. Donde <i>identificador</i> es es el nombre de como esta dada de alta la impresora en la sucursal
	  * @return varchar(128)
	  */
	this.getIdentificador = function ()
	{
		return _identificador;
	};

	/**
	  * setIdentificador( $identificador )
	  * 
	  * Set the <i>identificador</i> property for this object. Donde <i>identificador</i> es es el nombre de como esta dada de alta la impresora en la sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>identificador</i> es de tipo <i>varchar(128)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(128)
	  */
	this.setIdentificador  = function ( identificador )
	{
		_identificador = identificador;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Impresora} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		Impresora._callback_stack.push( _original_callback  );
		Impresora._callback_stack.push( function(res){ 
						console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create.call(this, this);
						}else{
							update.call(res, res);
						}
	 			});
		Impresora.getByPK(  this.getIdImpresora() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Impresora} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param Impresora [$impresora] El objeto de tipo Impresora
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from impresora WHERE ("; 
		$val = [];
		if( this.getIdImpresora() != null){
			$sql += " id_impresora = ? AND";
			$val.push( this.getIdImpresora() );
		}

		if( this.getIdSucursal() != null){
			$sql += " id_sucursal = ? AND";
			$val.push( this.getIdSucursal() );
		}

		if( this.getDescripcion() != null){
			$sql += " descripcion = ? AND";
			$val.push( this.getDescripcion() );
		}

		if( this.getIdentificador() != null){
			$sql += " identificador = ? AND";
			$val.push( this.getIdentificador() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Impresora($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Impresora suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Impresora dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Impresora [$impresora] El objeto de tipo Impresora a crear.
	  **/
	var create = function( impresora )
	{
		console.log('estoy en create(this)');
		$sql = "INSERT INTO impresora ( id_impresora, id_sucursal, descripcion, identificador ) VALUES ( ?, ?, ?, ?);";
		$params = [
			impresora.getIdImpresora(), 
			impresora.getIdSucursal(), 
			impresora.getDescripcion(), 
			impresora.getIdentificador(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				Impresora._callback_stack.pop().call(null, impresora);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param Impresora [$impresora] El objeto de tipo Impresora a actualizar.
	  **/
	var update = function( $impresora )
	{
		$sql = "UPDATE impresora SET  id_sucursal = ?, descripcion = ?, identificador = ? WHERE  id_impresora = ?;";
		$params = [ 
			$impresora.getIdSucursal(), 
			$impresora.getDescripcion(), 
			$impresora.getIdentificador(), 
			$impresora.getIdImpresora(),  ] ;
		console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				Impresora._callback_stack.pop().call(null, $impresora);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Impresora suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( Impresora.getByPK(this.getIdImpresora()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM impresora WHERE  id_impresora = ?;";
		$params = [ this.getIdImpresora() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Impresora} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Impresora}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param Impresora [$impresora] El objeto de tipo Impresora
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $impresora , $orderBy , $orden )
	{
		$sql = "SELECT * from impresora WHERE ("; 
		$val = [];
		if( (($a = this.getIdImpresora()) != null) & ( ($b = $impresora.getIdImpresora()) != null) ){
				$sql += " id_impresora >= ? AND id_impresora <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_impresora = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdSucursal()) != null) & ( ($b = $impresora.getIdSucursal()) != null) ){
				$sql += " id_sucursal >= ? AND id_sucursal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_sucursal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getDescripcion()) != null) & ( ($b = $impresora.getDescripcion()) != null) ){
				$sql += " descripcion >= ? AND descripcion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " descripcion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdentificador()) != null) & ( ($b = $impresora.getIdentificador()) != null) ){
				$sql += " identificador >= ? AND identificador <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " identificador = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Impresora($foo));
		//}
		return $sql;
	};


}
	Impresora._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Impresora}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	Impresora.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from impresora";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new Impresora( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link Impresora} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Impresora} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Impresora Un objeto del tipo {@link Impresora}. NULL si no hay tal registro.
	  **/
	Impresora.getByPK = function(  $id_impresora, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM impresora WHERE (id_impresora = ? ) LIMIT 1;";
		db.query($sql, [ $id_impresora] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new Impresora(results.rows.item(0)); 
				Impresora._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : Impresora._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table ingresos.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var Ingresos = function ( config )
{
 /**
	* id_ingreso
	* 
	* id para identificar el ingreso
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_ingreso = config === undefined ? '' : config.id_ingreso || '',

 /**
	* concepto
	* 
	* concepto en lo que se ingreso
	* @access private
	* @var varchar(100)
	*/
	_concepto = config === undefined ? '' : config.concepto || '',

 /**
	* monto
	* 
	* lo que costo este ingreso
	* @access private
	* @var float
	*/
	_monto = config === undefined ? '' : config.monto || '',

 /**
	* fecha
	* 
	* fecha del ingreso
	* @access private
	* @var timestamp
	*/
	_fecha = config === undefined ? '' : config.fecha || '',

 /**
	* fecha_ingreso
	* 
	* Fecha que selecciono el empleado en el sistema
	* @access private
	* @var timestamp
	*/
	_fecha_ingreso = config === undefined ? '' : config.fecha_ingreso || '',

 /**
	* id_sucursal
	* 
	* sucursal en la que se hizo el ingreso
	* @access private
	* @var int(11)
	*/
	_id_sucursal = config === undefined ? '' : config.id_sucursal || '',

 /**
	* id_usuario
	* 
	* usuario que registro el ingreso
	* @access private
	* @var int(11)
	*/
	_id_usuario = config === undefined ? '' : config.id_usuario || '',

 /**
	* nota
	* 
	* nota adicional para complementar la descripcion del ingreso
	* @access private
	* @var varchar(512)
	*/
	_nota = config === undefined ? '' : config.nota || '';

	/**
	  * getIdIngreso
	  * 
	  * Get the <i>id_ingreso</i> property for this object. Donde <i>id_ingreso</i> es id para identificar el ingreso
	  * @return int(11)
	  */
	this.getIdIngreso = function ()
	{
		return _id_ingreso;
	};

	/**
	  * setIdIngreso( $id_ingreso )
	  * 
	  * Set the <i>id_ingreso</i> property for this object. Donde <i>id_ingreso</i> es id para identificar el ingreso.
	  * Una validacion basica se hara aqui para comprobar que <i>id_ingreso</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdIngreso( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdIngreso  = function ( id_ingreso )
	{
		_id_ingreso = id_ingreso;
	};

	/**
	  * getConcepto
	  * 
	  * Get the <i>concepto</i> property for this object. Donde <i>concepto</i> es concepto en lo que se ingreso
	  * @return varchar(100)
	  */
	this.getConcepto = function ()
	{
		return _concepto;
	};

	/**
	  * setConcepto( $concepto )
	  * 
	  * Set the <i>concepto</i> property for this object. Donde <i>concepto</i> es concepto en lo que se ingreso.
	  * Una validacion basica se hara aqui para comprobar que <i>concepto</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	this.setConcepto  = function ( concepto )
	{
		_concepto = concepto;
	};

	/**
	  * getMonto
	  * 
	  * Get the <i>monto</i> property for this object. Donde <i>monto</i> es lo que costo este ingreso
	  * @return float
	  */
	this.getMonto = function ()
	{
		return _monto;
	};

	/**
	  * setMonto( $monto )
	  * 
	  * Set the <i>monto</i> property for this object. Donde <i>monto</i> es lo que costo este ingreso.
	  * Una validacion basica se hara aqui para comprobar que <i>monto</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setMonto  = function ( monto )
	{
		_monto = monto;
	};

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha del ingreso
	  * @return timestamp
	  */
	this.getFecha = function ()
	{
		return _fecha;
	};

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha del ingreso.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFecha  = function ( fecha )
	{
		_fecha = fecha;
	};

	/**
	  * getFechaIngreso
	  * 
	  * Get the <i>fecha_ingreso</i> property for this object. Donde <i>fecha_ingreso</i> es Fecha que selecciono el empleado en el sistema
	  * @return timestamp
	  */
	this.getFechaIngreso = function ()
	{
		return _fecha_ingreso;
	};

	/**
	  * setFechaIngreso( $fecha_ingreso )
	  * 
	  * Set the <i>fecha_ingreso</i> property for this object. Donde <i>fecha_ingreso</i> es Fecha que selecciono el empleado en el sistema.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_ingreso</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFechaIngreso  = function ( fecha_ingreso )
	{
		_fecha_ingreso = fecha_ingreso;
	};

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal en la que se hizo el ingreso
	  * @return int(11)
	  */
	this.getIdSucursal = function ()
	{
		return _id_sucursal;
	};

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal en la que se hizo el ingreso.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdSucursal  = function ( id_sucursal )
	{
		_id_sucursal = id_sucursal;
	};

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es usuario que registro el ingreso
	  * @return int(11)
	  */
	this.getIdUsuario = function ()
	{
		return _id_usuario;
	};

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es usuario que registro el ingreso.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdUsuario  = function ( id_usuario )
	{
		_id_usuario = id_usuario;
	};

	/**
	  * getNota
	  * 
	  * Get the <i>nota</i> property for this object. Donde <i>nota</i> es nota adicional para complementar la descripcion del ingreso
	  * @return varchar(512)
	  */
	this.getNota = function ()
	{
		return _nota;
	};

	/**
	  * setNota( $nota )
	  * 
	  * Set the <i>nota</i> property for this object. Donde <i>nota</i> es nota adicional para complementar la descripcion del ingreso.
	  * Una validacion basica se hara aqui para comprobar que <i>nota</i> es de tipo <i>varchar(512)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(512)
	  */
	this.setNota  = function ( nota )
	{
		_nota = nota;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Ingresos} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		Ingresos._callback_stack.push( _original_callback  );
		Ingresos._callback_stack.push( function(res){ 
						console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create.call(this, this);
						}else{
							update.call(res, res);
						}
	 			});
		Ingresos.getByPK(  this.getIdIngreso() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Ingresos} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param Ingresos [$ingresos] El objeto de tipo Ingresos
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from ingresos WHERE ("; 
		$val = [];
		if( this.getIdIngreso() != null){
			$sql += " id_ingreso = ? AND";
			$val.push( this.getIdIngreso() );
		}

		if( this.getConcepto() != null){
			$sql += " concepto = ? AND";
			$val.push( this.getConcepto() );
		}

		if( this.getMonto() != null){
			$sql += " monto = ? AND";
			$val.push( this.getMonto() );
		}

		if( this.getFecha() != null){
			$sql += " fecha = ? AND";
			$val.push( this.getFecha() );
		}

		if( this.getFechaIngreso() != null){
			$sql += " fecha_ingreso = ? AND";
			$val.push( this.getFechaIngreso() );
		}

		if( this.getIdSucursal() != null){
			$sql += " id_sucursal = ? AND";
			$val.push( this.getIdSucursal() );
		}

		if( this.getIdUsuario() != null){
			$sql += " id_usuario = ? AND";
			$val.push( this.getIdUsuario() );
		}

		if( this.getNota() != null){
			$sql += " nota = ? AND";
			$val.push( this.getNota() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Ingresos($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Ingresos suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Ingresos dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Ingresos [$ingresos] El objeto de tipo Ingresos a crear.
	  **/
	var create = function( ingresos )
	{
		console.log('estoy en create(this)');
		$sql = "INSERT INTO ingresos ( id_ingreso, concepto, monto, fecha, fecha_ingreso, id_sucursal, id_usuario, nota ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			ingresos.getIdIngreso(), 
			ingresos.getConcepto(), 
			ingresos.getMonto(), 
			ingresos.getFecha(), 
			ingresos.getFechaIngreso(), 
			ingresos.getIdSucursal(), 
			ingresos.getIdUsuario(), 
			ingresos.getNota(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				Ingresos._callback_stack.pop().call(null, ingresos);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param Ingresos [$ingresos] El objeto de tipo Ingresos a actualizar.
	  **/
	var update = function( $ingresos )
	{
		$sql = "UPDATE ingresos SET  concepto = ?, monto = ?, fecha = ?, fecha_ingreso = ?, id_sucursal = ?, id_usuario = ?, nota = ? WHERE  id_ingreso = ?;";
		$params = [ 
			$ingresos.getConcepto(), 
			$ingresos.getMonto(), 
			$ingresos.getFecha(), 
			$ingresos.getFechaIngreso(), 
			$ingresos.getIdSucursal(), 
			$ingresos.getIdUsuario(), 
			$ingresos.getNota(), 
			$ingresos.getIdIngreso(),  ] ;
		console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				Ingresos._callback_stack.pop().call(null, $ingresos);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Ingresos suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( Ingresos.getByPK(this.getIdIngreso()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM ingresos WHERE  id_ingreso = ?;";
		$params = [ this.getIdIngreso() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Ingresos} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Ingresos}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param Ingresos [$ingresos] El objeto de tipo Ingresos
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $ingresos , $orderBy , $orden )
	{
		$sql = "SELECT * from ingresos WHERE ("; 
		$val = [];
		if( (($a = this.getIdIngreso()) != null) & ( ($b = $ingresos.getIdIngreso()) != null) ){
				$sql += " id_ingreso >= ? AND id_ingreso <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_ingreso = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getConcepto()) != null) & ( ($b = $ingresos.getConcepto()) != null) ){
				$sql += " concepto >= ? AND concepto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " concepto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getMonto()) != null) & ( ($b = $ingresos.getMonto()) != null) ){
				$sql += " monto >= ? AND monto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " monto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFecha()) != null) & ( ($b = $ingresos.getFecha()) != null) ){
				$sql += " fecha >= ? AND fecha <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFechaIngreso()) != null) & ( ($b = $ingresos.getFechaIngreso()) != null) ){
				$sql += " fecha_ingreso >= ? AND fecha_ingreso <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha_ingreso = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdSucursal()) != null) & ( ($b = $ingresos.getIdSucursal()) != null) ){
				$sql += " id_sucursal >= ? AND id_sucursal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_sucursal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdUsuario()) != null) & ( ($b = $ingresos.getIdUsuario()) != null) ){
				$sql += " id_usuario >= ? AND id_usuario <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_usuario = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getNota()) != null) & ( ($b = $ingresos.getNota()) != null) ){
				$sql += " nota >= ? AND nota <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " nota = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Ingresos($foo));
		//}
		return $sql;
	};


}
	Ingresos._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Ingresos}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	Ingresos.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from ingresos";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new Ingresos( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link Ingresos} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Ingresos} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Ingresos Un objeto del tipo {@link Ingresos}. NULL si no hay tal registro.
	  **/
	Ingresos.getByPK = function(  $id_ingreso, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM ingresos WHERE (id_ingreso = ? ) LIMIT 1;";
		db.query($sql, [ $id_ingreso] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new Ingresos(results.rows.item(0)); 
				Ingresos._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : Ingresos._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table inventario.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var Inventario = function ( config )
{
 /**
	* id_producto
	* 
	* id del producto
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_producto = config === undefined ? '' : config.id_producto || '',

 /**
	* descripcion
	* 
	* descripcion del producto
	* @access private
	* @var varchar(30)
	*/
	_descripcion = config === undefined ? '' : config.descripcion || '',

 /**
	* escala
	* 
	*  [Campo no documentado]
	* @access private
	* @var enum('kilogramo','pieza','litro','unidad')
	*/
	_escala = config === undefined ? '' : config.escala || '',

 /**
	* tratamiento
	* 
	* Tipo de tratatiento si es que existe para este producto.
	* @access private
	* @var enum('limpia')
	*/
	_tratamiento = config === undefined ? '' : config.tratamiento || '',

 /**
	* agrupacion
	* 
	* La agrupacion de este producto
	* @access private
	* @var varchar(8)
	*/
	_agrupacion = config === undefined ? '' : config.agrupacion || '',

 /**
	* agrupacionTam
	* 
	* El tamano de cada agrupacion
	* @access private
	* @var float
	*/
	_agrupacionTam = config === undefined ? '' : config.agrupacionTam || '',

 /**
	* activo
	* 
	* si este producto esta activo o no en el sistema
	* @access private
	* @var tinyint(1)
	*/
	_activo = config === undefined ? '' : config.activo || '',

 /**
	* precio_por_agrupacion
	* 
	*  [Campo no documentado]
	* @access private
	* @var tinyint(1)
	*/
	_precio_por_agrupacion = config === undefined ? '' : config.precio_por_agrupacion || '';

	/**
	  * getIdProducto
	  * 
	  * Get the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es id del producto
	  * @return int(11)
	  */
	this.getIdProducto = function ()
	{
		return _id_producto;
	};

	/**
	  * setIdProducto( $id_producto )
	  * 
	  * Set the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es id del producto.
	  * Una validacion basica se hara aqui para comprobar que <i>id_producto</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdProducto( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdProducto  = function ( id_producto )
	{
		_id_producto = id_producto;
	};

	/**
	  * getDescripcion
	  * 
	  * Get the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es descripcion del producto
	  * @return varchar(30)
	  */
	this.getDescripcion = function ()
	{
		return _descripcion;
	};

	/**
	  * setDescripcion( $descripcion )
	  * 
	  * Set the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es descripcion del producto.
	  * Una validacion basica se hara aqui para comprobar que <i>descripcion</i> es de tipo <i>varchar(30)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(30)
	  */
	this.setDescripcion  = function ( descripcion )
	{
		_descripcion = descripcion;
	};

	/**
	  * getEscala
	  * 
	  * Get the <i>escala</i> property for this object. Donde <i>escala</i> es  [Campo no documentado]
	  * @return enum('kilogramo','pieza','litro','unidad')
	  */
	this.getEscala = function ()
	{
		return _escala;
	};

	/**
	  * setEscala( $escala )
	  * 
	  * Set the <i>escala</i> property for this object. Donde <i>escala</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>escala</i> es de tipo <i>enum('kilogramo','pieza','litro','unidad')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('kilogramo','pieza','litro','unidad')
	  */
	this.setEscala  = function ( escala )
	{
		_escala = escala;
	};

	/**
	  * getTratamiento
	  * 
	  * Get the <i>tratamiento</i> property for this object. Donde <i>tratamiento</i> es Tipo de tratatiento si es que existe para este producto.
	  * @return enum('limpia')
	  */
	this.getTratamiento = function ()
	{
		return _tratamiento;
	};

	/**
	  * setTratamiento( $tratamiento )
	  * 
	  * Set the <i>tratamiento</i> property for this object. Donde <i>tratamiento</i> es Tipo de tratatiento si es que existe para este producto..
	  * Una validacion basica se hara aqui para comprobar que <i>tratamiento</i> es de tipo <i>enum('limpia')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('limpia')
	  */
	this.setTratamiento  = function ( tratamiento )
	{
		_tratamiento = tratamiento;
	};

	/**
	  * getAgrupacion
	  * 
	  * Get the <i>agrupacion</i> property for this object. Donde <i>agrupacion</i> es La agrupacion de este producto
	  * @return varchar(8)
	  */
	this.getAgrupacion = function ()
	{
		return _agrupacion;
	};

	/**
	  * setAgrupacion( $agrupacion )
	  * 
	  * Set the <i>agrupacion</i> property for this object. Donde <i>agrupacion</i> es La agrupacion de este producto.
	  * Una validacion basica se hara aqui para comprobar que <i>agrupacion</i> es de tipo <i>varchar(8)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(8)
	  */
	this.setAgrupacion  = function ( agrupacion )
	{
		_agrupacion = agrupacion;
	};

	/**
	  * getAgrupacionTam
	  * 
	  * Get the <i>agrupacionTam</i> property for this object. Donde <i>agrupacionTam</i> es El tamano de cada agrupacion
	  * @return float
	  */
	this.getAgrupacionTam = function ()
	{
		return _agrupacionTam;
	};

	/**
	  * setAgrupacionTam( $agrupacionTam )
	  * 
	  * Set the <i>agrupacionTam</i> property for this object. Donde <i>agrupacionTam</i> es El tamano de cada agrupacion.
	  * Una validacion basica se hara aqui para comprobar que <i>agrupacionTam</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setAgrupacionTam  = function ( agrupacionTam )
	{
		_agrupacionTam = agrupacionTam;
	};

	/**
	  * getActivo
	  * 
	  * Get the <i>activo</i> property for this object. Donde <i>activo</i> es si este producto esta activo o no en el sistema
	  * @return tinyint(1)
	  */
	this.getActivo = function ()
	{
		return _activo;
	};

	/**
	  * setActivo( $activo )
	  * 
	  * Set the <i>activo</i> property for this object. Donde <i>activo</i> es si este producto esta activo o no en el sistema.
	  * Una validacion basica se hara aqui para comprobar que <i>activo</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	this.setActivo  = function ( activo )
	{
		_activo = activo;
	};

	/**
	  * getPrecioPorAgrupacion
	  * 
	  * Get the <i>precio_por_agrupacion</i> property for this object. Donde <i>precio_por_agrupacion</i> es  [Campo no documentado]
	  * @return tinyint(1)
	  */
	this.getPrecioPorAgrupacion = function ()
	{
		return _precio_por_agrupacion;
	};

	/**
	  * setPrecioPorAgrupacion( $precio_por_agrupacion )
	  * 
	  * Set the <i>precio_por_agrupacion</i> property for this object. Donde <i>precio_por_agrupacion</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>precio_por_agrupacion</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	this.setPrecioPorAgrupacion  = function ( precio_por_agrupacion )
	{
		_precio_por_agrupacion = precio_por_agrupacion;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Inventario} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		Inventario._callback_stack.push( _original_callback  );
		Inventario._callback_stack.push( function(res){ 
						console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create.call(this, this);
						}else{
							update.call(res, res);
						}
	 			});
		Inventario.getByPK(  this.getIdProducto() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Inventario} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param Inventario [$inventario] El objeto de tipo Inventario
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from inventario WHERE ("; 
		$val = [];
		if( this.getIdProducto() != null){
			$sql += " id_producto = ? AND";
			$val.push( this.getIdProducto() );
		}

		if( this.getDescripcion() != null){
			$sql += " descripcion = ? AND";
			$val.push( this.getDescripcion() );
		}

		if( this.getEscala() != null){
			$sql += " escala = ? AND";
			$val.push( this.getEscala() );
		}

		if( this.getTratamiento() != null){
			$sql += " tratamiento = ? AND";
			$val.push( this.getTratamiento() );
		}

		if( this.getAgrupacion() != null){
			$sql += " agrupacion = ? AND";
			$val.push( this.getAgrupacion() );
		}

		if( this.getAgrupacionTam() != null){
			$sql += " agrupacionTam = ? AND";
			$val.push( this.getAgrupacionTam() );
		}

		if( this.getActivo() != null){
			$sql += " activo = ? AND";
			$val.push( this.getActivo() );
		}

		if( this.getPrecioPorAgrupacion() != null){
			$sql += " precio_por_agrupacion = ? AND";
			$val.push( this.getPrecioPorAgrupacion() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Inventario($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Inventario suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Inventario dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Inventario [$inventario] El objeto de tipo Inventario a crear.
	  **/
	var create = function( inventario )
	{
		console.log('estoy en create(this)');
		$sql = "INSERT INTO inventario ( id_producto, descripcion, escala, tratamiento, agrupacion, agrupacionTam, activo, precio_por_agrupacion ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			inventario.getIdProducto(), 
			inventario.getDescripcion(), 
			inventario.getEscala(), 
			inventario.getTratamiento(), 
			inventario.getAgrupacion(), 
			inventario.getAgrupacionTam(), 
			inventario.getActivo(), 
			inventario.getPrecioPorAgrupacion(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				Inventario._callback_stack.pop().call(null, inventario);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param Inventario [$inventario] El objeto de tipo Inventario a actualizar.
	  **/
	var update = function( $inventario )
	{
		$sql = "UPDATE inventario SET  descripcion = ?, escala = ?, tratamiento = ?, agrupacion = ?, agrupacionTam = ?, activo = ?, precio_por_agrupacion = ? WHERE  id_producto = ?;";
		$params = [ 
			$inventario.getDescripcion(), 
			$inventario.getEscala(), 
			$inventario.getTratamiento(), 
			$inventario.getAgrupacion(), 
			$inventario.getAgrupacionTam(), 
			$inventario.getActivo(), 
			$inventario.getPrecioPorAgrupacion(), 
			$inventario.getIdProducto(),  ] ;
		console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				Inventario._callback_stack.pop().call(null, $inventario);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Inventario suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( Inventario.getByPK(this.getIdProducto()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM inventario WHERE  id_producto = ?;";
		$params = [ this.getIdProducto() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Inventario} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Inventario}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param Inventario [$inventario] El objeto de tipo Inventario
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $inventario , $orderBy , $orden )
	{
		$sql = "SELECT * from inventario WHERE ("; 
		$val = [];
		if( (($a = this.getIdProducto()) != null) & ( ($b = $inventario.getIdProducto()) != null) ){
				$sql += " id_producto >= ? AND id_producto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_producto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getDescripcion()) != null) & ( ($b = $inventario.getDescripcion()) != null) ){
				$sql += " descripcion >= ? AND descripcion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " descripcion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getEscala()) != null) & ( ($b = $inventario.getEscala()) != null) ){
				$sql += " escala >= ? AND escala <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " escala = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTratamiento()) != null) & ( ($b = $inventario.getTratamiento()) != null) ){
				$sql += " tratamiento >= ? AND tratamiento <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " tratamiento = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getAgrupacion()) != null) & ( ($b = $inventario.getAgrupacion()) != null) ){
				$sql += " agrupacion >= ? AND agrupacion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " agrupacion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getAgrupacionTam()) != null) & ( ($b = $inventario.getAgrupacionTam()) != null) ){
				$sql += " agrupacionTam >= ? AND agrupacionTam <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " agrupacionTam = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getActivo()) != null) & ( ($b = $inventario.getActivo()) != null) ){
				$sql += " activo >= ? AND activo <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " activo = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPrecioPorAgrupacion()) != null) & ( ($b = $inventario.getPrecioPorAgrupacion()) != null) ){
				$sql += " precio_por_agrupacion >= ? AND precio_por_agrupacion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " precio_por_agrupacion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Inventario($foo));
		//}
		return $sql;
	};


}
	Inventario._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Inventario}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	Inventario.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from inventario";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new Inventario( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link Inventario} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Inventario} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Inventario Un objeto del tipo {@link Inventario}. NULL si no hay tal registro.
	  **/
	Inventario.getByPK = function(  $id_producto, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM inventario WHERE (id_producto = ? ) LIMIT 1;";
		db.query($sql, [ $id_producto] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new Inventario(results.rows.item(0)); 
				Inventario._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : Inventario._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table pagos_venta.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var PagosVenta = function ( config )
{
 /**
	* id_pago
	* 
	* id de pago del cliente
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_pago = config === undefined ? '' : config.id_pago || '',

 /**
	* id_venta
	* 
	* id de la venta a la que se esta pagando
	* @access private
	* @var int(11)
	*/
	_id_venta = config === undefined ? '' : config.id_venta || '',

 /**
	* id_sucursal
	* 
	* Donde se realizo el pago
	* @access private
	* @var int(11)
	*/
	_id_sucursal = config === undefined ? '' : config.id_sucursal || '',

 /**
	* id_usuario
	* 
	* Quien cobro este pago
	* @access private
	* @var int(11)
	*/
	_id_usuario = config === undefined ? '' : config.id_usuario || '',

 /**
	* fecha
	* 
	* Fecha en que se registro el pago
	* @access private
	* @var timestamp
	*/
	_fecha = config === undefined ? '' : config.fecha || '',

 /**
	* monto
	* 
	* total de credito del cliente
	* @access private
	* @var float
	*/
	_monto = config === undefined ? '' : config.monto || '',

 /**
	* tipo_pago
	* 
	* tipo de pago para este abono
	* @access private
	* @var enum('efectivo','cheque','tarjeta')
	*/
	_tipo_pago = config === undefined ? '' : config.tipo_pago || '';

	/**
	  * getIdPago
	  * 
	  * Get the <i>id_pago</i> property for this object. Donde <i>id_pago</i> es id de pago del cliente
	  * @return int(11)
	  */
	this.getIdPago = function ()
	{
		return _id_pago;
	};

	/**
	  * setIdPago( $id_pago )
	  * 
	  * Set the <i>id_pago</i> property for this object. Donde <i>id_pago</i> es id de pago del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>id_pago</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdPago( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdPago  = function ( id_pago )
	{
		_id_pago = id_pago;
	};

	/**
	  * getIdVenta
	  * 
	  * Get the <i>id_venta</i> property for this object. Donde <i>id_venta</i> es id de la venta a la que se esta pagando
	  * @return int(11)
	  */
	this.getIdVenta = function ()
	{
		return _id_venta;
	};

	/**
	  * setIdVenta( $id_venta )
	  * 
	  * Set the <i>id_venta</i> property for this object. Donde <i>id_venta</i> es id de la venta a la que se esta pagando.
	  * Una validacion basica se hara aqui para comprobar que <i>id_venta</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdVenta  = function ( id_venta )
	{
		_id_venta = id_venta;
	};

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Donde se realizo el pago
	  * @return int(11)
	  */
	this.getIdSucursal = function ()
	{
		return _id_sucursal;
	};

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Donde se realizo el pago.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdSucursal  = function ( id_sucursal )
	{
		_id_sucursal = id_sucursal;
	};

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Quien cobro este pago
	  * @return int(11)
	  */
	this.getIdUsuario = function ()
	{
		return _id_usuario;
	};

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Quien cobro este pago.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdUsuario  = function ( id_usuario )
	{
		_id_usuario = id_usuario;
	};

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es Fecha en que se registro el pago
	  * @return timestamp
	  */
	this.getFecha = function ()
	{
		return _fecha;
	};

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es Fecha en que se registro el pago.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFecha  = function ( fecha )
	{
		_fecha = fecha;
	};

	/**
	  * getMonto
	  * 
	  * Get the <i>monto</i> property for this object. Donde <i>monto</i> es total de credito del cliente
	  * @return float
	  */
	this.getMonto = function ()
	{
		return _monto;
	};

	/**
	  * setMonto( $monto )
	  * 
	  * Set the <i>monto</i> property for this object. Donde <i>monto</i> es total de credito del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>monto</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setMonto  = function ( monto )
	{
		_monto = monto;
	};

	/**
	  * getTipoPago
	  * 
	  * Get the <i>tipo_pago</i> property for this object. Donde <i>tipo_pago</i> es tipo de pago para este abono
	  * @return enum('efectivo','cheque','tarjeta')
	  */
	this.getTipoPago = function ()
	{
		return _tipo_pago;
	};

	/**
	  * setTipoPago( $tipo_pago )
	  * 
	  * Set the <i>tipo_pago</i> property for this object. Donde <i>tipo_pago</i> es tipo de pago para este abono.
	  * Una validacion basica se hara aqui para comprobar que <i>tipo_pago</i> es de tipo <i>enum('efectivo','cheque','tarjeta')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('efectivo','cheque','tarjeta')
	  */
	this.setTipoPago  = function ( tipo_pago )
	{
		_tipo_pago = tipo_pago;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link PagosVenta} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		PagosVenta._callback_stack.push( _original_callback  );
		PagosVenta._callback_stack.push( function(res){ 
						console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create.call(this, this);
						}else{
							update.call(res, res);
						}
	 			});
		PagosVenta.getByPK(  this.getIdPago() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link PagosVenta} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param PagosVenta [$pagos_venta] El objeto de tipo PagosVenta
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from pagos_venta WHERE ("; 
		$val = [];
		if( this.getIdPago() != null){
			$sql += " id_pago = ? AND";
			$val.push( this.getIdPago() );
		}

		if( this.getIdVenta() != null){
			$sql += " id_venta = ? AND";
			$val.push( this.getIdVenta() );
		}

		if( this.getIdSucursal() != null){
			$sql += " id_sucursal = ? AND";
			$val.push( this.getIdSucursal() );
		}

		if( this.getIdUsuario() != null){
			$sql += " id_usuario = ? AND";
			$val.push( this.getIdUsuario() );
		}

		if( this.getFecha() != null){
			$sql += " fecha = ? AND";
			$val.push( this.getFecha() );
		}

		if( this.getMonto() != null){
			$sql += " monto = ? AND";
			$val.push( this.getMonto() );
		}

		if( this.getTipoPago() != null){
			$sql += " tipo_pago = ? AND";
			$val.push( this.getTipoPago() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new PagosVenta($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto PagosVenta suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto PagosVenta dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param PagosVenta [$pagos_venta] El objeto de tipo PagosVenta a crear.
	  **/
	var create = function( pagos_venta )
	{
		console.log('estoy en create(this)');
		$sql = "INSERT INTO pagos_venta ( id_pago, id_venta, id_sucursal, id_usuario, fecha, monto, tipo_pago ) VALUES ( ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			pagos_venta.getIdPago(), 
			pagos_venta.getIdVenta(), 
			pagos_venta.getIdSucursal(), 
			pagos_venta.getIdUsuario(), 
			pagos_venta.getFecha(), 
			pagos_venta.getMonto(), 
			pagos_venta.getTipoPago(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				PagosVenta._callback_stack.pop().call(null, pagos_venta);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param PagosVenta [$pagos_venta] El objeto de tipo PagosVenta a actualizar.
	  **/
	var update = function( $pagos_venta )
	{
		$sql = "UPDATE pagos_venta SET  id_venta = ?, id_sucursal = ?, id_usuario = ?, fecha = ?, monto = ?, tipo_pago = ? WHERE  id_pago = ?;";
		$params = [ 
			$pagos_venta.getIdVenta(), 
			$pagos_venta.getIdSucursal(), 
			$pagos_venta.getIdUsuario(), 
			$pagos_venta.getFecha(), 
			$pagos_venta.getMonto(), 
			$pagos_venta.getTipoPago(), 
			$pagos_venta.getIdPago(),  ] ;
		console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				PagosVenta._callback_stack.pop().call(null, $pagos_venta);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto PagosVenta suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( PagosVenta.getByPK(this.getIdPago()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM pagos_venta WHERE  id_pago = ?;";
		$params = [ this.getIdPago() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link PagosVenta} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link PagosVenta}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param PagosVenta [$pagos_venta] El objeto de tipo PagosVenta
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $pagos_venta , $orderBy , $orden )
	{
		$sql = "SELECT * from pagos_venta WHERE ("; 
		$val = [];
		if( (($a = this.getIdPago()) != null) & ( ($b = $pagos_venta.getIdPago()) != null) ){
				$sql += " id_pago >= ? AND id_pago <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_pago = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdVenta()) != null) & ( ($b = $pagos_venta.getIdVenta()) != null) ){
				$sql += " id_venta >= ? AND id_venta <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_venta = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdSucursal()) != null) & ( ($b = $pagos_venta.getIdSucursal()) != null) ){
				$sql += " id_sucursal >= ? AND id_sucursal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_sucursal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdUsuario()) != null) & ( ($b = $pagos_venta.getIdUsuario()) != null) ){
				$sql += " id_usuario >= ? AND id_usuario <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_usuario = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFecha()) != null) & ( ($b = $pagos_venta.getFecha()) != null) ){
				$sql += " fecha >= ? AND fecha <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getMonto()) != null) & ( ($b = $pagos_venta.getMonto()) != null) ){
				$sql += " monto >= ? AND monto <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " monto = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTipoPago()) != null) & ( ($b = $pagos_venta.getTipoPago()) != null) ){
				$sql += " tipo_pago >= ? AND tipo_pago <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " tipo_pago = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new PagosVenta($foo));
		//}
		return $sql;
	};


}
	PagosVenta._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link PagosVenta}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	PagosVenta.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from pagos_venta";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new PagosVenta( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link PagosVenta} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link PagosVenta} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link PagosVenta Un objeto del tipo {@link PagosVenta}. NULL si no hay tal registro.
	  **/
	PagosVenta.getByPK = function(  $id_pago, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM pagos_venta WHERE (id_pago = ? ) LIMIT 1;";
		db.query($sql, [ $id_pago] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new PagosVenta(results.rows.item(0)); 
				PagosVenta._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : PagosVenta._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table sucursal.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var Sucursal = function ( config )
{
 /**
	* id_sucursal
	* 
	* Identificador de cada sucursal
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_sucursal = config === undefined ? '' : config.id_sucursal || '',

 /**
	* gerente
	* 
	* Gerente de esta sucursal
	* @access private
	* @var int(11)
	*/
	_gerente = config === undefined ? '' : config.gerente || '',

 /**
	* descripcion
	* 
	* nombre o descripcion de sucursal
	* @access private
	* @var varchar(100)
	*/
	_descripcion = config === undefined ? '' : config.descripcion || '',

 /**
	* razon_social
	* 
	* razon social de la sucursal
	* @access private
	* @var varchar(100)
	*/
	_razon_social = config === undefined ? '' : config.razon_social || '',

 /**
	* rfc
	* 
	* El RFC de la sucursal
	* @access private
	* @var varchar(20)
	*/
	_rfc = config === undefined ? '' : config.rfc || '',

 /**
	* calle
	* 
	* calle del domicilio fiscal
	* @access private
	* @var varchar(50)
	*/
	_calle = config === undefined ? '' : config.calle || '',

 /**
	* numero_exterior
	* 
	* nuemro exterior del domicilio fiscal
	* @access private
	* @var varchar(10)
	*/
	_numero_exterior = config === undefined ? '' : config.numero_exterior || '',

 /**
	* numero_interior
	* 
	* numero interior del domicilio fiscal
	* @access private
	* @var varchar(10)
	*/
	_numero_interior = config === undefined ? '' : config.numero_interior || '',

 /**
	* colonia
	* 
	* colonia del domicilio fiscal
	* @access private
	* @var varchar(50)
	*/
	_colonia = config === undefined ? '' : config.colonia || '',

 /**
	* localidad
	* 
	* localidad del domicilio fiscal
	* @access private
	* @var varchar(50)
	*/
	_localidad = config === undefined ? '' : config.localidad || '',

 /**
	* referencia
	* 
	* referencia del domicilio fiscal
	* @access private
	* @var varchar(200)
	*/
	_referencia = config === undefined ? '' : config.referencia || '',

 /**
	* municipio
	* 
	* municipio del domicilio fiscal
	* @access private
	* @var varchar(100)
	*/
	_municipio = config === undefined ? '' : config.municipio || '',

 /**
	* estado
	* 
	* estado del domicilio fiscal
	* @access private
	* @var varchar(50)
	*/
	_estado = config === undefined ? '' : config.estado || '',

 /**
	* pais
	* 
	* pais del domicilio fiscal
	* @access private
	* @var varchar(50)
	*/
	_pais = config === undefined ? '' : config.pais || '',

 /**
	* codigo_postal
	* 
	* codigo postal del domicilio fiscal
	* @access private
	* @var varchar(15)
	*/
	_codigo_postal = config === undefined ? '' : config.codigo_postal || '',

 /**
	* telefono
	* 
	* El telefono de la sucursal
	* @access private
	* @var varchar(20)
	*/
	_telefono = config === undefined ? '' : config.telefono || '',

 /**
	* token
	* 
	* Token de seguridad para esta sucursal
	* @access private
	* @var varchar(512)
	*/
	_token = config === undefined ? '' : config.token || '',

 /**
	* letras_factura
	* 
	*  [Campo no documentado]
	* @access private
	* @var char(1)
	*/
	_letras_factura = config === undefined ? '' : config.letras_factura || '',

 /**
	* activo
	* 
	*  [Campo no documentado]
	* @access private
	* @var tinyint(1)
	*/
	_activo = config === undefined ? '' : config.activo || '',

 /**
	* fecha_apertura
	* 
	* Fecha de apertura de esta sucursal
	* @access private
	* @var timestamp
	*/
	_fecha_apertura = config === undefined ? '' : config.fecha_apertura || '',

 /**
	* saldo_a_favor
	* 
	* es el saldo a favor que tiene la sucursal encuanto a los abonos de sus compras
	* @access private
	* @var float
	*/
	_saldo_a_favor = config === undefined ? '' : config.saldo_a_favor || '';

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Identificador de cada sucursal
	  * @return int(11)
	  */
	this.getIdSucursal = function ()
	{
		return _id_sucursal;
	};

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Identificador de cada sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdSucursal( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdSucursal  = function ( id_sucursal )
	{
		_id_sucursal = id_sucursal;
	};

	/**
	  * getGerente
	  * 
	  * Get the <i>gerente</i> property for this object. Donde <i>gerente</i> es Gerente de esta sucursal
	  * @return int(11)
	  */
	this.getGerente = function ()
	{
		return _gerente;
	};

	/**
	  * setGerente( $gerente )
	  * 
	  * Set the <i>gerente</i> property for this object. Donde <i>gerente</i> es Gerente de esta sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>gerente</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setGerente  = function ( gerente )
	{
		_gerente = gerente;
	};

	/**
	  * getDescripcion
	  * 
	  * Get the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es nombre o descripcion de sucursal
	  * @return varchar(100)
	  */
	this.getDescripcion = function ()
	{
		return _descripcion;
	};

	/**
	  * setDescripcion( $descripcion )
	  * 
	  * Set the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es nombre o descripcion de sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>descripcion</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	this.setDescripcion  = function ( descripcion )
	{
		_descripcion = descripcion;
	};

	/**
	  * getRazonSocial
	  * 
	  * Get the <i>razon_social</i> property for this object. Donde <i>razon_social</i> es razon social de la sucursal
	  * @return varchar(100)
	  */
	this.getRazonSocial = function ()
	{
		return _razon_social;
	};

	/**
	  * setRazonSocial( $razon_social )
	  * 
	  * Set the <i>razon_social</i> property for this object. Donde <i>razon_social</i> es razon social de la sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>razon_social</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	this.setRazonSocial  = function ( razon_social )
	{
		_razon_social = razon_social;
	};

	/**
	  * getRfc
	  * 
	  * Get the <i>rfc</i> property for this object. Donde <i>rfc</i> es El RFC de la sucursal
	  * @return varchar(20)
	  */
	this.getRfc = function ()
	{
		return _rfc;
	};

	/**
	  * setRfc( $rfc )
	  * 
	  * Set the <i>rfc</i> property for this object. Donde <i>rfc</i> es El RFC de la sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>rfc</i> es de tipo <i>varchar(20)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(20)
	  */
	this.setRfc  = function ( rfc )
	{
		_rfc = rfc;
	};

	/**
	  * getCalle
	  * 
	  * Get the <i>calle</i> property for this object. Donde <i>calle</i> es calle del domicilio fiscal
	  * @return varchar(50)
	  */
	this.getCalle = function ()
	{
		return _calle;
	};

	/**
	  * setCalle( $calle )
	  * 
	  * Set the <i>calle</i> property for this object. Donde <i>calle</i> es calle del domicilio fiscal.
	  * Una validacion basica se hara aqui para comprobar que <i>calle</i> es de tipo <i>varchar(50)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(50)
	  */
	this.setCalle  = function ( calle )
	{
		_calle = calle;
	};

	/**
	  * getNumeroExterior
	  * 
	  * Get the <i>numero_exterior</i> property for this object. Donde <i>numero_exterior</i> es nuemro exterior del domicilio fiscal
	  * @return varchar(10)
	  */
	this.getNumeroExterior = function ()
	{
		return _numero_exterior;
	};

	/**
	  * setNumeroExterior( $numero_exterior )
	  * 
	  * Set the <i>numero_exterior</i> property for this object. Donde <i>numero_exterior</i> es nuemro exterior del domicilio fiscal.
	  * Una validacion basica se hara aqui para comprobar que <i>numero_exterior</i> es de tipo <i>varchar(10)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(10)
	  */
	this.setNumeroExterior  = function ( numero_exterior )
	{
		_numero_exterior = numero_exterior;
	};

	/**
	  * getNumeroInterior
	  * 
	  * Get the <i>numero_interior</i> property for this object. Donde <i>numero_interior</i> es numero interior del domicilio fiscal
	  * @return varchar(10)
	  */
	this.getNumeroInterior = function ()
	{
		return _numero_interior;
	};

	/**
	  * setNumeroInterior( $numero_interior )
	  * 
	  * Set the <i>numero_interior</i> property for this object. Donde <i>numero_interior</i> es numero interior del domicilio fiscal.
	  * Una validacion basica se hara aqui para comprobar que <i>numero_interior</i> es de tipo <i>varchar(10)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(10)
	  */
	this.setNumeroInterior  = function ( numero_interior )
	{
		_numero_interior = numero_interior;
	};

	/**
	  * getColonia
	  * 
	  * Get the <i>colonia</i> property for this object. Donde <i>colonia</i> es colonia del domicilio fiscal
	  * @return varchar(50)
	  */
	this.getColonia = function ()
	{
		return _colonia;
	};

	/**
	  * setColonia( $colonia )
	  * 
	  * Set the <i>colonia</i> property for this object. Donde <i>colonia</i> es colonia del domicilio fiscal.
	  * Una validacion basica se hara aqui para comprobar que <i>colonia</i> es de tipo <i>varchar(50)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(50)
	  */
	this.setColonia  = function ( colonia )
	{
		_colonia = colonia;
	};

	/**
	  * getLocalidad
	  * 
	  * Get the <i>localidad</i> property for this object. Donde <i>localidad</i> es localidad del domicilio fiscal
	  * @return varchar(50)
	  */
	this.getLocalidad = function ()
	{
		return _localidad;
	};

	/**
	  * setLocalidad( $localidad )
	  * 
	  * Set the <i>localidad</i> property for this object. Donde <i>localidad</i> es localidad del domicilio fiscal.
	  * Una validacion basica se hara aqui para comprobar que <i>localidad</i> es de tipo <i>varchar(50)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(50)
	  */
	this.setLocalidad  = function ( localidad )
	{
		_localidad = localidad;
	};

	/**
	  * getReferencia
	  * 
	  * Get the <i>referencia</i> property for this object. Donde <i>referencia</i> es referencia del domicilio fiscal
	  * @return varchar(200)
	  */
	this.getReferencia = function ()
	{
		return _referencia;
	};

	/**
	  * setReferencia( $referencia )
	  * 
	  * Set the <i>referencia</i> property for this object. Donde <i>referencia</i> es referencia del domicilio fiscal.
	  * Una validacion basica se hara aqui para comprobar que <i>referencia</i> es de tipo <i>varchar(200)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(200)
	  */
	this.setReferencia  = function ( referencia )
	{
		_referencia = referencia;
	};

	/**
	  * getMunicipio
	  * 
	  * Get the <i>municipio</i> property for this object. Donde <i>municipio</i> es municipio del domicilio fiscal
	  * @return varchar(100)
	  */
	this.getMunicipio = function ()
	{
		return _municipio;
	};

	/**
	  * setMunicipio( $municipio )
	  * 
	  * Set the <i>municipio</i> property for this object. Donde <i>municipio</i> es municipio del domicilio fiscal.
	  * Una validacion basica se hara aqui para comprobar que <i>municipio</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	this.setMunicipio  = function ( municipio )
	{
		_municipio = municipio;
	};

	/**
	  * getEstado
	  * 
	  * Get the <i>estado</i> property for this object. Donde <i>estado</i> es estado del domicilio fiscal
	  * @return varchar(50)
	  */
	this.getEstado = function ()
	{
		return _estado;
	};

	/**
	  * setEstado( $estado )
	  * 
	  * Set the <i>estado</i> property for this object. Donde <i>estado</i> es estado del domicilio fiscal.
	  * Una validacion basica se hara aqui para comprobar que <i>estado</i> es de tipo <i>varchar(50)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(50)
	  */
	this.setEstado  = function ( estado )
	{
		_estado = estado;
	};

	/**
	  * getPais
	  * 
	  * Get the <i>pais</i> property for this object. Donde <i>pais</i> es pais del domicilio fiscal
	  * @return varchar(50)
	  */
	this.getPais = function ()
	{
		return _pais;
	};

	/**
	  * setPais( $pais )
	  * 
	  * Set the <i>pais</i> property for this object. Donde <i>pais</i> es pais del domicilio fiscal.
	  * Una validacion basica se hara aqui para comprobar que <i>pais</i> es de tipo <i>varchar(50)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(50)
	  */
	this.setPais  = function ( pais )
	{
		_pais = pais;
	};

	/**
	  * getCodigoPostal
	  * 
	  * Get the <i>codigo_postal</i> property for this object. Donde <i>codigo_postal</i> es codigo postal del domicilio fiscal
	  * @return varchar(15)
	  */
	this.getCodigoPostal = function ()
	{
		return _codigo_postal;
	};

	/**
	  * setCodigoPostal( $codigo_postal )
	  * 
	  * Set the <i>codigo_postal</i> property for this object. Donde <i>codigo_postal</i> es codigo postal del domicilio fiscal.
	  * Una validacion basica se hara aqui para comprobar que <i>codigo_postal</i> es de tipo <i>varchar(15)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(15)
	  */
	this.setCodigoPostal  = function ( codigo_postal )
	{
		_codigo_postal = codigo_postal;
	};

	/**
	  * getTelefono
	  * 
	  * Get the <i>telefono</i> property for this object. Donde <i>telefono</i> es El telefono de la sucursal
	  * @return varchar(20)
	  */
	this.getTelefono = function ()
	{
		return _telefono;
	};

	/**
	  * setTelefono( $telefono )
	  * 
	  * Set the <i>telefono</i> property for this object. Donde <i>telefono</i> es El telefono de la sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>telefono</i> es de tipo <i>varchar(20)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(20)
	  */
	this.setTelefono  = function ( telefono )
	{
		_telefono = telefono;
	};

	/**
	  * getToken
	  * 
	  * Get the <i>token</i> property for this object. Donde <i>token</i> es Token de seguridad para esta sucursal
	  * @return varchar(512)
	  */
	this.getToken = function ()
	{
		return _token;
	};

	/**
	  * setToken( $token )
	  * 
	  * Set the <i>token</i> property for this object. Donde <i>token</i> es Token de seguridad para esta sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>token</i> es de tipo <i>varchar(512)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(512)
	  */
	this.setToken  = function ( token )
	{
		_token = token;
	};

	/**
	  * getLetrasFactura
	  * 
	  * Get the <i>letras_factura</i> property for this object. Donde <i>letras_factura</i> es  [Campo no documentado]
	  * @return char(1)
	  */
	this.getLetrasFactura = function ()
	{
		return _letras_factura;
	};

	/**
	  * setLetrasFactura( $letras_factura )
	  * 
	  * Set the <i>letras_factura</i> property for this object. Donde <i>letras_factura</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>letras_factura</i> es de tipo <i>char(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param char(1)
	  */
	this.setLetrasFactura  = function ( letras_factura )
	{
		_letras_factura = letras_factura;
	};

	/**
	  * getActivo
	  * 
	  * Get the <i>activo</i> property for this object. Donde <i>activo</i> es  [Campo no documentado]
	  * @return tinyint(1)
	  */
	this.getActivo = function ()
	{
		return _activo;
	};

	/**
	  * setActivo( $activo )
	  * 
	  * Set the <i>activo</i> property for this object. Donde <i>activo</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>activo</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	this.setActivo  = function ( activo )
	{
		_activo = activo;
	};

	/**
	  * getFechaApertura
	  * 
	  * Get the <i>fecha_apertura</i> property for this object. Donde <i>fecha_apertura</i> es Fecha de apertura de esta sucursal
	  * @return timestamp
	  */
	this.getFechaApertura = function ()
	{
		return _fecha_apertura;
	};

	/**
	  * setFechaApertura( $fecha_apertura )
	  * 
	  * Set the <i>fecha_apertura</i> property for this object. Donde <i>fecha_apertura</i> es Fecha de apertura de esta sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_apertura</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFechaApertura  = function ( fecha_apertura )
	{
		_fecha_apertura = fecha_apertura;
	};

	/**
	  * getSaldoAFavor
	  * 
	  * Get the <i>saldo_a_favor</i> property for this object. Donde <i>saldo_a_favor</i> es es el saldo a favor que tiene la sucursal encuanto a los abonos de sus compras
	  * @return float
	  */
	this.getSaldoAFavor = function ()
	{
		return _saldo_a_favor;
	};

	/**
	  * setSaldoAFavor( $saldo_a_favor )
	  * 
	  * Set the <i>saldo_a_favor</i> property for this object. Donde <i>saldo_a_favor</i> es es el saldo a favor que tiene la sucursal encuanto a los abonos de sus compras.
	  * Una validacion basica se hara aqui para comprobar que <i>saldo_a_favor</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setSaldoAFavor  = function ( saldo_a_favor )
	{
		_saldo_a_favor = saldo_a_favor;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Sucursal} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		Sucursal._callback_stack.push( _original_callback  );
		Sucursal._callback_stack.push( function(res){ 
						console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create.call(this, this);
						}else{
							update.call(res, res);
						}
	 			});
		Sucursal.getByPK(  this.getIdSucursal() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Sucursal} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param Sucursal [$sucursal] El objeto de tipo Sucursal
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from sucursal WHERE ("; 
		$val = [];
		if( this.getIdSucursal() != null){
			$sql += " id_sucursal = ? AND";
			$val.push( this.getIdSucursal() );
		}

		if( this.getGerente() != null){
			$sql += " gerente = ? AND";
			$val.push( this.getGerente() );
		}

		if( this.getDescripcion() != null){
			$sql += " descripcion = ? AND";
			$val.push( this.getDescripcion() );
		}

		if( this.getRazonSocial() != null){
			$sql += " razon_social = ? AND";
			$val.push( this.getRazonSocial() );
		}

		if( this.getRfc() != null){
			$sql += " rfc = ? AND";
			$val.push( this.getRfc() );
		}

		if( this.getCalle() != null){
			$sql += " calle = ? AND";
			$val.push( this.getCalle() );
		}

		if( this.getNumeroExterior() != null){
			$sql += " numero_exterior = ? AND";
			$val.push( this.getNumeroExterior() );
		}

		if( this.getNumeroInterior() != null){
			$sql += " numero_interior = ? AND";
			$val.push( this.getNumeroInterior() );
		}

		if( this.getColonia() != null){
			$sql += " colonia = ? AND";
			$val.push( this.getColonia() );
		}

		if( this.getLocalidad() != null){
			$sql += " localidad = ? AND";
			$val.push( this.getLocalidad() );
		}

		if( this.getReferencia() != null){
			$sql += " referencia = ? AND";
			$val.push( this.getReferencia() );
		}

		if( this.getMunicipio() != null){
			$sql += " municipio = ? AND";
			$val.push( this.getMunicipio() );
		}

		if( this.getEstado() != null){
			$sql += " estado = ? AND";
			$val.push( this.getEstado() );
		}

		if( this.getPais() != null){
			$sql += " pais = ? AND";
			$val.push( this.getPais() );
		}

		if( this.getCodigoPostal() != null){
			$sql += " codigo_postal = ? AND";
			$val.push( this.getCodigoPostal() );
		}

		if( this.getTelefono() != null){
			$sql += " telefono = ? AND";
			$val.push( this.getTelefono() );
		}

		if( this.getToken() != null){
			$sql += " token = ? AND";
			$val.push( this.getToken() );
		}

		if( this.getLetrasFactura() != null){
			$sql += " letras_factura = ? AND";
			$val.push( this.getLetrasFactura() );
		}

		if( this.getActivo() != null){
			$sql += " activo = ? AND";
			$val.push( this.getActivo() );
		}

		if( this.getFechaApertura() != null){
			$sql += " fecha_apertura = ? AND";
			$val.push( this.getFechaApertura() );
		}

		if( this.getSaldoAFavor() != null){
			$sql += " saldo_a_favor = ? AND";
			$val.push( this.getSaldoAFavor() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Sucursal($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Sucursal suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Sucursal dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Sucursal [$sucursal] El objeto de tipo Sucursal a crear.
	  **/
	var create = function( sucursal )
	{
		console.log('estoy en create(this)');
		$sql = "INSERT INTO sucursal ( id_sucursal, gerente, descripcion, razon_social, rfc, calle, numero_exterior, numero_interior, colonia, localidad, referencia, municipio, estado, pais, codigo_postal, telefono, token, letras_factura, activo, fecha_apertura, saldo_a_favor ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			sucursal.getIdSucursal(), 
			sucursal.getGerente(), 
			sucursal.getDescripcion(), 
			sucursal.getRazonSocial(), 
			sucursal.getRfc(), 
			sucursal.getCalle(), 
			sucursal.getNumeroExterior(), 
			sucursal.getNumeroInterior(), 
			sucursal.getColonia(), 
			sucursal.getLocalidad(), 
			sucursal.getReferencia(), 
			sucursal.getMunicipio(), 
			sucursal.getEstado(), 
			sucursal.getPais(), 
			sucursal.getCodigoPostal(), 
			sucursal.getTelefono(), 
			sucursal.getToken(), 
			sucursal.getLetrasFactura(), 
			sucursal.getActivo(), 
			sucursal.getFechaApertura(), 
			sucursal.getSaldoAFavor(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				Sucursal._callback_stack.pop().call(null, sucursal);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param Sucursal [$sucursal] El objeto de tipo Sucursal a actualizar.
	  **/
	var update = function( $sucursal )
	{
		$sql = "UPDATE sucursal SET  gerente = ?, descripcion = ?, razon_social = ?, rfc = ?, calle = ?, numero_exterior = ?, numero_interior = ?, colonia = ?, localidad = ?, referencia = ?, municipio = ?, estado = ?, pais = ?, codigo_postal = ?, telefono = ?, token = ?, letras_factura = ?, activo = ?, fecha_apertura = ?, saldo_a_favor = ? WHERE  id_sucursal = ?;";
		$params = [ 
			$sucursal.getGerente(), 
			$sucursal.getDescripcion(), 
			$sucursal.getRazonSocial(), 
			$sucursal.getRfc(), 
			$sucursal.getCalle(), 
			$sucursal.getNumeroExterior(), 
			$sucursal.getNumeroInterior(), 
			$sucursal.getColonia(), 
			$sucursal.getLocalidad(), 
			$sucursal.getReferencia(), 
			$sucursal.getMunicipio(), 
			$sucursal.getEstado(), 
			$sucursal.getPais(), 
			$sucursal.getCodigoPostal(), 
			$sucursal.getTelefono(), 
			$sucursal.getToken(), 
			$sucursal.getLetrasFactura(), 
			$sucursal.getActivo(), 
			$sucursal.getFechaApertura(), 
			$sucursal.getSaldoAFavor(), 
			$sucursal.getIdSucursal(),  ] ;
		console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				Sucursal._callback_stack.pop().call(null, $sucursal);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Sucursal suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( Sucursal.getByPK(this.getIdSucursal()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM sucursal WHERE  id_sucursal = ?;";
		$params = [ this.getIdSucursal() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Sucursal} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Sucursal}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param Sucursal [$sucursal] El objeto de tipo Sucursal
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $sucursal , $orderBy , $orden )
	{
		$sql = "SELECT * from sucursal WHERE ("; 
		$val = [];
		if( (($a = this.getIdSucursal()) != null) & ( ($b = $sucursal.getIdSucursal()) != null) ){
				$sql += " id_sucursal >= ? AND id_sucursal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_sucursal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getGerente()) != null) & ( ($b = $sucursal.getGerente()) != null) ){
				$sql += " gerente >= ? AND gerente <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " gerente = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getDescripcion()) != null) & ( ($b = $sucursal.getDescripcion()) != null) ){
				$sql += " descripcion >= ? AND descripcion <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " descripcion = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getRazonSocial()) != null) & ( ($b = $sucursal.getRazonSocial()) != null) ){
				$sql += " razon_social >= ? AND razon_social <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " razon_social = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getRfc()) != null) & ( ($b = $sucursal.getRfc()) != null) ){
				$sql += " rfc >= ? AND rfc <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " rfc = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getCalle()) != null) & ( ($b = $sucursal.getCalle()) != null) ){
				$sql += " calle >= ? AND calle <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " calle = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getNumeroExterior()) != null) & ( ($b = $sucursal.getNumeroExterior()) != null) ){
				$sql += " numero_exterior >= ? AND numero_exterior <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " numero_exterior = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getNumeroInterior()) != null) & ( ($b = $sucursal.getNumeroInterior()) != null) ){
				$sql += " numero_interior >= ? AND numero_interior <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " numero_interior = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getColonia()) != null) & ( ($b = $sucursal.getColonia()) != null) ){
				$sql += " colonia >= ? AND colonia <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " colonia = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getLocalidad()) != null) & ( ($b = $sucursal.getLocalidad()) != null) ){
				$sql += " localidad >= ? AND localidad <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " localidad = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getReferencia()) != null) & ( ($b = $sucursal.getReferencia()) != null) ){
				$sql += " referencia >= ? AND referencia <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " referencia = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getMunicipio()) != null) & ( ($b = $sucursal.getMunicipio()) != null) ){
				$sql += " municipio >= ? AND municipio <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " municipio = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getEstado()) != null) & ( ($b = $sucursal.getEstado()) != null) ){
				$sql += " estado >= ? AND estado <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " estado = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPais()) != null) & ( ($b = $sucursal.getPais()) != null) ){
				$sql += " pais >= ? AND pais <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " pais = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getCodigoPostal()) != null) & ( ($b = $sucursal.getCodigoPostal()) != null) ){
				$sql += " codigo_postal >= ? AND codigo_postal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " codigo_postal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTelefono()) != null) & ( ($b = $sucursal.getTelefono()) != null) ){
				$sql += " telefono >= ? AND telefono <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " telefono = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getToken()) != null) & ( ($b = $sucursal.getToken()) != null) ){
				$sql += " token >= ? AND token <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " token = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getLetrasFactura()) != null) & ( ($b = $sucursal.getLetrasFactura()) != null) ){
				$sql += " letras_factura >= ? AND letras_factura <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " letras_factura = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getActivo()) != null) & ( ($b = $sucursal.getActivo()) != null) ){
				$sql += " activo >= ? AND activo <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " activo = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFechaApertura()) != null) & ( ($b = $sucursal.getFechaApertura()) != null) ){
				$sql += " fecha_apertura >= ? AND fecha_apertura <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha_apertura = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getSaldoAFavor()) != null) & ( ($b = $sucursal.getSaldoAFavor()) != null) ){
				$sql += " saldo_a_favor >= ? AND saldo_a_favor <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " saldo_a_favor = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Sucursal($foo));
		//}
		return $sql;
	};


}
	Sucursal._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Sucursal}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	Sucursal.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from sucursal";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new Sucursal( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link Sucursal} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Sucursal} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Sucursal Un objeto del tipo {@link Sucursal}. NULL si no hay tal registro.
	  **/
	Sucursal.getByPK = function(  $id_sucursal, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM sucursal WHERE (id_sucursal = ? ) LIMIT 1;";
		db.query($sql, [ $id_sucursal] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new Sucursal(results.rows.item(0)); 
				Sucursal._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : Sucursal._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


/** Value Object file for table ventas.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

var Ventas = function ( config )
{
 /**
	* id_venta
	* 
	* id de venta
	* <b>Llave Primaria</b>
	* @access private
	* @var int(11)
	*/
	var _id_venta = config === undefined ? '' : config.id_venta || '',

 /**
	* id_venta_equipo
	* 
	*  [Campo no documentado]
	* @access private
	* @var int(11)
	*/
	_id_venta_equipo = config === undefined ? '' : config.id_venta_equipo || '',

 /**
	* id_equipo
	* 
	*  [Campo no documentado]
	* @access private
	* @var int(11)
	*/
	_id_equipo = config === undefined ? '' : config.id_equipo || '',

 /**
	* id_cliente
	* 
	* cliente al que se le vendio
	* @access private
	* @var int(11)
	*/
	_id_cliente = config === undefined ? '' : config.id_cliente || '',

 /**
	* tipo_venta
	* 
	* tipo de venta, contado o credito
	* @access private
	* @var enum('credito','contado')
	*/
	_tipo_venta = config === undefined ? '' : config.tipo_venta || '',

 /**
	* tipo_pago
	* 
	* tipo de pago para esta venta en caso de ser a contado
	* @access private
	* @var enum('efectivo','cheque','tarjeta')
	*/
	_tipo_pago = config === undefined ? '' : config.tipo_pago || '',

 /**
	* fecha
	* 
	* fecha de venta
	* @access private
	* @var timestamp
	*/
	_fecha = config === undefined ? '' : config.fecha || '',

 /**
	* subtotal
	* 
	* subtotal de la venta, puede ser nulo
	* @access private
	* @var float
	*/
	_subtotal = config === undefined ? '' : config.subtotal || '',

 /**
	* iva
	* 
	* iva agregado por la venta, depende de cada sucursal
	* @access private
	* @var float
	*/
	_iva = config === undefined ? '' : config.iva || '',

 /**
	* descuento
	* 
	* descuento aplicado a esta venta
	* @access private
	* @var float
	*/
	_descuento = config === undefined ? '' : config.descuento || '',

 /**
	* total
	* 
	* total de esta venta
	* @access private
	* @var float
	*/
	_total = config === undefined ? '' : config.total || '',

 /**
	* id_sucursal
	* 
	* sucursal de la venta
	* @access private
	* @var int(11)
	*/
	_id_sucursal = config === undefined ? '' : config.id_sucursal || '',

 /**
	* id_usuario
	* 
	* empleado que lo vendio
	* @access private
	* @var int(11)
	*/
	_id_usuario = config === undefined ? '' : config.id_usuario || '',

 /**
	* pagado
	* 
	* porcentaje pagado de esta venta
	* @access private
	* @var float
	*/
	_pagado = config === undefined ? '' : config.pagado || '',

 /**
	* cancelada
	* 
	* verdadero si esta venta ha sido cancelada, falso si no
	* @access private
	* @var tinyint(1)
	*/
	_cancelada = config === undefined ? '' : config.cancelada || '',

 /**
	* ip
	* 
	* ip de donde provino esta compra
	* @access private
	* @var varchar(16)
	*/
	_ip = config === undefined ? '' : config.ip || '',

 /**
	* liquidada
	* 
	* Verdadero si esta venta ha sido liquidada, falso si hay un saldo pendiente
	* @access private
	* @var tinyint(1)
	*/
	_liquidada = config === undefined ? '' : config.liquidada || '';

	/**
	  * getIdVenta
	  * 
	  * Get the <i>id_venta</i> property for this object. Donde <i>id_venta</i> es id de venta
	  * @return int(11)
	  */
	this.getIdVenta = function ()
	{
		return _id_venta;
	};

	/**
	  * setIdVenta( $id_venta )
	  * 
	  * Set the <i>id_venta</i> property for this object. Donde <i>id_venta</i> es id de venta.
	  * Una validacion basica se hara aqui para comprobar que <i>id_venta</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdVenta( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	this.setIdVenta  = function ( id_venta )
	{
		_id_venta = id_venta;
	};

	/**
	  * getIdVentaEquipo
	  * 
	  * Get the <i>id_venta_equipo</i> property for this object. Donde <i>id_venta_equipo</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	this.getIdVentaEquipo = function ()
	{
		return _id_venta_equipo;
	};

	/**
	  * setIdVentaEquipo( $id_venta_equipo )
	  * 
	  * Set the <i>id_venta_equipo</i> property for this object. Donde <i>id_venta_equipo</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_venta_equipo</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdVentaEquipo  = function ( id_venta_equipo )
	{
		_id_venta_equipo = id_venta_equipo;
	};

	/**
	  * getIdEquipo
	  * 
	  * Get the <i>id_equipo</i> property for this object. Donde <i>id_equipo</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	this.getIdEquipo = function ()
	{
		return _id_equipo;
	};

	/**
	  * setIdEquipo( $id_equipo )
	  * 
	  * Set the <i>id_equipo</i> property for this object. Donde <i>id_equipo</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_equipo</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdEquipo  = function ( id_equipo )
	{
		_id_equipo = id_equipo;
	};

	/**
	  * getIdCliente
	  * 
	  * Get the <i>id_cliente</i> property for this object. Donde <i>id_cliente</i> es cliente al que se le vendio
	  * @return int(11)
	  */
	this.getIdCliente = function ()
	{
		return _id_cliente;
	};

	/**
	  * setIdCliente( $id_cliente )
	  * 
	  * Set the <i>id_cliente</i> property for this object. Donde <i>id_cliente</i> es cliente al que se le vendio.
	  * Una validacion basica se hara aqui para comprobar que <i>id_cliente</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdCliente  = function ( id_cliente )
	{
		_id_cliente = id_cliente;
	};

	/**
	  * getTipoVenta
	  * 
	  * Get the <i>tipo_venta</i> property for this object. Donde <i>tipo_venta</i> es tipo de venta, contado o credito
	  * @return enum('credito','contado')
	  */
	this.getTipoVenta = function ()
	{
		return _tipo_venta;
	};

	/**
	  * setTipoVenta( $tipo_venta )
	  * 
	  * Set the <i>tipo_venta</i> property for this object. Donde <i>tipo_venta</i> es tipo de venta, contado o credito.
	  * Una validacion basica se hara aqui para comprobar que <i>tipo_venta</i> es de tipo <i>enum('credito','contado')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('credito','contado')
	  */
	this.setTipoVenta  = function ( tipo_venta )
	{
		_tipo_venta = tipo_venta;
	};

	/**
	  * getTipoPago
	  * 
	  * Get the <i>tipo_pago</i> property for this object. Donde <i>tipo_pago</i> es tipo de pago para esta venta en caso de ser a contado
	  * @return enum('efectivo','cheque','tarjeta')
	  */
	this.getTipoPago = function ()
	{
		return _tipo_pago;
	};

	/**
	  * setTipoPago( $tipo_pago )
	  * 
	  * Set the <i>tipo_pago</i> property for this object. Donde <i>tipo_pago</i> es tipo de pago para esta venta en caso de ser a contado.
	  * Una validacion basica se hara aqui para comprobar que <i>tipo_pago</i> es de tipo <i>enum('efectivo','cheque','tarjeta')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('efectivo','cheque','tarjeta')
	  */
	this.setTipoPago  = function ( tipo_pago )
	{
		_tipo_pago = tipo_pago;
	};

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha de venta
	  * @return timestamp
	  */
	this.getFecha = function ()
	{
		return _fecha;
	};

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha de venta.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	this.setFecha  = function ( fecha )
	{
		_fecha = fecha;
	};

	/**
	  * getSubtotal
	  * 
	  * Get the <i>subtotal</i> property for this object. Donde <i>subtotal</i> es subtotal de la venta, puede ser nulo
	  * @return float
	  */
	this.getSubtotal = function ()
	{
		return _subtotal;
	};

	/**
	  * setSubtotal( $subtotal )
	  * 
	  * Set the <i>subtotal</i> property for this object. Donde <i>subtotal</i> es subtotal de la venta, puede ser nulo.
	  * Una validacion basica se hara aqui para comprobar que <i>subtotal</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setSubtotal  = function ( subtotal )
	{
		_subtotal = subtotal;
	};

	/**
	  * getIva
	  * 
	  * Get the <i>iva</i> property for this object. Donde <i>iva</i> es iva agregado por la venta, depende de cada sucursal
	  * @return float
	  */
	this.getIva = function ()
	{
		return _iva;
	};

	/**
	  * setIva( $iva )
	  * 
	  * Set the <i>iva</i> property for this object. Donde <i>iva</i> es iva agregado por la venta, depende de cada sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>iva</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setIva  = function ( iva )
	{
		_iva = iva;
	};

	/**
	  * getDescuento
	  * 
	  * Get the <i>descuento</i> property for this object. Donde <i>descuento</i> es descuento aplicado a esta venta
	  * @return float
	  */
	this.getDescuento = function ()
	{
		return _descuento;
	};

	/**
	  * setDescuento( $descuento )
	  * 
	  * Set the <i>descuento</i> property for this object. Donde <i>descuento</i> es descuento aplicado a esta venta.
	  * Una validacion basica se hara aqui para comprobar que <i>descuento</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setDescuento  = function ( descuento )
	{
		_descuento = descuento;
	};

	/**
	  * getTotal
	  * 
	  * Get the <i>total</i> property for this object. Donde <i>total</i> es total de esta venta
	  * @return float
	  */
	this.getTotal = function ()
	{
		return _total;
	};

	/**
	  * setTotal( $total )
	  * 
	  * Set the <i>total</i> property for this object. Donde <i>total</i> es total de esta venta.
	  * Una validacion basica se hara aqui para comprobar que <i>total</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setTotal  = function ( total )
	{
		_total = total;
	};

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal de la venta
	  * @return int(11)
	  */
	this.getIdSucursal = function ()
	{
		return _id_sucursal;
	};

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal de la venta.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdSucursal  = function ( id_sucursal )
	{
		_id_sucursal = id_sucursal;
	};

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es empleado que lo vendio
	  * @return int(11)
	  */
	this.getIdUsuario = function ()
	{
		return _id_usuario;
	};

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es empleado que lo vendio.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	this.setIdUsuario  = function ( id_usuario )
	{
		_id_usuario = id_usuario;
	};

	/**
	  * getPagado
	  * 
	  * Get the <i>pagado</i> property for this object. Donde <i>pagado</i> es porcentaje pagado de esta venta
	  * @return float
	  */
	this.getPagado = function ()
	{
		return _pagado;
	};

	/**
	  * setPagado( $pagado )
	  * 
	  * Set the <i>pagado</i> property for this object. Donde <i>pagado</i> es porcentaje pagado de esta venta.
	  * Una validacion basica se hara aqui para comprobar que <i>pagado</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	this.setPagado  = function ( pagado )
	{
		_pagado = pagado;
	};

	/**
	  * getCancelada
	  * 
	  * Get the <i>cancelada</i> property for this object. Donde <i>cancelada</i> es verdadero si esta venta ha sido cancelada, falso si no
	  * @return tinyint(1)
	  */
	this.getCancelada = function ()
	{
		return _cancelada;
	};

	/**
	  * setCancelada( $cancelada )
	  * 
	  * Set the <i>cancelada</i> property for this object. Donde <i>cancelada</i> es verdadero si esta venta ha sido cancelada, falso si no.
	  * Una validacion basica se hara aqui para comprobar que <i>cancelada</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	this.setCancelada  = function ( cancelada )
	{
		_cancelada = cancelada;
	};

	/**
	  * getIp
	  * 
	  * Get the <i>ip</i> property for this object. Donde <i>ip</i> es ip de donde provino esta compra
	  * @return varchar(16)
	  */
	this.getIp = function ()
	{
		return _ip;
	};

	/**
	  * setIp( $ip )
	  * 
	  * Set the <i>ip</i> property for this object. Donde <i>ip</i> es ip de donde provino esta compra.
	  * Una validacion basica se hara aqui para comprobar que <i>ip</i> es de tipo <i>varchar(16)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(16)
	  */
	this.setIp  = function ( ip )
	{
		_ip = ip;
	};

	/**
	  * getLiquidada
	  * 
	  * Get the <i>liquidada</i> property for this object. Donde <i>liquidada</i> es Verdadero si esta venta ha sido liquidada, falso si hay un saldo pendiente
	  * @return tinyint(1)
	  */
	this.getLiquidada = function ()
	{
		return _liquidada;
	};

	/**
	  * setLiquidada( $liquidada )
	  * 
	  * Set the <i>liquidada</i> property for this object. Donde <i>liquidada</i> es Verdadero si esta venta ha sido liquidada, falso si hay un saldo pendiente.
	  * Una validacion basica se hara aqui para comprobar que <i>liquidada</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	this.setLiquidada  = function ( liquidada )
	{
		_liquidada = liquidada;
	};

	/**
	  *	Guardar registros. 
	  *	
	  *	Este metodo guarda el estado actual del objeto {@link Ventas} pasado en la base de datos. La llave 
	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves
	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando
	  *	en ese objeto el ID recien creado.
	  *	
	  *	@static
	  * @throws Exception si la operacion fallo.
	  * @param Function to callback
	  * @return Un entero mayor o igual a cero denotando las filas afectadas.
	  **/
	this.save = function( _original_callback )
	{
		//console.log('estoy en save()');
		Ventas._callback_stack.push( _original_callback  );
		Ventas._callback_stack.push( function(res){ 
						console.log('estoy de regreso en save(',res,')');
						if(res == null){
							create.call(this, this);
						}else{
							update.call(res, res);
						}
	 			});
		Ventas.getByPK(  this.getIdVenta() , { context : this } ) 
	}; //save()


	/**
	  *	Buscar registros.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Ventas} de la base de datos. 
	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. 
	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.
	  *	
	  * <code>
	  *  / **
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000
	  *   {@*} 
	  *	  cliente = new Cliente();
	  *	  cliente.setLimiteCredito("20000");
	  *	  resultados = cliente.search();
	  *	  
	  *	  foreach(resultados as c ){
	  *	  	//console.log( c.getNombre() );
	  *	  }
	  * </code>
	  *	@static
	  * @param Ventas [$ventas] El objeto de tipo Ventas
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.search = function( $orderBy , $orden )
	{
		$sql = "SELECT * from ventas WHERE ("; 
		$val = [];
		if( this.getIdVenta() != null){
			$sql += " id_venta = ? AND";
			$val.push( this.getIdVenta() );
		}

		if( this.getIdVentaEquipo() != null){
			$sql += " id_venta_equipo = ? AND";
			$val.push( this.getIdVentaEquipo() );
		}

		if( this.getIdEquipo() != null){
			$sql += " id_equipo = ? AND";
			$val.push( this.getIdEquipo() );
		}

		if( this.getIdCliente() != null){
			$sql += " id_cliente = ? AND";
			$val.push( this.getIdCliente() );
		}

		if( this.getTipoVenta() != null){
			$sql += " tipo_venta = ? AND";
			$val.push( this.getTipoVenta() );
		}

		if( this.getTipoPago() != null){
			$sql += " tipo_pago = ? AND";
			$val.push( this.getTipoPago() );
		}

		if( this.getFecha() != null){
			$sql += " fecha = ? AND";
			$val.push( this.getFecha() );
		}

		if( this.getSubtotal() != null){
			$sql += " subtotal = ? AND";
			$val.push( this.getSubtotal() );
		}

		if( this.getIva() != null){
			$sql += " iva = ? AND";
			$val.push( this.getIva() );
		}

		if( this.getDescuento() != null){
			$sql += " descuento = ? AND";
			$val.push( this.getDescuento() );
		}

		if( this.getTotal() != null){
			$sql += " total = ? AND";
			$val.push( this.getTotal() );
		}

		if( this.getIdSucursal() != null){
			$sql += " id_sucursal = ? AND";
			$val.push( this.getIdSucursal() );
		}

		if( this.getIdUsuario() != null){
			$sql += " id_usuario = ? AND";
			$val.push( this.getIdUsuario() );
		}

		if( this.getPagado() != null){
			$sql += " pagado = ? AND";
			$val.push( this.getPagado() );
		}

		if( this.getCancelada() != null){
			$sql += " cancelada = ? AND";
			$val.push( this.getCancelada() );
		}

		if( this.getIp() != null){
			$sql += " ip = ? AND";
			$val.push( this.getIp() );
		}

		if( this.getLiquidada() != null){
			$sql += " liquidada = ? AND";
			$val.push( this.getLiquidada() );
		}

		if( $val.length == 0){return [];}
		$sql = $sql.substr( 0, $sql.length - 3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Ventas($foo));
		//}
		return $sql;
	};


	/**
	  *	Crear registros.
	  *	
	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los 
	  * contenidos del objeto Ventas suministrado. Asegurese
	  * de que los valores para todas las columnas NOT NULL se ha especificado 
	  * correctamente. Despues del comando INSERT, este metodo asignara la clave 
	  * primaria generada en el objeto Ventas dentro de la misma transaccion.
	  *	
	  * @internal private information for advanced developers only
	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error
	  * @param Ventas [$ventas] El objeto de tipo Ventas a crear.
	  **/
	var create = function( ventas )
	{
		console.log('estoy en create(this)');
		$sql = "INSERT INTO ventas ( id_venta, id_venta_equipo, id_equipo, id_cliente, tipo_venta, tipo_pago, fecha, subtotal, iva, descuento, total, id_sucursal, id_usuario, pagado, cancelada, ip, liquidada ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$params = [
			ventas.getIdVenta(), 
			ventas.getIdVentaEquipo(), 
			ventas.getIdEquipo(), 
			ventas.getIdCliente(), 
			ventas.getTipoVenta(), 
			ventas.getTipoPago(), 
			ventas.getFecha(), 
			ventas.getSubtotal(), 
			ventas.getIva(), 
			ventas.getDescuento(), 
			ventas.getTotal(), 
			ventas.getIdSucursal(), 
			ventas.getIdUsuario(), 
			ventas.getPagado(), 
			ventas.getCancelada(), 
			ventas.getIp(), 
			ventas.getLiquidada(), 
		 ];
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de insert():',tx,results);
				Ventas._callback_stack.pop().call(null, ventas);  
			});
		return; 
	};

	/**
	  *	Actualizar registros.
	  *	
	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones
	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT 
	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.
	  *	
	  * @internal private information for advanced developers only
	  * @return Filas afectadas o un string con la descripcion del error
	  * @param Ventas [$ventas] El objeto de tipo Ventas a actualizar.
	  **/
	var update = function( $ventas )
	{
		$sql = "UPDATE ventas SET  id_venta_equipo = ?, id_equipo = ?, id_cliente = ?, tipo_venta = ?, tipo_pago = ?, fecha = ?, subtotal = ?, iva = ?, descuento = ?, total = ?, id_sucursal = ?, id_usuario = ?, pagado = ?, cancelada = ?, ip = ?, liquidada = ? WHERE  id_venta = ?;";
		$params = [ 
			$ventas.getIdVentaEquipo(), 
			$ventas.getIdEquipo(), 
			$ventas.getIdCliente(), 
			$ventas.getTipoVenta(), 
			$ventas.getTipoPago(), 
			$ventas.getFecha(), 
			$ventas.getSubtotal(), 
			$ventas.getIva(), 
			$ventas.getDescuento(), 
			$ventas.getTotal(), 
			$ventas.getIdSucursal(), 
			$ventas.getIdUsuario(), 
			$ventas.getPagado(), 
			$ventas.getCancelada(), 
			$ventas.getIp(), 
			$ventas.getLiquidada(), 
			$ventas.getIdVenta(),  ] ;
		console.log('estoy en update()');
		db.query($sql, $params, function(tx, results){ 
				//console.log('ya termine el query de update():',tx,results);
				Ventas._callback_stack.pop().call(null, $ventas);  
			});
		return; 
	}


	/**
	  *	Eliminar registros.
	  *	
	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria
	  * en el objeto Ventas suministrado. Una vez que se ha suprimido un objeto, este no 
	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila 
	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. 
	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.
	  *	
	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.
	  *	@return boolean Verdadero si todo salio bien.
	  **/
	this.delete = function(  )
	{
		if( Ventas.getByPK(this.getIdVenta()) === null) throw new Exception('Campo no encontrado.');
		$sql = "DELETE FROM ventas WHERE  id_venta = ?;";
		$params = [ this.getIdVenta() ];
		//$conn->Execute($sql, $params);
		return $sql;
	};


	/**
	  *	Buscar por rango.
	  *	
	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link Ventas} de la base de datos siempre y cuando 
	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link Ventas}.
	  * 
	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. 
	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.
	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.
	  *	
	  * <code>
	  *  /**
	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito 
	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.
	  *   {@*} 
	  *	  $cr1 = new Cliente();
	  *	  $cr1->setLimiteCredito("2000");
	  *	  $cr1->setDescuento("50");
	  *	  
	  *	  $cr2 = new Cliente();
	  *	  $cr2->setLimiteCredito("5000");
	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);
	  *	  
	  *	  foreach($resultados as $c ){
	  *	  	echo $c->getNombre() . "<br>";
	  *	  }
	  * </code>
	  *	@static
	  * @param Ventas [$ventas] El objeto de tipo Ventas
	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	this.byRange = function( $ventas , $orderBy , $orden )
	{
		$sql = "SELECT * from ventas WHERE ("; 
		$val = [];
		if( (($a = this.getIdVenta()) != null) & ( ($b = $ventas.getIdVenta()) != null) ){
				$sql += " id_venta >= ? AND id_venta <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_venta = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdVentaEquipo()) != null) & ( ($b = $ventas.getIdVentaEquipo()) != null) ){
				$sql += " id_venta_equipo >= ? AND id_venta_equipo <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_venta_equipo = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdEquipo()) != null) & ( ($b = $ventas.getIdEquipo()) != null) ){
				$sql += " id_equipo >= ? AND id_equipo <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_equipo = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdCliente()) != null) & ( ($b = $ventas.getIdCliente()) != null) ){
				$sql += " id_cliente >= ? AND id_cliente <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_cliente = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTipoVenta()) != null) & ( ($b = $ventas.getTipoVenta()) != null) ){
				$sql += " tipo_venta >= ? AND tipo_venta <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " tipo_venta = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTipoPago()) != null) & ( ($b = $ventas.getTipoPago()) != null) ){
				$sql += " tipo_pago >= ? AND tipo_pago <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " tipo_pago = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getFecha()) != null) & ( ($b = $ventas.getFecha()) != null) ){
				$sql += " fecha >= ? AND fecha <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " fecha = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getSubtotal()) != null) & ( ($b = $ventas.getSubtotal()) != null) ){
				$sql += " subtotal >= ? AND subtotal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " subtotal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIva()) != null) & ( ($b = $ventas.getIva()) != null) ){
				$sql += " iva >= ? AND iva <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " iva = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getDescuento()) != null) & ( ($b = $ventas.getDescuento()) != null) ){
				$sql += " descuento >= ? AND descuento <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " descuento = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getTotal()) != null) & ( ($b = $ventas.getTotal()) != null) ){
				$sql += " total >= ? AND total <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " total = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdSucursal()) != null) & ( ($b = $ventas.getIdSucursal()) != null) ){
				$sql += " id_sucursal >= ? AND id_sucursal <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_sucursal = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIdUsuario()) != null) & ( ($b = $ventas.getIdUsuario()) != null) ){
				$sql += " id_usuario >= ? AND id_usuario <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " id_usuario = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getPagado()) != null) & ( ($b = $ventas.getPagado()) != null) ){
				$sql += " pagado >= ? AND pagado <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " pagado = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getCancelada()) != null) & ( ($b = $ventas.getCancelada()) != null) ){
				$sql += " cancelada >= ? AND cancelada <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " cancelada = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getIp()) != null) & ( ($b = $ventas.getIp()) != null) ){
				$sql += " ip >= ? AND ip <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " ip = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		if( (($a = this.getLiquidada()) != null) & ( ($b = $ventas.getLiquidada()) != null) ){
				$sql += " liquidada >= ? AND liquidada <= ? AND";
				$val.push( Math.min($a,$b)); 
				$val.push( Math.max($a,$b)); 
		}else{ 
			if( $a || $b ){
				$sql += " liquidada = ? AND"; 
				$a = $a == null ? $b : $a;
				$val.push( $a);
			}
		}

		$sql = $sql.substr( 0, $sql.length -3) + " )";
		if( $orderBy !== null ){
		    $sql += " order by " + $orderBy + " " + $orden ;
		
		}
		//global $conn;
		//$rs = $conn->Execute($sql, $val);
		//$ar = array();
		//foreach ($rs as $foo) {
    	//	array_push( $ar, new Ventas($foo));
		//}
		return $sql;
	};


}
	Ventas._callback_stack = [];
	/**
	  *	Obtener todas las filas.
	  *	
	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira
	  * un vector que contiene objetos de tipo {@link Ventas}. Tenga en cuenta que este metodo
	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. 
	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.
	  *	
	  *	@static
	  * @param config Un objeto de configuracion con por lo menos success, y failure
	  * @param $pagina Pagina a ver.
	  * @param $columnas_por_pagina Columnas por pagina.
	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.
	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'
	  **/
	Ventas.getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )
	{
		$sql = "SELECT * from ventas";
		if($orden != null)
		{ $sql += " ORDER BY " + $orden + " " + $tipo_de_orden;	}
		if($pagina != null)
		{
			$sql += " LIMIT " + (( $pagina - 1 )*$columnas_por_pagina) + "," + $columnas_por_pagina; 
		}
		db.query($sql, [], function(tx,results){ 
				fres = [];
				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new Ventas( results.rows.item(i) ) ) }
				//console.log(fres, config) 
			});
		return;
	};

	/**
	  *	Obtener {@link Ventas} por llave primaria. 
	  *	
	  * Este metodo cargara un objeto {@link Ventas} de la base de datos 
	  * usando sus llaves primarias. 
	  *	
	  *	@static
	  * @return @link Ventas Un objeto del tipo {@link Ventas}. NULL si no hay tal registro.
	  **/
	Ventas.getByPK = function(  $id_venta, config )
	{
		//console.log('estoy en getbypk()');
		$sql = "SELECT * FROM ventas WHERE (id_venta = ? ) LIMIT 1;";
		db.query($sql, [ $id_venta] , function(tx,results){ 
				//console.log('ya termine el query de getByPK():',tx,results);
				if(results.rows.length == 0) fres = null;
				else fres = new Ventas(results.rows.item(0)); 
				Ventas._callback_stack.length == 0 ? config.callback.call(config.context || null, fres)    : Ventas._callback_stack.pop().call(config.context || null, fres);  
			});
		return;
	};


