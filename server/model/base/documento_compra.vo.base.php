<?php
/** Value Object file for table documento_compra.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author someone@caffeina.mx
  * @access public
  * @package docs
  * 
  */

class DocumentoCompra extends VO
{
	/**
	  * Constructor de DocumentoCompra
	  * 
	  * Para construir un objeto de tipo DocumentoCompra debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return DocumentoCompra
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
                    if(is_string($data))
                        $data = self::object_to_array(json_decode($data));


			if( isset($data['id_documento']) ){
				$this->id_documento = $data['id_documento'];
			}
			if( isset($data['id_compra']) ){
				$this->id_compra = $data['id_compra'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto DocumentoCompra en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_documento" => $this->id_documento,
			"id_compra" => $this->id_compra
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_documento
	  * 
	  * id del documento que se aplica a la compra<br>
	  * <b>Llave Primaria</b><br>
	  * @access public
	  * @var int(11)
	  */
	public $id_documento;

	/**
	  * id_compra
	  * 
	  * id de la compra<br>
	  * <b>Llave Primaria</b><br>
	  * @access public
	  * @var int(11)
	  */
	public $id_compra;

	/**
	  * getIdDocumento
	  * 
	  * Get the <i>id_documento</i> property for this object. Donde <i>id_documento</i> es id del documento que se aplica a la compra
	  * @return int(11)
	  */
	final public function getIdDocumento()
	{
		return $this->id_documento;
	}

	/**
	  * setIdDocumento( $id_documento )
	  * 
	  * Set the <i>id_documento</i> property for this object. Donde <i>id_documento</i> es id del documento que se aplica a la compra.
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

	/**
	  * getIdCompra
	  * 
	  * Get the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es id de la compra
	  * @return int(11)
	  */
	final public function getIdCompra()
	{
		return $this->id_compra;
	}

	/**
	  * setIdCompra( $id_compra )
	  * 
	  * Set the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es id de la compra.
	  * Una validacion basica se hara aqui para comprobar que <i>id_compra</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdCompra( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdCompra( $id_compra )
	{
		$this->id_compra = $id_compra;
	}

}
