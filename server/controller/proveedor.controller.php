<?php 
/** Clientes Controller.
  * 
  * Este archivo es la capa entre la interfaz de usuario y peticiones ajaxa y los 
  * procedimientos para realizar las operaciones sobre Clientes. 
  * @author Alan Gonzalez <alan@caffeina.mx>, Manuel Garcia Carmona <manuel@caffeina.mx>
  * 
  */

require_once('model/proveedor.dao.php');
require_once('model/detalle_inventario.dao.php');
require_once('model/compra_sucursal.dao.php');
require_once('model/detalle_compra_sucursal.dao.php');
require_once('model/autorizacion.dao.php');
//require_once('model/base/compra_sucursal.dao.base.php');
require_once('logger.php');



/**
  *	Crea un proveedor. 
  *	
  * Este metodo intentara crear un proveedor dado un arreglo de datos proporcionado.
  *	
  *	@static
  * @throws Exception si la operacion fallo.
  * @param Autorizacion [$autorizacion] El objeto de tipo Autorizacion
  * @return Un entero mayor o igual a cero denotando las filas afectadas, o un string con el error si es que hubo alguno.
  **/
function nuevoProveedor( $json = null){

	if($json == null){
        Logger::log("No hay parametros para ingresar nuevo cliente.");
		die('{ "success": false, "reason" : "Parametros invalidos" }');
	}	

	$data = parseJSON( $json );

	if($data == null){
		Logger::log("Json invalido para crear cliente:" . $json);
		die('{ "success": false, "reason" : "Parametros invalidos" }');
	}

	//echo $data;
	
	if(!( isset($data->rfc) &&
			isset($data->nombre) &&
			isset($data->direccion) &&
			isset($data->telefono) &&
			isset($data->tipo_proveedor)
		)){
		Logger::log("Faltan parametros para crear el proveedor:" . $json);
		die('{ "success": false, "reason" : "Faltan parametros." }');
	}

	//crear el objeto de proveedor a ingresar
	$proveedor = new Proveedor();
	$proveedor->setRfc( $data->rfc );
	
	//buscar que no exista ya un proveedor con este RFC
	if( count(ProveedorDAO::search( $proveedor )) > 0){
		Logger::log("RFC ya existe en proveedores.");
		die ( '{"success": false, "reason": "Ya existe un proveedor con este RFC." }' );
	}



	if(strlen($data->nombre) < 8){
		Logger::log("Nombre muy corto para insertar proveedor.");
		die ( '{"success": false, "reason": "El nombre del proveedor es muy corto." }' );		
	}


    $proveedor->setNombre( $data->nombre );
	$proveedor->setDireccion( $data->direccion );
	$proveedor->setTelefono( $data->telefono );
		
	if(isset($data->e_mail))
		$proveedor->setEMail( $data->e_mail );
	
	$proveedor->setActivo ( 1 );
	
	
	 switch( $data->tipo_proveedor ){
        case 'admin' :
            $proveedor->setTipoProveedor ( $data->tipo_proveedor );
        break;
        
        case 'sucursal' :
            $proveedor->setTipoProveedor ( $data->tipo_proveedor );
        break;
        
        case 'ambos' :
            $proveedor->setTipoProveedor ( $data->tipo_proveedor );
        break;
        
        default:
             Logger::log("Tipo ptoveedor invalido" . $json);
    		die('{ "success": false, "reason" : "El tipo de proovedor es invalido" }');
    }

	try{
		ProveedorDAO::save($proveedor);
	}catch(Exception $e){
        Logger::log("Error al guardar el nuevo proveedor:" . $e);
	    die( '{"success": false, "reason": "Error" }' );
	}

	printf('{"success": true, "id": "%s"}' , $proveedor->getIdProveedor());
	Logger::log("Proveedor creado !");
}





/*
 * Surtir Sucursal de un proveedor.
 * 
 * Surte productos a una sucursal. Recibe el id del proveedor que surtira esta
 * sucursal, y un arreglo de objetos que describen el producto, la cantidad
 * que se ha surtido, y el precio de cada producto. Esta funcion se encarga de
 * incrementar el inventario de esta sucursal, e insertar los datos de la compra
 * en la tabla compra_sucursal, asi como insertar sus detalles en detalle_compra_sucursal
 *
 * @param int id_proveedor El id del proveedor que surtira esta sucursal
 * @param Object[] items La descripcion de los detalles de la compra.
 * @return boolean True si hubo exito, o false si la operacion fallo.
 * 
 * */
