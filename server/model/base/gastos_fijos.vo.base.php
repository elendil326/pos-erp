<?php
/** Value Object file for table gastos_fijos.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Anonymous
  * @access public
  * @package docs
  * 
  */

class GastosFijos extends VO
{
	/**
	  * Constructor de GastosFijos
	  * 
	  * Para construir un objeto de tipo GastosFijos debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return GastosFijos
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_gasto_fijo']) ){
				$this->id_gasto_fijo = $data['id_gasto_fijo'];
			}
			if( isset($data['nombre']) ){
				$this->nombre = $data['nombre'];
			}
			if( isset($data['descripcion']) ){
				$this->descripcion = $data['descripcion'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto GastosFijos en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_gasto_fijo" => $this->id_gasto_fijo,
			"nombre" => $this->nombre,
			"descripcion" => $this->descripcion
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_gasto_fijo
	  * 
	  *  [Campo no documentado]<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_gasto_fijo;

	/**
	  * nombre
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var varchar(64)
	  */
	protected $nombre;

	/**
	  * descripcion
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var varchar(128)
	  */
	protected $descripcion;

	/**
	  * getIdGastoFijo
	  * 
	  * Get the <i>id_gasto_fijo</i> property for this object. Donde <i>id_gasto_fijo</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	final public function getIdGastoFijo()
	{
		return $this->id_gasto_fijo;
	}

	/**
	  * setIdGastoFijo( $id_gasto_fijo )
	  * 
	  * Set the <i>id_gasto_fijo</i> property for this object. Donde <i>id_gasto_fijo</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_gasto_fijo</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdGastoFijo( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdGastoFijo( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdGastoFijo( $id_gasto_fijo )
	{
		$this->id_gasto_fijo = $id_gasto_fijo;
	}

	/**
	  * getNombre
	  * 
	  * Get the <i>nombre</i> property for this object. Donde <i>nombre</i> es  [Campo no documentado]
	  * @return varchar(64)
	  */
	final public function getNombre()
	{
		return $this->nombre;
	}

	/**
	  * setNombre( $nombre )
	  * 
	  * Set the <i>nombre</i> property for this object. Donde <i>nombre</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>nombre</i> es de tipo <i>varchar(64)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(64)
	  */
	final public function setNombre( $nombre )
	{
		$this->nombre = $nombre;
	}

	/**
	  * getDescripcion
	  * 
	  * Get the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es  [Campo no documentado]
	  * @return varchar(128)
	  */
	final public function getDescripcion()
	{
		return $this->descripcion;
	}

	/**
	  * setDescripcion( $descripcion )
	  * 
	  * Set the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>descripcion</i> es de tipo <i>varchar(128)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(128)
	  */
	final public function setDescripcion( $descripcion )
	{
		$this->descripcion = $descripcion;
	}

}
