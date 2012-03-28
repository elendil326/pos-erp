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

 		if(sizeof($this->tabs) == 0)  throw new Exception ("there are no tabs in your tabpage");

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
 		for ($ti=0; $ti < sizeof($this->tabs); $ti++) { 

			parent::addComponent("<div class='gTab' style='display:none' id='tab_" . $this->tabs[$ti]["title"] . "'>");	

 			for ($ti_cmps=0; $ti_cmps < sizeof( $this->tabs[$ti]["components"] ); $ti_cmps++) { 
 				

 				parent::addComponent($this->tabs[$ti]["components"][ $ti_cmps ]);	
 			}
 			

 			parent::addComponent("</div>");
 		}

			$h = "<script>
	 			var currentTab = '';
				
				
				if(window.location.hash.length == 0){
					Ext.get('tab_". $this->tabs[0]["title"] ."').setStyle('display', 'block');
					Ext.get('atab_". $this->tabs[0]["title"] ."').toggleCls('selected');
					currentTab = '". $this->tabs[0]["title"] ."';
				}else{
					currentTab = window.location.hash.substr(1);
					Ext.get('tab_'+currentTab).setStyle('display', 'block');
					Ext.get('atab_'+currentTab).toggleCls('selected');						
				}

	 			if ( 'onhashchange' in window ) {
	 				console.log('`onhashchange` available....');

				    window.onhashchange = function() {

						if((currentTab.length > 0) && (Ext.get('tab_'+currentTab) != null)){
							Ext.get('tab_'+currentTab).setStyle('display', 'none');
							Ext.get('atab_'+currentTab).toggleCls('selected');						
						}

						currentTab = window.location.hash.substr(1);
						Ext.get('tab_'+currentTab).setStyle('display', 'block');
						Ext.get('atab_'+currentTab).toggleCls('selected');
				    }
				}</script>";

	 		parent::addComponent($h);

 		parent::render();		
 	}

 }