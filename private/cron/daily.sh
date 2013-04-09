#!/bin/sh
# daily.sh
# manuel@caffeina.mx
#
# INSTALACION
#
# 1.- Guardamos este archivo con el nombre: daily.sh en cualquier directorio
# 2.- En la terminal ejecutamos: crontab daily.sh (luego si quieres ya puedes borrar el archivo de texto cron-usuario). 
# 3.- Al ejecutar dicho comando se crea un archivo con el nombre del usuario en /var/spool/cron/crontabs/ ; 
#     Este archivo no se debe editar directamente, sino a través de la ejecución del comando crontab -e en la terminal.
# 4.- A partir de ahí se puede ejecutar en la terminal: crontab -e , e ir añadiendo las lineas necesarias para que se 
#     ejecuten programas, comandos o scripts. 
# 5.- Al ejecutar la orden crontab -e en la terminal se abre el editor nano: una vez añadida la/s linea(s), que queremos 
#     programar como tarea(s), se pueden añadir tantas lineas como se quiera para ejecutar tantas tareas como se deseen.
# 6.- La sintaxis del comando crontab es la siguiente : * * * * * daily.sh

# datos de conexion
BDUSER="pos"
BDPASS="pos"
BDNAME="pos"

echo "UPDATE instances SET activa = '0', status = 'prospecto' WHERE instance_id IN ( SELECT instance_id FROM instance_request WHERE ( date_installed  + ( 30 * 24 * 60 * 60 )  ) >= UNIX_TIMESTAMP( NOW( ) ) AND instance_id <> 'NULL' )" | mysql -h 127.0.0.1 -u $BDUSER -p$BDPASS $BDNAME