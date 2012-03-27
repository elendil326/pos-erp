<?php



require_once("../../server/bootstrap.php");

require_once("Utils.php");


class AlmacenControllerTest extends PHPUnit_Framework_TestCase {

	

	protected function setUp(){
		Logger::log("-----------------------------");
		$r = SesionController::Iniciar(123, 1, true);

	}

    /**
     * Nuevo Tipo de Almacen
     */
	public function testTipoNuevo(){

		$a = AlmacenesController::NuevoTipo("Nuevo_Tipo_Almacen" . time());	
		$this->assertInternalType("int", $a["id_tipo_almacen"]);

	}

    /**
     * Editar Tipo de Almacen
     */
	public function testTipoEditar(){

        $target = "descripcion_editada_" . time();

		$a = AlmacenesController::NuevoTipo("Tipo_Almacen_Actual_" . time());	
		$this->assertInternalType("int", $a["id_tipo_almacen"]);

        AlmacenesController::EditarTipo(
            $id_tipo_almacen = $a["id_tipo_almacen"], 
            $activo = null,
		    $descripcion = $target
        );

        $this->assertEquals($target, TipoAlmacenDAO::getByPK($a["id_tipo_almacen"])->getDescripcion(), "Error al editar la descripcion del tipo de almacen");

	}

    /**
     * Desactivar Tipo de Almacen
     */
	public function testTipoDesactivar(){        

		$a = AlmacenesController::NuevoTipo("Desactivar_Tipo_Almacen" . time());

        AlmacenesController::DesactivarTipo(
            $id_tipo_almacen = $a["id_tipo_almacen"]
        );
        
        $this->assertEquals(0, TipoAlmacenDAO::getByPK( $a["id_tipo_almacen"] )->getActivo(), "Error al desactivar el tipo de almacen");

	}

    /**
     * Buscar Tipo de Almacen
     */
	public function testTipoBuscar(){

        //creamos una sucursal para fines del experimento

        $a = AlmacenesController::NuevoTipo("Buscar_Tipo_Almacen" . time());
        
        //realizamos una busqueda general y verificamso que contenga los parametros de respuesta
        $busqueda = AlmacenesController::BuscarTipo(
            $activo = 0, 
    		$limit = 30, 
    		$query = "Tipo_Almacen", 
    		$start = 0
        );

        $this->assertArrayHasKey('resultados', $busqueda);
        $this->assertArrayHasKey('numero_de_resultados', $busqueda);

        $this->assertInternalType('int', $busqueda['numero_de_resultados']);
        $this->assertInternalType('array', $busqueda['resultados']);

        $this->assertGreaterThanOrEqual(0, $busqueda['numero_de_resultados']);

        //probamos la busqueda por activo, al menos debe de haber una, ya que cuando se cree esta sucursal estara activa  
        $busqueda = AlmacenesController::BuscarTipo(
            $activo = 1, 
    		$limit = null, 
    		$query = null, 
    		$start = null
        );
        $this->assertGreaterThanOrEqual(1, $busqueda["numero_de_resultados"]);

        //probamos busqueda por query
        $busqueda = AlmacenesController::BuscarTipo(
            $activo = null, 
    		$limit = null,
    		$query = "Tipo_Almacen", 
    		$start = null
        );
        $this->assertGreaterThanOrEqual(0, $busqueda["numero_de_resultados"]);

	}

