/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


Aplicacion.Servicios = function (  ){    

    /**
     * -----------------------------------|
     *  REGRESA LA CONFIGURACION DEL MENU |
     * -----------------------------------|
     */

    //lista de servicios
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
    
    

    this.lista_servicios_store = new Ext.data.Store({
        model: 'listaDeServicios',        
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
            store: this.lista_servicios_store,
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

    /**
     * -------------------------------------|
     *  PANEL DE LISTA DE SERVICIOS ACTIVAS |
     * -------------------------------------|
     */

    //lista de ordenes de servicio activas
    Ext.regModel('ordenesDeServicio', {
        fields: [
        {
            name: 'nombre_cliente', 
            type: 'string'
        },
        {
            name: 'nombre_servicio', 
            type: 'string'
        },
        {
            name: 'id_orden_de_servicio', 
            type: 'int'
        }
        ]
    });
    
    this.lista_ordenes_activas_store = new Ext.data.Store({
        model: 'ordenesDeServicio',        
        sorters: 'nombre_cliente',
        autoLoad: true,
        proxy: {
            type: 'ajax',
            url: 'proxy.php?action=3001',
            reader: {
                type: 'json',
                root: 'ordenesActivas'
            }
        },
        getGroupString : function(record) {
            if( record.get('nombre_cliente') === undefined || record.get('nombre_cliente') === null){
                return "#";
            }else{
			
                var fName = record.get('nombre_cliente')[0];
			
                if(fName === undefined){
                    return "#";
                }else{
                    return fName.toUpperCase();
                }
			
            }
        }
    });    

    this.listaDeOrdenesDeServicioActivasPanel =  new Ext.Panel({
        layout: 'fit',
        items: [{
            xtype: 'list',
            store: this.lista_ordenes_activas_store,
            //Aplicacion.Clientes.currentInstance.listaDeClientesPanel.items.items[0]
            itemTpl: '<div><strong>{nombre_cliente}</strong> {nombre_servicio}</div>',
            grouped: true,
            indexBar: true,
            listeners : {
                "beforerender" : function (a,b,c){
                    //Aplicacion.Clientes.currentInstance.listaDeClientesPanel.getComponent(0).setHeight(sink.Main.ui.getSize().height - sink.Main.ui.navigationBar.getSize().height );
                    if(DEBUG)console.log("beforerender lista de ordenes de servicio activa panel creator:", a,b,c);
                },
                show : function (a,b,c){
                    if(DEBUG)console.log("show lista de ordenes de servicio activa",a,b,c);
                }
            }
			
        }]
    });
    
   
    /**
     * -----------------------------------|
     *  REGRESA LA CONFIGURACION DEL MENU |
     * -----------------------------------|
     */
    this.getConfig = function(){
        return POS.U.g ? {
            text: 'Servicios',
            cls: 'launchscreen',
            card: this.listaDeOrdenesDeServicioActivasPanel        
        } : {
            text: 'Servicios',
            cls: 'launchscreen',
            card: this.listaDeOrdenesDeServicioActivasPanel,	
            //leaf: true
            items:[{
                text: 'Nueva Orden',
                cls: 'launchscreen',
                card: this.listaDeServiciosPanel,	
                leaf: true
            }, {
                text: 'Ordenes de Servicio',
                cls: 'launchscreen',
                card: this.listaDeOrdenesDeServicioActivasPanel,	
                leaf: true
            }]
        };
    }
    
    
    
};

POS.Apps.push( new Aplicacion.Servicios() );