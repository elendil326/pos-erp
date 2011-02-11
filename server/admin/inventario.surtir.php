<?php


	require_once("model/sucursal.dao.php");
    require_once('model/autorizacion.dao.php');
	require_once("controller/clientes.controller.php");
	require_once("controller/sucursales.controller.php");
	require_once("controller/inventario.controller.php");

	$productos = InventarioDAO::getAll();
	
	$iMaestro = listarInventarioMaestro(50, POS_SOLO_ACTIVOS) ;

    if(isset( $_REQUEST['aut'])){
        $autorizacion = AutorizacionDAO::getByPK( $_REQUEST['aut'] );
        $autorizacionDetalles = json_decode( $autorizacion->getParametros() );
    }
	
	$foo = new Sucursal();
    $foo->setActivo(1);
    $foo->setIdSucursal(1);

    $bar = new Sucursal();
    $bar->setIdSucursal(99);

    $sucursales = SucursalDAO::byRange($foo, $bar);

?>



<script type="text/javascript" charset="utf-8">


	/**
	  *	Arreglo con un objeto por cada sucursal
	  **/
var sucursales = [],

	/**
	  *	Sucursal que se esta surtiendo en este momento
	  **/
	currentSuc = null,
	
	/**
	  *	El carrito final de productos que voy a surtir
	  **/
	carrito = [],
	
	/**
	  *	Un objeto de la clase inventario
	  **/
	inventario,
	
	tablaInventario,
	
	composicionTabla,
	
	/**
	  * Un arreglo que contiene toda la informacion de las composiciones
	  **/ 
	composiciones = [];




/**
  *
  * Main
  *
  **/
jQuery(document).ready(function(){

	//construir el objeto de inventario
	inventario = new InventarioMaestro();
	
	
	tablaInventario = new InventarioMaestroTabla({ 
		inventario : inventario, 
		renderTo : "InventarioMaestroTabla"
	});
	
	
	//seleccionar sucursal si es que se envio un id de sucursal
	<?php if(isset($_REQUEST['sid'])){ echo " seleccionarSucursal(); "; } ?>

	
	jQuery("#MAIN_TITLE").html("Surtir sucursal");
});

function round( n ){
	return  Math.round(parseFloat(n)*Math.pow(10,4))/Math.pow(10,4); 
}

function tr(s, o){
	if(o)
	return "<tr "+o+">"+s+"</tr>";
	return "<tr >"+s+"</tr>";
}

function td(s, o){
	if(o)
	return "<td "+o+">"+s+"</td>";
	return "<td >"+s+"</td>";
}

function div(s, o){
	if(o)
	return "<div "+o+">"+s+"</div>";
	return "<div >"+s+"</div>";
}

/**
  * Clase InventarioMaestro 
  *
  **/
InventarioMaestro = function( ){
	
	var estructura = [];

	//revisar si existe este producto en el inventario maestro
	function existeProducto( producto ){
		for( var z = 0; z < estructura.length; z++ ){
			if( estructura[z].compare(producto) ){
				return true;
			}
		}
		return false;
	}

	//agregar un producto al inventario maestro
	function agregarProducto( producto ){
		if( existeProducto(producto) ){
			throw ("Este producto ya existe en el inventario maestro");
		}else{
			estructura.push(producto);
		}
	};

	this.getProductos = function(){
		return estructura;
	};

	this.getProducto = function (id_compra, id_producto){
		for( var z = 0; z < estructura.length; z++ ){
			if(estructura[z].id_compra_proveedor === id_compra && 
				estructura[z].id_producto === id_producto){
					return estructura[z];
				}
		}
		return null;
	}

	this.getProductoDesc = function(id_producto){
		for( var z = 0; z < estructura.length; z++ ){
			if( estructura[z].id_producto === id_producto){
					return {
						id_producto : estructura[z].id_producto,
						descripcion : estructura[z].producto_desc
					};
				}
		}
		throw("No encontre el producto " + id_producto);
		return null;
	}

	/**
	  * Constructor
	  *
	  *
	  **/
	var foo;
	<?php
	foreach( $iMaestro as $i ){	
		echo " 	foo = new Producto (" . json_encode($i) . ") ;";
		echo "	agregarProducto( foo );";
	} ?>
	


};





