<?php


class TableComponent implements GuiComponent{


	private $header;
	protected $rows;	
	private $actionFunction;
	private $actionField;
	
	private	$actionSendID;
	private $renderRowIds;
	private $specialRender;
	private $convertToExtjs;
	
	private $noDataText;

	protected $simple_render;

	public function __construct( $header = array(), $rows = array()	){
		$this->header = $header;
		$this->rows = $rows;
		$this->specialRender = array();
		$renderRowIds = null;
		$this->noDataText = "No hay datos para mostrar.";
		$this->simple_render = false;
		$this->convertToExtjs = false;
	}
	


	public function setRows($rows )
	{
		$this->rows = $rows;
	}



	public function convertToExtJs($tof){
		$this->convertToExtjs = $tof;
	}



	public function renderRowId( $prefix )
	{
		$this->renderRowIds = $prefix;
	}
	



	public function addNoData ( $msg )
	{
		$this->noDataText = $msg;
	}




	public function addRow( $row )
	{
		array_push($this->rows, $row);
	}
	
	


	public function addOnClick( $actionField , $actionFunction, $sendJSON = false, $sendId = false )
	{
		$this->actionField 	  	= $actionField;
		$this->actionFunction 	= $actionFunction;
		$this->actionSendJSON 	= $sendJSON;
		$this->actionSendID 	= $sendId;		
	}
	

	
	public function addColRender( $id, $fn )
	{
		array_push( $this->specialRender, array( $id => $fn ) );
	}
	


	public function renderCmp(  ){
		
		//create some id;
		$id = "tc" . md5( rand() );

		//
		// Si no hay datos, regresa el mensaje 
		// 
		if(sizeof($this->rows) == 0){
			return $this->noDataText;
		}
		
		$html = "";

		if($this->convertToExtjs){
			$html .= '<script>
				var TableComponent = TableComponent || {};

				if(typeof TableComponent.convertToExtJs === "undefined"){
					TableComponent.convertToExtJs = ["'. $id .'"];
					TableComponent.ExtJsTables = [{ 
						id : "'.$id.'",
						actionFunction : "'.$this->actionFunction.'"
					}];


				}else{
					TableComponent.convertToExtJs.push("'.$id.'");

					TableComponent.ExtJsTables.push({
						id : "'.$id.'",
						actionFunction : "'.$this->actionFunction.'"
					});
						
				}</script>';
		}


		//
		// Iniciar la creacion de la tabla
		// 
		$html .= '<table border="0" style="width:100%" id="'.$id.'">';
		$html .= '<thead align = "left">';
		
		// Renderear los headers
		foreach ( $this->header  as $key => $value){
			$html .= '<th>' . $value . '</th>';			
		}

		$html .= '</thead><tbody>';
		
		// Cicle trought rows
		for( $a = 0; $a < sizeof( $this->rows ) ; $a++ ){

			//@TODO pagination should be implemented here
			/*if($a == 50){
				$html .= "<tr style='background-color:#3F8CE9; color:white; text-align:center;'><td colspan=" .sizeof($this->header). ">Mostrar siguientes 50</td></tr>";
				break;
			}*/

			//si el row no es un array intentar convertirlo
			if( !is_array($this->rows[$a]) ){
				$row = $this->rows[$a]->asArray();
			}else{
				$row = $this->rows[$a];
			}


			//
			// Render action fields if necesary
			// 
			if( isset($this->actionField)){
				//action field !
				if($this->convertToExtjs){
					$html .= '<tr  ';

					if($this->actionSendJSON){
						//$html .= '<tr style=" cursor: pointer;" onClick="' . $this->actionFunction. '( \''. urlencode(json_encode($row)) . '\' )" ';

					}elseif($this->actionSendID){
						//$html .= '<tr style=" cursor: pointer;" onClick="' . $this->actionFunction. '( \'' . $this->renderRowIds . $a . '\' )" ';

					}else{
						//$html .= '<tr style=" cursor: pointer;" onClick="' ." ';

						//$this->actionFunction. '( ' . $row[ $this->actionField ] . ' )
						$html .= " id='" . $row[ $this->actionField ] . "' ";
					}

				}else{

					if($this->actionSendJSON){
						$html .= '<tr style=" cursor: pointer;" onClick="' . $this->actionFunction. '( \''. urlencode(json_encode($row)) . '\' )" ';

					}elseif($this->actionSendID){
						$html .= '<tr style=" cursor: pointer;" onClick="' . $this->actionFunction. '( \'' . $this->renderRowIds . $a . '\' )" ';

					}else{
						$html .= '<tr style=" cursor: pointer;" onClick="' . $this->actionFunction. '( ' . $row[ $this->actionField ] . ' )" ';		
								
					}					
				}

				
			}else{
				// or else just render the tr
				$html .= '<tr ';
				
			}			

			// Render id's or not
			if($this->renderRowIds != null){
				$html .= " id=\"". $this->renderRowIds . $a ."\" ";
			}

			// Render the effect

			if($this->convertToExtjs){
            	//extjs already has this effect
            	$html .= '>';

            }else{
            	
            	$html .= ' onmouseover="this.style.backgroundColor = \'#D7EAFF\'" onmouseout="this.style.backgroundColor = \'white\'" >';
            }
            
			$i = 0;
			
			if($this->simple_render){
				/**
				  *
				  *	Just print the damn rows
				  **/				
				foreach($this->rows[$a] as $column){

					$html .=  "<td align='left' >" . $column . "</td>";
				}

			}else{
				/**
				  *
				  *	Render based on the header
				  **/
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
							
							$html .=  "<td align='left' >" . call_user_func( $found[$key] , $row[ $key ], $row ) . "</td>";							

						}else{
							$html .=  "<td align='left'  >" . $row[ $key ] . "</td>";
						}
						

					}//if array key exists

				}//foreach

			}//simple_render

			
			
			$html .='</tr>';
		}
		
		$html .= "<tbody></table>";
		

		return $html;


	}
}





class SimpleTableComponent extends TableComponent{
	
	function __construct(){
		parent::__construct();
		$this->simple_render = true;
	}


	function addRow( $var_args ){
		$row = array();
		
		$n_args =  func_num_args();

		for ($ai=0; $ai < $n_args; $ai++)
		{ 
			array_push( $row , func_get_arg( $ai ) );	
		}

		parent::addRow( $row );
	}


	function setRows( $rows ){
		$this->rows = $rows;
	}	

}





