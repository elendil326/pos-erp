

class PosErp{

    private static PosErp $singleton_obj;
    private boolean $waiting;
    private String $instance_token;
    private String $url;
    private boolean $incoming_login_results;
    private String $at;

    private PosErp( $i_token){
        this.waiting = false;
       	this.instance_token = $i_token;
        this.url = "http://pos2.labs2.caffeina.mx/";
        this.incoming_login_results = false;
        this.at = null;
    }



    public static PosErp getInstance( $i_token  ){

        if(this.$singleton_obj == null){

            if($i_token == null){
                throw new Exception("You must pass your instance token when calling getInstance() for the first time.");
            }

            this.$singleton_obj = new PosErp( $i_token );
        }

        return this.$singleton_obj;  
    }

}


class ApiResponse{

	public String get(String key){

	}

}

class ApiRequest{
	ArrayList <String> params;

	ApiRequest (){
		params = new ArrayList<String>();
	}

	public void set(String key, String value){

	}
}


class Test{

	public static void main(String ... args){

		PosErp pos = PosErp.getInstance("a1d25245faa62545dfasd4fasf");

		ApiRequest r = new ApiRequest();

		r.set("usuario", "1");
		r.set("password", "123");

		ApiRespose res = pos.api("/api/sesion/iniciar", r);

		
	}

}