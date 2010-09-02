<?php
/** Productos-Proveedor Controller.
  * 
  * Este script contiene las funciones necesarias para realizar las diferentes operaciones 
  * que se pueden hacer con respecto a los productos que un proveedor surte a cada sucursal
  * 
  * @author Juan Manuel Hernadez <manuel@caffeina.mx> 
  * 
  */

/**
 * Se importan los DAO para poder realizar las operaciones sobre la BD.
 */ 

require_once('../server/model/detalle_inventario.dao.php');
require_once('../server/model/productos_proveedor.dao.php');
require_once('../server/model/inventario.dao.php');
require_once('../server/model/sucursal.dao.php');
require_once('../server/model/usuario.dao.php');


/**
 * insert_provider_product
 *
 * Funcion que se utiliza desde el modulo compras a un proveedor para cuando 
 * se agrega un producto existente de la BD al inventario de la sucursal. 
 * Inserta un nuevo producto al inventario de la sucursal desde la tabla productos_proveedor
 *
 * @param <type> $jsonItems
 */
function insert_provider_product( $jsonItems ) { 

	$sucursal=$_SESSION['sucursal'];
	
	$arregloItems = json_decode($jsonItems,true);
	$dim = count($arregloItems);
	$agregados =0;
	$out = "";
	for ( $i = 0; $i < $dim; $i++ ){	
	
		$detalle = new DetalleInventario();
			
		$detalle->setIdProducto( $arregloItems[$i]['id'] );
		$detalle->setIdSucursal( $sucursal );
		$detalle->setPrecioVenta( $arregloItems[$i]['precioVenta'] );
		$detalle->setMin( $arregloItems[$i]['stockMin'] );
		$detalle->setExistencias ( 0 );	
		//$ans = DetalleInventarioDAO::save( $detalle );
			
		//if( $ans > 0 ){
			$agregados++;
			$out .= ", ID: ".$detalle->getIdProducto()." precio venta: ".$detalle->getPrecioVenta()." Min: ".$detalle->getMin()." existencias: ".$detalle->getExistencias();
		//}
	}//fin for
	
	if( $agregados < 1 ){
		return "{success: false, reason: 'No se agrego ningun producto al inventario, intente nuevamente'}";
	}else{
		return "{success: true, reason: 'Se agregaron ".$agregados." productos al inventario', details: '".$out."'}";
	}
}

/**
 * list_provider_available_products
 * 
 * Dado el id del proveedor regresa los productos que el proveedor ofrece pero 
 * que no se venden en esta sucursal
 *
 * @param <type> $id_provider
 */
function list_provider_available_products( $id_provider ){
	
	$sucursal = $_SESSION['sucursal'];
	
	$productosSucursal = new DetalleInventario();
	$productosSucursal->setIdSucursal( $sucursal );
	
	$productos = DetalleInventarioDAO::search( $productosSucursal );
	
	$prodProv = new ProductosProveedor();
	$prodProv->setIdProveedor( $id_provider );
	
	$productosProveedor = ProductosProveedorDAO::search( $prodProv );
	
	$numProd = count( $productos );
	
	$numProdProv = count( $productosProveedor );
	
	$datos ="";
	$numProductosProv = 0;
	
	//for anidados para detectar que productos no hay en el almacen (son marcados con id = 0 )
	for( $i = 0; $i < $numProdProv; $i++ ){
		
		for( $j = 0; $j < $numProd; $j++ ){
			
			if( $productos[ $j ]->getIdProducto() == $productosProveedor[ $i ]->getIdInventario()  ){
				//este producto ya lo surte el proveedor
				
				$productosProveedor[ $i ]->setIdInventario( 0 );
				
			}//fin if
			
		}//fin for j		
	}//fin for i
	
	//for para generar json con los productos que tiene el proveedor pero q no surte a la sucursal
	
	for( $k = 0; $k < $numProdProv; $k++){
		
		if( $productosProveedor[ $k ]->getIdInventario() > 0 ){
			
			$numProductosProv++;
			
			$inventario = InventarioDAO::getByPK( $productosProveedor[ $k ]->getIdInventario() );
			
			$datos .= '{ "id_producto":"'.$productosProveedor[ $k ]->getIdInventario().'","nombre":"'.$inventario->getNombre().'","denominacion":"'.$inventario->getDenominacion().'","precio":"'.$productosProveedor[ $k ]->getPrecio().'" },';

		}//fin if
	}//fin for k
	
	if( $numProductosProv < 1){
		return "{success:false , reason: 'Este proveedor no tiene productos que ofrecer a esta sucursal'}";
	}
	
	$out = substr($datos,0,-1);
	
	return '{ success:true , datos:['.$out.'] ,details:" numero de productos en esta suc '.$numProd.' y num de prod q ofrece este prov y no estan en el inventario de esta sucursal '.$numProductosProv.'"}';
}//fin metodo list_provider_productos


?>