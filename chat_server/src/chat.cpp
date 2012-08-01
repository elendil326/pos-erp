#include <cstdio>
#include <mysql++.h>
#include <hiredis.h>



using namespace std;
using namespace mysqlpp;




#define MISSING_INSTANCE 	1
#define WRONG_INSTANCE 		2
#define MISSING_AUTHTOKEN 	3
#define WRONG_AUTHTOKEN 	4





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
};




class ChatController{

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

		if(spath[0] == "contacts"){
			ContactsController::getOnlineContacts();
		}

	}

};
















int main( int nargs, char **args ){

	argss = args;


	HttpResponse::bootstrap();
	

	redisContext *c = redisConnect("127.0.0.1", 6379);

	if (c->err) {
	    printf("Error: %s\n", c->errstr);
		
	}


	redisCommand(c, "SET foo bar");



	//string h = _get("_instance_");
	
	/*cout <<  _get("_instance_");
	cout <<  _get("_path_");

	*/


	ApiHandler ah ;
	ah.dispatch( header("PATH_INFO")  );




/*		cout << findInRequest("param0") << "<br>";
	
	if(getParam("param1"))
		cout << findInRequest("param1") << "<br>";
*/


	
		


		//char *entrada;
		//cin >> entrada;
		//cout << entrada << endl;
		//cout << nargs << "<br>" << endl;

		//$.ajax({ url : "http://127.0.0.1/c/hola.bin", "type" : "POST", data : { data1 : 24 } })
		
		for( int j = 0 ; j < 35 ; j++){
			if(!args[j]) continue;
			if(j == 32) continue;
			if(j == 33) continue;
			if(j == 34) continue;
			//cout << j << " : " << args[j] << "<br>" <<  endl;	
		}
		
		//int j = 28;
		//cout << "--" <<  j << " : " << args[j] << "<br>" <<  endl;
		
	


	return EXIT_SUCCESS;

}
