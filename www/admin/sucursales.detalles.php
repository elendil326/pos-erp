<?php


require_once("controller/sucursales.controller.php");
require_once("controller/ventas.controller.php");
require_once("controller/personal.controller.php");
require_once("controller/efectivo.controller.php");
require_once("controller/inventario.controller.php");
require_once('model/corte.dao.php');


$sucursal = SucursalDAO::getByPK( $_REQUEST['id'] );


?>
<style type="text/css" media="screen">
	#map_canvas { 
		height: 200px;
		width: 400px;
 	}
</style>
<script type="text/javascript" charset="utf-8" src="../frameworks/prototype/prototype.js"></script>
<script src="../frameworks/humblefinance/flotr/flotr.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/excanvas.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/canvastext.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/canvas2image.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/base64.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8" src="../frameworks/humblefinance/humble/HumbleFinance.js"></script>
<link rel="stylesheet" href="../frameworks/humblefinance/humble/finance.css" type="text/css" media="screen" title="no title" charset="utf-8">

<?php 
if(POS_ENABLE_GMAPS){
    ?><script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script><?php
}
?>

<h1><?php echo $sucursal->getDescripcion(); ?></h1>


<h2>Detalles</h2>
<table border="0" cellspacing="5" cellpadding="5">
	<tr><td><b>Descripcion</b></td><td><?php echo $sucursal->getDescripcion(); ?></td><td rowspan=9><div id="map_canvas"></div></td></tr>
	<tr><td><b>Direccion</b></td><td><?php echo $sucursal->getDireccion(); ?></td></tr>
	<tr><td><b>Apertura</b></td><td><?php echo $sucursal->getFechaApertura(); ?></td></tr>
	<tr><td><b>Gerente</b></td><td>
        <?php 
            $gerente = UsuarioDAO::getByPK( $sucursal->getGerente() );
            echo "<a href='gerentes.php?action=detalles&id=". $sucursal->getGerente() ."'>";
            echo $gerente->getNombre();
            echo "</a>";
        ?>
    </td></tr>
	<tr><td><b>ID</b></td><td><?php echo $sucursal->getIdSucursal(); ?></td></tr>
	<tr><td><b>Letras factura</b></td><td><?php echo $sucursal->getLetrasFactura(); ?></td></tr>
	<tr><td><b>RFC</b></td><td><?php echo $sucursal->getRfc(); ?></td></tr>	
	<tr><td><b>Telefono</b></td><td><?php echo $sucursal->getTelefono(); ?></td></tr>	

	<tr><td colspan=2><input type=button value="Editar detalles" onclick="editar()"></td> </tr>
</table>

<script type="text/javascript"> 


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
		    address : "<?php echo $sucursal->getDireccion(); ?>, Mexico"
	    };
	    try{

		    gc = new google.maps.Geocoder( );

		    gc.geocode(GeocoderRequest,  drawMap);
		
	    }catch(e){
		    console.log(e)
	    }


    }



</script>

<h2>Mapa de ventas</h2>
<div id="finance">
    <div id="fechas">
    </div>
</div>

<h2>Mapa de rendimiento</h2>
<div id="rendimiento">
    <div id="fechas_rendimiento">
    </div>
</div>


