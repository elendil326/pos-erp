<?php
class notaRemisionExistente extends nota_remision {
		public function __construct($id) {
			$this->bd=new bd_default();
			$this->obtener_datos($id);
		}
	}
?>
