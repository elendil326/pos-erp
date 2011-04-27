<?php
/** Value Object file for table impresiones.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author no author especified
  * @access public
  * @package docs
  * 
  */

class Impresiones extends VO
{
	/**
	  * Constructor de Impresiones
	  * 
	  * Para construir un objeto de tipo Impresiones debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return Impresiones
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_impresora']) ){
				$this->id_impresora = $data['id_impresora'];
			}
			if( isset($data['id_documento']) ){
				$this->id_documento = $data['id_documento'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto Impresiones en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_impresora" => $this->id_impresora,
			"id_documento" => $this->id_documento
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_impresora
	  * 
	  * id de la impresora<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_impresora;

	/**
	  * id_documento
	  * 
	  * id del documento<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_documento;

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
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdImpresora( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdImpresora( $id_impresora )
	{
		$this->id_impresora = $id_impresora;
	}

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
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdDocumento( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdDocumento( $id_documento )
	{
		$this->id_documento = $id_documento;
	}

}
