<?php
class cuentaClienteVacio extends cuenta_cliente {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
?>
