<?php

/**
 *	Controller de Prueba
 *
 *	Contiene todas las funciones que se conectan con la vista, generalmente JSON
 *	@author Rene Michel <rene@caffeina.mx>
 */

/**
 *
 */
require_once('../server/model/model.inc.php');


function div($t=NULL, $c = "")
{
	if($t)
		echo "<div><b>". $t ."</b> " . $c . "</div>";
	else
		echo "<br>";
}


function vender()
{

	
	//seleccionar un usuario
	$usuario = UsuarioDAO::getByPK( rand(1, 25) );
	div( "Usuario a vender", $usuario->getNombre() );
	div();
	
	//seleccionar un cliente
	$cliente = ClienteDAO::getByPK( rand(1,200) );
	div( "Cliente a comprar", $cliente->getNombre() );
	div( "Su limite de credito es", $cliente->getLimiteCredito() );
	div( "Su descuento es", $cliente->getDescuento() );
	div();
	
	
	//seleccinar si sera credito o contado
	$tipo = array("contado", "credito");
	$tipo = $tipo[ rand(0,1) ];
	div("La venta sera de tipo", $tipo);
	
	
	//insertar venta 'vacia'
	$venta = new Ventas();
	$venta->setIdCliente( $cliente->getIdCliente() );
	$venta->setIdUsuario( $usuario->getIdUsuario() );
	$venta->setTipoVenta( $tipo );
	$venta->setIdSucursal( $usuario->getIdSucursal() );
	$resultado = VentasDAO::save($venta);
	
	if($resultado === 1){
		//venta bien
		div("Inserscion de venta", "OK");
		div("Id de esta venta", $venta->getIdVenta());
		div();
	}else{
		//venta mal, si la venta sale mal, trae el error 
		div("Inserscion de venta", $resultado);
		die("Script ended.");
	}
	
	
	//obtener todos los productos de esa sucursal
	$prodABuscar = new DetalleInventario();
	$prodABuscar->setIdSucursal( $usuario->getIdSucursal() );
	$productosSucursal = DetalleInventarioDAO::search( $prodABuscar );
	$totalProds = count($productosSucursal);
	div("Productos en esta sucursal", $totalProds);
	div();

	//aqui guardo el subtotal
	$subtotal = 0;

	//insetar productos a detalle_venta
	//inventar un numero de prodcutos a comprar
	$prodsAComprar = rand(1, 10);
	div("Intentare comprar " . $prodsAComprar . " productos" );
	div();
	
	while($prodsAComprar >= 0)
	{
		
		//escojer un producto del arreglo $productosSucursal al azar
		$producto = $productosSucursal[ rand( 0, $totalProds - 1 ) ];
		div("Intentando comprar el producto ", $producto->getIdProducto() );

		// escojer una cantidad al azar de este producto, osea, cuantos voy a comprar
		$cantidad = rand(1, $producto->getExistencias() - 1);
		div("Cantidad de estos articulos que comprare", $cantidad);

		//revisar que haya existencias
		if($producto->getExistencias() < $cantidad)
		{
			//no hay suficientes existencias
			div("Error", "En esta sucursal solo hay una existencia de " . $producto->getExistencias());
			div();
			continue;
		}
		
		//ok producto valido, lo voy a insertar en detalle_venta
		$detalleVenta = new DetalleVenta();
		$detalleVenta->setIdVenta( $venta->getIdVenta() );
		$detalleVenta->setIdProducto( $producto->getIdProducto() );
		$detalleVenta->setCantidad( $cantidad );
		$detalleVenta->setPrecio( $producto->getPrecioVenta() );
		
		$res = DetalleVentaDAO::save($detalleVenta);
		
		if($res === 1)
		{
			div("Agregando a detalle venta", "OK");
		}else{
			div("Error al crear detalle venta", $res );
			return;
		}
		
		//si todo salio bien
		$subtotal += $producto->getPrecioVenta();
		div();
		
		$prodsAComprar--;
		
	}

	//muestro el subtotal
	div("Sub Total", $subtotal );
	
	//ok, ya inserte detalles a la venta, ahora
	//actualizo la venta que estava 'vacia' osea la cabecera

	
	//obtengo los impuestos que aplican a esta sucursal, mediante una busqueda
	$imp = new Impuesto();
	$imp->setIdSucursal( $usuario->getIdSucursal() );
	$impuestos = ImpuestoDAO::search( $imp );

	//sumo los impuestos, ya que varios impouestos aplicacn a una misma sucursal	
	$totalImp = 0;
	foreach( $impuestos as $imp )
	{
		$totalImp += $imp->getValor();
		div("Aplicando impuesto", $imp->getDescripcion() );
	}

	//imprimo el total de impuestos que cobrare
	div("Impuesto Total", $totalImp);


	//le hare la rebaja a este cliente si es que tiene descuento
	$total = (int)$totalImp + (int)$subtotal;
	$desc = ($cliente->getDescuento() / 100) * $total; 
	$total = $total - $desc;
	div("Aplicando descuento", $desc );

	//muestro el total total total :P
	div("Total", $total );
	
	
	//actualizo la venta, este objeto fue el que usamos al inicio
	//asi que llamar a save() no creara uno nuevo, sino que actualizara
	//los datos nuevos
	$venta->setSubTotal( $subtotal );
	$venta->setIva( $totalImp );
	$res = VentasDAO::save($venta);
	
	if($res === 1){
		div("Actualizando cabecera", "OK");
		div();
	}else{
		div("Actualizando cabecera", "Ups");
		div();
		return;
	}
	
	//si es a contado ahi termina la cosa
	if($tipo == "contado"){
		
		//ya solo me resta decidirme si voy a querer o no factura
		if(rand(0,1)==1)
		{
			//si quiero factura
			div("Quiero Factura !!");			
			
			$factura = new FacturaVenta();
			$factura->setIdVenta($venta->getIdVenta());
			$factura->setFolio("ALGUNFOLIO7" . rand(1000, 9999));
			$res = FacturaVentaDAO::save($factura);

			if($res===1){
				div("Facturando", "OK");
				div();
			}else{
				div("Error al facturar", $res);
				div();
			}
		}
		
		div("Todo bien !", "Gracias por usar el modelo DAO");
		return;
	}
	
	
	
	//si es a credito, vamos a ingresar algunos pago al azar
	div("Esta venta fue a Credito, revisando si esta dentro de mi limite de credito");
	
	if($total > $cliente->getLimiteCredito()){
		div("Ups", "Esta compra esta fuera de mi limite de credito... ya no quiero nada");
		div("FALTA BORRAR COMPRA");
		//borrar esta compra....
		
		return;
	}
	
	div("Compra dentro de mi limite !!");
	
	$pagosQty = rand( 0, 15 );
	
	div("Pagos que realizare", $pagosQty);
	div();
	
	//en este momento mi saldo es igual al total, ya que no he pagado nada
	$saldo = $total;
	
	for($i = 1; $i <= $pagosQty; $i++)
	{

		if($saldo <= 0){
			div("Termine de pagar el articulo !!");
			break;
		}
		div("Saldo restante", $saldo);
		
		$pVenta = new PagosVenta();
		$pVenta->setIdVenta( $venta->getIdVenta() );
		
		//cantidad que pagare al azar...
		$amount = rand(1, ($saldo * .25));
		div("Pago " . $i, $amount);
		
		$pVenta->setMonto($amount);
		$res = PagosVentaDAO::save($pVenta);
		
		if($res===1){
			div("Pagando... ", "OK, el id del pago fue " . $pVenta->getIdPago());
			div();
			$saldo -= $amount;
			
		}else{
			div("Error pagando", $res);
		}
		
		

		
	}
	
	div("Saldo en el que quedo la venta", $saldo);
	
	div("Gracias por usar el modelo DAO");
	
}



