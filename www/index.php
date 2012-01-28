<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<title>POS ERP</title>
	<P> HOLA </P>
	<style type="text/css" media="screen">
		
		html, body, div, span, applet, object, iframe,
		h1, h2, h3, h4, h5, h6, p, blockquote, pre,
		a, abbr, acronym, address, big, cite, code,
		del, dfn, em, img, ins, kbd, q, s, samp,
		small, strike, strong, sub, sup, tt, var,
		b, u, i, center,
		dl, dt, dd, ol, ul, li,
		fieldset, form, label, legend,
		table, caption, tbody, tfoot, thead, tr, th, td,
		article, aside, canvas, details, embed, 
		figure, figcaption, footer, header, hgroup, 
		menu, nav, output, ruby, section, summary,
		time, mark, audio, video {
			margin: 0;
			padding: 0;
			border: 0;
			font-size: 100%;
			font: inherit;
			vertical-align: baseline;
		}
		/* HTML5 display-role reset for older browsers */
		article, aside, details, figcaption, figure, 
		footer, header, hgroup, menu, nav, section {
			display: block;
		}
		body {
			line-height: 1;
		}
		ol, ul {
			list-style: none;
		}
		blockquote, q {
			quotes: none;
		}
		blockquote:before, blockquote:after,
		q:before, q:after {
			content: '';
			content: none;
		}
		table {
			border-collapse: collapse;
			border-spacing: 0;
		}
		
		body { background: #101010; color: white; font: 12pt/16pt 'Lato', Tahoma, sans-serif; }

		a { color: white; }
		    a:hover { color: white; }
		    a:active { color: white; text-shadow: 0 0 5px white; }

		.md-header-main h1 { font-weight: 300; color: white; text-shadow: 0 0 1pt white; }

		.md-nav ul li { margin-left: 0; }
		.md-nav ul li.selected { margin-left: -1pt; }
		.md-nav ul li a { opacity: .6; font-weight: 300; color: white; }
		.md-nav ul li.selected a { opacity: 1; font-weight: 400; color: white; text-shadow: 0 0 1pt white; }

		.md-content { font-family: 'Lato', Tahoma, sans-serif; color: #b0b0b0; }
		    .md-content h2 { font-size: 20pt; font-weight: 400; margin-bottom: .2em; line-height: 1.4em; text-shadow: 0 0 1pt #b0b0b0; color: white; }
		    .md-content p { line-height: 1.4em; margin-bottom: 1em; color: #b0b0b0; }
		    .md-content li { line-height: 1.4em; }

		table, table td { border: 1px solid #505050; }
		    table thead th { background: #c0c0c0; color:black; font-weight: 400; font-size: 13pt; }
		    table td, table th { padding: 2pt 4pt; color: #b0b0b0; }
		    table tr:nth-child(2n) { background: #303030; }

		form input[type="text"], form input[type="password"], form select, form textarea { border: none; background: #404040; color: white }
		form input[type="text"], form input[type="password"], form textarea { padding: 6pt; }
		form input[type="button"], form button { border: 1px solid black; background-color: #404040;}

		.md-list-tiles { }
		    .md-list-tiles .md-list-item { float: left; background: #1b1b1b; }
		    .md-list-tiles .md-list-item:last-child { clear: right; }
		    .md-list-tiles .md-list-tile-large { width: 190pt; height: 190pt; margin-right: 6pt; margin-bottom: 6pt; }
		    .md-list-tiles .md-list-item-image { width: 100%; height: 120pt; background: #202020; }
		    .md-list-tiles .md-list-item-overlay { padding: 5pt 10pt; }
		    .md-list-tiles .md-list-item-big-text { font-size: 12pt; line-height: 16pt; color: white; }
		    .md-list-tiles .md-list-item-small-text { font-size: 10pt; line-height: 14pt; color: #a0a0a0; }

		/* Lato font */
		@font-face { font-family: 'Lato'; font-style: normal; font-weight: 300; src: local('Lato Light'), local('Lato-Light'), url('media/fonts/Lato-Light.woff') format('woff'); }
		@font-face { font-family: 'Lato'; font-style: italic; font-weight: 700; src: local('Lato Bold Italic'), local('Lato-BoldItalic'), url('media/fonts/Lato-BoldItalic.woff') format('woff');}
		@font-face { font-family: 'Lato'; font-style: normal; font-weight: 100; src: local('Lato Hairline'), local('Lato-Hairline'), url('media/fonts/Lato-Hairline.woff') format('woff');}
		@font-face { font-family: 'Lato'; font-style: italic; font-weight: 300; src: local('Lato Light Italic'), local('Lato-LightItalic'), url('media/fonts/Lato-LightItalic.woff') format('woff');}
		@font-face { font-family: 'Lato'; font-style: italic; font-weight: 900; src: local('Lato Black Italic'), local('Lato-BlackItalic'), url('media/fonts/Lato-BlackItalic.woff') format('woff');}
		@font-face { font-family: 'Lato'; font-style: italic; font-weight: 400; src: local('Lato Italic'), local('Lato-Italic'), url('media/fonts/Lato-Italic.woff') format('woff');}
		@font-face { font-family: 'Lato'; font-style: italic; font-weight: 100; src: local('Lato Hairline Italic'), local('Lato-HairlineItalic'), url('media/fonts/Lato-HairlineItalic.woff') format('woff');}
		@font-face { font-family: 'Lato'; font-style: normal; font-weight: 400; src: local('Lato Regular'), local('Lato-Regular'), url('media/fonts/Lato-Regular.woff') format('woff');}
		@font-face { font-family: 'Lato'; font-style: normal; font-weight: 900; src: local('Lato Black'), local('Lato-Black'), url('media/fonts/Lato-Black.woff') format('woff');}
		@font-face { font-family: 'Lato'; font-style: normal; font-weight: 700; src: local('Lato Bold'), local('Lato-Bold'), url('media/fonts/Lato-Bold.woff') format('woff');}
		html, body { height: 100%; width: 100%; }

		body { background: white; font: 12pt/16pt Arial, sans-serif; overflow-y: hidden; color: black; position: relative; }

		a { color: blue; cursor: pointer; text-decoration: none;}
		    a:active { color: red; }

		.md-header-main { position: absolute; top: 33pt; left: 90pt; height: 48pt; }
		    .md-header-main h1 { font-size: 42pt; margin-left: -3pt; line-height: 48pt; vertical-align: baseline; }

		.md-nav { position: relative; }
		    .md-nav ul { list-style: none; }
		        .md-nav ul li { float: left; margin-right: 1em; font-size: 20pt; }
		            .md-nav ul li:last-child { clear: right; }
		            .md-nav ul li a { text-decoration: none; }

		.md-nav-main { position: absolute; top: 100pt; left: 90pt; }

		.md-content { position: absolute; top: 140pt; left: 0; right: 0; bottom: 0; overflow: hidden; }

		.md-content h2 { font-size: 20pt; line-height: 24pt; vertical-align: baseline; height: 30pt; }

		.md-content p { margin-bottom: 1em; text-align: justify; }

		.md-viewport { width: 100%; height: 100%; overflow: hidden; }
		.md-viewport-horizontal { overflow-x: auto; overflow-y: hidden; }
		    .md-viewport-horizontal .md-scrollable { width: 1000%; }
		.md-viewport-vertical { overflow-x: hidden; overflow-y: auto; }
		    .md-viewport-vertical .md-scrollable { height: 1000%; }

		.md-content section { float: left; margin: 0 45pt; overflow: hidden; }
		    .md-content section:first-child { margin-left: 90pt; }
		    .md-content section:last-child { margin-right: 0pt; clear: right; }

		.md-content .md-auto-resize { width: 100000%; }
		.md-content article { margin: 0 30pt; float: left; position: relative; }
		    .md-content article:first-child { margin-left: 0; }
		    .md-content article:last-child { margin-right: 0; clear: right; }

		.md-content .md-column { float: left; width: 360pt; margin: 0 15pt; }
		    .md-content .md-column:first-child { margin-left: 0; }
		    .md-content .md-column:last-child { margin-right: 0; }
		    .md-content .md-width-column { width: 360pt; }

		.md-content ul { list-style-type: square; list-style-position: outside; padding-left: 20pt; }
		.md-content ol { list-style-type: decimal; list-style-position: outside; padding-left: 20pt; }

		table { border: 1px solid black; padding: 2pt; }
		    table tr:nth-child(2n) { background: #e0e0e0; }
		    table td { border: 1px solid black; padding: 1pt; }
		    table th { background: black; color: white; }

		form input[type="text"], form input[type="password"], form textarea { border: 1px solid black; padding: 2pt; font-size: 12pt; }
		form select { border: 1px solid black; }
		    form select option { padding: 2pt; }
		        form select option[selected] { background: black; color: white; }
		form button { border: 1pt solid black; background: #404040; color: white; padding: 4pt 12pt; }
		    form button:active { border: 1pt solid black; background: white; color: black; padding: 4pt 12pt; }

		.md-list-tiles { }
		    .md-list-tiles .md-list-item { float: left; background: #f5f5f5; }
		    .md-list-tiles .md-list-item:last-child { clear: right; }
		    .md-list-tiles .md-list-tile-large { width: 190pt; height: 190pt; margin-right: 6pt; margin-bottom: 6pt; }
		    .md-list-tiles .md-list-item-image { width: 100%; height: 120pt; background: #e0e0e0; }
		    .md-list-tiles .md-list-item-overlay { padding: 5pt 10pt; }
		    .md-list-tiles .md-list-item-big-text { font-size: 12pt; line-height: 16pt; }
		    .md-list-tiles .md-list-item-small-text { font-size: 10pt; line-height: 14pt; color: #505050; }

		.hidden { display: none; }
		
	</style>
</head>
<body id="">
	<header class="md-header md-header-main">
	        <h1>Hades - for Metro Dynamis</h1>
    </header>
	<p>this is POS</p>	
</body>
