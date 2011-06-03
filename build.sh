#!/bin/bash
echo "Iniciando build"



#firmando el applet
keytool -genkey -alias Caffeina -validity 365 -v
echo caffeina  | jarsigner www/sucursal/PRINTER/dist/PRINTER.jar  Caffeina -verbose

#compilar el jsminify
cd build
gcc jsmin.c -o ../jsmin
cd ..


# quitando la carpeta de documentacion
if [ -d build ]
then
	rm -rf build
fi

# quitando la carpeta de documentacion
if [ -d docs ]
then
	rm -rf docs
fi

# quitando la carpeta de privado
if [ -d private ]
then
	rm -rf private
fi

#renombrar el archivo config.php a config.example.php,
#para que al ejecutar el sistema, no encuentre el config
#y comienze la configuracion
#mv server/config.php server/config.example.php


function parsefiles(){

	#pegar todos los js en un solo archivo
	cat $1/*.js > $1/out
	rm $1/*.js	
	
	#minificar ese archivo
	./jsmin <$1/out > $1/out.js

	rm $1/out


	
	if [ $(cat $1/out.js | wc -l) = "0" ]
	then
		#quitar archivos vacios
		rm $1/out.js
	else
		echo "removiendo console.log()"
		# es un archivo con codigo, remover los if(DEBUG)
		# if[ ]?[(]DEBUG[)][ {].*[ }]
		
		#touch $1/out
		
		#while read line
		#do 
		#	echo ${line//if[(]DEBUG[)][{].*[}]/} >> $1/out
		#done < $1/out.js
	fi

	#buscar otros directorios para recursion
	for i in $1/*
	do
		if [ -d $i ]
		then
			parsefiles $i
		fi
	done
}

# entrar en el directorio de los javascripts
# y fusionar los archivos que estan dentro de la misma carpeta
parsefiles js

#eliminar el jsmin y el script de build, osea este mismo
rm jsmin
rm build.sh
rm -rf testing

#enzipar todo
tar -pczf pos-build.tar.gz *

mv pos-build.tar.gz "pos-build-`date +%Y_%m_%d`.tar.gz"

#borrar lo que sobre para que solo quede el tar enzipado
rm -rf www
rm -rf css
rm -rf server
rm -rf static_content
rm -rf js

