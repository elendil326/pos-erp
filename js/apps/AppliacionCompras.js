ApplicationCompras = function ()
{
	if(DEBUG){
		console.log("ApplicationCompras: construyendo");
	}
	
	return this._init();
	
};


//variables de esta clase, NO USEN VARIABLES ESTATICAS A MENOS QUE SEA 100% NECESARIO

//aqui va el panel principal 
ApplicationCompras.prototype.mainCard = null;

//aqui va el nombre de la applicacion
ApplicationCompras.prototype.appName = null;

//aqui va el nombre de la applicacion
ApplicationCompras.prototype.leftMenuItems = null;

//aqui va un texto de ayuda en html
ApplicationCompras.prototype.ayuda = null;


ApplicationCompras.prototype._init = function()
{
	
	//iniciar variables
	
	//nombre de la aplicacion
	this.appName = "Compras";
	
	//ayuda sobre esta applicacion
	this.ayuda = "esto es una ayuda sobre este modulo de compras, html es valido <br> :D";
	
	//submenues en el panel de la izquierda
	this.leftMenuItems = [
	{
        text: 'menu1',
        ayuda: 'ayuda en menu 1'
    },
    {
        text: 'menu2',
       	//card: demos.Forms,
        ayuda: 'ayuda en menu2'
    }
	];
	
	//panel principal
	this.mainCard = new Ext.Panel({
		cls: 'card card1',
		html: 'Esto es html y va dentro del panel'
	});
	
	
};


//autoinstalar esta applicacion
AppInstaller( new ApplicationCompras() );
















/*
demos.Animations = new Ext.Panel({

})

demos.Animations.slide = new Ext.Panel({
	html: 'Slides can be used in tandem with <code>direction: "up/down/left/right"</code>.',
	cls: 'card card2'
});

demos.Animations.slidecover = new Ext.Panel({
	html: 'In addition to <code>direction</code>, slide can also accept <code>cover: true</code>',
	cls: 'card card3'
});

demos.Animations.slidereveal = new Ext.Panel({
	html: 'Then there&#8217;s <code>reveal: true</code>, which is opposite to <code>cover</code>',
	cls: 'card card4'
});

demos.Animations.pop = new Ext.Panel({
	html: 'Pop is another 2d animation that should work across iPhone OS &amp; Android.',
	cls: 'card card5'
});

demos.Animations.flip = new Ext.Panel({
	html: 'This one&#8217;s 3d and can also accept <code>direction: "up/down/left/right"</code>',
	cls: 'card card1'
});

demos.Animations.cube = new Ext.Panel({
	html: 'Crazy enough, this one does every <code>direction</code>, too.',
	cls: 'card card2'
});

demos.Animations.fade = new Ext.Panel({
	html: 'This one&#8217;s pretty straight-forward.',
	cls: 'card card3'
});
*/