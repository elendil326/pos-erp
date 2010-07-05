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
			$sucursal=new sucursal_existente($id);
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
			$sucursal=new sucursal_existente($id);
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
			$sucursal = new sucursal_existente($id);
			//verificamos que exista la sucursal
			if( $sucursal->existe() )										ok_datos( 'datos: '.$sucursal->json());				//imprimimos json con los datos
			else															fail( "No se encontró la sucursal especificada " );	//no existe sucursal

		}//if verifica datos
		else 																fail( "Faltan parametros" );						//datos incompletos
		return;
	}
	//funcion detalle sucursal
?>
