<?php
class clienteVacio extends cliente {       
		public function __construct( ) {
			$this->bd=new bd_default();
		}
	}
?>
