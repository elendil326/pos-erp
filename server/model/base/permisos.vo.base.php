<?php
/** Value Object file for table permisos.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */

class Permisos extends VO
{
	/**
	  * Constructor de Permisos
	  * 
	  * Para construir un objeto de tipo Permisos debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return Permisos
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_permiso = $data['id_permiso'];
			$this->nombre = $data['nombre'];
			$this->descripcion = $data['descripcion'];
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto Permisos en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array();
		array_push($vec, array( 
		"id_permiso" => $this->id_permiso,
		"nombre" => $this->nombre,
		"descripcion" => $this->descripcion
		)); 
	return json_encode($vec, true); 
	}
	
	/**
	  * id_permiso
	  * 
	  * Campo no documentado<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_permiso;

	/**
	  * nombre
	  * 
	  * Campo no documentado<br>
	  * @access protected
	  * @var varchar(45)
	  */
	protected $nombre;

	/**
	  * descripcion
	  * 
	  * Campo no documentado<br>
	  * @access protected
	  * @var varchar(45)
	  */
	protected $descripcion;

	/**
	  * getIdPermiso
	  * 
	  * Get the <i>id_permiso</i> property for this object. Donde <i>id_permiso</i> es Campo no documentado
	  * @return int(11)
	  */
	final public function getIdPermiso()
	{
		return $this->id_permiso;
	}

	/**
	  * setIdPermiso( $id_permiso )
	  * 
	  * Set the <i>id_permiso</i> property for this object. Donde <i>id_permiso</i> es Campo no documentado.
	  * Una validacion basica se hara aqui para comprobar que <i>id_permiso</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdPermiso( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdPermiso( $id_permiso )
	{
		$this->id_permiso = $id_permiso;
	}

	/**
	  * getNombre
	  * 
	  * Get the <i>nombre</i> property for this object. Donde <i>nombre</i> es Campo no documentado
	  * @return varchar(45)
	  */
	final public function getNombre()
	{
		return $this->nombre;
	}

	/**
	  * setNombre( $nombre )
	  * 
	  * Set the <i>nombre</i> property for this object. Donde <i>nombre</i> es Campo no documentado.
	  * Una validacion basica se hara aqui para comprobar que <i>nombre</i> es de tipo <i>varchar(45)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(45)
	  */
	final public function setNombre( $nombre )
	{
		$this->nombre = $nombre;
	}

	/**
	  * getDescripcion
	  * 
	  * Get the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es Campo no documentado
	  * @return varchar(45)
	  */
	final public function getDescripcion()
	{
		return $this->descripcion;
	}

	/**
	  * setDescripcion( $descripcion )
	  * 
	  * Set the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es Campo no documentado.
	  * Una validacion basica se hara aqui para comprobar que <i>descripcion</i> es de tipo <i>varchar(45)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(45)
	  */
	final public function setDescripcion( $descripcion )
	{
		$this->descripcion = $descripcion;
	}

}
