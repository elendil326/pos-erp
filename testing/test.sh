cd ..
cd private
mysql --user root --password=56022 pos < pos.sql 
cd ..
cd testing
java -cp .:gson-1.6.jar Test test.txt

