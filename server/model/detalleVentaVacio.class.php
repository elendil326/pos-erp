<?php
class detalleVentaVacio extends detalle_venta {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
?>
