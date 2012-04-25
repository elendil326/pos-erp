<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <link rel="stylesheet" href="http://docs.sencha.com/touch/2-0/touch/resources/css/sencha-touch.css" type="text/css">
    <link rel="stylesheet" href="test.css" type="text/css">
    <script type="text/javascript" src="http://docs.sencha.com/touch/2-0/touch/sencha-touch.js"></script>
	
    <script type="text/javascript" src="base.js" charset="utf-8"></script>
	<script type="text/javascript" src="test.js" charset="utf-8"></script>


	<script type="text/javascript">
	
	var Signature;
		(function() {

			var clicked, canvas, ctx, coords, offsetX, offsetY, oldX, oldY;

			 Signature = Ext.extend(Ext.Panel, {
				layout: {
					type: 'vbox',
					pack: "center",
					align: "center"
				},
				items: [{
					scroll: false,
					html: "<canvas width='500' id='canvas' height='100'/>"
				}, {
					xtype: 'button',
					text: 'Submit',
					handler: function(b, e) {
						var img = canvas.toDataURL();
						Ext.Ajax.request({
							url: 'img.php',
							method: 'POST',
							params: {
								img: img
							}
						});
					}
				}],
				scroll: false,
				listeners: {
					activate: function() {
						setupCanvas();
						canvas.ontouchmove = handleMove;
						canvas.onmousemove = handleMouseMove;
					}
				}
			});

			window.onmousedown = function() {
				clicked = true;
			}

			window.onmouseup = function() {
				oldX = oldY = clicked = false;
			}

			window.ontouchend = function() {
				oldX = oldY = clicked = false;
			}

			function handleMouseMove(e) {
				var x = e.offsetX,
					y = e.offsetY;
				if (clicked) drawCircle(x, y);			
			}

			function handleMove(e) {
				var x, y, i;
				for (i = 0; i < e.targetTouches.length; i++) {
					x = e.targetTouches[i].clientX - offsetX;
					y = e.targetTouches[i].clientY - offsetY;
					drawCircle(x, y);
				}
			}

			function setupCanvas() {
				canvas = document.getElementById('canvas');
				ctx = canvas.getContext("2d");
				coords = getCumulativeOffset(canvas);
				offsetX = coords.x;
				offsetY = coords.y;
				drawBg(ctx);
			}

			function drawBg() {
				ctx.beginPath();
				ctx.moveTo(0, 75);
				ctx.lineTo(500, 75);
				ctx.stroke();
				ctx.font = "36pt Arial";
				ctx.fillStyle = "rgb(180,33,33)";
				ctx.fillText("X", 10, 75);
			}

			function drawCircle(x, y) {
				ctx.strokeStyle = "rgb(55,55,255)";
				ctx.beginPath();
				if (oldX && oldY) {
			 		ctx.moveTo(oldX, oldY);
			 		ctx.lineTo(x, y);
			 		ctx.stroke();
					ctx.closePath();
				}
				oldX = x;
				oldY = y;
			}

		        // see: http://stackoverflow.com/questions/160144/find-x-y-of-an-html-element-with-javascript
		 	function getCumulativeOffset(obj) {
			    var left, top;
			    left = top = 0;
			    if (obj.offsetParent) {
			        do {
			            left += obj.offsetLeft;
			            top  += obj.offsetTop;
			        } while (obj = obj.offsetParent);
			    }
			    return {
			        x : left,
			        y : top
			    };
			};

		})();
		
		
Ext.Loader.setPath({
    'Ext.data': '../../src/data',
    'Kitchensink': 'app'
});

//NOTE: This will not be necessary after beta 1 and is only present temporarily to enable built
// (non-dynamic loading) versions of the kitchen sink to work on tablet and phone devices
Ext.require([
    'Kitchensink.view.phone.Main',
    'Kitchensink.view.phone.TouchEvents',
    'Kitchensink.view.tablet.Main',
    'Kitchensink.view.tablet.TouchEvents',
    'Kitchensink.controller.phone.Main',
    'Kitchensink.controller.tablet.Main'
]);

Ext.application({
    name: 'Kitchensink',

    icon: 'resources/img/icon.png',
    tabletStartupScreen: 'resources/img/tablet_startup.png',
    phoneStartupScreen: 'resources/img/phone_startup.png',


    views: [
        'NestedList',
        'List',
        'SourceOverlay',
        'Buttons',
        'Forms',
        'Icons',
        'BottomTabs',
        'Themes',
        'Map',
        'Overlays',
        'Tabs',
        'Toolbars',
        'JSONP',
        'YQL',
        'Ajax',
        'Video',
        'Audio',
        'NestedLoading',
        'Carousel',
        'TouchEvents',
        'SlideLeft',
        'SlideRight',
        'SlideUp',
        'SlideDown',
        'CoverLeft',
        'CoverRight',
        'CoverUp',
        'CoverDown',
        'RevealLeft',
        'RevealRight',
        'RevealUp',
        'RevealDown',
        'Pop',
        'Fade',
        'Flip',
        'Cube'
    ],

    stores: ['Demos'],
    profiles: ['Tablet', 'Phone']
});



	</script>
</head>
<body></body>
</html>
