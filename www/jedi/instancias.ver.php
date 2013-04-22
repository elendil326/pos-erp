<?php

    define("BYPASS_INSTANCE_CHECK", true);

    require_once("../../server/bootstrap.php");

    function dispatcher()
    {
        switch($_GET["action"]){
            case "del":
                $response = json_decode(InstanciasController::Eliminar($_GET["id"]));
                if ($response->success === "false") {
                    ?>
                        <script>
                         (function(){alert("<?php echo $response->reason;?>");})();
                        </script>
                    <?php
                }else{
?>
                        <script>
                         (function(){alert("Instancia eliminada correctamente");location.href="instancias.lista.php";})();
                        </script>
<?php
                }
                break;
            default :
                return;
        }
    }

    if (isset($_GET["action"])) {
        dispatcher();
    }

    $p = new JediComponentPage("Detalles de la instancia");

    $p->requireParam("id", "GET", "Esta instancia no existe.", "InstanciasController::Detalles");

    $instancia = InstanciasController::Detalles($_GET["id"]);

    $p->addComponent(new TitleComponent("Detalles de la instancia" ));

    $p->addComponent("<br><a href='../". $instancia["instance_token"] ."/' target='_new'><div class='POS Boton'>Visitar la instancia</div></a>");

    $p->addComponent("<a href='./instancias.editar.php?id=" . $_GET["id"]. "'><div class='POS Boton'>Editar Informaci&oacute;n</div></a>");

    $p->addComponent("<div class='POS Boton' onClick = \"eliminarInstancia();\">Eliminar Instancia</div>");

    $p->addComponent(new TitleComponent($instancia["instance_token"], 3));

    $t = new TableComponent(array(
             "instance_id" => "Id",
             "instance_token" => "Token",
             "descripcion" => "Descripcion",
             "fecha_creacion" => "Creaci&oacute;n",
             "activa" => "Activa",
             "status" => "status"
        ), array($instancia));

    $t->addColRender( "fecha_creacion", "FormatTime" );
    $t->addColRender( "activa", "FormatBoolean" );
    $t->addColRender( "pos_instance", "FormatBoolean" );

    function FormatBoolean($activa)
    {
        if($activa === "0"){
            return "<font style = \"color:red;\">no</font>";
        }

        if($activa === "1"){
            return "s&iacute;";
        }

        return "undefined";
    }

    $p->addComponent($t);

    if (!empty($instancia["request"])) {
        $p->addComponent(new TitleComponent("Request", 3));

        $tt = new TableComponent(array(
                  "id_request" => "Id",
                  "email" => "Email",
                  "fecha" => "Fecha",
                  "ip" => "Ip",
                  "date_validated" => "Validado",
                  "date_installed" => "Instalado"
             ), array($instancia["request"]));

        $tt->addColRender("fecha", "FormatTime");
        $tt->addColRender("date_validated", "FormatTime");
        $tt->addColRender("date_installed", "FormatTime");

        $p->addComponent($tt);
    }

    $p->partialRender();

?>

    <script>
        var eliminarInstancia = function()
        {
            var answer = confirm("¿En verdad quiere eliminar la instancia? Tenga en cuenta que se eliminara la instalacion de la BD 'pos_instance'")
            if (answer){
                window.location = "instancias.ver.php?id=<?php echo $_GET['id'];?>&action=del";
            }
        }
    </script>

<?php

    $p->render();