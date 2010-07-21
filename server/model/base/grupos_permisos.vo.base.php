<?php
/** Value Object file for table grupos_permisos.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */

class GruposPermisos
{
	/**
	  * Constructor de GruposPermisos
	  * 
	  * Para construir un objeto de tipo GruposPermisos debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return GruposPermisos
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_grupo = $data['id_grupo'];
			$this->id_permiso = $data['id_permiso'];
		}
	}

	/**
	  * id_grupo
	  * 
	  * Campo no documentado<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_grupo;

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
	  * getIdGrupo
	  * 
	  * Get the <i>id_grupo</i> property for this object. Donde <i>id_grupo</i> es Campo no documentado
	  * @return int(11)
	  */
	final public function getIdGrupo()
	{
		return $this->id_grupo;
	}

	/**
	  * setIdGrupo( $id_grupo )
	  * 
	  * Set the <i>id_grupo</i> property for this object. Donde <i>id_grupo</i> es Campo no documentado.
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

}
