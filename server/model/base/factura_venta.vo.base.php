<?php
/** Value Object file for table factura_venta.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */

class FacturaVenta
{
	/**
	  * Constructor de FacturaVenta
	  * 
	  * Para construir un objeto de tipo FacturaVenta debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return FacturaVenta
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->folio = $data['folio'];
			$this->id_venta = $data['id_venta'];
		}
	}

	/**
	  * folio
	  * 
	  * folio que tiene la factura<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var varchar(15)
	  */
	protected $folio;

	/**
	  * id_venta
	  * 
	  * venta a la cual corresponde la factura<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_venta;

	/**
	  * getFolio
	  * 
	  * Get the <i>folio</i> property for this object. Donde <i>folio</i> es folio que tiene la factura
	  * @return varchar(15)
	  */
	final public function getFolio()
	{
		return $this->folio;
	}

	/**
	  * setFolio( $folio )
	  * 
	  * Set the <i>folio</i> property for this object. Donde <i>folio</i> es folio que tiene la factura.
	  * Una validacion basica se hara aqui para comprobar que <i>folio</i> es de tipo <i>varchar(15)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setFolio( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param varchar(15)
	  */
	final public function setFolio( $folio )
	{
		$this->folio = $folio;
	}

	/**
	  * getIdVenta
	  * 
	  * Get the <i>id_venta</i> property for this object. Donde <i>id_venta</i> es venta a la cual corresponde la factura
	  * @return int(11)
	  */
	final public function getIdVenta()
	{
		return $this->id_venta;
	}

	/**
	  * setIdVenta( $id_venta )
	  * 
	  * Set the <i>id_venta</i> property for this object. Donde <i>id_venta</i> es venta a la cual corresponde la factura.
	  * Una validacion basica se hara aqui para comprobar que <i>id_venta</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdVenta( $id_venta )
	{
		$this->id_venta = $id_venta;
	}

}
