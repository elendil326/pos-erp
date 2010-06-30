
/* helpers */
String.prototype.startsWith = function(str){return (this.match("^"+str)==str)}
Array.prototype.has=function(v){for (i=0;i<this.length;i++){if (this[i]==v) return true;}return false;}

Mosaico = function ( config )
{
	this.uniqueID = "mosaic-" + parseInt( Math.random() * 1000 ) ;

	this.config = config;

	this.createHtml();

	return this;
};

Mosaico.prototype.uniqueID = null;

Mosaico.prototype.config = null;

Mosaico.prototype.destroy = function ()
{
	document.getElementById(this.uniqueID).parentNode.removeChild(document.getElementById(this.uniqueID));
};

Mosaico.prototype.doSearch = function ( string )
{

	if(string.length == 0){
		this.doShadow([]);
		return;
	}

	var coincidencias = [];

	for(a = 0; a < this.config.items.length; a++)
	{
	
		if(this.config.items[a].title.startsWith(string) ){
			coincidencias.push(a);
			continue;
		}
	
		if(this.config.items[a].keywords == null){ 
			continue; 
		}
	
		for(k=0; k < this.config.items[a].keywords.length; k++)
		{
			if(this.config.items[a].keywords[k].startsWith(string) ){
				coincidencias.push(a);
				break;
			}
		}
	}


	this.doShadow( coincidencias );
};


Mosaico.prototype.doShadow = function ( ids )
{
	
	var wrapper = document.getElementById(this.uniqueID);

	if(ids.length == 0){
		wrapper.setAttribute('style', 'background-image:url(media/g2.png);'  );
	}else{
		wrapper.setAttribute('style', 'background-image:url(media/g1.png);'  );
	}

	for(a = 0; a < this.config.items.length; a++){

		if( ids.has(a) ){
		
			var el = document.getElementById( 'mosaico-item-' + a );
			el.setAttribute('class', 'mosaico-item-selected'  );
		
		}else{
		
			var el = document.getElementById( 'mosaico-item-' + a );
			el.setAttribute('class', 'mosaico-item'  );
	
		}
	}

};




Mosaico.prototype.createHtml = function ()
{
	var wrapper = document.createElement('div');
	wrapper.setAttribute('id', this.uniqueID );
	wrapper.setAttribute('class', 'mosaico-wrapper');

	document.getElementById(this.config.renderTo).appendChild(wrapper);

	var item, title, image;

	for( a = 0; a < this.config.items.length; a++ ){

		item = document.createElement('div');
		item.setAttribute('id', 'mosaico-item-' + a );
		item.setAttribute('class', 'mosaico-item');
		wrapper.appendChild(item);


		image = document.createElement('div');
		image.setAttribute('style', 'background: url('+this.config.items[a].image+') no-repeat;' );
		image.setAttribute('class', 'mosaico-image');
		item.appendChild(image);
	
		title = document.createElement('div');
		title.innerHTML = this.config.items[a].title;
		title.setAttribute('class', 'mosaico-title');
		item.appendChild(title);				
	
	}

};

			

/* 

Example of usage 
<body>
	
	<div id="test" style="width: 500px; height: 500px; ">
	</div>
	<input type="text" value="" onkeyup="m.doSearch(this.value)">

	<script>
	var m = new Mosaico({
		renderTo : 'test',
		items: [{ 
				title: 'norte',
				image: 'media/truck.png',
				keywords: [ 'f', 'g']
			},{
				title: 'pino suarez',
				image: 'media/truck.png',
				keywords: [ 'h','i']
			},{ 
				title: 'pinos',
				image: 'media/truck.png',
				keywords: []
			},{
				title: 'leon',
				image: 'media/truck.png'
			}]
	});
	</script>
</body>
*/