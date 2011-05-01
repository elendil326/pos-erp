<?php

require_once("controller/compras.controller.php");
require_once("controller/sucursales.controller.php");
require_once("controller/ventas.controller.php");
require_once("controller/personal.controller.php");
require_once("controller/efectivo.controller.php");
require_once("controller/inventario.controller.php");

require_once('model/pagos_venta.dao.php');
require_once('model/corte.dao.php');


$sucursal = SucursalDAO::getByPK( $_REQUEST['id'] );



?>
<style type="text/css" media="screen">
	#map_canvas { 
		height: 200px;
		width: 400px;
 	}
</style>

<script src="../frameworks/humblefinance/flotr/flotr.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/excanvas.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/canvastext.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/canvas2image.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/base64.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8" src="../frameworks/humblefinance/humble/HumbleFinance.js"></script>
<link rel="stylesheet" href="../frameworks/humblefinance/humble/finance.css" type="text/css" media="screen" title="no title" charset="utf-8">


<h2>Detalles</h2>
<table style="width:100%" border="0" cellspacing="1" cellpadding="1">
	<tr><td><b>Descripcion</b></td><td>		<?php echo $sucursal->getDescripcion(); ?></td><td valign="top" align="right" rowspan=9><div  id="map_canvas"></div></td></tr>
	<tr><td><b>Direccion</b></td><td>		<?php echo $sucursal->getCalle(); ?></td></tr>
	<tr><td><b>Apertura</b></td><td>		<?php echo toDate($sucursal->getFechaApertura()); ?></td></tr>
	<tr><td><b>Gerente</b></td><td>
        <?php 
            $gerente = UsuarioDAO::getByPK( $sucursal->getGerente() );
			if($gerente === null){
				echo "Esta sucursa no tiene gerente !";
			}else{
	            echo "<a href='gerentes.php?action=detalles&id=". $sucursal->getGerente() ."'>";
	            echo $gerente->getNombre();
	            echo "</a>";				
			}
        ?>
    </td></tr>
	<tr><td><b>ID</b></td><td>				<?php echo $sucursal->getIdSucursal(); ?></td></tr>
	<tr><td><b>Letras factura</b></td><td>	<?php echo $sucursal->getLetrasFactura(); ?></td></tr>
	<tr><td><b>RFC</b></td><td>				<?php echo $sucursal->getRfc(); ?></td></tr>	
	<tr><td><b>Telefono</b></td><td>		<?php echo $sucursal->getTelefono(); ?></td></tr>	

	<tr><td colspan=2><input type=button value="Editar detalles" onclick="editar()"></td> </tr>
</table>

<script type="text/javascript"> 

	jQuery("#MAIN_TITLE").html( "<?php echo $sucursal->getDescripcion(); ?>" )

    var drawMap = function ( result, status ) {
        if(result.length == 0){
            document.getElementById("map_canvas").innerHTML = "<div align='center'> Imposible localizar esta direccion. </div>"; 
            return;
        }

        var myLatlng = result[0].geometry.location;

        var myOptions = {
            zoom: 18,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.HYBRID,
            navigationControl : true
        };

        try{
            var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
        }catch(e){
            document.getElementById("map_canvas").innerHTML = "<div align='center'> Imposible crear el mapa.</div>";
            return;
        }


        m = new google.maps.Marker({
                map: map,
                position: myLatlng
            });
    }


    function startMap(){

	    GeocoderRequest = {
		    address : "<?php echo $sucursal->getCalle() . " " . $sucursal->getNumeroExterior() . ", ". $sucursal->getColonia() . ", " .  $sucursal->getMunicipio(); ?>, Mexico"
	    };
	    try{

		    gc = new google.maps.Geocoder( );

		    gc.geocode(GeocoderRequest,  drawMap);
		
	    }catch(e){
		    console.log(e)
	    }


    }


	function mostrarDetalles( a ){
		window.location = "clientes.php?action=detalles&id=" + a;
	}
</script>

<h2>Mapa de ventas</h2>
<div id="finance">
    <div id="fechas">
    </div>
</div>
<br>

<!--
<h2>Mapa de rendimiento</h2>
<div id="rendimiento">
    <div id="fechas_rendimiento">
    </div>