/**
  * Clase Producto
  *
  **/
Producto = function( json_rep ){

	//compara si este producto es igual a *producto*
	this.compare = function ( producto ){
		return this.id_compra_proveedor === producto.id_compra_proveedor && 
			this.id_producto === producto.id_producto;
	}
	
	
	//constructor a partir de un json
	this.id_compra_proveedor	= parseInt( json_rep.id_compra_proveedor ); 
	this.id_producto 			= parseInt( json_rep.id_producto );	
	this.peso_origen 			= parseFloat( json_rep.peso_origen );
	this.id_proveedor 			= parseInt( json_rep.id_proveedor );
	this.fecha 					= json_rep.fecha;
	this.fecha_origen 			= json_rep.fecha_origen;
	this.folio 					= json_rep.folio;   
	this.numero_de_viaje 		= json_rep.numero_de_viaje;
	this.peso_recibido			= parseFloat( json_rep.peso_recibido );
	this.arpillas 				= parseFloat( json_rep.arpillas );
	this.peso_por_arpilla 		= parseFloat( json_rep.peso_por_arpilla );
	this.productor 				= json_rep.productor;
	this.calidad 				= json_rep.calidad;           
	this.merma_por_arpilla 		= parseFloat( json_rep.merma_por_arpilla );
	this.total_origen 			= parseFloat( json_rep.total_origen );
	this.existencias  			= parseFloat( json_rep.existencias );
	this.existencias_procesadas = parseFloat( json_rep.existencias_procesadas );
	this.sitio_descarga 		= json_rep.sitio_descarga;
	this.variedad 				= json_rep.variedad;  
	this.kg 					= json_rep.kg;        
	this.precio_por_kg 			= json_rep.precio_por_kg;
	this.producto_desc 			= json_rep.producto_desc;         
	this.producto_tratamiento 	= json_rep.producto_tratamiento;
	this.escala 				= json_rep.medida;
	this.sitio_descarga_desc 	= json_rep.sitio_descarga_desc;
	this.costo_flete 			= parseFloat( json_rep.costo_flete );
}





/**
  * Clase para dibujar el inventario maestro
  *
  **/
