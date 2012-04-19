<?php
define("BYPASS_INSTANCE_CHECK", false);

require_once("../../../../server/bootstrap.php");

$page = new GerenciaComponentPage();


//titulos
$page->addComponent(new TitleComponent("Nueva orden de servicio"));


$csel = new ClienteSelectorComponent();

$csel->addJsCallback("asignaCliente");

$page->addComponent($csel);

$page->partialRender();
?>
<script type="text/javascript" charset="utf-8">
	var SERVICIOS = [];
	<?php
	foreach (ServicioDAO::getAll() as $servicio) {
		echo "SERVICIOS[". $servicio->getIdServicio() ."] = $servicio;";
    }
	?>
</script>

<form name = "orden_servicio" id = "orden_servicio">
    <table width = 100% border = 0 >
        <tr>
            <td>
                <label>Servicio</label>
            </td>
            <td>
                <select name = "id_servicio" id = "id_servicio" onChange = "formatForm()" >
                    <?php
                    $options = "<option value = null>-------</option>";

                    foreach (ServicioDAO::getAll() as $servicio) {
                        $options .= "<option value = \"{$servicio->getIdServicio()}-{$servicio->getMetodoCosteo()}-{$servicio->getCostoEstandar()}-{$servicio->getPrecio()}\">{$servicio->getNombreServicio()}</option>";
                    }

                    echo $options;
                    ?>
                </select>
            </td>
            <td><label>Fecha Entrega</label></td>
            <td><div id = "render_date">&nbsp;</div></td>
        </tr>
        <tr>
            <td><label>Precio</label></td>
            <td><input type = "text" disabled = true name = "precio" id = "precio" value = "" /></td>
            <td><label>Adelanto</label></td>
            <td><input type = "text" name = "adelanto" id = "adelanto"value = "" /></td>
        <tr>
            <td><label>Descripci&oacuten</label></td>
            <td><textarea style = "width:100%; height:100%;" name = "descripcion" id = "descripcion"></textarea></td>
        </tr>
		<tr>
			<td id="extra_params_el" colspan=4></td>
		</tr>
        <tr>
            <td colspan = "4"  align="right" style = "border-width:0px; background:#EDEFF4;">
                <input class="POS Boton OK" style = "position:relative; float:right;" type = "button" value = "Aceptar" onClick = "nuevaOrdenServicio()" /> <input class="POS Boton" style = "position:relative; float:right;" type = "reset" value = "Cancelar" /> <input style = "display:none;" name = "id_cliente" id = "id_cliente" type = "hidden" value = ""/>
            </td>
        </tr>
    </table>
</form>

<script>

    var fecha_entrega = Ext.create('Ext.form.field.Date', {
        name : 'fecha_entrega',         
        style : {
            marginTop : '-10px'
        },
        anchor: '100%',
        name: 'fecha',
        value: new Date(),  // defaults to today           
        renderTo: "render_date"
    });

    function asignaCliente(record){
        Ext.get('id_cliente').dom.value = record.get('id_usuario');   
    }

    function formatForm(){

        Ext.get('precio').dom.disabled = true;
        Ext.get('precio').dom.value = "";

        var option = Ext.get('id_servicio').dom.options[Ext.get('id_servicio').dom.options.selectedIndex].value.split("-");

        if( option[1] == "variable" ){
            Ext.get('precio').dom.value = "";
            Ext.get('precio').dom.disabled = false;
        }

        if( option[1] == "costo" ){
            Ext.get('precio').dom.value = option[2];
        }

        if( option[1] == "precio" ){
            Ext.get('precio').dom.value = option[3];
        }


		if(SERVICIOS[ option[0] ].extra_params == null){
			Ext.get("extra_params_el").update("");
		}else{
			Ext.get("extra_params_el").update(buildExtraParams( SERVICIOS[ option[0] ].extra_params ));
		}

    }


	function getExtraParams(){
		
        var option = Ext.get('id_servicio').dom.options[Ext.get('id_servicio').dom.options.selectedIndex].value.split("-");
		
		if(SERVICIOS[ option[0] ].extra_params == null){
			return null;
		}
		
		var out = [ ];
		
		try{
			var obj = Ext.JSON.decode( SERVICIOS[ option[0] ].extra_params );	
					
		}catch(e){
			console.error(e);
			return null;
		}
		
		for (var i=0; i < obj.length; i++) {

			switch(obj[i].type){
				case "text": 
				case "textarea":
					out.push({
						desc : obj[i].desc,
						value: Ext.get("extra_params_"+ i).getValue()
					});

				break;
				
			}

		}
		
		return Ext.JSON.encode(out);
	}
	
	
	function buildExtraParams(paramsJson){
		try{
			var obj = Ext.JSON.decode(paramsJson);			
		}catch(e){
			console.error(e);
			return "";
		}

		var html = "<table width='100%'>";

		for (var i=0; i < obj.length; i++) {
			var input = "";
			switch(obj[i].type){
				case "text": 
					input = "<input id='extra_params_"+ i +"' type='text'>";
				break;
				
				case "textarea":
					input = "<textarea id='extra_params_"+ i +"'></textarea>";
				break;
				
			}
			html += "<tr><td>" + obj[i].desc + "</td><td>" + input + "</td></tr>";
		};
		
		html += "</table>";
		
		return html;

	}

    function nuevaOrdenServicio(){

    
        var option = Ext.get('id_servicio').dom.options[Ext.get('id_servicio').dom.options.selectedIndex].value.split("-");

        var fecha = fecha_entrega.getRawValue().split("/");

        POS.API.POST(
        "api/servicios/orden/nueva/", 
        {
            "id_cliente" 	: Ext.get('id_cliente').getValue(),
            "id_servicio" 	: option[0] ,
            "adelanto" 		: Ext.get('adelanto').dom.value,
            "descripcion" 	: Ext.get('descripcion').getValue(),
            "fecha_entrega" : Math.round((new Date( fecha[2], fecha[0], fecha[1] )).getTime() / 1000),
            "precio" 		: Ext.get('precio').getValue(),
			"extra_params" 	: getExtraParams()
        }, 
        {
            callback : function(a){ 
                window.onbeforeunload = function(){}
                //window.location = "servicios.lista.orden.php"; 

            }
        }
    );
    }
    


</script>

<?php
//forma de nueva orden de servicio
/* $form = new DAOFormComponent( array( new OrdenDeServicio() ) );


  $form->hideField( array(
  "id_orden_de_servicio",
  "id_usuario",
  "fecha_orden",
  "activa",
  "cancelada",
  "motivo_cancelacion"
  ));


  $form->renameField( array(
  "id_usuario_venta"    => "id_cliente"
  ));

  $form->addApiCall( "api/servicios/orden/nueva/" );
  $form->onApiCallSuccessRedirect("servicios.lista.orden.php");

  $form->makeObligatory(array(
  "id_cliente",
  "id_servicio"
  ));


  $form->createComboBoxJoin("id_servicio", "nombre_servicio", ServicioDAO::search( new Servicio( array("activo" => 1) ) ) );

  $clientes = $lista = ClientesController::Buscar();

  $form->createComboBoxJoinDistintName(
  "id_cliente",
  "id_usuario",
  "nombre",
  $clientes["resultados"]

  );

  $page->addComponent( $form ); */


//render the page
$page->render();
