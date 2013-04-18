<?php
require_once("../../server/bootstrap.php");

class ContabilidadControllerTest extends PHPUnit_Framework_TestCase
{


    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaMismoNombre()
    {
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Circulante', 1, 0, "Deudora", 
                                            "Bancos", "Balance", $id_cuenta_padre = ""
                                            );

        ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Circulante', 1, 0, "Deudora", 
                                            "Bancos", "Balance", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaMayorYOrden()
    {
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Circulante', 1, 1, "Deudora", 
                                            "Cajas", "Balance", $id_cuenta_padre = ""
                                            );

    }

    public function testNuevaCuentaSeaAfectable()
    {
        $randmon_str = "Cuenta X ".time();
        $id_nueva_cuenta = ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Circulante', 1, 0, "Deudora", 
                                            $randmon_str, "Balance", $id_cuenta_padre = ""
                                            );

        $nueva = CuentaContableDAO::getByPK($id_nueva_cuenta['id_cuenta_contable']);

        $this->assertEquals($nueva->getAfectable(),1);

    }

        public function testNuevaCuentaSeaAfectablePadreNoAfectable()
    {
        $randmon_str = "Cuenta Padre ".time();
        $id_nueva_cuenta = ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Circulante', 1, 0, "Deudora", 
                                            $randmon_str, "Balance", $id_cuenta_padre = ""
                                            );

        $padre = CuentaContableDAO::getByPK($id_nueva_cuenta['id_cuenta_contable']);

        $this->assertEquals($padre->getAfectable(),1);

        $randmon_str = "Cuenta Hija ".time();
        $id_nueva_cuenta2 = ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Circulante', 1, 0, "Deudora", 
                                            $randmon_str, "Balance", $id_nueva_cuenta['id_cuenta_contable']
                                            );
        $hija = CuentaContableDAO::getByPK($id_nueva_cuenta2['id_cuenta_contable']);
        //se vuelve a trar a cuenta padre para obtener el valor actualzado
        $padre = CuentaContableDAO::getByPK($id_nueva_cuenta['id_cuenta_contable']);

        $this->assertEquals($hija->getAfectable(),1);
        $this->assertEquals($padre->getAfectable(),0);

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaBalanceDeudoraNoIngresos()
    {
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Ingresos', 1, 0, "Deudora", 
                                            "Bancos", "Balance", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaBalanceDeudoraNoEgresos()
    {
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Egresos', 1, 0, "Deudora", 
                                            "Bancos", "Balance", $id_cuenta_padre = ""
                                            );
    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaBalanceDeudoraNoPasivoCirculante()
    {
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Pasivo Circulante', 1, 0, "Deudora", 
                                            "Bancos", "Balance", $id_cuenta_padre = ""
                                            );
    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaBalanceDeudoraNoPasivoLargoPlazo()
    {
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Pasivo Largo Plazo', 1, 0, "Deudora", 
                                            "Bancos", "Balance", $id_cuenta_padre = ""
                                            );
    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaBalanceDeudoraNoCapitalContable()
    {
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Capital Contable', 1, 0, "Deudora", 
                                            "Bancos", "Balance", $id_cuenta_padre = ""
                                            );
    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaBalanceAcreedoraNoIngresos()
    {
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Ingresos', 1, 0, "Acreedora", 
                                            "Bancos", "Balance", $id_cuenta_padre = ""
                                            );

    }

        /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaBalanceAcreedoraNoEgresos()
    {
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Egresos', 1, 0, "Acreedora", 
                                            "Bancos", "Balance", $id_cuenta_padre = ""
                                            );

    }

        /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaBalanceAcreedoraNoActivoDiferido()
    {
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Diferido', 1, 0, "Acreedora", 
                                            "Bancos", "Balance", $id_cuenta_padre = ""
                                            );

    }

        /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaBalanceAcreedoraNoActivoFijo()
    {
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Fijo', 1, 0, "Acreedora", 
                                            "Bancos", "Balance", $id_cuenta_padre = ""
                                            );

    }

        /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaBalanceAcreedoraNoActivoCirculante()
    {
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Circulante', 1, 0, "Acreedora", 
                                            "Bancos", "Balance", $id_cuenta_padre = ""
                                            );

    }

        /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaBalanceAcreedoraNoCapitalContable()
    {
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Capital Contable', 1, 0, "Acreedora", 
                                            "Bancos", "Balance", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaEstadoResultadosAcreedoraNoActivoFijo()
    {
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Fijo', 1, 0, "Acreedora", 
                                            "Bancos", "Estado de Resultados", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaEstadoResultadosAcreedoraNoActivoCirculante()
    {
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Circulante', 1, 0, "Acreedora", 
                                            "Bancos", "Estado de Resultados", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaEstadoResultadosAcreedoraNoActivoDiferido()
    {
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Diferido', 1, 0, "Acreedora", 
                                            "Bancos", "Estado de Resultados", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaEstadoResultadosAcreedoraNoPasivoCirculante()
    {
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Pasivo Circulante', 1, 0, "Acreedora", 
                                            "Bancos", "Estado de Resultados", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaEstadoResultadosAcreedoraNoPasivoLargoPlazo()
    {
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Pasivo Largo Plazo', 1, 0, "Acreedora", 
                                            "Bancos", "Estado de Resultados", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaEstadoResultadosAcreedoraNoCapitalContable()
    {
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Capital Contable', 1, 0, "Acreedora", 
                                            "Bancos", "Estado de Resultados", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaEstadoResultadosAcreedoraNoEgresos()
    {
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Egresos', 1, 0, "Acreedora", 
                                            "Bancos", "Estado de Resultados", $id_cuenta_padre = ""
                                            );

    }

        /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaEstadoResultadosDeudoraNoActivoFijo()
    {
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Fijo', 1, 0, "Deudora", 
                                            "Bancos", "Estado de Resultados", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaEstadoResultadosDeudoraNoActivoCirculante()
    {
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Circulante', 1, 0, "Deudora", 
                                            "Bancos", "Estado de Resultados", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaEstadoResultadosDeudoraNoActivoDiferido()
    {
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Diferido', 1, 0, "Deudora", 
                                            "Bancos", "Estado de Resultados", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaEstadoResultadosDeudoraNoPasivoCirculante()
    {
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Pasivo Circulante', 1, 0, "Deudora", 
                                            "Bancos", "Estado de Resultados", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaEstadoResultadosDeudoraNoPasivoLargoPlazo()
    {
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Pasivo Largo Plazo', 1, 0, "Deudora", 
                                            "Bancos", "Estado de Resultados", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaEstadoResultadosDeudoraNoCapitalContable()
    {
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Capital Contable', 1, 0, "Deudora", 
                                            "Bancos", "Estado de Resultados", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaEstadoResultadosDeudoraNoIngresos()
    {
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Ingresos', 1, 0, "Deudora", 
                                            "Bancos", "Estado de Resultados", $id_cuenta_padre = ""
                                            );

    }



}
?>