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

 		if($this->tab_index == -1){
 			throw new Exception ("Primero debes hacer un `nextTab`");
 		}

 		if(!isset($this->tabs[$this->tab_index])){
 			$this->tabs[$this->tab_index] = array();
 		}
 		
 		array_push($this->tabs[$this->tab_index]["components"], $cmp);
 	}

 	public function render(){
 		/**
 		 *
 		 * Create tab header
 		 *
 		 **/
 		$h = "<script>
 			var currentTab = null;
 			if ( 'onhashchange' in window ) {
 				console.log('`onhashchange` available....');
			    window.onhashchange = function() {
			        var token = window.location.hash.substr(1);
			        currentTab = token;
			        Ext.get(\"tab_\"+token).show();
			    }
			}</script>
			<table class=\"tabs\" style=\"width:100%\"><tr>";
 		
 		for ($ti=0; $ti < sizeof($this->tabs); $ti++) { 
			$h .= "<td><a href='#". $this->tabs[$ti]["title"] ."'>" . $this->tabs[$ti]["title"] . "</a></td>";
 		}

 		$h .= "<td class=\"dummy\"></td></tr></table>";

 		/*
	 	<table class="tabs">
			<tr>
				<td>asdf</td>
				<td>asdf</td>
				<td>asdf</td>
				<td class="selected">asdf</td>
				<td>asdf</td>
				<td>asdf</td>
				<td>asdf</td>
				<td class="dummy"></td>
			</tr>
		</table>
 		 */
 		
 		
 		//parent::addComponent("<h1>".$this->page_title . "</h1>");
 		parent::addComponent($h);


 		for ($ti=0; $ti < sizeof($this->tabs); $ti++) { 

 			if($ti > 0){
				parent::addComponent("<div class='gTab' style='display:none' id='tab_" . $this->tabs[$ti]["title"] . "'>"); 				
 			}else{
 				parent::addComponent("<div class='gTab' id='tab_" . $this->tabs[$ti]["title"] . "'>");	
 			}

 			

			//parent::addComponent('<H3>'. $this->tabs[$ti]["title"] .'</H3>');	

 			for ($ti_cmps=0; $ti_cmps < sizeof( $this->tabs[$ti]["components"] ); $ti_cmps++) { 
 				

 				parent::addComponent($this->tabs[$ti]["components"][ $ti_cmps ]);	
 			}
 			

 			parent::addComponent("</div>");
 		}


 		parent::render();		
 	}

 }