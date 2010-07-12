<?php
class detalleInventarioExistente extends detalle_inventario {
		public function __construct($idp,$ids) {
			$this->bd=new bd_default();
			$this->obtener_datos($idp,$ids);
		}
	}
?>
