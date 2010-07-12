<?php
class detalleCompraExistente extends detalle_compra {
		public function __construct($idc,$idp) {
			$this->bd=new bd_default();
			$this->obtener_datos($idc,$idp);
		}
	}
?>
