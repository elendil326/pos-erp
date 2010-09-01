<?php
	
	function sanitize(&$params) {
		foreach($params as &$param) {
			// se remueven los tags html
			if (is_string($param)) {
				$param = strip_tags($param);
			}

			// se comprueba que no sea un string en blanco
			if (!ctype_space($param)){
				$param = trim($param);
			}
			else
			{
				echo '{ "success" : false, "error" : "Error: existen campos en blanco" }';
			}

		}
		
	}
	
	
?>