function surtirSucursalProveedor($id_proveedor,$jsonProductos){

	if($id_proveedor==NULL){die('{"success":false,"reason":"Faltan datos."}');}
	if($jsonProductos==NULL){die('{"success":false,"reason":"Faltan productos."}');}
	
	$productos=parseJSON($jsonProductos);
	$arrProductos=$productos->productos;
	DAO::transBegin();
			$compra_sucursal=new CompraSucursal();
		$compra_sucursal->setFecha(time());
		$compra_sucursal->setIdUsuario($_SESSION["userid"]);
		$compra_sucursal->setIdSucursal($_SESSION["sucursal"]);
		$compra_sucursal->setIdProveedor($id_proveedor);
		$cantExistencias=0;
		$cantExistenciasProc=0;
		$subtotal=0;
		$total=0;
		
	for($i=0;$i<sizeof($arrProductos);$i++)
	{
		$existe=$inventario=DetalleInventarioDAO::getByPK($arrProductos[$i]->id_producto,$_SESSION["sucursal"]);
		if($existe==NULL){
			die('{"success":false,"reason":"No existe el producto"}');
			DAO::transRollback();
		}
 		$cantExistencias+=$arrProductos[$i]->existenciasOriginales+$arrProductos[$i]->existenciasProcesadas;
		$cantExistenciasProc+=$arrProductos[$i]->existenciasProcesadas;
		$inventario->setExistencias($inventario->getExistencias()+$cantExistencias);
		$inventario->setExistenciasProcesadas($inventario->getExistenciasProcesadas()+$cantExistenciasProc);
		$subtotal+=$arrProductos[$i]->precioVenta*($cantExistencias+$cantExistenciasProc);

		try{
			DetalleInventarioDAO::save($inventario);
		}catch(Exception $e){
		Logger::log($e);
		die('{"success":false,"reason":"Error al intentar surtir la sucursal, intente de nuevo."}');
		}
	}
 			$total=$subtotal;
			$compra_sucursal->setSubtotal($subtotal);
			$compra_sucursal->setTotal($total);
			$compra_sucursal->setLiquidado(0);
			$compra_sucursal->setPagado(0);
			try{
				CompraSucursalDAO::save($compra_sucursal);

				$id_compra=$compra_sucursal->getIdCompra();
				$detalleCompraSucursal=new DetalleCompraSucursal();
				$detalleCompraSucursal->setIdCompra($id_compra);
					for($i=0;$i<sizeof($arrProductos);$i++)
					{
					$detalleCompraSucursal->setCantidad($arrProductos[$i]->existenciasOriginales);
					$detalleCompraSucursal->setDescuento(0);
					$detalleCompraSucursal->setIdProducto($arrProductos[$i]->id_producto);
				echo	$detalleCompraSucursal->setPrecio($arrProductos[$i]->precioVenta);
					$detalleCompraSucursal->setProcesadas($arrProductos[$i]->existenciasProcesadas);
						try{
							DetalleCompraSucursalDAO::save($detalleCompraSucursal);
						}catch(Exception $f){
							die('{"success":false,"reason":"Error al guardar detalle de compra."}');
						}
					}
				}catch(Exception $e){
				Logger::log($e);
				DAO::transRollback();
				die('{"success":false,"reason":"Error al intentar guardar la compra, intente de nuevo."}');
				}

	DAO::transEnd();
	echo '{"success":true}';
}