</div>
-->

<script type="text/javascript" charset="utf-8">
    function mostrarDetallesVenta (vid){ window.location = "ventas.php?action=detalles&id=" + vid; }
    function editar(){ window.location = "sucursales.php?action=editar&sid=<?php echo $_REQUEST['id'] ?>"; }

    <?php
		/*
		 * Buscar el numero de ventas de esta sucursal, versus las ventas de todas las sucursales en la empresa
		 * 
		 * */
		
		//entero con el numero de ventas
        $ventasEstaSucursal = VentasDAO::contarVentasPorDia( $sucursal->getIdSucursal(), -1 );

		//entero con el numero de ventas de todas las sucursales
        $ventasTodasLasSucursales = VentasDAO::contarVentasPorDia( null, -1 );

		//fechas en Y-m-d
        $fechas = array();

		//buscar la fecha mas vieja
		if(sizeof($ventasEstaSucursal) != 0){
			//no hay ventas aca
			// 
			// 
			
			$startDate = strtotime($ventasEstaSucursal[0]["fecha"]);

			if( strtotime( $ventasTodasLasSucursales[0]["fecha"] )
					< strtotime($ventasEstaSucursal[0]["fecha"])  )
			{
				$startDate = strtotime($ventasTodasLasSucursales[0]["fecha"]) ;
			}



			//current day
			$cDay =  date("Y-m-d",  $startDate  ); 

			//the day the loop will end
			$tomorrow = date("Y-m-d", strtotime("+1 day",  time()));

			//utility variables
			$thisBranchSales = $ventasEstaSucursal;
			$thisBranchSalesIndex = $thisBranchMissingDays = 0;

			$allBranchesSales = $ventasTodasLasSucursales;
			$allBranchesSalesIndex = $allBranchesMissingDays = 0;

			//iniciar en $startDate y sumar dias hasta llegar al dia de hoy
			//en $cDay esta el dia en la iteracion
			while( $tomorrow != $cDay ){

				array_push($fechas, $cDay);

				if( sizeof($allBranchesSales) == $allBranchesSalesIndex ){
					//im out of days !
					array_push($allBranchesSales, array( "fecha" => $cDay, "ventas" => 0 ));
				}

			 	if( $allBranchesSales[ $allBranchesSalesIndex ]["fecha"] != $cDay){
					$allBranchesMissingDays++;
				}else{
					//fill the allBranchesMissingDays !
					for($a = 0 ; $a < $allBranchesMissingDays; $a++){
						array_splice($allBranchesSales, $allBranchesSalesIndex, 0, array(array( "fecha" => "missing_day" , "ventas" => 0)) );
					}
					$allBranchesSalesIndex += $allBranchesMissingDays+1;
					$allBranchesMissingDays = 0;
				}


				if( sizeof($thisBranchSales) == $thisBranchSalesIndex ){
					//im out of days !
					array_push($thisBranchSales, array( "fecha" => $cDay, "ventas" => 0 ));
				}

			 	if( $thisBranchSales[ $thisBranchSalesIndex ]["fecha"] != $cDay){
					$thisBranchMissingDays++;
				}else{
					for($a = 0 ; $a < $thisBranchMissingDays; $a++){
						array_splice($thisBranchSales, $thisBranchSalesIndex, 0, array(array( "fecha" => "missing_day" , "ventas" => 0)) );
					}

					$thisBranchSalesIndex += $thisBranchMissingDays+1;
					$thisBranchMissingDays = 0;
				}



				$cDay = date("Y-m-d", strtotime("+1 day", strtotime($cDay)));
			}



	        echo "\nvar estaSucursal = [";
	        for($i = 0; $i < sizeof($thisBranchSales); $i++ ){
	            echo  "[" . $i . "," . $thisBranchSales[$i]["ventas"] . "]";
	            if($i < sizeof($thisBranchSales) - 1){
	                echo ",";
	            }
	        }
	        echo "];\n";

			echo "console.log( 'esta sucural->', estaSucursal );";

	       echo "\nvar todasSucursales = [";
	        for($i = 0; $i < sizeof($allBranchesSales); $i++ ){
	            echo  "[" . $i . "," . $allBranchesSales[$i]["ventas"] . "]";
	            if($i < sizeof($allBranchesSales) - 1){
	                echo ",";
	            }
	        }
	        echo "];\n";			



	        echo "var fechasVentas = [";
	        for($i = 0; $i < sizeof($fechas); $i++ ){
	            echo  "{ fecha : '" . $fechas[$i] . "'}";
	            if($i < sizeof($fechas) - 1){
	                echo ",";
	            }
	        }
	        echo "];\n";
		}
					
	?>




	Event.observe(document, 'dom:loaded', function() {


	<?php 
		if(POS_ENABLE_GMAPS){
			echo "startMap();";
		}
	?>

	function meses(m){
		m = parseInt(m);
		switch(m){
			case 1: return "enero";
			case 2: return "febrero";
			case 3: return "marzo";
			case 4: return "abril";
			case 5: return "mayo";
			case 6: return "junio";
			case 7: return "julio";
			case 8: return "agosto";
			case 9: return "septiembre";
			case 10: return "octubre";
			case 11: return "noviembre";
			case 12: return "diciembre";
									
		}
	}

	var graficaVentas = new HumbleFinance();
	graficaVentas.setXFormater(
			function(val){
				if(val ==0)return "";
				return meses(fechasVentas[val].fecha.split("-")[1]) + " "  + fechasVentas[val].fecha.split("-")[2]; 
			}
		);
		
	graficaVentas.setYFormater(
			function(val){
				if(val ==0)return "";
				return val + " ventas";
			}
		);
	
	graficaVentas.setTracker(
		function (obj){
				obj.x = parseInt( obj.x );

				return meses(fechasVentas[obj.x].fecha.split("-")[1]) + " "  + fechasVentas[obj.x].fecha.split("-")[2]
							+ ", <b>"+ parseInt(obj.y) + "</b> ventas";

			}
		);	

	graficaVentas.addGraph( estaSucursal );
	graficaVentas.addGraph( todasSucursales );
	graficaVentas.addSummaryGraph( todasSucursales );
	graficaVentas.render('finance');


	});

