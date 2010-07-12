<?php
/*este documentotiene todas las funciones de inventario
como insertar, eliminar, actualizar, consultas, listar 
y algunas otras funciones
*/
	//esta funcion inserta un producto al inventario y nos regresa un succes
	function insertarInventario()
	{
		//verificamos que no nos envien datos vacios
		if((!empty($_REQUEST['nombre']))&&(!empty($_REQUEST['denominacion'])))
		{
			//asignamos valores obtenidos a las variables
			$nombre=$_REQUEST['nombre'];
			$denominacion=$_REQUEST['denominacion'];
			//creamos un objeto del tipo inventario
			$inventario=new inventario($nombre,$denominacion);
			//varificamos que no exista
			if(!$inventario->existe())
			{
				//intentamos insertar
				if($inventario->inserta())		ok();															//se logro insertar
				else							fail("Error al guardar el producto.");							//se fallo el intento de insercion
			}//if no existe producto inventario
			else 								fail("Ya existe este producto.");								//el producto ya existia
		}//if verifica datos
		else									fail("Faltan datos.");											//no se enviaron los datos requeridos
		return;
	}
	//funcion insertarInventario
	
	//esta funcion recibe el id del inventario (producto que vendemos) y lo elimina
	function eliminarInventario()
	{
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
	}
	//funcioneliminarInventario
	
	//esta funcion actualiza los datos de un producto de inventario existente
	function actualizarInventario()
	{
		//verificamos que no nos envien datos vacios
		if((!empty($_REQUEST['id_inventario']))&&(!empty($_REQUEST['nombre']))&&(!empty($_REQUEST['denominacion'])))
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
				//asignamos los datos de las variables a las variables del objeto
				$inventario->nombre=$nombre;
				$inventario->denominacion=$denominacion;
				//intentamos actualizar los datos
				if($inventario->actualiza())	ok();																//exito al actualizar la informacion
				else							fail("Error al modificar el producto.");							//fallo en el intento de actualizacion de objero
			}//if existe producto inventario
			else								fail("El producto que desea modificar no existe.");					//el producto de inventario que se deseaba actualizar no existe
		}//if verifica datos
		else									fail("Faltan datos.");												//no se enviaron los datos necesarios para la funcion
		return;
	}
	//funcion actualizarInventario
	

	function listarProductosInventario(){
		$sucursal = $_SESSION['sucursal_id'];
		$listar = new listar("select inventario.id_producto, inventario.denominacion, detalle_inventario.precio_venta,detalle_inventario.existencias,detalle_inventario.id_sucursal,detalle_inventario.min from inventario inner join detalle_inventario on inventario.id_producto = detalle_inventario.id_producto where detalle_inventario.id_sucursal=?",array($sucursal));

		//imprimimos el json
		echo $listar->lista();
		return;
	}
	//funcion listarProductosInventario
	
	//esta funcion regresa un json con todos los datos de inventario y su detalle de una sucursal en especifico
	function listarProductosInventarioSucursal()
	{
		//verificamos que no nos envien datos vacios
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
	}

	
	function obtenerSucursalUsuario(){
		$user = $_SESSION['user'];	

		$listar = new listar("select usuario.sucursal_id from usuario where usuario.usuario = ?", array($user));
		echo $listar->lista();
		return;
	}
	//funcion listarProductosInventarioSucursal
	

	//Esta funcion nos regresa las existencias de un producto en una sucursal, recibiendo el id del producto y el de la sucursal
	function existenciaProductoSucursal()
	{
		//verificamos que no nos envien datos vacios
		if((!empty($_REQUEST['id_producto'])))
		{
			//asignamos valores obtenidos a las variables
			$id_producto=$_REQUEST['id_producto'];
			$id_sucursal=$_SESSION['sucursal_id'];
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
						//definimos la consulta para obtener los datos
						$query="SELECT id_producto, nombre, denominacion, precio_venta, existencias
								FROM detalle_inventario
								NATURAL JOIN inventario
								where id_producto=? and id_sucursal=?";
								//creamos un objeto de la clase listar con la consulta y parametros necesarios
								$listar = new listar($query,array($id_producto,$id_sucursal));
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
	}


	//Funcion para insertar un nuevo producto al inventario (tiene que insertar en la tabla inventario y en detalles_inventario)
	function agregarNuevoProducto()
	{

		if((!empty($_REQUEST['nombre']))&&(!empty($_REQUEST['denominacion']))&&(!empty($_REQUEST['precio']))&&(!empty($_REQUEST['min'])))
		{
			//asignamos valores obtenidos a las variables
			$nombre=$_REQUEST['nombre'];
			$denominacion=$_REQUEST['denominacion'];
			//creamos un objeto del tipo inventario
			$inventario=new inventario($nombre,$denominacion);
			//varificamos que no exista
			if(!$inventario->existe())
			{
				//intentamos insertar
				if(!$inventario->inserta())		
				{
					fail("Error al guardar el producto.");							//se fallo el intento de insercion
				}
			}//if no existe producto inventario
			else 								fail("Ya existe este producto.");								//el producto ya existia
		}//if verifica datos
		else									fail("Faltan datos.");	
										//no se enviaron los datos requeridos

		$detalles_inventario = new detalle_inventario( $inventario->id_producto, $_SESSION['sucursal_id'], $_REQUEST['precio'], $_REQUEST['min'], 0);
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
	}
	
?>
