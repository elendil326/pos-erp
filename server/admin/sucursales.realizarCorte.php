<?php

	require_once("model/sucursal.dao.php");
	require_once("controller/clientes.controller.php");
?>

<h2>Corte para sucursal</h2>



<h4 >
	<div class="hide_on_ajax">
		<input type="button"  value="realizar corte" onclick="sendCorte()">
	</div>
    <div id="loader" 		style="display: none;" align="center"  >
		Realizando venta <img src="../media/loader.gif">
    </div>
</h4>

<script type="text/javascript" charset="utf-8">
function sendCorte(){
	
	jQuery(".hide_on_ajax").fadeOut("fast", function(){
		jQuery("#loader").fadeIn();		
		jQuery.ajax({
	            url: "../proxy.php",
	            data: { 
	                action : 708, 
	                id_sucursal : <?php echo $_GET["id_sucursal"]?>,
	            },
	            cache: false,
	            success: function(data){

	                try{
	                    response = jQuery.parseJSON(data);
	                    //console.log(response, data.responseText)
	                }catch(e){

	                    jQuery("#loader").fadeOut('slow', function(){
	                        window.scroll(0,0);                         
	                        jQuery("#ajax_failure").html("Error en el servidor, porfavor intente de nuevo").show();
	                        jQuery(".hide_on_ajax").fadeIn();
	                    });                
	                    return;                    
	                }

                    jQuery("#loader").fadeOut('slow', function(){
                        jQuery(".hide_on_ajax").fadeIn();
                    });


	                if(response.success === false){

	                    if(response.reason){
	                        jQuery("#ajax_failure").html(response.reason).show();							
	                    }else{
	                        jQuery("#ajax_failure").html("Error en el servidor, porfavor intente de nuevo").show();
	                    }


	            		window.scroll(0,0);
	                    return ;
	                }


					console.log("OK !");

	            }
	        });
	});

	
	
}
</script>