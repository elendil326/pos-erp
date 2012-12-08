<?php

define("BYPASS_INSTANCE_CHECK", false);

require_once("../../../server/bootstrap.php");

if (!empty($_FILES)) {
    if (!SesionController::isLoggedIn()) {
        throw new ApiException($this->error_dispatcher->invalidAuthToken());
    }
    
    set_time_limit(600);
    
    Logger::log("Subiendo archivo ... ");    
    
    
    if ($_REQUEST["type"] == "csv-clientes") {
        move_uploaded_file($_FILES["logo"]["tmp_name"], "../../../static_content/" . IID . "-clientes.csv");
        ClientesController::Importar(file_get_contents("../../../static_content/" . IID . "-clientes.csv"));
    }
    
    
    if ($_REQUEST["type"] == "csv-productos") {
        move_uploaded_file($_FILES["logo"]["tmp_name"], "../../../static_content/" . IID . "-productos.csv");
        ProductosController::Importar(file_get_contents("../../../static_content/" . IID . "-productos.csv"));
    }
    
    
    if ($_REQUEST["type"] == "csv-proveedores") {
        move_uploaded_file($_FILES["logo"]["tmp_name"], "../../../static_content/" . IID . "-proveedores.csv");
        ProveedoresController::Importar(file_get_contents("../../../static_content/" . IID . "-proveedores.csv"));
    }
    
    if ($_REQUEST["type"] == "logo") {
        move_uploaded_file($_FILES["logo"]["tmp_name"], "../static/" . IID . ".jpg");
    }
    
    echo '{"status":"ok"}';
    exit;
}




$page = new GerenciaTabPage();
$page->addComponent("<script>Ext.Ajax.timeout = 5 * 60 * 1000; /* 5 minutos */ </script>");
$page->addComponent(new TitleComponent("Configuracion de POS ERP"));

if (!is_writable("../../../static_content/")) {
    $page->addComponent(" <div id=''>ALERTA: No se pueden subir archivos. Contacte a un administrador de POS ERP</div>");
}


$page->nextTab("Importar");
$page->addComponent(new TitleComponent("Importar clientes de CSV/AdminPAQ/Excel", 2));
$page->addComponent(" <div id='clientes-csvup'></div>");


$page->addComponent("<hr>");



$page->addComponent(new TitleComponent("Importar productos de CSV/AdminPAQ/Excel", 2));
$page->addComponent(" <div id='productos-csvup'></div>");


//
// Importar usando PosClient
// 
// 
$page->addComponent(new TitleComponent("Importar datos AdminPAQ automaticamente", 2));


$adminPF = new FormComponent();
$adminPF->addField("url", "URL de AdminPAQ", "text", "https://192.168.0.14:16001/json/AdminPAQProxy/");
$adminPF->addField("path", "Path de la emprsa", "text", "");

$html = "";

$html .= "(function(){";
$html .= "  AdminPAQExplorer( \"" . $adminPF->getGuiComponentId() . "\" );";
$html .= "})";

$adminPF->addOnClick("Importar", $html);
$page->addComponent($adminPF);



$page->nextTab("Sesiones");
$sesiones = SesionController::Lista(); //SesionDAO::GetAll();
$header   = array(
    "id_usuario" => "Usuario",
    "fecha_de_vencimiento" => "Fecha de vencimiento",
    "client_user_agent" => "User agent",
    "ip" => "IP"
);
$tabla    = new TableComponent($header, $sesiones["resultados"]);

function username($id_usuario)
{
    $u = UsuarioDAO::getBypK($id_usuario);
    if (is_null($u))
        return "ERROR";
    return $u->getNombre();
}

function ft($time)
{
    return FormatTime(strtotime($time));
}

$html = "";

$html .= "<script type=\"text/javascript\" charset=\"utf-8\">";
$html .= "  function detallesUsuario(id){";
$html .= "      window.location = 'personal.usuario.ver.php?uid='+id;";
$html .= "  }";
$html .= "</script>";

$page->addComponent($html);

$tabla->addColRender("id_usuario", "username");
$tabla->addColRender("fecha_de_vencimiento", "ft");
$tabla->addOnClick("id_usuario", "detallesUsuario");

$page->addComponent($tabla);