</script>

<h2><img src='../media/icons/basket_go_32.png'>&nbsp;Ventas en el ultimo dia</h2>

<?php

    $date = new DateTime("now");
    $date->setTime ( 0 , 0, 1 );

    $v1 = new Ventas();
    $v1->setFecha( $date->format('Y-m-d H:i:s') );
    $v1->setIdSucursal( $_REQUEST['id'] );

    $date->setTime ( 23, 59, 59 );
    $v2 = new Ventas();
    $v2->setFecha( $date->format('Y-m-d H:i:s') );

    $ventas = VentasDAO::byRange($v1, $v2);

    //render the table
    $header = array(
	    "id_venta"=>  "Venta",
	    //"id_sucursal"=>  "Sucursal",
	    "id_cliente"=>  "Cliente",
	    "tipo_venta"=>  "Tipo",
	    "fecha"=>  "Hora",
	    "subtotal"=>  "Subtotal",
	    //"iva"=>  "IVA",
	    "descuento"=>  "Descuento",
	    "total"=>  "Total",

	    //"pagado"=>  "Pagado" 
        );

    function getNombrecliente($id)
    {
        if($id < 0){
             return "Caja Comun";
        }
        return ClienteDAO::getByPK( $id )->getRazonSocial();
    }




    function pDate($fecha)
    {

		$foo = toDate($fecha);
		$bar = explode( " " , $foo);
        return $bar[1] . " " . $bar[2];

    }

    function setTipocolor($tipo)
    {
            if($tipo =="credito")
                return "<b>Credito</b>";
            return "Contado";
    }




    $tabla = new Tabla( $header, $ventas );
    $tabla->addColRender( "subtotal", "moneyFormat" ); 
    $tabla->addColRender( "saldo", "moneyFormat" ); 
    $tabla->addColRender( "total", "moneyFormat" ); 
    $tabla->addColRender( "pagado", "moneyFormat" ); 
    $tabla->addColRender( "tipo_venta", "setTipoColor" ); 
    $tabla->addColRender( "id_cliente", "getNombreCliente" ); 
    $tabla->addColRender( "fecha", "pDate" ); 
    $tabla->addOnClick("id_venta", "mostrarDetallesVenta");
    $tabla->addColRender( "descuento", "percentFormat" );
    $tabla->addNoData("Esta sucursal no ha realizado ventas este dia.");
    $tabla->render();



