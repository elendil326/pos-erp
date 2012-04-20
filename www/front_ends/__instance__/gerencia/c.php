<?php 

		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");


		//
		// 
		// 
		// 
		// 
		if(!empty($_FILES)){

			Logger::log( "subiendo nuevo logotipo ... ");
			
			move_uploaded_file ( $_FILES["logo"]["tmp_name"], "../static/".IID.".jpg" );
			
			die('{"status": "ok"}');
		}

		$page = new GerenciaTabPage(  );
		
		$page->addComponent(new TitleComponent("Configuracion"));
		
		
		$page->nextTab("Importar");

		$page->addComponent( new TitleComponent("Importar datos AdminPAQ mediante archivos TXT", 2));
		$page->addComponent( new TitleComponent("Importar clientes", 3));
		$importarClientes = new FormComponent();
		$importarClientes->addField("raw_content", "Contenido de la exportacion CSV", "textarea");
		$importarClientes->addApiCall("api/clientes/importar/", "POST");
		$page->addComponent( $importarClientes );



		$page->addComponent( new TitleComponent("Importar productos", 3));
		$importarProductos = new FormComponent();
		$importarProductos->addField("raw_content", "Contenido de la exportacion CSV", "textarea");
		$importarProductos->addApiCall("api/producto/importar/", "POST");
		$page->addComponent( $importarProductos );

		$page->addComponent( new TitleComponent("Importar datos AdminPAQ automaticamente", 2));


		$page->nextTab("Sesiones");
		$sesiones = SesionController::Lista();//SesionDAO::GetAll();
		$header = array(
			"id_sesion" => "id_sesion",
			"id_usuario"=> "id_usuario",
			"fecha_de_vencimiento"=> "fecha_de_vencimiento",
			"client_user_agent"=> "client_user_agent",
			"ip"=> "ip"
		);
		$tabla = new TableComponent($header, $sesiones["resultados"]);
		$page->addComponent( $tabla );
		
		$page->nextTab("Respaldar");
		
		
		$page->nextTab( "Personalizar" );
		//$page->partialRender();

		$page->addComponent("<h2>Logotipo</h2><p>Una imagen principal de 256x256 pixeles.</p>
		<div id='fi-form'></div>
		<script type='text/javascript' charset='utf-8'>
			Ext.onReady(function(){

				    Ext.create('Ext.form.Panel', {
				        renderTo: 'fi-form',
				        width: '100%',
				        frame: false,
				        bodyPadding: '10 10 0',

				        defaults: {
				            anchor: '100%',
				            allowBlank: false,
				            msgTarget: 'side',
				            labelWidth: 50
				        },

				        items: [{
				            xtype: 'filefield',
				            id: 'form-file',
				            emptyText: 'Seleccione una imagen',
				            fieldLabel: 'Photo',
				            name: 'logo',
				            buttonText: '',
				            buttonConfig: {
				                iconCls: 'upload-icon'
				            }
				        }],

				        buttons: [{
				            text: 'Subir logotipo',
				            handler: function(){
				                var form = this.up('form').getForm();
				                if(form.isValid()){
				                    form.submit({
				                        url: 'c.php',
				                        waitMsg: 'Subiendo...',
				                        success: function(fp, o) {
				                            msg('Success', 'Processed file ' + o.result.file + ' on the server');
				                        }
				                    });
				                }
				            }
				        },{
				            text: 'Cancelar',
				            handler: function() {
				                this.up('form').getForm().reset();
				            }
				        }]
				    });

				});
		</script>");

		$page->render();
