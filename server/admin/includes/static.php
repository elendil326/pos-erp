<?php 


function moneyFormat( $val ){

	return sprintf( "<b>$</b>%.2f", $val);
}


function percentFormat( $val ){
	
	return sprintf( "%.2f<b>%%</b>", $val);
	
}

class Tabla {
	

	private $header;
	private $rows;	
	private $actionFunction;
	private $actionField;
	
	
	private $specialRender;
	private $noDataText;
	
	public function __construct($header = array(), $rows = array()){
		$this->header = $header;
		$this->rows = $rows;
		$this->specialRender = array();
	}
	
	public function addNoData ( $msg ){
		$this->noDataText = $msg;
	}

	public function addRow( $row ){
		
	}
	
	
	public function addOnClick( $actionField, $actionFunction, $sendJSON = false ){
		$this->actionField = $actionField;
		$this->actionFunction = $actionFunction;
		$this->actionSendJSON = $sendJSON;
	}
	

	
	public function addColRender( $id, $fn ){
		
		array_push( $this->specialRender, array( $id => $fn ) );
	}
	


	public function render( $write = true ){
		
		
		if(sizeof($this->rows) == 0){
			if($write){
				return print( $this->noDataText );
			}else{
				return $this->noDataText;			
			}
		}
		
		
		
		$html = "";
		
		$html .= '<table border="0" style="width:100%">';
		$html .= '<tr >';
		
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
				if($this->actionSendJSON){
					
					$html .= '<tr style="cursor: pointer;" onClick="' . $this->actionFunction. '( \''. urlencode(json_encode($row)) . '\' )" ';
				}else{
					$html .= '<tr style="cursor: pointer;" onClick="' . $this->actionFunction. '( ' . $row[ $this->actionField ] . ' )" ';
				}
				
			}else{
				$html .= '<tr';
			}			

            $html .= ' onmouseover="this.style.backgroundColor = \'#D7EAFF\'" onmouseout="this.style.backgroundColor = \'white\'" >';

			foreach ( $this->header  as $key => $value){
				if( array_key_exists( $key , $row )){

					//ver si necesita rendereo especial
					$found = null;
					for( $k = 0; $k < sizeof($this->specialRender); $k++ ){
						if( array_key_exists( $key, $this->specialRender[$k] )){
								$found = $this->specialRender[$k];
						}
					}
					
					
					if( $found ){
						$html .=  "<td>" . call_user_func( $found[$key] , $row[ $key ]) . "</td>";
					}else{
						$html .=  "<td>" . $row[ $key ] . "</td>";
					}
					

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
