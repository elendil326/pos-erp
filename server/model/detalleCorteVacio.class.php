<?php 
	class detalleCorteVacio extends detalle_corte {
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
?>