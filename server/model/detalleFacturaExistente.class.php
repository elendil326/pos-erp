<?php
class detalleFacturaExistente extends detalle_factura {
		public function __construct($idv,$idp) {
			$this->bd=new bd_default();
			$this->obtener_datos($idv,$idp);
		}
	}
?>
