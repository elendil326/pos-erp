<?php
class gastoVacio extends gasto {   
		public function __construct() {
			$this->bd=new bd_default();
		}
	}
?>