$page->nextTab("Restaurar");
//$page->addComponent("<a href=''><div class='POS Boton'>Respaldar BD</div></a>");//Boton de respaldo
$page->addComponent("<hr>"); //Separador
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
$CadenaJSForm .= "                  \"id_instancia\" 	:  " . INSTANCE_ID . ",";
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
$Contador = 0;
foreach (InstanciasController::BuscarRespaldosComponents(INSTANCE_ID) as $Cadena) {
    $Contador++;
    $CadenaJSForm .= "  <br><input type=\"radio\" name=\"GRespaldo\" value=\"{$Cadena}\"" . ($Contador == 1 ? " checked " : "") . "> Respaldo no {$Contador}, creado " . date("D d/m/Y g:i a", $Cadena) . "<br>";
}
unset($Cadena); //Elimina la referencia usada en FOREACH  
//$Retorno.="<br><input type=\"radio\" name=\"GRespaldo\" value=\"{$TArchivo}\"" . ($Contador == 1? " checked " : "") . "> Respaldo no {$Contador}, creado " . date("D d/m/Y g:i a", $TArchivo) . "<br>";
$CadenaJSForm .= "<br>";
$CadenaJSForm .= "</form></div><br/><div class=\"POS Boton\" onclick=\"fn();\">Restaurar</div>";
$page->addComponent($CadenaJSForm);

//--------------------------------------------------------------------------------

$page->nextTab("Personalizar");

$html = "";

$html .= "<div id='logo256up'></div>";
$html .= "<script type='text/javascript' charset='utf-8'>";
$html .= "   Ext.onReady(function(){";
$html .= "	Ext.create('Ext.form.Panel', {";
$html .= "           renderTo: 'logo256up',";
$html .= "	    width: '100%',";
$html .= "           frame: false,";
$html .= "           bodyPadding: '10 10 0',";
$html .= "           defaults: {";
$html .= "               anchor: '100%',";
$html .= "               allowBlank: false,";
$html .= "               msgTarget: 'side',";
$html .= "               labelWidth: 50";
$html .= "           },";
$html .= "           items: [{";
$html .= "               xtype: 'filefield',";
$html .= "               id: 'form-file',";
$html .= "               emptyText: 'Seleccione una imagen',";
$html .= "               fieldLabel: 'Imagen',";
$html .= "               name: 'logo',";
$html .= "               buttonText: 'Buscar archivo',";
$html .= "   	        },{";
$html .= "               xtype: 'hiddenfield',";
$html .= "               value : 'logo',";
$html .= "               name: 'type'";
$html .= "           },";
$html .= "           {";
$html .= "               xtype: 'hiddenfield',";
$html .= "               value : Ext.util.Cookies.get('at'),";
$html .= "               name: 'auth_token'";
$html .= "           }],";
$html .= "           buttons: [{";
$html .= "               text: 'Subir logotipo',";
$html .= "               handler: function(){";
$html .= "                   var form = this.up('form').getForm();";
$html .= "                   if(form.isValid()){";
$html .= "                       form.submit({";
$html .= "                           url: 'c.php',";
$html .= "                           waitMsg: 'Subiendo...',";
$html .= "                           timeout : 60 * 10,";
$html .= "                           success: function(fp, o) {";
$html .= "                               console.log('ok', fp, o);";
$html .= "                           },";
$html .= "                           failure : function(fp,o){";
$html .= "                               console.log('error', fp, o);";
$html .= "                           }";
$html .= "                       });";
$html .= "                   }";
$html .= "               }";
$html .= "           },{";
$html .= "               text: 'Cancelar',";
$html .= "               handler: function() {";
$html .= "                   this.up('form').getForm().reset();";
$html .= "               }";
$html .= "           }]";
$html .= "       });";

