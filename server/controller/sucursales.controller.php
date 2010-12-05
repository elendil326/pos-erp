<?php

function listarSucursales( $args ){

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


switch( $args['action'] )
{
    case 700://listar sucursales
        listarSucursales(  );
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

?>