<?php
/**
  * POST api/cliente/nuevo
  * Insertar un nuevo cliente.
  *
  * Crear un nuevo cliente. Para los campos de Fecha_alta y Fecha_ultima_modificacion se usar? la fecha actual del servidor. El campo Agente y Usuario_ultima_modificacion ser?n tomados de la sesi?n activa. Para el campo Sucursal se tomar? la sucursal activa donde se est? creando el cliente. 

Al crear un cliente se le creara un usuario para la interfaz de cliente y pueda ver sus facturas y eso, si tiene email. Al crearse se le enviara un correo electronico con el url.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.cliente.nuevo.php");

$api = new ApiClienteNuevo();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
