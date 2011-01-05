<?php

require_once('model/sucursal.dao.php');
require_once('model/ventas.dao.php');
require_once('model/usuario.dao.php');
require_once('model/grupos_usuarios.dao.php');
require_once('model/cliente.dao.php');
require_once('logger.php');

function listarSucursales(  ){

    $s = new Sucursal();
    $s->setActivo(1);

    $sucursales = SucursalDAO::search($s);

    $array_sucursales = array();

    foreach( $sucursales as $sucursal )
    {
        array_push( $array_sucursales , array(
            'id_sucursal' => $sucursal->getIdSucursal(),
            'descripcion' => $sucursal->getDescripcion(),
            'text' => $sucursal->getDescripcion(),
            'value' => $sucursal->getIdSucursal()
        ));
    }

	return $array_sucursales;
}


function ventasSucursal( $sid = null){
	
	if(!$sid){
        return null;
    }

	$cC = array();
  
    $ventas = new Ventas();
    $ventas->setIdSucursal($sid);
    
    if(isset($tipo_venta))
    {
        $ventas->setTipoVenta($tipo_venta);
    }

    $comprasCliente = VentasDAO::search($ventas);


	foreach( $comprasCliente as $c )
	{
		//make readable data
	
		$sucursal = SucursalDAO::getByPK( $c->getIdSucursal() );
		$cajero = UsuarioDAO::getByPK( $c->getIdUsuario() );
		
		if($c->getIdCliente() < 0){
			$cliente = "Caja Comun";
		}else{
			$cliente = ClienteDAO::getByPK( $c->getIdCliente() )->getNombre();
		}
		

				
		$data = array(
			"cajero" => $cajero ? $cajero->getNombre() : "<b>Error</b>",
			"sucursal" => $sucursal ? $sucursal->getDescripcion() : "<b>Error</b>",
			"descuento" => $c->getDescuento(),
			"fecha" => $c->getFecha(),
			"id_cliente" => $c->getIdCliente(),
			"cliente" => $cliente,
			"id_sucursal" => $c->getIdSucursal(),
			"id_usuario" => $c->getIdUsuario(),
			"id_venta" => $c->getIdVenta(),
			"ip" => $c->getIp(),
			"iva" => $c->getIva(),
			"pagado" => $c->getPagado(),
			"subtotal" => $c->getSubtotal(),
			"tipo_venta" => $c->getTipoVenta(),
			"total" => $c->getTotal()
		);
		

		
		array_push( $cC, $data );
	}

	return $cC;
	
}


function detallesSucursal( $sid = null ){
	if(!$sid){
		return null;
	}
	
	return SucursalDAO::getByPK($sid);
	
}




/*
 *
 *	@param detalles( gerente, descripcion, direccion, prefijo_factura, rfc, telefono )
 *
 *
 * */

