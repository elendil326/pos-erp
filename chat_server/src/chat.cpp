#include <cstdio>
#include <mysql++.h>
#include <hiredis.h>
#include <ctime>


using namespace std;
using namespace mysqlpp;




#define MISSING_INSTANCE 	1
#define WRONG_INSTANCE 		2
#define MISSING_AUTHTOKEN 	3
#define WRONG_AUTHTOKEN 	4
#define MISSING_ARGUMENT 	5
#define NOT_FOUND			6


#include <sstream>

template <class T>
inline std::string to_string (const T& t)
{
std::stringstream ss;
ss << t;
return ss.str();
}





char **argss;

int split_ocurrences ( string s , char c)
{
	int found = 0;
	for (int i = 0; i < s.length(); ++i)
	{
		if(s[i] == c)
		{
			found++;
		}
	}
	return found + 1;
}



string *  split (string s, char c){

	int found = split_ocurrences(s, c);

	string * res  = new string[found];
	int start = 0;
	int current = 0;
	int i;

	for ( i = 0; i < s.length() ; ++i)
	{
		
		if( s[i] == c )
		{
			if(i == 0) {
				start++;
				continue;	
			}

			res[current] = s.substr( start, i - start );
			start = i + 1;
			current++;
		}
	}

	res[current] = s.substr( start, i - start );

	return res;
}







bool startsWith(string s, string test){
	//test size first

	//test last character, which is less probable to
	//match if startsWith is false

	for( int i = 0 ; ; i++){
		if( s[i] == '\0') return 0;
		if( test[i] == '\0') return 1;
		if( test[i] != s[i]) return 0;
	}

	return 1;
}








string header( string headerName ){

	for(int i = 0 ; i < 30; i++){

		if(!argss[i]) continue;

		if(startsWith(argss[i], headerName )) {

			string s (argss[i]);

			size_t foo = s.find( "=" ) + 1;

			return s.substr( foo );			
		}
	}

	return string();
}



















string _get(string p){
	
	string h = header( "QUERY_STRING" );

	

	string * parts = split( h , '&' );

	int oc = split_ocurrences( h, '&' );

	p.append("=");

	for (int j = 0; j < oc; ++j)
	{


		if( startsWith( parts[ j ], p )  )
		{

			size_t io = parts[j].find("=");
			
			return parts[ j].substr( io  + 1);
		}
	}
	

	
	
	return string("__________NULL");
}








class DB{


	private:
	static Connection coreConn;
	static Connection instanceConn;
	static string intanceToken;

	DB(){

	}

	public:
	void bootstrap(){
		
	}

	static Connection getCoreConn(){
		Connection coreConn(false);
		coreConn.connect("pos", "127.0.0.1", "root", "anti4581549");
		return coreConn;
	}





	static Connection getInstanceConn(string token){
		Connection instanceConn(false);
		instanceConn.connect("pos_instance_85", "127.0.0.1", "root", "anti4581549");
		return instanceConn;
	}	


	static Connection getInstanceConn(){
		return instanceConn;
	}	



	static redisContext* getRedisConnection(){

		redisContext *redis = redisConnect("127.0.0.1", 6379);

		if (redis->err) {
		    printf("Error: %s\n", redis->errstr);
		}
		
		return redis;
	}


	

};












class InstanceController{

public:
	static int instanceExists(string token){

		try{

			Connection conn = DB::getCoreConn();

			Query query = conn.query();

			query << "select * from instances where instance_token = \"" + token + "\";";
			
			StoreQueryResult bres = query.store();
			
			
			
			return ( bres.num_rows() == 1 );
			
			/*for(size_t i = 0 ; i < bres.num_rows(); i++){
				//cout << bres[i]["id_usuario"] << "<br>";
				cout << bres[i]["id_usuario"] << " ";
			}*/
			
		}catch(BadQuery er){
			cout << "Error:" << er.what() << endl ;
			return 0;

		}catch(const BadConversion& er){
			cout << "Conversion error " << er.what() << endl;
			return 0; 

		}catch(const Exception& er){
			cout << "Error" << er.what() << endl;
			return 0;		

		}

	}




