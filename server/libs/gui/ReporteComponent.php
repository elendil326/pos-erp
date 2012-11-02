<?php

class ReporteComponent implements GuiComponent{
	
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
	
	private function logData(){
		$foo = "";
		for ($i=0; $i < sizeof($this->timelines[0]); $i++) { 
			$foo .= $this->timelines[0][$i]["fecha"] . " ( ". $this->timelines[0][$i]["value"] ." ) | ";
		}
		//Logger::log($foo);
		
	}
	
	public function renderCmp(  ){
		$title = "ASDF";
		$this->logData();
		$this->fillEmptySpaces();
		$this->writeJavascriptAndHTML($title);		
	}
	
	private function sortByDates(){
		
	}
	private function buscar($timelineNumber, $day){
		$out = array();
		for ($st=0; $st < sizeof( $this->timelines[$timelineNumber] ); $st++) { 
			//Logger::log($day . "|" . date("Y-m-d", strtotime($this->timelines[$timelineNumber][$st]["fecha"])));
			if( date("Y-m-d", strtotime($this->timelines[$timelineNumber][$st]["fecha"]))  == $day){
				array_push($out, $this->timelines[$timelineNumber][$st]);
			}
		}
		return $out;
	}
	private function writeJavascriptAndHTML( $title ){

		$id = str_replace(" ", "_", $title);
		
		?>
		<script type="text/javascript" charset="utf-8">
			function meses(m){m=parseFloat(m);switch(m){case 1:return"enero";case 2:return"febrero";case 3:return"marzo";case 4:return"abril";case 5:return"mayo";case 6:return"junio";case 7:return"julio";case 8:return"agosto";case 9:return"septiembre";case 10:return"octubre";case 11:return"noviembre";case 12:return"diciembre";}}
		</script>
		<script type="text/javascript" charset="utf-8" src="http://api.caffeina.mx/prototype/prototype.js"></script>
		<script src="../../../frameworks/humblefinance/flotr/flotr.js" type="text/javascript" charset="utf-8"></script>
		<script src="../../../frameworks/humblefinance/flotr/excanvas.js" type="text/javascript" charset="utf-8"></script>
		<script src="../../../frameworks/humblefinance/flotr/canvastext.js" type="text/javascript" charset="utf-8"></script>
		<script src="../../../frameworks/humblefinance/flotr/canvas2image.js" type="text/javascript" charset="utf-8"></script>
		<script src="../../../frameworks/humblefinance/flotr/base64.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript" charset="utf-8" src="../../../frameworks/humblefinance/humble/HumbleFinance.js"></script>
		<link rel="stylesheet" href="../../../frameworks/humblefinance/humble/finance.css" type="text/css" media="screen" title="no title" charset="utf-8">
		
		<h2><?php echo $title; ?></h2>
		
		<div style ="width: 95%" id="<?php echo $id; ?>"><div id="fechas"></div></div>
		
		<script type="text/javascript" charset="utf-8">

		    <?php
			$GRAFICAS_ACUMULATIVAS = false;
			Logger::log("rendering...");			




			echo "var fechas".$this->random_id . " = [";
			for($i = 0; $i < sizeof($this->fechas); $i++ ){
				echo  "{ fecha : '" . $this->fechas[$i] . " '}";
				if($i < sizeof($this->fechas) - 1){
					echo ",";
				}
			}
			echo "];\n";
			
			


			$this->acumulado = array();
			
			for ($s=0; $s < sizeof($this->timelines); $s++) { 

				$GRAFICAS_ACUMULATIVAS = $this->timelines_draw_acumulable[$s];
				
				$acc = 0;
						
				echo "var g". $this->random_id . $s ." = [";

				for($i = 0; $i < sizeof($this->fechas); $i++ ){
					
					$today = $this->buscar($s, $this->fechas[$i]);
					
					$tot = 0;
					
					if(sizeof($today) > 0){
						foreach ($today as $t ) {
							$tot += $t["value"];
						}
					}

					$acc += $tot;					
					$this->acumulado[$i] = $acc;
					
					if($GRAFICAS_ACUMULATIVAS){
						echo  "[" . $i . "," . $acc . "]";

					}else{
						echo  "[" . $i . "," . $tot . "]";

					}

					//ok estoy en el dia que comenzo, vamos a buscar ese valor 
					//en este timeline

					


					if($i < sizeof($this->fechas) - 1){
							echo ",";		
					}
				}
				echo "];\n\n";
				
				
				echo "var todos".$this->random_id . " = [";

				for($i = 0; $i < sizeof($this->acumulado); $i++ ){
					echo  "[" . $i . "," . $this->acumulado[$i] . "]";

					if($i < sizeof($this->acumulado) - 1){
							echo ",";		
					}
				}
				echo "];\n\n";
				
				
			}

			/*
			for ($s=0; $s < sizeof($this->timelines); $s++) { 
				Logger::log("timeline $s:"  );
				$GRAFICAS_ACUMULATIVAS = $this->timelines_draw_acumulable[$s];
				$acc = 0;		
				echo "var g". $this->random_id . $s ." = [";

				for($i = 0; $i < sizeof($this->timelines[$s]); $i++ ){
					Logger::log("data $i:");
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
			}*/
		    ?>

			Event.observe(document, 'dom:loaded', function() {
				
				var g = new HumbleFinance(  );

				g.setXFormater(
					function(val){
					if(val == 0)return "";
					val = parseInt(val);

					return meses(fechas<?php echo $this->random_id; ?>[val].fecha.split("-")[1]) + " "  + fechas<?php echo $this->random_id; ?>[val].fecha.split("-")[2]; 
					}
				);

				g.setYFormater(
					function(val){
						if(val ==0) return "";
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
						?>ß

					}
				);


				g.setTracker(
					function (obj){
						obj.x = parseInt( obj.x );

						<?php if($this->yFormater == "pesos"){ ?>
							return meses(fechas<?php echo $this->random_id; ?>[obj.x].fecha.split("-")[1]) 
							+ " "  
							+ fechas<?php echo $this->random_id; ?>[obj.x].fecha.split("-")[2]
							+ ", <b>"
							+ cf(obj.y )
							+ "</b> <?php echo $this->yFormater; ?>";
						<?php }else{ ?>
							return meses(fechas<?php echo $this->random_id; ?>[obj.x].fecha.split("-")[1]) 
							+ " "  
							+ fechas<?php echo $this->random_id; ?>[obj.x].fecha.split("-")[2]
							+ ", <b>"
							+ obj.y 
							+ "</b> <?php echo $this->yFormater; ?>";
						<?php }	?>
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
		

		$this->logData();
		
		//the day the loop will end, mañana
		$tomorrow = date("Y-m-d", strtotime("+1 day",  time()));
		
		
		$acumulado_index = 0;
		$sub_total = 0;
		
		while( $tomorrow != $dayIndex ){

			
			for ($mainIndex=0; $mainIndex < sizeof($this->timelines); $mainIndex++) { 


				$this->logData();
				
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
						

						$this->logData();
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
