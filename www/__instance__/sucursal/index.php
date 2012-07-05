<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <link rel="stylesheet" href="http://docs.sencha.com/touch/2-0/touch/resources/css/sencha-touch.css" type="text/css">
    <link rel="stylesheet" href="test.css" type="text/css">
    <script type="text/javascript" src="http://docs.sencha.com/touch/2-0/touch/sencha-touch.js"></script>
	


	<script type="text/javascript">
	
	
		</script>		

			<script type="text/javascript" src="base.js" charset="utf-8"></script>
			<script type="text/javascript" src="test.js" charset="utf-8"></script>
		    

		<script type="text/javascript" charset="utf-8">		
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
