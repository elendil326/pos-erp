<?php
/**	 inventario Controller.
*
* 	Este script contiene las funciones necesarias para realizar las operaciones
* 	a sobre el inventario asi como detalle inventario 
*	@author Diego Emanuelle Ventura <diego@caffeina.mx>
*
*	@see InventarioDAO, DetalleInventarioDAO, SucursalDAO
*/

/**
* Se importan los DAO para poder realizar las operaciones sobre la BD.
*/


//no se porque no funcionan
require_once('../server/model/inventario.dao.php');
require_once('../server/model/detalle_inventario.dao.php');
require_once('../server/model/sucursal.dao.php');

/**
*	insertarInventario
* 	
*	esta funcion inserta un producto al inventario.
*
*
*       @access public
*       @return json que nos indica si la operacion fue satisfactoria o fallo
*	@params String [$nombre] nombre que asignaremos al nuevo producto
*	@params String [$denominacion] descripcion del producto a agregar
*	@see InventarioDAO::save()
*	
*
*/
	//
	function insertarInventario($nombre,$denominacion)//1701
	{
		$inventario=new Inventario();
		$inventario->setNombre($nombre);
		$inventario->setDenominacion($denominacion);
		try
		{
			InventarioDAO::save($inventario);
			return '{ "success" : true }';		
		}
		catch(Exception$e)
		{
			return '{ "success" : false , "reason" : "Error al guardar el producto."}';
		}		
	}
	//funcion insertarInventario


/**
*	eliminarInventario
* 	
*	esta funcion recibe el id del inventario (producto que vendemos) y lo elimina
*
*
*       @access public
*       @return JSON q
*	@paramsue nos indica si la operacion fu exitos o fallo y si fallo nos indica la razon de esto.
*	@params int [$idInventario] id del producto de inventario que queremos eliminar. 
*	@see  InventarioDAO::getByPK(), DetalleInventarioDAO::delete()
*	
*/

	function eliminarInventario($idInventario)//1702
	{
		$inventario=InventarioDAO::getByPK($idInventario);
		try
		{
			InventarioDAO::delete($inventario);
			return '{ "success" : true }';		
		}
		catch(Exception$e)
		{
			return '{ "success" : false , "reason" : "Error al eliminar el producto."}';
		}
	}
	//funcioneliminarInventario

 
/**
*
* 	actualizarInventario
*
*	Esta funcion actualiza los datos de un producto de inventario existente
*
*       @access public
*       @return JSON que nos indica si la actualizacion fue exitosa o fallo
*	@params int [$idInventario] id del producto de inventario a actualizar
*	@params String [$nombre] nombre que le queremos asignar al producto de inventario
*	@params String [$denominacion] descripcion que deseamos asignar al producto de inventario
*	@see InventarioDAO::getByPK(), InventarioDAO::save() 
*	
*/ 

	function actualizarInventario($idInventario,$nombre,$denominacion)//1703
	{
		$inventario=InventarioDAO::getByPK($idInventario);
		$inventario->setNombre($nombre);
		$inventario->setDenominacion($denominacion);
		try
		{
			InventarioDAO::save($inventario);
			return '{ "success" : true }';		
		}
		catch(Exception$e)
		{
			return '{ "success" : false , "reason" : "Error al guardar los cambios del producto."}';
		}		
	}
	//funcion actualizarInventario
	



/**
*
* 	listarProductosInventario
*	
*	nos muestra la informacion de los productos que vendemos y sus caracteristicas en cada sucursal
*
*       @access public
*       @return JSON con los datos de los productos de inventario y sus detalles en las sucursales
*	@see DetalleInventarioDAO::search(), InventarioDAO::getByPK()
*	
*/

	function listarProductosInventario()//1704
	{
	
		$sucursal = $_SESSION['sucursal'];
		$detalleInventarioSearch=new DetalleInventario();
		$detalleInventarioSearch->setIdSucursal($sucursal);
		$detallesInventario=DetalleInventarioDAO::search($detalleInventarioSearch);
		
		$resul='{ "success" : true , "datos" : [';
		foreach ($detallesInventario as $detalle)
		{
			$inventario=InventarioDAO::getByPK($detalle->getIdProducto());
			$resul.='{ "id_producto" : "'.$inventario->getIdProducto().'" , "denominacion" : "'.$inventario->getDenominacion().'" , "nombre" : "'.$inventario->getNombre().'" , "precio_venta" : "'.$detalle->getPrecioVenta().'" , "existencias" : "'.$detalle->getExistencias().'" , "id_sucursal" : "'.$sucursal.'" , "min" : "'.$detalle->getMin().'" } , ';
		}
		$resul.= "]}";
		return str_replace(", ]","]",$resul);
		
	}
	//funcion listarProductosInventario
