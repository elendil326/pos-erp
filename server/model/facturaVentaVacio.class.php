<?php
class facturaVentaVacio extends factura_venta {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
?>
