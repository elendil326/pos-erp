<?php
class ingresoVacio extends ingreso {   
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
?>