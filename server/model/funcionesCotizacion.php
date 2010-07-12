<?php
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
			if($this->actualizaCabecera($this->detalle_cotizacion,$idCot,$idClien)){
				echo "{ success : true , \"datos\" : ".json_encode($this->detalle_cotizacion)."}";
			}else{
				echo "{success: false}";	
			}
		}else{
			echo "{success: false , \"error\": [{\"metodo\":\"if_detalleCot->inserta()\"}]}";
		}
	}
	function eliminarProductoCotizacion(){
		$idCot=$_REQUEST['idCot'];
		$idProd=$_REQUEST['id_Producto'];
		$cantidad=$_REQUEST['cantidad'];
		$precio =$_REQUEST['precio'];
		$idClien=$_REQUEST['idClien'];
		$detalleCot= new detalle_cotizacion($idCot,$idProd,$cantidad,$precio);
		
		if($detalleCot->borra()){
			$cotizacion = new cotizacion($idClien);
			$cotizacion->id_cotizacion=$idCot;
			$this->detalle_cotizacion =$cotizacion->detalle_cotizacion($idCot);
			if($this->actualizaCabecera($this->detalle_cotizacion,$idCot,$idClien)){
				echo "{ success : true , \"datos\" : ".json_encode($this->detalle_cotizacion)."}";
			}else{
				echo "{success: false}";	
			}
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
	function actualizarCantidadProductoDetCot(){
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
			if($this->actualizaCabecera($this->detalle_cotizacion,$idCot,$idClien)){
				echo "{ success : true , \"datos\" : ".json_encode($this->detalle_cotizacion)."}";
			}else{
				echo "{success: false}";	
			}
		}else{
			echo "{success: false}";
		}
	}//fin modificarCantidad
	function insertarCotizacion(){
		$idClien=$_REQUEST['idClien'];
		$cotizacion = new cotizacion($idClien);
				
		if($cotizacion->inserta()){
			echo "{success: true}";
		}else{
			echo "{success: false}";
		}
	}//fin insertar
	function eliminarCotizacion(){
		$idCot=$_REQUEST['idCot'];
		
		$cotizacion = new cotizacionExistente($idCot);
		$cotizacion->id_cotizacion=$idCot;
		if($cotizacion->borra()){
			echo "{success: true}";
		}else{
			echo "{success: false}";
		}
	}		 
	function actualizaCabecera($detalle_cot,$idCot,$idClien){
		$subtot=0;
		$dim = count($detalle_cot);
		//var_dump($detalle_cot);
		for($i=0;$i<$dim;$i++){
			$subtot += $detalle_cot[$i]["subtotal"];
			//echo "subtot".$i." = ".$detalle_cot[$i]["subtotal"];
		}
		$iva = new impuesto_existente(5);//en mi bd el iva es el id 5
		$iva->id_impuesto=5;
		//$iva->obtener_datos(5);
		$iva_valor=$iva->valor;
		
		$cotizacion = new cotizacionExistente($idCot);
		$cotizacion->id_cotizacion=$idCot;
		$cotizacion->id_cliente=$idClien;
		$cotizacion->subtotal=$subtot;
		$cotizacion->iva=($iva_valor/100) * $subtot;
		//echo "iva: ".$iva->valor;
		//echo "id_cot: ".$cotizacion->id_cotizacion." subtotal: ".$cotizacion->subtotal." iva: ".$cotizacion->iva;
		//echo "id_cot: ".$idCot." subtotal: ".$subtot." iva: ".($iva_valor/100) * $subtot;
		if($cotizacion->actualiza()){
			return true;
		}else{
			return false;
		}
	}//actualiza cabecera
}//fin clase
?>
