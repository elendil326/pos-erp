<?php
require_once("interfaces/ImpuestosYRetenciones.interface.php");
/**
  *
  *
  *
  **/
	
  class ImpuestosYRetencionesController implements IImpuestosYRetenciones{
  
  
	/**
 	 *
 	 *Edita la informacion de un impuesto
 	 *
 	 * @param id_impuesto int Id del impuesto a editar
 	 * @param es_monto bool Si es verdadero, el campo de monto_porcentaje sera tomado como un monto fijo, si es false, sera tomado como un porcentaje
 	 * @param monto_porcentaje float Monto o porcentaje que representa este impuesto
 	 * @param descripcion string Descripcion larga del impuesto
 	 * @param nombre string Nombre del impuesto
 	 **/
	public function EditarImpuesto
	(
		$id_impuesto, 
		$es_monto = null, 
		$monto_porcentaje = null, 
		$descripcion = null, 
		$nombre = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Edita la informacion de una retencion
 	 *
 	 * @param id_retencion int Id de la retencion a editar
 	 * @param es_monto bool Si es verdadero, el campo monto_porcentaje sera tomado como un monto fijo, si es false, sera tomado como un porcentaje
 	 * @param monto_porcentaje float Monto o porcentaje de la retencion
 	 * @param descripcion string Descripcion larga de al retencion
 	 * @param nombre string Nombre de la retencion
 	 **/
	public function EditarRetencion
	(
		$id_retencion, 
		$es_monto = null, 
		$monto_porcentaje = null, 
		$descripcion = null, 
		$nombre = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Lista las retenciones
 	 *
 	 * @param ordenar json Objeto que determinara el orde de la lista
 	 * @return retenciones json Objeto que contendra la lista de retenciones
 	 **/
	public function ListaRetencion
	(
		$ordenar = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Listas los impuestos
 	 *
 	 * @param ordenar json Objeto que determinara el orden de la lista
 	 * @return impuestos json Lista de impuestos
 	 **/
	public function ListaImpuesto
	(
		$ordenar = null
	)
	{  
  
  
	}
  
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
	public function NuevaRetencion
	(
		$es_monto, 
		$monto_porcentaje, 
		$nombre, 
		$descripcion = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Crear un nuevo impuesto.
 	 *
 	 * @param monto_porcentaje float monto o porcentaje que representa este impuesto
 	 * @param nombre string Nombre del impuesto
 	 * @param es_monto bool Si es verdadero, el campo de monto_porcentaje sera tomado como un monto fijo, si es falso, sera tomado como un porcentaje
 	 * @param descripcion string Descripcion del impuesto
 	 * @return id_impuesto int Id del impuesto insertado.
 	 **/
	public function NuevoImpuesto
	(
		$monto_porcentaje, 
		$nombre, 
		$es_monto, 
		$descripcion = null
	)
	{  
  
  
	}
  }
