<?php
class cuentaProveedorVacio extends cuenta_proveedor {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
?>
