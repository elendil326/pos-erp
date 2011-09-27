<?php



class InstancesController {

	

/*  ****************************************************
	*	Lista de instancias 
	**************************************************** */
	public static function lista()
	{	
		global $POS_CONFIG;

		$sql = "SELECT * FROM instances;";

		$rs = $POS_CONFIG["CORE_CONN"]->Execute($sql);
		
		return $rs->GetArray();
	}


	public static function nuevaInstancia(  )
	{
		
		

	}


}