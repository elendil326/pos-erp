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
			/*data.addColumn('string', 'ToolTip'); */
			
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


								echo "[ { v: '".$e->getRazonSocial()."', f: '<div>Empresa</div>".$e->getRazonSocial()."' } , '' ],\n ";
								
								//buscar sucursales de compui
								$id_sucursales = SucursalEmpresaDAO::search( new SucursalEmpresa( array( "id_empresa" => $e->getIdEmpresa() ) ) );

								//iterear sucursales
								foreach($id_sucursales as $id_s){

									$s = SucursalDAO::getByPK( $id_s->getIdSucursal() );

									echo "/* sucursal, empresa */[  { v: '".$s->getRazonSocial()."', f: '<div>Sucursal</div>".$s->getRazonSocial()."' } , '".$e->getRazonSocial()."' ], \n";
									//iterar almacenes
									$almacenes = AlmacenDAO::search( new Almacen( array ( "id_almacen" )) );

									foreach ($almacenes as $a) {

										echo "/* almacen, sucursal */ [ { v: '".$a->getNombre()."', f: '<div>Almacen</div>".$a->getNombre()."' }, '".$s->getRazonSocial()."' ], \n";

										//lotes de esa sucursal
										$lotes = LoteDAO::search( new Lote( array ( "id_almacen" => $a->getIdAlmacen() ) ) );
										
										foreach ($lotes as $l) {
											
											echo "/* lote, almacen */[ { v: '".$l->getFolio()."', f: '<div>Lote</div>".$l->getFolio()."' }, '". $a->getNombre() ."' ], \n";
											
										}
										
									}//for-each sucursales

								}//for-each id_sucursales

							}//for-each empresas
							
					?>
	        ]);
	        var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
	
	        chart.draw(data, {allowHtml:true});
	
			google.visualization.events.addListener(chart, 'select', function(a,b,c) {
				console.log(chart.getSelection()[0].row,a,b,c);
			  });
	      }
	
	
	    </script>

	    <div id='chart_div'></div>
		<?php


		//$page->add
		$nuevoLote = new DAOFormComponent(new Lote());
		$nuevoLote->addApiCall("api/almacen/lote/nuevo", "POST");

		$page->addComponent($nuevoLote);
		
		$page->render();
