<?php
class encargadoVacio extends encargado {       
		public function __construct( ) {
			$this->bd=new bd_default();
		}
	}
?>