    /**
     * Nuevo Almacen
     */
    public function testNuevoAlmacen(){

        $tipo_almacen = AlmacenesController::NuevoTipo("Nuevo_Tipo_Almacen_" . time());	

        DireccionDAO::save( $direccion = new Direccion(array(
            "calle" => "Una Calle",
            "numero_exterior" => "322",
            "id_ciudad" => "12",
            "codigo_postal" => "38000",
            "ultima_modificacion" => "2012-02-21 22:10:45",
            "id_usuario_ultima_modificacion" => "2"
        )));

        EmpresaDAO::save( $empresa = new Empresa ( array(
            "id_direccion" => $direccion->getIdDireccion(),
            "rfc" => "RFC_" . time(),
            "razon_social" => "Empresa_Razon_Social____" . time(),
            "fecha_alta" => "2012-02-21 22:10:45",
            "activo" => 1,
            "direccion_web" => "Dir_" . time()
        )));

        SucursalDAO::save($sucursal = new Sucursal(array(
            "id_direccion" => $direccion->getIdDireccion(),
            "razon_social" => "Sucursal_Razon_Social____" . time(),
            "saldo_a_favor" => 2000,
            "fecha_apertura" => "2012-02-21 22:10:45",
            "activa" => 1
        )));

        $almacen = AlmacenesController::Nuevo(
		    $id_empresa = $empresa->getIdEmpresa(), 
		    $id_sucursal = $sucursal->getIdSucursal(), 
		    $id_tipo_almacen = $tipo_almacen["id_tipo_almacen"], 
		    $nombre = "Almacen_" . time(), 
		    $descripcion = "Almacen de prueba " . time()
	    );

        $this->assertInternalType("int", $almacen["id_almacen"]);

    }

    /**
     * Editar Almacen
     */
    public function testEditarAlmacen(){

        DireccionDAO::save( $direccion = new Direccion(array(
            "calle" => "Una Calle",
            "numero_exterior" => "322",
            "id_ciudad" => "12",
            "codigo_postal" => "38000",
            "ultima_modificacion" => "2012-02-21 22:10:45",
            "id_usuario_ultima_modificacion" => "2"
        )));

        EmpresaDAO::save( $empresa = new Empresa ( array(
            "id_direccion" => $direccion->getIdDireccion(),
            "rfc" => "RFC_" . time(),
            "razon_social" => "Empresa_Razon_Social_" . time(),
            "fecha_alta" => "2012-02-21 22:10:45",
            "activo" => 1,
            "direccion_web" => "Dir_" . time()
        )));

        SucursalDAO::save($sucursal = new Sucursal(array(
            "id_direccion" => $direccion->getIdDireccion(),
            "razon_social" => "Sucursal_Razon_Social_" . time(),
            "saldo_a_favor" => 2000,
            "fecha_apertura" => "2012-02-21 22:10:45",
            "activa" => 1
        )));
        
        $tipo_almacen = AlmacenesController::NuevoTipo("Nuevo_Tipo_Almacen__" . time());	        

        $almacen = AlmacenesController::Nuevo(
		    $id_empresa = $empresa->getIdEmpresa(), 
		    $id_sucursal = $sucursal->getIdSucursal(), 
		    $id_tipo_almacen = $tipo_almacen["id_tipo_almacen"], 
		    $nombre = "Almacen_Editar" . time(), 
		    $descripcion = "Almacen de prueba_ " . time()
	    );

        $tipo_almacen_mod = AlmacenesController::NuevoTipo("Nuevo_Tipo_Almacen__mod_" . time());	        

        // Probamos editar el tipo de almacen

        $almacen_editado = AlmacenesController::Editar(
            $id_almacen = $almacen["id_almacen"], 
		    $descripcion = null, 
		    $id_tipo_almacen = $tipo_almacen_mod["id_tipo_almacen"], 
		    $nombre = null
        );

        $_almacen = AlmacenDAO::getByPK($almacen["id_almacen"]);

        $this->assertEquals($tipo_almacen_mod["id_tipo_almacen"], $_almacen->getIdTipoAlmacen());

        // Probamos editar la descripcion del almacen

        $almacen_editado = AlmacenesController::Editar(
            $id_almacen = $almacen["id_almacen"], 
		    $descripcion = "Descripcion Editada", 
		    $id_tipo_almacen = null, 
		    $nombre = null
        );

        $_almacen = AlmacenDAO::getByPK($almacen["id_almacen"]);

        $this->assertEquals("Descripcion Editada", $_almacen->getDescripcion());

        // Probamos editar el nombre del Alamcen

        $almacen_editado = AlmacenesController::Editar(
            $id_almacen = $almacen["id_almacen"], 
		    $descripcion = null, 
		    $id_tipo_almacen = null, 
		    $nombre = "Nombre del Almacen"
        );

        $_almacen = AlmacenDAO::getByPK($almacen["id_almacen"]);

        $this->assertEquals("Nombre del Almacen", $_almacen->getNombre());

    }


