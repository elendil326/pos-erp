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

				console.log("parsed response", response)
				
				if( response == null ){
					return;
				}
				
				if(	auths_hash != response.hash){
					
					notification();					
				}

				
				auths_hash = response.hash;
				
            }catch(e){
				console.log("failed to parse", e)
            }
        	
		}
	});


};


setInterval("heartbeat()", 5000);
