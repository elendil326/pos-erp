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

POS._ajaxToClientPORT = 16001;

POS.ajaxToClient = function( options ) //module, args, callback, onFailureCallback )
{
	
	
	if(DEBUG){
		console.log("AJAX TO CLIENT !!", options.args);		
	}

	
	/** **** Cross-broswer call **** **/

	    Ext.util.JSONP.request({
	        url: 'http://127.0.0.1:'+POS._ajaxToClientPORT+'/',
	        callbackKey: "callback",
	        params: {
				action : options.module,
				data : Ext.util.JSON.encode(options.args),
				unique : Math.random(    )
			},
	        callback: function(r) {
				if(DEBUG){
					console.log("Client responded !");
				}

				options.success.call(null, r);
		    },
			onFailure: function() {
				if(DEBUG){
					console.error("Cant find client !!!!");
				}
				options.failure.call(null);
			}
		
	    });		


	/** **** Cross-broswer call **** **/
	
}