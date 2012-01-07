<?php

class ClienteSelectorComponent implements GuiComponent{
	
	
	
	private $_js_callback;
	
	
	
	
	public function addJsCallback($_js_callback){
		$this->_js_callback = $_js_callback;
	}
	
	
	//
	// 
	// 
	public function renderCmp(){
		
	}
	
}