InventarioMaestroTabla = function( config ) {
	
	/**
	  * 
	  **/
	var inventario,

	/**
	  * id del elemento donde se dibujara esta tabla
	  **/
		renderTo;
	
	
	function render(){
		var html,
			row_html;
			
		html = '';	
		html += '<table style="width: 100%">';
		html += '<tr>';
		html += '<th>Remision</th>';
		html += '<th>Producto</th>';
		html += '<th>Variedad</th>';
		html += '<th>Arpillas en embarque</th>';
		html += '<th>Promedio</th>';		
		html += '<th>Productor</th>';
		html += '<th>Llegada</th>';		
		html += '<th>Existencias</th>';		
		html += '<th>Procesadas</th>';		
		html += '</tr>';
		
		var productos = inventario.getProductos();
		for(var a = 0; a < productos.length; a++){
			row_html = '';
			
			row_html += td( productos[a].folio )
				+ td( productos[a].producto_desc )
				+ td( productos[a].variedad )
				+ td( productos[a].arpillas )
				+ td( productos[a].peso_por_arpilla )
				+ td( productos[a].productor )
				+ td( productos[a].fecha )
				+ td( div("<b>" + productos[a].existencias + "</b>&nbsp;" + productos[a].escala + "s", "id='"
					+ productos[a].id_compra_proveedor + "-" 
					+ productos[a].id_producto + "-existencias"
				 	+ "'"));
				
			if(productos[a].producto_tratamiento){
				row_html += td( div("<b>" + productos[a].existencias_procesadas + "</b>&nbsp;" + productos[a].escala + "s", "id='"
						+ productos[a].id_compra_proveedor + "-" 
						+ productos[a].id_producto + "-existencias-procesadas"
				 		+ "'"));
				//td( "<b>" + productos[a].existencias_procesadas + "</b>&nbsp;" + productos[a].escala + "s");				
			}else{
				row_html += td( "NA");
			}

			html += tr( row_html, "onClick='composicionTabla.agregarProducto( "+  productos[a].id_compra_proveedor +", "+ productos[a].id_producto +" );'" );
		}
		
			
		html += '</table>';		
		
		jQuery("#" + renderTo).html( html );
	}

	function __init(config){
		if( !config.inventario instanceof InventarioMaestro ){
			throw ("Configuracion contiene algo que no es un inventario maestro");
		}
		
		inventario = config.inventario;
		
		
		renderTo = config.renderTo;
		
		render();
	}

	this.tomarProducto = function (id_compra, id_producto, cantidadATomar, procesadas ){

		var producto = inventario.getProducto(id_compra, id_producto);
		
		var newQty = producto.existencias - cantidadATomar,
			newQtyProc = producto.existencias_procesadas - cantidadATomar;
			
		if(newQty < 0){
			newQty = "<b style='color: #AB443B'>" + round(newQty) + "</b>&nbsp;" + producto.escala + "s";
		}else{
			newQty = "<b style='color: #3F8CE9'>" + round(newQty) + "</b>&nbsp;" + producto.escala + "s";			
		}
		

		jQuery("#"+id_compra+"-"+id_producto+"-existencias").html(newQty);
		jQuery("#"+id_compra+"-"+id_producto+"-existencias").slideUp(
			250,
			function (){
				jQuery("#"+id_compra+"-"+id_producto+"-existencias").slideDown(  );
			}
		);
		
		
		if(procesadas){

			
			if(newQtyProc < 0){
				newQtyProc = "<b style='color: #AB443B'>" + round(newQtyProc) + "</b>&nbsp;" + producto.escala + "s";
			}else{
				newQtyProc = "<b style='color: #3F8CE9'>" + round(newQtyProc) + "</b>&nbsp;" + producto.escala + "s";
			}

			jQuery("#"+id_compra+"-"+id_producto+"-existencias-procesadas").html(newQtyProc);			
			jQuery("#"+id_compra+"-"+id_producto+"-existencias-procesadas").slideUp(
				250,
				function (){

					jQuery("#"+id_compra+"-"+id_producto+"-existencias-procesadas").slideDown(  );
				}
			);
		}else{
			newQtyProc = "<b>" + producto.existencias_procesadas + "</b>&nbsp;" + producto.escala + "s";
			jQuery("#"+id_compra+"-"+id_producto+"-existencias-procesadas").html(newQtyProc);			
			
		}
	}
	
	__init(config);
	
};





/**
  * Clase de Composicion Tabla
  *
  **/
