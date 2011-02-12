<?php 


function moneyFormat( $val ){
	
	//return "$&nbsp;<b>" . money_format( $val, 2 ) . "</b>";
	return sprintf( "$&nbsp;<b>%.2f</b>", $val );
}




function percentFormat( $val ){
	
	return sprintf( "%.2f<b>%%</b>", $val);
	
}

function toDate($fecha){
	return date( POS_DATE_FORMAT, strtotime($fecha) );
}

class Tabla {
	

	private $header;
	private $rows;	
	private $actionFunction;
	private $actionField;
	
	private	$actionSendID;
	private $renderRowIds;
	private $specialRender;
	
	private $noDataText;
	
	public function __construct($header = array(), $rows = array()){
		$this->header = $header;
		$this->rows = $rows;
		$this->specialRender = array();
		$renderRowIds = null;
	}
	
	
	public function renderRowId( $prefix )
	{
		$this->renderRowIds = $prefix;
	}
	
	public function addNoData ( $msg ){
		$this->noDataText = $msg;
	}

	public function addRow( $row ){
		
	}
	
	
	public function addOnClick( $actionField , $actionFunction, $sendJSON = false, $sendId = false ){
		$this->actionField 	  = $actionField;
		$this->actionFunction = $actionFunction;
		$this->actionSendJSON = $sendJSON;
		$this->actionSendID = $sendId;		
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
			$html .= '<th align="left">' . $value . '</th>';			
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
					
					$html .= '<tr style=" cursor: pointer;" onClick="' . $this->actionFunction. '( \''. urlencode(json_encode($row)) . '\' )" ';
				}elseif($this->actionSendID){
					$html .= '<tr style=" cursor: pointer;" onClick="' . $this->actionFunction. '( \'' . $this->renderRowIds . $a . '\' )" ';
				}else{
					$html .= '<tr style=" cursor: pointer;" onClick="' . $this->actionFunction. '( ' . $row[ $this->actionField ] . ' )" ';				
				}
				
			}else{
				$html .= '<tr ';
			}			

			//renderear ids o no
			if($this->renderRowIds != null)	{
				$html .= " id=\"". $this->renderRowIds . $a ."\" ";
			}

            $html .= ' onmouseover="this.style.backgroundColor = \'#D7EAFF\'" onmouseout="this.style.backgroundColor = \'white\'" >';
            
			$i = 0;
			
			foreach ( $this->header  as $key => $value){
			
				if( array_key_exists( $key , $row )){

					//ver si necesita rendereo especial
					$found = null;
				
					for( $k = 0; $k < sizeof($this->specialRender); $k++ ){
						
						if( array_key_exists( $key, $this->specialRender[$k] )){
								$found = $this->specialRender[$k];
						}
					}
					
					if($i++ % 2 == 0){
						$bgc = "";
					}else{
						$bgc = ""; //"rgba(200, 200, 200, 0.199219)";
					}
					
					if( $found ){
						
						$html .=  "<td style='background-color:".$bgc.";'>" . call_user_func( $found[$key] , $row[ $key ], $row ) . "</td>";							

					}else{
						$html .=  "<td style='background-color:".$bgc.";'>" . $row[ $key ] . "</td>";
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
