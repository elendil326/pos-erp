<?php
class pagosVentaVacio extends pagos_venta {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
?>
