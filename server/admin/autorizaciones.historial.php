<?php

    require_once('model/autorizacion.dao.php');
    require_once('model/sucursal.dao.php');
    require_once('model/usuario.dao.php');

    $autorizaciones = AutorizacionDAO::getAll(1, 250, "fecha_peticion", "DESC");
?>


<div  >

	<table border=0 style='width: 100%; font-size: 14px; cursor: pointer;'>
		<tr>
			<td class='prod rounded'  onClick='filterByState(-1)' >
				Todas
			</td>
			<td class='prod rounded'  onClick='filterByState(0)' >
				Pendientes
			</td>
			<td class='prod rounded'   onClick='filterByState(1)' >
				Aceptadas
			</td>
			
			<td class='prod rounded' onClick='filterByState(2)' >
				Rechazadas
			</td>		
			
			<td class='prod rounded' onClick='filterByState(3)' >
				En transito
			</td>				
			
			<td class='prod rounded'  onClick='filterByState(4)' >
				Emarques recibidos
			</td>			
			<td class='prod rounded'  onClick='filterByState(5)' >
				Eliminadas
			</td>
			<td class='prod rounded' onClick='filterByState(6)' >
				Aplicada
			</td>						
		</tr>
	</table>
</div>


<script type="text/javascript" charset="utf-8">
	function filterByState( s ){
		
		if(s==-1){
			store_para_autorizaciones.clearFilter();			
			return;
		}
		
		store_para_autorizaciones.filter([
			{
				property     : 'estado',
				value        : s
			}
		]);
	}
	
	
	
</script>
<br>
<h2>Mostrando las ultimas autorizaciones</h2><?php


##########################################################################



function renderSucursal($sucID){
    $foo = SucursalDAO::getByPK($sucID);
    if($foo)
        return $foo->getDescripcion();
    else
        return "";
}

function renderParam( $json ){
    $obj = json_decode($json);

    return $obj->descripcion;
}

function renderUsuario ($usr){
    $foo = UsuarioDAO::getByPK($usr);
    if($foo)
        return $foo->getNombre();
    else
        return "";
}

?>
<script>
		var autorizaciones = [];

		var store_para_autorizaciones =  new Ext.data.ArrayStore({
	        fields: [
					{ name : 'id_autorizacion', 		type : 'int' },
					{ name : 'fecha_peticion', 			type : 'date', dateFormat: 'Y-m-d H:i:s' },
					{ name : 'estado', 					type : 'int' },
					{ name : 'id_usuario', 				type : 'string' },
					{ name : 'parametros', 				type : 'string' },
					{ name : 'id_sucursal', 			type : 'string' }
				]
			});
			
<?php

foreach($autorizaciones as $c){
	?>
	autorizaciones.push([
			<?php echo $c->getIdAutorizacion(); ?>,
			"<?php echo $c->getFechaPeticion(); ?>",
			<?php echo $c->getEstado(); ?>,
			"<?php echo renderUsuario($c->getIdUsuario()); ?>",
			"<?php echo renderParam($c->getParametros()); ?>",
			"<?php echo renderSucursal($c->getIdSucursal()); ?>"
		]);

	<?php
}

?>




		Ext.onReady(function(){
		    Ext.QuickTips.init();
		    store_para_autorizaciones.loadData(autorizaciones);
			// create the Grid
		    var tabla_autorizaciones = new Ext.grid.GridPanel({
		        store: store_para_autorizaciones,
				header : false,
		        columns: [
			        {
		                header   : 'Fecha', 
		                width    : 95, 
		                sortable : true, 
		                renderer : Ext.util.Format.dateRenderer('d/m/Y H:i'),  
		                dataIndex: 'fecha_peticion'
		            },
		            {
		                header   : 'Autorizacion', 
		                width    : 75, 
		                sortable : true, 
		                dataIndex: 'id_autorizacion'
		            },	
		            {
		                header   : 'Sucursal', 
		                width    : 150, 
		                sortable : true, 
		                dataIndex: 'id_sucursal'
		            },		
		            {
		                header   : 'Estado actual', 
		                width    : 150, 
		                sortable : true, 
		                dataIndex: 'estado',
						renderer : function (edo){

							    switch( edo ){
							        case 0 : return "<div style='color:red'>Pendiente</div>";
							        case 1 : return "Aceptada";
							        case 2 : return "Rechazada";
							        case 3 : return "En transito";
							        case 4 : return "<div style='color:green'>Embarque recibido</div>";
							        case 5 : return "<div style='color:red'>Eliminada</div>";
							        case 6 : return "<div style='color:green'>Aplicada</div>";
							        default : return "Indefinido {$edo}";
								}
								
						}
		            },
		            {
      					header   : 'Usuario', 
		                width    : 165, 
		                sortable : true, 
		                dataIndex: 'id_usuario'
		            },
		            {
		                header   : 'Descripcion', 
		                width    : 145, 
		                sortable : true, 
		                dataIndex: 'parametros'
		            }],
			        stripeRows: false,
			        //autoExpandColumn: 'total',
			        height: 1500,
					minHeight : 300,
			        width: "100%",
			        stateful: false,
			        stateId: 'sucursales_autorizaciones_cookie',
					listeners : {
						"rowclick" : function (grid, rowIndex, e){
							
							var datos = grid.getStore().getAt( rowIndex );
						 	window.location = "autorizaciones.php?action=detalle&id=" + datos.get("id_autorizacion" );
						}
					}

			    });
			tabla_autorizaciones.render("tabla_autorizaciones_holder");
		});


</script>

<div id="tabla_autorizaciones_holder" style="padding: 5px;">
</div>

<?php
##########################################################################
 

$header = array(
           "id_autorizacion" => "ID",
           "fecha_peticion" => "Fecha",
           "estado" => "Estado",
           "id_usuario" => "Usuario",
           "parametros" => "Descripcion",
           "id_sucursal" => "Sucursal" );




$tabla = new Tabla($header, $autorizaciones );
$tabla->addColRender("parametros", "renderParam");
$tabla->addColRender("estado", "renderEstado");
$tabla->addColRender("fecha_peticion", "toDate");
$tabla->addColRender("id_sucursal", "renderSucursal");
$tabla->addColRender("id_usuario", "renderUsuario");
$tabla->addOnClick("id_autorizacion", "detalle");
$tabla->addNoData("No hay autorizaciones pendientes");
//$tabla->render();




?>
<script>
    function detalle(id)
    {
        window.location = "autorizaciones.php?action=detalle&id=" + id;
    }
</script>
<style>
table{
	font-size: 12px;;
}
</style>
