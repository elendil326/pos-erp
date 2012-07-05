<?php

class EmpresaSelectorComponent implements GuiComponent{
	
	private $_js_callback;
	
	public function addJsCallback($_js_callback){
		$this->_js_callback = $_js_callback;
	}
	
	
	public function renderCmp(){
		?>
		<script>

		
		Ext.define("EmpresaModel", {
	        extend: 'Ext.data.Model',
	        proxy: {
	            type: 'ajax',
				url : '../api/empresa/buscar/',
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
				{name: 'razon_social',		 	mapping: 'razon_social'},
				{name: 'fecha_apertura',		mapping: 'fecha_apertura'},
				{name: 'id_empresa',			mapping: 'id_empresa'},				
				{name: 'fecha_baja',		 	mapping: 'fecha_baja'},
				{name: 'id_direccion',		 	mapping: 'id_direccion'},
				{name: 'id_gerente',		 	mapping: 'id_gerente'},
				{name: 'razon_social',		 	mapping: 'razon_social'},
				{name: 'rfc',		 			mapping: 'rfc'},
				{name: 'saldo_a_favor',		 	mapping: 'saldo_a_favor'},
	        ]
	    });

		var EmpresaSelector = {
			
			
			
			empresa_store : Ext.create('Ext.data.Store', {
			    model: 'EmpresaModel'
			}),
			
			selected 	  : null, 
			
			show_selector : function( ){

				Ext.widget('window', {
	                title: 			'Buscar empresa',
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
								fieldLabel: 'Seleccione una empresa',
								xtype: 'combobox',
							    displayField: 'razon_social',
							    valueField: 'id_empresa',
							    width: 500,
							    labelWidth: 130,
							    store: EmpresaSelector.empresa_store,
							    editable: false,
								listeners: {
									change : function(a){

										var index = EmpresaSelector.empresa_store.findExact( "id_empresa", a.getValue() );
										EmpresaSelector.selected = EmpresaSelector.empresa_store.getAt( index );
									
									}
								}
							}],

			                buttons: [{
			                    text: 'Cancelar',
			                    handler: function() {
			                        this.up('window').hide();
			                    }
			                }, {
			                    text: 'Seleccionar esta empresa',
			                    handler: function() {
									this.up('window').hide();
									Ext.get("EmpresaSelectorComponent_Buscar").update("Seleccionar otra empresa");
									Ext.get("EmpresaSelectorComponent_Result").show();
									
									var info = "";
									info += "<p>" + EmpresaSelector.selected.get("razon_social") + "</p>";
									
									Ext.get("EmpresaSelectorComponent_ResultData").update(info);
									
									<?php echo $this->_js_callback . ".call(null, EmpresaSelector.selected);"; ?>
			                    }
			                }]
			            })
	            }).show();
			}


		};
		</script>

		<div id="EmpresaSelectorComponent_Result" >
			<div id="EmpresaSelectorComponent_ResultData"></div>
			<!--<div class="POS Boton" id="EmpresaSelectorComponent_Buscar" onClick="Sucursal.show_selector()">Seleccionar otra sucursal</div>-->
		</div>
		<div class="POS Boton" id="EmpresaSelectorComponent_Buscar" onClick="EmpresaSelector.show_selector()">Seleccionar empresa</div>		
		<?php
	}
	
	
	
}