    /**
     * Desactivar Almacen
     */
    public function testDesactivarAlmacen(){

        $usuario = UsuarioDAO::getAll();
    
        $id_usuario = $usuario[0]->getIdUsuario();

        DireccionDAO::save( $direccion = new Direccion(array(
            "calle" => "Una Calle",
            "numero_exterior" => "322",
            "id_ciudad" => "12",
            "codigo_postal" => "38000",
            "ultima_modificacion" => "2012-02-21 22:10:45",
            "id_usuario_ultima_modificacion" => "2"
        )));

        EmpresaDAO::save( $empresa = new Empresa ( array(
            "id_direccion" => $direccion->getIdDireccion(),
            "rfc" => "RFC_" . time(),
            "razon_social" => "Empresa_Razon_Social__" . time(),
            "fecha_alta" => "2012-02-21 22:10:45",
            "activo" => 1,
            "direccion_web" => "Dir_" . time()
        )));

        SucursalDAO::save($sucursal = new Sucursal(array(
            "id_direccion" => $direccion->getIdDireccion(),
            "razon_social" => "Sucursal_Razon_Social__" . time(),
            "saldo_a_favor" => 2000,
            "fecha_apertura" => "2012-02-21 22:10:45",
            "activa" => 1
        )));
        
        $tipo_almacen = AlmacenesController::NuevoTipo("Nuevo_Tipo_Almacen___" . time());	        

        $almacen = AlmacenesController::Nuevo(
		    $id_empresa = $empresa->getIdEmpresa(), 
		    $id_sucursal = $sucursal->getIdSucursal(), 
		    $id_tipo_almacen = $tipo_almacen["id_tipo_almacen"], 
		    $nombre = "Almacen_Editar" . time(), 
		    $descripcion = "Almacen de prueba_ " . time()
	    );

        // Desactivamos el Almacen

        $almacen_desactivado = AlmacenesController::Desactivar(
            $id_almacen = $almacen["id_almacen"]
        );

        $_almacen = AlmacenDAO::getByPK($almacen["id_almacen"]);

        $this->assertEquals(0, $_almacen->getActivo());

    }

    public function testNuevoLote(){
        
    }
    


    /**
     * Desactivar Almacen con Productos
     */
    public function testDesactivarAlmacenProductos(){ 

/*        DireccionDAO::save( $direccion = new Direccion(array(
            "calle" => "Una Calle",
            "numero_exterior" => "322",
            "id_ciudad" => "12",
            "codigo_postal" => "38000",
            "ultima_modificacion" => "2012-02-21 22:10:45",
            "id_usuario_ultima_modificacion" => "2"
        )));

        EmpresaDAO::save( $empresa = new Empresa ( array(
            "id_direccion" => $direccion->getIdDireccion(),
            "rfc" => "RFC_" . time(),
            "razon_social" => "Empresa_Razon_Social___" . time(),
            "fecha_alta" => "2012-02-21 22:10:45",
            "activo" => 1,
            "direccion_web" => "Dir_" . time()
        )));

        SucursalDAO::save($sucursal = new Sucursal(array(
            "id_direccion" => $direccion->getIdDireccion(),
            "razon_social" => "Sucursal_Razon_Social___" . time(),
            "saldo_a_favor" => 2000,
            "fecha_apertura" => "2012-02-21 22:10:45",
            "activa" => 1
        )));
        
        $tipo_almacen = AlmacenesController::NuevoTipo("Nuevo_Tipo_Almacen____" . time());	        

        $almacen = AlmacenesController::Nuevo(
		    $id_empresa = $empresa->getIdEmpresa(), 
		    $id_sucursal = $sucursal->getIdSucursal(), 
		    $id_tipo_almacen = $tipo_almacen["id_tipo_almacen"], 
		    $nombre = "Almacen_Editar" . time(), 
		    $descripcion = "Almacen de prueba_ " . time()
	    );

        MonedaDAO::save( $moneda = new Moneda( array(
            "nombre" => "Moneda",
            "simbolo" => "MN",
            "activa" => 1
        ); ) );

        TarifaDAO::save( $tarifa = new Tarifa( array(
            "nombre" => "",
            "tipo_tarifa" => "",
            "activa" => "",
            "" => "",
            "" => "",
            "" => "",
            "" => "",
            "" => "",
            "" => "",
        ) ) );
        
        UsuarioDAO::save( $usuario = new Usuario( array(
            "id_rol" => 
            "fecha_asignacion_rol" => 
            "nombre" => 
            "fecha_alta" => 
            "limite_credito" => 
            "activo" => 
            "limite_credito" => 
            "password" => 
            "consignatario" => 
            "saldo_del_ejercicio" => 
            "id_tarifa_compra" => 
            "tarifa_compra_obtenida" => 
            "id_tarifa_venta" => 
            "tarifa_venta_obtenida" => 
        ) ) );

        LoteDAO::save( $lote = new Lote( array(
            "id_compra" => 1,
            "id_usuario" => 1,
            "fecha_ingreso" => "2012-02-21 22:10:45",
            "observaciones" => ""
        ) ) );

        LoteAlmacenDAO::save( $lote_almacen = new LoteAlmacen( array(
            "id_lote" => $lote->getIdLote(),
            "id_almacen" => $almacen["id_almacen"]
        ) ) );

        ProductoDAO::save( $producto = new Producto( array(
            
        ) ) );

        LoteProducto::save( $lote_producto = new LoteProducto( array(
            "id_lote" => $lote->getIdLote(),
            "id_producto" => $producto->getIdProducto(),
            "cantidad" => 100
        ) ) );

        // Desactivamos el Almacen

        $almacen_desactivado = AlmacenesController::Desactivar(
            $id_almacen = $almacen["id_almacen"]
        );

        $_almacen = AlmacenDAO::getByPK($almacen["id_almacen"]);

        $this->assertEquals(0, $_almacen->getActivo());
*/

    }


//--------------------------------------------------


