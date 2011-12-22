<?php


/**
 * ShoppingCartComponent
 * 
 * ShoppingCartComponent es un componente para crear carros de compras
 * facilmente, incluye una caja de busqueda para seleccionar productos
 * o bien.
 *
 * 
 * Hay unas cuantas cosas a considerar:
 * Hay que seleccionar un cliente o una caja comun.
 * Los productos pueden ser computestos, servicios, u otras cosas.
 * Hay otras caracteristicas como "PAPA FIANA" en los productos.
 * Considerar el promedio, o procesadas y originales y todo eso.
 * Manejar Remisiones.
 * Fecha.
 * Cambio de precios.
 * 
 * 
 * 
 * 
 * */
class ShoppingCartComponent implements GuiComponent {

	/**
	 * 
	 * 
	 * 
	 * */
	

	function __construct(){
		
	}

	

	function renderCmp()
	{
		?>
<script>

	Ext.require([
	    'Ext.data.*',
	    'Ext.form.*'
	]);


	var cliente_seleccionado;
	var seleccion_de_cliente = function(a,c){
		cliente_seleccionado = c[0];
		console.log("Cliente seleccionado", cliente_seleccionado);
		
		Ext.get("buscar_cliente_01").enableDisplayMode('block').hide();
		var pphtml = "<h2 style='margin-bottom:0px'>Venta para <a href='clientes.ver.php?cid="+cliente_seleccionado.get("id_cliente")+"'>" + cliente_seleccionado.get("nombre") + "</a></h2>"
			+ "<p>" + cliente_seleccionado.get("rfc") + "</p>"
			+ "<div class='POS Boton' onClick='buscar_cliente()'  >Buscar otro cliente</div>"
		
		Ext.get("buscar_cliente_02").update(pphtml).show();
	};
	
	var buscar_cliente = function(){
		Ext.get("buscar_cliente_02").enableDisplayMode('block').hide();
		Ext.get("buscar_cliente_01").show();

	}
	
	var productos_en_carrito = [];
	var seleccionar_producto = function( a, p ){
		console.log( "seleccionando producto", p );
	}
	
	Ext.onReady(function(){



		/**
		  *
		  *
		  **/
	    Ext.define("Cliente", {
	        extend: 'Ext.data.Model',
	        proxy: {
	            type: 'ajax',
				url : '../api/cliente/buscar/',
	            reader: {
	                type: 'json',
	                root: 'resultados',
	                totalProperty: 'numero_de_resultados'
	            }
	        },

	        fields: [
	            {name: 'id_cliente', mapping: 'id_usuario'},
	            {name: 'nombre', mapping: 'nombre'},
	            {name: 'rfc', mapping: 'rfc'},
	            {name: 'fecha_alta', mapping: 'fecha_alta', type: 'date', dateFormat: 'timestamp'}
	        ]
	    });
	
	    ds = Ext.create('Ext.data.Store', {
	        pageSize: 10,
	        model: 'Cliente'
	    });

	    Ext.create('Ext.panel.Panel', {
	        renderTo: "ShoppingCartComponent_002",
	        title: '',
	        width: '100%',
	        bodyPadding: 10,
	        layout: 'anchor',

	        items: [{
				listeners :{
					"select" : seleccion_de_cliente
				},
	            xtype: 'combo',
	            store: ds,
	            displayField: 'title',
	            typeAhead: true,
	            hideLabel: true,
	            hideTrigger:false,
	            anchor: '100%',

	            listConfig: {
	                loadingText: 'Buscando...',
	                emptyText: 'No se encontraron clientes.',

	                // Custom rendering template for each item
	                 getInnerTpl: function() {
		                    return '<p>{nombre}</p>{rfc}';
		                }
	            },
	            pageSize: 10
	        }]
	    });/* Ext.create */
		
		
		

		/**
		  *
		  *
		  **/
	    Ext.define("Producto", {
	        extend: 'Ext.data.Model',
	        proxy: {
	            type: 'ajax',
				url : '../api/producto/buscar/',
	            reader: {
	                type: 'json',
	                root: 'resultados',
	                totalProperty: 'numero_de_resultados'
	            }
	        },

	        fields: [
	            {name: 'id_producto', mapping: 'id_producto'},
	            {name: 'nombre_producto', mapping: 'nombre_producto'}
	        ]
	    });
	
	    pdts = Ext.create('Ext.data.Store', {
	        pageSize: 10,
	        model: 'Producto'
	    });		
		
	    Ext.create('Ext.panel.Panel', {
	        renderTo: "ShoppingCartComponent_001",
	        title: '',
	        width: '100%',
	        bodyPadding: 10,
	        layout: 'anchor',

	        items: [{
	            xtype: 'combo',
	            store: pdts,
	            displayField: 'title',
	            typeAhead: true,
	            hideLabel: true,
	            hideTrigger:false,
	            anchor: '100%',
				listeners :{
					"select" : seleccionar_producto
				},
	            listConfig: {
	                loadingText: 'Buscando...',
	                emptyText: 'No se encontraron productos.',

	                // Custom rendering template for each item
	                getInnerTpl: function() {
	                    return '<h3>{nombre_producto}-{id_producto}</h3>';
	                }
	            },
	            pageSize: 10
	        }, {
	            xtype: 'component',
	            style: 'margin-top:10px',
	            html: 'Buscando por descripcion, nombre o codigo de barras.'
	        }]
	    });/* Ext.create */
	}); /* Ext.onReady */
		

</script>

			<div id="buscar_cliente_01">
				<p style="margin-bottom: 0px;">Buscar cliente</p>
				<div style="margin-bottom: 15px;" id="ShoppingCartComponent_002"><!-- clientes --></div>				
			</div>
			
			<div id="buscar_cliente_02" style="display:none; margin-bottom: 10px">
			</div>


			<p style="margin-bottom: 0px;">Buscar productos</p>				
			<div id="ShoppingCartComponent_001"><!-- buscar productos --></div>
			
			
			<div id="carrito_de_compras">
				
			</div>
		<?php
	}
}