/*Surtir Sucursal de Proveedor
*/
function surtirSucursalDeProveedor($json){

	if($json==NULL){die('{"success":false,"reason":"Faltan datos."}');}
	
	$datos=parseJSON($json);
	$arrProductos=$datos->productos;
	DAO::transBegin();
			$compra_sucursal=new CompraSucursal();
		$compra_sucursal->setFecha(time());
		$compra_sucursal->setIdUsuario($_SESSION["userid"]);
		$compra_sucursal->setIdSucursal($_SESSION["sucursal"]);
		$compra_sucursal->setIdProveedor($datos->id_proveedor);
		$cantExistencias=0;
		$cantExistenciasProc=0;
		$subtotal=0;
		$total=0;
		
	for($i=0;$i<sizeof($arrProductos);$i++)
	{
		$existe=$inventario=DetalleInventarioDAO::getByPK($arrProductos[$i]->id_producto,$_SESSION["sucursal"]);
		if($existe==NULL){
			die('{"success":false,"reason":"No existe el producto"}');
			DAO::transRollback();
		}
 		//$cantExistencias+=$arrProductos[$i]->existenciasOriginales+$arrProductos[$i]->existenciasProcesadas;
		//$cantExistenciasProc+=$arrProductos[$i]->existenciasProcesadas;
		//$inventario->setExistencias($inventario->getExistencias()+$arrProductos[$i]->cantidad);
		$inventario->setExistenciasProcesadas($inventario->getExistenciasProcesadas()+$arrProductos[$i]->cantidad);
		$subtotal+=$arrProductos[$i]->precio*($arrProductos[$i]->cantidad);

		try{
			DetalleInventarioDAO::save($inventario);
		}catch(Exception $e){
		Logger::log($e);
		die('{"success":false,"reason":"Error al intentar surtir la sucursal, intente de nuevo."}');
		}
	}
 			$total=$subtotal;
			$compra_sucursal->setSubtotal($subtotal);
			$compra_sucursal->setTotal($total);
			$compra_sucursal->setLiquidado(0);
			$compra_sucursal->setPagado(0);
			try{
				CompraSucursalDAO::save($compra_sucursal);

				$id_compra=$compra_sucursal->getIdCompra();
				$detalleCompraSucursal=new DetalleCompraSucursal();
				$detalleCompraSucursal->setIdCompra($id_compra);
					for($i=0;$i<sizeof($arrProductos);$i++)
					{
					$detalleCompraSucursal->setCantidad($arrProductos[$i]->cantidad);
					$detalleCompraSucursal->setDescuento(0);
					$detalleCompraSucursal->setIdProducto($arrProductos[$i]->id_producto);
					$detalleCompraSucursal->setPrecio($arrProductos[$i]->precio);
					$detalleCompraSucursal->setProcesadas($arrProductos[$i]->cantidad);
						try{
							DetalleCompraSucursalDAO::save($detalleCompraSucursal);
						}catch(Exception $f){
							die('{"success":false,"reason":"Error al guardar detalle de compra."}');
						}
					}
				}catch(Exception $e){
				Logger::log($e);
				DAO::transRollback();
				die('{"success":false,"reason":"Error al intentar guardar la compra, intente de nuevo."}');
				}

	$autorizacion=new Autorizacion();
	$autorizacion->setEstado(3);
	$autorizacion->setFechaPeticion(time());
	$autorizacion->setFechaRespuesta(time());
	$autorizacion->setIdSucursal($_SESSION["sucursal"]);
	$autorizacion->setIdUsuario($_SESSION["userid"]);
	$products='[';
	for($i=0;$i<sizeof($arrProductos);$i++)
	{
		$products.='{"id_producto":'.$arrProductos[$i]->id_producto.',';
		$products.='"procesado":true,';
		$products.='"cantidad_procesada":'.$arrProductos[$i]->cantidad.',';
		$products.='"cantidad":0,';
		$products.='"precio":'.$arrProductos[$i]->precio;
		$products.='}';
		if($i==(sizeof($arrProductos)-1)){
		}else{
			$products.=',';
		}
	}
	$products.=']';
	$parametros='{"clave":209,"descripcion":"Envio de Productos","productos":'.$products.'}';
	$autorizacion->setParametros($parametros);
	$autorizacion->setTipo("envioDeProductos");
	
	try{
		AutorizacionDAO::save($autorizacion);
	}catch(Exception $f){
		DAO::transEnd();
		die('{"success":false,"reason":"Error al guardar autorizacion."}');
	}
	DAO::transEnd();
	echo '{"success":true}';
}


/*
 * Listar proveedores.
 * 
 * Regresa un arreglo de objetos {@link Proveedor}. Si su argumento
 * <i>sucursal</i> esta en verdadero. Solo regresara los proveedores 
 * que pueden surtir una sucursal.
 * 
 * @param sucursal Verdadero para regresar solo los proveedores que pueden surtir una sucursal
 * @return Proveedor[] Un arreglo de objetos Proveedor.
 * 
 * */
function listarProveedores( $sucursal = true ){


	$total_customers = array();
	
	//buscar clientes que esten activos
	$foo = new Proveedor();
	$foo->setActivo("1");
	$foo->setTipoProveedor("ambos");
	$arrAmbos=ProveedorDAO::search($foo);

	$bar = new Proveedor();
	$bar->setActivo("1");
	$bar->setTipoProveedor("sucursal");
	$arrSucursal=ProveedorDAO::search($bar);
	$proveedores=array_merge($arrAmbos,$arrSucursal);
	$prov='[';
//	$proveedores = ProveedorDAO::byRange($foo, $bar);
		for($i=0;$i<sizeof($proveedores);$i++){
			if($i==sizeof($proveedores)-1){$prov.=$proveedores[$i];
			}else{$prov.=$proveedores[$i].',';}
		}
	Logger::log("Listando Yproveedores");
	$prov.=']';
	return $prov;

}








