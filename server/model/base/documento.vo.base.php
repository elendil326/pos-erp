<?php
/** Value Object file for table documento.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author no author especified
  * @access public
  * @package docs
  * 
  */

class Documento extends VO
{
	/**
	  * Constructor de Documento
	  * 
	  * Para construir un objeto de tipo Documento debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return Documento
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_documento']) ){
				$this->id_documento = $data['id_documento'];
			}
			if( isset($data['numero_de_impresiones']) ){
				$this->numero_de_impresiones = $data['numero_de_impresiones'];
			}
			if( isset($data['identificador']) ){
				$this->identificador = $data['identificador'];
			}
			if( isset($data['descripcion']) ){
				$this->descripcion = $data['descripcion'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto Documento en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_documento" => $this->id_documento,
			"numero_de_impresiones" => $this->numero_de_impresiones,
			"identificador" => $this->identificador,
			"descripcion" => $this->descripcion
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_documento
	  * 
	  * id del documento<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_documento;

	/**
	  * numero_de_impresiones
	  * 
	  * numero de veces que se tiene que imprmir este documento<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $numero_de_impresiones;

	/**
	  * identificador
	  * 
	  * identificador con el cual se le conocera en el sistema<br>
	  * @access protected
	  * @var varchar(128)
	  */
	protected $identificador;

	/**
	  * descripcion
	  * 
	  * descripcion breve del documento<br>
	  * @access protected
	  * @var varchar(256)
	  */
	protected $descripcion;

	/**
	  * getIdDocumento
	  * 
	  * Get the <i>id_documento</i> property for this object. Donde <i>id_documento</i> es id del documento
	  * @return int(11)
	  */
	final public function getIdDocumento()
	{
		return $this->id_documento;
	}

	/**
	  * setIdDocumento( $id_documento )
	  * 
	  * Set the <i>id_documento</i> property for this object. Donde <i>id_documento</i> es id del documento.
	  * Una validacion basica se hara aqui para comprobar que <i>id_documento</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdDocumento( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdDocumento( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdDocumento( $id_documento )
	{
		$this->id_documento = $id_documento;
	}

	/**
	  * getNumeroDeImpresiones
	  * 
	  * Get the <i>numero_de_impresiones</i> property for this object. Donde <i>numero_de_impresiones</i> es numero de veces que se tiene que imprmir este documento
	  * @return int(11)
	  */
	final public function getNumeroDeImpresiones()
	{
		return $this->numero_de_impresiones;
	}

	/**
	  * setNumeroDeImpresiones( $numero_de_impresiones )
	  * 
	  * Set the <i>numero_de_impresiones</i> property for this object. Donde <i>numero_de_impresiones</i> es numero de veces que se tiene que imprmir este documento.
	  * Una validacion basica se hara aqui para comprobar que <i>numero_de_impresiones</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setNumeroDeImpresiones( $numero_de_impresiones )
	{
		$this->numero_de_impresiones = $numero_de_impresiones;
	}

	/**
	  * getIdentificador
	  * 
	  * Get the <i>identificador</i> property for this object. Donde <i>identificador</i> es identificador con el cual se le conocera en el sistema
	  * @return varchar(128)
	  */
	final public function getIdentificador()
	{
		return $this->identificador;
	}

	/**
	  * setIdentificador( $identificador )
	  * 
	  * Set the <i>identificador</i> property for this object. Donde <i>identificador</i> es identificador con el cual se le conocera en el sistema.
	  * Una validacion basica se hara aqui para comprobar que <i>identificador</i> es de tipo <i>varchar(128)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(128)
	  */
	final public function setIdentificador( $identificador )
	{
		$this->identificador = $identificador;
	}

	/**
	  * getDescripcion
	  * 
	  * Get the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es descripcion breve del documento
	  * @return varchar(256)
	  */
	final public function getDescripcion()
	{
		return $this->descripcion;
	}

	/**
	  * setDescripcion( $descripcion )
	  * 
	  * Set the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es descripcion breve del documento.
	  * Una validacion basica se hara aqui para comprobar que <i>descripcion</i> es de tipo <i>varchar(256)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(256)
	  */
	final public function setDescripcion( $descripcion )
	{
		$this->descripcion = $descripcion;
	}

}
