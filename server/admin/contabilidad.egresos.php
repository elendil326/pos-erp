<?php

require_once("controller/compras.controller.php");
require_once("controller/sucursales.controller.php");
require_once("controller/ventas.controller.php");
require_once("controller/personal.controller.php");
require_once("controller/efectivo.controller.php");
require_once("controller/inventario.controller.php");

require_once('model/pagos_venta.dao.php');
require_once('model/corte.dao.php');


function renderUsuario($var) {
    return UsuarioDAO::getByPK($var)->getNombre();
}
?>

<script>
	jQuery("#MAIN_TITLE").html("EGRESOS");
	
	function doNuevoGasto(){
		
		//recuperacion
		var g = {
			concepto	: jQuery("#gasto-concepto").val(),
			fecha 		: jQuery("#gasto-fecha").val(),
			folio		: jQuery("#gasto-folio").val(),
			nota 		: jQuery("#gasto-nota").html(),
			monto 		: jQuery("#gasto-monto").val()
		};
		
		//validacion
		if(isNaN(g.monto) || g.monto.length == 0){
			window.scroll(0,0);                         
            jQuery("#ajax_failure").html("El monto no es un numero.").show();
			return;
		}

		console.log(g);

	    //hacer ajaxaso
	    jQuery.ajaxSettings.traditional = true;

	    jQuery("#submitGasto").fadeOut("slow",function(){
	        jQuery("#loader").fadeIn();

	        jQuery.ajax({
	        url: "../proxy.php",
	        data: { 
	            action : 600, 
	            data : jQuery.JSON.encode( g ),
	        },
	        cache: false,
	        success: function(data){
	            try{
	                response = jQuery.parseJSON(data);
	                //console.log(response, data.responseText)
	            }catch(e){

	                jQuery("#loader").fadeOut('slow', function(){
	                    jQuery("#submitGasto").fadeIn();
	                    window.scroll(0,0);                         
	                    jQuery("#ajax_failure").html("Error en el servidor, porfavor intente de nuevo").show();
	                    jQuery("#submitGasto").fadeIn();
	                });                
	                return;                    
	            }


	            if(response.success === false){

	                jQuery("#loader").fadeOut('slow', function(){
	                    //jQuery("#submitGasto").fadeIn();    
	                    window.scroll(0,0);
						try{
	                    	jQuery("#ajax_failure").html(response.reason).show();
						}catch(e){
							jQuery("#ajax_failure").html("Error inesperado").show();
						}

	                    jQuery("#submitGasto").fadeIn();
	                });                
	                return ;
	            }

				var msg = "Gasto creado exitosamente.";
				window.location = "contabilidad.php?action=egresos&success=true&reason=" + msg;

	        }
	        });
	    });
	}
</script>


<h2>Nuevo Gasto</h2>
<div>
	
	<table style="width:100%">
		
		<tr>
			<td>Concepto</td>
			<td>
				<select id="gasto-concepto">
					<option>Comida</option>
					<option>Gasolina</option>
					<option>Lubricantes</option>
					<option>Refacciones</option>
					<option>Oxigeno</option>
					<option>Gas</option>
					<option>Papeleria</option>
					<option>Material Limpieza</option>
					<option>Luz</option>
					<option>Agua</option>
					<option>Teléfono</option>
					<option>Teléfono Celular</option>
					<option>Multas y Recargos</option>
					<option>Permisos Autorizados</option>
					<option>Prestamos Personales</option>
					<option>Sueldos y salarios</option>
					<option>Comisiónes de Personal</option>
					<option>Bascula Publíca</option>
					<option>Casa</option>
					<option>Luis Alberto / Personal</option>
					<option>Varios</option>
					<option>Articulos de Oficina</option>
					<option>Equipo de Seguridad</option>
					<option>Ferreteria</option>
					<option>Carto Empaques</option>
					<option>disel</option>
					<option>AGUINALDOS</option>
					<option>Lucy</option>
					<option>Servicios de casa</option>
					<option>Casetas de CAPUFE</option>
					<option>Estafeta</option>
					<option>Estacionamientos</option>
					<option>Deudas/pagadas</option>		
				</select>
			</td>
			
			<td>Fecha del egreso</td>
			<td><input id="gasto-fecha" type="text" style="width:185px;" placeholder="dd/mm/aaaa"></td>
		</tr>
		
		<tr>
			<td>Folio</td>
			<td><input type="text" id="gasto-folio" style="width:185px;" placeholder="Sin folio"></td>
			<td>Nota</td>
			<td><textarea style="width:300px;" id="gasto-nota" placeholder="Nota adicional sobre este gasto"></textarea></td>
		</tr>

		<tr>
			<td>Monto</td>
			<td><input style="width:185px;" id="gasto-monto" type="text"></td>
		</tr>
		
	</table>
	<div id="loader" 		style="display: none;" align="center"  >
		Procesando <img src="../media/loader.gif">
	</div>
	<div id="submitGasto">
		<h4><input type="button" value="CREAR EL NUEVO GASTO" onClick="doNuevoGasto()"></h4>
	</div>
</div>

<h2>Egresos desde el ultimo corte</h2>


<?php


	if(POS_MULTI_SUCURSAL){
		$sucursal = SucursalDAO::getByPK($_REQUEST['id']);
	}else{
		$sucursal = SucursalDAO::getByPK(0);
	}


    $flujo = array();


	/*******************************************
	 * Fecha desde el ultimo corte
	 * *******************************************/
    $corte = new Corte();
	$corte->setIdSucursal($sucursal->getIdSucursal());

    $cortes = CorteDAO::getAll( 1, 1, 'fecha', 'desc' );



    if(sizeof($cortes) == 0){
	
		if(POS_MULTI_SUCURSAL){
			echo "<div class='light-blue-rounded' >No se han hecho cortes en esta sucursal. Mostrando flujo desde la apertura de sucursal.</div><br>";
		}else{
			echo "<div class='light-blue-rounded' >No se han hecho cortes. Mostrando flujo desde la apertura.</div><br>";
		}

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
    $foo->setIdSucursal( $sucursal->getIdSucursal() );

    $bar = new Gastos();
    $bar->setFecha($hoy);
    
    $gastos = GastosDAO::byRange( $foo, $bar );


    foreach ($gastos as $g )
    {
        array_push( $flujo, array(
            "concepto" => $g->getConcepto(),
            "monto" => $g->getMonto() * -1,
            "usuario" => $g->getIdUsuario(),
  			"fecha"=>$g->getFecha()
        ));
    }



	/*******************************************
	 * DIBUJAR LA GRAFICA
	 * *******************************************/
    $header = array(
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
    $tabla->addNoData("<div class='blue-rounded' >No hay egresos de efectivo.</div>");
    $tabla->render();

	$enCaja = 0;

	foreach($flujo as $f){
		$enCaja += $f['monto'];
	}
	
	if($enCaja != 0){
		?>
			<div align=center style="margin-top: 15px">
				<div  class='blue-rounded' style='width: 300px;'>Egresos totales <?php echo moneyFormat( abs($enCaja) ); ?> pesos </div>
			</div>
		<?php
	}