/*
salida ejemplo:
{ success : true, datos : [{"id_producto":"2","denominacion":"arcu. Curabitur ut odio vel","precio_venta":"272","existencias":"92","id_sucursal":"28","min":"61"}]}
*/	


/**
*
* 	listarProductosInventarioSucursal
*
*	Esta funcion regresa un json con todos los datos de inventario y su detalle de una sucursal en especifico
*
*       @access public
*       @return json con los datos de los productos de una sucursal
*	@params int [$idSucursal] id de la sucursal de la que queremos obtener sus productos
*	@see DetalleInventarioDAO::search(),InventarioDAO::getByPK()
*	
*/                                                                

                
	function listarProductosInventarioSucursal($idSucursal)//1705
	{
		$detalleInventarioSearch=new DetalleInventario();
		$detalleInventarioSearch->setIdSucursal($idSucursal);
		$detallesInventario=DetalleInventarioDAO::search($detalleInventarioSearch);
		
		$resul='{ "success" : true , "datos" : [';
		foreach ($detallesInventario as $detalle)
		{
			$inventario=InventarioDAO::getByPK($detalle->getIdProducto());
			$resul.='{ "id_producto" : "'.$inventario->getIdProducto().'" , "denominacion" : "'.$inventario->getDenominacion().'" , "precio_venta" : "'.$detalle->getPrecioVenta().'" , "existencias" : "'.$detalle->getExistencias().'" , "min" : "'.$detalle->getMin().'" } , ';
		}
		$resul.= "]}";
		return str_replace(", ]","]",$resul);
	}
/*
salida ejemplo
{ success : true, datos : [{"id_producto":"9","denominacion":"malesuada fames ac turpis eges","precio_venta":"203","existencias":"196","min":"79"},{"id_producto":"38","denominacion":"Phasellus elit pede, malesuada","precio_venta":"343","existencias":"31","min":"85"}]}
*/




/**
*
* 	agregarNuevoProducto
*
* 	Funcion para insertar un nuevo producto al inventario (tiene que insertar en la tabla inventario y en detalles_inventario)
*
*       @access public
*       @return JSON con el resultado de la operacion.
*	@params String [$nombre] nombre del nuevo producto
*	@params String [$denomincion] descripcion del nuevo producto
*	@params float [$precio] precio al que venderemos el nuevo producto en la sucursal actual
*	@params	$min [$min] existencia minima del producto dentro de la sucursal actual
*	@see InventarioDAO::save(), DetalleInventarioDAO::save()
*	
*/
	function agregarNuevoProducto($nombre,$denominacion,$precio,$min)//1707
	{
		$inventario=new Inventario();
		$inventario->setNombre($nombre);
		$inventario->setDenominacion($denominacion);
		try
		{
			$sucursal = $_SESSION['sucursal'];
			InventarioDAO::save($inventario);
			$detalleInventario=new DetalleInventario();
			$detalleInventario->setExistencias(0);
			$detalleInventario->setIdProducto($inventario->getIdProducto());
			$detalleInventario->setMin($min);
			$detalleInventario->setPrecioVenta($precio);
			$detalleInventario->setIdSucursal($sucursal);
			DetalleInventarioDAO::save($detalleInventario);
			return '{ "success" : true }';			
			
		}
		catch(Exception$e)
		{
			return '{ "success" : false , "reason" : "Error al guardar el producto."}';
		}
	}
	
	
