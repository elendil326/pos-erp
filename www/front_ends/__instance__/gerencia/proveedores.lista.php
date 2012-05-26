<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

		$page->addComponent( new TitleComponent( "Proveedores" ) );
		$page->addComponent( new MessageComponent( "Lista de proveedores" ) );
		
		
		
		$proveedoresLista =  ProveedoresController::Lista();
		
		$tabla = new TableComponent( 
			array(
				"nombre"                        => "Nombre",
				"id_clasificacion_proveedor" 	=> "Clasificacion de proveedor",
                "activo"                        => "Activo",
                "consignatario"                 => "Consignatario"
			),
			$proveedoresLista["resultados"]

		);
    
        function funcion_rol($id_rol)
        {
            return (RolDAO::getByPK($id_rol) ? RolDAO::getByPK($id_rol)->getNombre() : "sin rol" );
        }
        
        function funcion_clasificacion_proveedor($id_clasificacion_proveedor)
        {
            return (ClasificacionProveedorDAO::getByPK($id_clasificacion_proveedor) ? ClasificacionProveedorDAO::getByPK($id_clasificacion_proveedor)->getNombre() : "----" );
        }
        
        function funcion_activo($activo)
        {
            return ($activo ? "Activo" : "Inactivo" );
        }
        
        function funcion_consignatario($consignatario)
        {
            return ($consignatario ? "Consignatario" : "----" );
        }
        
        $tabla->addColRender("id_rol", "funcion_rol");
        $tabla->addColRender("id_clasificacion_proveedor", "funcion_clasificacion_proveedor");
        $tabla->addColRender("activo", "funcion_activo");
        $tabla->addColRender("consignatario", "funcion_consignatario");
                
		$tabla->addOnClick( "id_usuario", "(function(a){ window.location = 'proveedores.ver.php?pid=' + a; })" );
		
			
		$page->addComponent( $tabla );
                
		$page->render();
