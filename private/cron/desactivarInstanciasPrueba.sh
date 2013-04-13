#!/bin/sh
# desactivarInstanciasPrueba.sh
# manuel@caffeina.mx
#
# INSTALACION
#
# 1.- Guardamos este archivo con el nombre: desactivarInstanciasPrueba.sh en cualquier directorio
# 2.- En la terminal ejecutamos: crontab desactivarInstanciasPrueba.sh (luego si quieres ya puedes borrar el archivo de texto cron-usuario). 
# 3.- Al ejecutar dicho comando se crea un archivo con el nombre del usuario en /var/spool/cron/crontabs/ ; 
#     Este archivo no se debe editar directamente, sino a través de la ejecución del comando crontab -e en la terminal.
# 4.- A partir de ahí se puede ejecutar en la terminal: crontab -e , e ir añadiendo las lineas necesarias para que se 
#     ejecuten programas, comandos o scripts. 
# 5.- Al ejecutar la orden crontab -e en la terminal se abre el editor nano: una vez añadida la/s linea(s), que queremos 
#     programar como tarea(s), se pueden añadir tantas lineas como se quiera para ejecutar tantas tareas como se deseen.
# 6.- La sintaxis del comando crontab es la siguiente : 0 1 * * * desactivarInstanciasPrueba.sh Se ejecuta al minuto 1 de cada hora de todos los días

cd /var/www/pos-local/branches/dev-main/private/cron/

php -f desactivarInstanciasPrueba.php