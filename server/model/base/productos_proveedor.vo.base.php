<?php
/* Value Object file for table ProductosProveedor */

class ProductosProveedor
{
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_producto = $data['id_producto'];
			$this->clave_producto = $data['clave_producto'];
			$this->id_proveedor = $data['id_proveedor'];
			$this->id_inventario = $data['id_inventario'];
			$this->descripcion = $data['descripcion'];
			$this->precio = $data['precio'];
		}
	}

	/**
	  * id_producto
	  * es llave primara 
	  * es de auto incremento 
	  * @var int(11) identificador del producto
	  */
	protected $id_producto;

	/**
	  * clave_producto
	  * @var varchar(20) clave de producto para el proveedor
	  */
	protected $clave_producto;

	/**
	  * id_proveedor
	  * @var int(11) clave del proveedor
	  */
	protected $id_proveedor;

	/**
	  * id_inventario
	  * @var int(11) clave con la que entra a nuestro inventario
	  */
	protected $id_inventario;

	/**
	  * descripcion
	  * @var varchar(200) Descripcion del producto que nos vende el proveedor
	  */
	protected $descripcion;

	/**
	  * precio
	  * @var int(11) precio al que se compra el producto (sin descuento)
	  */
	protected $precio;

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
	  * @return varchar(20)
	  */
	final public function getClaveProducto()
	{
		return $this->clave_producto;
	}

	/**
	  * @param varchar(20)
	  */
	final public function setClaveProducto( $clave_producto )
	{
		$this->clave_producto = $clave_producto;
	}

	/**
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
	  * @return int(11)
	  */
	final public function getIdInventario()
	{
		return $this->id_inventario;
	}

	/**
	  * @param int(11)
	  */
	final public function setIdInventario( $id_inventario )
	{
		$this->id_inventario = $id_inventario;
	}

	/**
	  * @return varchar(200)
	  */
	final public function getDescripcion()
	{
		return $this->descripcion;
	}

	/**
	  * @param varchar(200)
	  */
	final public function setDescripcion( $descripcion )
	{
		$this->descripcion = $descripcion;
	}

	/**
	  * @return int(11)
	  */
	final public function getPrecio()
	{
		return $this->precio;
	}

	/**
	  * @param int(11)
	  */
	final public function setPrecio( $precio )
	{
		$this->precio = $precio;
	}

}
