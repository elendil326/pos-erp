
for(a=0;a<POS.Apps.length;a++){sink.Structure.push(POS.Apps[a].getConfig());}
Ext.regModel('APP',{fields:[{name:'text',type:'string'},{name:'preventHide',type:'boolean'},{name:'cardSwitchAnimation'},{name:'card'}]});sink.StructureStore=new Ext.data.TreeStore({model:'APP',root:{items:sink.Structure},proxy:{type:'ajax',reader:{type:'tree',root:'items'}}});