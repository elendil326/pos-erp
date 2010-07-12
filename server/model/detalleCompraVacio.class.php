<?php
class detalleCompraVacio extends detalle_compra {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
?>