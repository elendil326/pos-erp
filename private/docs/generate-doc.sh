#!/bin/bash

phpdoc -d ../../server/mx.caffeina.logger/ -t docs/ -ti "Documentación de POS" -o HTML:frames:phpedit -s on -pp on -ed examples/
