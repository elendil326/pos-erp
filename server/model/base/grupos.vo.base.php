<?php
/** Value Object file for table grupos.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author no author especified
  * @access public
  * @package docs
  * 
  */

class Grupos extends VO
{
	/**
	  * Constructor de Grupos
	  * 
	  * Para construir un objeto de tipo Grupos debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return Grupos
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_grupo']) ){
				$this->id_grupo = $data['id_grupo'];
			}
			if( isset($data['nombre']) ){
				$this->nombre = $data['nombre'];
			}
			if( isset($data['descripcion']) ){
				$this->descripcion = $data['descripcion'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto Grupos en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_grupo" => $this->id_grupo,
			"nombre" => $this->nombre,
			"descripcion" => $this->descripcion
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_grupo
	  * 
	  *  [Campo no documentado]<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_grupo;

	/**
	  * nombre
	  * 
	  * Nombre del Grupo<br>
	  * @access protected
	  * @var varchar(45)
	  */
	protected $nombre;

	/**
	  * descripcion
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var varchar(256)
	  */
	protected $descripcion;

	/**
	  * getIdGrupo
	  * 
	  * Get the <i>id_grupo</i> property for this object. Donde <i>id_grupo</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	final public function getIdGrupo()
	{
		return $this->id_grupo;
	}

	/**
	  * setIdGrupo( $id_grupo )
	  * 
	  * Set the <i>id_grupo</i> property for this object. Donde <i>id_grupo</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_grupo</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdGrupo( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdGrupo( $id_grupo )
	{
		$this->id_grupo = $id_grupo;
	}

	/**
	  * getNombre
	  * 
	  * Get the <i>nombre</i> property for this object. Donde <i>nombre</i> es Nombre del Grupo
	  * @return varchar(45)
	  */
	final public function getNombre()
	{
		return $this->nombre;
	}

	/**
	  * setNombre( $nombre )
	  * 
	  * Set the <i>nombre</i> property for this object. Donde <i>nombre</i> es Nombre del Grupo.
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
	  * Get the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es  [Campo no documentado]
	  * @return varchar(256)
	  */
	final public function getDescripcion()
	{
		return $this->descripcion;
	}

	/**
	  * setDescripcion( $descripcion )
	  * 
	  * Set the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>descripcion</i> es de tipo <i>varchar(256)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(256)
	  */
	final public function setDescripcion( $descripcion )
	{
		$this->descripcion = $descripcion;
	}

}
