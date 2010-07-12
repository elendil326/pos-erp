<?php
class detalleVentaExistente extends detalle_venta {
		public function __construct($idv,$idp) {
			$this->bd=new bd_default();
			$this->obtener_datos($idv,$idp);
		}
	}
?>