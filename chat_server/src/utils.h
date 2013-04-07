#include <sstream>
#include <cstdio>


using namespace std;

template <class T>
inline std::string to_string (const T& t)
{
	std::stringstream ss;
	ss << t;
	return ss.str();
}



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


