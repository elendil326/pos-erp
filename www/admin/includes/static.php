<?php 
class Tabla {
	

	private $header;
	private $rows;	
	
	public function __construct($header = array(), $rows = array()){
		$this->header = $header;
		$this->rows = $rows;
	}
	
	
	
	public function addRow( $row ){
		
	}
	
	
	public function render( $write = true ){
		

		$html = "";
		
		$html .= '<table border="1">';
		$html .= '<tr>';
		for( $a = 0; $a < sizeof($this->header) - 1 ; $a++ ){
			$html .= '<th>' . $this->header[$a] . '</th>';
		}

		$html .= '</tr>';
		
		for( $a = 0; $a < sizeof($this->rows) - 1 ; $a++ ){
			$html .= '<tr>';
			

			$arr = (array)$this->rows[$a];
			
			foreach( $arr as $col ){
				$html .= '<td>' . $col .'</td>';
			}
			
			$html .='</tr>';
		}
		
		$html .= "</table>";
		

		
		if($write){
			echo $html;
			return true;
		}else{
			return $html;			
		}

	}
	
}