?>



<h2>Compras no saldadas de esta sucursal</h2><?php

	function rSaldo( $pagado ){
		return moneyFormat( $pagado );
	}
	function toDateS( $d ){
		$foo = toDate($d);
		$bar = explode(" ", $foo);
		return $bar[0];
	}
	
	$compras = comprasDeSucursalSinSaldar( $_REQUEST['id'] , false );	

	$header = array(
			"id_compra" => "ID Compra",
		    "fecha"=> "Fecha",
		    "total"=> "Total",
		    "pagado"=> "Pagado" );
	
	$tabla = new Tabla($header, $compras);
	$tabla->addColRender( "fecha", "toDate" );
	$tabla->addColRender("total", "moneyFormat");
	$tabla->addColRender("pagado", "rSaldo");	
	$tabla->addOnClick("id_compra", "detalleCompraSucursal" );
	$tabla->addNoData("Esta sucursal no tiene cuentas sin saldar");
	$tabla->render();

?>
<script>
	function detalleCompraSucursal(id){
		window.location = "inventario.php?action=detalleCompraSucursal&cid=" + id;
	}
</script>



<h2><img src='../media/icons/users_business_32.png'>&nbsp;Personal</h2><?php

    $empleados = listarEmpleados($_REQUEST['id']);
    
    
	switch(POS_PERIODICIDAD_SALARIO){
			case POS_SEMANA : $periodicidad = "semanal"; break;
			case POS_MES 	: $periodicidad = "menusal"; break;
	}
	
    $header = array(
        "id_usuario" => "ID",
        "nombre" => "Nombre",
        "puesto" => "Puesto",
        "RFC" => "RFC",
        //"direccion" => "Direccion",
        //"telefono" => "Telefono",
        "fecha_inicio" => "Inicio",
        "salario" => "Salario " . $periodicidad );


    $tabla = new Tabla( $header, $empleados );
    $tabla->addColRender("salario", "moneyFormat");
	$tabla->addColRender("fecha_inicio", "toDateS");
    $tabla->addNoData("Esta sucursal no cuenta con nigun empleado.");
	$tabla->addOnClick( "id_usuario", "(function(id){window.location='personal.php?action=detalles&uid=' + id;})" );
    $tabla->render();


    $totalEmpleados = 0;

    foreach ($empleados as $e)
    {
         $totalEmpleados += $e['salario'];
    }

   	$salarioGerente  = 0;
	if($gerente !== null)
    	$salarioGerente  = $gerente->getSalario();



?>
<div align="right">
<table>
    <tr><td>Salarios empleados</td><td><b><?php echo moneyFormat($totalEmpleados); ?></b></td></tr>
    <tr><td>Salario gerente</td><td><b><?php echo moneyFormat($salarioGerente); ?></b></td></tr>
    <tr><td>Total</td><td><b><?php echo moneyFormat($totalEmpleados + $salarioGerente); ?></b></td></tr>
</table>
</div>










<h2><img src='../media/icons/window_app_list_chart_32.png'>&nbsp;Inventario actual</h2><?php
	
	//obtener los clientes del controller de clientes
	$inventario = listarInventario( $_REQUEST['id'] );

    function colorExistencias( $n )
    {
        //buscar en el arreglo
        if($n < 10){
            return "<div style='color:red;'>" . $n . "</div>";
        }

        return $n;
    }

	function toUnit( $e, $row )
	{
		//$row["tratamiento"]
		switch($row["medida"]){
			case "kilogramo" : $escala = "Kgs"; break;
			case "pieza" : $escala = "Pzas"; break;		
		}

		return "<b>" . number_format( $e / 60, 2 ) . "</b>Arp. / <b>" . number_format($e, 2) . "</b>" . $escala ;
	}

	function toUnitProc( $e, $row )
	{
		if($row["tratamiento"] == null){
			return "<i>NA</i>";
		}

		switch($row["medida"]){
			case "kilogramo" : $escala = "Kgs"; break;
			case "pieza" : $escala = "Pzas"; break;		
		}

		return "<b>" . number_format( $e / 60, 2 ) . "</b>Arp. / <b>" . number_format($e, 2) . "</b>" . $escala ;
	}

	//render the table
	$header = array( 
		"productoID" => "ID",
		"descripcion"=> "Descripcion",
		"precioVenta"=> "Precio sugerido",
		"existenciasOriginales"=> "Existencias Originales",
		"existenciasProcesadas"=> "Existencias Procesadas");
		

	
	$tabla = new Tabla( $header, $inventario );
	$tabla->addColRender( "precioVenta", "moneyFormat" ); 
	$tabla->addColRender( "existenciasOriginales", "toUnit" ); 
	$tabla->addColRender( "existenciasProcesadas", "toUnitProc" );	
    $tabla->addNoData("Esta sucursal no cuenta con ningun producto en su inventario.");
	$tabla->render();
