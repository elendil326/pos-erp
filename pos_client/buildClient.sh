
#find java src files
find src -name \*.java -print > file.list

#compile those
javac -d bin -cp src:lib/json-simple-1.1.jar @file.list 

rm file.list

cd bin

#create manifest file
echo "Main-Class: mx.caffeina.pos.PosClient
Class-Path: lib/json-simple-1.1.jar lib/GiovynetDriver.jar" > ../manifest

jar cfm ../PosClient.jar ../manifest mx 


rm ../manifest

cd ..

#zip the client
zip -r client.zip posClient.jar *.so *.dll lib media -x \*.svn*

rm -rf bin

#run main biatch
java -jar PosClient.jar 
