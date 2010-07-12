<?php
class detalleInventarioVacio extends detalle_inventario {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
?>
