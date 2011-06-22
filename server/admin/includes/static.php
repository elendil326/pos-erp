<?php 

define("DONT_USE_HTML", FALSE);
function moneyFormat( $val, $useHtml = true){
	
	if( $useHtml ==  DONT_USE_HTML){
		return "$ <b>" . number_format( (float)$val, 2 ) . "</b>";		
	}else{
		return "$&nbsp;<b>" . number_format( (float)$val, 2 ) . "</b>";		
	}

}

function floatFormat( $val ){
    return sprintf( "<b>%.2f</b>", $val);
}

function floatFormatAlert( $val ){
    
    $c1 = "red";
    $c2 = "green";
    
    return sprintf( "<b style = 'color:" . ($val < 0 ? $c1: $c2) . "'>%.2f</b>", $val);
}

function moneyFormatAlert( $val, $useHtml = true){
	
        $c1 = "red";
        $c2 = "green";
    
	if( $useHtml ==  DONT_USE_HTML){
		return "$ <b style = 'color:" . ($val < 0 ? $c1: $c2) . "'>" . number_format( (float)$val, 2 ) . "</b>";		
	}else{
		return "$&nbsp;<b style = 'color:" . ($val < 0 ? $c1: $c2) . "'>" . number_format( (float)$val, 2 ) . "</b>";		
	}


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
		$html .= '<tr align = "left">';
		
		foreach ( $this->header  as $key => $value){
			$html .= '<th>' . $value . '</th>';			
		}
		

		$html .= '</tr>';
		
		//cicle trough rows
		for( $a = 0; $a < sizeof($this->rows) ; $a++ ){

			/*if($a == 50){
				$html .= "<tr style='background-color:#3F8CE9; color:white; text-align:center;'><td colspan=" .sizeof($this->header). ">Mostrar siguientes 50</td></tr>";
				break;
			}*/

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
						
						$html .=  "<td align='left' style='background-color:".$bgc.";'>" . call_user_func( $found[$key] , $row[ $key ], $row ) . "</td>";							

					}else{
						$html .=  "<td align='left' style='background-color:".$bgc.";'>" . $row[ $key ] . "</td>";
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






class Reporte{
	
	private $timelines;
	private $timelines_draw_acumulable;
	private $titles;
	private $indexes;
	private $missingDays;
	private $fechas;
	
	private $dateStart;
	private $acumulado;
	private $random_id;
	private $yFormater;
	
	function __construct(){
		$this->timelines = array();
		$this->timelines_draw_acumulable = array();		
		$this->titles = array();
		$this->indexes = array();
		$this->missingDays = array();
		$this->fechas = array();		
		$this->dateStart = null;	
		$this->acumulado = array();
		$this->random_id = rand();
		$this->yFormater = "";
	}
	
	
	
	public function agregarMuestra( $title, $data, $acumulable = false ){
		array_push( $this->indexes, 0 );
		array_push( $this->missingDays, 0 );
		array_push( $this->titles, $title );
		array_push( $this->timelines, $data );
		array_push( $this->timelines_draw_acumulable, $acumulable );		
	}
	
	public function setEscalaEnY($e){
		$this->yFormater = $e;
	}
	
	public function fechaDeInicio( $time ){
		$this->dateStart = $time;
	}
	
	public function graficar( $title ){
		$this->fillEmptySpaces();
		$this->writeJavascriptAndHTML($title);		
	}
	
	private function writeJavascriptAndHTML($title){
		$id = str_replace(" ", "_", $title);
		?>
		<h2><?php echo $title; ?></h2>
		<div id="<?php echo $id; ?>"><div id="fechas"></div></div>
		<script type="text/javascript" charset="utf-8">

		    <?php
			$GRAFICAS_ACUMULATIVAS = false;
			
			for ($s=0; $s < sizeof($this->timelines); $s++) { 
				$GRAFICAS_ACUMULATIVAS = $this->timelines_draw_acumulable[$s];
				$acc = 0;		
				echo "var g". $this->random_id . $s ." = [";

				for($i = 0; $i < sizeof($this->timelines[$s]); $i++ ){
					if($GRAFICAS_ACUMULATIVAS){
						$acc += $this->timelines[$s][$i]["value"] ;
						echo  "[" . $i . "," . $acc . "]";				
					}else{
						echo  "[" . $i . "," . $this->timelines[$s][$i]["value"] . "]";
					}

					if($i < sizeof($this->timelines[$s]) - 1){
							echo ",";		
					}
				}
				echo "];\n\n";
			}




			echo "var todos".$this->random_id . " = [";

			for($i = 0; $i < sizeof($this->acumulado); $i++ ){
				echo  "[" . $i . "," . $this->acumulado[$i] . "]";

				if($i < sizeof($this->acumulado) - 1){
						echo ",";		
				}
			}
			echo "];\n\n";



			echo "var fechas".$this->random_id . " = [";
			for($i = 0; $i < sizeof($this->fechas); $i++ ){
				echo  "{ fecha : '" . $this->fechas[$i] . "'}";
				if($i < sizeof($this->fechas) - 1){
					echo ",";
				}
			}
			echo "];\n";

		    ?>

			Event.observe(document, 'dom:loaded', function() {
				
			    var g = new HumbleFinance(  );

				g.setXFormater(
						function(val){
							if(val ==0)return "";					
							return meses(fechas<?php echo $this->random_id; ?>[val].fecha.split("-")[1]) + " "  + fechas<?php echo $this->random_id; ?>[val].fecha.split("-")[2]; 
						}
					);

				g.setYFormater(
						function(val){
							if(val ==0)return "";
							<?php
								if($this->yFormater == "pesos"){
									?>
									if(val < 0){
										return "<span style='color:red'>" + cf(val) + " <?php echo $this->yFormater; ?></span>"; 
									}else{
										return cf(val) + " <?php echo $this->yFormater; ?>"; 
									}
									<?php
								}else{
									?>	return  val  + " <?php echo $this->yFormater; ?>";  <?php
								}
							?>

						}
					);


				g.setTracker(
					function (obj){
							obj.x = parseInt( obj.x );

							<?php
								if($this->yFormater == "pesos"){
									?>
									return meses(fechas<?php echo $this->random_id; ?>[obj.x].fecha.split("-")[1]) 
										+ " "  
										+ fechas<?php echo $this->random_id; ?>[obj.x].fecha.split("-")[2]
										+ ", <b>"
										+ cf(obj.y )
										+ "</b> <?php echo $this->yFormater; ?>";
									<?php									
								}else{
									?>
									return meses(fechas<?php echo $this->random_id; ?>[obj.x].fecha.split("-")[1]) 
										+ " "  
										+ fechas<?php echo $this->random_id; ?>[obj.x].fecha.split("-")[2]
										+ ", <b>"
										+ obj.y 
										+ "</b> <?php echo $this->yFormater; ?>";
									<?php
								}
							?>



						}
					);
				<?php
					for ($s=0; $s < sizeof($this->timelines); $s++) { 
						echo "g.addGraph( g".$this->random_id . $s." , \"" .$this->titles[$s]. "\" );";
					}

				?>
			    g.addSummaryGraph( todos<?php echo $this->random_id; ?> );
			    g.render("<?php echo $id; ?>");
			});


		</script>
		<?php
	}
	
	private function fillEmptySpaces(){
		//esa el la fecha que comenzare a iterar
		$dayIndex =  date("Y-m-d", $this->dateStart  );

		//the day the loop will end
		$tomorrow = date("Y-m-d", strtotime("+1 day",  time()));
		
		
		$acumulado_index = 0;
		$sub_total = 0;
		
		while( $tomorrow != $dayIndex ){

			for ($mainIndex=0; $mainIndex < sizeof($this->timelines); $mainIndex++) { 

				//im out of days !
				if( sizeof($this->timelines[ $mainIndex ]) == $this->indexes[ $mainIndex ] ){
					array_push($this->timelines[ $mainIndex ], array( "fecha" => $dayIndex, "value" => 0 ));
				}

				if( $this->timelines[ $mainIndex ][ $this->indexes[ $mainIndex ] ]["fecha"] != $dayIndex){
					$this->missingDays[ $mainIndex ]++;
				}else{
					$sub_total += $this->timelines[ $mainIndex ][ $this->indexes[ $mainIndex ] ]["value"];
					for($a = 0 ; $a < $this->missingDays[ $mainIndex ]; $a++){
						array_splice($this->timelines[ $mainIndex ], 
										$this->indexes[ $mainIndex ], 
										0, 
										array(array( "fecha" => "missing_day" , "value" => 0)));
					}
				 	$this->indexes[ $mainIndex ] += $this->missingDays[ $mainIndex ]+1;
					$this->missingDays[ $mainIndex ] = 0;
				}

			}

			$this->acumulado[$acumulado_index++] = $sub_total;
			$sub_total = 0;
			array_push($this->fechas, $dayIndex);
			$dayIndex = date("Y-m-d", strtotime("+1 day", strtotime($dayIndex)));
		}
	}
	
}







class ExtTabla {
	
	
	
	
	
	
}


