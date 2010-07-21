<?php
/** Value Object file for table factura_compra.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */

class FacturaCompra extends VO
{
	/**
	  * Constructor de FacturaCompra
	  * 
	  * Para construir un objeto de tipo FacturaCompra debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return FacturaCompra
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->folio = $data['folio'];
			$this->id_compra = $data['id_compra'];
		}
	}

	/**
	  * folio
	  * 
	  * Campo no documentado<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var varchar(15)
	  */
	protected $folio;

	/**
	  * id_compra
	  * 
	  * COMPRA A LA QUE CORRESPONDE LA FACTURA<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_compra;

	/**
	  * getFolio
	  * 
	  * Get the <i>folio</i> property for this object. Donde <i>folio</i> es Campo no documentado
	  * @return varchar(15)
	  */
	final public function getFolio()
	{
		return $this->folio;
	}

	/**
	  * setFolio( $folio )
	  * 
	  * Set the <i>folio</i> property for this object. Donde <i>folio</i> es Campo no documentado.
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
	  * getIdCompra
	  * 
	  * Get the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es COMPRA A LA QUE CORRESPONDE LA FACTURA
	  * @return int(11)
	  */
	final public function getIdCompra()
	{
		return $this->id_compra;
	}

	/**
	  * setIdCompra( $id_compra )
	  * 
	  * Set the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es COMPRA A LA QUE CORRESPONDE LA FACTURA.
	  * Una validacion basica se hara aqui para comprobar que <i>id_compra</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdCompra( $id_compra )
	{
		$this->id_compra = $id_compra;
	}

}
