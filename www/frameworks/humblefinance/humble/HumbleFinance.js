////////////////////////////////////////////////////////////////////
// Copyright (c) 2010 Humble Software Development
//
// Permission is hereby granted, free of charge, to any person
// obtaining a copy of this software and associated documentation
// files (the "Software"), to deal in the Software without
// restriction, including without limitation the rights to use,
// copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the
// Software is furnished to do so, subject to the following
// conditions:
//
// The above copyright notice and this permission notice shall be
// included in all copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
// EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
// OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
// NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
// HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
// WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
// FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
// OTHER DEALINGS IN THE SOFTWARE.
////////////////////////////////////////////////////////////////////

/**
 * HumbleFinance Flotr Financial Charts
 * 
 * @license MIT License <http://www.opensource.org/licenses/mit-license.php>
 * @author Carl Sutherland
 * @version 1.1.0
 */


var HumbleFinance = function(  ){



	this.graphs = [];
	this.summary = null;
	this.summaryData = null;
	this.bounds = {xmin: null, xmax: null, ymin: null, ymax: null};
	this.handles = {left: null, right: null, scroll: null};
	this.containers = {mainGraph: null, summary: null, flags: null};
	this.trackFormatter= Flotr.defaultTrackFormatter;
	this.xTickFormatter= Flotr.defaultTickFormatter;
    this.yTickFormatter= Flotr.defaultTickFormatter;
	
	this.setXFormater = function(f){
		this.xTickFormatter = f;
	}
	
	this.setYFormater = function(f){
		this.yTickFormatter = f;
	}
	
	this.setTracker = function(t){
		this.trackFormatter= t;
	}
	
	this.addGraph = function (data){
		this.graphs.push(data);
	}
	
	this.addSummaryGraph = function (data){
		this.summaryData = data;
	}
	
	this.render = function(id){
		this.id = id;
		
		this.bounds.xmin = 0;
        this.bounds.xmax = this.graphs[0].length -1;
        this.bounds.ymin = null;
        this.bounds.ymax = null;
        
        // Set up DOM
        this.buildDOM();
        this.attachEventObservers();
        
        
		var area = {
            x1: 0, 
            y1: this.bounds.ymin, 
            x2: this.graphs[0].length -1, 
            y2: this.bounds.ymax
        };
        
        this.summary = this.summaryGraph(this.summaryData, this.bounds);
        this.summary.setSelection(area);
	};
	
	
	
	this.summaryGraph = function (data, bounds) {
        
        var xmin = bounds.xmin;
        var xmax = bounds.xmax;
        var ymin = bounds.ymin;
        var ymax = bounds.ymax;
        
        var p = Flotr.draw(
            $(this.id + 'summaryGraph'),
            [data],
            {
                lines: {show: true, fill: true, fillOpacity: .1, lineWidth: 1},
                yaxis: {min: ymin, max: ymax, autoscaleMargin: .5, showLabels: false, tickDecimals: 1},
                xaxis: {min: xmin, max: xmax, noTicks: 5, tickFormatter: this.xTickFormatter, labelsAngle: 60},
                grid: {verticalLines: false, horizontalLines: false, labelMargin: 0, outlineWidth: 0},
                selection: {mode: 'x'},
                shadowSize: false,
                HtmlText: true
            }
        );
        
        return p;
    }
	
	
	this.buildDOM = function(){
		var container = $(this.id);

        // Build DOM element
        this.containers.mainGraph = new Element('div', 	{id: this.id + 'priceGraph', style: 'margin-bottom: 10px; width: 100%; height: 240px;'});
        this.containers.summary = new Element('div', 	{id: this.id + 'summaryGraph', style: ' width: 100%; height: 60px; margin-bottom: 15px;'});
        this.containers.flags = new Element('div', 		{id: this.id + 'flagContainer'/*, style: 'width: 0px; height: 0px;'*/});
        this.handles.left = new Element('div', 			{id: this.id + 'leftHandle', 'class': 'handle zoomHandle', style: 'display: none;'});
        this.handles.right = new Element('div', 		{id: this.id + 'rightHandle', 'class': 'handle zoomHandle', style: 'display: none;'});
        this.handles.scroll = new Element('div', 		{id: this.id + 'scrollHandle', 'class': 'handle scrollHandle', style: 'display: none;'});
        
        this.handles.left.onselectstart = function () { return false; };
        this.handles.right.onselectstart = function () { return false; };
        this.handles.scroll.onselectstart = function () { return false; };
        
        // Insert into container
        container.insert(this.containers.mainGraph);
        container.insert(this.containers.summary);
        container.insert(this.containers.flags);
        container.insert(this.handles.left);
        container.insert(this.handles.right);
        container.insert(this.handles.scroll);
	}

	this.attachEventObservers = function(){
		// Attach summary click event to clear selection
        Event.observe(this.containers.summary, 'flotr:click', this.reset.bind(this));
        
        // Attach observers for hit tracking on price and volume points
        Event.observe(this.containers.mainGraph, 'flotr:hit', this.priceHitObserver.bind(this));
        Event.observe(this.containers.mainGraph, 'flotr:clearhit', this.clearHit.bind(this));
        
        // Handle observers
        Event.observe(this.containers.summary, 'flotr:select', this.positionScrollHandle.bind(this));
        Event.observe(this.containers.summary, 'flotr:select', this.positionZoomHandles.bind(this));
        Event.observe(this.handles.left, 'mousedown', this.zoomObserver.bind(this));
        Event.observe(this.handles.right, 'mousedown', this.zoomObserver.bind(this));
        Event.observe(this.handles.scroll, 'mousedown', this.scrollObserver.bind(this));
        
        // On manual selection, hide zoom and scroll handles
        Event.observe(this.containers.summary, 'mousedown', this.hideSelection.bind(this));
        
        // Attach summary selection event to redraw price and volume charts
        Event.observe(this.containers.summary, 'flotr:select', this.selectObserver.bind(this));
	};
	
	
	/**
     * Summary Graph Selection Observer
     * 
     * @param e MouseEvent
     */
    this.selectObserver = function (e) {
            
        var area = e.memo[0];
        xmin = Math.floor(area.x1);
        xmax = Math.ceil(area.x2);
        
        var newBounds = {'xmin': xmin, 'xmax': xmax, 'ymin': null, 'ymax': null};
        
        newData = [];
		for(var i = 0; i < this.graphs.length; i++ ){
			newData.push( this.graphs[i].slice(xmin, xmax+1) );
		}

        this.graphs.price = this.drawGraphs( newData, newBounds);

    };
	
	
	
	this.drawGraphs = function (graficas, bounds) {
		
		if(!bounds) return;

        var xmin = bounds.xmin;
        var xmax = bounds.xmax;
        var ymin = bounds.ymin;
        var ymax = bounds.ymax;

        var p = Flotr.draw(
            $(this.id + 'priceGraph'),
            graficas,
            {
                lines: {show: true, fill: true, fillOpacity: .1, lineWidth: 2},
                yaxis: {min: ymin, max: ymax, tickFormatter: this.yTickFormatter, noTicks: 3, autoscaleMargin: .5,  tickDecimals: 0},
                xaxis: {min: xmin, max: xmax, showLabels: false},
                grid: {outlineWidth: 0, labelMargin: 1},
                mouse: {track: true, sensibility: 1, trackDecimals: 4, trackFormatter: this.trackFormatter, position: 'ne'},
                shadowSize: false,
                HtmlText: true,
				selection: false
				/*
				selection: {
					mode: 'x',		// => one of null, 'x', 'y' or 'xy'
					color: '#cb4b4b',	// => selection box color
					fps: 24			// => frames-per-second
				}*/
            }
        );
        
        return p;
    };
	
	
	
	
	/**
     * Hide selection and handles
     */
    this.hideSelection = function () {

        // Hide handles
        this.handles.left.hide();
        this.handles.right.hide();
        this.handles.scroll.hide();
        
        // Clear selection
        this.summary.clearSelection();
    };
	
	
	
	/**
     * Begin scrolling observer
     * 
     * @param e MouseEvent
     */
	this.scrollObserver = function (e) {
        
        var x = e.clientX;
        var offset = this.handles.scroll.cumulativeOffset();
        var prevSelection = this.summary.prevSelection;
        
        /**
         * Perform scroll on handle move, observer
         * 
         * @param e MouseEvent
         */
        var handleObserver = function (e) {

            Event.stopObserving(document, 'mousemove', handleObserver);
            
            var deltaX = e.clientX - x;

            var xAxis = this.summary.axes.x;
            
            var x1 = xAxis.p2d(Math.min(prevSelection.first.x, prevSelection.second.x) + deltaX);
            var x2 = xAxis.p2d(Math.max(prevSelection.first.x, prevSelection.second.x) + deltaX);
            
            // Check and handle boundary conditions
            if (x1 < this.bounds.xmin) {
                x2 = this.bounds.xmin + (x2 - x1);
                x1 = this.bounds.xmin;
            }
            if (x2 > this.bounds.xmax) {
                x1 = this.bounds.xmax - (x2 - x1);
                x2 = this.bounds.xmax;
            }
            
            // Set selection area object
            var area = {
                x1: x1,
                y1: prevSelection.first.y,
                x2: x2,
                y2: prevSelection.second.y
            };
            
            // If selection varies from previous, set new selection
            if (area.x1 != prevSelection.first.x) {
                this.summary.setSelection(area);
            }
            
            Event.observe(document, 'mousemove', handleObserver);
        }.bind(this);
        
        /**
         * End scroll observer to detach event listeners
         * 
         * @param e MouseEvent
         */
        function handleEndObserver (e) {
            Event.stopObserving(document, 'mousemove', handleObserver);
            Event.stopObserving(document, 'mouseup', handleEndObserver);
        };
        
        // Attach scroll handle observers
        Event.observe(document, 'mousemove', handleObserver);
        Event.observe(document, 'mouseup', handleEndObserver);
    };
	
	
	
	this.zoomObserver = function (e) {

        var zoomHandle = e.element();
        var x = e.clientX;
        var offset = zoomHandle.cumulativeOffset();
        var prevSelection = this.summary.prevSelection;
        
        /**
         * Perform zoom on handle move, observer
         * 
         * @param e MouseEvent
         */
        var handleObserver = function (e) {
            
            Event.stopObserving(document, 'mousemove', handleObserver);

            var deltaX = e.clientX - x;
            var xAxis = this.summary.axes.x;

            // Set initial new x bounds
            var x1, x2;
            if (Element.identify(zoomHandle) == 'rightHandle') {
                x1 = xAxis.p2d(Math.min(prevSelection.first.x, prevSelection.second.x));
                x2 = xAxis.p2d(Math.max(prevSelection.first.x, prevSelection.second.x) + deltaX);
            } else if (Element.identify(zoomHandle) == 'leftHandle') {
                x1 = xAxis.p2d(Math.min(prevSelection.first.x, prevSelection.second.x) + deltaX);
                x2 = xAxis.p2d(Math.max(prevSelection.first.x, prevSelection.second.x));
            }
       
            
            // Check and handle boundary conditions
            if (x1 < this.bounds.xmin) {
                x1 = this.bounds.xmin;
            }
            if (x2 > this.bounds.xmax) {
                x2 = this.bounds.xmax;
            }
            if (x1 > this.bounds.xmax) {
                x1 = this.bounds.xmax;
            }
            if (x2 < this.bounds.xmin) {
                x2 = this.bounds.xmin;
            }
            
            // Set selection area object
            var area = {
                x1: 0,
                y1: prevSelection.first.y, 
                x2: 6, 
                y2: prevSelection.second.y
            };
            
            // If selection varies from previous, set new selection
            if (area.x1 != prevSelection.first.x || area.x2 != prevSelection.second.x) {

                this.summary.setSelection(area);
            }
            
            Event.observe(document, 'mousemove', handleObserver);
        }.bind(this);

        /**
         * End zoom observer to detach event listeners
         * 
         * @param e MouseEvent
         */
        function handleEndObserver (e) {
            Event.stopObserving(document, 'mousemove', handleObserver);
            Event.stopObserving(document, 'mouseup', handleEndObserver);
        };
        
        // Attach handler slide event listeners
        Event.observe(document, 'mousemove', handleObserver);
        Event.observe(document, 'mouseup', handleEndObserver);
    };
	
	
	/**
     * Set the position of the zoom handles
     * 
     * @param e MouseEvent
     */
	this.positionZoomHandles = function (e) {
        
        var x1 = e.memo[0].x1;
        var x2 = e.memo[0].x2;
        var xaxis = e.memo[1].axes.x;
        var plotOffset = e.memo[1].plotOffset;
        var height = this.containers.summary.getHeight();
        var offset = this.containers.summary.positionedOffset();
        //this.handles.left.show();
        var dimensions = this.handles.left.getDimensions();
        
        // Set positions
        var xPosOne = Math.floor(offset[0]+plotOffset.left+xaxis.d2p(x1)-dimensions.width/2+1);
        var xPosTwo = Math.ceil(offset[0]+plotOffset.left+xaxis.d2p(x2)-dimensions.width/2);
        var xPosLeft = Math.min(xPosOne, xPosTwo);
        var xPosRight = Math.max(xPosOne, xPosTwo);
        var yPos = Math.floor(offset[1]+height/2 - dimensions.height/2);
        
        //this.handles.left.setStyle({position: 'absolute', left: xPosLeft+'px', top: yPos+'px'});
        //this.handles.right.setStyle({position: 'absolute', left: xPosRight+'px', top: yPos+'px'});
        //this.handles.left.show();
        //this.handles.right.show();
    };
    
    
	this.reset = function () {
        this.graphs.price = this.drawGraphs(this.graphs, this.bounds);
        this.handles.left.hide();
        this.handles.right.hide();
        this.handles.scroll.hide();
        //this.drawFlags();
    };
	
	this.clearHit = function(e) {
        //this.graphs.mainGraph.clearHit();//.mouseTrack.hide();

    };
	
	
	
	this.priceHitObserver = function (e) {
        
        // Display hit on volume graph
        var point = this.graphs[0][e.memo[0].x];
        Event.stopObserving(this.containers.mainGraph, 'flotr:hit');
        Event.observe(this.containers.mainGraph, 'flotr:hit', this.priceHitObserver.bind(this));

    }
	
	this.positionScrollHandle = function (e) {

        var x1 = e.memo[0].x1;
        var x2 = e.memo[0].x2;
        var xaxis = e.memo[1].axes.x;
        var plotOffset = e.memo[1].plotOffset;
        var graphOffset = this.containers.summary.positionedOffset();
        var graphHeight = this.containers.summary.getHeight();
        var height = this.handles.scroll.getHeight();
        
        // Set width
        var width = Math.floor(xaxis.d2p(x2) - xaxis.d2p(x1)) + 8;
        width = (width < 10) ? 18 : width;
        
        // Set positions
        var xPosLeft = Math.floor(graphOffset[0] + plotOffset.left + xaxis.d2p(x1) + (xaxis.d2p(x2) - xaxis.d2p(x1) - width)/2);
        var yPos = Math.ceil(graphOffset[1] + graphHeight - 2);
        
        this.handles.scroll.setStyle({position: 'absolute', left: xPosLeft+'px', top: yPos+'px', width: width+'px'});
        this.handles.scroll.show();
    };
    
    
    
    
	


}













