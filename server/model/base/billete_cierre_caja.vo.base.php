<?php
/** Value Object file for table billete_cierre_caja.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Andres
  * @access public
  * @package docs
  * 
  */

class BilleteCierreCaja extends VO
{
	/**
	  * Constructor de BilleteCierreCaja
	  * 
	  * Para construir un objeto de tipo BilleteCierreCaja debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return BilleteCierreCaja
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_billete']) ){
				$this->id_billete = $data['id_billete'];
			}
			if( isset($data['id_cierre_caja']) ){
				$this->id_cierre_caja = $data['id_cierre_caja'];
			}
			if( isset($data['cantidad']) ){
				$this->cantidad = $data['cantidad'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto BilleteCierreCaja en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_billete" => $this->id_billete,
			"id_cierre_caja" => $this->id_cierre_caja,
			"cantidad" => $this->cantidad
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_billete
	  * 
	  *  [Campo no documentado]<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_billete;

	/**
	  * id_cierre_caja
	  * 
	  *  [Campo no documentado]<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_cierre_caja;

	/**
	  * cantidad
	  * 
	  * Cantidad de billetes encontrados en el cierre de caja<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $cantidad;

	/**
	  * getIdBillete
	  * 
	  * Get the <i>id_billete</i> property for this object. Donde <i>id_billete</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	final public function getIdBillete()
	{
		return $this->id_billete;
	}

	/**
	  * setIdBillete( $id_billete )
	  * 
	  * Set the <i>id_billete</i> property for this object. Donde <i>id_billete</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_billete</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdBillete( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdBillete( $id_billete )
	{
		$this->id_billete = $id_billete;
	}

	/**
	  * getIdCierreCaja
	  * 
	  * Get the <i>id_cierre_caja</i> property for this object. Donde <i>id_cierre_caja</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	final public function getIdCierreCaja()
	{
		return $this->id_cierre_caja;
	}

	/**
	  * setIdCierreCaja( $id_cierre_caja )
	  * 
	  * Set the <i>id_cierre_caja</i> property for this object. Donde <i>id_cierre_caja</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_cierre_caja</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdCierreCaja( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdCierreCaja( $id_cierre_caja )
	{
		$this->id_cierre_caja = $id_cierre_caja;
	}

	/**
	  * getCantidad
	  * 
	  * Get the <i>cantidad</i> property for this object. Donde <i>cantidad</i> es Cantidad de billetes encontrados en el cierre de caja
	  * @return int(11)
	  */
	final public function getCantidad()
	{
		return $this->cantidad;
	}

	/**
	  * setCantidad( $cantidad )
	  * 
	  * Set the <i>cantidad</i> property for this object. Donde <i>cantidad</i> es Cantidad de billetes encontrados en el cierre de caja.
	  * Una validacion basica se hara aqui para comprobar que <i>cantidad</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setCantidad( $cantidad )
	{
		$this->cantidad = $cantidad;
	}

}