ComposicionTabla = function( config ){
	var renderTo,
	
		composicion = [],
		
		id_producto;
	
	function render(){
		var html = '';
		html += '<table style="width:100%">';
		html += '<tr id="ASurtirTablaHeader">';
			html += td("");
			html += td("Remision");
			html += td("Producto");
			html += td("Cantidad");
			html += td("Procesada");						
			html += td("Precio");		
			html += td("Descuento");		
			html += td("Importe");		
		html += '</tr>';
		html += '</table>';		
		jQuery("#" + renderTo).html( html );
	}
	
	function __init(config){
		renderTo = config.renderTo;
		id_producto = config.id_producto;
		render();
	}
	
	
	this.doMath = function( id_compra, id_producto, campo, valor ){
		
		//console.log("doing some math !", campo, valor);
		
		//buscar este producto en el inventario
		var prod = inventario.getProducto( id_compra, id_producto );
		var comp;
		
		for (var i = composicion.length - 1; i >= 0; i--){
			if( composicion[i].id_compra === id_compra && composicion[i].id_producto === id_producto ){
				comp = composicion[i];
				break;
			}
		}

		if(valor.length == 0){
			valor = 0;
		}

		switch( campo ){
			case "proc" : 
				comp.procesada =  valor ;
				tablaInventario.tomarProducto( id_compra, id_producto, comp.cantidad, comp.procesada );
			break;
			
			case "cantidad":
				comp.cantidad = parseFloat( valor );
				tablaInventario.tomarProducto( id_compra, id_producto, comp.cantidad, comp.procesada );
			break;
			
			case "precio" :
				comp.precio = parseFloat( valor );
			break;
			
			case "descuento":
				comp.descuento = parseFloat( valor );
			break;
		}

		
		jQuery( "#" + comp.id_compra + "-" + comp.id_producto + "-importe" ).val( cf(comp.precio * (comp.cantidad - comp.descuento) ) );
		
		
	}
	
	this.quitarProducto = function (id_compra, id_producto){
		jQuery("#" +  id_compra + "-" + id_producto + "-composicion").remove();
		
		var index = null;
		
		//buscar esa composicion el arreglo
		for (var i = composicion.length - 1; i >= 0; i--){
			if( composicion[i].id_compra === id_compra 
					&& composicion[i].id_producto === id_producto){
						index = i;
						break;
					}
		}
		
		if(composicion[index].cantidad != 0){
			this.doMath( id_compra, id_producto, "cantidad", 0 );
		}
		
		composicion.splice(index,1);
		
	}
	
	
	this.agregarProducto = function( id_compra, id_producto ){
		
		producto = inventario.getProducto( id_compra, id_producto );
		
		var html = "";
		
		html += td( "<img onClick='composicionTabla.quitarProducto(" + id_compra + "," + id_producto + ")' src='../media/icons/close_16.png'>" );
		html += td( producto.folio );
		html += td( producto.producto_desc );
		
		var keyup = "onkeyup='composicionTabla.doMath(" + id_compra + "," + id_producto + ", this.name, this.value )'";
		var click = "onClick='composicionTabla.doMath(" + id_compra + "," + id_producto + ", this.name, this.value )'";
				
		html += td( "<input name='cantidad' "+keyup+" value='0' type='text'>" );

		var procesadas = parseFloat( producto.existencias_procesadas );

		if( producto.producto_tratamiento !== null){
			if(procesadas > 0){
				html += td( "<input style='width: 100px' name='proc' "+click+" type='checkbox'>" );			
			}else{
				html += td( "<input style='width: 100px'  type='checkbox' disabled>" );
			}			
		}else{
				html += td( "<input type='hidden'><i>NA</i>" );			
		}

	
		//sumar el flete !!
		//console.log(producto, producto.peso_origen, producto.costo_flete)
		
		var costo_flete = 0;
		
		if( parseFloat (producto.costo_flete) != 0){
			 costo_flete =  producto.peso_origen / producto.costo_flete;
		}
		
		html += td( "<input   	name='precio'     value='"+ ( producto.precio_por_kg + costo_flete )+"' "	+keyup+"	type='text'>" );
		html += td( "<input 	name='descuento'  value='0'	  					    	"	+keyup+" 	type='text'>" );
		html += td( "<input  	id='" +id_compra+"-"+ id_producto+ "-importe'							type='text' disabled>" );

	
		composicion.push({
			id_compra 	: producto.id_compra_proveedor,
			id_producto	: producto.id_producto, 
			cantidad	: 0,
			desc 		: producto.producto_desc,
			procesada	: false,
			precio		: producto.precio_por_kg,
			descuento	: 0
		});

		jQuery("#ASurtirTablaHeader").after( tr(html, "id='" + id_compra + "-" + id_producto + "-composicion'") );		
	}
	
	
	this.doneWithMix = function (){
		var c, total_qty = 0;
		
		//revisar que todo concuerde
		for (var i = composicion.length - 1; i >= 0; i--){
			c = composicion[i];
			total_qty += parseFloat( c.cantidad - c.descuento );
		}

		if(composicion.length == 0){
			error("No ha agregado ningun producto", "El producto debe conmponerse de por lo menos un producto. Agregue un producto de su inventario maestro para continuar.");
			return;
		}

		if(total_qty == 0){
			error("El peso total es cero", "No puede componer un producto a surtir cuando el peso total es igual a cero. ");
			return;
		}

		composiciones.push({
			items : composicion,
			producto : id_producto
		});
	
		jQuery("#listaDeProductos").slideDown('fast', function (){

			jQuery('#InvMaestro').slideUp();
			jQuery('#ASurtir').slideUp('fast', function (){


				//jQuery('html,body').animate({scrollTop: jQuery('#InvMaestro').position().top }, 1000);
			});		

		});
		
		renderFinalShip();

	}
	
	this.rollbackMix = function(){
		
		renderFinalShip();
		
		jQuery("#listaDeProductos").slideDown('fast', function (){

			jQuery('#InvMaestro').slideUp();
			jQuery('#ASurtir').slideUp('fast', function (){



			});		

		});
	}
	
	__init(config);
}




