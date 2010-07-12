<?php
class impuestoVacio extends impuesto {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
?>