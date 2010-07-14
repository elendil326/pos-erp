<?php	
/*este documentotiene todas las funciones de sucursal
como insertar, eliminar, actualizar, consultas, listar 
y algunas otras funciones
*/	
	
	//esta funcion inserta una sucursal
	function insertarSucursal()
	{
		//verificamos que no nos envien datos vacios  
		if((!empty($_REQUEST['descripcion']))&&(!empty($_REQUEST['direccion'])))
		{
			//asignamos valores obtenidos a las variables
			$descripcion=$_REQUEST['descripcion'];
			$direccion=$_REQUEST['direccion'];
			//creamos objeto-sucursal
			$sucursal=new sucursal($descripcion,$direccion);
			//verficamos que no exista
			if(!$sucursal->existe())
			{
				//intentamos insertar la sucursal
				if($sucursal->inserta())								ok();												//insercion existosa
				else													fail("Error al guardar la sucursal.");				//fallo la insercion
			}//if sucursal no existe
			else 														fail("Ya existe esta sucursal.");					//sucursal existente
		}//if verificar datos
		else															fail("Faltan datos.");								//datos incompletos
		return;
	}
	//funcion insertar sucursal
	
	//esta funcion elimina una sucursal
	function eliminarSucursal()
	{
		//verificamos que no nos envien datos vacios  	
		if(!empty($_REQUEST['id_sucursal']))
		{
			//asignamos valores obtenidos a las variables
			$id=$_REQUEST['id_sucursal'];
			//creamos objeto de la clase sucursal
			$sucursal=new sucursalExistente($id);
			//verificamos que exista la sucursal
			if($sucursal->existe())
			{
				//intentamos borrar
				if($sucursal->borra())									ok();												//borrado correcto
				else													fail("Error al borrar sucursal.");					//fallo el borrado
			}//if sucursal existe
			else 														fail("La sucursal que desea eliminar no existe.");	//sucursal inexistente
		}//if verificar datos
		else fail("faltan datos.");
		return;
	}
	//funcion eliminar sucursal
	
	//esta funcion actualiza los datos de una sucursal
	function actualizarSucursal(){
		//verificamos que no nos envien datos vacios  
		if((!empty($_REQUEST['id_sucursal']))&&(!empty($_REQUEST['descripcion']))&&(!empty($_REQUEST['direccion'])))
		{
			//asignamos valores obtenidos a las variables
			$id=$_REQUEST['id_sucursal'];
			$descripcion=$_REQUEST['descripcion'];
			$direccion=$_REQUEST['direccion'];
			//creamos objeto- sucursal existente
			$sucursal=new sucursalExistente($id);
			//verficamos que exista la sucursal
			if($sucursal->existe())
			{
				//asignamos los datos obtenidos a nuestro objeto
				$sucursal->descripcion=$descripcion;
				$sucursal->direccion=$direccion;
				//intentamos actualizar
				if($sucursal->actualiza())									ok();												//actualizacion correcta
				else														fail("Error al modificar la sucursal.");			//fallo actualizacion
			}//if existe sucursal
			else															fail("La sucursal que desea modificar no existe.");	//sucursal inexistente
		}//if verifica datos
		else																fail("Faltan datos.");								//datos incompletos
		return;
	}
	//funcion actualizar sucursal
	
	//esta funcion lista todas las sucursales
	function listarSucursal()
	{
		//se crea objeto-listar con la consulta
		$listar = new listar("select * from sucursal",array());
		//imprime los datos obtenidos
		echo $listar->lista();
		return;
	}
	//funcion listar sursal

	//esta funcion imprime los datos de una sucursal
	function detallesSucursal()
	{
		//verificamos que no nos envien datos vacios  	
		if( !empty( $_REQUEST['id_sucursal'] ) )
		{
			//asignamos valores obtenidos a las variables
			$id = $_REQUEST['id_sucursal'];			
			//creamos un objeto - sucursal existente
			$sucursal = new sucursalExistente($id);
			//verificamos que exista la sucursal
			if( $sucursal->existe() )										ok_datos( 'datos: '.$sucursal->json());				//imprimimos json con los datos
			else															fail( "No se encontró la sucursal especificada " );	//no existe sucursal

		}//if verifica datos
		else 																fail( "Faltan parametros" );						//datos incompletos
		return;
	}
	//funcion detalle sucursal
	
	
	
	/*
	Aqui van las funciones de encargado que se llevan a cabo en cada sucursal
	*/
	
	
	//esta funcion inserta un encargado a una sucursal
	function insertarEncargado()
	{
		//verificamos que no nos envien datos vacios  
		if((!empty($_REQUEST['id_usuario']))&&(!empty($_REQUEST['porciento'])))
		{
			//asignamos valores obtenidos a las variables
			$id_usuario=$_REQUEST['id_usuario'];
			$porciento=$_REQUEST['porciento'];
			//creamos un objeto usuario
			$usuario=new usuario_existente($id_usuario);
			//verificamos que exista el usuario
			if($usuario->existe())
			{
				//creamos objeto-encargado
				$encargado=new encargado($id_usuario,$porciento);
				//verficamos que no exista
				if(!$encargado->existe())
				{
					//verificamos que el porciento sea correcto
					if($encargado->porcientoValido())
					{
						//intentamos insertar al encargado
						if($encargado->inserta())							ok();												//insercion existosa
						else												fail("Error al guardar al encargado.");				//fallo la insercion
					}//if porciento valido
					else 													fail("El porcentaje debe ser menor para no exeder el 100%");//excede el 100%
				}//if encargado no existe
				else 														fail("Ya existe este encargado.");					//encargado existente
			}//if existe usuario
			else 															fail("El usuario no existe");						//usuario inexistente	
		}//if verificar datos
		else																fail("Faltan datos.");								//datos incompletos
		return;
	}
	//funcion insertar encargado
	
	//esta funcion elimina a un encargado a una sucursal
	function eliminarEncargado()
	{
		//verificamos que no nos envien datos vacios  
		if((!empty($_REQUEST['id_usuario'])))
		{
			//asignamos valores obtenidos a las variables
			$id_usuario=$_REQUEST['id_usuario'];
			//creamos objeto-encargado
			$encargado=new encargado_existente($id_usuario);
			//verficamos que exista
			if($encargado->existe())
			{
				//intentamos eliminar al encargado
				if($encargado->borra())									ok();												//borrado existoso
				else													fail("Error al guardar al encargado.");				//fallo el borado
			}//if encargado  existe
			else 														fail("No existe este encargado.");					//encargado inexistente
		}//if verificar datos
		else															fail("Faltan datos.");								//datos incompletos
		return;
	}
	//funcion eliminar encargado
	
	//esta funcion inserta un encargado a una sucursal
	function cambiarEncargado()
	{
		//verificamos que no nos envien datos vacios  
		if((!empty($_REQUEST['id_usuario']))&&(!empty($_REQUEST['porciento'])))
		{
			//asignamos valores obtenidos a las variables
			$id_usuario=$_REQUEST['id_usuario'];
			$porciento=$_REQUEST['porciento'];
			//creamos un objeto usuario
			$usuario=new usuario_existente($id_usuario);
			//verificamos que exista el usuario
			if($usuario->existe())
			{
				//creamos objeto-encargado
				$encargado=new encargado($id_usuario,$porciento);
				//verificamos que el porciento sea correcto
					//intentamos insertar al encargado
					if($encargado->cambiar())							ok();												//cambio existoso
					else
					{
						if($encargado->porcientoValido())						fail("Error al cambiar al encargado.");				//fallo el cambio
						else 													fail("El porcentaje debe ser menor para no exeder el 100%");//excede el 100%
					}
			}//if existe usuario
			else 														fail("El usuario no existe");						//usuario inexistente	
		}//if verificar datos
		else															fail("Faltan datos.");								//datos incompletos
		return;
	}
	//funcion insertar encargado
	
	
	/*
	Aqui van las funciones de gastos que se llevaran a cabo en las sucursales
	*/
	
	
	//esta funcion inserta un gasto a una sucursal
	function insertarGasto()
	{
		//verificamos que no nos envien datos vacios  
		if((!empty($_REQUEST['concepto']))&&(!empty($_REQUEST['monto']))&&(!empty($_REQUEST['fecha'])))
		{
			//asignamos valores obtenidos a las variables
			$concepto=$_REQUEST['concepto'];
			$monto=$_REQUEST['monto'];
			$fecha=$_REQUEST['fecha'];
			$id_sucursal=$_SESSION['sucursal_id'];
			$id_usuario=$_SESSION['id_usuario'];
			//creamos objeto-sucursal
			$sucursal=new sucursalExistente($id_sucursal);
			//verificamos que la sucursal exista
			if($sucursal->existe())
			{
				//creamos objeto-usuario
				$usuario=new usuarioExistente($id_usuario);
				if($usuario->existe())
				{
					//creamos objeto-gasto
					$gasto=new gasto($concepto,$monto,$fecha,$id_sucursal,$id_usuario);
					//verficamos que no exista
					if(!$gasto->existe())
					{
						//intentamos insertar al gasto
						if($gasto->inserta())								ok();											//insercion existosa
						else												fail("Error al guardar al gasto.");				//fallo la insercion
					}//if gasto no existe
					else 													fail("Ya existe este gasto.");					//gasto existente
				}//if existe usuario
				else														fail("El usuario no existe");					//usuario inexistente
			}//if existe sucursal
			else															fail("La sucursal no existe");					//sucursal inexistente
		}//if verificar datos
		else																fail("Faltan datos.");							//datos incompletos
		return;
	}
	//funcion insertar gasto
	
	
	//esta funcion elimina a un gasto de una sucursal
	function eliminarGasto()
	{
		//verificamos que no nos envien datos vacios  
		if((!empty($_REQUEST['id_gasto'])))
		{
			//asignamos valores obtenidos a las variables
			$id_gasto=$_REQUEST['id_gasto'];
			//creamos objeto-gasto
			$gasto=new gastoExistente($id_gasto);
			//verficamos que exista
			if($gasto->existe())
			{
				//intentamos eliminar el gasto
				if($gasto->borra())										ok();												//borrado existoso
				else													fail("Error al guardar el gasto.");					//fallo el borado
			}//if gasto  existe
			else 														fail("No existe este gasto.");						//gasto inexistente
		}//if verificar datos
		else															fail("Faltan datos.");								//datos incompletos
		return;
	}
	//funcion eliminar gasto
	
	
	//esta funcion actualiza un gasto a una sucursal
	function actualizarGasto()
	{
		//verificamos que no nos envien datos vacios  
		if((!empty($_REQUEST['id_gasto']))&&(!empty($_REQUEST['concepto']))&&(!empty($_REQUEST['monto']))&&(!empty($_REQUEST['id_sucursal']))&&(!empty($_REQUEST['id_usuario'])))
		{
			//asignamos valores obtenidos a las variables
			$id_gasto=$_REQUEST['id_gasto'];
			$concepto=$_REQUEST['concepto'];
			$monto=$_REQUEST['monto'];
			$id_sucursal=$_REQUEST['id_sucursal'];
			$id_usuario=$_REQUEST['id_usuario'];
			//creamos objeto-sucursal
			$sucursal=new sucursalExistente($id_sucursal);
			//verificamos que la sucursal exista
			if($sucursal->existe())
			{
				//creamos objeto-usuario
				$usuario=new usuarioExistente($id_usuario);
				if($usuario->existe())
				{
					//creamos objeto-gasto
					$gasto=new gastoExistente($id_gasto);
					//verficamos que exista
					if($gasto->existe())
					{
						//asignamos valriables al objeto
						$gasto->concepto=$concepto;
						$gasto->monto=$monto;
						$gasto->id_sucursal=$id_sucursal;
						$gasto->id_usuario=$id_usuario;
						//intentamos actualizar al gasto
						if($gasto->actualiza())								ok();											//actualizacion existosa
						else												fail("Error al guardar al gasto.");				//fallo la actualizacion
					}//if gasto  existe
					else 													fail("No existe este gasto.");					//gasto inexistente
				}//if existe usuario
				else														fail("El usuario no existe");					//usuario inexistente
			}//if existe sucursal
			else															fail("La sucursal no existe");					//sucursal inexistente
		}//if verificar datos
		else																fail("Faltan datos.");							//datos incompletos
		return;
	}
	//funcion actualizar gasto

	//esta funcion inserta un ingreso a una sucursal
	function insertarIngreso()
	{
		//verificamos que no nos envien datos vacios  
		if((!empty($_REQUEST['concepto']))&&(!empty($_REQUEST['monto']))&&(!empty($_REQUEST['fecha'])))
		{
			//asignamos valores obtenidos a las variables
			$concepto=$_REQUEST['concepto'];
			$monto=$_REQUEST['monto'];
			$fecha=$_REQUEST['fecha'];
			$id_sucursal=$_SESSION['sucursal_id'];
			$id_usuario=$_SESSION['id_usuario'];
			//creamos objeto-sucursal
			$sucursal=new sucursalExistente($id_sucursal);
			//verificamos que la sucursal exista
			if($sucursal->existe())
			{
				//creamos objeto-usuario
				$usuario=new usuarioExistente($id_usuario);
				if($usuario->existe())
				{
					//creamos objeto-ingreso
					$ingreso=new ingreso($concepto,$monto,$fecha,$id_sucursal,$id_usuario);
					//verficamos que no exista
					if(!$ingreso->existe())
					{
						//intentamos insertar al ingreso
						if($ingreso->inserta())								ok();											//insercion existosa
						else												fail("Error al guardar al ingreso.");				//fallo la insercion
					}//if gasto no existe
					else 													fail("Ya existe este ingreso.");					//gasto existente
				}//if existe usuario
				else														fail("El usuario no existe");					//usuario inexistente
			}//if existe sucursal
			else															fail("La sucursal no existe");					//sucursal inexistente
		}//if verificar datos
		else																fail("Faltan datos.");							//datos incompletos
		return;
	}
	//funcion insertar ingreso	
	
	
	//Esta funcion nos genera el reporte de corte para la reparticion de ganancias
	//si se envia un corte=true inserta el corte en la bd
	function reparticionGanancias(){
		if((!empty($_REQUEST['de']))&&(!empty($_REQUEST['al'])))
		{
			$con=new bd_default();
			$inicio=$_REQUEST['de'];
			$fin=$_REQUEST['al'];
			$queryVentas="
					SELECT sum(IF( v.tipo_venta =1, v.subtotal + v.iva, pv.monto )) as ventas
					FROM 
					ventas v LEFT JOIN pagos_venta pv 
					ON ( v.id_venta = pv.id_venta )
					where 
					(DATE(v.fecha) BETWEEN ? AND ? and v.tipo_venta=1)
					or
					(DATE(pv.fecha) BETWEEN  ? AND ?);
			";
			$params=array($inicio,$fin,$inicio,$fin);
			$ventas=$con->select_un_campo($queryVentas,$params);
			
			$params=array($inicio,$fin);
			$queryIngresos="SELECT sum(monto) 
							from ingresos 
							where 
							DATE(fecha) BETWEEN ? AND ? 
							";
			$ingresos=$con->select_un_campo($queryIngresos,$params);
			$queryGastos="SELECT sum(monto) 
						FROM gastos
						where 
						DATE(fecha) BETWEEN ? AND ? ;
						";
			$gastos=$con->select_un_campo($queryGastos,$params);
			$GananciasNetas=$ventas+$ingresos-$gastos;
			
			$queryReport="select u.nombre,?*(e.porciento*.01) as total,sum(deben) as deben
							from encargado e
							join usuario u
							on (e.id_usuario=u.id_usuario)
							left join adeudan_sucursal a
							on (e.id_usuario=a.id_usuario)
							where date(fecha)<?
							group by u.nombre,e.porciento;";
			$params=array($GananciasNetas,$fin);
			
			$listar = new listar($queryReport,$params);
			$salida="ventas: $ventas, ";
			$salida.=" gastos: $gastos, ";
			$salida.=" ingresos: $ingresos, ";
			$salida.=" GananciasNetas: $GananciasNetas, ";	
			$salida.=$listar->lista_datos("datos");	
			ok_datos($salida);
			
		}
		else														fail("faltan datos");
	}
?>
