<?php

	require_once("../../../server/bootstrap.php");

	$page = new GerenciaTabPage();

	$page->addComponent(new TitleComponent("Documentos"));

	$page->nextTab("Documentos");
	$page->addComponent(new TitleComponent("Documentos", 3));
	//buscar un documento
	$documentos_base = DocumentoDAO::getAll();

	$header = array(
			"id_documento"			=> "id_documento",
			"id_documento_base" 	=> "id_documento_base",
			"fecha"					=> "fecha"
		);
	
	$tableDb = new TableComponent( $header, $documentos_base  );
	$tableDb->addColRender("fecha", "R::FriendlyDateFromUnixTime");
	$tableDb->addColRender("id_documento_base", "R::NombreDocumentoBaseFromId");
	$tableDb->addOnClick( "id_documento", "(function(a){ window.location  = 'documentos.ver.php?d=' + a;  })"  );
	$page->addComponent( $tableDb );



	/**
	  *
	  *
	  *
	  **/
	$page->nextTab("Nuevo");

	$page->addComponent(new TitleComponent("Nueva instancia de documento", 3));

	//buscar un documento
	$documentos_base = DocumentoBaseDAO::getAll();

	$header = array(
			"nombre"				=> "Nombre",
			"ultima_modificacion" => "Ultima modificacion"
		);
	
	$tableDb = new TableComponent( $header, $documentos_base  );
	$tableDb->addColRender("ultima_modificacion", "R::FriendlyDateFromUnixTime");
	$tableDb->addOnClick( "id_documento_base", "(function(a){ window.location  = 'documentos.nuevo.instancia.php?base=' + a;  })"  );
	$page->addComponent( $tableDb );


	/**
	  *
	  *
	  *
	  **/
	$page->nextTab("Base");
	$page->addComponent(new TitleComponent( "Nuevo documento base", 3));

	$f = new DAOFormComponent(  new DocumentoBase( ) );
	$f->addApiCall("api/documento/base/nuevo", "POST");
	$f->beforeSend("attachExtraParams");
	$f->hideField(array(
			"id_documento_base",
			"ultima_modificacion",
			"id_sucursal",
			"id_empresa",
			"activo"
		));

	$f->setType("json_impresion", "textarea");
	$page->addComponent($f);

   $page->addComponent(new TitleComponent("Campos para el documento", 3));

	$html = "<div id='editor-grid' style='margin-top: 5px'></div>
    	<script type='text/javascript' charset='utf-8'>
        var extraParamsStore;
        function attachExtraParams(o){
            o.extra_params = getParams();
            return o;
        }
        function getParams(){
            var c = extraParamsStore.getCount(),
            out = [];
            for (var i=0; i < c; i++) {
                var o = extraParamsStore.getAt(i);
                out.push({
                    desc : o.get('desc'),
                    type : o.get('type'),
                    obligatory : o.get('obligatory')
                });
            };
            return Ext.JSON.encode(out);
        }
        Ext.onReady(function(){
            Ext.define('ExtraParam', {
                extend: 'Ext.data.Model',
                fields: ['id','desc', { name: 'type', type: 'enum' }, { name: 'obligatory', type: 'bool' } ]
            });
            extraParamsStore = Ext.create('Ext.data.Store', {
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
            var rowEditing = Ext.create('Ext.grid.plugin.RowEditing', { clicksToMoveEditor: 1, autoCancel: false });
            var grid = Ext.create('Ext.grid.Panel', {
                store: extraParamsStore,
                bodyCls: 'foo',
                id : 'extra-params-grid',
                columns: [{
                    header: 'Descripcion',
                    dataIndex: 'desc',
                    flex: 1,
                    editor: { allowBlank: false }
               },  {
                    header: 'Tipo de dato',
                    dataIndex: 'type',
                    width: 130,
                    field: {
                        xtype: 'combobox',
                        typeAhead: true,
                        triggerAction: 'all',
                        selectOnTab: true,
                        store: [
                            ['textarea',    'Area de texto'],
                            ['text',        'Linea de texto'],
                            ['date',        'Fecha'],
							['bool',		'Desicion'],
							['password',	'Contrasena']
                        ],
                        lazyRender: true,
                        listClass: 'x-combo-list-small'
                    }
                },  {
                        header: 'Obligatorio',
                        dataIndex: 'obligatory',
                        width: 130,
                        field: {
                            xtype: 'combobox',
                            typeAhead: true,
                            triggerAction: 'all',
                            selectOnTab: true,
                            store: [
                                [true,  'Si'],
                                [false, 'No']
                            ],
                            lazyRender: true,
                            listClass: 'x-combo-list-small'
                        }
                    }],
                renderTo: 'editor-grid',
                width: '100%',
                height: 400,
                frame: false,
                tbar: [{
                    text: 'Nuevo parametro',
                    iconCls: 'not-ok',
                    handler : function() {
						rowEditing.cancelEdit();
                        var r = Ext.ModelManager.create({
                            desc: 'nuevo',
                            type: 'text',
                            obligator: false
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
        });</script>";
	$page->addComponent( $html );

	$page->render();

	die;

		$json = '{
			"margin-top" : 1,
			"margin-bottom" : 1,
			"margin-left" : 1,
			"margin-right" : 1,
			"body" : [
				{
					"type" 		: "text",
					"fontSize" 	: 17,
					"x" 		: 0,
					"y" 		: 15,
					"value" 	: "hola {nombre}"
				},
				{
					"type" 		: "text",
					"fontSize" 	: 18,
					"x" 		: 50,
					"y" 		: 15,
					"value" 	: "hola"
				},				
				{
					"type" 		: "round-box",
					"fontSize" 	: 18,
					"x" 		: 150,
					"y" 		: 650,
					"w"			: 100,
					"h"			: 100
				},
				{
					"type" 		: "text",
					"fontSize" 	: 18,
					"x" 		: 50,
					"y" 		: 150,
					"value" 	: "hola"
				}				
			]
		}';
		$json = '{
			"margin-top" 	: 1,
			"margin-bottom" : 1,
			"margin-left" 	: 1,
			"margin-right" 	: 1,
			"width"  		: 612,
			"height" 		: 492,
			"body" : [
				{
					"type" 		: "text",
					"fontSize" 	: 17,
					"x" 		: 0,
					"y" 		: 15,
					"value" 	: "hola {nombre}, como estas? seguro {nombre} !?!?!?"
				}				
			]
		}';



		

		$page->render();
