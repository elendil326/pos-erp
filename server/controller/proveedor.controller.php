<?php 
/** Clientes Controller.
  * 
  * Este archivo es la capa entre la interfaz de usuario y peticiones ajaxa y los 
  * procedimientos para realizar las operaciones sobre Clientes. 
  * @author Alan Gonzalez <alan@caffeina.mx>, Manuel Garcia Carmona <manuel@caffeina.mx>
  * 
  */

require_once('model/proveedor.dao.php');
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
function listarProveedores( $sucursal = false ){
	$total_customers = array();
	
	//buscar clientes que esten activos
	$foo = new Proveedor();
	$foo->setIdProveedor("0");
	$foo->setActivo("1");


	$bar = new Proveedor();
	$bar->setIdProveedor("9999");
	$bar->setActivo("1");

	$proveedores = ProveedorDAO::byRange($foo, $bar);

	Logger::log("Listando ´proveedores");
	
	return $proveedores;

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
		Logger::log("Json invalido para modificar proveedor: " . $data);
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
		$proveedore->setEMail( $data->e_mail );
	
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
				printf('{ "success": true, "payload": %s }',  json_encode(listarProveedores()) );
	    	}catch(Exception $e){
	    		Logger::log($e);
				printf('{ "success": false, "reason": "Error, porfavor intente de nuevo." }' );	    	
	    	}
		break;


		#crear un nuevo proveedor
		case 901:
			if($_SESSION['grupo'] != 1)
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

	}
	
}
