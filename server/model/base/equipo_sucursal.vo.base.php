<?php
/** Value Object file for table equipo_sucursal.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author no author especified
  * @access public
  * @package docs
  * 
  */

class EquipoSucursal extends VO
{
	/**
	  * Constructor de EquipoSucursal
	  * 
	  * Para construir un objeto de tipo EquipoSucursal debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return EquipoSucursal
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_equipo']) ){
				$this->id_equipo = $data['id_equipo'];
			}
			if( isset($data['id_sucursal']) ){
				$this->id_sucursal = $data['id_sucursal'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto EquipoSucursal en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_equipo" => $this->id_equipo,
			"id_sucursal" => $this->id_sucursal
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_equipo
	  * 
	  * identificador del equipo <br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(6)
	  */
	protected $id_equipo;

	/**
	  * id_sucursal
	  * 
	  * identifica una sucursal<br>
	  * @access protected
	  * @var int(6)
	  */
	protected $id_sucursal;

	/**
	  * getIdEquipo
	  * 
	  * Get the <i>id_equipo</i> property for this object. Donde <i>id_equipo</i> es identificador del equipo 
	  * @return int(6)
	  */
	final public function getIdEquipo()
	{
		return $this->id_equipo;
	}

	/**
	  * setIdEquipo( $id_equipo )
	  * 
	  * Set the <i>id_equipo</i> property for this object. Donde <i>id_equipo</i> es identificador del equipo .
	  * Una validacion basica se hara aqui para comprobar que <i>id_equipo</i> es de tipo <i>int(6)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdEquipo( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(6)
	  */
	final public function setIdEquipo( $id_equipo )
	{
		$this->id_equipo = $id_equipo;
	}

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es identifica una sucursal
	  * @return int(6)
	  */
	final public function getIdSucursal()
	{
		return $this->id_sucursal;
	}

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es identifica una sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(6)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(6)
	  */
	final public function setIdSucursal( $id_sucursal )
	{
		$this->id_sucursal = $id_sucursal;
	}

}
