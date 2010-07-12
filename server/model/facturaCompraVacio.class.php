<?php
class facturaCompraVacio extends factura_compra {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
?>