function error(title, msg){
	
	var html = '<h1><img src="../media/icons/warning_32.png">&nbsp;' + title + '</h1>';
	html += msg;
	html += "<div align='center'><input type='button' value='Aceptar' onclick='jQuery(document).trigger(\"close.facebox\");'></div>"
	jQuery.facebox( html );
	
}


<?php 
	/**
	 * Renderear un arreglo con las sucursales
	 * 
	 * */
	foreach( $sucursales as $suc ){	
		echo " sucursales[" . $suc->getIdSucursal() . "] = \"" .  $suc->getDescripcion()  . "\";";
	}
?>

function seleccionarSucursal(){


	if(currentSuc !== null){
		jQuery("#actual" + currentSuc).slideUp();
	}


    <?php 
	if(isset($_REQUEST['aut'])) { 
		echo 'jQuery("#Solicitud").slideDown();';
	}
	?>            

	jQuery("#actual" + jQuery('#sucursal').val()).slideDown();
	//jQuery("#InvMaestro").slideDown();
	//jQuery("#ASurtir").slideDown();
    currentSuc = jQuery('#sucursal').val();
    jQuery("#select_sucursal").slideUp();
}


function seleccionDeProd( id ){
	console.log("Seleccione el producto "  + id);
	
	composicionTabla = new ComposicionTabla({
		renderTo : "ComposicionTabla",
		id_producto : id
	});
	
	jQuery("#listaDeProductos").slideUp('fast', function (){
		jQuery("#FinalShip").slideUp();
		jQuery('#InvMaestro').slideDown();
		jQuery('#ASurtir').slideDown('fast', function (){
			

			jQuery('html,body').animate({scrollTop: jQuery('#InvMaestro').position().top }, 1000);
		});		
		
	});
	
}


function renderFinalShip(){
	
	if(composiciones.length == 0 )
		return;
		
	var global_qty = 0, global_qty_real = 0, global_importe = 0;
	
	var html = '<table style="width: 100%">';
	html += '<tr align=left>'
		+ '<th>Producto</th>'
		+ '<th>Peso real</th>'
		+ '<th>Peso a cobrar</th>'		
		+ '<th>Composicion</th>'
		+ '<th>Importe</th>';
			
	for (var i=0; i < composiciones.length; i++) {
		
		jQuery("#producto-" + composiciones[i].producto ).css("text-decoration", "line-through");
		jQuery("#producto-" + composiciones[i].producto ).fadeTo(250, .25);
		
		desc = inventario.getProductoDesc( composiciones[i].producto );
		
		var total_qty = 0;
		var total_qty_with_desc = 0;		
		var total_money = 0;
		var composition = '';
		

		
		for (var j = composiciones[i].items.length - 1; j >= 0; j--){
			total_qty += composiciones[i].items[j].cantidad  ;
			total_qty_with_desc += composiciones[i].items[j].cantidad   - composiciones[i].items[j].descuento ;			
			total_money += ( composiciones[i].items[j].cantidad - composiciones[i].items[j].descuento ) * composiciones[i].items[j].precio ;
			composition += composiciones[i].items[j].cantidad + "<b> / </b>" + composiciones[i].items[j].descuento + " &nbsp; <b>"+ composiciones[i].items[j].desc + "</b><br>";
		}
		
		var color = i % 2 == 0 ? 'style="background-color: #D7EAFF"' : "";
		
		html += tr(
					td("<img src='../media/icons/basket_32.png'>"+ desc.descripcion )
		 			+ td( total_qty )
		 			+ td( total_qty_with_desc )		
					+ td( composition)
					+ td( cf(total_money)) , color);
					
		global_qty += total_qty;
		global_qty_real += total_qty_with_desc;
		global_importe += total_money;
	};



	html += tr(
				  td( "Totales", "style='padding-top: 10px'" )
	 			+ td( global_qty )
	 			+ td( global_qty_real )		
				+ td( "")
				+ td( cf(global_importe) ) ,
				
				"style='border-top: 1px solid #3F8CE9; font-size: 15px;'");

	html += '</html>';
	
	jQuery("#FinalShipTabla").html(html);
	jQuery("#FinalShip").fadeIn();
	
}