	/**
	*
	*
	*	Almacenes
	*
	**/

	
	//Imprime la lista de tipos de almacen
	public function testTipoBuscarYDesactivar(){

		$r = AlmacenesController::BuscarTipo();

		$this->assertInternalType("int", $r["numero_de_resultados"]);

		$this->assertEquals( $r["numero_de_resultados"], count($r["resultados"]) );

		if($r["numero_de_resultados"] == 0){
			return;
		}


		foreach ($r["resultados"] as $tipo) {
			$tipo = $tipo;
			if($tipo["descripcion"] == "1dee80c7d5ab2c1c90aa8d2f7dd47256"){
				//ya existe este tipo para testing, hay que desactivarlo

				Logger::testerLog("Ya encontre el repetido, procedo a desactivar");
				$d = AlmacenesController::DesactivarTipo( $tipo["id_tipo_almacen"] );
			}
		}

		//volvamos a buscar y esperemos que ya no exista
		$r = AlmacenesController::BuscarTipo();

		$found = false;

		foreach ($r["resultados"] as $tipo) {
			$tipo = $tipo;
			if($tipo["descripcion"] == "1dee80c7d5ab2c1c90aa8d2f7dd47256"){
				//ya existe este tipo para testing, hay que desactivarlo
				$found = true;
			}
		}

		$this->assertFalse($found);
	}




	



	/**
     * @expectedException BusinessLogicException
     */
	/*public function testTipoNuevoRepetido(){
		$a = AlmacenesController::NuevoTipo("1dee80c7d5ab2c1c90aa8d2f7dd47256");	
	}


	public function testBuscar(){
		
	}

	public function testDesactivar(){
		
	}

	public function testEditar(){
		
	}*/


	/**
	*
	*
	*	Lotes
	*
	**/
	/*public function testLoteNuevo(){
		//AlamacenController::NuevoLote(  );
	}

	public function testLoteEntrada(){
		
	}



	public function testLoteSalida(){
		
	}

	public function testLoteTraspasoBuscar(){
		
	}

	public function testLoteTraspasoCancelar(){
		
	}

	public function testLoteTraspasoEditar(){
		
	}

	public function testLoteTraspasoEnviar(){
		
	}

	public function testLoteTraspasoProgramar(){
		
	}

	public function testLoteTraspasoRecibir(){
		
	}

	public function testNuevo(){
		
	}*/


	/*public function testTipoBuscar(){}
	public function testTipoDesactivar(){}
	public function testTipoEditar(){}
	public function testTipoNuevo(){}*/
		
}
