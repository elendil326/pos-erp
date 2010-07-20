<?php
/* Value Object file for table GruposPermisos */

class GruposPermisos
{
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
	  * es llave primara 
	  * @var int(11) Campo no documentado
	  */
	protected $id_grupo;

	/**
	  * id_permiso
	  * es llave primara 
	  * @var int(11) Campo no documentado
	  */
	protected $id_permiso;

	/**
	  * es llave primara 
	  * @return int(11)
	  */
	final public function getIdGrupo()
	{
		return $this->id_grupo;
	}

	/**
	  * @param int(11)
	  */
	final public function setIdGrupo( $id_grupo )
	{
		$this->id_grupo = $id_grupo;
	}

	/**
	  * es llave primara 
	  * @return int(11)
	  */
	final public function getIdPermiso()
	{
		return $this->id_permiso;
	}

	/**
	  * @param int(11)
	  */
	final public function setIdPermiso( $id_permiso )
	{
		$this->id_permiso = $id_permiso;
	}

}