/*
function confirmed()
{
//cerrar el facebox
jQuery(document).trigger('close.facebox');

	//hacer ajaxaso
       jQuery.ajaxSettings.traditional = true;

	jQuery("#submitButtons").fadeOut("slow",function(){
		jQuery("#loader").fadeIn();
		
		jQuery.ajax({
		url: "../proxy.php",
		data: { 
			action : 1005, 
			data : jQuery.JSON.encode( readyDATA ),
		},
		cache: false,
		success: function(data){
			try{
		  		response = jQuery.parseJSON(data);
		  		//console.log(response, data.responseText)
			}catch(e){
			
				jQuery("#loader").fadeOut('slow', function(){
					jQuery("#submitButtons").fadeIn();
									window.scroll(0,0);           				
					jQuery("#ajax_failure").html("Error en el servidor, porfavor intente de nuevo").show();
				});                
				return;                    
			}
	

			if(response.success === false){
				
				jQuery("#loader").fadeOut('slow', function(){
					//jQuery("#submitButtons").fadeIn();    
					window.scroll(0,0);           									  				
					jQuery("#ajax_failure").html(response.reason).show();
				});                
				return ;
			}

			reason = "El caragmento se enuentra ahora en transito";
			window.location = "inventario.php?action=transit&success=true&reason=" + reason;
	
		}
		});
	});
}


function restart()
{

	
	jQuery.facebox('<h1>Volver a comenzar</h1>Todos los cambios que ha realizado se perderan. &iquest; Esta seguro que desea comenzar de nuevo ?'
			+ "<br><div align='center'>"
			+ "			<input type='button' onclick=\"window.location = 'inventario.php?action=surtir'\" value='Si'>"
			+ "&nbsp;	<input type='button' onclick=\"jQuery(document).trigger('close.facebox')\" value='No'></div>"
		);
}

**************************************************************/
</script>




<!-- 
		SELECCIONAR SUCURSAL
 -->
<?php if(!isset($_REQUEST['sid'])) { ?>
	<div id="select_sucursal">
    <h2>Seleccione la sucursal que desea surtir</h2>
    <form id="newClient">
    <table border="0" cellspacing="5" cellpadding="5">
	    <tr><td>Sucursal</td>
		    <td>
			    <select id="sucursal"> 
			    <?php
			

				    foreach( $sucursales as $suc ){
					    echo "<option value='" . $suc->getIdSucursal() . "' >" .  $suc->getDescripcion()  . "</option>";
				    }
			    ?>
	
	            </select>
		    </td>
            <td><input type="button" onClick="seleccionarSucursal()" value="Seleccionar"/> </td>
	    </tr>
    </table>
    </form>
    </div>
<?php }else{ ?>
    <input type="hidden" value="<?php echo $_REQUEST['sid']; ?>" id="sucursal" />
<?php } ?>






<?php

//get sucursales
$sucursales = listarSucursales();

function renderProducto( $val ,$row){
	return "<b>" . number_format($val, 2) . "</b>&nbsp;" . $row['medida'] . "s";
}

function renderProductoSmall( $val ,$row){
	
	$medida = "";
	
	switch($row['medida']){
		case "kilogramo" : $medida = "Kgs"; break;
		case "pieza" : $medida = "Pzas"; break;
	}
	
	return "<b>" . number_format( (float)$val, 2) . "</b>&nbsp;" . $medida ;
}