	static int getInstanceId(string token){

		try{

			Connection conn = DB::getCoreConn();

			Query query = conn.query();

			query << "select instance_id from instances where instance_token = \"" + token + "\";";
			
			StoreQueryResult bres = query.store();
			
			return bres[0]["instance_id"];

			//return ( bres.num_rows() == 1 );
			
			/*for(size_t i = 0 ; i < bres.num_rows(); i++){
				//cout << bres[i]["id_usuario"] << "<br>";
				cout << bres[i]["id_usuario"] << " ";
			}*/
			
		}catch(BadQuery er){
			cout << "Error:" << er.what() << endl ;
			return 0;

		}catch(const BadConversion& er){
			cout << "Conversion error " << er.what() << endl;
			return 0; 

		}catch(const Exception& er){
			cout << "Error" << er.what() << endl;
			return 0;		

		}

	}
};





class SesionController{
public:
	static int isValidSesion(string at){

		
		try{

			Connection conn = DB::getInstanceConn("laskdfj");

			Query query = conn.query();

			query << "select id_usuario from sesion where auth_token  = \"" + at + "\" limit 1;";
			
			StoreQueryResult bres = query.store();
			
			return ( bres.num_rows() == 1 );
			
			/*for(size_t i = 0 ; i < bres.num_rows(); i++){
				//cout << bres[i]["id_usuario"] << "<br>";
				cout << bres[i]["id_usuario"] << " ";
			}*/
			
		}catch(BadQuery er){
			cout << "Error:" << er.what() << endl ;
			return 0;

		}catch(const BadConversion& er){
			cout << "Conversion error " << er.what() << endl;
			return 0; 

		}catch(const Exception& er){
			cout << "Error" << er.what() << endl;
			return 0;		

		}		
		return 1;
	}



	static int getUserIdFromAuthToken(string at){

		try{

			Connection conn = DB::getInstanceConn("laskdfj");

			Query query = conn.query();

			query << "select id_usuario from sesion where auth_token  = \"" + at + "\" limit 1;";
			
			StoreQueryResult bres = query.store();
			
			if( bres.num_rows() != 1 ){
				//do smething
			}
			
			return bres[0]["id_usuario"];

			/*for(size_t i = 0 ; i < bres.num_rows(); i++){
				//cout <<  << "<br>";
				cout << bres[i]["id_usuario"] << " ";
			}*/
			
		}catch(BadQuery er){
			cout << "Error:" << er.what() << endl ;
			return 0;

		}catch(const BadConversion& er){
			cout << "Conversion error " << er.what() << endl;
			return 0; 

		}catch(const Exception& er){
			cout << "Error" << er.what() << endl;
			return 0;		

		}		
		return 1;	
	}


};




class ChatController{

public:
	static void postMessage(){
		
		//reciever
		string to_user_id = _get("to");

		//instance id
		int instance_id = InstanceController::getInstanceId( _get("instance") );

		//from
		int from_user_id = SesionController::getUserIdFromAuthToken(_get("auth_token"));

		//content
		string content = _get("content");

		//date
		

		
		string key("");

		key.append( to_string(instance_id));
		key.append("-");
		key.append( to_string(to_user_id));
		key.append("-unread");

		string json("{\"from\":"); json.append( to_string(from_user_id) );
		json.append( ",\"date\":" );	json.append( to_string(time(0)));
		json.append( ",\"content\":\""); json.append( content );
		json.append( "\"}");



		//post to them
		string redisCmd ("lpush ");
		redisCmd.append( key );
		redisCmd.append( " " );
		redisCmd.append( json );

		
		redisContext* redis = DB::getRedisConnection();
		redisCommand(redis, redisCmd.c_str());

		cout << redisCmd;

		cout << "{ \"status\" : \"ok\" }";
	}



	static void getMessages(){

		redisContext* redis = DB::getRedisConnection();

		//get curret user
		string at = _get("auth_token");

		int user_id = SesionController::getUserIdFromAuthToken(at);
		int instance_id = InstanceController::getInstanceId( _get("instance") );

		string rCmd ("llen ");
		rCmd.append( to_string(instance_id) );
		rCmd.append("-");
		rCmd.append( to_string(user_id) );
		rCmd.append("-unread");



		redisReply *res ;

		res = (redisReply*)redisCommand(redis, rCmd.c_str());
		
		
		int results = res->integer;

		

		cout << "{ \"number_of_results\" : "<< results << ",\"results\" : [";
		rCmd.clear();
		rCmd.append( "lpop " );
		rCmd.append( to_string(instance_id) );
		rCmd.append("-");
		rCmd.append( to_string(user_id) );
		rCmd.append("-unread");

		for (int i = 0; i < results; ++i)
		{

			res = (redisReply*)redisCommand(redis, rCmd.c_str());

			cout << res->str ;

			if(i < (results - 1))
				cout << ", ";
		}

		cout << "]}";

	}
};




class ContactsController{

