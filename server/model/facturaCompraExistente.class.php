<?php
class facturaCompraExistente extends factura_compra {
		public function __construct($id) {
			$this->bd=new bd_default();
			$this->obtener_datos($id);
		}
	}
?>
