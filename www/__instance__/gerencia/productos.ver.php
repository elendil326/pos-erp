<?php



	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../server/bootstrap.php");

	$page = new GerenciaTabPage();

	//
	// Parametros necesarios
	// 
	$page->requireParam("pid", "GET", "Este producto no existe.");



	$este_producto = ProductoDAO::getByPK($_GET["pid"]);



	if(is_null($este_producto)){
		$page->render();
		exit;
	}






	$nexistencias = ProductoDAO::ExistenciasTotales( $este_producto->getIdProducto() );
	$precios = TarifasController::_CalcularTarifa( $este_producto, "venta" );

	$html = "<table><tr><td colspan=2><h3>Tarifas</h3></td></tr>	";

	for ($i=0; $i < sizeof($precios); $i++) { 
		$html .= "<tr><td>".$precios[$i]["descripcion"] . "</td><td>" . FormatMoney($precios[$i]["precio"]) . "</td>";
	}

	$um = UnidadMedidaDAO::getByPK( $este_producto->getIdUnidad() );
	$html .= "<tr><td colspan=2><h3>Existencias</h3></td></tr><tr><td> ". $nexistencias." " . $um->getDescripcion() ."</td></tr></tr>";

	$html .= "</table>";


	



	
	if(is_null($este_producto->getFotoDelProducto())){
		//$page->addComponent(" &iquest; Es esta una imagen descriptiva de su producto?");
	}
	


	$page->addComponent("
	<table  class=\"\">

	    <tr>
	        <td rowspan=2><div id=\"gimg\"></div></td>
	        <td><h2>" . $este_producto->getNombreProducto() . "</h2></td>
	    </tr>
	    <tr>
	        <td>" .  $html . "</td>
	    </tr>
	</table>
	<script type=\"text/javascript\">
	    function gimgcb(a,b,c){
	        if(a.responseData.results.length > 0)
	            document.getElementById(\"gimg\").innerHTML = \"<img src='\" + a.responseData.results[0].tbUrl + \"'>\";
	    }
	</script>
	<script 
	        type=\"text/javascript\" 
	        src=\"https://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=".  $este_producto->getCodigoProducto() . "&callback=gimgcb\">
	</script>");












	$page->nextTab("General");

	//
	// Forma de producto
	// 
	$este_producto->setCostoEstandar(FormatMoney($este_producto->getCostoEstandar()));

	$um = UnidadMedidaDAO::getByPK($este_producto->getIdUnidadCompra(  ) );
	if(!is_null($um)){
		$este_producto->setIdUnidadCompra( $um->getDescripcion() ) ;	
	}
	
	
	$um = UnidadMedidaDAO::getByPK($este_producto->getIdUnidad(  ));
	if(!is_null($um)){
		$este_producto->setIdUnidad( $um->getDescripcion() ) ;	
	}

	//$form->createComboBoxJoinDistintName("id_unidad_compra","id_unidad_medida" ,"descripcion", UnidadMedidaDAO::getAll(), $este_producto->getIdUnidadCompra());

	//$form->createComboBoxJoinDistintName("id_unidad", "id_unidad_medida", "descripcion", UnidadMedidaDAO::getAll(), $este_producto->getIdUnidad());

	$form = new DAOFormComponent($este_producto);

	$form->setEditable(false);

	$form->hideField(array(
	  	  "id_producto",
			"foto_del_producto",
			"precio",
			"control_de_existencia",
			"activo",
			"compra_en_mostrador"
		));

	$form->setCaption("id_unidad", "Unidad de medida");
	$form->setCaption("id_unidad_compra", "Unidad de medida al comprar");	

	$page->addComponent($form);











	$page->nextTab("Existencias");

	$existencias = LoteProductoDAO::search( new LoteProducto( array( "id_producto" => $_GET["pid"] ) ) );

	$table_e = new TableComponent(array(
		"id_lote" => "Lote",
		"cantidad" => "Cantidad",
		"id_unidad" => "Unidad"
	),
	$existencias);

	function lotename($l){ 

		$lvo = LoteDAO::GetByPK($l); 

		if(is_null($lvo)){ return ""; }


		$avo = AlmacenDAO::getByPK( $lvo->getIdAlmacen() );

		return $lvo->getFolio() . " <div style='font-size:11px; color: gray;'>Almacen ". $avo ->getNombre()  ."</div>";

	}



	function unidadname($l){ $ll = UnidadMedidaDAO::GetByPK($l); if (is_null($ll)) return "ERROR"; return $ll->getAbreviacion();  }

	$page->addComponent($table_e);
	$table_e->addColRender("id_lote", "lotename");
	$table_e->addColRender("id_unidad", "unidadname");


	$page->addComponent("<hr>");
	$page->addComponent(new TitleComponent("Nueva entrada",2));

	$entrada_lote = new FormComponent(  );
	$entrada_lote->addField("id_lote", "Lote", "combobox" );

	//$entrada_lote->createComboBoxJoin("id_lote", "id_lote", LoteDAO::getAll(   ) );
	$entrada_lote->createComboBoxJoinDistintName("id_lote","id_lote" ,"folio", LoteDAO::getAll());
	$entrada_lote->setHelp("id_lote", "Lote a donde se insertara este arrivo");


	$entrada_lote->addField("cantidad", "Cantidad", "text");
	$este_producto = ProductoDAO::GetByPK($_GET["pid"]);
	$um = UnidadMedidaDAO::getByPK( $este_producto->getIdUnidad(  ) );
	if(!is_null($um)){
		$entrada_lote->setHelp("cantidad", "Cantidad en " .  $um->getDescripcion());
	}else{
		$entrada_lote->setHelp("cantidad", "Error!");	
	}
	

	
	$entrada_lote->addField("productos", "", "text", "\"   [ { \\\"id_producto\\\" : ". $_GET["pid"] .", \\\"cantidad\\\"    : 0 } ]   \"");
	$entrada_lote->sendHidden("productos");
	$entrada_lote->makeObligatory(array( "id_lote", "cantidad"));
	$entrada_lote->beforeSend("beforeSendNuevaEntrada");
	$entrada_lote->addApiCall("api/almacen/lote/entrada", "POST");

	$page->addComponent("<script> function beforeSendNuevaEntrada(a){ 
					console.log('beforeSend(' + a + ')');
					var aPdec = Ext.JSON.decode(a.productos);
					console.log(aPdec);
					aPdec[0].cantidad = a.cantidad;
					a.productos = Ext.JSON.encode(aPdec);
					return a;
				}</script>");
			
	$page->addComponent( $entrada_lote );












	$page->nextTab("Historial");
	//mostrar entradas
	$entradas = LoteEntradaProductoDAO::obtenerEntradaPorProducto( $_GET["pid"] );
	$salidas = LoteSalidaProductoDAO::obtenerSalidaPorProducto( $_GET["pid"] );

	$merged = array_merge($entradas, $salidas);

	function cmpByFecha( $a, $b ){

		if ($a["fecha_registro"] == $b["fecha_registro"]) {
        	return 0;
    	}

    	return ($a["fecha_registro"] < $b["fecha_registro"]) ? 1 : -1;

	}

	usort($merged, "cmpByFecha");

	//var_dump($merged);

	$header = Array(
			//"id_producto" 	=> "id_producto",
			"tipo"			=> "Movimiento",
			"cantidad"		=> "Cantidad",
			"cantidad"		=> "Cantidad",		
			"id_lote"		=> "Lote",
			"id_usuario"	=> "Usuario",
			"fecha_registro"=> "Fecha"
			//"motivo"		=> "Motivo"
		);
	$tabla = new TableComponent($header, $merged);
	$tabla->addColRender("id_usuario", "username");
	$tabla->addColRender("cantidad", "rCantidad");
	$tabla->addColRender("fecha_registro", "FormatTime");
	$tabla->addColRender("id_lote", "idlote");
	$tabla->addColRender("tipo", "tipoMov");

	function tipoMov($tipo, $obj){

		return $tipo . "<div style='color:gray; font-size:11px'>" . ucfirst($obj["motivo"]) . "</div>";
	}

	function username($idu){
		$u = UsuarioDAO::getByPK($idu);
		return $u->getNombre();
	}

	function idlote($v){
		$loteO = LoteDAO::getByPK($v);
		return $loteO->getFolio();
	}

	function rCantidad($v, $obj){
		$um = UnidadMedidaDAO::getByPK($obj["id_unidad"]);
		return $v . " " . $um->getAbreviacion();
	}

	$page->addComponent($tabla);



/*
	$salidas = LoteSalidaProductoDAO::search(new LoteSalidaProducto( array( "id_producto" => $_GET["pid"]) ));

	$merged = array_merge($entradas, $salidas);

	$header = Array(
			"id_producto" => "id_producto",
			"cantidad"	=> "cantidad"
		);
	
	$historial = array();

	for ($i=0; $i < sizeof($merged); $i++) { 
		$historial[$i] = LoteEntradaDAO::getByPK( $merged[$i]->id_lote_entrada );
	}

	$tabla = new TableComponent($header, $historial);
	$page->addComponent($tabla);

	//LoteSalida



*/






	$page->nextTab("Configuracion");
	$menu = new MenuComponent();
	$menu->addItem("Editar este producto", "productos.editar.php?pid=" . $_GET["pid"]);
	//$menu->addItem("Desactivar este producto", null);

	$btn_eliminar = new MenuItem("Desactivar este producto", null);
	$btn_eliminar->addApiCall("api/producto/desactivar", "GET");
	$btn_eliminar->onApiCallSuccessRedirect("productos.lista.php");
	$btn_eliminar->addName("eliminar");

	$funcion_eliminar = " function eliminar_producto(btn){" . "if(btn == 'yes')" . "{" . "var p = {};" . "p.id_producto = " . $_GET["pid"] . ";" . "sendToApi_eliminar(p);" . "}" . "}" . "      " . "function confirmar(){" . " Ext.MessageBox.confirm('Desactivar', 'Desea eliminar este producto?', eliminar_producto );" . "}";

	$btn_eliminar->addOnClick("confirmar", $funcion_eliminar);

	$menu->addMenuItem($btn_eliminar);

	$page->addComponent($menu);

	$page->render();
