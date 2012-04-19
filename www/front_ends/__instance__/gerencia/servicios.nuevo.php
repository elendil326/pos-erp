<?php 


	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();

	//titulos
	$page->addComponent( new TitleComponent( "Nuevo servicio" ,2 ) );
	$page->addComponent( "Crear un servicio aqui le permitira levantar ordenes de servicio." );
	//forma de nuevo servicio
	$form = new DAOFormComponent( array( new Servicio() ) );
	$form->beforeSend("foo");
	$form->hideField( array( "id_servicio", "extra_params", "foto_servicio"));

	$form->addApiCall( "api/servicios/nuevo/", "POST" );

	$form->onApiCallSuccessRedirect( "servicios.lista.php" );

	$form->makeObligatory(array( 
		"costo_estandar",
		"metodo_costeo",
		"nombre_servicio",
		"codigo_servicio",
		"compra_en_mostrador"
	));

	$form->createComboBoxJoin( "id_ciudad", "nombre", CiudadDAO::getAll( ) );

	$form->createComboBoxJoin( "metodo_costeo", "metodo_costeo", array("precio", "costo", "variable") );

	$form->createComboBoxJoin( "compra_en_mostrador", "compra_en_mostrador", array( array( "id" => 1 , "caption" => "si" ), 
	              array( "id" => 0 , "caption" => "no" ) ), 1 );
	
	$form->createComboBoxJoin( "activo", "activo", array( array( "id" => 1 , "caption" => "si" ), 
	              array( "id" => 0 , "caption" => "no" ) ), 1 );
	
	$form->setType("descripcion_servicio", "textarea");
	
	$form->sortOrder(array( 
			"foto_servicio", 
			"codigo_servicio", 
			"compra_en_mostrador",
			"control_existencia",
			"costo_estandar",
			"descripcion_servicio",
			"precio",
			"garantia",
			"id_servicio",
			"metodo_costeo",
			"nombre_servicio",
			"activo",
			"extra_params"
		));
		
	$page->addComponent( $form );

	$page->addComponent(new TitleComponent("&iquest; Necesita mas parametros para su servicio ?", 2));
	$page->addComponent("Si necesita mas datos para levantar ordenes de servicio, agregue sus parametros extra aqui.");
	
	$page->partialRender();
	?>
	<div id="editor-grid" style="margin-top: 5px"></div>
	<script type="text/javascript" charset="utf-8">
		
		var extraParamsStore;
		
		function foo(o){
			o.extra_params = getParams();
			return o;
		}
		
		function getParams(){
			var c = extraParamsStore.getCount(),
				out = [];
				
			for (var i=0; i < c; i++) {
				var o = extraParamsStore.getAt(i);
				out.push({
					desc : o.get("desc"),
					type : o.get("type"),
					obligatory : o.get("obligatory")
				});
			};
			
			return Ext.JSON.encode(out);

		}
		
		Ext.onReady(function(){
		    // Define our data model
		    Ext.define('ExtraParam', {
		        extend: 'Ext.data.Model',
		        fields: [
		            'id',
		            'desc',
		            { name: 'type', type: 'enum' },
		            { name: 'obligatory', type: 'bool' }
		        ]
		    });


		    // create the Data Store
		    extraParamsStore = Ext.create('Ext.data.Store', {
		        // destroy the store if the grid is destroyed
		        autoDestroy: true,
		        model: 'ExtraParam',
		        proxy: {
		            type: 'memory'
		        },
		        data: [],
		        sorters: [{
		            property: 'start',
		            direction: 'ASC'
		        }]
		    });

		    var rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
		        clicksToMoveEditor: 1,
		        autoCancel: false
		    });

		    // create the grid and specify what field you want
		    // to use for the editor at each column.
		    var grid = Ext.create('Ext.grid.Panel', {
		        store: extraParamsStore,
				id : "extra-params-grid",
		        columns: [{
		            header: 'Descripcion',
		            dataIndex: 'desc',
		            flex: 1,
		            editor: {
		                // defaults to textfield if no xtype is supplied
		                allowBlank: false
		            }
		        }, 	{
		            header: 'Tipo de dato',
		            dataIndex: 'type',
		            width: 130,
		            field: {
		                xtype: 'combobox',
		                typeAhead: true,
		                triggerAction: 'all',
		                selectOnTab: true,
		                store: [
		                    ['textarea', 	'Texto grande'],
		                    ['text', 		'Texto peque&ntilde;o o numero'],
		                    ['file', 		'Archivo' ],
		                    ['date',		'Fecha']
		                ],
		                lazyRender: true,
		                listClass: 'x-combo-list-small'
		            }
		        }, 	{
			            header: 'Obligatorio',
			            dataIndex: 'obligatory',
			            width: 130,
			            field: {
			                xtype: 'combobox',
			                typeAhead: true,
			                triggerAction: 'all',
			                selectOnTab: true,
			                store: [
			                    [true, 	'Si'],
			                    [false, 'No']
			                ],
			                lazyRender: true,
			                listClass: 'x-combo-list-small'
			            }
			        }],
		        renderTo: 'editor-grid',
		        width: "100%",
		        height: 400,
		        frame: false,
		        tbar: [{
		            text: 'Nuevo parametro',
		            iconCls: 'not-ok',
		            handler : function() {
		                rowEditing.cancelEdit();

		                // Create a record instance through the ModelManager
		                var r = Ext.ModelManager.create({
		                    desc: 'nuevo',
		                    type: 'text',
		                    obligatory: false
		                }, 'ExtraParam');

		                extraParamsStore.insert(0, r);
		                rowEditing.startEdit(0, 0);
		            }
		        }, {
		            itemId: 'removeEmployee',
		            text: 'Remover parametro',
		            iconCls: 'ok',
		            handler: function() {
		                var sm = grid.getSelectionModel();
		                rowEditing.cancelEdit();
		                extraParamsStore.remove(sm.getSelection());
		                sm.select(0);
		            },
		            disabled: true
		        }],
		        plugins: [rowEditing],
		        listeners: {
		            'selectionchange': function(view, records) {
		                grid.down('#removeEmployee').setDisabled(!records.length);
		            }
		        }
		    });
		});
	</script>
	
	<?php
	//render the page
	$page->render();
