<?php
/* Value Object file for table Cliente */

class Cliente
{
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_cliente = $data['id_cliente'];
			$this->rfc = $data['rfc'];
			$this->nombre = $data['nombre'];
			$this->direccion = $data['direccion'];
			$this->telefono = $data['telefono'];
			$this->e_mail = $data['e_mail'];
			$this->limite_credito = $data['limite_credito'];
			$this->descuento = $data['descuento'];
		}
	}

	/**
	  * id_cliente
	  * es llave primara 
	  * es de auto incremento 
	  * @var int(11) identificador del cliente
	  */
	protected $id_cliente;

	/**
	  * rfc
	  * @var varchar(20) rfc del cliente si es que tiene
	  */
	protected $rfc;

	/**
	  * nombre
	  * @var varchar(100) nombre del cliente
	  */
	protected $nombre;

	/**
	  * direccion
	  * @var varchar(300) domicilio del cliente calle, no, colonia
	  */
	protected $direccion;

	/**
	  * telefono
	  * @var varchar(25) Telefono del cliete
	  */
	protected $telefono;

	/**
	  * e_mail
	  * @var varchar(60) dias de credito para que pague el cliente
	  */
	protected $e_mail;

	/**
	  * limite_credito
	  * @var float Limite de credito otorgado al cliente
	  */
	protected $limite_credito;

	/**
	  * descuento
	  * @var tinyint(4) Taza porcentual de descuento de 0 a 100
	  */
	protected $descuento;

	/**
	  * es llave primara 
	  * @return int(11)
	  */
	final public function getIdCliente()
	{
		return $this->id_cliente;
	}

	/**
	  * @param int(11)
	  */
	final public function setIdCliente( $id_cliente )
	{
		$this->id_cliente = $id_cliente;
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
	  * @return varchar(100)
	  */
	final public function getNombre()
	{
		return $this->nombre;
	}

	/**
	  * @param varchar(100)
	  */
	final public function setNombre( $nombre )
	{
		$this->nombre = $nombre;
	}

	/**
	  * @return varchar(300)
	  */
	final public function getDireccion()
	{
		return $this->direccion;
	}

	/**
	  * @param varchar(300)
	  */
	final public function setDireccion( $direccion )
	{
		$this->direccion = $direccion;
	}

	/**
	  * @return varchar(25)
	  */
	final public function getTelefono()
	{
		return $this->telefono;
	}

	/**
	  * @param varchar(25)
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

	/**
	  * @return float
	  */
	final public function getLimiteCredito()
	{
		return $this->limite_credito;
	}

	/**
	  * @param float
	  */
	final public function setLimiteCredito( $limite_credito )
	{
		$this->limite_credito = $limite_credito;
	}

	/**
	  * @return tinyint(4)
	  */
	final public function getDescuento()
	{
		return $this->descuento;
	}

	/**
	  * @param tinyint(4)
	  */
	final public function setDescuento( $descuento )
	{
		$this->descuento = $descuento;
	}

}
