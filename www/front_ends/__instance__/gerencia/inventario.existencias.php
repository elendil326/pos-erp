<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
		
		$page->addComponent( new TitleComponent( "Mapa de existencias" ) );
		
		$page->partialRender();
	

		?>
		
		<script type='text/javascript' src='https://www.google.com/jsapi'></script>
	    <script type='text/javascript'>
	      google.load('visualization', '1', {packages:['orgchart']});
	      google.setOnLoadCallback(drawChart);
	      function drawChart() {
	        var data = new google.visualization.DataTable();
	        data.addColumn('string', 'Name');
	        data.addColumn('string', 'Manager');
/*	        data.addColumn('string', 'ToolTip'); */
	        data.addRows([
				/*
	          [{v:'Mike', f:'Mike<div style="color:red; font-style:italic">President</div>'}, '', 'The President'],
	          [{v:'Jim', f:'Jim<div style="color:red; font-style:italic">Vice President</div>'}, 'Mike', 'VP'],
	          ['Alice', 'Mike', ''],
	          ['Bob', 'Jim', 'Bob Sponge'],
	          ['Carol', 'Bob', ''],
			  ['Carol2', 'Bob', '']	
			  */
			
				<?php
							$empresas 	= EmpresaDAO::getAll();


							//iterar empresas
							foreach($empresas as $e){


								echo "[ '".$e->getRazonSocial()."', '' ], ";



								//buscar sucursales de compui

								$id_sucursales = SucursalEmpresaDAO::search( new SucursalEmpresa( array( "id_empresa" => $e->getIdEmpresa() ) ) );

								//iterear sucursales
								foreach($id_sucursales as $id_s){

									$s = SucursalDAO::getByPK( $id_s->getIdSucursal() );

									echo "[ '".$s->getRazonSocial()."', '".$e->getRazonSocial()."' ], ";
									//iterar almacenes
									$almacenes = AlmacenDAO::search( new Almacen( array ( "id_almacen" )) );

									foreach ($almacenes as $a) {

										echo "[ '".$a->getNombre()."', '".$s->getRazonSocial()."' ], ";


									}//for-each sucursales

								}//for-each id_sucursales

								/*
								$almacenes 	= AlmacenDAO::GetAll();
								$lotes		= LoteDAO::GetAll();			
								*/
		
							}//for-each empresas
							
					?>
	        ]);
	        var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
	        chart.draw(data, {allowHtml:true});
	      }
	    </script>
	  

	  
	    <div id='chart_div'></div>
		
		
		
		
		<?php


		
		$inventario = InventarioController::Existencias();
		$inventario = $inventario["resultados"];
		
                
		$page->render();
