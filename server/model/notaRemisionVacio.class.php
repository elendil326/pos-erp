<?php
class notaRemisionVacio extends nota_remision {      
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
?>