$html .= "       Ext.create('Ext.form.Panel', {";
$html .= "           renderTo: 'clientes-csvup',";
$html .= "           width: '100%',";
$html .= "           frame: false,";
$html .= "	    bodyPadding: '10 10 0',";
$html .= "	    defaults: {";
$html .= "               anchor: '100%',";
$html .= "	        allowBlank: false,";
$html .= "	        msgTarget: 'side',";
$html .= "	        labelWidth: 50";
$html .= "	    },";
$html .= "	    items: [{";
$html .= "	        xtype: 'filefield',";
$html .= "	        id: 'form-filecsv',";
$html .= "	        emptyText: 'Seleccione un archivo',";
$html .= "	        fieldLabel: 'Imagen',";
$html .= "	        name: 'logo',";
$html .= "	        buttonText: 'Buscar archivo',";
$html .= "	    },{";
$html .= "               xtype: 'hiddenfield',";
$html .= "               value : 'csv-clientes',";
$html .= "               name: 'type'";
$html .= "           },";
$html .= "           {";
$html .= "               xtype: 'hiddenfield',";
$html .= "		value : Ext.util.Cookies.get('at'),";
$html .= "		name: 'auth_token'";
$html .= "           }],";
$html .= "           buttons: [{";
$html .= "               text: 'Subir archivo',";
$html .= "		handler: function(){";
$html .= "                   var form = this.up('form').getForm();";
$html .= "                   if(form.isValid()){";
$html .= "                       form.submit({";
$html .= "                           url: 'c.php',";
$html .= "                           waitMsg: 'Subiendo...',";
$html .= "                           timeout : 60 * 10,";
$html .= "                           success: function(fp, o) {";
$html .= "                              console.log('error', fp, o);";
$html .= "               	    },";
$html .= "       		    failure : function(fp,o){";
$html .= "                               console.log('error', fp, o);";
$html .= "                           }";
$html .= "		        });";
$html .= "		    }";
$html .= "               }";
$html .= "           },{";
$html .= "               text: 'Cancelar',";
$html .= "		handler: function() {";
$html .= "                  this.up('form').getForm().reset();";
$html .= "		}";
$html .= "           }]";
$html .= "       });";

$html .= "	Ext.create('Ext.form.Panel', {";
$html .= "           renderTo: 'productos-csvup',";
$html .= "	    width: '100%',";
$html .= "	    frame: false,";
$html .= "           bodyPadding: '10 10 0',";
$html .= "	    defaults: {";
$html .= "               anchor: '100%',";
$html .= "		allowBlank: false,";
$html .= "		msgTarget: 'side',";
$html .= "		labelWidth: 50";
$html .= "           },";
$html .= "	    items: [{";
$html .= "               xtype: 'filefield',";
$html .= "               id: 'form-filecsv',";
$html .= "	        emptyText: 'Seleccione un archivo',";
$html .= "	        fieldLabel: 'Imagen',";
$html .= "	        name: 'logo',";
$html .= "	        buttonText: 'Buscar archivo',";
$html .= "	    },{";
$html .= "               xtype: 'hiddenfield',";
$html .= "		value : 'csv-productos',";
$html .= "		name: 'type'";
$html .= "           },";
$html .= "           {";
$html .= "               xtype: 'hiddenfield',";
$html .= "	        value : Ext.util.Cookies.get('at'),";
$html .= "	        name: 'auth_token'";
$html .= "	    }],";
$html .= "	    buttons: [{";
$html .= "               text: 'Subir archivo',";
$html .= "	        handler: function(){";
$html .= "                   var form = this.up('form').getForm();";
$html .= "		    if(form.isValid()){";
$html .= "                       form.submit({";
$html .= "                           url: 'c.php',";
$html .= "		            waitMsg: 'Subiendo...',";
$html .= "		            timeout : 60 * 10,";
$html .= "		            success: function(fp, o) {";
$html .= "                               console.log('error', fp, o);";
$html .= "		            },";
$html .= "		            failure : function(fp,o){";
$html .= "                               console.log('error', fp, o);";
$html .= "		            }";
$html .= "		        });";
$html .= "		    }";
$html .= "               }";
$html .= "           },{";
$html .= "               text: 'Cancelar',";
$html .= "		handler: function() {";
$html .= "                   this.up('form').getForm().reset();";
$html .= "		}";
$html .= "           }]";
$html .= "       });";

$html .= "   });";

$html .= "</script>";

$page->addComponent($html);

//---------------------------------------------------------

$page->nextTab("Mail");

/*
POSController::EnviarMail(
$cuerpo = "cuerpo" ,
$destinatario = "alan.gohe@gmail.com",
$titulo	= "titulo"
);
*/

//---------------------------------------------------------

$page->nextTab("Decimales");

$page->addComponent(new TitleComponent("Numero de decimales", 2));

$html = "";