function abrirSucursal( $json = null )
{

	//solo el admin puede abrir sucursales
	if($_SESSION['grupo'] != 1){
		die('{"success" : false, "reason": "Accesso denegado."}');
	}

	if(!isset($json) || $json == null){
		Logger::log("Parametros invalidos para abrir sucursal");
		die('{"success" : false, "reason": "Parametros invalidos."}');
	}

	$foo = $json;
    $json = parseJSON( $json );

	if($json == null){
		Logger::log("Parametros invalidos para abrir sucursal:" . $foo );	
		die('{"success" : false, "reason": "Parametros invalidos."}');	
	}

	if(!( isset($json->gerente) &&
			isset($json->descripcion) &&
			isset($json->direccion) &&			
			isset($json->prefijo_factura) &&
			isset($json->rfc) &&
			isset($json->telefono)))
	{
		Logger::log("Parametros invalidos para abrir sucursal:" . $foo);
		die('{"success" : false, "reason": "Parametros invalidos."}');
	}
	
	
	if(( strlen($json->gerente) < 1 ||
			strlen($json->descripcion) < 1 ||
			strlen($json->direccion) < 1 ||			
			strlen($json->prefijo_factura) < 1 ||
			strlen($json->rfc) < 1 ||
			strlen($json->telefono) < 1 ))
	{
		Logger::log("Parametros invalidos para abrir sucursal:" . $foo);	
		die('{"success" : false, "reason": "Parametros invalidos."}');
	}	
	
    $gerente = UsuarioDAO::getByPK($json->gerente);
    
    if($gerente == null){
   		Logger::log("Gerente no existe en lista de usuarios:" . $foo);
		die('{"success" : false, "reason": "Este usuario no existe."}');    
    }
    
    
    //revisar que pertenesca al grupo de gerentes
    $gu = GruposUsuariosDAO::getByPK($json->gerente);
    if($gu->getIdGrupo() != 2){
		die('{"success" : false, "reason": "Este usuario no pertenece al grupo de gerentes."}');    
    }
    
    
    $sucursal = new Sucursal();

    //validar los datos
    //revisar que no sea gerente ya de una sucursal
    $suc = new Sucursal();
    $suc->setGerente( $json->gerente );

    if(sizeof(SucursalDAO::search( $suc )) > 0 ){
		die('{"success" : false, "reason": "Este empleado ya es gerente de una sucursal."}');
    }

    $sucursal->setActivo 		( "1");
    $sucursal->setDescripcion	( $json->descripcion );
    $sucursal->setDireccion 	( $json->direccion);
    $sucursal->setGerente 		( $json->gerente);
    $sucursal->setLetrasFactura ( $json->prefijo_factura);
    $sucursal->setRfc 			( $json->rfc);
    $sucursal->setTelefono 		( $json->telefono);
    $sucursal->setSaldoAfavor 	( 0 );

	DAO::transBegin();

    try{
        SucursalDAO::save( $sucursal );
    }catch( Exception $e ){
        DAO::transRollback();
        Logger::log("Error al insertar nueva sucursal " . $e);
		die('{"success" : false, "reason": "Error, intente de nuevo."}');
    }


    //mover a este gerente a la nueva sucursal
    $gerente->setIdSucursal( $sucursal->getIdSucursal() );

    try{
		UsuarioDAO::save( $gerente );
    }catch( Exception $e ){
		DAO::transRollback();
    	Logger::log($e);
    	die('{"success" : false, "reason": "Error, porfavor intente de nuevo."}');
    }


    //crear su caaja comun
    $cajaComun = new Cliente();

    $cajaComun->setActivo 		( 1 	);
    $cajaComun->setCiudad 		( "" 	);
    $cajaComun->setDescuento 	( 0		);
    $cajaComun->setDireccion 	( ""	);
    $cajaComun->setEMail 		( ""	);
    $cajaComun->setIdCliente 	( "-" . $sucursal->getIdSucursal() );
    $cajaComun->setIdSucursal	( $sucursal->getIdSucursal() );
    $cajaComun->setIdUsuario 	( $_SESSION['userid'] );
    $cajaComun->setLimiteCredito( 0		);
    $cajaComun->setNombre 		( "Caja Comun"		);
    $cajaComun->setRfc 			( $json->rfc	);
    $cajaComun->setTelefono 	( $json->telefono );

    try{
        ClienteDAO::save( $cajaComun );
    }catch( Exception $e ){
     	DAO::transRollback();
     	Logger::log($e);
        die('{"success" : false, "reason": "Error, porfavor intente de nuevo."}'); 
    }


	DAO::transEnd();
    echo '{"success" : true, "nid": '.$sucursal->getIdSucursal().' }'; 
    Logger::log("Sucursal ". $sucursal->getIdSucursal(). " creada !");
    return;


}





function editarSucursal($sid, $payloadJSON, $verbose = true ){

    Logger::log("editar detalles de sucursal iniciado...");

    $suc = SucursalDAO::getByPK($sid);

    if(sizeof($suc) < 1){
        if($verbose){
            echo '{ "success" : false, "reason" : "Esta sucursal no existe." }';
        }
        Logger::log("intentando editar una sucursal que no existe");
        return false;
    }

    try{
        $payload = parseJSON( $payloadJSON );

    }catch(Exception $e){
        if($verbose){
            echo '{ "success" : false, "reason" : "Invalid JSON." }';
        }
        Logger::log("json invalido " . $e);
        return false;
    }

    if($payload === null){
        if($verbose){
            echo '{ "success" : false, "reason" : "Invalid DATA." }';
        }

        return false;
    }

    if(isset($payload->descripcion)){
        $suc->setDescripcion($payload->descripcion);
    }

    if(isset($payload->direccion)){
        $suc->setDireccion($payload->direccion);
    }

    if(isset($payload->letras_factura)){
        $suc->setLetrasFactura($payload->letras_factura);
    }

    if(isset($payload->rfc)){
        $suc->setRFC($payload->rfc);
    }

    if(isset($payload->telefono)){
        $suc->setTelefono($payload->telefono);
    }

    try{
        SucursalDAO::save($suc);
    }catch(Exception $e){
        if($verbose){
            echo '{ "success" : false, "reason" : "'.$e.'" }';
        }
        Logger::log("error al editar sucursal " . $e);
        return false;
    }

    if($verbose){
        echo '{ "success" : true }';
    }

    Logger::log("detalles de sucursal editados !");
    return true;
}





