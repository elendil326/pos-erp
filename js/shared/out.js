
Ext.util.JSONP={queue:[],current:null,request:function(o){o=o||{};if(!o.url){return;}
var me=this;o.params=o.params||{};if(o.callbackKey){o.params[o.callbackKey]='Ext.util.JSONP.callback';}
var params=Ext.urlEncode(o.params);var script=document.createElement('script');script.type='text/javascript';Ext.fly(script).on({error:Ext.util.Functions.createDelegate(this.onScriptResponseFailure,this)});this.queue.push({url:o.url,script:script,callback:o.callback||function(){},onFailure:o.onFailure||function(){},scope:o.scope||window,params:params||null});if(!this.current){this.next();}},next:function(){this.current=null;if(this.queue.length){this.current=this.queue.shift();this.current.script.src=this.current.url+(this.current.params?('?'+this.current.params):'');document.getElementsByTagName('head')[0].appendChild(this.current.script);}},callback:function(json){this.current.callback.call(this.current.scope,json);document.getElementsByTagName('head')[0].removeChild(this.current.script);this.next();},onScriptResponseFailure:function(json,data){this.current.onFailure.call(this.current.scope,json);document.getElementsByTagName('head')[0].removeChild(this.current.script);this.next();}};POS._ajaxToClientPORT=16001;POS.ajaxToClient=function(options)
{if(DEBUG){console.log("AJAX TO CLIENT : ",options);}
var to_send=options.raw_args||{};to_send.action=options.module;to_send.data=Ext.util.JSON.encode(options.args);to_send.unique=Math.random();Ext.util.JSONP.request({url:'http://127.0.0.1:'+POS._ajaxToClientPORT+'/',callbackKey:"callback",params:to_send,callback:function(r){if(DEBUG){console.log("Client responded !");}
options.success.call(null,r);},onFailure:function(){if(DEBUG){console.error("Cant find client !!!!");}
options.failure.call(null);}});}
POS.Keyboard={Keyboard:null,keyboardObj:null,callback:null,hide:null,campo:null,genHTML:null,callbackFn:null,_genHTMLalfa:null,_HTMLalfa:null,_genHTMLalfanum:null,_HTMLalfanum:null,_genHTMLnum:null,_HTMLnum:null,_genHTMLcomplete:null,_HTMLcomplete:null};POS.Keyboard.Keyboard=function(campo,config){if(POS.Keyboard.KeyboardObj){var internalConfig=POS.Keyboard.genHTML(config);POS.Keyboard.callback=config.callback;POS.Keyboard.KeyboardObj.showBy(campo,true,false);if(POS.Keyboard.KeyboardObj.getWidth()!=internalConfig.width)
POS.Keyboard.KeyboardObj.setWidth(internalConfig.width);if(POS.Keyboard.KeyboardObj.getHeight()!=internalConfig.height)
POS.Keyboard.KeyboardObj.setHeight(internalConfig.height);POS.Keyboard.KeyboardObj.update(internalConfig.html);POS.Keyboard.KeyboardObj.showBy(campo,true,false);POS.Keyboard.campo=campo;return POS.Keyboard.Keyboard;}
POS.Keyboard.KeyboardObj=new Ext.Panel({floating:true,style:{zIndex:'20000 !important'},ui:"dark",modal:false,scroll:false,width:100,height:100,hideOnMaskTap:true,bodyPadding:0,bodyMargin:0,styleHtmlContent:false,html:null,listeners:{'hide':function(){POS.Keyboard.campo.blur();}}});return POS.Keyboard.Keyboard(campo,config);};POS.Keyboard.hide=function(){if(POS.Keyboard.KeyboardObj)
{POS.Keyboard.KeyboardObj.hide(Ext.anims.fade);POS.Keyboard.campo.blur();}};POS.Keyboard.callbackFn=function(val,isSubmit){if(isSubmit===true){POS.Keyboard.hide();if(POS.Keyboard.callback){POS.Keyboard.callback.call(this,POS.Keyboard.campo);}
POS.Keyboard.campo.blur();return;}
if(val=="_DEL_"){var str=POS.Keyboard.campo.getValue();POS.Keyboard.campo.setValue(str.substring(0,str.length-1));return;}
if(val=="_SPACE_"){POS.Keyboard.campo.setValue(POS.Keyboard.campo.getValue()+" ");return;}
if(val=='_CANCEL_'){POS.Keyboard.campo.blur();POS.Keyboard.campo.setValue(POS.Keyboard.campo.startValue);POS.Keyboard.hide();return;}
POS.Keyboard.campo.setValue(POS.Keyboard.campo.getValue()+val);};POS.Keyboard.genHTML=function(config){var html="",w=100,h=100;var iConfig;html+="<div class='Keyboard'>";switch(config.type){case'alfa':iConfig=POS.Keyboard._genHTMLalfa(config);break;case'num':iConfig=POS.Keyboard._genHTMLnum(config);break;case'alfanum':iConfig=POS.Keyboard._genHTMLalfanum(config);break;case'complete':iConfig=POS.Keyboard._genHTMLcomplete(config);break;default:throw("Invalid Keyboard Type");}
html+=iConfig.html;w=iConfig.w;h=iConfig.h;html+="</div>";return{html:html,width:w,height:h};};POS.Keyboard._genHTMLalfa=function(config){var html="";html+="<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>Q</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>W</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>E</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>R</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>T</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>Y</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>U</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>I</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>O</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>P</div>"
+"<div class='Keyboard-key' onclick='POS.Keyboard.callbackFn( \"_DEL_\", false )'>&#8592;</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )' style='margin-left:30px'>A</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>S</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>D</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>F</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>G</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>H</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>J</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>K</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>L</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )' style='margin-right:30px'>&Ntilde;</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>Z</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>X</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>C</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>V</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>B</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>N</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>M</div>"
+"<div class='Keyboard-key long'  onclick='POS.Keyboard.callbackFn( \"_SPACE_\", false  )'></div>";html+="<div class='Keyboard-key long' onclick='POS.Keyboard.callbackFn( null, true)'>"+config.submitText+"</div>";return POS.Keyboard._HTMLalfa={html:html,w:690,h:160};};POS.Keyboard._genHTMLalfanum=function(config){var html="";html+="<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>1</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>2</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>3</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>4</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>5</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>6</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>7</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>8</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>9</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>0</div>"
+"<div class='Keyboard-key' onclick='POS.Keyboard.callbackFn( \"_DEL_\", false )'>&#8592;</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>Q</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>W</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>E</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>R</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>T</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>Y</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>U</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>I</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>O</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>P</div>"
+"<div class='Keyboard-key ' onclick='POS.Keyboard.callbackFn( null, true)'>"+config.submitText+"</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )' style='margin-left:30px'>A</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>S</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>D</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>F</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>G</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>H</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>J</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>K</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>L</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>&Ntilde;</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )' style='margin-left:60px'>Z</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>X</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>C</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>V</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>B</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>N</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )' >M</div>"
+"<div class='Keyboard-key long'  onclick='POS.Keyboard.callbackFn( \"_SPACE_\", false  )'></div>";return POS.Keyboard._HTMLalfa={html:html,w:677,h:210};};POS.Keyboard._genHTMLnum=function(config){var html="";html+="<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>7</div>";html+="<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>8</div>";html+="<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>9</div>";html+="<div class='Keyboard-key ' onclick='POS.Keyboard.callbackFn(  \"_DEL_\", false )'>&#8592;</div>";html+="<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>4</div>";html+="<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>5</div>";html+="<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>6</div>";html+="<div class='Keyboard-key ' onclick='POS.Keyboard.callbackFn(  \"_CANCEL_\", false )' style=\"padding-left: 2px;\">Cancelar</div>";html+="<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>1</div>";html+="<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>2</div>";html+="<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>3</div>";html+="<div class='Keyboard-key ' onclick='POS.Keyboard.callbackFn(  null, true )'>"+config.submitText+"</div>";html+="<div class='Keyboard-key ' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>0</div>";html+="<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>.</div>";return POS.Keyboard._HTMLnum={html:html,w:282,h:205};};POS.Keyboard._genHTMLcomplete=function(config){var html="";html+="<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>!</div>"
+"<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>@</div>"
+"<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>#</div>"
+"<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>$</div>"
+"<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>%</div>"
+"<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>^</div>"
+"<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>&</div>"
+"<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>*</div>"
+"<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>(</div>"
+"<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>)</div>"
+"<div class='Keyboard-key tinyNormal' onclick='POS.Keyboard.callbackFn( \"_DEL_\", false )'>&#8592;</div>"
+"<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>1</div>"
+"<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>2</div>"
+"<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>3</div>"
+"<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>4</div>"
+"<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>5</div>"
+"<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>6</div>"
+"<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>7</div>"
+"<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>8</div>"
+"<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>9</div>"
+"<div class='Keyboard-key tiny' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>0</div>"
+"<div class='Keyboard-key tinyNormal' onclick='POS.Keyboard.callbackFn( null, true )'>"+config.submitText+"</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )' style='margin-left:15px'>Q</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>W</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>E</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>R</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>T</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>Y</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>U</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>I</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>O</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )' style='margin-right:50px'>P</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )' style='margin-left:30px'>A</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>S</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>D</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>F</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>G</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>H</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>J</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>K</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>L</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>&Ntilde;</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )' style='margin-left:60px'>Z</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>X</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>C</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>V</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>B</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>N</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )' >M</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )'>,</div>"
+"<div class='Keyboard-key small' onclick='POS.Keyboard.callbackFn( this.innerHTML, false )' >.</div>"
+"<div class='Keyboard-key tinyLong'  onclick='POS.Keyboard.callbackFn( \"_SPACE_\", false  )' style='margin-left:200px'></div>";return POS.Keyboard._HTMLalfa={html:html,w:670,h:265};};var hexcase=0;var b64pad="";function hex_md5(s){return rstr2hex(rstr_md5(str2rstr_utf8(s)));}
function b64_md5(s){return rstr2b64(rstr_md5(str2rstr_utf8(s)));}
function any_md5(s,e){return rstr2any(rstr_md5(str2rstr_utf8(s)),e);}
function hex_hmac_md5(k,d)
{return rstr2hex(rstr_hmac_md5(str2rstr_utf8(k),str2rstr_utf8(d)));}
function b64_hmac_md5(k,d)
{return rstr2b64(rstr_hmac_md5(str2rstr_utf8(k),str2rstr_utf8(d)));}
function any_hmac_md5(k,d,e)
{return rstr2any(rstr_hmac_md5(str2rstr_utf8(k),str2rstr_utf8(d)),e);}
function md5_vm_test()
{return hex_md5("abc").toLowerCase()=="900150983cd24fb0d6963f7d28e17f72";}
function rstr_md5(s)
{return binl2rstr(binl_md5(rstr2binl(s),s.length*8));}
function rstr_hmac_md5(key,data)
{var bkey=rstr2binl(key);if(bkey.length>16)bkey=binl_md5(bkey,key.length*8);var ipad=Array(16),opad=Array(16);for(var i=0;i<16;i++)
{ipad[i]=bkey[i]^0x36363636;opad[i]=bkey[i]^0x5C5C5C5C;}
var hash=binl_md5(ipad.concat(rstr2binl(data)),512+data.length*8);return binl2rstr(binl_md5(opad.concat(hash),512+128));}
function rstr2hex(input)
{try{hexcase}catch(e){hexcase=0;}
var hex_tab=hexcase?"0123456789ABCDEF":"0123456789abcdef";var output="";var x;for(var i=0;i<input.length;i++)
{x=input.charCodeAt(i);output+=hex_tab.charAt((x>>>4)&0x0F)
+hex_tab.charAt(x&0x0F);}
return output;}
function rstr2b64(input)
{try{b64pad}catch(e){b64pad='';}
var tab="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";var output="";var len=input.length;for(var i=0;i<len;i+=3)
{var triplet=(input.charCodeAt(i)<<16)|(i+1<len?input.charCodeAt(i+1)<<8:0)|(i+2<len?input.charCodeAt(i+2):0);for(var j=0;j<4;j++)
{if(i*8+j*6>input.length*8)output+=b64pad;else output+=tab.charAt((triplet>>>6*(3-j))&0x3F);}}
return output;}
function rstr2any(input,encoding)
{var divisor=encoding.length;var i,j,q,x,quotient;var dividend=Array(Math.ceil(input.length/2));for(i=0;i<dividend.length;i++)
{dividend[i]=(input.charCodeAt(i*2)<<8)|input.charCodeAt(i*2+1);}
var full_length=Math.ceil(input.length*8/(Math.log(encoding.length)/Math.log(2)));var remainders=Array(full_length);for(j=0;j<full_length;j++)
{quotient=Array();x=0;for(i=0;i<dividend.length;i++)
{x=(x<<16)+dividend[i];q=Math.floor(x/divisor);x-=q*divisor;if(quotient.length>0||q>0)
quotient[quotient.length]=q;}
remainders[j]=x;dividend=quotient;}
var output="";for(i=remainders.length-1;i>=0;i--)
output+=encoding.charAt(remainders[i]);return output;}
function str2rstr_utf8(input)
{var output="";var i=-1;var x,y;while(++i<input.length)
{x=input.charCodeAt(i);y=i+1<input.length?input.charCodeAt(i+1):0;if(0xD800<=x&&x<=0xDBFF&&0xDC00<=y&&y<=0xDFFF)
{x=0x10000+((x&0x03FF)<<10)+(y&0x03FF);i++;}
if(x<=0x7F)
output+=String.fromCharCode(x);else if(x<=0x7FF)
output+=String.fromCharCode(0xC0|((x>>>6)&0x1F),0x80|(x&0x3F));else if(x<=0xFFFF)
output+=String.fromCharCode(0xE0|((x>>>12)&0x0F),0x80|((x>>>6)&0x3F),0x80|(x&0x3F));else if(x<=0x1FFFFF)
output+=String.fromCharCode(0xF0|((x>>>18)&0x07),0x80|((x>>>12)&0x3F),0x80|((x>>>6)&0x3F),0x80|(x&0x3F));}
return output;}
function str2rstr_utf16le(input)
{var output="";for(var i=0;i<input.length;i++)
output+=String.fromCharCode(input.charCodeAt(i)&0xFF,(input.charCodeAt(i)>>>8)&0xFF);return output;}
function str2rstr_utf16be(input)
{var output="";for(var i=0;i<input.length;i++)
output+=String.fromCharCode((input.charCodeAt(i)>>>8)&0xFF,input.charCodeAt(i)&0xFF);return output;}
function rstr2binl(input)
{var output=Array(input.length>>2);for(var i=0;i<output.length;i++)
output[i]=0;for(var i=0;i<input.length*8;i+=8)
output[i>>5]|=(input.charCodeAt(i/8)&0xFF)<<(i%32);return output;}
function binl2rstr(input)
{var output="";for(var i=0;i<input.length*32;i+=8)
output+=String.fromCharCode((input[i>>5]>>>(i%32))&0xFF);return output;}
function binl_md5(x,len)
{x[len>>5]|=0x80<<((len)%32);x[(((len+64)>>>9)<<4)+14]=len;var a=1732584193;var b=-271733879;var c=-1732584194;var d=271733878;for(var i=0;i<x.length;i+=16)
{var olda=a;var oldb=b;var oldc=c;var oldd=d;a=md5_ff(a,b,c,d,x[i+0],7,-680876936);d=md5_ff(d,a,b,c,x[i+1],12,-389564586);c=md5_ff(c,d,a,b,x[i+2],17,606105819);b=md5_ff(b,c,d,a,x[i+3],22,-1044525330);a=md5_ff(a,b,c,d,x[i+4],7,-176418897);d=md5_ff(d,a,b,c,x[i+5],12,1200080426);c=md5_ff(c,d,a,b,x[i+6],17,-1473231341);b=md5_ff(b,c,d,a,x[i+7],22,-45705983);a=md5_ff(a,b,c,d,x[i+8],7,1770035416);d=md5_ff(d,a,b,c,x[i+9],12,-1958414417);c=md5_ff(c,d,a,b,x[i+10],17,-42063);b=md5_ff(b,c,d,a,x[i+11],22,-1990404162);a=md5_ff(a,b,c,d,x[i+12],7,1804603682);d=md5_ff(d,a,b,c,x[i+13],12,-40341101);c=md5_ff(c,d,a,b,x[i+14],17,-1502002290);b=md5_ff(b,c,d,a,x[i+15],22,1236535329);a=md5_gg(a,b,c,d,x[i+1],5,-165796510);d=md5_gg(d,a,b,c,x[i+6],9,-1069501632);c=md5_gg(c,d,a,b,x[i+11],14,643717713);b=md5_gg(b,c,d,a,x[i+0],20,-373897302);a=md5_gg(a,b,c,d,x[i+5],5,-701558691);d=md5_gg(d,a,b,c,x[i+10],9,38016083);c=md5_gg(c,d,a,b,x[i+15],14,-660478335);b=md5_gg(b,c,d,a,x[i+4],20,-405537848);a=md5_gg(a,b,c,d,x[i+9],5,568446438);d=md5_gg(d,a,b,c,x[i+14],9,-1019803690);c=md5_gg(c,d,a,b,x[i+3],14,-187363961);b=md5_gg(b,c,d,a,x[i+8],20,1163531501);a=md5_gg(a,b,c,d,x[i+13],5,-1444681467);d=md5_gg(d,a,b,c,x[i+2],9,-51403784);c=md5_gg(c,d,a,b,x[i+7],14,1735328473);b=md5_gg(b,c,d,a,x[i+12],20,-1926607734);a=md5_hh(a,b,c,d,x[i+5],4,-378558);d=md5_hh(d,a,b,c,x[i+8],11,-2022574463);c=md5_hh(c,d,a,b,x[i+11],16,1839030562);b=md5_hh(b,c,d,a,x[i+14],23,-35309556);a=md5_hh(a,b,c,d,x[i+1],4,-1530992060);d=md5_hh(d,a,b,c,x[i+4],11,1272893353);c=md5_hh(c,d,a,b,x[i+7],16,-155497632);b=md5_hh(b,c,d,a,x[i+10],23,-1094730640);a=md5_hh(a,b,c,d,x[i+13],4,681279174);d=md5_hh(d,a,b,c,x[i+0],11,-358537222);c=md5_hh(c,d,a,b,x[i+3],16,-722521979);b=md5_hh(b,c,d,a,x[i+6],23,76029189);a=md5_hh(a,b,c,d,x[i+9],4,-640364487);d=md5_hh(d,a,b,c,x[i+12],11,-421815835);c=md5_hh(c,d,a,b,x[i+15],16,530742520);b=md5_hh(b,c,d,a,x[i+2],23,-995338651);a=md5_ii(a,b,c,d,x[i+0],6,-198630844);d=md5_ii(d,a,b,c,x[i+7],10,1126891415);c=md5_ii(c,d,a,b,x[i+14],15,-1416354905);b=md5_ii(b,c,d,a,x[i+5],21,-57434055);a=md5_ii(a,b,c,d,x[i+12],6,1700485571);d=md5_ii(d,a,b,c,x[i+3],10,-1894986606);c=md5_ii(c,d,a,b,x[i+10],15,-1051523);b=md5_ii(b,c,d,a,x[i+1],21,-2054922799);a=md5_ii(a,b,c,d,x[i+8],6,1873313359);d=md5_ii(d,a,b,c,x[i+15],10,-30611744);c=md5_ii(c,d,a,b,x[i+6],15,-1560198380);b=md5_ii(b,c,d,a,x[i+13],21,1309151649);a=md5_ii(a,b,c,d,x[i+4],6,-145523070);d=md5_ii(d,a,b,c,x[i+11],10,-1120210379);c=md5_ii(c,d,a,b,x[i+2],15,718787259);b=md5_ii(b,c,d,a,x[i+9],21,-343485551);a=safe_add(a,olda);b=safe_add(b,oldb);c=safe_add(c,oldc);d=safe_add(d,oldd);}
return Array(a,b,c,d);}
function md5_cmn(q,a,b,x,s,t)
{return safe_add(bit_rol(safe_add(safe_add(a,q),safe_add(x,t)),s),b);}
function md5_ff(a,b,c,d,x,s,t)
{return md5_cmn((b&c)|((~b)&d),a,b,x,s,t);}
function md5_gg(a,b,c,d,x,s,t)
{return md5_cmn((b&d)|(c&(~d)),a,b,x,s,t);}
function md5_hh(a,b,c,d,x,s,t)
{return md5_cmn(b^c^d,a,b,x,s,t);}
function md5_ii(a,b,c,d,x,s,t)
{return md5_cmn(c^(b|(~d)),a,b,x,s,t);}
function safe_add(x,y)
{var lsw=(x&0xFFFF)+(y&0xFFFF);var msw=(x>>16)+(y>>16)+(lsw>>16);return(msw<<16)|(lsw&0xFFFF);}
function bit_rol(num,cnt)
{return(num<<cnt)|(num>>>(32-cnt));}