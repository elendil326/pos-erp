<?php
class inventarioVacio extends inventario {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
?>
