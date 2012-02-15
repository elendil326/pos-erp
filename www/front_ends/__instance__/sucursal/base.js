Ext.application({
    name: 'Sencha',

    launch: function() {
        Ext.create("Ext.Panel", {
            fullscreen: true,
            flex: false,
            layout: {
               type: 'hbox',
               align: 'middle'
            },
            items: [
                {
                style : "color:red",
                xtype : 'button',
                text: 'Button'
                  
                },
                {
                xtype : 'button',
                text: 'Button'
                
                  
                },
                {
                xtype : 'button',
                text: 'Button'
                
                  
                },
                {
                xtype : 'button',
                text: 'Button'
                
                  
                }
            ]
        });
    }
});