
rm file.list
rm -rf bin

#find java src files
find src -name \*.java -print > file.list

mkdir bin

#compile those
javac -d bin -cp src:lib/json-simple-1.1.jar:lib/GiovynetDriver.jar @file.list || exit;

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

#create the version file
date | md5 -q > VERSION
date "+BUILT: %Y-%m-%d  %H:%M:%S" >> VERSION

#run main biatch
java -jar posClient.jar 
