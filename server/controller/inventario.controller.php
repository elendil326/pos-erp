<?php 

require_once('model/inventario.dao.php');
require_once('model/detalle_inventario.dao.php');


/*
 * listar las existencias para la sucursal dada sucursal
 * */
function listarInventario( $sucID = null){
    
	if(!$sucID){
		return null; 
	}

    $q = new DetalleInventario();
    $q->setIdSucursal( $sucID ); 
    
    $results = DetalleInventarioDAO::search( $q );
    
    $json = array();
    
    foreach( $results as $producto ){
        $productoData = InventarioDAO::getByPK( $producto->getIdProducto() );
        
        Array_push( $json , array(
            "productoID" => $productoData->getIdProducto(),
            "descripcion" => $productoData->getDescripcion(),
            "precioVenta" => $producto->getPrecioVenta(),
            "existenciasMinimas" => $producto->getMin(),
            "existencias" => $producto->getExistencias(),
            "medida" => $productoData->getMedida(),
            "precioIntersucursal" => $productoData->getPrecioIntersucursal()
        ));
    }

	return $json;
    


}

function detalleProductoSucursal( $args ){

    if( !isset( $args['id_producto'] ) )
    {
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
    }

    
    if( !($producto = DetalleInventarioDAO::getByPK( $args['id_producto'], $_SESSION['sucursal'] ) ) )
    {
        die('{"success": false, "reason": "No se tiene registros de ese producto." }');
    }

    printf('{ "success": true, "datos": %s }',  $producto);

}



function listarInventarioMaestro ()
{
	
	return InventarioDAO::getAll();
	
	
}


if(isset($args['action'])){
	switch($args['action']){
	    case 400:
	    	printf('{ "success": true, "datos": %s }',  json_encode( listarInventario( $_SESSION["sucursal"] ) ));
	    break;

	    case 401://regresa el detalle del producto en la sucursal actual
	        detalleProductoSucursal( $args );
	    break;

	    default:
	        printf( '{ "success" : "false" }' );
	    break;

	}
}

