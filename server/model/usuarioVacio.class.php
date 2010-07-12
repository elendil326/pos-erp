<?php
class usuarioVacio extends usuario {       
		public function __construct( ) {
			$this->bd=new bd_default();
		}
	}
?>