function cerrarSucursal($sid, $verbose = true){

    Logger::log("CERRANDO SUCURSAL INICIADO", 2);

    $suc = SucursalDAO::getByPK($sid);

    if(sizeof($suc) < 1){
        if($verbose){
            echo '{ "success" : false, "reason" : "Esta sucursal no existe." }';
        }

        return false;
    }

    //verificar que no este ya cerrada
    if($suc->getActivo() == "0"){
        if($verbose){
            echo '{ "success" : false, "reason" : "Esta sucursal ya esta cerrada." }';
        }

        return false;
    }
    
    //obtener el gerente de esta sucursal
    $gerente = $suc->getGerente();

    if($gerente !== null){
        //desasignar a este gerente
        $gU = UsuarioDAO::getByPK( $gerente );
        $gU->setIdSucursal(null);
        UsuarioDAO::save($gU);
    }



    $suc->setGerente(null);
    $suc->setActivo(0);
    SucursalDAO::save( $suc );


    if($verbose){
        echo '{ "success" : true }';
    }

    return true;
}






function listarPersonal(){

}

function estadisticasVentas(){

}

function presindirEmpleado(){

}

function agregarGerente(){

}

function corte(){

}

function clientesDeudores(){
	
}

function inventarioSucursal(){
	//esta ya esta en inventario
}

//obtiene la informacion de la sucursal actual
function informacionSucursal(){
	
	if( !isset($_SESSION['sucursal']) ){
		die( '{"success": false, "reason": "Su cuenta no esta ligada a una sucursal especifica." }' );	
	}
	
	if( !( $sucursal = SucursalDAO::getByPK( $_SESSION['sucursal'] ) )  )
    {
        die( '{"success": false, "reason": "No se tiene registros de esa sucursal." }' );
    }
	
	printf( '{ "success": true, "datos": %s }',  $sucursal );
	
}

if(isset($args['action'])){

	switch( $args['action'] )
	{
		case 700://listar sucursales
		    printf('{"success" : true, "datos": %s}', json_encode( listarSucursales(  ) ) );
		break;

		case 701://abrir sucursal
		
			if(!isset($args['data'])){
				die('{"success" : false, "reason": "Parametros invalidos."}');
			}
			
			abrirSucursal( $args['data'] );
			
		break;

		case 702://editar detalle sucursal
			if(!isset($args['sid']) || !isset($args['payload'])){
				die('{"success" : false, "reason": "Parametros invalidos."}');
			}
			
		    editarSucursal( $args['sid'], $args['payload'] );
		break;

		case 703://cerrar sucursal
			if(!isset($args['sid'])){
				die('{"success" : false, "reason": "Parametros invalidos."}');
			}
		    cerrarSucursal( $args['sid'] );
		break;

		case 704://listar personal
		    listarPersonal();
		break;

		/*
		case 705://estadisticas de venta por empleado
		    estadisticasVentas( $args );
		break;
		*/
		case 706://presindir empleado
		    presindirEmpleado( $args );
		break;

		case 707://agregar gerentes
		    agregarGerente( $args );
		break;

		case 708://hacer corte
		    corte( $args );
		break;

		case 709://clientes deudores sucursal (arrojara le total de las deudas de la sucursal)
		    clientesDeudores( $args );
		break;

		case 710://flujo de efectivo
		    
		break;

		case 711://inventario por sucursal
		    inventarioSucursal( $args );
		break;
		
		case 712:
			informacionSucursal(  );
		break;

	}
}

