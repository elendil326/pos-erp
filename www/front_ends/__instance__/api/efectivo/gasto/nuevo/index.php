<?php
/**
  * GET api/efectivo/gasto/nuevo
  * Registrar un gasto.
  *
  * Registrar un gasto. El usuario y la sucursal que lo registran ser?n tomados de la sesi?n actual.

Update :Ademas deber?a tambi?n de tomar la fecha de ingreso del gasto del servidor y agregar tambi?n como par?metro una fecha a la cual se deber?a de aplicar el gasto. Por ejemplo si el d?a 09/09/11 (viernes) se tomo dinero para pagar la luz, pero resulta que ese d?a se olvidaron de registrar el gasto y lo registran el 12/09/11 (lunes). Entonces tambien se deberia de tomar como parametro una fecha a la cual aplicar el gasto, tambien se deberia de enviar como parametro una nota
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.gasto.nuevo.php");

$api = new ApiEfectivoGastoNuevo();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
