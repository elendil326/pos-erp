<?php

require_once('model/sucursal.dao.php');



function listarSucursales(  ){

    $s = new Sucursal();
    $s->setActivo(1);

    $sucursales = SucursalDAO::search($s);

    $array_sucursales = array();

    foreach( $sucursales as $sucursal )
    {
        array_push( $array_sucursales , array(
            'id_sucursal' => $sucursal->getIdSucursal(),
            'descripcion' => $sucursal->getDescripcion(),
            'text' => $sucursal->getDescripcion(),
            'value' => $sucursal->getIdSucursal()
        ));
    }

	return $array_sucursales;
}

function abrirSucursal(){

}

function editarSucursal(){

}

function cerrarSucursal(){

}

function listarPersonal(){

}

function estadisticasVentas(){

}

function presindirEmpleado(){

}

function agregarGerente(){

}

function corte(){

}

function clientesDeudores(){

}

function inventarioSucursal(){

}



if(isset($args['action'])){

	switch( $args['action'] )
	{
		case 700://listar sucursales
		    printf('{"success" :" true", "datos": %s}', json_encode( listarSucursales(  ) ) );
		break;

		case 701://abrir sucursal
		    abrirSucursal( $args );
		break;

		case 702://editar detalle sucursal
		    editarSucursal( $args );
		break;

		case 703://cerrar sucursal
		    cerrarSucursal( $args );
		break;

		case 704://listar personal
		    listarPersonal(  );
		break;

		case 705://estadisticas de venta por empleado
		    estadisticasVentas( $args );
		break;

		case 706://presindir empleado
		    presindirEmpleado( $args );
		break;

		case 707://agregar gerentes
		    agregarGerente( $args );
		break;

		case 708://hacer corte
		    corte( $args );
		break;

		case 709://clientes deudores sucursal (arrojara le total de las deudas de la sucursal)
		    clientesDeudores( $args );
		break;

		case 710://flujo de efectivo
		    
		break;

		case 711://inventario por sucursal
		    inventarioSucursal( $args );
		break;

	}
}

