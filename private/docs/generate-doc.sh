#!/bin/bash
pwd;
phpdoc -t docs/ -ti "Documentación de POS" -o HTML:frames:phpedit -s on -pp on -ed examples/ -f ../../server/mx.caffeina.logger/logger.php,../../server/db/DBConnection.php,../../server/dispatcher.php,../../www/proxy.php