?>










<h2><img src='../media/icons/email_forward_32.png'>&nbsp;Autorizaciones pendientes</h2><?php
	

    $autorizacion = new Autorizacion();
    $autorizacion->setIdSucursal($_REQUEST['id'] );
    $autorizacion->setEstado("0");
    $autorizaciones = AutorizacionDAO::search($autorizacion);


    $header = array(
               "id_autorizacion" => "ID",
               "fecha_peticion" => "Fecha",
               "id_usuario" => "Usuario que realizo la peticion",
               "parametros" => "Descripcion");


    function renderParam( $json )
    {
        $obj = json_decode($json);
        return $obj->descripcion;
    }

    function renderUsuario($uid){
        $usuario = UsuarioDAO::getByPK( $uid );
        return $usuario->getNombre();
    }

    $tabla = new Tabla($header, $autorizaciones );
    $tabla->addColRender("parametros", "renderParam");
    $tabla->addColRender("id_usuario", "renderUsuario");
    $tabla->addOnClick("id_autorizacion", "detalle");
    $tabla->addNoData("No hay autorizaciones pendientes");
    $tabla->render();


?>









<h2><img src='../media/icons/user_add_32.png'>&nbsp;Clientes que se registraron en esta sucursal</h2><?php
	
	$foo = new Cliente();
	$foo->setActivo(1);
    $foo->setIdCliente(1);
	$foo->setIdSucursal( $_REQUEST['id'] );
    
	$bar = new Cliente();
    $bar->setIdCliente(999);

	$clientes = ClienteDAO::byRange($foo, $bar);

    //render the table
    $header = array(  "razon_social" => "Razon Social", "rfc" => "RFC", /* "direccion" => "Direccion", */ "municipio" => "Municipio"  );
    $tabla = new Tabla( $header, $clientes );
    $tabla->addOnClick("id_cliente", "mostrarDetalles");
    $tabla->addNoData("Ningun cliente se ha registrado en esta sucursal.");
    $tabla->render();


?>





