<?php 

		define("BYPASS_INSTANCE_CHECK", false);

		

		require_once("../../../server/bootstrap.php");
		//
		// 
		// 
		// 
		// 




		if(!empty($_FILES) ){

			if(!SesionController::isLoggedIn()){
        		throw new ApiException( $this->error_dispatcher->invalidAuthToken() );
			}

			set_time_limit ( 600 );

			Logger::log( "Subiendo archivo ... ");

			

			if($_REQUEST["type"] == "csv-clientes"){
				move_uploaded_file ( $_FILES["logo"]["tmp_name"], "../../../static_content/".IID."-clientes.csv" );
				ClientesController::Importar( file_get_contents( "../../../static_content/".IID."-clientes.csv" ) );	

			}


			if($_REQUEST["type"] == "csv-productos"){
				move_uploaded_file ( $_FILES["logo"]["tmp_name"], "../../../static_content/".IID."-productos.csv" );
				ProductosController::Importar( file_get_contents( "../../../static_content/".IID."-productos.csv" ) );	

			}


			if($_REQUEST["type"] == "csv-proveedores"){
				move_uploaded_file ( $_FILES["logo"]["tmp_name"], "../../../static_content/".IID."-proveedores.csv" );
				ProveedoresController::Importar( file_get_contents( "../../../static_content/".IID."-proveedores.csv" ) );	

			}

			if($_REQUEST["type"] == "logo"){
				move_uploaded_file ( $_FILES["logo"]["tmp_name"], "../static/".IID.".jpg" );
				
			}
			
			echo '{"status":"ok"}';
			exit;
		}




		$page = new GerenciaTabPage(  );
		$page->addComponent("<script>Ext.Ajax.timeout = 5 * 60 * 1000; /* 5 minutos */ </script>");
		$page->addComponent(new TitleComponent("Configuracion de POS ERP"));

		if(!is_writable("../../../static_content/")){
			$page->addComponent(" <div id=''>ALERTA: No se pueden subir archivos. Contacte a un administrador de POS ERP</div>");	
		}


		$page->nextTab("Importar");
		$page->addComponent( new TitleComponent("Importar clientes de CSV/AdminPAQ/Excel", 2));
		$page->addComponent(" <div id='clientes-csvup'></div>");


		$page->addComponent("<hr>");



		$page->addComponent( new TitleComponent("Importar productos de CSV/AdminPAQ/Excel", 2));
		$page->addComponent(" <div id='productos-csvup'></div>");
                

		//
		// Importar usando PosClient
		// 
		// 
		$page->addComponent( new TitleComponent("Importar datos AdminPAQ automaticamente", 2));

		
		$adminPF = new FormComponent();
		$adminPF->addField("url", "URL de AdminPAQ", "text" , "https://192.168.0.14:16001/json/AdminPAQProxy/" );
		$adminPF->addField("path", "Path de la emprsa", "text", "" );
		$adminPF->addOnClick("Importar" , "(function(){ new AdminPAQExplorer( \"". $adminPF->getGuiComponentId() ."\" ); })");
		$page->addComponent($adminPF);

		

		$page->nextTab("Sesiones");
		$sesiones = SesionController::Lista();//SesionDAO::GetAll();
		$header = array(
			"id_usuario"	=> "Usuario",
			"fecha_de_vencimiento"=> "Fecha de vencimiento",
			"client_user_agent"=> "User agent",
			"ip"=> "IP"
		);
		$tabla = new TableComponent($header, $sesiones["resultados"]);

		function username($id_usuario){
			$u = UsuarioDAO::getBypK($id_usuario);
			if(is_null($u)) return "ERROR";
			return  $u->getNombre();
		}
		function ft($time){
			return FormatTime(strtotime($time));
		}
		
		$page->addComponent("<script type=\"text/javascript\" charset=\"utf-8\">
			function detallesUsuario(id){ window.location = 'personal.usuario.ver.php?uid='+id; }
		</script>");
		
		$tabla->addColRender("id_usuario", "username");
		$tabla->addColRender("fecha_de_vencimiento", "ft");
		$tabla->addOnClick("id_usuario", "detallesUsuario");
		
		$page->addComponent( $tabla );
                
                
                
		$page->nextTab("Restaurar");
		//$page->addComponent("<a href=''><div class='POS Boton'>Respaldar BD</div></a>");//Boton de respaldo
                $page->addComponent("<hr>");//Separador
		$page->addComponent(new TitleComponent("Respaldos disponibles", 2));
                    //Dibuja los elementos del la lista de archivos de respaldo encontrados          
                  $CadenaJSForm = "<script>";
                  $CadenaJSForm .= "     var valor = null;";
                  $CadenaJSForm .= "     var fn = function(){";
                  $CadenaJSForm .= "         for(var i = 0; i < document.frmRes.GRespaldo.length; i++){";
                  $CadenaJSForm .= "             if( document.frmRes.GRespaldo[i].checked == true ){";
                  $CadenaJSForm .= "                 valor = document.frmRes.GRespaldo[i].value;";
                  $CadenaJSForm .= "             }";
                  $CadenaJSForm .= "         }";        
                  $CadenaJSForm .= "         POS.API.POST( ";
                  $CadenaJSForm .= "             \"api/pos/bd/restaurar_bd_especifica\", ";
                  $CadenaJSForm .= "             {";
                  $CadenaJSForm .= "                  \"id_instancia\" 	:  ". INSTANCE_ID . ",";
                  $CadenaJSForm .= "                  \"time\" 	: valor";
                  $CadenaJSForm .= "             },";
                  $CadenaJSForm .= "            {";
                  $CadenaJSForm .= "                callback : function(a){";
                  $CadenaJSForm .= "                    window.onbeforeunload = function(){}";                  
                  //$CadenaJSForm .= "                    window.location = \"c.php\"; ";
                  $CadenaJSForm .= "                }";
                  $CadenaJSForm .= "            }";
                  $CadenaJSForm .= "         );";
                  $CadenaJSForm .= "     }";
                  $CadenaJSForm .= "</script>";
                  $CadenaJSForm .= "<div align=\"left\"><form name=\"frmRes\">";
                  $Contador=0;
                  foreach (InstanciasController::BuscarRespaldosComponents(INSTANCE_ID) as $Cadena)
                  {
                        $Contador++;
                        $CadenaJSForm.="               <br><input type=\"radio\" name=\"GRespaldo\" value=\"{$Cadena}\"" . ($Contador == 1? " checked " : "") . "> Respaldo no {$Contador}, creado " . date("D d/m/Y g:i a", $Cadena) . "<br>";
                  }
                  unset($Cadena);//Elimina la referencia usada en FOREACH  
                  //$Retorno.="<br><input type=\"radio\" name=\"GRespaldo\" value=\"{$TArchivo}\"" . ($Contador == 1? " checked " : "") . "> Respaldo no {$Contador}, creado " . date("D d/m/Y g:i a", $TArchivo) . "<br>";
                  $CadenaJSForm.="<br>";
                  $CadenaJSForm.="</form></div><br/><div class=\"POS Boton\" onclick=\"fn();\">Restaurar</div>";
                  $page->addComponent($CadenaJSForm);
                                    
                  //--------------------------------------------------------------------------------
                  
                  $page->nextTab( "Personalizar" );
                  $page->addComponent(" <div id='logo256up'></div>
			<script type='text/javascript' charset='utf-8'>
			Ext.onReady(function(){




  
				    Ext.create('Ext.form.Panel', {
				        renderTo: 'logo256up',
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
				            fieldLabel: 'Imagen',
				            name: 'logo',
				            buttonText: 'Buscar archivo',
				            /*buttonConfig: {
				                iconCls: 'upload-icon'
				            }*/
				        },{
				            xtype: 'hiddenfield',
				            value : 'logo',
				            name: 'type'
				        },
				        {
				            xtype: 'hiddenfield',
				            value : Ext.util.Cookies.get('at'),
				            name: 'auth_token'
				        }],

				        buttons: [{
				            text: 'Subir logotipo',
				            handler: function(){
				                var form = this.up('form').getForm();
				                if(form.isValid()){
				                    form.submit({
				                        url: 'c.php',
				                        waitMsg: 'Subiendo...',
				                        timeout : 60 * 10,
				                        success: function(fp, o) {
				                            console.log('ok', fp, o);
				                        },
				                        failure : function(fp,o){
				                        	console.log('error', fp, o);
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







					Ext.create('Ext.form.Panel', {
				        renderTo: 'clientes-csvup',
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
				            id: 'form-filecsv',
				            emptyText: 'Seleccione un archivo',
				            fieldLabel: 'Imagen',
				            name: 'logo',
				            buttonText: 'Buscar archivo',
				            /*buttonConfig: {
				                iconCls: 'upload-icon'
				            }*/
				        },{
				            xtype: 'hiddenfield',
				           	value : 'csv-clientes',
				            name: 'type'
				        },
				        {
				            xtype: 'hiddenfield',
				            value : Ext.util.Cookies.get('at'),
				            name: 'auth_token'
				        }],

				        buttons: [{
				            text: 'Subir archivo',
				            handler: function(){
				                var form = this.up('form').getForm();
				                if(form.isValid()){
				                    form.submit({
				                        url: 'c.php',
				                        waitMsg: 'Subiendo...',
				                        timeout : 60 * 10,
				                        success: function(fp, o) {
				                          console.log('error', fp, o);
				                        },
				                        failure : function(fp,o){
				                        	console.log('error', fp, o);
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














					Ext.create('Ext.form.Panel', {
				        renderTo: 'productos-csvup',
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
				            id: 'form-filecsv',
				            emptyText: 'Seleccione un archivo',
				            fieldLabel: 'Imagen',
				            name: 'logo',
				            buttonText: 'Buscar archivo',
				            /*buttonConfig: {
				                iconCls: 'upload-icon'
				            }*/
				        },{
				            xtype: 'hiddenfield',
				           	value : 'csv-productos',
				            name: 'type'
				        },
				        {
				            xtype: 'hiddenfield',
				            value : Ext.util.Cookies.get('at'),
				            name: 'auth_token'
				        }],

				        buttons: [{
				            text: 'Subir archivo',
				            handler: function(){
				                var form = this.up('form').getForm();
				                if(form.isValid()){
				                    form.submit({
				                        url: 'c.php',
				                        waitMsg: 'Subiendo...',
				                        timeout : 60 * 10,
				                        success: function(fp, o) {
				                          console.log('error', fp, o);
				                        },
				                        failure : function(fp,o){
				                        	console.log('error', fp, o);
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

		




		$page->nextTab("Mail");
		
		/*
		POSController::EnviarMail(
			$cuerpo = "cuerpo" , 
			$destinatario = "alan.gohe@gmail.com", 
			$titulo	= "titulo"
		);
		* */

		$page->nextTab("POS_CLIENT");

		$page->addComponent("<a href='../dl.php?file=client'><div class='POS Boton' >Descargar POS Client</div></a>");

		$page->render();
