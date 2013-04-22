<?php
/** Value Object file for table catalogo_cuentas.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Anonymous
  * @access public
  * @package docs
  * 
  */

class CatalogoCuentas extends VO
{
	/**
	  * Constructor de CatalogoCuentas
	  * 
	  * Para construir un objeto de tipo CatalogoCuentas debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return CatalogoCuentas
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_catalogo']) ){
				$this->id_catalogo = $data['id_catalogo'];
			}
			if( isset($data['descripcion']) ){
				$this->descripcion = $data['descripcion'];
			}
			if( isset($data['id_empresa']) ){
				$this->id_empresa = $data['id_empresa'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto CatalogoCuentas en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_catalogo" => $this->id_catalogo,
			"descripcion" => $this->descripcion,
			"id_empresa" => $this->id_empresa
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_catalogo
	  * 
	  * El id del catalogo de cuentas<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access public
	  * @var int(11)
	  */
	public $id_catalogo;

	/**
	  * descripcion
	  * 
	  * La descripción del catalogo de cuentas.<br>
	  * @access public
	  * @var varchar(150)
	  */
	public $descripcion;

	/**
	  * id_empresa
	  * 
	  * El id de la empresa a la que va vinculada ésta cuenta<br>
	  * @access public
	  * @var int(11)
	  */
	public $id_empresa;

	/**
	  * getIdCatalogo
	  * 
	  * Get the <i>id_catalogo</i> property for this object. Donde <i>id_catalogo</i> es El id del catalogo de cuentas
	  * @return int(11)
	  */
	final public function getIdCatalogo()
	{
		return $this->id_catalogo;
	}

	/**
	  * setIdCatalogo( $id_catalogo )
	  * 
	  * Set the <i>id_catalogo</i> property for this object. Donde <i>id_catalogo</i> es El id del catalogo de cuentas.
	  * Una validacion basica se hara aqui para comprobar que <i>id_catalogo</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdCatalogo( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdCatalogo( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdCatalogo( $id_catalogo )
	{
		$this->id_catalogo = $id_catalogo;
	}

	/**
	  * getDescripcion
	  * 
	  * Get the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es La descripción del catalogo de cuentas.
	  * @return varchar(150)
	  */
	final public function getDescripcion()
	{
		return $this->descripcion;
	}

	/**
	  * setDescripcion( $descripcion )
	  * 
	  * Set the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es La descripción del catalogo de cuentas..
	  * Una validacion basica se hara aqui para comprobar que <i>descripcion</i> es de tipo <i>varchar(150)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(150)
	  */
	final public function setDescripcion( $descripcion )
	{
		$this->descripcion = $descripcion;
	}

	/**
	  * getIdEmpresa
	  * 
	  * Get the <i>id_empresa</i> property for this object. Donde <i>id_empresa</i> es El id de la empresa a la que va vinculada ésta cuenta
	  * @return int(11)
	  */
	final public function getIdEmpresa()
	{
		return $this->id_empresa;
	}

	/**
	  * setIdEmpresa( $id_empresa )
	  * 
	  * Set the <i>id_empresa</i> property for this object. Donde <i>id_empresa</i> es El id de la empresa a la que va vinculada ésta cuenta.
	  * Una validacion basica se hara aqui para comprobar que <i>id_empresa</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdEmpresa( $id_empresa )
	{
		$this->id_empresa = $id_empresa;
	}

}
