Ext.util.JSONP = {
            queue: [],
            current: null,
            request : function(o) {
                o = o || {};
                if (!o.url) {
                    return;
                }

                var me = this;
                o.params = o.params || {};
                if (o.callbackKey) {
                    o.params[o.callbackKey] = 'Ext.util.JSONP.callback';
                }
                var params = Ext.urlEncode(o.params);

                var script = document.createElement('script');
                script.type = 'text/javascript';
                Ext.fly(script).on({
                    error: Ext.util.Functions.createDelegate(this.onScriptResponseFailure, this)
                });

                this.queue.push({
                    url: o.url,
                    script: script,
                    callback: o.callback || function(){},
                    onFailure: o.onFailure || function(){},
                    scope: o.scope || window,
                    params: params || null
                });

                if (!this.current) {
                    this.next();
                }
            },
            next : function() {
                this.current = null;
                if (this.queue.length) {
                    this.current = this.queue.shift();
                    this.current.script.src = this.current.url + (this.current.params ? ('?' + this.current.params) : '');
                    document.getElementsByTagName('head')[0].appendChild(this.current.script);
                }
            },
            callback: function(json) {
                this.current.callback.call(this.current.scope, json);
                document.getElementsByTagName('head')[0].removeChild(this.current.script);
                this.next();
            },
            onScriptResponseFailure: function(json, data) {
                this.current.onFailure.call(this.current.scope, json);
                document.getElementsByTagName('head')[0].removeChild(this.current.script);
                this.next();
            }
        };


POS.ajaxToClient = function( module, args, callback, onFailureCallback )
{
	
	if(DEBUG){
		console.log("AJAX TO CLIENT !!", args);		
	}

	
	/** **** Cross-broswer call **** **/

	    Ext.util.JSONP.request({
	        url: 'http://127.0.0.1:8080/',
	        callbackKey: "callback",
	        params: {
				action : module,
				data : Ext.util.JSON.encode(args),
				unique : Math.random(    )
			},
	        callback: function(r) {
				if(DEBUG){
					console.log("Client responded !");
				}

				callback.call(null, r);
		    },
			onFailure: function() {
				if(DEBUG){
					console.error("Cant find client !!!!");
				}
				onFailureCallback.call(null);
			}
		
	    });		


	/** **** Cross-broswer call **** **/
	
}