$html .= "<script>";
$html .= "  var fn_editar_decimales = function(){";
$html .= "      POS.API.POST( ";
$html .= "          \"api/pos/configuracion/decimales\", ";
$html .= "          {";
$html .= "              \"cantidades\" : Ext.get(\"dcantidades\").getValue().replace(/^\s+|\s+$/g,\"\"),";
$html .= "              \"cambio\"     : Ext.get(\"dcambio\").getValue().replace(/^\s+|\s+$/g,\"\"),";
$html .= "              \"costos\"     : Ext.get(\"dcostos\").getValue().replace(/^\s+|\s+$/g,\"\"),";
$html .= "              \"ventas\"     : Ext.get(\"dventas\").getValue().replace(/^\s+|\s+$/g,\"\")";
$html .= "          },";
$html .= "          {";
$html .= "              callback : function(a){";
$html .= "                  if(a.message){";
$html .= "                      Ext.MessageBox.show({";
$html .= "                          title : \"Error\",";
$html .= "                          msg : a.message,";
$html .= "                          buttons : Ext.MessageBox.OK,";
$html .= "                          icon :  \"error\"";
$html .= "                      });";
$html .= "                  }else{";
$html .= "                      Ext.MessageBox.show({";
$html .= "                          title : \"Decimales\",";
$html .= "                          msg : 'Se modifico correctamente la configuraci&oacute;n',";
$html .= "                          buttons : Ext.MessageBox.OK,";
$html .= "                          fn :  function(){window.location = \"c.php#Decimales\";}";
$html .= "                      });";
//$html .= "                      window.location = \"c.php\"; ";
$html .= "                  }";
$html .= "                  window.onbeforeunload = function(){}";
//$html .= "                  window.location = \"c.php\"; ";
$html .= "              }";
$html .= "          }";
$html .= "      );";
$html .= "  }";
$html .= "</script>";

$page->addComponent($html);


//buscamos la configuracion de decimales
$configuraciones = ConfiguracionDAO::search( new Configuracion( array("descripcion" => "decimales") ) );
        
if( empty($configuraciones) ){
    
    Logger::error("No se tiene registro de la configuracion 'decimales' en la tabla de configuraciones");
    
    $page->addComponent("No se tiene registro de la configuracion 'decimales' en la tabla de configuraciones");
    
    $dcantidades = "";
    $dcambio = "";
    $dventas = "";
    $dcostos = "";
    
}else{
    
    $decimales = $configuraciones[0];
    $config = json_decode($decimales->getValor());
    
    $dcantidades = $config->cantidades;
    $dcambio = $config->cambio;
    $dventas = $config->ventas;
    $dcostos = $config->costos;
    
}    

$html = "";

$html .= "<table style = \"width:100%;\">";
$html .= "  <tr>";
$html .= "      <td>1.- Cantidades</td>";
$html .= "      <td><input type = \"text\" name = \"\" value = \"{$dcantidades}\" id = \"dcantidades\" /></td>";
$html .= "  </tr>";
$html .= "  <tr>";
$html .= "      <td>2.- Tipos de Cambio</td>";
$html .= "      <td><input type = \"text\" name = \"\" value = \"{$dcambio}\" id = \"dcambio\" /></td>";
$html .= "  </tr>";
$html .= "  <tr>";
$html .= "      <td>3.- Precio de Venta</td>";
$html .= "      <td><input type = \"text\" name = \"\" value = \"{$dventas}\" id = \"dventas\" /></td>";
$html .= "  </tr>";
$html .= "  <tr>";
$html .= "      <td>4.- Costos y Precio de Compra</td>";
$html .= "      <td><input type = \"text\" name = \"\" value = \"{$dcostos}\" id = \"dcostos\" /></td>";
$html .= "  </tr>";
$html .= "  <tr>";
$html .= "      <td colspan =  \"2\"><input id = \"btn_modificar_decimales\" class = \"POS Boton OK\" style = \"position:relative; float:right;\" type = \"button\" value = \"Aceptar\" onClick = \"fn_editar_decimales();\" /></td>";
$html .= "  </tr>";
$html .= "";
$html .= "</table>";

$page->addComponent($html);

//---------------------------------------------------------
$page->nextTab("POS_CLIENT");

$page->addComponent("<a href='../dl.php?file=client'><div class='POS Boton' >Descargar POS Client</div></a>");

//---------------------------------------------------------

$page->render();
