<?php
class cliente_existente extends cliente {
		public function __construct($id) {
			//echo "me cree: ".$id;
			$this->bd=new bd_default();
			$this->obtener_datos($id);
		}
	}
?>