function editarProveedor( $json = null ){

	if( $json == null ){
        Logger::log("No hay parametros para editar proveedor.");
		die('{"success": false, "reason": "Parametros invalidos. no hay datos" }');
	}
	
	
	$data = parseJSON( $json );	


	if($data == null){
        Logger::log("Json invalido para modificar proveedor: " . $json);
		die('{"success": false, "reason": "Parametros invalidos. json invalido 1" }');	
	}

	//minimo debio haber mandado el id_proveedor
	if(!isset($data->id_proveedor)){
//	var_dump($data);
		Logger::log("Json invalido para modificar proveedor: " . $data->id_proveedor);
		die('{"success": false, "reason": "Parametros invalidos. json invalido 2" }');	
	}

	//crear el objeto de proveedor a ingresar
	$proveedor = ProveedorDAO::getByPK ( $data->id_proveedor );

	
	if( !$proveedor ){
        Logger::log("No existe el proveedor " . $data->id_proveedor);
		die ( '{"success": false, "reason": "Este proveedor no existe." }' );
	}
	
	if( isset($data->rfc) )
		$proveedor->setRfc( $data->rfc );
	
	if( isset($data->nombre) )
		$proveedor->setNombre( $data->nombre );
		
	if( isset($data->direccion) )
		$proveedor->setDireccion( $data->direccion );		
	
	if( isset($data->telefono) )		
		$proveedor->setTelefono( $data->telefono );
	
	if( isset($data->e_mail) )		
		$proveedor->setEMail( $data->e_mail );
	
	if( isset($data->activo) )		
		$proveedor->setActivo ( $data->activo );

	try{
       ProveedorDAO::save($proveedor);

	} catch(Exception $e) {
	
        Logger::log("Error al guardar modificacion del proveedor " . $e);
	    die( '{"success": false, "reason": "Error. Porfavor intente de nuevo." }' );
	}

   printf( '{"success": true, "id": "%s"}' , $proveedor->getIdProveedor() );
   Logger::log("Proveedor " . $proveedor->getIdProveedor() . " modificado !");
}




/*
 * 
 * 	Case dispatching for proxy
 * 
 * */
if(isset($args['action'])){
	switch($args['action'])
	{
		#lista de proveedores
		case 900:
			try{
				printf('{ "success": true, "payload": %s }',  listarProveedores() );
	    	}catch(Exception $e){
	    		Logger::log($e);
				printf('{ "success": false, "reason": "Error, porfavor intente de nuevo." }' );	    	
	    	}
		break;


		#crear un nuevo proveedor
		case 901:
			if($_SESSION['grupo'] < 1 || $_SESSION['grupo'] > 2)
	        {
				Logger::log("Nuevo proveedor : No tiene privilegios para hacer esto");
				die( '{ "success": false, "reason": "No tiene privilegios para hacer esto." }' ) ;
			}

			if( !isset( $args['data'] ) )
			{
				Logger::log("Nuevo proveedor : Faltan parametros para crear el proveedor");
				die( '{ "success": false, "reason": "Parametros invalidos." }' ) ;
			}
			
			nuevoProveedor( $args['data'] );
		break;


		#editar un proveedor
		case 902:
			if( $_SESSION['grupo'] != 1 )
	        {
				Logger::log("Editar proveedor : No tiene privilegios para hacer esto");
				die( '{ "success": false, "reason": "No tiene privilegios para hacer esto." }' ) ;
			}
			
			if( !isset( $args['data'] ) )
			{
				Logger::log("Editar proveedor : Faltan parametros para mododificar el proveedor");
				die( '{ "success": false, "reason": "Parametros invalidos." }' ) ;
			}
			
			editarProveedor( $args['data'] );
		break;
		
		case 903:
			if(!isset($args["id_proveedor"])){
				Logger::log("Surtir sucursal : Faltan parametros para surtir sucursal");
				die( '{ "success": false, "reason": "Parametros invalidos." }' ) ;
			}
			if(!isset($args["productos"])){
				Logger::log("Surtir sucursal : Faltan parametros para surtir sucursal");
				die( '{ "success": false, "reason": "Parametros invalidos." }' ) ;
			}
			surtirSucursalProveedor($args["id_proveedor"],$args["productos"]);
		break;
		
		case 904:
			if(!isset($args["data"])){
				Logger::log("Surtir sucursal : Faltan parametros para surtir sucursal");
				die( '{ "success": false, "reason": "Parametros invalidos." }' ) ;
			}
			surtirSucursalDeProveedor($args["data"]);
		break;

	}
	
}
