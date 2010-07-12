<?php
class cotizacionVacio extends cotizacion {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
?>