/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


Aplicacion.Servicios = function (  ){    

    Ext.regModel('listaDeServicios', {
        fields: [
        {
            name: 'nombre_servicio', 
            type: 'string'
        },

        {
            name: 'id_servicio', 
            type: 'int'
        }
        ]
    });

    this.store = new Ext.data.Store({
        model: 'listaDeServicios',
        //sorters: 'nombre',
        sorters: 'nombre_servicio',
        autoLoad: true,
        proxy: {
            type: 'ajax',
            url: 'proxy.php?action=3000',
            reader: {
                type: 'json',
                root: 'servicios'
            }
        },
        getGroupString : function(record) {
            if( record.get('nombre_servicio') === undefined || record.get('nombre_servicio') === null){
                return "#";
            }else{
			
                var fName = record.get('nombre_servicio')[0];
			
                if(fName === undefined){
                    return "#";
                }else{
                    return fName.toUpperCase();
                }
			
            }
        }
    });    

    this.listaDeServiciosPanel =  new Ext.Panel({
        layout: 'fit',
        items: [{
            xtype: 'list',
            store: this.store,
            //Aplicacion.Clientes.currentInstance.listaDeClientesPanel.items.items[0]
            itemTpl: '<div><strong>{id_servicio}</strong> {nombre_servicio}</div>',
            grouped: true,
            indexBar: true,
            listeners : {
                "beforerender" : function (a,b,c){
                    //Aplicacion.Clientes.currentInstance.listaDeClientesPanel.getComponent(0).setHeight(sink.Main.ui.getSize().height - sink.Main.ui.navigationBar.getSize().height );
                    if(DEBUG)console.log("beforerender lista de servicios panel creator:", a,b,c);
                },
                show : function (a,b,c){
                    if(DEBUG)console.log("show lista de servicios",a,b,c);
                }
            }
			
        }]
    });
    
};


Aplicacion.Servicios.prototype.getConfig = function (){

    return POS.U.g ? {
        text: 'Servicios',
        cls: 'launchscreen',
        card: Aplicacion.Servicios.listaDeServiciosPanel        
    } : {
        text: 'Servicios',
        cls: 'launchscreen',
        card: this.listaDeServiciosPanel,	
        leaf: true
    };

};


POS.Apps.push( new Aplicacion.Servicios() );