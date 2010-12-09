<?php 


function moneyFormat( $val ){
	
	return "$" . $val;
	
}

class Tabla {
	

	private $header;
	private $rows;	
	private $actionFunction;
	private $actionField;
	
	public function __construct($header = array(), $rows = array()){
		$this->header = $header;
		$this->rows = $rows;
	}
	
	
	
	public function addRow( $row ){
		
	}
	
	
	public function addOnClick( $actionField, $actionFunction ){
		$this->actionField = $actionField;
		$this->actionFunction = $actionFunction;
	}
	
	public function render( $write = true ){
		

		$html = " ";
		
		$html .= '<table border="1">';
		$html .= '<tr>';
		
		foreach ( $this->header  as $key => $value){
			$html .= '<th>' . $value . '</th>';			
		}
		

		$html .= '</tr>';
		
		//cicle trough rows
		for( $a = 0; $a < sizeof($this->rows) ; $a++ ){

			
			if( !is_array($this->rows[$a]) ){
				$row = $this->rows[$a]->asArray();
			}else{
				$row = $this->rows[$a];
			}


			if( isset($this->actionField)){
				$html .= '<tr onClick="' . $this->actionFunction. '( ' . $row[ $this->actionField ] . ' )">';
			}else{
				$html .= '<tr>';
			}			

			foreach ( $this->header  as $key => $value){
				if( array_key_exists( $key , $row )){
					$html .=  "<td>" . $row[ $key ] . "</td>";
				}				
			}
			
			$html .='</tr>';
		}
		
		$html .= "</table>";
		

		
		if($write){
			return print( $html);
		}else{
			return $html;			
		}

	}
	
}