/**
*
* 	listarSucursal
*
* 	esta funcion devuelve los datos de todas las sucursales
*
*       @access public
*       @return JSON con los datos de todas las sucursales.
*	@see SucursalDAO::getAll()
*	
*/
	function listarSucursal()//1708
	{
		$sucursales=SucursalDAO::getAll();
		$resul='{ "success" : true , "datos" : [';
		foreach ($sucursales as $sucursal)
		{
			$resul.='{ "id_sucursal" : "'.$sucursal->getIdSucursal().'", "descripcion" : "'.$sucursal->getDescripcion().'" , "direccion" : "'.$sucursal->getDireccion().'" } ,';
		}
		$resul.=']}';
		return str_replace(",]", "]",$resul);
	}
	
/**
*
* 	detallesSucursal
*
* 	esta funcion devuelve los datos de todas las sucursales
*
*       @access public
*       @return JSON con los datos de todas las sucursales.
*	@params int [$idSucursal] nos envia el id de la sucursal a consultar
*	@see SucursalDAO::getByPK()
*	
*/
	function detallesSucursal($idSucursal)//1709
	{
		$sucursal=SucursalDAO::getByPK($idSucursal);
		if(is_null($sucursal)) return '{ "success" : false , "reason" : "la sucursal no existe."}';
		$resul='{ "success" : true , "datos" : [ { "id_sucursal" : "'.$sucursal->getIdSucursal().'", "descripcion" : "'.$sucursal->getDescripcion().'" , "direccion" : "'.$sucursal->getDireccion().'" }]}';
		return $resul;
	}

//inventario dispatcher
switch($args['action'])
{

         case '1701':   //'insertarInventario':
        	if((empty($args['nombre']))||(empty($args['denominacion'])))
		{
			echo '{ "success" : false , "reason" : "Faltan datos."}';
         		return;
		}
		$nombre=$args['nombre'];
		$denominacion=$args['denominacion'];
        	echo insertarInventario($nombre,$denominacion);
                break;
                
         case '1702':   //'eliminarInventario':
        	if(empty($args['id_inventario']))
		{
			echo '{ "success" : false , "reason" : "Faltan datos."}';
         		return;
		}
		$id=$args['id_inventario'];
        	echo eliminarInventario($id);
                break;
                                
         case '1703':   //'actualizarInventario':
        	if((empty($args['id_inventario']))||(empty($args['nombre']))||(empty($args['denominacion'])))
		{
			echo '{ "success" : false , "reason" : "Faltan datos."}';
         		return;
		}
		$id=$args['id_inventario'];
		$nombre=$args['nombre'];
		$denominacion=$args['denominacion'];
        	echo actualizarInventario($id,$nombre,$denominacion);
                break;
                                                
         case '1704':   //'listarProductosInventario':
        	echo listarProductosInventario();
                break;
                                                                
         case '1705':   //'listarProductosInventarioSucursal':
      		if (empty($args['id_sucursal']))
         	{
         		echo '{ "success" : false , "reason" : "Faltan datos."}';
         		return;
         	}
         	$id=$args['id_sucursal'];
        	echo listarProductosInventarioSucursal($id);
                break;
                          
          //aqui estaba existenciasproductosucursal pero se elimino 1706
                                                                                               
         case '1707':   //'agregarNuevoProducto':
         	if((empty($args['nombre']))||(empty($args['denominacion']))||(empty($args['precio']))||(empty($args['min'])))
		{
			echo '{ "success" : false , "reason" : "Faltan datos."}';
         		return;
		}
		$nombre=$args['nombre'];
		$denominacion=$args['denominacion'];
		$precio=$args['precio'];
		$minimo=$args['min'];
        	echo agregarNuevoProducto($nombre,$denominacion,$precio,$minimo);
                break;
                                                                                                                              
         case '1708':   //'listarSucursal':
        	echo listarSucursal();
                break;
                                                                                                                                              
         case '1709':   //'detallesSucursal':
         	if (empty($args['id_sucursal']))
         	{
         		echo '{ "success" : false , "reason" : "Faltan datos."}';
         		return;
         	}
         	$id=$args['id_sucursal'];
        	echo detallesSucursal($id);
                break;
                
         default:  
                
         default:
         	echo '{ "success" : "false" }';
                break;
                
}

?>

