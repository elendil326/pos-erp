<?php
class productosProveedorVacio extends productos_proveedor {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
?>
