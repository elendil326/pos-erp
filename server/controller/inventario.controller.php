<?php
/**	 inventario Controller.
*
* 	Este script contiene las funciones necesarias para realizar las operaciones
* 	a sobre el inventario asi como detalle inventario 
*	@author Diego Emanuelle Ventura <diego@caffeina.mx>
*
*	@see 
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
*	@see 
*	
*
*/
	//
	function insertarInventario($nombre,$denominacion)//1701
	{
	
		return "ok";
	/*
		//verificamos que no nos envien datos vacios
		if((!empty($_REQUEST['nombre']))&&(!empty($_REQUEST['denominacion']))&&(!empty($_REQUEST['unidad_venta'])))
		{
			//asignamos valores obtenidos a las variables
			$nombre=$_REQUEST['nombre'];
			$denominacion=$_REQUEST['denominacion'];
			$unidad_venta=$_REQUEST['unidad_venta'];
			//creamos un objeto del tipo inventario
			$inventario=new inventario($nombre,$denominacion,$unidad_venta);
			//creamos un objeto de la clase unidad_venta
			$unidad=new unidadVentaExistente($unidad_venta);
			//verificamos qu exista la unidad
			if($unidad->existe)
			{
				//varificamos que no exista
				if(!$inventario->existe())
				{
					//intentamos insertar
					if($inventario->inserta())		ok();															//se logro insertar
					else							fail("Error al guardar el producto.");							//se fallo el intento de insercion
				}//if no existe producto inventario
				else 								fail("Ya existe este producto.");								//el producto ya existia
			}//if existe unidad
			else									fail("la unidad de compra/venta seleccionada no existe");
		}//if verifica datos
		else									fail("Faltan datos.");											//no se enviaron los datos requeridos
		return;
		*/
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
*	@see 
*	
*/

	function eliminarInventario($idInventario)//1702
	{
		return "ok";
	/*
		//verificamos que no nos envien datos vacios
		if(!empty($_REQUEST['id_inventario']))
		{
			//asignamos valores obtenidos a las variables
			$id=$_REQUEST['id_inventario'];
			//creamos un objeto del tipo inventario existente pasandole el id
			$inventario=new inventarioExistente($id);
			//verificamos que si exista
			if($inventario->existe())
			{
				//intentamos eliminar
				if($inventario->borra())		ok();															//exito al eliminar
				else							fail("Error al borrar el producto.");							//fallo el intento de eliminar
			}//if inventario existe (producto)
			else 								fail("El producto que desea eliminar no existe.");				//no existe el producto
		}//if verifica datos
		else 									fail("faltan datos.");											//no se enviaron los datos necesarios
		return;
		*/
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
*	@see 
*	
*/ 

	function actualizarInventario($idInventario,$nombre,$denominacion)//1703
	{
		return "ok";
		/*
		//verificamos que no nos envien datos vacios
		if((!empty($_REQUEST['id_inventario']))&&(!empty($_REQUEST['nombre']))&&(!empty($_REQUEST['denominacion']))&&(!empty($_REQUEST['unidad_venta'])))
		{
			//asignamos valores obtenidos a las variables
			$id=$_REQUEST['id_inventario'];
			$nombre=$_REQUEST['nombre'];
			$denominacion=$_REQUEST['denominacion'];
			//creamos un objeto del tipo inventario_existente
			$inventario=new inventarioExistente($id);
			//verificamos que si exista dicho producto
			if($inventario->existe())
			{
				//creamos un objeto de la clase unidad_venta
				$unidad=new unidadVentaExistente($unidad_venta);
				//verificamos qu exista la unidad
				if($unidad->existe)
				{
					//asignamos los datos de las variables a las variables del objeto
					$inventario->nombre=$nombre;
					$inventario->denominacion=$denominacion;
					//intentamos actualizar los datos
					if($inventario->actualiza())	ok();																//exito al actualizar la informacion
					else							fail("Error al modificar el producto.");							//fallo en el intento de actualizacion de objero
				}//if existe unidad
				else								fail("la unidad de compra/venta seleccionada no existe");
			}//if existe producto inventario
			else								fail("El producto que desea modificar no existe.");					//el producto de inventario que se deseaba actualizar no existe
		}//if verifica datos
		else									fail("Faltan datos.");												//no se enviaron los datos necesarios para la funcion
		return;
		*/
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
*	@see 
*	
*/

	function listarProductosInventario()//1704
	{
		$sucursal = $_SESSION['sucursal'];
		$detalleInventarioSearch=new DetalleInventario();
		$detallesInventario=DetalleInventarioDAO::search($detalleInventarioSearch);
		
		foreach ($detallesInventario as $detalle)
		{
			//$inventario=InventarioDAO::getByPK($detalle->getIdProducto());
			return "ok";
		}
		
		$listar = new listar("select inventario.id_producto, inventario.denominacion, detalle_inventario.precio_venta,detalle_inventario.existencias,detalle_inventario.id_sucursal,detalle_inventario.min 
		from inventario inner join 
		detalle_inventario 
		on inventario.id_producto = detalle_inventario.id_producto 
		where detalle_inventario.id_sucursal=?",array($sucursal));

		//imprimimos el json
		echo $listar->lista();
		return $resul;
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
*	@see 
*	
*/                                                                

                
	function listarProductosInventarioSucursal($idSucursal)//1705
	{
		
		/*//verificamos que no nos envien datos vacios
		if(!empty($_REQUEST['id_sucursal']))
		{
			//asignamos valores obtenidos a las variables
			$id=$_REQUEST['id_sucursal'];
				//creamos un objeto del tipo listar
				$listar = new listar("select inventario.id_producto, inventario.denominacion, detalle_inventario.precio_venta,detalle_inventario.existencias,detalle_inventario.min from inventario inner join detalle_inventario on inventario.id_producto = detalle_inventario.id_producto where detalle_inventario.id_sucursal=?",array($id));
				//imprimimos el json
				echo $listar->lista();
				return;
		}//if verifica datos
		else											fail("faltan datos.");										//no se envio el id de la sucursal
		return;
		*/
	}
/*
salida ejemplo
{ success : true, datos : [{"id_producto":"9","denominacion":"malesuada fames ac turpis eges","precio_venta":"203","existencias":"196","min":"79"},{"id_producto":"38","denominacion":"Phasellus elit pede, malesuada","precio_venta":"343","existencias":"31","min":"85"}]}
*/



/**
*
* 	existenciaProductoSucursal
*
*	Esta funcion nos regresa las existencias de un producto en una sucursal, recibiendo el id del producto y la sucursal la toma de la session.
*
*       @access public
*       @return json con la existencia de         case '1706':   //'existenciaProductoSucursal':
        	echo "ok";
                break;
                
                   un producto o con los datos en caso de error
*	@params int [$idProducto] id del producto a buscar
*	@see 
*	
*/


	function existenciaProductoSucursal()//1706
	{
		return "ok";
		/*
		//verificamos que no nos envien datos vacios
		if((!empty($_REQUEST['id_producto'])))
		{
			//asignamos valores obtenidos a las variables
			$id_producto=$_REQUEST['id_producto'];
			$id_sucursal=$_SESSION['sucursal'];
			//creamos un objeto del tipo inventario existente

			$prod=new inventarioExistente($id_producto);
			//vemos que el producto si exista
			if($prod->existe())
			{
				//creamos un objeto del tipo sucursal con el id que recibimos
				$sucursal=new sucursalExistente($id_sucursal);
				//verificamos que la sucursal si exista
				if($sucursal->existe())
				{
					//creamos un objeto de la clase detalle_inventario_existente que es un objeto que tiene
					//la informacion de cada producto en cada sucursal
					$producto=new detalleInventarioExistente($id_producto,$id_sucursal);
					//verificamos que el producto exista
					if($producto->existe())
					{	
						//verificamos si se envio algun proveedor en especifico para tambien regresar el precio de adquicision
						$proveedor=(!empty($_REQUEST['id_proveedor']))?$_REQUEST['id_proveedor']:0;
						//definimos la consulta para obtener los datos
						$query="SELECT i.id_producto, nombre, denominacion, precio_venta, existencias ";
						if($proveedor>0)$query.=" ,precio ";
						$query.="FROM detalle_inventario
								NATURAL JOIN inventario i ";
						if($proveedor>0)$query.="left join productos_proveedor pp
												on (i.id_producto=pp.id_inventario) ";		
						$query.="where i.id_producto=? and id_sucursal=? ";
								if($proveedor>0)$query.=" and id_proveedor=? ";
								//creamos arreglo de parametros
								$params=array($id_producto,$id_sucursal,$proveedor);
								//creamos un objeto de la clase listar con la consulta y parametros necesarios
								$listar = new listar($query,$params);
								//imprimomos los datos obtenidos
								echo $listar->lista();
					}//if producto existe
					else										fail("Este producto no existe en esta sucursal.");	//el producto no existe en esta sucursal
				}//if sucursal existe
				else											fail("La sucursal no existe");						//la sucursal con este id no existe
			}//if producto existe
			else												fail("El producto no existe");						//el producto con ese id no existe
		}//if verifica datos
		else													fail("faltan datos.");								//no se enviaron los datos necesarios
		return;
		*/
	}





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
*	@see 
*	
*/
	function agregarNuevoProducto($nombre,$denominacion,$precio,$min)//1707
	{
		return "ok";
		/*
		if((!empty($_REQUEST['nombre']))&&(!empty($_REQUEST['denominacion']))&&(!empty($_REQUEST['precio']))&&(!empty($_REQUEST['unidad_venta']))&&(!empty($_REQUEST['min'])))
		{
			//asignamos valores obtenidos a las variables
			$nombre=$_REQUEST['nombre'];
			$denominacion=$_REQUEST['denominacion'];
			$unidad_venta=$_REQUEST['unidad_venta'];
			//creamos un objeto del tipo inventario
			$inventario=new inventario($nombre,$denominacion,$unidad_venta);
			//varificamos que no exista
			if(!$inventario->existe())
			{
				//creamos un objeto de la clase unidad_venta
				$unidad=new unidadVentaExistente($unidad_venta);
				//verificamos qu exista la unidad
				if($unidad->existe)
					{
					//intentamos insertar
					if(!$inventario->inserta())		
					{
						fail("Error al guardar el producto.");							//se fallo el intento de insercion
					}
				}//if existe unidad
				else								fail("la unidad de compra/venta seleccionada no existe");
			}//if no existe producto inventario
			else 								fail("Ya existe este producto.");								//el producto ya existia
		}//if verifica datos
		else									fail("Faltan datos.");	
										//no se enviaron los datos requeridos

		$detalles_inventario = new detalle_inventario( $inventario->id_producto, $_SESSION['sucursal'], $_REQUEST['precio'], $_REQUEST['min'], 0);
		if (!$detalles_inventario->existe())
		{
			if($detalles_inventario->inserta())
			{
				ok();
			}
			else
			{
				fail("Error al intentar insertar el nuevo producto al inventario");
			}
		}
		else
		{
			fail("Ya existe el producto");			
		}
		
		return;
		*/
	}
	
	
/**
*
* 	listarSucursal
*
* 	esta funcion devuelve los datos de todas las sucursales
*
*       @access public
*       @return JSON con los datos de todas las sucursales.
*	@see 
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

//inventario dispatcher
switch($args['action'])
{

         case '1701':   //'insertarInventario':
        	echo "ok";
                break;
                
         case '1702':   //'eliminarInventario':
        	echo "ok";
                break;
                                
         case '1703':   //'actualizarInventario':
        	echo "ok";
                break;
                                                
         case '1704':   //'listarProductosInventario':
        	echo listarProductosInventario();
                break;
                                                                
         case '1705':   //'listarProductosInventarioSucursal':
        	echo listarProductosInventarioSucursal();
                break;
                                                                                
         case '1706':   //'existenciaProductoSucursal':
        	echo "ok";
                break;
                                                                                               
         case '1707':   //'agregarNuevoProducto':
        	echo "ok";
                break;
                                                                                                                              
         case '1708':   //'listarSucursal':
        	echo listarSucursal();
                break;
                
         default:  
                
         default:
         	echo '{ "success" : "false" }';
                break;
                
}

?>

