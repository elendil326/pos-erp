<?php 
class detalleCotizacionExistente extends detalle_cotizacion {
		public function __construct($idc,$idp) {
			$this->bd=new bd_default();
			$this->obtener_datos($idc,$idp);
		}
	}
?>