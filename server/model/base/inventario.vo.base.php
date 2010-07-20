<?php
/* Value Object file for table Inventario */

class Inventario
{
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_producto = $data['id_producto'];
			$this->nombre = $data['nombre'];
			$this->denominacion = $data['denominacion'];
		}
	}

	/**
	  * id_producto
	  * es llave primara 
	  * es de auto incremento 
	  * @var int(11) id del producto
	  */
	protected $id_producto;

	/**
	  * nombre
	  * @var varchar(90) Descripcion o nombre del producto
	  */
	protected $nombre;

	/**
	  * denominacion
	  * @var varchar(30) es lo que se le mostrara a los clientes
	  */
	protected $denominacion;

	/**
	  * es llave primara 
	  * @return int(11)
	  */
	final public function getIdProducto()
	{
		return $this->id_producto;
	}

	/**
	  * @param int(11)
	  */
	final public function setIdProducto( $id_producto )
	{
		$this->id_producto = $id_producto;
	}

	/**
	  * @return varchar(90)
	  */
	final public function getNombre()
	{
		return $this->nombre;
	}

	/**
	  * @param varchar(90)
	  */
	final public function setNombre( $nombre )
	{
		$this->nombre = $nombre;
	}

	/**
	  * @return varchar(30)
	  */
	final public function getDenominacion()
	{
		return $this->denominacion;
	}

	/**
	  * @param varchar(30)
	  */
	final public function setDenominacion( $denominacion )
	{
		$this->denominacion = $denominacion;
	}

}
