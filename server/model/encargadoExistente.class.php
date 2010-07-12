<?php
class encargadoExistente extends encargado {
		public function __construct($id) {
			$this->bd=new bd_default();
			$this->obtener_datos($id);
		}
	}
?>