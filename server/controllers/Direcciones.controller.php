<?php




/**
  *
  *	
  * @todo validarDireccion debe depender de nivel de exigencia en los datos
  *
  **/        
class DireccionController{
	

	/**
	  *
	  * @param $direccion_vo Direccion
	  * 
	  **/
	public function validarDireccion( $direccion_vo )
	{

		if( ( $direccion_vo instanceof Direccion ) === false )	
		{
			throw new InvalidArgumentException("You must suply a valid VO object to validadDireccion() ");
		}


		//validar calle
		if( is_null( $direccion_vo->getCalle() ) )
		{
			return false;
		}

		//la calle es muy corta
		if( strlen( $direccion_vo->getCalle() )  < 5 )
		{
			return false;
		}

		//validar numero exterior
		if( is_null( $direccion_vo->getNumeroExterior() ))
		{
			return false;
		}

		//validar numero interior


		//varlidar referencia


		//validar colonia


		//validar id_ciudad
		if( is_null( $direccion_vo->getIdCiudad() ))
		{
			return null;
		}

		//validar que exista esa ciudad
		if( is_null( CiudadDAO::getByPK( $direccion_vo->getIdCiudad() ) ) )
		{
			return null;
		}


		//validar codigo_postal


		//validar telefono

		return true;
	}

}