<?php
require_once("../../server/bootstrap.php");

class ContabilidadControllerTest extends PHPUnit_Framework_TestCase
{


    public function NuevoCatalogoCuentas()
    {
        $dir = new Direccion();
        $dir->setIdUsuarioUltimaModificacion(1);
        $dir->setUltimaModificacion(mktime());
        $dir->setCalle("Calle: ".mktime());

        DAO::transBegin();
        try {
            DireccionDAO::save($dir);
        }catch (Exception $e) {
            DAO::transRollback();
            Logger::error("No se ha podido guardar la direccion (Desde Unit Tests): " . $e);
            if ($e->getCode() == 901)
                throw new Exception("No se ha podido guardar la nueva direccion (desde Unit Test)|: " . $e->getMessage(), 901);
            throw new Exception("No se ha podido guardar la nueva direccion (Desde Unit Tests)", 901);
        }
        DAO::transEnd();

        $empresa = new Empresa();
        $empresa->setRazonSocial("Razon Social - ".time());
        $empresa->setRfc(time());
        $empresa->setIdDireccion($dir->getIdDireccion());
        $empresa->setFechaAlta(mktime());
        $empresa->setIdLogo(1);
        $empresa->setActivo(1);

        DAO::transBegin();
        try {
            EmpresaDAO::save($empresa);
        }catch (Exception $e) {
            DAO::transRollback();
            Logger::error("No se ha podido guardar la nueva empresa (Desde Unit Tests): " . $e);
            if ($e->getCode() == 901)
                throw new Exception("No se ha podido guardar la nueva empresa (desde Unit Test)|: " . $e->getMessage(), 901);
            throw new Exception("No se ha podido guardar la nueva empresa (Desde Unit Tests)", 901);
        }
        DAO::transEnd();

        $cat = new CatalogoCuentas();
        $cat->setDescripcion("Catalogo de cuentas ".$empresa->getRazonSocial());
        $cat->setIdEmpresa($empresa->getIdEmpresa());

        DAO::transBegin();
        try {
            CatalogoCuentasDAO::save($cat);
        }catch (Exception $e) {
            DAO::transRollback();
            Logger::error("No se ha podido guardar el nuevo catalogo de cuentas (Desde Unit Tests): " . $e);
            if ($e->getCode() == 901)
                throw new Exception("No se ha podido guardar el nuevo catalogo de cuentas (desde Unit Test)|: " . $e->getMessage(), 901);
            throw new Exception("No se ha podido guardar el nuevo catalogo de cuentas (Desde Unit Tests)", 901);
        }
        DAO::transEnd();
        return $cat->getIdCatalogo();
    }

