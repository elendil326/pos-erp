<?php 
	class detalleCorteExistente extends detalle_corte {
		public function __construct($num_c,$nom) {
			$this->bd=new bd_default();
			$this->obtener_datos($numc,$nom);
		}
	}
?>