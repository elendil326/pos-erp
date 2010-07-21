<?php
/** Value Object file for table impuesto.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */

class Impuesto
{
	/**
	  * Constructor de Impuesto
	  * 
	  * Para construir un objeto de tipo Impuesto debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return Impuesto
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_impuesto = $data['id_impuesto'];
			$this->descripcion = $data['descripcion'];
			$this->valor = $data['valor'];
			$this->id_sucursal = $data['id_sucursal'];
		}
	}

	/**
	  * id_impuesto
	  * 
	  * Campo no documentado<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_impuesto;

	/**
	  * descripcion
	  * 
	  * Campo no documentado<br>
	  * @access protected
	  * @var varchar(100)
	  */
	protected $descripcion;

	/**
	  * valor
	  * 
	  * Campo no documentado<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $valor;

	/**
	  * id_sucursal
	  * 
	  * Campo no documentado<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_sucursal;

	/**
	  * getIdImpuesto
	  * 
	  * Get the <i>id_impuesto</i> property for this object. Donde <i>id_impuesto</i> es Campo no documentado
	  * @return int(11)
	  */
	final public function getIdImpuesto()
	{
		return $this->id_impuesto;
	}

	/**
	  * setIdImpuesto( $id_impuesto )
	  * 
	  * Set the <i>id_impuesto</i> property for this object. Donde <i>id_impuesto</i> es Campo no documentado.
	  * Una validacion basica se hara aqui para comprobar que <i>id_impuesto</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdImpuesto( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdImpuesto( $id_impuesto )
	{
		$this->id_impuesto = $id_impuesto;
	}

	/**
	  * getDescripcion
	  * 
	  * Get the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es Campo no documentado
	  * @return varchar(100)
	  */
	final public function getDescripcion()
	{
		return $this->descripcion;
	}

	/**
	  * setDescripcion( $descripcion )
	  * 
	  * Set the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es Campo no documentado.
	  * Una validacion basica se hara aqui para comprobar que <i>descripcion</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	final public function setDescripcion( $descripcion )
	{
		$this->descripcion = $descripcion;
	}

	/**
	  * getValor
	  * 
	  * Get the <i>valor</i> property for this object. Donde <i>valor</i> es Campo no documentado
	  * @return int(11)
	  */
	final public function getValor()
	{
		return $this->valor;
	}

	/**
	  * setValor( $valor )
	  * 
	  * Set the <i>valor</i> property for this object. Donde <i>valor</i> es Campo no documentado.
	  * Una validacion basica se hara aqui para comprobar que <i>valor</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setValor( $valor )
	{
		$this->valor = $valor;
	}

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Campo no documentado
	  * @return int(11)
	  */
	final public function getIdSucursal()
	{
		return $this->id_sucursal;
	}

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Campo no documentado.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdSucursal( $id_sucursal )
	{
		$this->id_sucursal = $id_sucursal;
	}

}