     public function testCatalogoCuentasEmpresa()
    {

        $dir = new Direccion();
        $dir->setIdUsuarioUltimaModificacion(1);
        $dir->setUltimaModificacion(mktime());
        $dir->setCalle("Calle: ".mktime());

        DAO::transBegin();
        try {
            DireccionDAO::save($dir);
        }catch (Exception $e) {
            DAO::transRollback();
            Logger::error("No se ha podido guardar la direccion (Desde Unit Tests): " . $e);
            if ($e->getCode() == 901)
                throw new Exception("No se ha podido guardar la nueva direccion (desde Unit Test)|: " . $e->getMessage(), 901);
            throw new Exception("No se ha podido guardar la nueva direccion (Desde Unit Tests)", 901);
        }
        DAO::transEnd();

        $empresa = new Empresa();
        $empresa->setRazonSocial("Razon Social - ".time());
        $empresa->setRfc(time());
        $empresa->setIdDireccion($dir->getIdDireccion());
        $empresa->setFechaAlta(mktime());
        $empresa->setIdLogo(1);
        $empresa->setActivo(1);

        DAO::transBegin();
        try {
            EmpresaDAO::save($empresa);
        }catch (Exception $e) {
            DAO::transRollback();
            Logger::error("No se ha podido guardar la nueva empresa (Desde Unit Tests): " . $e);
            if ($e->getCode() == 901)
                throw new Exception("No se ha podido guardar la nueva empresa (desde Unit Test)|: " . $e->getMessage(), 901);
            throw new Exception("No se ha podido guardar la nueva empresa (Desde Unit Tests)", 901);
        }
        DAO::transEnd();

        $res = ContabilidadController::NuevoCatalogoCuentasEmpresa($empresa->getIdEmpresa());
        //return array("status"=>"ok","id_catalogo_cuentas"=>$nuevo_catalogo_cuentas->getIdCatalogo());
        $this->assertSame('ok', $res["status"]);
        $this->assertInternalType('int', ((int)$res["id_catalogo_cuentas"]));
    }
    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaMismoNombre()
    {
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Circulante', 1, 0, $id_catalogo_cuentas, "Deudora", 
                                            "Bancos", "Balance", $id_cuenta_padre = ""
                                            );

        ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Circulante', 1, 0, $id_catalogo_cuentas, "Deudora", 
                                            "Bancos", "Balance", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaMayorYOrden()
    {
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Circulante', 1, 1, $id_catalogo_cuentas,"Deudora", 
                                            "Cajas", "Balance", $id_cuenta_padre = ""
                                            );

    }

    public function testNuevaCuentaSeaAfectable()
    {
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        $randmon_str = "Cuenta X ".time();
        $id_nueva_cuenta = ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Circulante', 1, 0, $id_catalogo_cuentas,"Deudora", 
                                            $randmon_str, "Balance", $id_cuenta_padre = ""
                                            );

        $nueva = CuentaContableDAO::getByPK($id_nueva_cuenta['id_cuenta_contable']);

        $this->assertEquals($nueva->getAfectable(),1);

    }

        public function testNuevaCuentaSeaAfectablePadreNoAfectable()
    {
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        $randmon_str = "Cuenta Padre ".time();
        $id_nueva_cuenta = ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Circulante', 1, 0, $id_catalogo_cuentas,"Deudora", 
                                            $randmon_str, "Balance", $id_cuenta_padre = ""
                                            );

        $padre = CuentaContableDAO::getByPK($id_nueva_cuenta['id_cuenta_contable']);

        $this->assertEquals($padre->getAfectable(),1);

        $randmon_str = "Cuenta Hija ".time();
        $id_nueva_cuenta2 = ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Circulante', 1, 0, $id_catalogo_cuentas,"Deudora", 
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
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Ingresos', 1, 0, $id_catalogo_cuentas,"Deudora", 
                                            "Bancos", "Balance", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaBalanceDeudoraNoEgresos()
    {
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Egresos', 1, 0, $id_catalogo_cuentas,"Deudora", 
                                            "Bancos", "Balance", $id_cuenta_padre = ""
                                            );
    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaBalanceDeudoraNoPasivoCirculante()
    {
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Pasivo Circulante', 1, 0, $id_catalogo_cuentas,"Deudora", 
                                            "Bancos", "Balance", $id_cuenta_padre = ""
                                            );
    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaBalanceDeudoraNoPasivoLargoPlazo()
    {
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Pasivo Largo Plazo', 1, 0, $id_catalogo_cuentas,"Deudora", 
                                            "Bancos", "Balance", $id_cuenta_padre = ""
                                            );
    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaBalanceDeudoraNoCapitalContable()
    {
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Capital Contable', 1, 0, $id_catalogo_cuentas,"Deudora", 
                                            "Bancos", "Balance", $id_cuenta_padre = ""
                                            );
    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaBalanceAcreedoraNoIngresos()
    {
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Ingresos', 1, 0, $id_catalogo_cuentas,"Acreedora", 
                                            "Bancos", "Balance", $id_cuenta_padre = ""
                                            );

    }

        /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaBalanceAcreedoraNoEgresos()
    {
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Egresos', 1, 0, $id_catalogo_cuentas,"Acreedora", 
                                            "Bancos", "Balance", $id_cuenta_padre = ""
                                            );

    }

        /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaBalanceAcreedoraNoActivoDiferido()
    {
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Diferido', 1, 0, $id_catalogo_cuentas,"Acreedora", 
                                            "Bancos", "Balance", $id_cuenta_padre = ""
                                            );

    }

        /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaBalanceAcreedoraNoActivoFijo()
    {
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Fijo', 1, 0, $id_catalogo_cuentas,"Acreedora", 
                                            "Bancos", "Balance", $id_cuenta_padre = ""
                                            );

    }

        /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaBalanceAcreedoraNoActivoCirculante()
    {
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Circulante', 1, 0, $id_catalogo_cuentas,"Acreedora", 
                                            "Bancos", "Balance", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaBalanceAcreedoraNoCapitalContable()
    {
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Capital Contable', 1, 0, $id_catalogo_cuentas,"Deudora", 
                                            "Bancos", "Balance", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaEstadoResultadosAcreedoraNoActivoFijo()
    {
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Fijo', 1, 0, $id_catalogo_cuentas,"Acreedora", 
                                            "Bancos", "Estado de Resultados", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaEstadoResultadosAcreedoraNoActivoCirculante()
    {
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Circulante', 1, 0, $id_catalogo_cuentas,"Acreedora", 
                                            "Bancos", "Estado de Resultados", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaEstadoResultadosAcreedoraNoActivoDiferido()
    {
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Diferido', 1, 0, $id_catalogo_cuentas,"Acreedora", 
                                            "Bancos", "Estado de Resultados", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaEstadoResultadosAcreedoraNoPasivoCirculante()
    {
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Pasivo Circulante', 1, 0, $id_catalogo_cuentas,"Acreedora", 
                                            "Bancos", "Estado de Resultados", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaEstadoResultadosAcreedoraNoPasivoLargoPlazo()
    {
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Pasivo Largo Plazo', 1, 0, $id_catalogo_cuentas,"Acreedora", 
                                            "Bancos", "Estado de Resultados", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaEstadoResultadosAcreedoraNoCapitalContable()
    {
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Capital Contable', 1, 0, $id_catalogo_cuentas,"Acreedora", 
                                            "Bancos", "Estado de Resultados", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaEstadoResultadosAcreedoraNoEgresos()
    {
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Egresos', 1, 0, $id_catalogo_cuentas,"Acreedora", 
                                            "Bancos", "Estado de Resultados", $id_cuenta_padre = ""
                                            );

    }

        /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaEstadoResultadosDeudoraNoActivoFijo()
    {
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Fijo', 1, 0, $id_catalogo_cuentas,"Deudora", 
                                            "Bancos", "Estado de Resultados", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaEstadoResultadosDeudoraNoActivoCirculante()
    {
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Circulante', 1, 0, $id_catalogo_cuentas,"Deudora", 
                                            "Bancos", "Estado de Resultados", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaEstadoResultadosDeudoraNoActivoDiferido()
    {
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Activo Diferido', 1, 0, $id_catalogo_cuentas,"Deudora", 
                                            "Bancos", "Estado de Resultados", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaEstadoResultadosDeudoraNoPasivoCirculante()
    {
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Pasivo Circulante', 1, 0, $id_catalogo_cuentas,"Deudora", 
                                            "Bancos", "Estado de Resultados", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaEstadoResultadosDeudoraNoPasivoLargoPlazo()
    {
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Pasivo Largo Plazo', 1, 0, $id_catalogo_cuentas,"Deudora", 
                                            "Bancos", "Estado de Resultados", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaEstadoResultadosDeudoraNoCapitalContable()
    {
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Capital Contable', 1, 0, $id_catalogo_cuentas,"Deudora", 
                                            "Bancos", "Estado de Resultados", $id_cuenta_padre = ""
                                            );

    }

    /**
    * @expectedException BusinessLogicException
    */
    public function testNuevaCuentaEstadoResultadosDeudoraNoIngresos()
    {
        $id_catalogo_cuentas = self::NuevoCatalogoCuentas();
        ContabilidadController::NuevaCuenta(0, 1,
                                            'Ingresos', 1, 0, $id_catalogo_cuentas,"Deudora", 
                                            "Bancos", "Estado de Resultados", $id_cuenta_padre = ""
                                            );

    }



}
?>