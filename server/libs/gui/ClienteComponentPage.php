<?php


class ClienteComponentPage extends PosComponentPage{

	private $main_menu_json;

	function __construct( $title = "Clientes"){
		
		$file = $_SERVER["PHP_SELF"];

		Logger::log($file);
		
		parent::__construct( $title );

		//check for user login status
		if(SesionController::isLoggedIn() === FALSE){
			
			$a = explode("/", $_SERVER["SCRIPT_NAME"]);
			
			die(header("Location: ../?next_url=" . $a[sizeof($a)-1]));
		}
		
		$this->createMainMenu();
		
		return;
		
	}

	private function createMainMenu()
	{
		$productos = '';
		if (ConfiguracionDAO::MostrarProductos())
		{
			$productos = '{ "title": "Productos" }';
		}
		$this->main_menu_json = '{
		    "main_menu": ['.
		    	$productos.'
		    ]
		}';

		return;
	}

	protected function _renderTopMenu()	{

		$s = SesionController::Actual();

		$u = UsuarioDAO::getByPK($s["id_usuario"]);


		?>


			<a class="l" href="./helper.php">Ayuda</a>
			<a class="l">(<?php echo $u->getNombre(); ?>)</a>
			<a class="l" href="./../?cs=1"> Salir</a>
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


				if(strtolower( $foo ) == strtolower( str_replace(" ", "_", $item->title) )){
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

}







