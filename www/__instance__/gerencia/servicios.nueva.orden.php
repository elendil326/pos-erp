<?php
define("BYPASS_INSTANCE_CHECK", false);

require_once("../../../server/bootstrap.php");

$page = new GerenciaComponentPage();


//titulos
$page->addComponent(new TitleComponent("Nueva orden de servicio"));


$csel = new ClienteSelectorComponent();

$csel->addJsCallback("asignaCliente");

$page->addComponent($csel);

$page->partialRender();
?>
<div id="clienteInfo"></div>
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
            <td><label>Descripci&oacute;n</label></td>
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


	function renderClienteInfo(record){
		
		if(record.get("rfc") == null){
			rfc = "";
		}else{
			rfc = record.get("rfc");
		}
		
		html = "";
		
		html += "<table>"
		html += "<tr>"
		html += "<td colspan=2><h3>"+ record.get("nombre") +"</h3>"+ rfc +"</td>"
		html += "</tr>"
		html += "<tr>"
		html += "<td>Limite de credito:</td><td>"+ record.get("limite_credito") +"</td>"
		html += "</tr>"
		html += "<tr>"
		html += "<td>Saldo Disponible</td><td>"+ record.get("saldo_del_ejercicio") +"</td>"
		html += "</tr>"
		html += "</table>"
		
		return html;
	}
	
	/*
		limite_credito,
		nombre,
		id_usuario,
		id_tarifa_venta,
		fecha_alta,
		rfc,
		denominacion_comercial,
		activo,
		saldo_del_ejercicio,
		ventas_a_credito
	*/

	
    function asignaCliente(record){
		console.log("cliente seleccionado" , record);
		Ext.get("clienteInfo").update(renderClienteInfo(record));
        Ext.get('id_cliente').dom.value = record.get('id_usuario');   
    }


	var CurrentExtraParams = null;
	
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
			CurrentExtraParams = null;
			
		}else{

			
			try{
				CurrentExtraParams = Ext.JSON.decode( SERVICIOS[ option[0] ].extra_params);			
			}catch(e){
				console.error(e);
				CurrentExtraParams = null;
			}
			
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
			if(obj[i].obligatory){
				html += "<tr><td><b>*" + obj[i].desc + "</b></td><td>" + input + "</td></tr>";				
			}else{
				html += "<tr><td>" + obj[i].desc + "</td><td>" + input + "</td></tr>";				
			}

		};
		
		html += "</table>";
		
		return html;

	}



    function nuevaOrdenServicio(){
		
		//ver si esta chica tiene suficiente limite de credito para este pedo
		
    
        var option = Ext.get('id_servicio').dom.options[Ext.get('id_servicio').dom.options.selectedIndex].value.split("-");

        var fecha = fecha_entrega.getRawValue().split("/");
		
		//Vamos a ver cuales de los extraParams son obligatorios
		//y cuales llenaron
		
		var ep;
		
		try{
			ep = Ext.JSON.decode(getExtraParams());
			
		}catch(e){
			console.error(e);
			ep = null;
		}
		
		console.log(CurrentExtraParams, ep);
		
		
		var faltan = false;
		
		//busquemos solo los obligatorios
		for (var i=0; i < CurrentExtraParams.length; i++) {
			if(CurrentExtraParams[i].obligatory){
				//buscarlo en ep, ya que es obligatorio
				for (var j = ep.length - 1; j >= 0; j--){
					if(ep[j].desc == CurrentExtraParams[i].desc){
						if(ep[j].value.length == 0){
							faltan = true;
						}
						break;
					}
				};
			}
		};
		
		
		
		
		if(faltan){
			alert("te faltan parametros");
			return;			
		}

		if(Ext.get('id_cliente').getValue().length == 0){
			alert("No haz seleccionado a que cliente deseas ofrecerle el servicio");
			return;
		}
		
        POS.API.POST(
        "api/servicios/orden/nueva/", 
        {
            "id_cliente" 	: Ext.get('id_cliente').getValue(),
            "id_servicio" 	: option[0] ,
            "adelanto" 		: Ext.get('adelanto').dom.value,
            "descripcion" 	: Ext.get('descripcion').getValue(),
            "fecha_entrega" : Math.round( (new Date( fecha[2], fecha[0], fecha[1] )).getTime() / 1000),
            "precio" 		: Ext.get('precio').getValue(),
			"extra_params" 	: getExtraParams()
        }, 
        {
            callback : function(a){ 
                window.onbeforeunload = function(){}
                window.location = "servicios.detalle.orden.php?oid=" + a.id_orden; 
            }
        }
    );
    }
    


</script>

<?php


//render the page
$page->render();
