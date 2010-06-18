<?php
	include("../AddAllClass.php");

	function insertarCliente(){
		$id =null;
		$rfc=$_REQUEST['rfc'];
		$nombre=$_REQUEST['nombre'];
		$direccion=$_REQUEST['direccion'];
		$telefono=$_REQUEST['telefono'];
		$e_mail=$_REQUEST['e_mail'];
		$limite_credito=$_REQUEST['limite_credito'];
		$cliente = new cliente($rfc,$nombre,$direccion,$telefono,$e_mail,$limite_credito);
		
		if ($cliente->inserta()){
			echo "{success: true }";
		}else{
			echo "{success: false }";
		}
	}
	function actualizarCliente(){
		
		$cliente = new cliente_existente($_REQUEST['id']); 
		$cliente->id_cliente=$_REQUEST['id'];
		$cliente->rfc=$_REQUEST['rfc'];
		$cliente->nombre=$_REQUEST['nombre'];
		$cliente->direccion=$_REQUEST['direccion'];
		$cliente->telefono=$_REQUEST['telefono'];
		$cliente->e_mail=$_REQUEST['e_mail'];
		$cliente->limite_credito=$_REQUEST['limite_credito'];
	
		
		if($cliente->actualiza()){
			echo "{success: true}";
		}else{
			echo "{success: false}";
		}
	}
	
	function eliminarCliente(){
		$cliente = new cliente_existente($_REQUEST['id']); 
		$cliente->id_cliente =$_REQUEST['id'];
		if($cliente->borra()){
			echo "{success: true }";
		}else{
			echo "{success : false }";
		}
	}
	
	function listarClientes(){
		$listar = new listar("select * from cliente",array());
		echo $listar->lista();
		return $listar->lista();
	}
	
	function mostrarCliente(){
		$id=$_REQUEST['id'];
		//$cliente = new listar("select * from cliente where id_cliente=".$id.";",array());
		$cliente = new cliente_existente($id);
		$cliente->id_cliente=$id;
		echo "{ success: true , \"datos\":".$cliente->json()."}";
		//echo "".$cliente->lista();
	}
	
	switch ($_REQUEST['method']){
	case 'listarClientes':
		listarClientes();
	break;
	case 'actualizarCliente':
		actualizarCliente();
	break;
	case 'eliminarCliente':
		eliminarCliente();
	break;
	case 'insertarCliente': 
		insertarCliente();
	break;
	case 'mostrarCliente':
		mostrarCliente();
	break;
}//fin switch
	
?>