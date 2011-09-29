##########################################
############## COMPILE CLIENT ############
##########################################
	rm file.list
	rm -rf bin

	##find java src files
	find src -name \*.java -print > file.list

	mkdir bin

	##compile those
	javac -d bin -cp src:lib/json-simple-1.1.jar:lib/GiovynetDriver.jar @file.list || exit;

	rm file.list

	cd bin

	##create manifest file
echo "Main-Class: mx.caffeina.pos.PosClient
Class-Path: lib/json-simple-1.1.jar lib/GiovynetDriver.jar" > ../manifest

	jar cfm ../posClient.jar ../manifest mx 


	rm ../manifest

	cd ..

##########################################
############## COMPILE LOADER ############
##########################################
	javac POSLoader.java

	echo "Main-Class: POSLoader" > manifest
	jar cfm posLoader.jar manifest POSLoader.class
	rm POSLoader.class
	rm manifest

	
##create the version file
date | md5 -q > VERSION
date "+BUILT: %Y-%m-%d  %H:%M:%S" >> VERSION


##zip the client
zip  -Tr client.zip posClient.jar posLoader.jar *.so *.dll lib media VERSION -x \*.svn* \*.DS_Store
rm -rf bin


#run main biatch
#java -jar posClient.jar 
