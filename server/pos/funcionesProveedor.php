<?php
	include("../AddAllClass.php");

	function insertarProveedor(){
		$id =null;
		$rfc=$_REQUEST['rfcP'];
		$nombre=$_REQUEST['nombreP'];
		$direccion=$_REQUEST['direccionP'];
		$telefono=$_REQUEST['telefonoP'];
		$e_mail=$_REQUEST['e_mailP'];

		$proveedor = new proveedor($rfc,$nombre,$direccion,$telefono,$e_mail);
		
		if ($proveedor->inserta()){
			echo "{success: true }";
		}else{
			echo "{success: false }";
		}
	}
	function actualizarProveedor(){
		
		$proveedor = new proveedor_existente($_REQUEST['idP']); 
		$proveedor->id_proveedor=$_REQUEST['idP'];
		$proveedor->rfc=$_REQUEST['rfcP'];
		$proveedor->nombre=$_REQUEST['nombreP'];
		$proveedor->direccion=$_REQUEST['direccionP'];
		$proveedor->telefono=$_REQUEST['telefonoP'];
		$proveedor->e_mail=$_REQUEST['e_mailP'];
	
		
		if($proveedor->actualiza()){
			echo "{success: true}";
		}else{
			echo "{success: false}";
		}
	}
	
	function eliminarProveedor(){
		$proveedor = new proveedor_existente($_REQUEST['idP']); 
		$proveedor->id_proveedor =$_REQUEST['idP'];
		if($proveedor->borra()){
			echo "{success: true }";
		}else{
			echo "{success : false }";
		}
	}
	
	function listarProveedores(){
		$listar = new listar("select * from proveedor",array());
		echo $listar->lista();
		return $listar->lista();
	}
	
	function mostrarProveedor(){
		$id=$_REQUEST['idP'];
		$proveedor = new proveedor_existente($id);
		$proveedor->id_proveedor=$id;
		echo "{ success: true , \"datos\":".$proveedor->json()."}";
	}
	
	switch ($_REQUEST['method']){
	case 'listarProveedores': 			listarProveedores(); 	break;
	case 'actualizarProveedor':			actualizarProveedor();	break;
	case 'eliminarProveedor':			eliminarProveedor(); 	break;
	case 'insertarProveedor': 			insertarProveedor();	break;
	case 'mostrarProveedor':			mostrarProveedor();		break;
}//fin switch
	
?>