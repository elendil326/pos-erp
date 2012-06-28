<?php



 class GerenciaTabPage extends GerenciaComponentPage{
 	

 	private $tabs;
 	private $tab_index;
 	private $before_tabbing_cmps;
 	

 	public function __construct( $title = "Gerencia" ){
 		parent::__construct( $title );
 		$this->page_title = $title;
 		$this->tabs = array();
 		$this->tab_index = -1;
 		$this->before_tabbing_cmps = array();

 	}

 	public function nextTab( $title, $icon = null ){

 		$this->tabs[ ++$this->tab_index ] = array( "title" => $title, "icon" => $icon, "components" => array( ) );
 		
 	}

 	public function addComponent( $cmp ){

 		if($this->tab_index == -1){
 			array_push( $this->before_tabbing_cmps , $cmp );
 			return;
 		}

 		if(!isset($this->tabs[$this->tab_index])){
 			$this->tabs[$this->tab_index] = array();
 		}
 		
 		array_push($this->tabs[$this->tab_index]["components"], $cmp);
 	}

 	public function render(){

 		

 		for ($bfi=0; $bfi < sizeof($this->before_tabbing_cmps); $bfi++) { 
			parent::addComponent( $this->before_tabbing_cmps[$bfi] );
 		}

 		/**
 		 *
 		 * Create tab header
 		 *
 		 **/

 		if(sizeof($this->tabs) > 0){

	 		$h = "<table style='margin-top:10px' class=\"tabs\" ><tr>";

	 		for ($ti=0; $ti < sizeof($this->tabs); $ti++) { 
				$h .= "<td style='max-width:84px' id='atab_" . $this->tabs[$ti]["title"] . "' >
						<a href='#". $this->tabs[$ti]["title"] ."'>" . $this->tabs[$ti]["title"] . "</a>
					</td>";
	 		}

	 		$h .= "<td class=\"dummy\"></td></tr></table>";
				
	 		parent::addComponent($h);

 		/**
 		 *
 		 * Actual wrapped tabs
 		 *
 		 **/
 		$tabs_for_js = "";

 		for ($ti=0; $ti < sizeof($this->tabs); $ti++) { 

			parent::addComponent("<div class='gTab'  id='tab_" . $this->tabs[$ti]["title"] . "'>");	

			$tabs_for_js .= "'" . $this->tabs[$ti]["title"] . "',";

 			for ($ti_cmps=0; $ti_cmps < sizeof( $this->tabs[$ti]["components"] ); $ti_cmps++) { 
 				

 				parent::addComponent($this->tabs[$ti]["components"][ $ti_cmps ]);	
 			}
 			

 			parent::addComponent("</div>");
 		}



			$h = "<script>
				var TabPage = TabPage || {};
				TabPage.tabs = [$tabs_for_js];
				TabPage.tabsH = [];
				TabPage.currentTab = '';
				</script>";

	 		parent::addComponent($h);

 		}  //throw new Exception ("there are no tabs in your tabpage");




 		parent::render();		
 	}

 }