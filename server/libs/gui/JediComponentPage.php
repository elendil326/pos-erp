<?php

class JediComponentPage extends PosComponentPage{

	private $main_menu_json;

	function __construct( $title = "Jedi" ){

		parent::__construct( $title );


		$this->createMainMenu();
		
		return;
		
	}

	private function createMainMenu()	{
		$this->main_menu_json = '
		{
		    "main_menu": [
		        {
		            "title": "Instancias",
		            "url": "instancias.lista.php",
		            "children": [
		                {
		                    "title": "Ver instancias",
		                    "url": "instancias.lista.php"
		                },
		                {
		                    "title": "Nueva instancia",
		                    "url": "instancias.nueva.php"
		                },
		                 {
		                    "title": "Bases de Datos",
		                    "url": "instancias.bd.php"
		                }
		            ]
		        },
				{
					
					"title" : "Logs",
					"url"	: "logs.php",
					"children": [
		                {
		                    "title": "Acceso",
		                    "url": "logs.php"
		                },
		                {
		                    "title": "Error",
		                    "url": "logs.error.php"
		                }
		            ]
				},
				{
					
					"title" : "DemoRequests",
					"url"	: "requests.php"
		            
				}
		        
		    ]
		}';
		
	}

	protected function _renderTopMenu()	{
		?>
			<a class="l" href="./config.php">Configuracion</a>
			<a class="l" href="./../?cs=1">Salir</a>
		<?php
	}
	
	protected function _renderMenu()	{
			################ Main Menu ################
			echo "<ul>";
			
			$mm = json_decode( $this->main_menu_json );

			foreach ( $mm->main_menu as $item )
			{

				echo "<li ";

				if(isset( $item->children ))
				{
					echo "class='withsubsections'";
				}

				echo "><a href='". $item->url  ."'><div class='navSectionTitle'>" . $item->title . "</div></a>";

				$foo = explode( "/" ,  $_SERVER["SCRIPT_FILENAME"] );
				$foo = array_pop( $foo );

				$foo = explode( "." , $foo );
				$foo = $foo[0];


				if(strtolower( $foo ) == strtolower( $item->title )){
					if(isset( $item->children ) ){

						foreach( $item->children as $subitem )
						{
							echo '<ul class="subsections">';
							echo "<li>";
							echo '<a href="'. $subitem->url .'">' . $subitem->title . '</a>';
							echo "</li>";
							echo "</ul>";
						}

					}										
				}


				echo "</li>";

			}
			return 1;
			################ Main Menu ################
	}

	private function dieWithLogin($message = null){
		$login_cmp = new LoginComponent();

		if( $message != null )
		{
			self::addComponent(new MessageComponent($message));				
		}

		self::addComponent($login_cmp);
		parent::render();
		exit();
	}

}