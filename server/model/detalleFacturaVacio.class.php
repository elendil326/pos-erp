<?php
class detalleFacturaVacio extends detalle_factura {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
?>
