<?php
require_once("interfaces/Contabilidad.interface.php");
/**
  *
  *
  *
  **/
	
  class ContabilidadController implements IContabilidad
  {
	/**
 	 *
 	 *Lista todas las facturas emitadas. Puede filtrarse por empresa, sucursal, estado y ordenarse por sus atributos 

Update : �Es correcto como se esta manejando el argumento id_sucursal? Ya que entiendo que de esta manera solo se estan obteniendo las facturas de una sola sucursal.
 	 *
 	 * @param activos bool Si este valor no es obtenido, se listaran tanto facturas activas como canceladas, si es true, se listaran solo las facturas activas, si es false se listaran solo las facturas canceladas
 	 * @param id_empresa int Id de la empresa de la cual se listaran las facturas
 	 * @param id_sucursal int Id de la sucursal de la cual se listaran las facturas
 	 * @param orden json Valor que determinara el orden de la lista
 	 * @return facturas json Objeto que contendra la lista de facturas.
 	 **/
	public static function ListaFacturas
	(
		$activos = null, 
		$id_empresa = null, 
		$id_sucursal = null, 
		$orden = null
	)
	{  
  
  
	}

	/**
 	 *
 	 *Realiza una busqueda de las cuentas contables en base a los par?metros que se le pasen
 	 *
 	 * @param id_cuenta_padre int El id de la cuenta de la que depende
 	 * @param clasificacion enum `Activo Circulante`,`Activo Fijo`,`Activo Diferido`,`Pasivo Circulante`,`Pasivo Largo Plazo`,`Capital Contable`,`Ingresos`,`Egresos` 	 
 	 **/
	public static function NuevaClaveCuentaContable($id_cuenta_padre = "", $clasificacion = "")
	{
		if($id_cuenta_padre!=null) {
			if (is_null(CuentaContableDAO::getByPK($id_cuenta_padre))) {
				return null;
			}
		}

		$clave = "";

		if ($id_cuenta_padre == "") {
			switch ($clasificacion) {
			case 'Activo Circulante':
				$clave .= "11";
				break;

			case 'Activo Fijo':
				$clave .= "12";
				break;

			case 'Activo Diferido':
				$clave .= "13";
				break;

			case 'Pasivo Circulante':
				$clave .= "14";
				break;

			case 'Pasivo Largo Plazo':
				$clave .= "15";
				break;

			case 'Capital Contable':
				$clave .= "16";
				break;

			case 'Ingresos':
				$clave .= "17";
				break;

			case 'Egresos':
				$clave .= "18";
				break;

			default:
				$clave .= "";
				break;
			}//switch

			$cuentas_nivel_1 = self::BuscarCuenta("", $clasificacion ,
											"", "",
											"", "",
											"", "",
											"", $nivel = 1,
											"", ""
								);

			if (count($cuentas_nivel_1["resultados"])>0) { 

				$pre_clave = $cuentas_nivel_1["resultados"][count($cuentas_nivel_1["resultados"]) - 1]->getConsecutivoEnNivel() +1;
				$clave .= "-" . $pre_clave;

			} else {
				$clave .= "-1";
			}

		} else {
			$subcuentas = self::BuscarCuenta("", "",
										"", "",
										"", "",
										"", $id_cuenta_padre,
										"", "",
										"", ""
							);
			$detalle_c = CuentaContableDAO::getByPK($id_cuenta_padre);
			$clave_padre = $detalle_c->getClave();

			if (count($subcuentas["resultados"])>0) {
				$pre_clave = $subcuentas["resultados"][count($subcuentas["resultados"]) - 1]->getConsecutivoEnNivel() +1;
				$clave .= $clave_padre. "-" . $pre_clave;
			} else {
				$clave .= $clave_padre . "-1";
			}

		}

		return $clave;

	}

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
	public static function BuscarCuenta($afectable = "", $clasificacion = "",
		$clave = "", $consecutivo_en_nivel = "", $es_cuenta_mayor = "",
		$es_cuenta_orden = "", $id_cuenta_contable = "", $id_cuenta_padre = "",
		$naturaleza = "", $nivel = "", $nombre_cuenta = "", $tipo_cuenta = ""
	) {

		$cuenta_buscar = new CuentaContable();

		if ($afectable!="") {
			$cuenta_buscar->setAfectable($afectable);
		}
		if ($clasificacion!="") {
			$cuenta_buscar->setClasificacion($clasificacion);
		}
		if ($clave!="") {
			$cuenta_buscar->setClave($clave);
		}
		if ($consecutivo_en_nivel!="") {
			$cuenta_buscar->setConsecutivoEnNivel($consecutivo_en_nivel);
		}
		if ($es_cuenta_mayor!="") {
			$cuenta_buscar->setEsCuentaMayor($es_cuenta_mayor);
		}
		if ($es_cuenta_orden!="") {
			$cuenta_buscar->setEsCuentaOrden($es_cuenta_orden);
		}
		if ($id_cuenta_contable!="") {
			$cuenta_buscar->setIdCuentaContable($id_cuenta_contable);
		}
		if ($id_cuenta_padre!="") {
			$cuenta_buscar->setIdCuentaPadre($id_cuenta_padre);
		}
		if ($naturaleza!="") {
			$cuenta_buscar->setNaturaleza($naturaleza);
		}
		if ($nivel!="") {
			$cuenta_buscar->setNivel($nivel);
		}
		if ($nombre_cuenta!="") {
			$cuenta_buscar->setNombreCuenta($nombre_cuenta);
		}
		if ($tipo_cuenta!="") {
			$cuenta_buscar->setTipoCuenta($tipo_cuenta);
		}
		$cuenta_buscar->setActiva(1);

		$cuentas = CuentaContableDAO::search($cuenta_buscar, "`clave`", "ASC");
		return array("resultados" => $cuentas);
	}
	
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
	public static function EditarCuenta($id_cuenta_contable, $abonos_aumentan = "", 
		$afectable = "", $cargos_aumentan = "", $es_cuenta_mayor = "", $es_cuenta_orden = "", 
		$id_cuenta_padre = "", $naturaleza = "", $nombre_cuenta = "", $tipo_cuenta = ""
	) {

		$editar_cuenta = CuentaContableDAO::getByPK($id_cuenta_contable);
		if (is_null($editar_cuenta)) {
			throw new Exception("La cuenta con id " . $id_cuenta_contable . " no existe", 901);
		}
		
		$subctas = self::BuscarCuenta("", "",
											"", "",
											"", "",
											"", $id_cuenta_contable,
											"", "",
											"", ""
								);

		if (count($subctas["resultados"])>0) {
			throw new Exception("No se puede editar una cuenta que tiene subcuentas. ", 901);
		}

		if ($editar_cuenta->getNivel()==1 && count($subctas["resultados"])>0) {
			throw new Exception("Una cuenta de Mayor nivel 1 que es por default no se puede editar", 901);
		}

		if ($es_cuenta_orden == 1 && $es_cuenta_mayor == 1) {
			throw new Exception("Una cuenta de Mayor no puede ser de Orden", 901);
		}

		

		$cuenta_buscar = new CuentaContable();
		$cuenta_buscar->setNombreCuenta($nombre_cuenta);
		if (count(CuentaContableDAO::search($cuenta_buscar))>0) {
			throw new Exception("Ya existe una cuenta con el nombre " . $nombre_cuenta, 901);
		}

		$subcuenta = new CuentaContable();
		$subcuenta->setIdCuentaPadre($id_cuenta_contable);
		if (count(CuentaContableDAO::search($subcuenta))>0) {
			throw new Exception("Ya existe subcuentas en esta cuenta con id " . $id_cuenta_contable . ", no se puede editar" , 901);
		}

		$nivel ="";
		if ($editar_cuenta->getIdCuentaPadre()!=$id_cuenta_padre 
			&& !is_null($editar_cuenta->getIdCuentaPadre())
			&& $id_cuenta_padre!="") 
		{//cambia de cuenta padre, debe de cambiar la clave, nivel y el consecutivo
			
			$clave = self::NuevaClaveCuentaContable($id_cuenta_padre,$editar_cuenta->getClasificacion());
			$nivel = 1;
			$consecutivo = 1;

			if ($id_cuenta_padre!="") {

				$detalle_c = CuentaContableDAO::getByPK($id_cuenta_padre);
				if (is_null($detalle_c)) {
					throw new Exception("La cuenta con id " . $id_cuenta_padre . " no existe", 901);
				}

				$nivel = $detalle_c->getNivel() + 1;
				$subcuentas = self::BuscarCuenta("", "",
											"", "",
											"", "",
											"", $id_cuenta_padre,
											"", "",
											"", ""
								);
				if (count($subcuentas["resultados"])>0) {
					$consecutivo = $subcuentas["resultados"][count($subcuentas["resultados"])-1]->getConsecutivoEnNivel() + 1;
				}

			} else {

				$cuentas_clasifi = self::BuscarCuenta("", $clasificacion,
											"", "",
											"", "",
											"", "",
											"", $nivel=1,
											"", ""
								);
				if (count($cuentas_clasifi["resultados"])>0) {
					$consecutivo = $cuentas_clasifi["resultados"][count($cuentas_clasifi["resultados"])-1]->getConsecutivoEnNivel() + 1;
				}
			}
			$editar_cuenta->setClave($clave);
			$editar_cuenta->setNivel($nivel);
			$editar_cuenta->setConsecutivoEnNivel($consecutivo);
			$editar_cuenta->setIdCuentaPadre($id_cuenta_padre);
		}

		if ($nivel == 1 && $id_cuenta_padre != "") {
			throw new Exception("Las cuentas de Nivel 1 no deben tener cuentas padre", 901);
		}
		if ($nombre_cuenta!="") {
			$editar_cuenta->setNombreCuenta($nombre_cuenta);
		}
		if ($tipo_cuenta!="") {
			$editar_cuenta->setTipoCuenta($tipo_cuenta);
		}
		if ($naturaleza!="") {
			$editar_cuenta->setNaturaleza($naturaleza);
		}
		if ($afectable!="") {
			$editar_cuenta->setAfectable($afectable);
		}

		if ($tipo_cuenta=="Estado de Resultados" && $naturaleza=="Acreedora") {
			$editar_cuenta->setAbonosAumentan(1);
			$editar_cuenta->setCargosAumentan(0);
		}
		if ($tipo_cuenta=="Estado de Resultados" && $naturaleza=="Deudora") {
			$editar_cuenta->setAbonosAumentan(0);
			$editar_cuenta->setCargosAumentan(1);
		}
		if ($tipo_cuenta=="Balance" && $naturaleza=="Acreedora") {
			$editar_cuenta->setAbonosAumentan(1);
			$editar_cuenta->setCargosAumentan(0);
		}
		if ($tipo_cuenta=="Balance" && $naturaleza=="Deudora") {
			$editar_cuenta->setAbonosAumentan(0);
			$editar_cuenta->setCargosAumentan(1);
		}
		$editar_cuenta->setEsCuentaOrden($es_cuenta_orden);
		$editar_cuenta->setEsCuentaMayor($es_cuenta_mayor);


		DAO::transBegin();
        try {
            CuentaContableDAO::save($editar_cuenta);
            if ($id_cuenta_padre != "") {
				$cuenta_padre_afec = CuentaContableDAO::getByPK($id_cuenta_padre);
				if ($cuenta_padre_afec->getAfectable()==true) {
					$cuenta_padre_afec->setAfectable(false);
					CuentaContableDAO::save($cuenta_padre_afec);
				}
			}
        }catch (Exception $e) {
            DAO::transRollback();
            Logger::error("No se ha podido editar la cuenta: " . $e);
            if ($e->getCode() == 901)
                throw new Exception("No se ha podido editar la cuenta|: " . $e->getMessage(), 901);
            throw new Exception("No se ha podido editar la cuenta", 901);
        }
        DAO::transEnd();

        return array("status"=>"ok");

	}
	
	/**
 	 *
 	 *Desactiva una cuenta contable que exista en el sistema, no la elimina fisicamente de la BD, solo cambia su estado activa = 0
 	 *
 	 * @param id_cuenta_contable int El id de la cuenta a desactivar
 	 **/
	public static function EliminarCuenta($id_cuenta_contable)
	{
		$cuenta = CuentaContableDAO::getByPK($id_cuenta_contable);
		if (is_null($cuenta)) {
			throw new Exception("La cuenta con id " . $id_cuenta_contable . " no existe", 901);
		}

		$subcuentas = self::BuscarCuenta("", "",
											"", "",
											"", "",
											"", $id_cuenta_contable,
											"", "",
											"", ""
								);
		if (count($subcuentas["resultados"])>0) {
			throw new Exception("No se puede eliminar una cuenta que tiene subcuentas. ", 901);
		}

		$cuenta->setActiva(false);
		DAO::transBegin();
        try {
            CuentaContableDAO::save($cuenta);
        }catch (Exception $e) {
            DAO::transRollback();
            Logger::error("No se ha podido desactivar la cuenta con id $id_cuenta_contable: " . $e);
            if ($e->getCode() == 901)
                throw new Exception("No se ha podido desactivar la cuenta con id $id_cuenta_contable | " . $e->getMessage(), 901);
            throw new Exception("No se ha podido desactivar la cuenta con id $id_cuenta_contable", 901);
        }
        DAO::transEnd();

        return array("status"=>"ok");
	}
	
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
	public static function NuevaCuenta($abonos_aumentan, $cargos_aumentan,
		$clasificacion, $es_cuenta_mayor, $es_cuenta_orden, $naturaleza, 
		$nombre_cuenta, $tipo_cuenta, $id_cuenta_padre = ""
	) {

		if ($es_cuenta_orden == 1 && $es_cuenta_mayor == 1) {
			throw new Exception("Una cuenta de Mayor no puede ser de Orden", 901);
		}

		if (($tipo_cuenta == "Balance" && $naturaleza == "Deudora") &&
			($clasificacion != "Activo Circulante"
			&& $clasificacion != "Activo Fijo"
			&& $clasificacion != "Activo Diferido")) {
			throw new Exception("Clasificacion incorrecta para la cuenta de tipo Balance y Naturaleza Deudora", 901);
		}

		if (($tipo_cuenta == "Balance" && $naturaleza == "Acreedora") &&
			($clasificacion != "Pasivo Circulante"
			|| $clasificacion != "Pasivo Largo Plazo"
			|| $clasificacion != "Capital Contable")) {
			throw new Exception("Clasificacion incorrecta para la cuenta de tipo Balance y Naturaleza Acreedora", 901);
		}

		if ($tipo_cuenta == "Estado de Resultados" && $naturaleza == "Acreedora" && $clasificacion != "Ingresos") {
			throw new Exception("Clasificacion incorrecta para la cuenta de tipo Estado de Resultados y Naturaleza Acreedora", 901);
		}

		if ($tipo_cuenta == "Estado de Resultados" && $naturaleza == "Deudora" && $clasificacion != "Egresos") {
			throw new Exception("Clasificacion incorrecta para la cuenta de tipo Estado de Resultados y Naturaleza Deudora", 901);
		}

		$cuenta_buscar = new CuentaContable();
		$cuenta_buscar->setNombreCuenta($nombre_cuenta);
		$cc2 = CuentaContableDAO::search($cuenta_buscar);
		if (count($cc2)>0) {
			throw new Exception(" Ya existe una cuenta con el nombre " . $nombre_cuenta, 901);
		}

		$clave = self::NuevaClaveCuentaContable($id_cuenta_padre,$clasificacion);
		$nivel = 1;
		$consecutivo = 1;

		if ($id_cuenta_padre!="") {

			$detalle_c = CuentaContableDAO::getByPK($id_cuenta_padre);
			if (is_null($detalle_c)) {
				throw new Exception("La cuenta con id " . $id_cuenta_padre . " no existe", 901);
			}

			$nivel = $detalle_c->getNivel() + 1;
			$subcuentas = self::BuscarCuenta("", "",
										"", "",
										"", "",
										"", $id_cuenta_padre,
										"", "",
										"", ""
							);
			if (count($subcuentas["resultados"])>0) {
				$consecutivo = $subcuentas["resultados"][count($subcuentas["resultados"])-1]->getConsecutivoEnNivel() + 1;
			}

		} else {

			$cuentas_clasifi = self::BuscarCuenta("", $clasificacion,
										"", "",
										"", "",
										"", "",
										"", $nivel=1,
										"", ""
							);

			if (count($cuentas_clasifi["resultados"])>0) {
				//Logger::log(" ::::: Cuentas clasificacion: ". print_r($cuentas_clasifi,true));
				$consecutivo = $cuentas_clasifi["resultados"][count($cuentas_clasifi["resultados"])-1]->getConsecutivoEnNivel() + 1;
			}
		}

		if ($nivel == 1 && $id_cuenta_padre != "") {
			throw new Exception("Las cuentas de Nivel 1 no deben tener cuentas padre", 901);
		}

		$nueva_cuenta = new CuentaContable();
		$nueva_cuenta->setClave($clave);
		$nueva_cuenta->setNivel($nivel);
		$nueva_cuenta->setConsecutivoEnNivel($consecutivo);
		$nueva_cuenta->setNombreCuenta($nombre_cuenta);
		$nueva_cuenta->setTipoCuenta($tipo_cuenta);
		$nueva_cuenta->setNaturaleza($naturaleza);
		$nueva_cuenta->setClasificacion($clasificacion);
		$nueva_cuenta->setAfectable(1);
		$nueva_cuenta->setActiva(1);
		$nueva_cuenta->setAbonosAumentan($abonos_aumentan);
		$nueva_cuenta->setCargosAumentan($cargos_aumentan);
		if ($tipo_cuenta=="Estado de Resultados" && $naturaleza=="Acreedora") {
			$nueva_cuenta->setAbonosAumentan(1);
			$nueva_cuenta->setCargosAumentan(0);
		}
		if ($tipo_cuenta=="Estado de Resultados" && $naturaleza=="Deudora") {
			$nueva_cuenta->setAbonosAumentan(0);
			$nueva_cuenta->setCargosAumentan(1);
		}
		if ($tipo_cuenta=="Balance" && $naturaleza=="Acreedora") {
			$nueva_cuenta->setAbonosAumentan(1);
			$nueva_cuenta->setCargosAumentan(0);
		}
		if ($tipo_cuenta=="Balance" && $naturaleza=="Deudora") {
			$nueva_cuenta->setAbonosAumentan(0);
			$nueva_cuenta->setCargosAumentan(1);
		}
		$nueva_cuenta->setEsCuentaOrden($es_cuenta_orden);
		$nueva_cuenta->setEsCuentaMayor($es_cuenta_mayor);
		if($id_cuenta_padre!=""){
			$nueva_cuenta->setIdCuentaPadre($id_cuenta_padre);
		}


		DAO::transBegin();
        try {
            CuentaContableDAO::save($nueva_cuenta);
            if ($id_cuenta_padre != "") {
				$cuenta_padre_afec = CuentaContableDAO::getByPK($id_cuenta_padre);
				if ($cuenta_padre_afec->getAfectable()==true) {
					$cuenta_padre_afec->setAfectable(false);
					CuentaContableDAO::save($cuenta_padre_afec);
				}
			}
        }catch (Exception $e) {
            DAO::transRollback();
            Logger::error("No se ha podido guardar la nueva cuenta: " . $e);
            if ($e->getCode() == 901)
                throw new Exception("No se ha podido guardar la nueva cuenta|: " . $e->getMessage(), 901);
            throw new Exception("No se ha podido guardar la nueva cuenta", 901);
        }
        DAO::transEnd();

        return array("status"=>"ok","id_cuenta_contable"=>$nueva_cuenta->getIdCuentaContable());
	}

	/**
 	 *
 	 *
 	 *Dado un id obtiene una cuenta contable en el sistema que corresponda con ese id.
 	 *
 	 * @param id_cuenta_contable int id de la cuenta contable
 	 **/
	public static function DetalleCuenta($id_cuenta_contable)
	{
		$cuenta = CuentaContableDAO::getByPK($id_cuenta_contable);
		if (is_null($cuenta)) {
			throw new Exception("La cuenta con id " . $id_cuenta_contable . " no existe", 901);
		}
		return object_to_array($cuenta);
	}

  }
