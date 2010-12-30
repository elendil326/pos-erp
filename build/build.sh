#!/bin/bash
echo "Iniciando build"

#compilar el jsminify
gcc jsmin.c -o jsmin

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


# entrar en el directorio de los javascripts
# y fusionar los archivos que estan dentro de la misma carpeta
function parsefiles(){
	echo "escaneando $1"

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


parsefiles js


