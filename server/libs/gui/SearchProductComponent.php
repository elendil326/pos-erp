<?php


/**
 * SearchProductComponent
 * 
 * Un componente para buscar productos.
 * 
 * 
 * */
class SearchProductComponent implements GuiComponent{

	protected $js_callback;

	function __construct(   ){
		$this->js_callback = "console.log";		
	}

	public function setOnSelectedJsFunction($callback){
		$this->js_callback = $callback;
	}
	
	public function renderCmp(  ){
		?>
		
		<script>
		/** *****************************************************************
		  * PRODUCTOS
		  *
		  * ***************************************************************** */
	Ext.onReady(function(){		
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
					"select" : <?php /** **** ** */ echo $this->js_callback;  /** **** ** */?>
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
	});
		/** *****************************************************************
		  * /PRODUCTOS
		  *
		  * ***************************************************************** */
		</script>
		
		<p style="margin-bottom: 0px;">Buscar productos</p>				
		<div id="ShoppingCartComponent_001"><!-- buscar productos --></div>
		
		<?php
	}

}