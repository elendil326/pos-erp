
#find java src files
find src -name \*.java -print > file.list

#compile those
javac -d bin -cp src:lib/json-simple-1.1.jar @file.list 

rm file.list

cd bin

#create manifest file
echo "Main-Class: mx.caffeina.pos.PosClient
Class-Path: lib/json-simple-1.1.jar" > ../manifest

jar cfm ../PosClient.jar ../manifest mx ../media


rm ../manifest

cd ..

#zip the client
zip -r client.zip posClient.jar lib media -x \*.svn*

#run main biatch
java -jar PosClient.jar 
