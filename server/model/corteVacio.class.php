<?php 
	
	class corteVacio extends corte {
		public function __construct() {
			$this->bd=new bd_default();
		}
	}

?>