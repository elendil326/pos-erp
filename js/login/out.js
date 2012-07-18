
var DEBUG=false,login,POS={U:{G:null}},form;if(document.location.search==="?debug")
{DEBUG=true;console.log("Applicacion en modo de DEBUG !");}
function checkCurrentSession(clientPIN){if(DEBUG){console.log("checkCurrentSession()");}
Ext.getBody().mask('Validando sucursal...','x-mask-loading',false);Ext.Ajax.request({url:'../proxy.php',params:{action:2001,pin:clientPIN},success:function(response,opts){if(response.responseText.length===0){Ext.getBody().mask("Error.");return;}
Ext.getBody().unmask();var results;try{results=Ext.util.JSON.decode(response.responseText);}catch(e){Ext.getBody().mask("Error<br>Mostrando error orginal:<br>"+response.responseText);return;}
if(!results.success){if(DEBUG){console.error("Basic testing has returned something other than success.");}
Ext.getBody().mask(results.response);return;}
if(!results.sesion){createLoginForm(results.sucursal);return;}
window.location="../proxy.php?action=2005";},failure:function(response,opts){Ext.getBody().mask("No se puede alcanzar el servidor. Verifique que tenga conectividad a internet.");if(DEBUG){console.log('server-side failure with status code '+response.status);}}});}
function checkForClientPIN()
{Ext.getBody().mask('Iniciando...','x-mask-loading',false);if(DEBUG){console.log("Asking for PIN!");}
POS.ajaxToClient({module:"networking",args:"",success:function(a,b,c){if(DEBUG){console.log("PIN retrival succesful!",a);}
if(a.success===true){checkCurrentSession(a.response);return;}
var html="<div onClick='checkForClient()'>Se ha encontrado un error con su cliente. Toque aqui para reintentar."
+"<div>";Ext.getBody().mask(html,'x-mask-loading',true);},failure:function(){var html="<div onClick='checkForClient()'>No se ha encontrado el cliente de caffeina."
+"Toque aqui para reintentar."
+"<div>";Ext.getBody().mask(html,'x-mask-loading',true);}});}
function checkForClient()
{Ext.getBody().mask('Iniciando...','x-mask-loading',false);if(DEBUG){console.log("Enviando hanshake!");}
POS.ajaxToClient({module:"handshake",args:"",success:function(){if(DEBUG){console.log("Hanshake exitoso !");}
checkForClientPIN();},failure:function(){var html="<div onClick='checkForClient()'>No se ha encontrado el cliente de caffeina."
+"Toque aqui para reintentar."
+"<div>";Ext.getBody().mask(html,'x-mask-loading',true);}});}
function parseLoginResults(args)
{if(!args.success){form.getComponent(0).setInstructions(args.text);form.show();return;}
if(DEBUG){window.location="../proxy.php?DEBUG=true&action=2005";return;}
window.location="../proxy.php?action=2005";}
var sendLogin=function(){form.hide();Ext.getBody().mask('Revisando...','x-mask-loading',true);Ext.Ajax.request({url:'../proxy.php',params:{action:2004,u:Ext.getCmp("uid").getValue(),p:hex_md5(Ext.getCmp("pswd").getValue())},success:function(response,opts){Ext.getBody().unmask();parseLoginResults(Ext.util.JSON.decode(response.responseText));}});};Ext.setup({glossOnIcon:false,onReady:function(){if(DEBUG){console.log("Im ready to start !");}
checkForClient();}});function createLoginForm(sucursal){var formBase={scroll:null,standardSubmit:false,submitOnAction:false,items:[{xtype:'fieldset',title:'Bienvenido a '+sucursal,instructions:'Porfavor llene la informacion apropiada.',defaults:{required:false,labelAlign:'left'},items:[{xtype:'textfield',id:'uid',label:'Identifiaci&oacute;n',useClearIcon:true,autoCapitalize:false,listeners:{"focus":function(){kconf={type:'num',submitText:'Aceptar',callback:function(){}};POS.Keyboard.Keyboard(this,kconf);}}},{xtype:'passwordfield',id:'pswd',label:'Contrase&ntilde;a',useClearIcon:false,listeners:{"focus":function(){kconf={type:'complete',submitText:'Ingresar',callback:function(a,b){sendLogin();}};POS.Keyboard.Keyboard(this,kconf);}}}]}],listeners:{submit:function(form,result){if(DEBUG){console.log('success',Ext.toArray(result,form));}
return;},exception:function(form,result){}},dockedItems:[{xtype:'toolbar',dock:'bottom',items:[{xtype:'spacer'},{text:'Vaciar',handler:function(){form.getComponent(0).setInstructions("Porfavor llene la informacion apropiada.");form.reset();}},{text:'Entrar',ui:'confirm',handler:sendLogin}]}]};if(Ext.is.Phone){formBase.fullscreen=true;}else{Ext.apply(formBase,{autoRender:true,floating:true,modal:true,centered:true,hideOnMaskTap:false,height:285,width:480});}
form=new Ext.form.FormPanel(formBase);form.show();}