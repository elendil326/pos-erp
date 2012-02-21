<?php



class ValidacionesController {
	

	/*
	 * Validar que $a sea una fecha valida en unix
	 *
	 */
	public static function validarFechaUnix($a){
		
	}


	/*
     *
     * Validar que $a sea una fehca valida que sea 
     * despues de justo ahora con por lo menos
     * una holgura de $b. Si $b es null, se calculara
     * con una holgura default de 1 dia.
     *
     */
    public static function validarFechaUnixEnFuturo($a, $b){
    	
    }


    /*
     * Valida que la longitud de la cadena $a, sea entre
     * $i y $j inclusivo. 
     *
     *
     */
    public static function validarLongitudDeCadena($a, $i, $j = 1024){
    	return (is_string($a) && (strlen($a) >= $i && strlen($a) <= $j ) );
    }


    /*
     *
     * Validar que un numero $n sea entero y este entre el rango
     * de $i y $j inclusivo.
     *
     */
     public static function validarEntero($n, $i, $j){
     	return ( is_int($n) && ( $n >= $i && $n <= $j )  );
     }
     
     /*
     *
     * Validar que un numero $n este entre el rango
     * de $i y $j inclusivo.
     *
     */
     public static function validarNumero($n, $i, $j){
     	return ( is_numeric($n) && ( $n >= $i && $n <= $j )  );
     }


     /*
      *
      *
      *
      *
      */
      
}