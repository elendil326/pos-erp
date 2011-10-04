<?php
/** Value Object file for table sucursal_empresa.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Andres
  * @access public
  * @package docs
  * 
  */

class SucursalEmpresa extends VO
{
	/**
	  * Constructor de SucursalEmpresa
	  * 
	  * Para construir un objeto de tipo SucursalEmpresa debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return SucursalEmpresa
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_sucursal']) ){
				$this->id_sucursal = $data['id_sucursal'];
			}
			if( isset($data['id_empresa']) ){
				$this->id_empresa = $data['id_empresa'];
			}
			if( isset($data['margen_utilidad']) ){
				$this->margen_utilidad = $data['margen_utilidad'];
			}
			if( isset($data['descuento']) ){
				$this->descuento = $data['descuento'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto SucursalEmpresa en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_sucursal" => $this->id_sucursal,
			"id_empresa" => $this->id_empresa,
			"margen_utilidad" => $this->margen_utilidad,
			"descuento" => $this->descuento
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_sucursal
	  * 
	  *  [Campo no documentado]<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_sucursal;

	/**
	  * id_empresa
	  * 
	  *  [Campo no documentado]<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_empresa;

	/**
	  * margen_utilidad
	  * 
	  * Porcentaje de margen de utilidad que le gana esta sucursal a los productos de esta empresa<br>
	  * @access protected
	  * @var float
	  */
	protected $margen_utilidad;

	/**
	  * descuento
	  * 
	  * Descuento que se aplicara a todos los productos de esta empresa en esta sucursal<br>
	  * @access protected
	  * @var float
	  */
	protected $descuento;

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	final public function getIdSucursal()
	{
		return $this->id_sucursal;
	}

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdSucursal( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdSucursal( $id_sucursal )
	{
		$this->id_sucursal = $id_sucursal;
	}

	/**
	  * getIdEmpresa
	  * 
	  * Get the <i>id_empresa</i> property for this object. Donde <i>id_empresa</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	final public function getIdEmpresa()
	{
		return $this->id_empresa;
	}

	/**
	  * setIdEmpresa( $id_empresa )
	  * 
	  * Set the <i>id_empresa</i> property for this object. Donde <i>id_empresa</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_empresa</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdEmpresa( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdEmpresa( $id_empresa )
	{
		$this->id_empresa = $id_empresa;
	}

	/**
	  * getMargenUtilidad
	  * 
	  * Get the <i>margen_utilidad</i> property for this object. Donde <i>margen_utilidad</i> es Porcentaje de margen de utilidad que le gana esta sucursal a los productos de esta empresa
	  * @return float
	  */
	final public function getMargenUtilidad()
	{
		return $this->margen_utilidad;
	}

	/**
	  * setMargenUtilidad( $margen_utilidad )
	  * 
	  * Set the <i>margen_utilidad</i> property for this object. Donde <i>margen_utilidad</i> es Porcentaje de margen de utilidad que le gana esta sucursal a los productos de esta empresa.
	  * Una validacion basica se hara aqui para comprobar que <i>margen_utilidad</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setMargenUtilidad( $margen_utilidad )
	{
		$this->margen_utilidad = $margen_utilidad;
	}

	/**
	  * getDescuento
	  * 
	  * Get the <i>descuento</i> property for this object. Donde <i>descuento</i> es Descuento que se aplicara a todos los productos de esta empresa en esta sucursal
	  * @return float
	  */
	final public function getDescuento()
	{
		return $this->descuento;
	}

	/**
	  * setDescuento( $descuento )
	  * 
	  * Set the <i>descuento</i> property for this object. Donde <i>descuento</i> es Descuento que se aplicara a todos los productos de esta empresa en esta sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>descuento</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setDescuento( $descuento )
	{
		$this->descuento = $descuento;
	}

}
