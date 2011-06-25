
#find java src files
find src -name \*.java -print > file.list

mkdir bin

#compile those
javac -d bin -cp src:lib/json-simple-1.1.jar:lib/GiovynetDriver.jar @file.list 

rm file.list

cd bin

#create manifest file
echo "Main-Class: mx.caffeina.pos.PosClient
Class-Path: lib/json-simple-1.1.jar lib/GiovynetDriver.jar" > ../manifest

jar cfm ../posClient.jar ../manifest mx 


rm ../manifest

cd ..

rm posClient.zip

#zip the client
zip -r client.zip posClient.jar *.so *.dll lib media -x \*.svn*

rm -rf bin

#run main biatch
java -jar posClient.jar 
