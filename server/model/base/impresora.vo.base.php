<?php
/** Value Object file for table impresora.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author caffeina
  * @access public
  * @package docs
  * 
  */

class Impresora extends VO
{
	/**
	  * Constructor de Impresora
	  * 
	  * Para construir un objeto de tipo Impresora debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return Impresora
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_impresora']) ){
				$this->id_impresora = $data['id_impresora'];
			}
			if( isset($data['id_sucursal']) ){
				$this->id_sucursal = $data['id_sucursal'];
			}
			if( isset($data['descripcion']) ){
				$this->descripcion = $data['descripcion'];
			}
			if( isset($data['identificador']) ){
				$this->identificador = $data['identificador'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto Impresora en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_impresora" => $this->id_impresora,
			"id_sucursal" => $this->id_sucursal,
			"descripcion" => $this->descripcion,
			"identificador" => $this->identificador
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_impresora
	  * 
	  * id de la impresora<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_impresora;

	/**
	  * id_sucursal
	  * 
	  * id de la sucursal donde se encuentra esta impresora<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_sucursal;

	/**
	  * descripcion
	  * 
	  * descripcion breve de la impresora<br>
	  * @access protected
	  * @var varchar(256)
	  */
	protected $descripcion;

	/**
	  * identificador
	  * 
	  * es el nombre de como esta dada de alta la impresora en la sucursal<br>
	  * @access protected
	  * @var varchar(128)
	  */
	protected $identificador;

	/**
	  * getIdImpresora
	  * 
	  * Get the <i>id_impresora</i> property for this object. Donde <i>id_impresora</i> es id de la impresora
	  * @return int(11)
	  */
	final public function getIdImpresora()
	{
		return $this->id_impresora;
	}

	/**
	  * setIdImpresora( $id_impresora )
	  * 
	  * Set the <i>id_impresora</i> property for this object. Donde <i>id_impresora</i> es id de la impresora.
	  * Una validacion basica se hara aqui para comprobar que <i>id_impresora</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdImpresora( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdImpresora( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdImpresora( $id_impresora )
	{
		$this->id_impresora = $id_impresora;
	}

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es id de la sucursal donde se encuentra esta impresora
	  * @return int(11)
	  */
	final public function getIdSucursal()
	{
		return $this->id_sucursal;
	}

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es id de la sucursal donde se encuentra esta impresora.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdSucursal( $id_sucursal )
	{
		$this->id_sucursal = $id_sucursal;
	}

	/**
	  * getDescripcion
	  * 
	  * Get the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es descripcion breve de la impresora
	  * @return varchar(256)
	  */
	final public function getDescripcion()
	{
		return $this->descripcion;
	}

	/**
	  * setDescripcion( $descripcion )
	  * 
	  * Set the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es descripcion breve de la impresora.
	  * Una validacion basica se hara aqui para comprobar que <i>descripcion</i> es de tipo <i>varchar(256)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(256)
	  */
	final public function setDescripcion( $descripcion )
	{
		$this->descripcion = $descripcion;
	}

	/**
	  * getIdentificador
	  * 
	  * Get the <i>identificador</i> property for this object. Donde <i>identificador</i> es es el nombre de como esta dada de alta la impresora en la sucursal
	  * @return varchar(128)
	  */
	final public function getIdentificador()
	{
		return $this->identificador;
	}

	/**
	  * setIdentificador( $identificador )
	  * 
	  * Set the <i>identificador</i> property for this object. Donde <i>identificador</i> es es el nombre de como esta dada de alta la impresora en la sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>identificador</i> es de tipo <i>varchar(128)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(128)
	  */
	final public function setIdentificador( $identificador )
	{
		$this->identificador = $identificador;
	}

}
