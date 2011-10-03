<?php
/**
  *
  *
  *
  **/

  interaface IProductos {
  
  
	/**
 	 *
 	 *Lista las unidades convertibles. Se puede filtrar por activas o inactivas y ordenar por sus atributos
 	 *
 	 **/
	protected function ListaUnidad_convertible();  
  
  
  
  
	/**
 	 *
 	 *Lista las unidades no convertibles. Se puede filtrar por activas e inactivas y ordenar por sus atributos
 	 *
 	 **/
	protected function ListaUnidad_no_convertible();  
  
  
  
  
	/**
 	 *
 	 *Se puede ordenar por los atributos de producto. 
 	 *
 	 **/
	protected function Lista();  
  
  
  
  
	/**
 	 *
 	 *Crear un nuevo producto, 

NOTA: Se crea un producto tipo = 1 que es para productos
 	 *
 	 **/
	protected function Nuevo();  
  
  
  
  
	/**
 	 *
 	 *Agregar productos en volumen mediante un archivo CSV.
 	 *
 	 **/
	protected function En_volumenNuevo();  
  
  
  
  
	/**
 	 *
 	 *Este metodo sirve para dar de baja un producto
 	 *
 	 **/
	protected function Desactivar();  
  
  
  
  
	/**
 	 *
 	 *Edita la información de un producto
 	 *
 	 **/
	protected function Editar();  
  
  
  
  
	/**
 	 *
 	 *Crea una nueva categoria de producto, la categoria de un producto se relaciona con los meses de garantia del mismo, las unidades en las que se almacena entre, si se es suceptible a devoluciones, entre otros.
 	 *
 	 **/
	protected function NuevaCategoria();  
  
  
  
  
	/**
 	 *
 	 *Este metodo cambia la informacion de una categoria de producto
 	 *
 	 **/
	protected function EditarCategoria();  
  
  
  
  
	/**
 	 *
 	 *Este metodo desactiva una categoria de tal forma que ya no se vuelva a usar como categoria sobre un producto.
 	 *
 	 **/
	protected function DesactivarCategoria();  
  
  
  
  
	/**
 	 *
 	 *Este metodo crea una nueva unidad no convertible (caja, lote, arpilla, costal, etc.) Las unidades no convertibles son aquellas que varían su valor y su peso de acuerdo al producto que se ingresa en ellas.
 	 *
 	 **/
	protected function NuevaUnidad_no_convertible();  
  
  
  
  
	/**
 	 *
 	 *Metodo que cambia la informacion de una unidad no convertible
 	 *
 	 **/
	protected function EditarUnidad_no_convertible();  
  
  
  
  
	/**
 	 *
 	 *Desactiva una unidad no convertible para que no sea usada por otro producto
 	 *
 	 **/
	protected function EliminarUnidad_no_convertible();  
  
  
  
  
	/**
 	 *
 	 *Este metodo crea unidades convertibles, como son Kilogramos, Libras, Toneladas, Litros, etc.
 	 *
 	 **/
	protected function NuevaUnidad_convertible();  
  
  
  
  
	/**
 	 *
 	 *Este metodo modifica la informacion de una unidad convertible
 	 *
 	 **/
	protected function EditarUnidad_convertible();  
  
  
  
  
	/**
 	 *
 	 *Descativa una unidad convertible para que no sea usada por otro metodo
 	 *
 	 **/
	protected function EliminarUnidad_convertible();  
  
  
  
  }
