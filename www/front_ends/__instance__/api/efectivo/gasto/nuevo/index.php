<?php
/**
  * GET api/efectivo/gasto/nuevo
  * Registrar un gasto.
  *
  * Registrar un gasto. El usuario y la sucursal que lo registran ser?tomados de la sesi?ctual.

Update :Ademas deber?tambi?de tomar la fecha de ingreso del gasto del servidor y agregar tambi?como par?tro una fecha a la cual se deber?de aplicar el gasto. Por ejemplo si el d?09/09/11 (viernes) se tomo dinero para pagar la luz, pero resulta que ese d?se olvidaron de registrar el gasto y lo registran el 12/09/11 (lunes). Entonces tambien se deberia de tomar como parametro una fecha a la cual aplicar el gasto, tambien se deberia de enviar como parametro una nota
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.gasto.nuevo.php");

$api = new ApiEfectivoGastoNuevo();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
