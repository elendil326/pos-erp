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



	Ext.onReady(function(){



		/**
		  *
		  *
		  **/
	    Ext.define("Post", {
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
	            {name: 'id', mapping: 'id_usuario'},
	            {name: 'title', mapping: 'nombre'},
	            {name: 'topicId', mapping: 'rfc'},
	            {name: 'lastPost', mapping: 'fecha_alta', type: 'date', dateFormat: 'timestamp'}
	        ]
	    });
	
	
	
	

	    ds = Ext.create('Ext.data.Store', {
	        pageSize: 10,
	        model: 'Post'
	    });






		/**
		  *
		  *
		  **/
	    Ext.create('Ext.panel.Panel', {
	        renderTo: "ShoppingCartComponent_002",
	        title: '',
	        width: '100%',
	        bodyPadding: 10,
	        layout: 'anchor',

	        items: [{
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
		                    return '<p>{title}</p>';
		                }
	            },
	            pageSize: 10
	        }]
	    });/* Ext.create */
		
		
		
		
		
		
		
		/**
		  *
		  *
		  **/
	    Ext.create('Ext.panel.Panel', {
	        renderTo: "ShoppingCartComponent_001",
	        title: '',
	        width: '100%',
	        bodyPadding: 10,
	        layout: 'anchor',

	        items: [{
	            xtype: 'combo',
	            store: ds,
	            displayField: 'title',
	            typeAhead: true,
	            hideLabel: true,
	            hideTrigger:false,
	            anchor: '100%',

	            listConfig: {
	                loadingText: 'Buscando...',
	                emptyText: 'No se encontraron productos.',

	                // Custom rendering template for each item
	                getInnerTpl: function() {
	                    return '<a class="search-item" href="">' +
	                        '<h3><span>hola<br />by {title}</span>{title}</h3>' +
	                        'ok' +
	                    '</a>';
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
			

			<p style="margin-bottom: 0px;">Buscar cliente</p>
			<div style="margin-bottom: 15px;" id="ShoppingCartComponent_002"><!-- clientes --></div>	

			<p style="margin-bottom: 0px;">Buscar productos</p>				
			<div id="ShoppingCartComponent_001"><!-- buscar productos --></div>
		<?php
	}
}