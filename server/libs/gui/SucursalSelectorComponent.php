<?php

class SucursalSelectorComponent implements GuiComponent{
	
	private $_js_callback;
	
	public function addJsCallback($_js_callback){
		$this->_js_callback = $_js_callback;
	}
	
	
	public function renderCmp(){
		?>
		<script>

		
		Ext.define("Sucursal", {
	        extend: 'Ext.data.Model',
	        proxy: {
	            type: 'ajax',
				url : '../api/sucursal/buscar/',
				extraParams : {
					auth_token : Ext.util.Cookies.get("a_t")
				},
	            reader: {
	                type: 'json',
	                root: 'resultados',
	                totalProperty: 'numero_de_resultados'
	            }
	        },

	        fields: [
				{name: 'activa',		 		mapping: 'activa'},
				{name: 'descripcion',		 	mapping: 'descripcion'},
				{name: 'fecha_apertura',		mapping: 'fecha_apertura'},
				{name: 'fecha_baja',		 	mapping: 'fecha_baja'},
				{name: 'id_direccion',		 	mapping: 'id_direccion'},
				{name: 'id_gerente',		 	mapping: 'id_gerente'},
				{name: 'id_sucursal',		 	mapping: 'id_sucursal'},
				{name: 'razon_social',		 	mapping: 'razon_social'},
				{name: 'rfc',		 			mapping: 'rfc'},
				{name: 'saldo_a_favor',		 	mapping: 'saldo_a_favor'},
	        ]
	    });

		var Sucursal = {
			
			
			
			sucursales_store : Ext.create('Ext.data.Store', {
			    model: 'Sucursal'
			}),
			
			selected 	  : null, 
			
			show_selector : function( ){

				Ext.widget('window', {
	                title: 			'Buscar sucursal',
	                closeAction: 	'hide',
	                width: 			400,
	                height: 		150,
	                minHeight: 		150,
	                layout: 		'fit',
	                resizable: 		false,
	                modal: 			true,
	
	                items: Ext.widget('form', {
			                layout: {
			                    type: 'vbox',
			                    align: 'stretch'
			                },
			                border: false,
			                bodyPadding: 10,

			                fieldDefaults: {
			                    labelAlign: 'top',
			                    labelWidth: 100,
			                    labelStyle: 'font-weight:bold'
			                },
			                defaults: {
			                    margins: '0 0 10 0'
			                },

			                items: [{
								fieldLabel: 'Seleccione una sucursal',
								xtype: 'combobox',
							    displayField: 'descripcion',
							    valueField: 'id_sucursal',
							    width: 500,
							    labelWidth: 130,
							    store: Sucursal.sucursales_store,
							    editable: false,
								listeners: {
									change : function(a){

										var index = Sucursal.sucursales_store.findExact( "id_sucursal", a.getValue() );
										Sucursal.selected = Sucursal.sucursales_store.getAt( index );
									
									}
								}
							}],

			                buttons: [{
			                    text: 'Cancelar',
			                    handler: function() {
			                        this.up('window').hide();
			                    }
			                }, {
			                    text: 'Seleccionar esta sucursal',
			                    handler: function() {
									this.up('window').hide();
									Ext.get("SucursalSelectorComponent_Buscar").update("Seleccionar otra sucursal");
									Ext.get("SucursalSelectorComponent_Result").show();
									
									var info = "";
									info += "<p>" + Sucursal.selected.get("descripcion") + "</p>";
									
									Ext.get("SucursalSelectorComponent_ResultData").update(info);
									
									<?php echo $this->_js_callback . ".call(null, Sucursal.selected);"; ?>
			                    }
			                }]
			            })
	            }).show();
			}


		};
		</script>

		<div id="SucursalSelectorComponent_Result" >
			<div id="SucursalSelectorComponent_ResultData"></div>
			<!--<div class="POS Boton" id="SucursalSelectorComponent_Buscar" onClick="Sucursal.show_selector()">Seleccionar otra sucursal</div>-->
		</div>
		<div class="POS Boton" id="SucursalSelectorComponent_Buscar" onClick="Sucursal.show_selector()">Seleccionar sucursal</div>		
		<?php
	}
	
	
	
}