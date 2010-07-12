<?php
class facturaVentaExistente extends factura_venta {
		public function __construct($id) {
			$this->bd=new bd_default();
			$this->obtener_datos($id);
		}
	}
?>
