<?php
/**
  *
  *
  *
  **/
	
  interface IImpuestosYRetenciones {
  
  
	/**
 	 *
 	 *Edita la informacion de un impuesto
 	 *
 	 * @param id_impuesto int Id del impuesto a editar
 	 * @param descripcion string Descripcion larga del impuesto
 	 * @param es_monto bool Si es verdadero, el campo de monto_porcentaje sera tomado como un monto fijo, si es false, sera tomado como un porcentaje
 	 * @param monto_porcentaje float Monto o porcentaje que representa este impuesto
 	 * @param nombre string Nombre del impuesto
 	 **/
  static function EditarImpuesto
	(
		$id_impuesto, 
		$descripcion = null, 
		$es_monto = null, 
		$monto_porcentaje = null, 
		$nombre = null
	);  
  
  
	
  
	/**
 	 *
 	 *Listas los impuestos
 	 *
 	 * @param ordenar json Objeto que determinara el orden de la lista
 	 * @return impuestos json Lista de impuestos
 	 **/
  static function ListaImpuesto
	(
		$ordenar = null
	);  
  
  
	
  
	/**
 	 *
 	 *Crear un nuevo impuesto.
 	 *
 	 * @param es_monto bool Si es verdadero, el campo de monto_porcentaje sera tomado como un monto fijo, si es falso, sera tomado como un porcentaje
 	 * @param monto_porcentaje float monto o porcentaje que representa este impuesto
 	 * @param nombre string Nombre del impuesto
 	 * @param descripcion string Descripcion del impuesto
 	 * @return id_impuesto int Id del impuesto insertado.
 	 **/
  static function NuevoImpuesto
	(
		$es_monto, 
		$monto_porcentaje, 
		$nombre, 
		$descripcion = null
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la informacion de una retencion
 	 *
 	 * @param id_retencion int Id de la retencion a editar
 	 * @param descripcion string Descripcion larga de al retencion
 	 * @param es_monto bool Si es verdadero, el campo monto_porcentaje sera tomado como un monto fijo, si es false, sera tomado como un porcentaje
 	 * @param monto_porcentaje float Monto o porcentaje de la retencion
 	 * @param nombre string Nombre de la retencion
 	 **/
  static function EditarRetencion
	(
		$id_retencion, 
		$descripcion = null, 
		$es_monto = null, 
		$monto_porcentaje = null, 
		$nombre = null
	);  
  
  
	
  
	/**
 	 *
 	 *Lista las retenciones
 	 *
 	 * @param ordenar json Objeto que determinara el orde de la lista
 	 * @return retenciones json Objeto que contendra la lista de retenciones
 	 **/
  static function ListaRetencion
	(
		$ordenar = null
	);  
  
  
	
  
	/**
 	 *
 	 *Crea una nueva retencion
 	 *
 	 * @param es_monto float Si es veradera, el campo monto_porcentaje sera tomado como un monto fijo, si es falso, sera tomado como un porcentaje
 	 * @param monto_porcentaje float Monto o procentaje que representa esta retencion
 	 * @param nombre string Nombre de la retencion
 	 * @param descripcion string Descripcion larga de la retencion
 	 * @return id_retencion int Id de la retencion creada
 	 **/
  static function NuevaRetencion
	(
		$es_monto, 
		$monto_porcentaje, 
		$nombre, 
		$descripcion = null
	);  
  
  
	
  }
