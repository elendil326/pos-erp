<?php

date_default_timezone_set("America/Mexico_City");

?><html>
  <head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  </head>
  <body>
        <applet code="printer.Main" archive="../dist/PRINTER.jar" WIDTH=0 HEIGHT=0>
            <param name="json" value="<?php echo urlencode($_REQUEST['json'])?>">
            <param name="hora" value="<?php echo urlencode(date("d/m/y H:i:s"))?>">
        </applet>
  </body>
</html>