function test()
{
	
	div( "Probando by range" );
	
	$criteriaA = new Cliente();
	$criteriaA->setDescuento('34');


	$criteriaB = new Cliente();
	$criteriaB->setIdCliente('30');	
	$criteriaB->setDescuento('50');	
	global $conn;

	$res = ClienteDAO::getAll( 1, 10, 'rfc', 'DESC'  );

		foreach( $res as $c ){
			echo $c->getNombre() . "<br>";
		}

try{
	ClienteDAO::save($criteriaA);
}catch(Exception $e){
	//echo $e->getMessage() ."--- ";
}

	
	
}

function test1()
{


	$ventas = VentasDAO::getAll();
	
	foreach($ventas as $venta)
	{
	
		$randDay = rand(1,28);
		$randMonth = rand(1,12);
		$randYear = rand(2009,2010);
		
		$randHour = rand(0,24);
		$randMinute = rand(0,59);
		$randSec = rand(0,59);
		
		
		if($randDay < 10) $randDay = '0'.$randDay;
		if($randMonth < 10) $randMonth = '0'.$randMonth;
		
		if($randHour < 10) $randHour = '0'.$randHour;
		if($randMinute < 10) $randMinute = '0'.$randMinute;
		if($randSec < 10) $randSec = '0'.$randSec;
	
		$randDate = $randYear.'-'.$randMonth.'-'.$randDay.' '.$randHour.':'.$randMinute.':'.$randSec;
	
		//div("Fecha random: ", $randDate);
	
		$venta->setFecha($randDate);
		if(VentasDAO::save($venta))
		{
			div("Venta actualizada correctamente: ", $randDate);
		}
		else
		{
			div("Error al actualizar venta: ", $randDate);
		}
	
	}
}

function generarVentas()
{
	test();
	//vender();
}

switch($args['action'])
{
	case 901: test1(); break;
	default: echo "bad testing";
	
}
