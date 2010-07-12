<?php
class cuentaClienteExistente extends cuenta_cliente {
		public function __construct($id) {
			$this->bd=new bd_default();
			$this->obtener_datos($id);
		}
	}
?>