<script type="text/javascript" charset="utf-8">
    function mostrarDetallesVenta (vid){ window.location = "ventas.php?action=detalles&id=" + vid; }
    function editar(){ window.location = "sucursales.php?action=editar&sid=<?php echo $_REQUEST['id'] ?>"; }

    <?php
		//obtener la fecha de la primera venta de esta sucursal
        $primeraVenta = SucursalDAO::getByPK( $_REQUEST['id'] )->getFechaApertura();
        $date = new DateTime($primeraVenta);

        $now = new DateTime("now");
   
        $offset = $date->diff($now);


        $ventasEstaSucursal = array();
        $todasLasVentas = array();
        $fechas = array();
                
        while($offset->format("%r%a") > -1){


            /*if($offset->format("%r%a") > -1){
                echo "OK !\n";
            }
            echo $date->format('Y-m-d') . ":\n";
            echo $offset->format("%r%a") . "\n\n";*/


            //buscar las ventas de todas las sucursales
		    $date->setTime ( 0 , 0, 1 );

		    $v1 = new Ventas();
		    $v1->setFecha( $date->format('Y-m-d H:i:s') );


		    $date->setTime ( 23, 59, 59 );
		    $v2 = new Ventas();
		    $v2->setFecha( $date->format('Y-m-d H:i:s') );

		    $results = VentasDAO::byRange($v1, $v2);
            array_push( $todasLasVentas, count($results) );


            //ventas de esta sucursal
		    $v1->setIdSucursal( $_REQUEST['id'] );
		    $results = VentasDAO::byRange($v1, $v2);
            array_push( $ventasEstaSucursal, count($results) );

            array_push( $fechas, $date->format('Y-m-d') );

            //siguiente dia
            $date->add( new DateInterval("P1D") );
            $offset = $date->diff($now);
        }


        echo "\nvar estaSucursal = [";
        for($i = 0; $i < sizeof($ventasEstaSucursal); $i++ ){
            echo  "[" . $i . "," . $ventasEstaSucursal[$i] . "]";
            if($i < sizeof($ventasEstaSucursal) - 1){
                echo ",";
            }
        }
        echo "];\n";


        echo "var todasSucursales = [";
        for($i = 0; $i < sizeof($todasLasVentas); $i++ ){
            echo  "[" . $i . "," . $todasLasVentas[$i] . "]";
            if($i < sizeof($todasLasVentas) - 1){
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
	?>




	Event.observe(document, 'dom:loaded', function() {


        <?php 
            if(POS_ENABLE_GMAPS){ ?>startMap();<?php }
        ?>
 
              
/*
	    HumbleFinance.trackFormatter = function (obj) {
            return fechasVentas[ parseInt(obj.x) ].fecha + "\nVentas:" + obj.y ;

	    };

	    HumbleFinance.yTickFormatter = function (n) {
	        if (n == this.axes.y.max) {
	            return false;
	        }

            if(n == 0 ){
                return false;
            }

	        return n + " ventas";
	    };

	    HumbleFinance.xTickFormatter = function (n) { 
	        if (n == 0) {
	            return false;
	        }
	        var date = fechasVentas[ parseInt(n) ].fecha;
            return date;
	    }

		var grafica1 = new HumbleFinance();
	
	    HumbleFinance.init('finance', estaSucursal, todasSucursales, todasSucursales);

		
	    var xaxis = HumbleFinance.graphs.summary.axes.x;
	    var prevSelection = HumbleFinance.graphs.summary.prevSelection;
	    var xmin = xaxis.p2d(prevSelection.first.x);
	    var xmax = xaxis.p2d(prevSelection.second.x);

	    Event.observe(HumbleFinance.containers.summary, 'flotr:select', function (e) {
			var area = e.memo[0];
	        xmin = Math.floor(area.x1);
	        xmax = Math.ceil(area.x2);

	        var date1 = fechasVentas[xmin].fecha;
	        var date2 = fechasVentas[xmax].fecha;

	        $('fechas').update("Mostrando rango <b>" + date1 + '</b> al <b>' + date2 + "</b>");

	    });
*/
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
	    "id_sucursal"=>  "Sucursal",
	    "id_cliente"=>  "Cliente",
	    "tipo_venta"=>  "Tipo",
	    "fecha"=>  "Fecha",
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
        return ClienteDAO::getByPK( $id )->getNombre();
    }




    function getDescSuc($sid)
    {

        return SucursalDAO::getByPK( $sid )->getDescripcion();

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
    $tabla->addColRender( "id_sucursal", "getDescSuc" ); 
    $tabla->addOnClick("id_venta", "mostrarDetallesVenta");
    $tabla->addColRender( "descuento", "percentFormat" );
    $tabla->addNoData("Esta sucursal no ha realizado ventas este dia.");
    $tabla->render();



?>






<h2><img src='../media/icons/users_business_32.png'>&nbsp;Personal</h2><?php

    $empleados = listarEmpleados($_REQUEST['id']);
 
    $header = array(
        "id_usuario" => "ID",
        "nombre" => "Nombre",
        "puesto" => "Puesto",
        "RFC" => "RFC",
        //"direccion" => "Direccion",
        "telefono" => "Telefono",
        "fecha_inicio" => "Inicio",
        "salario" => "Salario" );


    $tabla = new Tabla( $header, $empleados );
    $tabla->addColRender("salario", "moneyFormat");
    $tabla->addNoData("Esta sucursal no cuenta con nigun empleado.");
    $tabla->render();


    $totalEmpleados = 0;

    foreach ($empleados as $e)
    {
         $totalEmpleados += $e['salario'];
    }

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



	//render the table
	$header = array( 
		"productoID" => "ID",
		"descripcion"=> "Descripcion",
		"precioVenta"=> "Precio Venta",
		"existenciasMinimas"=> "Minimas",
		"existencias"=> "Existencias",
		"medida"=> "Tipo",
		"precioIntersucursal"=> "Precio Intersucursal" );
		

	
	$tabla = new Tabla( $header, $inventario );
	$tabla->addColRender( "precioVenta", "moneyFormat" ); 
	$tabla->addColRender( "precioIntersucursal", "moneyFormat" ); 
	$tabla->addColRender( "existencias", "colorExistencias" );
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
    $header = array(  "nombre" => "Nombre", "rfc" => "RFC", "direccion" => "Direccion", "ciudad" => "Ciudad"  );
    $tabla = new Tabla( $header, $clientes );
    $tabla->addOnClick("id_cliente", "mostrarDetalles");
    $tabla->addNoData("Ningun cliente se ha registrado en esta sucursal.");
    $tabla->render();


?>





<h2><img src='../media/icons/window_app_list_chart_32.png'>&nbsp;Flujo de efectivo desde el ultimo corte</h2><?php

    $flujo = array();


    //obtener la fecha del ultimo corte
    $corte = new Corte();
    $corte->setIdSucursal($_REQUEST['id']);

    $cortes = CorteDAO::getAll( 1, 1, 'fecha', 'desc' );

    if(sizeof($cortes) == 0){
        echo "No se han hecho cortes en esta sucursal. Mostrando flujo desde la apertura de sucursal.<br>";
        
        $fecha = $sucursal->getFechaApertura();

    }else{

        $corte = $cortes[0];
        echo "Fecha de ultimo corte: <b>" . $corte->getFecha() . "</b><br>";
        $fecha = $corte->getFecha();

    }
    

    $now = new DateTime("now");
    $hoy = $now->format("Y-m-d H:i:s");

    
    //buscar todos los gastos desde el ultimo corte
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
            "usuario" => $g->getIdUsuario()
        ));
    }

    //buscar todos los ingresos desde el ultimo corte
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
            "usuario" => $i->getIdUsuario()
        ));
    }
    


    $header = array(
               "tipo" => "Tipo",
               "concepto" => "Concepto",
               "usuario" => "Descripcion",
               "monto" => "Monto" );


    function renderMonto( $monto )
    {
        if($monto < 0 )
          return "<div style='color:red;'>" . moneyFormat($monto) ."</div>";
    
        return "<div style='color:green;'>" . moneyFormat($monto) ."</div>";
    }



    $tabla = new Tabla($header, $flujo );
    $tabla->addColRender("usuario", "renderUsuario");
    $tabla->addColRender("monto", "renderMonto");
    $tabla->addNoData("No hay operaciones.");
    $tabla->render();


?>




