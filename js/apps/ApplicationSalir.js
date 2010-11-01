ApplicationSalir = function (){
	
	this.appName = "Salir";
	
	
	//panel principal    
    this.mainCard = this.venderMainPanel;
};


ApplicationSalir.prototype.mainCard = null;

ApplicationSalir.prototype.appName = null;



ApplicationSalir.doSalir = function(){
	
    POS.AJAXandDECODE({
            action: '2002'
        }, 
        function (datos){
			window.location = ".";
        },
        function (e){
            Ext.Msg.alert("Error", "Algo anda mal, porfavor intente de nuevo.");

            if(DEBUG){
                console.log(e);
            }
        }
    );
	
};

ApplicationSalir.prototype.venderMainPanel = new Ext.Panel({
    scroll: 'none',
    dockedItems: null,
    cls: "ApplicationVender-mainPanel",
    
    //items del formpanel
    items: [
			{   xtype: 'spacer' } ,
			{ 	maxWidth: 100, xtype: "button", pack: "center", text: 'Action', ui: 'action' , handler : ApplicationSalir.doSalir },
            {   xtype: 'spacer' }            
        ]
});

//autoinstalar esta applicacion
AppInstaller( new ApplicationSalir() );



