window.webkitNotifications.requestPermission();

function notification(){
	if (window.webkitNotifications.checkPermission() == 0) {
	  // you can pass any url as a parameter
	  window.webkitNotifications.createNotification("", "Autorizaciones", "Usted tiene nuevas autorizaciones por atender").show();
	} else {
	  window.webkitNotifications.requestPermission();
	}	
	
}


var auths_hash = null;

function heartbeat(){

    jQuery.ajaxSettings.traditional = true;

	jQuery.ajax({
		url: "../proxy.php",
		data: { 
			action : 207,
			hashCheck : auths_hash
		},
		cache: false,
		success: function(data){ 
		    try{
                response = jQuery.parseJSON(data);

				//console.log("parsed response", response)
				
				if( response == null ){
					return;
				}
				
				if(	(auths_hash != null) && (auths_hash != response.hash)){
					
					notification();					
				}

				
				auths_hash = response.hash;
				
            }catch(e){
				//console.log("failed to parse", e)
            }
        	
		}
	});


};


setInterval("heartbeat()", 15000);

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








function tr(s, o){
    if(o){
        return "<tr "+o+">"+s+"</tr>";  
    }else{
        return "<tr >"+s+"</tr>";   
    }
}

function td(s, o){
    if(o){
        return "<td "+o+">"+s+"</td>";
    }else{
        return "<td >"+s+"</td>";
    }
}

function div(s, o){
    if(o){
        return "<div "+o+">"+s+"</div>";
    }else{
        return "<div >"+s+"</div>";
    }


}

