<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

        //
		// Parametros necesarios
		// 
		$page->requireParam(  "sid", "GET", "Este servicio no existe." );
		$este_servicio = ServicioDAO::getByPK( $_GET["sid"] );
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Editar servicio " . $este_servicio->getNombreServicio()  , 2 ));

		//
		// Forma de edicion
		// 
		$form = new DAOFormComponent( $este_servicio );
		$form->beforeSend("foo");
		$form->hideField( array( 
                   "id_servicio",
                   "activo",
					"extra_params",
					"control_existencia"                
			 	));
			
        $form->sendHidden("id_servicio");
        $form->addApiCall( "api/servicios/editar/", "POST" );
        $form->onApiCallSuccessRedirect("servicios.ver.php?sid=".$_GET["sid"]);

		$page->addComponent( $form );
        
		$page->partialRender();
		?>
		<div id="editor-grid" style="margin-top: 5px"></div>
		<script type="text/javascript" charset="utf-8">

			var extraParamsStore, rowEditing;

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

			    rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
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
			                    ['text', 		'Texto o numero'],
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
			
				load();
			});
			
			
			function load(){

				
				<?php
				$foo = json_decode($este_servicio->getExtraParams());
				
				foreach ($foo as $f) {

					if( strlen($f->obligatory) == 0 ){  $f->obligatory = "false";  }
					
					echo "var r = Ext.ModelManager.create({
					    desc: '". $f->desc ."',
					    type: '". $f->type ."',
					    obligatory: ". $f->obligatory ."
					}, 'ExtraParam');";
					echo 'extraParamsStore.insert(0, r);';
				}
				?>
				
				

			}
		</script>

		<?php


		$page->render();
