<?php


class ClienteComponentPage extends PosComponentPage{

	private $main_menu_json;

	function __construct( $title = "Gerencia"){
		
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

	private function createMainMenu()	{
		
		$this->main_menu_json = '{
		    "main_menu": [

		        {
		            "title": "Cargos y abonos",
		            "url": "cargos_y_abonos.php"
		        },
		        {
		            "title": "Clientes",
		            "url": "clientes.php",
		            "children": [
		                {
		                    "title": "Clasificacion de clientes",
		                    "url": "clientes.lista.clasificacion.php"
		                },
		                {
		                    "title": "Nueva clasificacion",
		                    "url": "clientes.nueva.clasificacion.php"
		                },
		                {
		                    "title": "Lista de clientes",
		                    "url": "clientes.lista.php"
		                },
		                {
		                    "title": "<b>Nuevo cliente</b>",
		                    "url": "clientes.nuevo.php"
		                }
		            ]
		        },
		        {
		            "title": "Compras",
		            "url": "compras.php",
		            "children": [
		                {
		                    "title": "Lista",
		                    "url": "compras.lista.php"
		                },
		                {
		                    "title": "Nueva",
		                    "url": "compras.nueva.php"
		                }
		            ]
		        },
		        {
		            "title": "Consignaciones",
		            "url": "consignaciones.php",
		            "children": [
		                {
		                    "title": "Desactivar consignatario",
		                    "url": "consignaciones.desactivar.consignatario.php"
		                },
		                {
		                    "title": "Nuevo consignatario",
		                    "url": "consignaciones.nuevo.consignatario.php"
		                },
		                {
		                    "title": "Editar",
		                    "url": "consignaciones.editar.consignatario.php"
		                },
		                {
		                    "title": "Abonar a inspeccion",
		                    "url": "consignaciones.abonar.inspeccion.php"
		                },
		                {
		                    "title": "Cambiar fecha de inspeccion",
		                    "url": "consignaciones.cambiar_fecha.inspeccion.php"
		                },
		                {
		                    "title": "Cancelar inspeccion",
		                    "url": "consignaciones.cancelar.inspeccion.php"
		                },
		                {
		                    "title": "Nueva inspeccion",
		                    "url": "consignaciones.nueva.inspeccion.php"
		                },
		                {
		                    "title": "Registrar inspeccion",
		                    "url": "consignaciones.registrar.inspeccion.php"
		                },
		                {
		                    "title": "Lista",
		                    "url": "consignaciones.lista.php"
		                },
		                {
		                    "title": "Nueva",
		                    "url": "consignaciones.nueva.php"
		                },
		                {
		                    "title": "Terminar",
		                    "url": "consignaciones.terminar.php"
		                }
		            ]
		        },
		        {
		            "title": "Efectivo",
		            "url": "efectivo.php",
		            "children": [
		                {
		                    "title": "Lista billete",
		                    "url": "efectivo.lista.billete.php"
		                },
		                {
		                    "title": "Nuevo billete",
		                    "url": "efectivo.nuevo.billete.php"
		                },
		                {
		                    "title": "Lista moneda",
		                    "url": "efectivo.lista.moneda.php"
		                },
		                {
		                    "title": "Nueva moneda",
		                    "url": "efectivo.nueva.moneda.php"
		                }
		            ]
		        },
		        {
		            "title": "Empresas",
		            "url": "empresas.lista.php",
		            "children": [
		                {
		                    "title": "Lista de empresas",
		                    "url": "empresas.lista.php"
		                },
		                {
		                    "title": "Nueva empresa",
		                    "url": "empresas.nuevo.php"
		                }
		            ]
		        },
		        {
		            "title": "Impuestos",
		            "url": "impuestos.php",
		            "children": [
		                {
		                    "title": "Lista impuestos",
		                    "url": "impuestos.lista.impuesto.php"
		                },
		                {
		                    "title": "Nuevo impuesto",
		                    "url": "impuestos.nuevo.impuesto.php"
		                },
		                {
		                    "title": "Lista retenciones",
		                    "url": "impuestos.lista.retencion.php"
		                },
		                {
		                    "title": "Nueva retencion",
		                    "url": "impuestos.nueva.retencion.php"
		                }
		            ]
		        },
		        {
		            "title": "Inventario",
		            "url": "inventario.existencias.php",
		            "children": [
		                {
		                    "title": "Existencias",
		                    "url": "inventario.existencias.php"
		                },
		                {
		                    "title": "Procesar producto",
		                    "url": "inventario.procesar.producto.php"
		                },
		                {
		                    "title": "Terminar cargamento de compra",
		                    "url": "inventario.terminar.cargamento.compra.php"
		                }
		            ]
		        },
		        {
		            "title": "Productos",
		            "url": "productos.php",
		            "children": [
		                {
		                    "title": "Lista categoria",
		                    "url": "productos.lista.categoria.php"
		                },
		                {
		                    "title": "Nueva categoria",
		                    "url": "productos.nueva.categoria.php"
		                },
		                {
		                    "title": "Lista de productos",
		                    "url": "productos.lista.php"
		                },
		                {
		                    "title": "Nuevo producto",
		                    "url": "productos.nuevo.php"
		                },		             
		                {
		                    "title": "Lista Unidades de Medida",
		                    "url": "productos.lista.unidad_medida.php"
		                },		          
		                {
		                    "title": "Nueva Unidad Medida",
		                    "url": "productos.nueva.unidad_medida.php"
		                },
						{
		                    "title": "Lista Categorias Unidades de Medida",
		                    "url": "productos.lista.categoria_unidad_medida.php"
		                },	
		                {
		                    "title": "Nueva Categoria Unidad Medida",
		                    "url": "productos.nueva.categoria_unidad_medida.php"
		                }
		            ]
		        },
		        {
		            "title": "Personal",
		            "url": "personal.lista.usuario.php",
		            "children": [
		                {
		                    "title": "Lista de roles",
		                    "url": "personal.lista.rol.php"
		                },
		                {
		                    "title": "Nuevo rol",
		                    "url": "personal.nuevo.rol.php"
		                },
		                {
		                    "title": "Lista de usuarios",
		                    "url": "personal.lista.usuario.php"
		                },
		                {
		                    "title": "Nuevo usuario",
		                    "url": "personal.nuevo.usuario.php"
		                }
		            ]
		        },
		    {
	            "title": "Proveedores",
	            "url": "proveedores.lista.php",
	            "children": [
			        {
			                "title" : "Proveedores",
			                "url"   : "proveedores.lista.php"
			        },
			        {
			                "title" : "Nuevo proveedor",
			                "url"   : "proveedores.nuevo.php"
			        },
	                {
	                        "title" : "Clasificacion",
	                        "url"   : "proveedores.lista.clasificacion.php"
	                },
	                {
	                        "title" : "Nueva clasificacion",
	                        "url"   : "proveedores.nueva.clasificacion.php"
	                }
	            ]
	        },    {
		            "title": "Reportes",
		            "url": "reportes.php",
		            "children": [
				        {
				                "title" : "Presentaciones",
				                "url"   : "reportes.presentaciones.php"
				        },
		                {
		                        "title" : "Presentacion mensual",
		                        "url"   : "reportes.presentacion.php"
		                }
		            ]
		        },
		        {
		            "title": "Tarifas",
		            "url": "tarifas.lista.php",
		            "children": [
		                {
		                    "title": "Listar tarifas",
		                    "url": "tarifas.lista.php"
		                },
                                {
		                    "title": "Nueva tarifa",
		                    "url": "tarifas.nueva.php"
		                }
		            ]
		        },
		        {
		            "title": "Servicios",
		            "url": "servicios.php",
		            "children": [
		                {
		                    "title": "Nueva orden",
		                    "url": "servicios.nueva.orden.php"
		                },
		                {
		                    "title": "Nuevo servicio",
		                    "url": "servicios.nuevo.php"
		                }		
		            ]
		        },
		        {
		            "title": "Sucursales",
		            "url": "sucursales.lista.php",
		            "children": [
			            {
			                "title": "Lista de sucursales",
			                "url": "sucursales.lista.php"
			            },
			            {
			                "title": "Nueva sucursal",
			                "url": "sucursales.nueva.php"
			            },
		                {
		                    "title": "Cajas",
		                    "url": "sucursales.lista.caja.php"
		                },
		                {
		                    "title": "Nueva caja",
		                    "url": "sucursales.nueva.caja.php"
		                },
		                {
		                    "title": "Lista tipo de almacenes",
		                    "url": "sucursales.lista.tipo_almacen.php"
		                },
		                {
		                    "title": "Nuevo tipo de almacen",
		                    "url": "sucursales.nuevo.tipo_almacen.php"
		                }
		            ]
		        },
		        {
		            "title": "Ventas",
		            "url": "ventas.php",
		            "children": [
		                {
		                    "title": "Nueva",
		                    "url": "ventas.nueva.php"
		                }
		            ]
		        }
		    ]
		}';



		return;
		
		
	}

	protected function _renderTopMenu()	{

		$s = SesionController::Actual();

		$u = UsuarioDAO::getByPK($s["id_usuario"]);


		?>

			<a class="l" href="./c.php">Configuracion</a>
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







