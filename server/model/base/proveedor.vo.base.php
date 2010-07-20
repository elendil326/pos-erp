<?php
/* Value Object file for table Proveedor */

class Proveedor
{
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_proveedor = $data['id_proveedor'];
			$this->rfc = $data['rfc'];
			$this->nombre = $data['nombre'];
			$this->direccion = $data['direccion'];
			$this->telefono = $data['telefono'];
			$this->e_mail = $data['e_mail'];
		}
	}

	/**
	  * id_proveedor
	  * es llave primara 
	  * es de auto incremento 
	  * @var int(11) identificador del proveedor
	  */
	protected $id_proveedor;

	/**
	  * rfc
	  * @var varchar(20) rfc del proveedor
	  */
	protected $rfc;

	/**
	  * nombre
	  * @var varchar(30) nombre del proveedor
	  */
	protected $nombre;

	/**
	  * direccion
	  * @var varchar(100) direccion del proveedor
	  */
	protected $direccion;

	/**
	  * telefono
	  * @var varchar(20) telefono
	  */
	protected $telefono;

	/**
	  * e_mail
	  * @var varchar(60) email del provedor
	  */
	protected $e_mail;

	/**
	  * es llave primara 
	  * @return int(11)
	  */
	final public function getIdProveedor()
	{
		return $this->id_proveedor;
	}

	/**
	  * @param int(11)
	  */
	final public function setIdProveedor( $id_proveedor )
	{
		$this->id_proveedor = $id_proveedor;
	}

	/**
	  * @return varchar(20)
	  */
	final public function getRfc()
	{
		return $this->rfc;
	}

	/**
	  * @param varchar(20)
	  */
	final public function setRfc( $rfc )
	{
		$this->rfc = $rfc;
	}

	/**
	  * @return varchar(30)
	  */
	final public function getNombre()
	{
		return $this->nombre;
	}

	/**
	  * @param varchar(30)
	  */
	final public function setNombre( $nombre )
	{
		$this->nombre = $nombre;
	}

	/**
	  * @return varchar(100)
	  */
	final public function getDireccion()
	{
		return $this->direccion;
	}

	/**
	  * @param varchar(100)
	  */
	final public function setDireccion( $direccion )
	{
		$this->direccion = $direccion;
	}

	/**
	  * @return varchar(20)
	  */
	final public function getTelefono()
	{
		return $this->telefono;
	}

	/**
	  * @param varchar(20)
	  */
	final public function setTelefono( $telefono )
	{
		$this->telefono = $telefono;
	}

	/**
	  * @return varchar(60)
	  */
	final public function getEMail()
	{
		return $this->e_mail;
	}

	/**
	  * @param varchar(60)
	  */
	final public function setEMail( $e_mail )
	{
		$this->e_mail = $e_mail;
	}

}
