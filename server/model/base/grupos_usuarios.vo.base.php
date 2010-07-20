<?php
/* Value Object file for table GruposUsuarios */

class GruposUsuarios
{
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_grupo = $data['id_grupo'];
			$this->id_usuario = $data['id_usuario'];
		}
	}

	/**
	  * id_grupo
	  * es llave primara 
	  * @var int(11) Campo no documentado
	  */
	protected $id_grupo;

	/**
	  * id_usuario
	  * es llave primara 
	  * @var int(11) Campo no documentado
	  */
	protected $id_usuario;

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
	final public function getIdUsuario()
	{
		return $this->id_usuario;
	}

	/**
	  * @param int(11)
	  */
	final public function setIdUsuario( $id_usuario )
	{
		$this->id_usuario = $id_usuario;
	}

}
