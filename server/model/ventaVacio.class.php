<?php
class ventaVacio extends venta {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
?>