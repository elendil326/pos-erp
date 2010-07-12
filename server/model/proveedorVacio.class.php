<?php
class proveedorVacio extends proveedor {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
?>