	private:
		ContactsController(){

		};


	public:
		static void getOnlineContacts(){
			try{

				Connection conn = DB::getInstanceConn("laskdfj");

				Query query = conn.query();

				query << "	select \
								usuario.id_usuario,\
								usuario.id_rol,\
								usuario.nombre,\
								usuario.correo_electronico,\
								sesion.ip\
							from \
								usuario,\
								sesion\
							where\
								sesion.id_usuario = usuario.id_usuario\
								and sesion.fecha_de_vencimiento < NOW()\
								and usuario.activo = 1;";
				
				StoreQueryResult bres = query.store();
				

				
				cout << "{ \"status\" : \"ok\" , \"number_of_results\" : " << bres.num_rows() << ", \"results\" : [ ";

				for(size_t i = 0 ; i < bres.num_rows(); i++){
					
					cout << "{" ;
					cout << "\"id_usuario\" : " 		<< bres[i]["id_usuario"] << ",";
					cout << "\"id_rol\" : " 			<< bres[i]["id_rol"] << ",";
					cout << "\"nombre\" : \"" 			<< bres[i]["nombre"] << "\",";
					cout << "\"correo_electronico\" : \"" << bres[i]["correo_electronico"] << "\",";
					cout << "\"ip\" : \"" 				<< bres[i]["ip"] << "\"";
					cout << "}";

					if(i < bres.num_rows() - 1)
						cout << ",";
				}

				cout << "]}";
				
			}catch(BadQuery er){
				cout << "Error:" << er.what() << endl ;
				//return 0;

			}catch(const BadConversion& er){
				cout << "Conversion error " << er.what() << endl;
				//return 0; 

			}catch(const Exception& er){
				cout << "Error" << er.what() << endl;
				//return 0;		

			}		
			
		}


};









class HttpResponse{

	public:
		

		static void error(int err_code){
			switch(err_code){
				case MISSING_INSTANCE :
					cout << "{ \"status\" : \"false\", \"reason\" : \"You must provide an instance token.\" }";
				break;

				case WRONG_INSTANCE :
					cout << "{ \"status\" : \"false\", \"reason\" : \"This instance token is invalid.\" }";
				break;

				case MISSING_AUTHTOKEN :
					cout << "{ \"status\" : \"false\", \"reason\" : \"You must provide an auth token.\" }";
				break;

				case WRONG_AUTHTOKEN :
					cout << "{ \"status\" : \"false\", \"reason\" : \"The auth token you provided is invalid.\" }";
				break;

				case MISSING_ARGUMENT :
					cout << "{ \"status\" : \"false\", \"reason\" : \"You are missing arguments to make this call.\" }";
				break;

				
				case NOT_FOUND :
					cout << "{ \"status\" : \"false\", \"reason\" : \"This method does not exist.\" }";
				break;				

			}
			

			exit(EXIT_SUCCESS);
		}

		static void bootstrap(){
			printf("Content-type: application/json\n\n");
		}

};








class ApiHandler{

	private:
	int testInstance(){

		

		string instance = _get("instance");

		if(instance == "__________NULL"){
			HttpResponse::error(MISSING_INSTANCE);
		}

		if(!InstanceController::instanceExists(instance)){
			HttpResponse::error(WRONG_INSTANCE);	
		}
		
		//test auth token
		string auth_token = _get("auth_token");

		if(auth_token == "__________NULL"){
			HttpResponse::error(MISSING_AUTHTOKEN);
		}

		if(!SesionController::isValidSesion(auth_token)){
			HttpResponse::error(WRONG_AUTHTOKEN);
		}




	}

	public:

	void dispatch( string path ){

		//look for global necesary params
		testInstance();

		

		string * spath = split( path, '/' );
		int spath_size = split_ocurrences(path, '/');

		if(spath[0] == "getOnlineContacts"){
			ContactsController::getOnlineContacts();
			return;

		}else if(spath[0] == "postMessage"){
			ChatController::postMessage();
			return;

		}else if(spath[0] == "getMessages"){
			ChatController::getMessages();
			return;
		}

		

		HttpResponse::error(NOT_FOUND);


	}

};
















int main( int nargs, char **args ){

	argss = args;


	HttpResponse::bootstrap();
	


	redisContext *redis = redisConnect("127.0.0.1", 6379);

	if (redis->err) {
	    printf("Error: %s\n", redis->errstr);
		
	}


	redisCommand(redis, "SET foo bar");


	ApiHandler ah ;

	ah.dispatch( header("PATH_INFO")  );


	return EXIT_SUCCESS;

}
