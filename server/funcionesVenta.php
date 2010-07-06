<?php
/*este documentotiene todas las funciones de ventas
como insertar, eliminar, actualizar, consultas, listar 
y algunas otras funciones
*/
	//esta funcion inserta una nueva factura para una venta
	function insertarFacturaVenta()
	{
		//verificamos que no nos envien datos vacios  		
		if((!empty($_REQUEST['folio']))&&(!empty($_REQUEST['id_venta'])))
		{
			//asignamos valores obtenidos a las variables
			$folio=$_REQUEST['folio'];
			$id_venta=$_REQUEST['id_venta'];
			//crea un objeto de la clase factura_venta con el id y el folio
			$factura=new factura_venta($folio,$id_venta);
			//crea un objeto de la clase venta con el id
			$verifica_venta=new venta_existente($id_venta);
			//checa que exista dicha venta
			if($verifica_venta->existe())
			{						 	
				//checa que no haya otra factura para la venta
				if(!$factura->existe_venta())
				{						
					//checa que ninguna factura tenga ese folio
					if(!$factura->existe_folio())
					{					
						//verifica que no exista la factura
						if(!$factura->existe())
						{
							//intentamos insertar la factura
							if($factura->inserta())			ok();														//logramos inserta
							else							fail("Error al guardar la factura.");						//fallo el intento de insersion
						}//if no exista la fcatura
						else 								fail("Ya existe esta factura.");							//la factura ya existe
					}//if existe folio
					else 									fail("Ya existe una factura con este numero de folio.");	//el numero de folio ya existe
				}//if no existe factura
				else 										fail("Ya existe una factura para esta venta.");				//la venta ya tiene factura
			}//if verifica venta
			else 											fail("La venta que desea facturar no existe.");				//la venta no existe
		}//if verifica datos
		else												fail("Faltan datos.");										//no se pasaron todos los datos necesarios
		return;
	}
	//funcion insertar factura Venta
	
	//esta funcion elimina una factura de venta
	function eliminarFacturaVenta()
	{
		//verificamos que no nos envien datos vacios  	
		if(!empty($_REQUEST['id_factura']))
		{
			//asignamos valores obtenidos a las variables
			$id=$_REQUEST['id_factura'];
			//creamos objeto de la clase factura venta
			$factura=new factura_venta_existente($id);
			//verifica que si exista la factura
			if($factura->existe())
			{												
				//intentamos elimina la factura
				if($factura->borra())						ok();														//se borro la factura
				else										fail("Error al borrar la factura.");						//se fallo el borrado de factura
			}//if existe factura
			else 											fail("La factura que desea eliminar no existe.");			//no existe la factura
		}//if verifica datos
		else 												fail("faltan datos.");										//datos incompletos
		return;
	}
	//funcion aliminarFacturaVenta
	
	//esta funcion actualiza una factura venta
	function actualizarFacturaVenta()
	{
		//verificamos que no nos envien datos vacios  
		if((!empty($_REQUEST['id_factura']))&&(!empty($_REQUEST['folio']))&&(!empty($_REQUEST['id_venta'])))
		{
			//asignamos valores obtenidos a las variables
			$id=$_REQUEST['id_factura'];
			$folio=$_REQUEST['folio'];
			$id_venta=$_REQUEST['id_venta'];
			//creamos objeto venta existente para modificar
			$factura=new factura_venta_existente($id);								
			//variable para verificar si es el mismo id de venta
			$venta=$factura->id_venta;												
			//variable para verificar si es el mismo folio
			$folio1=$factura->folio;												
			//verificamos que si exista la factura
			if($factura->existe())
			{											
				//le asignamos los valores al objeto
				$factura->folio=$folio;												
				$factura->id_venta=$id_venta;
				//creamos un objeto venta existente
				$verifica_venta=new venta_existente($id_venta);						
				//checamos que exista la venta para poder facturar
				if($verifica_venta->existe())
				{	
					//checamos o que sea la misma venta o que no haya facturas para la venta nueva
					if(($venta==$id_venta)||(!$factura->existe_venta()))
					{
						//checamos o que sea el mismo folio o que no haya facturas con el
						if(($folio1==$folio)||(!$factura->existe_folio()))
						{	
							//intentamos actualizar la factura
							if($factura->actualiza())		ok();														//actualizacion correcta
							else							fail("Error al modificar la factura.");						//error al actualizar
						}//if es el mismo filio o no existe el folio
						else								fail("Ya existe una factura con este numero de folio.");	//otra factura tiene el numero de folio
					}//if es la misma venta o no hay factura para venta
					else									fail("Ya existe una factura para esta venta.");				//la venta ya tiene factura
				}//if existe venta
				else										fail("La venta que desea facturar no existe.");				//no existe la venta
			}//if existe factura
			else											fail("La factura que desea modificar no existe.");			//no existe la factura
		}//if verifica datos
		else												fail("Faltan datos.");										//datos incompletos
		return;
	}
	//funcion actualizar factura venta
	
	//esta funcion inserta una nota de una venta
	function insertarNota()
	{
		//verificamos que no nos envien datos vacios  	
		if(!empty($_REQUEST['id_venta']))
		{
			//asignamos valores obtenidos a las variables
			$id_venta=$_REQUEST['id_venta'];
			$nota=new nota_remision($id_venta);
			//crea un objeto de la clase venta con el id
			$verifica_venta=new venta_existente($id_venta);			
			//checa que exista dicha venta
			if($verifica_venta->existe())
			{
				//checa que no haya otra nota para la venta
				if(!$nota->existe_venta())
				{
					//verifica que no exista la nota
					if(!$nota->existe())
					{
						//intenta inserta
						if($nota->inserta())				ok();														//insercion correcta
						else								fail("Error al guardar la nota.");							//fallo la insercion
					}//if nota no existe
					else 									fail("Ya existe esta nota.");								//la nota con ese id ya existe
				}//if no existe nota de la venta
				else 										fail("Ya existe una nota para esta venta.");				//nota de venta existente
			}//if existe venta
			else 											fail("La venta para la nota no existe.");					//no existe la venta
		}//if verifica datoa
		else												fail("Faltan datos.");										//datos incompletos
		return;
	}
	//funcion insertar nota
	
	function eliminarNota()
	{
		//verificamos que no nos envien datos vacios  	
		if(!empty($_REQUEST['id_nota']))
		{
			//asignamos valores obtenidos a las variables
			$id=$_REQUEST['id_nota'];
			//creamos objeto del tipo nota existente
			$nota=new nota_remision_existente($id);
			//verificamos que exista la nota
			if($nota->existe())
			{									
				//intentamos borrar la nota
				if($nota->borra())							ok();														//elimina la nota
				else										fail("Error al borrar la nota.");							//fallo el borrado
			}//if existe nota
			else 											fail("La nota que desea eliminar no existe.");				//nota inexistente
		}//if verifica datos
		else fail("faltan datos.");
		return;
	}
	//funcion eliminar nota
	
	
	//esta funcion actualiza los datos de una nota
	function actualizarNota()
	{
		//verificamos que no nos envien datos vacios  
		if((!empty($_REQUEST['id_nota']))&&(!empty($_REQUEST['id_venta'])))
		{
			//asignamos valores obtenidos a las variables
			$id=$_REQUEST['id_nota'];
			$id_venta=$_REQUEST['id_venta'];
			//creamos objeto venta existente para modificar
			$nota=new nota_remision_existente($id);									
			//variable para verificar si es el mismo id de venta
			$venta=$nota->id_venta;													
			//verificamos que si exista la nota
			if($nota->existe())
			{
				$nota->id_venta=$id_venta;
				//creamos un objeto venta existente
				$verifica_venta=new venta_existente($id_venta);						
				//checamos que exista la venta para poder notar
				if($verifica_venta->existe())
				{
					//checamos o que sea la misma venta o que no haya notas para la venta nueva
					if(($venta==$id_venta)||(!$nota->existe_venta()))
					{
						//intentamos actualizamos los datos
						if($nota->actualiza())				ok();														//se actualizo correctamente
						else								fail("Error al modificar la nota.");						//fallo la actualizacion
					}//if misma venta o no hay nota para la venta
					else									fail("Ya existe una nota para esta venta.");				//venta ya con nota
				}//if existe venta
				else										fail("La venta para la nota no existe.");					//no existe venta
			}//if existe nota
			else											fail("La nota que desea modificar no existe.");				//no existe nota
		}//if verifica datos
		else												fail("Faltan datos.");										//datos incompletos
		return;
	}
	//funcion actualizanota
	
	//esta funcion vende un producto, checa las existencias antes de vender
	function vendeProducto()
	{
		//verificamos que no nos envien datos vacios  
		if((!empty($_REQUEST['id_producto']))&&(!empty($_REQUEST['existencias']))&&(!empty($_REQUEST['id_sucursal'])))
		{
			//asignamos valores obtenidos a las variables
			$id=$_REQUEST['id_producto'];
			$existencias=$_REQUEST['existencias'];
			$id_sucursal=$_REQUEST['id_sucursal'];
			//creamos un objeto tipo detalle_inventario_existente para ver que vendamos el producto en la sucursal
			$detalle_inventario=new detalle_inventario_existente($id,$id_sucursal);
			//creamos un objeto inventario existente para veridicar que vendemos el producto
			$producto=new inventario_existente($id);
			//verificamos que vendemos este prodcuto
			if($producto->existe())
			{
				//creamos un objeto sucursal
				$verifica_sucursal=new sucursal_existente($id_sucursal);
				//verificamos que exista la sucursal
				if($verifica_sucursal->existe())
				{
					//verificamos que vendemos el producto en la sucursal
					if($detalle_inventario->existe())
					{
						//verificamos que hay existencia
						if(($detalle_inventario->existencias)>0)
						{
							//verificamos que tenemos mas o igual productos que los que queremos vender
							if(($detalle_inventario->existencias)>=$existencias)
							{
								//restamos existencias al inventario
								$detalle_inventario->existencias-=$existencias;
								//intentamos actualizar los datos (vender)
								if($detalle_inventario->actualiza())				ok();													//venta exitosa
								else												fail("Error al agregar los datos");						//error al actualizar
							}//if existencias mas o igual que las que se venden
							else													fail("No puede vender mas producto de el que existe");	//se quiere vender mas de lo que existe
						}//if hay existencias
						else														fail("No hay mas producto, existencias en 0");			//no hay existencia
					}//if producto existe en sucursal
					else															fail("No existe el producto que desea vender");			//producto inexistente
				}//if sucursal existe
				else																fail("La sucursal de la vender no existe.");			//sucursal inexistente
			}//if existe producto
			else 																	fail("El producto que desea vender no existe.");		//producto inexistente
		}//if verifica datos
		else 																		fail("Faltan datos.");									//datos incompletos
		return;
	}
	//funcion vender producto
	
	//esta funcion lista todas las facturas
	function listarFacturasVenta()
	{
		//creamos un objeto de la clase listar con la consulta
		$listar = new listar("select * from factura_venta",array());
		//imprimimos el json
		echo $listar->lista();
		return;
	}
	//funcion listar facturas ventas
	
	//esta funcion lista todas las notas
	function listarNotas()
	{
		//creamos un objeto de la clase listar con la consulta
		$listar = new listar("select * from nota_remision",array());
		//imprimimos el json
		echo $listar->lista();
		return;
	}
	//funcion listar notas
	
	//esta funcion regresa un reporte de ventas por usuario,
	//si se le envian fechas agrega un periodo
	//si se le agrega un id de sucursal tambien lo busca
	function reporteVentasEmpleado(){
		//asignamos los datos recibidos a las variables (en caso de que se reciban)
		$id_sucursal=$_REQUEST['id_sucursal'];
		$de=$_REQUEST['de'];
		$al=$_REQUEST['al'];
		//asignamos variables que seran booleanos para saber si nos enviaron parametros de sucursal y/o periodo
		$sucursal=!empty($id_sucursal);
		$fecha=(!empty($de)&&!empty($al));
		//inicializamos arreglo de periodo vacio
		$params=array();
		//inicializamos la consulta, esta sera final si no se enviaron parametros
		$query="SELECT v.nombre,sum(v.total) as total
				FROM  ventasusuario v
				group by v.nombre,sucursal,v.fecha ";
		//verificamos el booleano de sucursal
		if($sucursal){
			//agregamos having para que solo cuente los de la sucursal deseada
			$query.=" having sucursal=? ";
			//agregamos parametro al arreglo
			array_push($params,$id_sucursal);
		}//if sucursal
		//verificamos si se enviaron fechas
		if($fecha)
		{
				//agregamos la condicion que cuente los que esten en las fechas
				//si se agrego sucursal lo pone con and, de lo contrario pone el having
				$query.=(($sucursal)?" and ":" having ")."v.fecha BETWEEN ? AND ? ";
				//agrega los parametros a la pila
				array_push($params,$de,$al);
		}//if fechas
		//agregamos el ; final
		$query.=";";
		echo "$query<br><br>";
		//creamos objeto de la clase listar y le pasamos el arreglo de parametros
		$listar = new listar($query,$params);
		//imprimimos el resultado
		echo $listar->lista();
		return;
	}
	//reporte ventas empleado
	
	
	//esta funcion regresa un reporte de ventas por sucursal,
	//si se le envian fechas agrega un periodo
	function reporteVentasSucursales(){
		//asignamos los datos recibidos a las variables (en caso de que se reciban)
		$de=$_REQUEST['de'];
		$al=$_REQUEST['al'];
		//asignamos variables que seran booleanos para saber si nos enviaron parametros de periodo
		$fecha=(!empty($de)&&!empty($al));
		//inicializamos arreglo de parametros vacio
		$params=array();
		//inicializamos la consulta, esta sera final si no se enviaron parametros
		$query="SELECT v.descripcion, sum( v.total ) AS total
				FROM ventassucursal v
				GROUP BY v.descripcion";
		//verificamos si se enviaron fechas
		if($fecha)
		{
				//agregamos la condicion que cuente las que esten en las fechas
				$query.=", v.fecha having DATE(v.fecha) BETWEEN ? AND ? ";
				//agrega los parametros a la pila
				array_push($params,$de,$al);
		}//if fechas
		//agregamos el ; final
		$query.=";";
		//creamos objeto de la clase listar y le pasamos el arreglo de parametros
		$listar = new listar($query,$params);
		//imprimimos el resultado
		echo $listar->lista();
		return;
	}
	//reporte ventas sucursal
	
?>
