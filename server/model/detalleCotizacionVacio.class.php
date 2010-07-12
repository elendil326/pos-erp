<?php
class detalleCotizacionVacio extends detalle_cotizacion {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
?>