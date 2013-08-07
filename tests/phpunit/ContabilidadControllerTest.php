<?php
require_once("../../server/bootstrap.php");

class ContabilidadControllerTest extends PHPUnit_Framework_TestCase {


	public function NuevoCatalogoCuentas() {

		$direccion = Array(
			"calle"				=> "Dir-".time(),
			"numero_exterior"	=> "".time(),
			"colonia"			=> "Col-".time(),
			"id_ciudad"			=> 334,
			"codigo_postal"		=> "".time(),
			"numero_interior"	=> null,
			"texto_extra"		=> "Calle cerrada",
			"telefono1"			=> "4616149974",
			"telefono2"			=> "45*451*454"
		);

		$razon_social = "Caffeina Software".time();
		$rfc  = "RFC".time();

		$c = new stdClass();
		$c->id_moneda			= 1;
		$c->ejercicio			= "2013";
		$c->periodo_actual		= 1;
		$c->duracion_periodo	= 1;

		$empresa = EmpresasController::Nuevo(
			$contabilidad		= $c,
			$direccion			= array($direccion),
			$razon_social,
			$rfc,
			$cuentas_bancarias	= null,
			$direccion_web		= null,
			$duplicar			= false,
			$email				= time() . "d",
			$impuestos_compra	= null, 
			$impuestos_venta	= null,
			$mensajes_morosos	= null,
			$representante_legal= null,
			$uri_logo			= null
		);

		$this->assertInternalType('int', $empresa["id_empresa"]);

		$catalogo = ContabilidadController::NuevoCatalogoCuentasEmpresa($empresa["id_empresa"]);
		$this->assertInternalType('int', $catalogo["id_catalogo_cuentas"]);
	}

	public function testCatalogoCuentasEmpresa() {

		$direccion = Array(
			"calle"				=> "Dir-".time(),
			"numero_exterior"	=> "".time(),
			"colonia"			=> "Col-".time(),
			"id_ciudad"			=> 334,
			"codigo_postal"		=> "".time(),
			"numero_interior"	=> null,
			"texto_extra"		=> "Calle cerrada",
			"telefono1"			=> "4616149974",
			"telefono2"			=> "45*451*454"
		);

		$razon_social = "Caffeina Software".time();
		$rfc  = "RFC".time();

		$c = new stdClass();
		$c->id_moneda			= 1;
		$c->ejercicio			= "2013";
		$c->periodo_actual		= 1;
		$c->duracion_periodo	= 1;

		$empresa = EmpresasController::Nuevo(
			$contabilidad		= $c,
			$direccion			= array($direccion),
			$razon_social,
			$rfc,
			$cuentas_bancarias	= null,
			$direccion_web		= null,
			$duplicar			= false,
			$email				= time() . "d",
			$impuestos_compra	= null, 
			$impuestos_venta	= null,
			$mensajes_morosos	= null,
			$representante_legal= null,
			$uri_logo			= null
		);

		$this->assertInternalType('int', $empresa["id_empresa"]);

		$catalogo = ContabilidadController::NuevoCatalogoCuentasEmpresa($empresa["id_empresa"]);
		$this->assertInternalType('int', $catalogo["id_catalogo_cuentas"]);
		$this->assertSame('ok', $catalogo["status"]);
	}

	/**
	* @expectedException BusinessLogicException
	*/
	public function testNuevaCuentaMismoNombre() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		ContabilidadController::NuevaCuenta(0, 1, 'Activo Circulante', 1, 0, $id_catalogo_cuentas, "Deudora",
											"Bancos", "Balance", $id_cuenta_padre = "");

