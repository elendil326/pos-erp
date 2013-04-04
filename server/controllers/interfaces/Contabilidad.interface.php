<?php
/**
  *
  *
  *
  **/
	
  interface IContabilidad {
  
  
	/**
 	 *
 	 *Lista todas las facturas emitadas. Puede filtrarse por empresa, sucursal, estado y ordenarse por sus atributos 

Update : ?Es correcto como se esta manejando el argumento id_sucursal? Ya que entiendo que de esta manera solo se estan obteniendo las facturas de una sola sucursal.
 	 *
 	 * @param activos bool Si este valor no es obtenido, se listaran tanto facturas activas como canceladas, si es true, se listaran solo las facturas activas, si es false se listaran solo las facturas canceladas
 	 * @param id_empresa int Id de la empresa de la cual se listaran las facturas
 	 * @param id_sucursal int Id de la sucursal de la cual se listaran las facturas
 	 * @param orden string Nombre de la columan por el cual se ordenara la lista
 	 * @return facturas json Objeto que contendra la lista de facturas.
 	 **/
  static function ListaFacturas
	(
		$activos = null, 
		$id_empresa = null, 
		$id_sucursal = null, 
		$orden = null
	);  
  
  
	
  
	/**
 	 *
 	 *Realiza una busqueda de las cuentas contables en base a los par?metros que se le pasen
 	 *
 	 * @param afectable bool indica si sobre esta cuenta ya se pueden realizar operaciones
 	 * @param clasificacion enum `Activo Circulante`,`Activo Fijo`,`Activo Diferido`,`Pasivo Circulante`,`Pasivo Largo Plazo`,`Capital Contable`,`Ingresos`,`Egresos`
 	 * @param clave string La clave que se le dará a la nueva cuenta contable
 	 * @param consecutivo_en_nivel int Dependiendo del nivel de profundidad de la cuenta contable, este valor indicara dentro de su nivel que numero consecutivo le corresponde con respecto a las mismas que estan en su mismo nivel
 	 * @param es_cuenta_mayor bool Indica si la cuenta es de mayor
 	 * @param es_cuenta_orden bool si la cuenta no se contemplara en los estados financieros
 	 * @param id_cuenta_contable int El id de la cuenta contable
 	 * @param id_cuenta_padre int El id de la cuenta de la que depende
 	 * @param naturaleza enum `Acreedora`,`Deudora`
 	 * @param nivel int Nivel de profundidad que tendra la cuenta en el arbol de cuentas
 	 * @param nombre_cuenta string El nombre de la cuenta
 	 * @param tipo_cuenta enum `Balance`,`Estado de Resultados`
 	 **/
  static function BuscarCuenta
	(
		$afectable = "", 
		$clasificacion = "", 
		$clave = "", 
		$consecutivo_en_nivel = "", 
		$es_cuenta_mayor = "", 
		$es_cuenta_orden = "", 
		$id_cuenta_contable = "", 
		$id_cuenta_padre = "", 
		$naturaleza = "", 
		$nivel = "", 
		$nombre_cuenta = "", 
		$tipo_cuenta = ""
	);  
  
  
	
  
	/**
 	 *
 	 *Edita una cuenta contable que exista en el sistema
 	 *
 	 * @param id_cuenta_contable int El id de la cuenta a editar
 	 * @param abonos_aumentan bool si abonos aumentan es igual a 1 significa que en los movimientos los abonos aumentantaran
 	 * @param afectable bool Si se va a permitir realizar movimientos en ésta cuenta o no
 	 * @param cargos_aumentan bool Si es igual 1 significa que en los movimientos cuando se cargue a esta cuenta los cargos aumentaran
 	 * @param es_cuenta_mayor bool Indica si es cuenta de mayor
 	 * @param es_cuenta_orden bool Indica si es cuenta de orden
 	 * @param id_cuenta_padre int El Id de la cuenta a la que va a pertencer
 	 * @param naturaleza enum `Acreedora`,`Deudora`
 	 * @param nombre_cuenta string El nombre de la cuenta reemplazado
 	 * @param tipo_cuenta enum `Balance`,`Estado de Resultados`
 	 **/
  static function EditarCuenta
	(
		$id_cuenta_contable, 
		$abonos_aumentan = "", 
		$afectable = "", 
		$cargos_aumentan = "", 
		$es_cuenta_mayor = "", 
		$es_cuenta_orden = "", 
		$id_cuenta_padre = "", 
		$naturaleza = "", 
		$nombre_cuenta = "", 
		$tipo_cuenta = ""
	);  
  
  
	
  
	/**
 	 *
 	 *Desactiva una cuenta contable que exista en el sistema, no la elimina fisicamente de la BD, solo cambia su estado activa = 0
 	 *
 	 * @param id_cuenta_contable int El id de la cuenta a desactivar
 	 **/
  static function EliminarCuenta
	(
		$id_cuenta_contable
	);  
  
  
	
  
	/**
 	 *
 	 *Genera una nueva cuenta contable en el sistema
 	 *
 	 * @param abonos_aumentan bool si abonos aumentan es igual a 1 significa que en los movimientos los abonos aumentantaran
 	 * @param cargos_aumentan bool Si es igual 1 significa que en los movimientos cuando se cargue a esta cuenta los cargos aumentaran
 	 * @param clasificacion enum `Activo Circulante`,`Activo Fijo`,`Activo Diferido`,`Pasivo Circulante`,`Pasivo Largo Plazo`,`Capital Contable`,`Ingresos`,`Egresos`
 	 * @param es_cuenta_mayor bool Indica si la cuenta es de mayor
 	 * @param es_cuenta_orden bool si la cuenta no se contemplara en los estados financieros
 	 * @param naturaleza enum `Acreedora`,`Deudora`
 	 * @param nombre_cuenta string El nombre de la cuenta
 	 * @param tipo_cuenta enum `Balance`,`Estado de Resultados`
 	 * @param id_cuenta_padre int id de la cuenta de la que depende
 	 **/
  static function NuevaCuenta
	(
		$abonos_aumentan, 
		$cargos_aumentan, 
		$clasificacion, 
		$es_cuenta_mayor, 
		$es_cuenta_orden, 
		$naturaleza, 
		$nombre_cuenta, 
		$tipo_cuenta, 
		$id_cuenta_padre = ""
	);  
  
  
	
  }
