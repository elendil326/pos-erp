<?php

/**
 *
 */
/**
 *
 */
 
//require_once('../server/model/base/cliente.vo.base.php');

/**
 *
 */
require_once('../server/model/cliente.dao.php');



/**
 *
 * @param <type> $rfc
 * @param <type> $nombre
 * @param <type> $direccion
 * @param <type> $limite_credito
 * @param <type> $descuento
 * @param <type> $telefono
 * @param <type> $e_mail
 * @return string JSON con la respuesta para el cliente.
 * @todo validad RFC con expresión regular.
 * @access private
 */
function save_customer($id, $rfc, $nombre, $direccion, $limite_credito, $telefono, $e_mail, $descuento = 0) {
    //$data = array('rfc' => $rfc, 'nombre' => $nombre, 'telefono' => $telefono, 'e_mail' => $e_mail, 'limite_credito' => $limite_credito, 'descuento' => $descuento, 'direccion' => $direccion);

    if ($limite_credito < 0.0) {
        return "{success: false, reason: 'El limite de crédito debe ser mayor a cero.' }";
    }

    if ($descuento < 0.0 || $descuento > 100) {
        return "{success: false, reason: 'El descuento debe ser menor a 100' }";
    }

    //validar RFC
	
	$cliente = new Cliente();
	
	if( is_numeric($id) ){

		$cliente->setIdCliente( $id );
	}
    
	$cliente->setRfc( $rfc );
	$cliente->setNombre( $nombre );
	$cliente->setDireccion( $direccion );
	$cliente->setLimiteCredito( $limite_credito );
	$cliente->setDescuento( $descuento );
	$cliente->setTelefono( $telefono );
	$cliente->setEmail( $e_mail );
	
    $ans = ClienteDAO::save($cliente);

    if ($ans) {
        return sprintf("{success: true, reason: 'Se inserto el cliente con id %s, %d'}", $cliente->getIdCliente(),$out);
    } else {
        return "{success: false, reason: 'No se inserto el cliente.' }";
    }
}

/**
 *
 * @param <type> $id
 * @param <type> $rfc
 * @param <type> $nombre
 * @param <type> $direccion
 * @param <type> $limite_credito
 * @param <type> $descuento
 * @param <type> $telefono
 * @param <type> $e_mail
 */
function update_customer($id, $rfc, $nombre, $direccion, $limite_credito, $telefono, $e_mail, $descuento = 0) {
	
	if ($limite_credito < 0.0) {
        return "{success: false, reason: 'El limite de crédito debe ser mayor a cero.' }";
    }

    if ($descuento < 0.0 || $descuento > 100) {
        return "{success: false, reason: 'El descuento debe ser menor a 100' }";
    }

    //validar RFC
	
	$cliente = new Cliente();
	$cliente->setIdCliente( $id );

    $cliente->setRfc( $rfc );
	$cliente->setNombre( $nombre );
	$cliente->setDireccion( $direccion );
	$cliente->setLimiteCredito( $limite_credito );
	$cliente->setDescuento( $descuento );
	$cliente->setTelefono( $telefono );
	$cliente->setEmail( $e_mail );
	
    $ans = ClienteDAO::save($cliente);
	
    if ($ans) {
        return "{success: true, reason: 'Se modifico el cliente correctamente. AR:".$ans."'}";
    } else {
        return "{success: false, reason: 'No se modifico el cliente. AR:".$ans."' }";
    }
}

/**
 *
 * @param <type> $rfc
 * @param <type> $nombre
 * @param <type> $direccion
 * @param <type> $limite_credito
 * @param <type> $descuento
 * @param <type> $telefono
 * @param <type> $e_mail 
 */
