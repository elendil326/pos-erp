
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







Mosaico.prototype.createHtml = function ()
{
	var wrapper = document.createElement('div');
	wrapper.setAttribute('id', this.uniqueID );
	wrapper.setAttribute('class', 'mosaico-wrapper');

	document.getElementById( this.config.renderTo ).appendChild(wrapper);

	var item, title, image;

	for( a = 0; a < this.config.items.length; a++ ){

		item = document.createElement('div');
		item.setAttribute('id', 'mosaico-item-' + a );
		item.setAttribute('class', 'mosaico-item');
		item.onclick = function( a, b){
			console.log("WAAA" , a , b )
		};
		
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

			
