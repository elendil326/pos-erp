<?php
include("../AddAllClass.php");
class funcionesCotizacion{


	var $detalle_cotizacion = array();
	
	function __construct(){ 	 	 	 	 	 	
	}
	
	function agregarProductoCotizacion(){
		$idCot=$_REQUEST['idCot'];
		$idProd=$_REQUEST['id_Producto'];
		$cantidad=$_REQUEST['cantidad'];
		$precio =$_REQUEST['precio'];
		$idClien= $_REQUEST['idClien'];
		$detalleCot= new detalle_cotizacion($idCot,$idProd,$cantidad,$precio);

		if($detalleCot->inserta()){
			$cotizacion = new cotizacion($idClien);
			$cotizacion->id_cotizacion=$idCot;
			$this->detalle_cotizacion =$cotizacion->detalle_cotizacion($idCot);
			echo "{ success : true , \"datos\" : ".json_encode($this->detalle_cotizacion)."}";
		}else{
			echo "{success: false}";
		}
	}
	function eliminarProductoCotizacion(){
		$idCot=$_REQUEST['idCot'];
		$idProd=$_REQUEST['id_Producto'];
		$cantidad=$_REQUEST['cantidad'];
		$precio =$_REQUEST['precio'];
		$idClien=$_REQUEST['idClien'];
		$detalleCot= new detalle_cotizacion($id,$idProd,$cantidad,$precio);
		
		if($detalleCot->borra()){
			$cotizacion = new cotizacion($idClien);
			$cotizacion->id_cotizacion=$idCot;
			$this->detalle_cotizacion =$cotizacion->detalle_cotizacion($idCot);
			echo "{ success : true , \"datos\" : ".json_encode($this->detalle_cotizacion)."}";
		}else{
			echo "{success: false}";
		}
	}
	function listarCotizaciones(){
		$listar = new listar("SELECT id_cotizacion ,id_cliente,fecha,subtotal,iva,(iva + subtotal) as total FROM `cotizacion`",array());
		echo $listar->lista();
		return $listar->lista();
	}
	function mostrarDetalleCotizacion(){
		$idClien=$_REQUEST['idClien'];
		$idCot=$_REQUEST['idCot'];
		$cotizacion = new cotizacion($idClien);
		$cotizacion->id_cotizacion=$idCot;
		if(count($this->detalle_cotizacion =$cotizacion->detalle_cotizacion($idCot))>0){
			echo "{ success: true , \"datos\" : ".json_encode($this->detalle_cotizacion)."}";	
		}else{
			echo "{ success: false }";
		}
	}
	function modificarCantidadProducto(){
		$idCot=$_REQUEST['idCot'];
		$idProd=$_REQUEST['id_Producto'];
		$cantidad=$_REQUEST['cantidad'];
		$precio =$_REQUEST['precio'];
		$idClien=$_REQUEST['idClien'];
		$detalleCot= new detalle_cotizacion($id,$idProd,$cantidad,$precio);
	
		if($detalleCot->actualiza()){
			$cotizacion = new cotizacion($idClien);
			$cotizacion->id_cotizacion=$idCot;
			$this->detalle_cotizacion =$cotizacion->detalle_cotizacion($idCot);
			echo "{ success : true , \"datos\" : ".json_encode($this->detalle_cotizacion)."}";
		}else{
			echo "{success: false}";
		}
	}//fin modificarCantidad
	function insertar(){
		$idClien=$_REQUEST['idClien'];
		$cotizacion = new cotizacion($idClien);
				
		if($cotizacion->inserta()){
			echo "{success: true}";
		}else{
			echo "{success: false}";
		}
	}//fin insertar
	function eliminar(){
		$idClien=$_REQUEST['idClien'];
		$cotizacion = new cotizacion($idClien);
				
		if($cotizacion->borra()){
			echo "{success: true}";
		}else{
			echo "{success: false}";
		}
	}
}//fin clase


	$fC = new funcionesCotizacion();
switch ($_REQUEST['action']){
	case 'list':
		$fC->listarCotizaciones();
	break;
	case 'modificarCantidadProducto':
		$fC->modificarCantidadProducto();
	break;
	case 'delete':
		$fC->eliminar();
	break;
	case 'insert': 
		$fC->insertar();
	break;
	case 'agregarProducto':
		$fC->agregarProductoCotizacion();
	break;
	case 'eliminarProducto':
		$fC->eliminarProductoCotizacion();
	break;
	case 'showCotizacion':
		$fC->mostrarDetalleCotizacion();
	break;
}
?>