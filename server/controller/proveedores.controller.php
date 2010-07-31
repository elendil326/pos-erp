<?php

/**
 * Este archivo contiene las funciones necesarias para realizar las acciones
 * correspondientes con un proveedor.
 */
/**
 * Importa el bean 
 */


/**
 *
 */
require_once('../server/model/proveedor.dao.php');


/**
 *
 * @param <type> $rfc
 * @param <type> $nombre
 * @param <type> $direccion
 * @param <type> $telefono
 * @param <type> $e_mail 
 */
function insert_provider($rfc, $nombre, $direccion, $telefono , $e_mail) {
	
    return save_provider(null, $rfc, $nombre, $direccion, $telefono , $e_mail);
}

/**
 *
 * @param <type> $id
 * @param <type> $rfc
 * @param <type> $nombre
 * @param <type> $direccion
 * @param <type> $telefono
 * @param <type> $e_mail
 */
function update_provider($id, $rfc, $nombre, $direccion, $telefono , $e_mail) {
    return save_provider($id, $rfc, $nombre, $direccion, $telefono, $e_mail);
}

/**
 *
 * @param <type> $rfc
 * @param <type> $nombre
 * @param <type> $direccion
 * @param <type> $telefono
 * @param <type> $e_mail
 * @return string JSON con la respuesta para el proveedor.
 * @todo validad RFC con expresión regular.
 * @access private
 */
function save_provider($id, $rfc, $nombre, $direccion, $telefono , $e_mail) {
    //validar RFC
	
    $proveedor = new Proveedor();
	
	if( is_int($id) )
		$proveedor->setIdProveedor( $id );
	
	$proveedor->setRfc( $rfc );
	$proveedor->setNombre( $nombre );
	$proveedor->setDireccion( $direccion );
	$proveedor->setTelefono( $telefono );
	$proveedor->setEmail( $e_mail );
	
    $ans = ProveedorDAO::save($proveedor);
    if ( $ans > 0 ) {
        return "{ success: true , reason: 'Se inserto el proveedor' }";
    } else {	 
        return "{success: false, reason: 'No se inserto el proveedor.' }";
    }
}

/**
 *
 * @param <type> $id_proveedor
 */
function delete_provider($id_proveedor) {
    if (!is_int($id_proveedor)){
        return "{success: false, reason: 'Id no válido.' }";
    }
    $proveedor = ProveedorDAO::getByPK($id_proveedor);
    if (is_object($proveedor)) {
        $ans = ProveedorDAO::delete($proveedor);

        if ($ans) {
            return sprintf("{success: true, reason: 'Se borro el proveedor con id %s'}", $proveedor->getIdProveedor());
        } else {
            return "{success: false, reason: 'No se borro el proveedor.' }";
        }
    } else {
        return "{success: false, reason: 'No existe proveedor con ese id.' }";
    }
}

/**
 *
 * @param <type> $id_proveedor
 */
function show_provider($id_proveedor) {
    if (!is_int($id_proveedor)) {
        return "{success: false, reason: 'Id no válido.' }";
    }
    $proveedor = ProveedorDAO::getByPK($id_proveedor);
    if (is_object($proveedor)) {
        return sprintf("success: true, datos: %s", $proveedor->getJSON());
    } else {
        return "{success: false, reason: 'No existe proveedor con ese id.' }";
    }
}

function list_providers() {
    $proveedores = ProveedorDAO::getAll();
	if( count($proveedores) > 0 ){
		$ans = '';
		foreach ($proveedores as $proveedor) :
				//$ans .= sprintf("%s,", $proveedor->getJSON());
				$tmp = substr( $proveedor, 1, -1 );//[{jgkjgk}] -> {jgkjgk}
				$ans .= $tmp."," ;
		endforeach;
		$out = substr($ans,0,-1);
		$ans = sprintf("{success:true, datos:[%s] }", $out);
		return $ans;
	}else{
		return "{success: false, reason: 'No hay lista de proveedores' }";
	}
}

?>