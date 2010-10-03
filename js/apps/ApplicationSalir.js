ApplicationSalir = function (){
	
	this.appName = "Salir";
	
};


ApplicationSalir.prototype.mainCard = null;

ApplicationSalir.prototype.appName = null;
/*
//boton de cerrar sesion
this.salirButton = new Ext.Button({
    text: 'Salir',
    ui: 'action',
    hidden: true,
    handler: this.onSalirButtonTap,
    scope: this
});
onSalirButtonTap : function () {
	
	POS.AJAXandDECODE(
			//params
			{
				action : '2002'
			},
			//sucess
			function ( response ){
				window.location = "./";
			},
			//failure
			function (){
				
			}
		);
}*/

//autoinstalar esta applicacion
AppInstaller( new ApplicationSalir() );



