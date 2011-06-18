
#find java src files
find src -name \*.java -print > file.list

#compile those
javac -d bin -cp src:lib/json-simple-1.1.jar @file.list 

rm file.list

cd bin

#create manifest file
echo "Main-Class: mx.caffeina.pos.PosClient" > ../manifest

jar cfm ../PosClient.jar ../manifest mx


rm ../manifest

#run main biatch
#java -cp .:lib/json-simple-1.1.jar 

cd ..

java -jar PosClient.jar 