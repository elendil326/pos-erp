<?php 
	class unidadVentaExistente extends unidad_venta{
		function __construct($id){ 	 	
			$this->obtener_datos($id);
			$this->bd=new bd_default();
		}
		function __destruct(){ 
			 return; 
		}
	}
	
	
?>
