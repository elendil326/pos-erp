<?php


date_default_timezone_set ( "America/Mexico_City" );

if(!defined("BYPASS_INSTANCE_CHECK"))
	define("BYPASS_INSTANCE_CHECK", false);

$_GET["_instance_"] = 71;

require_once("../../server/bootstrap.php");





class SucursalesControllerTest extends PHPUnit_Framework_TestCase {
	
	protected function setUp(){
		Logger::log("-----------------------------");
		$r = SesionController::Iniciar(123, 1, true);
	}


    public function testNuevaSucursal(){
            
		$sucursal = SucursalesController::Nueva(array(
            "calle"                 => "Monte Balcanes",
        	"numero_exterior"       => "107",
            "colonia"               => "Arboledas",
            "id_ciudad"             => 334,
       	    "codigo_postal"         => "38060",
            "numero_interior"       => null,
            "referencia"            => "Calle cerrada",
       	    "telefono1"             => "4616149974",
            "telefono2"             => "45*451*454"
        ), "Sucursal_" . time());

        $this->assertInternalType("int" , $sucursal["id_sucursal"]);

    }
    

    /**
     * @expectedException BusinessLogicException
     */
    public function testNuevaSucursalRepetida(){

        $direccion = Array(
            "calle"                 => "Monte Balcanes",
        	"numero_exterior"       => "107",
            "colonia"               => "Arboledas",
            "id_ciudad"             => 334,
       	    "codigo_postal"         => "38060",
            "numero_interior"       => null,
            "referencia"            => "Calle cerrada",
       	    "telefono1"             => "4616149974",
            "telefono2"             => "45*451*454"
        );

        try{
            SucursalesController::Nueva($direccion, "Sucursal_Repetida");
        }catch(Exception $e){

        }

        SucursalesController::Nueva($direccion, "Sucursal_Repetida");

    }

    public function testBuscar(){
        
        //creamos una sucursal para fines del experimento

        $sucursal = SucursalesController::Nueva(array(
            "calle"                 => "Monte Balcanes",
        	"numero_exterior"       => "107",
            "colonia"               => "Arboledas",
            "id_ciudad"             => 334,
       	    "codigo_postal"         => "38060",
            "numero_interior"       => null,
            "referencia"            => "Calle cerrada",
       	    "telefono1"             => "4616149974",
            "telefono2"             => "45*451*454"
        ), "Sucursal" . time());

        
        //realizamos una busqueda general y verificamso que contenga los parametros de respuesta
        $busqueda = SucursalesController::Buscar();

        $this->assertArrayHasKey('resultados', $busqueda);
        $this->assertArrayHasKey('numero_de_resultados', $busqueda);

        $this->assertInternalType('int', $busqueda['numero_de_resultados']);
        $this->assertInternalType('array', $busqueda['resultados']);

        $this->assertGreaterThanOrEqual(0, $busqueda['numero_de_resultados']);

        //probamos la busqueda por activo, al menos debe de haber una, ya que cuando se cree esta sucursal estara activa  
        $busqueda = SucursalesController::Buscar($activo = 1, $id_empresa = null, $limit = null, $query = null, $sort = null, $start = null);
        $this->assertGreaterThanOrEqual(1, $busqueda["numero_de_resultados"]);

        //probamos busqueda por start
        $busqueda = SucursalesController::Buscar($activo = null, $id_empresa = null, $limit = null, $query = null, $sort = null, $start = 1);       
        $this->assertGreaterThanOrEqual(1, $busqueda["numero_de_resultados"]);

        //probamos busqueda por limit
        $busqueda = SucursalesController::Buscar($activo = null, $id_empresa = null, $limit = 1, $query = null, $sort = null, $start = null);
        $this->assertGreaterThanOrEqual(1, $busqueda["numero_de_resultados"]);

        //probamos busqueda por query
        $busqueda = SucursalesController::Buscar($activo = null, $id_empresa = null, $limit = null, $query = 1, $sort = null, $start = null);
        $this->assertGreaterThanOrEqual(0, $busqueda["numero_de_resultados"]);

        //probamos busqueda por id_empresa
        $busqueda = SucursalesController::Buscar($activo = null, $id_empresa = 1, $limit = null, $query = null, $sort = null, $start = null);
        $this->assertGreaterThanOrEqual(0, $busqueda["numero_de_resultados"]);

        //valores combinados
        $busqueda = SucursalesController::Buscar($activo = 1, $id_empresa = 1, $limit = 1, $query = 1, $sort = 1, $start = 1);

        $this->assertGreaterThanOrEqual(0, $busqueda["numero_de_resultados"]);

    }

	public function testEliminar(){

        //creamos una sucursal para fines del experimento

        $sucursal = SucursalesController::Nueva(array(
            "calle"                 => "Monte Balcanes",
        	"numero_exterior"       => "107",
            "colonia"               => "Arboledas",
            "id_ciudad"             => 334,
       	    "codigo_postal"         => "38060",
            "numero_interior"       => null,
            "referencia"            => "Calle cerrada",
       	    "telefono1"             => "4616149974",
            "telefono2"             => "45*451*454"
        ), "Eliminar_Sucursal_" . time());

        //eliminamos la sucursal (desactivamos)
        
        SucursalesController::Eliminar( $sucursal["id_sucursal"] );
        
        $sucursal = SucursalDAO::getByPK( $sucursal["id_sucursal"] );

        $this->assertEquals(0, $sucursal->getActiva());

    }

	public function testEditar(){

        //creamos una sucursal para fines del experimento

        $sucursal = SucursalesController::Nueva(array(
            "calle"                 => "Monte Balcanes",
        	"numero_exterior"       => "107",
            "colonia"               => "Arboledas",
            "id_ciudad"             => 334,
       	    "codigo_postal"         => "38060",
            "numero_interior"       => null,
            "referencia"            => "Calle cerrada",
       	    "telefono1"             => "4616149974",
            "telefono2"             => "45*451*454"
        ), "Editar_Sucursal_" . time());

        //para cambiar la moneda
        try{
            $moneda = new Moneda(array( 
                "nombre" => "Moneda_" . time(),
                "simbolo" => "Simbolo_" . time(),
                "activa" => 1
            ));
            MonedaDAO::save($moneda);
        }catch(Exception $e){
            
        }

        SucursalesController::Editar(
		    $id_sucursal = $sucursal["id_sucursal"],
		    $activo = 0,
		    $descripcion = "Descripcion de la sucursal",
		    $direccion = "[
                {
                    \"id_direccion\":605,
                    \"tipo\": \"postal\",
                    \"calle\": \"Francisco I Madero\",
                    \"numero_exterior\": \"1009A\",
                    \"numero_interior\": \"12\",
                    \"colonia\": \"centro\",
                    \"codigo_postal\": \"38000\",
                    \"telefono1\": \"4611223312\",
                    \"telefono2\": \"\",
                    \"id_ciudad\": 3,
                    \"referencia\": \"El local naranja\"
                }
            ]",
		    $empresas = "[1]",
		    $id_gerente = 1,
		    $id_moneda = $moneda->getIdMoneda(),
		    $razon_social = "Empresa x",
		    $saldo_a_favor = "100000"
	    );

    }

}				