function insert_customer($id, $rfc, $nombre, $direccion, $limite_credito, $telefono, $e_mail, $descuento = 0) {
    
	if ($limite_credito < 0.0) {
        return "{success: false, reason: 'El limite de crédito debe ser mayor a cero.' }";
    }

    if ($descuento < 0.0 || $descuento > 100) {
        return "{success: false, reason: 'El descuento debe ser menor a 100' }";
    }

    //validar RFC
	
	$cliente = new Cliente();
    
	$cliente->setRfc( $rfc );
	$cliente->setNombre( $nombre );
	$cliente->setDireccion( $direccion );
	$cliente->setLimiteCredito( $limite_credito );
	$cliente->setDescuento( $descuento );
	$cliente->setTelefono( $telefono );
	$cliente->setEmail( $e_mail );
	
    $ans = ClienteDAO::save($cliente);

    if ($ans) {
        return sprintf("{success: true, reason: 'Se inserto el cliente con id %s'}", $cliente->getIdCliente());
    } else {
        return "{success: false, reason: 'No se inserto el cliente.' }";
    }
}

/**
 *
 * @param <type> $id_cliente 
 */
function delete_customer($id_cliente) {
    if (!is_int($id_cliente)){
        return "{success: false, reason: 'Id no válido.' }";
    }
    $cliente = ClienteDAO::getByPK($id_cliente);
    if (is_object($cliente)) {
        $ans = ClienteDAO::delete($cliente);

        if ($ans) {
            return sprintf("{success: true, reason: 'Se borro el cliente con id %s'}", $cliente->getIdCliente());
        } else {
            return "{success: false, reason: 'No se borro el cliente.' }";
        }
    } else {
        return "{success: false, reason: 'No existe cliente con ese id.' }";
    }
}

function list_customers() {
	$clientes = ClienteDAO::getAll();
    $ans = '';
	if( count($clientes) > 0 ){
		foreach ($clientes as $cliente){
				if( $cliente->getIdCliente() > 0 ){
					$tmp = substr( $cliente, 1, -1 );//[{jgkjgk}] -> {jgkjgk}
					$ans .= $tmp."," ;
				}
		}
		$out = substr($ans,0,-1);
		$ans = sprintf("{ success: true, datos:[%s] }", $out);
		return $ans;
	}else{
		return "{success: false, reason: 'No hay lista de Clientes' }";
	}
 
}

/**
 *
 * @param <type> $id_cliente 
 */
function show_customer($id_cliente) {
    if (!is_numeric($id_cliente)) {
        return "{success: false, reason: 'Id no válido.' }";
    }
    $cliente = ClienteDAO::getByPK($id_cliente);
    if (is_object($cliente)) {
        return "{success: true, datos: ".$cliente." }";
    } else {
        return "{success: false, reason: 'No existe cliente con ese id.' }";
    }
}



function getGridDataAllClientes($page,$rp,$sortname,$sortorder,$search,$qtype)
{
	$clientes = ClienteDAO::getClientesAll_grid($page,$rp,$sortname,$sortorder,$search,$qtype);
	
	//$array_result = '{ "page": '.$page.', "total": '.count($clientes).', "rows" : '.json_encode($clientes).'}';
	$array_result = '{ "page": '.$page.', "total": '.$clientes['total'].', "rows" : '.json_encode($clientes['data']).'}';
	return $array_result;
}



/**
*	Funcion para obtener el json formateado de los clientes que compraron a credito y deben dineros
*	para que sea leido por el Flexigrid
*
*	@author Rene Michel <rene@caffeina.mx>
*	@return	String JSON con los datos formateados para Flexigrid	
*/
function getGridDataClientesCreditoDeudores($page,$rp,$sortname,$sortorder,$search,$qtype, $de, $al, $id_cliente){
	
	
	
	$clientes = ClienteDAO::getClientesCreditoDeudores_grid($page, $rp,$sortname,$sortorder,$search,$qtype,$de, $al, $id_cliente);
	
	$array_result = '{ "page": '.$page.', "total": '.$clientes['total'].', "rows" : '.json_encode($clientes['data']).'}';
	return $array_result;

}

function getGridDataClientesCreditoPagado($page,$rp,$sortname,$sortorder,$search,$qtype, $de, $al, $id_cliente){

	$clientes = ClienteDAO::getClientesCreditoPagado_grid($page, $rp,$sortname,$sortorder,$search,$qtype, $de, $al, $id_cliente);
	
	//$array_result = '{ "page": '.$page.', "total": '.count($clientes).', "rows" : '.json_encode($clientes).'}';
	$array_result = '{ "page": '.$page.', "total": '.$clientes['total'].', "rows" : '.json_encode($clientes['data']).'}';
	return $array_result;
}


