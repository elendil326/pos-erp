<?php	
/*este documentotiene todas las funciones de impuestos
como insertar, eliminar, actualizar, consultas, listar 
y algunas otras funciones
*/


	//esta funcion inserta un impuesto, solo se le envia la descripcion(iva,isr,etc) y el valor de porcentaje de dicho impuesto
	function insertarImpuesto(){
		//verificamos que no nos envien datos vacios
		if((!empty($_REQUEST['descripcion']))&&(!empty($_REQUEST['valor'])))
		{
			//asignamos valores obtenidos a las variables
			$descripcion=$_REQUEST['descripcion'];
			$valor=$_REQUEST['valor'];
			//creamos un objeto del tipo impuesto
			$impuesto=new Impuesto($descripcion,$valor);
			//verificamos que no exista este impuesto
			if(!$impuesto->existe())
			{
				//insertamos el impuesto
				if($impuesto->inserta())					ok();														//enviamos un success true a la operacion
				else										fail("Error al guardar el Impuesto.");						//si no pudo insertar
			}//if no existe impuesto
			else 											fail("Ya existe este Impuesto.");							//si el impuesto ya existe
		}//if verifica datos
		else												fail("Faltan datos.");										//si no se enviaron los parametros necesarios
		return;
	}
	//funcion insertarImpuesto
	
	//esta funcion recibe el id del impuesto y lo elimina
	function eliminarImpuesto()
	{
		//verificamos que no nos envien datos vacios
		if(!empty($_REQUEST['id_impuesto']))
		{
			//asignamos valor obtenido a la variable id
			$id=$_REQUEST['id_impuesto'];
			//creamos un objeto de la clase impuesto_existente (al pasarle el id obtiene los demas datos automaticamente)
			$impuesto=new Impuesto_existente($id);
			//verificamos que el impuesto si exista
			if($impuesto->existe())
			{
				//de existir lo intenta borrar
				if($impuesto->borra())						ok();														//exito al eliminar
				else										fail("Error al borrar Impuesto.");							//no pudo borrar el impuesto
			}//if existe impuesto
			else 											fail("El Impuesto que desea eliminar no existe.");			//el impuesto no existe
		}//if verifica datos
		else fail("faltan datos.");
		return;
	}
	//funcion eliminarImpuesto
	
	//esta funcion actualiza los datos de un impuesto existente, recibe el id del impuesto y la descripcion y valor que se desea asignar
	function actualizarImpuesto()
	{
		//verificamos que no nos envien datos vacios
		if((!empty($_REQUEST['id_impuesto']))&&(!empty($_REQUEST['descripcion']))&&(!empty($_REQUEST['valor'])))
		{
			//asignamos los valores obtenidos a las variables
			$id=$_REQUEST['id_impuesto'];
			$descripcion=$_REQUEST['descripcion'];
			$valor=$_REQUEST['valor'];
			//creamos un objeto del tipo objeto_existente
			$impuesto=new Impuesto_existente($id);
			//verificamos que el impuesto si exista
			if($impuesto->existe())
			{
				//asignamos los valores obtenidos a nuestro objeto que creamos
				$impuesto->descripcion=$descripcion;
				$impuesto->valor=$valor;
				//intentamos actualizar el impuesto
				if($impuesto->actualiza())							ok();													//se logro actualizar
				else												fail("Error al modificar el Impuesto.");				//no se pudo actualizar
			}//if existe impueto
			else													fail("El Impuesto que desea modificar no existe.");		//no existia el impuesto con el id
		}//if verifica datos
		else														fail("Faltan datos.");									//datos incompletos
		return;
	}
	//funcion actualizarImpuesto
	
	//esta funcion nos regresa un json con todos los impuestos de la bd y todos sus campos
	function listarImpuesto()
	{
		//creamos un objeto de la clase listar con la consulta y parametros deseados
		$listar = new listar("select * from impuesto",array());
		//imprimimos la lista de datos
		echo $listar->lista();
		return;
	}
	//funcion listarImpuestos
?>