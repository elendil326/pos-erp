<?php



 class GerenciaTabPage extends GerenciaComponentPage{
 	

 	private $tabs;
 	private $tab_index;
 	

 	public function __construct( $title = "Gerencia" ){
 		parent::__construct( $title );
 		$this->page_title = $title;
 		$this->tabs = array();
 		$this->tab_index = -1;
 	}

 	public function nextTab( $title, $icon = null ){
 		$this->tabs[ ++$this->tab_index ] = array( "title" => $title, "icon" => $icon, "components" => array( ) );
 		
 	}

 	public function addComponent( $cmp ){
 		array_push( $this->tabs[$this->tab_index]["components"], $cmp );
 	}

 	public function render(){
 		/**
 		 *
 		 * Create tab header
 		 *
 		 **/
 		$h = "";
 		for ($ti=0; $ti < sizeof($this->tabs); $ti++) { 
			$h .= "<a href='#". $this->tabs[$ti]["title"] ."'>" . $this->tabs[$ti]["title"] . "</a>";
			if($ti < sizeof($this->tabs) - 1) $h .= " | ";
 		}
 		
 		 	
 		
 		
 		parent::addComponent("<h1>".$this->page_title . "</h1>");
 		parent::addComponent($h);


 		for ($ti=0; $ti < sizeof($this->tabs); $ti++) { 
 			parent::addComponent("<div id='" . $this->tabs[$ti]["title"] . "'>");
			parent::addComponent('<H3>'. $this->tabs[$ti]["title"] .'</H3>');	
 			for ($ti_cmps=0; $ti_cmps < sizeof( $this->tabs[$ti]["components"] ); $ti_cmps++) { 
 				

 				parent::addComponent($this->tabs[$ti]["components"][ $ti_cmps ]);	
 			}
 			

 			parent::addComponent("</div>");
 		}


 		parent::render();		
 	}

 }