//Clientes dispatcher
switch ($args['action']) {
    case '1001':
        $rfc = $args['rfc'];
        $nombre = $args['nombre'];
        $direccion = $args['direccion'];
        $limite_credito = $args['limite_credito'];
        $telefono = $args['telefono'];
        $e_mail = $args['e_mail'];
        unset($args);

        $ans = insert_customer($id, $rfc, $nombre, $direccion, $limite_credito, $telefono, $e_mail, $descuento = 0);
        echo $ans;
	break;
	
	case '1002':
	
		$id = $args['id'];
        $rfc = $args['rfc'];
        $nombre = $args['nombre'];
        $direccion = $args['direccion'];
        $limite_credito = $args['limite_credito'];
        $telefono = $args['telefono'];
        $e_mail = $args['e_mail'];
		$descuento = $args['descuento'];

        $ans = update_customer($id, $rfc, $nombre, $direccion, $limite_credito, $telefono, $e_mail, $descuento = 0);
        echo $ans;
	break;
	
	case '1003':
		$id = $args['id'];
        unset($args);
        $ans = delete_customer($id);
        echo $ans;
	break;
	
	case '1004':
		$id = $args['id'];
        unset($args);

        $ans = show_customer($id);
        echo $ans;
	break;
	
	case '1005':
        unset($args);

        $ans = list_customers();
        echo $ans;
	break;
	
	case '1006': 	//'getGridDataClientesCreditoDeudores':
        
        	
        
        	@$id_cliente=$args['id_cliente'];
                @$de=$args['de'];
                @$al=$args['al'];
                
        	@$page = strip_tags($args['page']);
		@$rp = strip_tags($args['rp']);
		@$sortname = strip_tags($args['sortname']);
		@$sortorder = strip_tags($args['sortorder']);
		
		if(isset($args['query']) && !empty($args['query']))
		{
		        $search = strip_tags($args['query']);
		        @$qtype = strip_tags($args['qtype']);
		}
		
		//Si no se envia el dato de page, significa que estamos en la 1
		if(isset($args['page']) && !empty($args['page']))
		{
			$page = strip_tags($args['page']);
		}
		else{
			$page = 1;
		}
        
        	$ans = getGridDataClientesCreditoDeudores($page,$rp,$sortname,$sortorder,$search,$qtype, $de, $al, $id_cliente);
        	echo $ans;
        
        	break;
        	
       
       case '1007': 	//'getGridDataClientesCreditoPagado':
       
       		
        
        	@$id_cliente=$args['id_cliente'];
                @$de=$args['de'];
                @$al=$args['al'];
                
        	@$page = strip_tags($args['page']);
		@$rp = strip_tags($args['rp']);
		@$sortname = strip_tags($args['sortname']);
		@$sortorder = strip_tags($args['sortorder']);
		
		if(isset($args['query']) && !empty($args['query']))
		{
		        $search = strip_tags($args['query']);
		        @$qtype = strip_tags($args['qtype']);
		}
		
		//Si no se envia el dato de page, significa que estamos en la 1
		if(isset($args['page']))
		{
			$page = strip_tags($args['page']);
		}
		else{
			$page = 1;
		}
        
        	$ans = getGridDataClientesCreditoPagado($page,$rp,$sortname,$sortorder,$search,$qtype, $de, $al, $id_cliente);
        	echo $ans;
       
       
       		break;
        	
       
       case '1008': //'getGridDataAllClientes':
       
       		
       
       		@$page = strip_tags($args['page']);
		@$rp = strip_tags($args['rp']);
		@$sortname = strip_tags($args['sortname']);
		@$sortorder = strip_tags($args['sortorder']);
		
		if(isset($args['query']) && !empty($args['query']))
		{
		        $search = strip_tags($args['query']);
		        @$qtype = strip_tags($args['qtype']);
		}
		
		//Si no se envia el dato de page, significa que estamos en la 1
		if(isset($args['page']))
		{
			$page = strip_tags($args['page']);
		}
		else{
			$page = 1;
		}
        
        	$ans = getGridDataAllClientes($page,$rp,$sortname,$sortorder,$search,$qtype);
        	echo $ans;
       		
       		break;

}//end switch



