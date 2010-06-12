<?php
	include("../AddAllClass.php");

	function insertar(){
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
	function modificar(){
		
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
	
	function eliminar(){
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
	
	switch ($_REQUEST['action']){
	case 'list':
		listarProveedores();
	break;
	case 'update':
		modificar();
	break;
	case 'delete':
		eliminar();
	break;
	case 'insert': 
		insertar();
	break;
	case 'showProveedor':
		mostrarProveedor();
	break;
}//fin switch
	
?>