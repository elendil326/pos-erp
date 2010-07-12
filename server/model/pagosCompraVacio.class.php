<?php
class pagosCompraVacio extends pagos_compra {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
?>