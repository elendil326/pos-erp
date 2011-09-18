<?php 

class JediComponentPage extends StdComponentPage{

	private $permisos_controller;

	function __construct()
	{

		//vamos a ver si tengo permiso para 
		//crear una pagina jedi
		$permisos_controller = new JediLoginController();

		if(!$permisos_controller->isLoggedIn())
			throw new PermsissionDeniedException();

		//voy a construir una pagina de Jedi
		parent::__construct();
		
	}



	public function render()
	{
		
		$html = "";

		foreach( $this->components as $cmp ){
			$html .= $cmp->renderCmp();
		}

		parent::addContent( $html );

		parent::render();
	}



}