<h2><img src='../media/icons/window_app_list_chart_32.png'>&nbsp;Flujo de efectivo desde el ultimo corte</h2><?php

    $flujo = array();


	/*******************************************
	 * Fecha desde el ultimo corte
	 * *******************************************/
    $corte = new Corte();
    $corte->setIdSucursal($_REQUEST['id']);

    $cortes = CorteDAO::getAll( 1, 1, 'fecha', 'desc' );



    if(sizeof($cortes) == 0){
        echo "<div align=center>No se han hecho cortes en esta sucursal. Mostrando flujo desde la apertura de sucursal.</div><br>";
        
        $fecha = $sucursal->getFechaApertura();

    }else{

        $corte = $cortes[0];
        echo "Fecha de ultimo corte: <b>" . $corte->getFecha() . "</b><br>";
        $fecha = $corte->getFecha();

    }
    

    $now = new DateTime("now");
    $hoy = $now->format("Y-m-d H:i:s");

	/*******************************************
	 * Buscar los gastos
	 * Buscar todos los gastos desde la fecha inicial
	 * *****************************************/
    $foo = new Gastos();
    $foo->setFecha( $fecha );
    $foo->setIdSucursal( $_REQUEST['id'] );

    $bar = new Gastos();
    $bar->setFecha($hoy);
    
    $gastos = GastosDAO::byRange( $foo, $bar );


    foreach ($gastos as $g )
    {
        array_push( $flujo, array(
            "tipo" => "gasto",
            "concepto" => $g->getConcepto(),
            "monto" => $g->getMonto() * -1,
            "usuario" => $g->getIdUsuario(),
  			"fecha"=>$g->getFecha()
        ));
    }


	/*******************************************
	 * Ingresos
	 * Buscar todos los ingresos desde la fecha inicial
	 * *******************************************/
    $foo = new Ingresos();
    $foo->setFecha( $fecha );
    $foo->setIdSucursal( $_REQUEST['id'] );

    $bar = new Ingresos();
    $bar->setFecha($hoy);
    
    $ingresos = IngresosDAO::byRange( $foo, $bar );

    foreach ($ingresos as $i )
    {
        array_push( $flujo, array(
            "tipo" => "ingreso",
            "concepto" => $i->getConcepto(),
            "monto" => $i->getMonto(),
            "usuario" => $i->getIdUsuario(),
   			"fecha"=>$i->getFecha()
        ));
    }
    
    
	/*******************************************
	 * Ventas
	 * Buscar todas la ventas a contado para esta sucursal desde esa fecha
	 * *******************************************/
	$foo = new Ventas();
    $foo->setFecha( $fecha );
    $foo->setIdSucursal( $_REQUEST['id'] );
    $foo->setTipoVenta( 'contado' );

    $bar = new Ventas();
    $bar->setFecha($hoy);
    
    $ventas = VentasDAO::byRange( $foo, $bar );


	//las ventas
    foreach ($ventas as $i )
    {
        array_push( $flujo, array(
            "tipo" => "venta",
            "concepto" => "<a href='ventas.php?action=detalles&id=" . $i->getIdVenta() . "'>Venta de contado</a>",
            "monto" => $i->getPagado(),
            "usuario" => $i->getIdUsuario(),
   			"fecha"=>$i->getFecha()
        ));
    }



	/*******************************************
	 * Abonos
	 * Buscar todos los abonos para esta sucursal que se hicierond espues de esa fecha
	 * *******************************************/
	$query = new PagosVenta();
	$query->setIdSucursal($_REQUEST["id"]);
	$query->setFecha($fecha);
	
	$queryE = new PagosVenta();
	$queryE->setFecha($hoy);


	$results = PagosVentaDAO::byRange( $query, $queryE );
	
	foreach( $results as $pago ){
		array_push($flujo, array(
			"tipo" => "abono",
			"concepto" => "<a href='ventas.php?action=detalles&id=" . $pago->getIdVenta() . "'>Abono a venta</a>",
			"monto" => $pago->getMonto(),
			"usuario" => $pago->getIdUsuario(),
			"fecha"=>$pago->getFecha()
		));
	}


	/*******************************************
	 * DIBUJAR LA GRAFICA
	 * *******************************************/
    $header = array(
               "tipo" => "Tipo",
               "concepto" => "Concepto",
               "usuario" => "Usuario",
               "fecha"=> "Fecha",
               "monto" => "Monto" );


    function renderMonto( $monto )
    {
        if($monto < 0 )
          return "<div style='color:red;'>" . moneyFormat($monto) ."</div>";
    
        return "<div style='color:green;'>" . moneyFormat($monto) ."</div>";
    }


function cmpFecha($a, $b)
{
    if ($a["fecha"] == $b["fecha"]) {
        return 0;
    }
    return ($a["fecha"] <$b["fecha"]) ? -1 : 1;
}
usort($flujo, "cmpFecha");

    $tabla = new Tabla($header, $flujo );
    $tabla->addColRender("usuario", "renderUsuario");
    $tabla->addColRender("fecha", "toDate");
    $tabla->addColRender("monto", "renderMonto");
    $tabla->addNoData("No hay operaciones.");
    $tabla->render();

	$enCaja = 0;

	foreach($flujo as $f){
		$enCaja += $f['monto'];
	}
	
	echo "<div align=right><h3>Total en caja: " . moneyFormat($enCaja) . "</h3></div>";
?>



<?php 
if(POS_ENABLE_GMAPS){
    ?><script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script><?php
}
?>