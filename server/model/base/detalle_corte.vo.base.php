<?php
/** Value Object file for table detalle_corte.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */

class DetalleCorte extends VO
{
	/**
	  * Constructor de DetalleCorte
	  * 
	  * Para construir un objeto de tipo DetalleCorte debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return DetalleCorte
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->num_corte = $data['num_corte'];
			$this->nombre = $data['nombre'];
			$this->total = $data['total'];
			$this->deben = $data['deben'];
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto DetalleCorte en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	  public function __toString( )
	  { 
		$vec = array( 
		"num_corte" => $this->num_corte,
		"nombre" => $this->nombre,
		"total" => $this->total,
		"deben" => $this->deben
		); 
	return json_encode($vec); 
	}
	/**
	  * num_corte
	  * 
	  * id del corte al que hace referencia<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $num_corte;

	/**
	  * nombre
	  * 
	  * nombre del encargado de sucursal al momento del corte<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var varchar(100)
	  */
	protected $nombre;

	/**
	  * total
	  * 
	  * total que le corresponde al encargado al momento del corte<br>
	  * @access protected
	  * @var float
	  */
	protected $total;

	/**
	  * deben
	  * 
	  * lo que deben en la sucursal del encargado al momento del corte<br>
	  * @access protected
	  * @var float
	  */
	protected $deben;

	/**
	  * getNumCorte
	  * 
	  * Get the <i>num_corte</i> property for this object. Donde <i>num_corte</i> es id del corte al que hace referencia
	  * @return int(11)
	  */
	final public function getNumCorte()
	{
		return $this->num_corte;
	}

	/**
	  * setNumCorte( $num_corte )
	  * 
	  * Set the <i>num_corte</i> property for this object. Donde <i>num_corte</i> es id del corte al que hace referencia.
	  * Una validacion basica se hara aqui para comprobar que <i>num_corte</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setNumCorte( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setNumCorte( $num_corte )
	{
		$this->num_corte = $num_corte;
	}

	/**
	  * getNombre
	  * 
	  * Get the <i>nombre</i> property for this object. Donde <i>nombre</i> es nombre del encargado de sucursal al momento del corte
	  * @return varchar(100)
	  */
	final public function getNombre()
	{
		return $this->nombre;
	}

	/**
	  * setNombre( $nombre )
	  * 
	  * Set the <i>nombre</i> property for this object. Donde <i>nombre</i> es nombre del encargado de sucursal al momento del corte.
	  * Una validacion basica se hara aqui para comprobar que <i>nombre</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setNombre( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param varchar(100)
	  */
	final public function setNombre( $nombre )
	{
		$this->nombre = $nombre;
	}

	/**
	  * getTotal
	  * 
	  * Get the <i>total</i> property for this object. Donde <i>total</i> es total que le corresponde al encargado al momento del corte
	  * @return float
	  */
	final public function getTotal()
	{
		return $this->total;
	}

	/**
	  * setTotal( $total )
	  * 
	  * Set the <i>total</i> property for this object. Donde <i>total</i> es total que le corresponde al encargado al momento del corte.
	  * Una validacion basica se hara aqui para comprobar que <i>total</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setTotal( $total )
	{
		$this->total = $total;
	}

	/**
	  * getDeben
	  * 
	  * Get the <i>deben</i> property for this object. Donde <i>deben</i> es lo que deben en la sucursal del encargado al momento del corte
	  * @return float
	  */
	final public function getDeben()
	{
		return $this->deben;
	}

	/**
	  * setDeben( $deben )
	  * 
	  * Set the <i>deben</i> property for this object. Donde <i>deben</i> es lo que deben en la sucursal del encargado al momento del corte.
	  * Una validacion basica se hara aqui para comprobar que <i>deben</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setDeben( $deben )
	{
		$this->deben = $deben;
	}

}
