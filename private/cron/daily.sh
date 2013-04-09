#!/bin/sh
# daily.sh
# manuel@caffeina.mx

# datos de conexion
BDUSER="pos"
BDPASS="pos"
BDNAME="pos"

#
echo "UPDATE instances SET activa = '0', status = 'prospecto' WHERE instance_id IN ( SELECT instance_id FROM instance_request WHERE ( date_installed  + ( 30 * 24 * 60 * 60 )  ) >= UNIX_TIMESTAMP( NOW( ) ) AND instance_id <> 'NULL' )" | mysql -h 127.0.0.1 -u $BDUSER -p$BDPASS $BDNAME