foreach( $sucursales as $sucursal ){
	
	print ("<div id='actual" . $sucursal["id_sucursal"] . "' style='display: none'>");
	print ("<h2>Inventario actual de " . $sucursal["descripcion"] . "</h2>");
	
	//obtener los clientes del controller de clientes
	$inventario = listarInventario( $sucursal["id_sucursal"] );



	//render the table
	$header = array( 
		"productoID" 		=> "ID",
		"descripcion"		=> "Descripcion",
		"precioVenta"		=> "Precio a la venta",
		"existenciasOriginales"		=> "Originales",
		"existenciasProcesadas"		=> "Procesadas" );
		

	
	$tabla = new Tabla( $header, $inventario );
	$tabla->addColRender( "precioVenta", "moneyFormat" );
	$tabla->addColRender( "existenciasOriginales", "renderProducto" );	
	$tabla->addColRender( "existenciasProcesadas", "renderProducto" );		
    $tabla->addNoData("Esta sucursal no tiene nigun registro de productos en su inventario");
	$tabla->render();
	printf("</div>");
}

?>



<!--
	Seleccion de producto a surtir
-->
<div id="listaDeProductos">
	<h2>Sub productos que conformaran este producto a surtir</h2>
	<h3>&iquest; Que productos desea surtir ?</h3>
		<?php
		echo "<table border=0 style='width: 100%; font-size: 14px; cursor: pointer;'>";
			echo "<tr>";
			for($a = 0; $a < sizeof($productos); $a++){

				//buscar su precio sugerido actual
				$act = new ActualizacionDePrecio();
				$act->setIdProducto( $productos[$a]->getIdProducto() );
				$res = ActualizacionDePrecioDAO::search($act, "fecha", "desc");
				$lastOne = $res[0];

				//buscar todas las existencias
				$totals = 0;
				for($i = 0; $i < sizeof($iMaestro); $i++){
					if($iMaestro[$i]['id_producto'] == $productos[$a]->getIdProducto()){
						$totals +=  $iMaestro[$i]['existencias'];
					}

				}
				if($a % 5 == 0){
					echo "</tr><tr>";
				}

				echo "<td id='producto-" . $productos[$a]->getIdProducto() . "'  onClick='seleccionDeProd( " .  $productos[$a]->getIdProducto() . " )' onmouseover=\"this.style.backgroundColor = '#D7EAFF'\" onmouseout=\"this.style.backgroundColor = 'white'\"><img style='float:left;' src='../media/icons/basket_add_32.png'>" . $productos[$a]->getDescripcion() . "<br>";
				//echo "<b>" . number_format( $totals , 2) ."</b>&nbsp;" .$productos[$a]->getEscala() . "s<br/><br/>";
				echo " " . moneyFormat($lastOne->getPrecioVenta()) .  "<br><br>";
				echo "</td>";
			}
			echo "</tr>";
		echo "</table>";
		?>
</div>





<!-- 
		MOSTRAR INVENTARIO MAESTRO
 -->
<div id="InvMaestro" style="display: none;">
	<h2>Inventario Maestro</h2>
	<h3>&iquest; Como se conformara este producto ?</h3>
	<div id="InventarioMaestroTabla"></div>
</div>





<!-- 
		SELECCIONAR PRODUCTOS A SURTIR
 -->
<div id="ASurtir" style="display: none;">
<h2>Composicion del producto</h2>

	<div id="ComposicionTabla"></div>

	<div id="listoMezclarProducto" align='center'  >
		<input type="button" value="Terminar de componer producto" onclick="composicionTabla.doneWithMix()">
		<input type="button" value="Cancelar de componer este producto" onclick="composicionTabla.rollbackMix()">
	</div>
		

</div>




<div id="FinalShip" style="display: none;">
<h2>Productos a surtir</h2>

	<div id="FinalShipTabla"></div>


	<h4 id="submitButtons" align='center'  >
		<input type="button" value="Surtir" onclick="doSurtir()">
		<!-- <input type="button" value="Volver a comenzar" onclick="restart()"> -->
	</h4>

	<div id="loader" 		style="display: none;" align="center"  >
		<img src="../media/loader.gif">
	</div>


</div>


<style>
    table{
		font-size: 11px;
    }
</style>