		ContabilidadController::NuevaCuenta(0, 1, 'Activo Circulante', 1, 0, $id_catalogo_cuentas, "Deudora",
											"Bancos", "Balance", $id_cuenta_padre = "");
	}

	/**
	* @expectedException BusinessLogicException
	*/
	public function testNuevaCuentaMayorYOrden() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		ContabilidadController::NuevaCuenta(0, 1, 'Activo Circulante', 1, 1, $id_catalogo_cuentas, "Deudora",
											"Cajas", "Balance", $id_cuenta_padre = "");
	}

	public function testNuevaCuentaSeaAfectable() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		$randmon_str = "Cuenta X ".time();
		$id_nueva_cuenta = ContabilidadController::NuevaCuenta(0, 1, 'Activo Circulante', 1, 0, $id_catalogo_cuentas, "Deudora",
											$randmon_str, "Balance", $id_cuenta_padre = "");

		$nueva = CuentaContableDAO::getByPK($id_nueva_cuenta['id_cuenta_contable']);

		$this->assertEquals($nueva->getAfectable(), 1);
	}

	public function testNuevaCuentaSeaAfectablePadreNoAfectable() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		$randmon_str = "Cuenta Padre ".time();
		$id_nueva_cuenta = ContabilidadController::NuevaCuenta(0, 1, 'Activo Circulante', 1, 0, $id_catalogo_cuentas, "Deudora",
											$randmon_str, "Balance", $id_cuenta_padre = "");

		$padre = CuentaContableDAO::getByPK($id_nueva_cuenta['id_cuenta_contable']);

		$this->assertEquals($padre->getAfectable(), 1);

		$randmon_str = "Cuenta Hija ".time();
		$id_nueva_cuenta2 = ContabilidadController::NuevaCuenta(0, 1, 'Activo Circulante', 1, 0, $id_catalogo_cuentas, "Deudora",
											$randmon_str, "Balance", $id_nueva_cuenta['id_cuenta_contable']);
		$hija = CuentaContableDAO::getByPK($id_nueva_cuenta2['id_cuenta_contable']);
		//se vuelve a trar a cuenta padre para obtener el valor actualzado
		$padre = CuentaContableDAO::getByPK($id_nueva_cuenta['id_cuenta_contable']);

		$this->assertEquals($hija->getAfectable(), 1);
		$this->assertEquals($padre->getAfectable(), 0);
	}

	/**
	* @expectedException BusinessLogicException
	*/
	public function testNuevaCuentaBalanceDeudoraNoIngresos() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		ContabilidadController::NuevaCuenta(0, 1, 'Ingresos', 1, 0, $id_catalogo_cuentas, "Deudora",
											"Bancos", "Balance", $id_cuenta_padre = "");

	}

	/**
	* @expectedException BusinessLogicException
	*/
	public function testNuevaCuentaBalanceDeudoraNoEgresos() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		ContabilidadController::NuevaCuenta(0, 1, 'Egresos', 1, 0, $id_catalogo_cuentas, "Deudora",
											"Bancos", "Balance", $id_cuenta_padre = "");
	}

	/**
	* @expectedException BusinessLogicException
	*/
	public function testNuevaCuentaBalanceDeudoraNoPasivoCirculante() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		ContabilidadController::NuevaCuenta(0, 1, 'Pasivo Circulante', 1, 0, $id_catalogo_cuentas, "Deudora",
											"Bancos", "Balance", $id_cuenta_padre = "");
	}

	/**
	* @expectedException BusinessLogicException
	*/
	public function testNuevaCuentaBalanceDeudoraNoPasivoLargoPlazo() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		ContabilidadController::NuevaCuenta(0, 1, 'Pasivo Largo Plazo', 1, 0, $id_catalogo_cuentas, "Deudora",
											"Bancos", "Balance", $id_cuenta_padre = "");
	}

	/**
	* @expectedException BusinessLogicException
	*/
	public function testNuevaCuentaBalanceDeudoraNoCapitalContable() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		ContabilidadController::NuevaCuenta(0, 1, 'Capital Contable', 1, 0, $id_catalogo_cuentas, "Deudora",
											"Bancos", "Balance", $id_cuenta_padre = "");
	}

	/**
	* @expectedException BusinessLogicException
	*/
	public function testNuevaCuentaBalanceAcreedoraNoIngresos() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		ContabilidadController::NuevaCuenta(0, 1, 'Ingresos', 1, 0, $id_catalogo_cuentas, "Acreedora",
											"Bancos", "Balance", $id_cuenta_padre = "");
	}

	/**
	* @expectedException BusinessLogicException
	*/
	public function testNuevaCuentaBalanceAcreedoraNoEgresos() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		ContabilidadController::NuevaCuenta(0, 1, 'Egresos', 1, 0, $id_catalogo_cuentas, "Acreedora",
											"Bancos", "Balance", $id_cuenta_padre = "" );

	}

	/**
	* @expectedException BusinessLogicException
	*/
	public function testNuevaCuentaBalanceAcreedoraNoActivoDiferido() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		ContabilidadController::NuevaCuenta(0, 1, 'Activo Diferido', 1, 0, $id_catalogo_cuentas, "Acreedora",
											"Bancos", "Balance", $id_cuenta_padre = "");
	}

	/**
	* @expectedException BusinessLogicException
	*/
	public function testNuevaCuentaBalanceAcreedoraNoActivoFijo() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		ContabilidadController::NuevaCuenta(0, 1, 'Activo Fijo', 1, 0, $id_catalogo_cuentas, "Acreedora",
											"Bancos", "Balance", $id_cuenta_padre = "");
	}

	/**
	* @expectedException BusinessLogicException
	*/
	public function testNuevaCuentaBalanceAcreedoraNoActivoCirculante() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		ContabilidadController::NuevaCuenta(0, 1, 'Activo Circulante', 1, 0, $id_catalogo_cuentas, "Acreedora",
											"Bancos", "Balance", $id_cuenta_padre = "");
	}

	/**
	* @expectedException BusinessLogicException
	*/
	public function testNuevaCuentaBalanceAcreedoraNoCapitalContable() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		ContabilidadController::NuevaCuenta(0, 1, 'Capital Contable', 1, 0, $id_catalogo_cuentas, "Deudora",
											"Bancos", "Balance", $id_cuenta_padre = "");
	}

	/**
	* @expectedException BusinessLogicException
	*/
	public function testNuevaCuentaEstadoResultadosAcreedoraNoActivoFijo() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		ContabilidadController::NuevaCuenta(0, 1, 'Activo Fijo', 1, 0, $id_catalogo_cuentas, "Acreedora",
											"Bancos", "Estado de Resultados", $id_cuenta_padre = "");
	}

	/**
	* @expectedException BusinessLogicException
	*/
	public function testNuevaCuentaEstadoResultadosAcreedoraNoActivoCirculante() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		ContabilidadController::NuevaCuenta(0, 1, 'Activo Circulante', 1, 0, $id_catalogo_cuentas, "Acreedora",
											"Bancos", "Estado de Resultados", $id_cuenta_padre = "");
	}

	/**
	* @expectedException BusinessLogicException
	*/
	public function testNuevaCuentaEstadoResultadosAcreedoraNoActivoDiferido() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		ContabilidadController::NuevaCuenta(0, 1, 'Activo Diferido', 1, 0, $id_catalogo_cuentas, "Acreedora",
											"Bancos", "Estado de Resultados", $id_cuenta_padre = "");
	}

	/**
	* @expectedException BusinessLogicException
	*/
	public function testNuevaCuentaEstadoResultadosAcreedoraNoPasivoCirculante() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		ContabilidadController::NuevaCuenta(0, 1, 'Pasivo Circulante', 1, 0, $id_catalogo_cuentas, "Acreedora",
											"Bancos", "Estado de Resultados", $id_cuenta_padre = "");
	}

	/**
	* @expectedException BusinessLogicException
	*/
	public function testNuevaCuentaEstadoResultadosAcreedoraNoPasivoLargoPlazo() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		ContabilidadController::NuevaCuenta(0, 1, 'Pasivo Largo Plazo', 1, 0, $id_catalogo_cuentas, "Acreedora",
											"Bancos", "Estado de Resultados", $id_cuenta_padre = "");
	}

	/**
	* @expectedException BusinessLogicException
	*/
	public function testNuevaCuentaEstadoResultadosAcreedoraNoCapitalContable() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		ContabilidadController::NuevaCuenta(0, 1,'Capital Contable', 1, 0, $id_catalogo_cuentas, "Acreedora",
											"Bancos", "Estado de Resultados", $id_cuenta_padre = "");
	}

	/**
	* @expectedException BusinessLogicException
	*/
	public function testNuevaCuentaEstadoResultadosAcreedoraNoEgresos() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		ContabilidadController::NuevaCuenta(0, 1,'Egresos', 1, 0, $id_catalogo_cuentas, "Acreedora",
											"Bancos", "Estado de Resultados", $id_cuenta_padre = "");
	}

	/**
	* @expectedException BusinessLogicException
	*/
	public function testNuevaCuentaEstadoResultadosDeudoraNoActivoFijo() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		ContabilidadController::NuevaCuenta(0, 1, 'Activo Fijo', 1, 0, $id_catalogo_cuentas, "Deudora",
											"Bancos", "Estado de Resultados", $id_cuenta_padre = "");
	}

	/**
	* @expectedException BusinessLogicException
	*/
	public function testNuevaCuentaEstadoResultadosDeudoraNoActivoCirculante() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		ContabilidadController::NuevaCuenta(0, 1, 'Activo Circulante', 1, 0, $id_catalogo_cuentas, "Deudora",
											"Bancos", "Estado de Resultados", $id_cuenta_padre = "");
	}

	/**
	* @expectedException BusinessLogicException
	*/
	public function testNuevaCuentaEstadoResultadosDeudoraNoActivoDiferido() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		ContabilidadController::NuevaCuenta(0, 1, 'Activo Diferido', 1, 0, $id_catalogo_cuentas, "Deudora",
											"Bancos", "Estado de Resultados", $id_cuenta_padre = "");
	}

	/**
	* @expectedException BusinessLogicException
	*/
	public function testNuevaCuentaEstadoResultadosDeudoraNoPasivoCirculante() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		ContabilidadController::NuevaCuenta(0, 1, 'Pasivo Circulante', 1, 0, $id_catalogo_cuentas, "Deudora",
											"Bancos", "Estado de Resultados", $id_cuenta_padre = "");
	}

	/**
	* @expectedException BusinessLogicException
	*/
	public function testNuevaCuentaEstadoResultadosDeudoraNoPasivoLargoPlazo() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		ContabilidadController::NuevaCuenta(0, 1, 'Pasivo Largo Plazo', 1, 0, $id_catalogo_cuentas, "Deudora",
											"Bancos", "Estado de Resultados", $id_cuenta_padre = "");
	}

	/**
	* @expectedException BusinessLogicException
	*/
	public function testNuevaCuentaEstadoResultadosDeudoraNoCapitalContable() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		ContabilidadController::NuevaCuenta(0, 1, 'Capital Contable', 1, 0, $id_catalogo_cuentas, "Deudora",
											"Bancos", "Estado de Resultados", $id_cuenta_padre = "");
	}

	/**
	* @expectedException BusinessLogicException
	*/
	public function testNuevaCuentaEstadoResultadosDeudoraNoIngresos() {
		$id_catalogo_cuentas = self::NuevoCatalogoCuentas();
		ContabilidadController::NuevaCuenta(0, 1, 'Ingresos', 1, 0, $id_catalogo_cuentas, "Deudora",
											"Bancos", "Estado de Resultados", $id_cuenta_padre = "");
	}

}
?>
