<?php

	define("BYPASS_INSTANCE_CHECK", true);

	require_once("../../server/bootstrap.php");

function OutputFile($file, $name, $mime_type='')
{

 /*
 This function takes a path to a file to output ($file), 
 the filename that the browser will see ($name) and 
 the MIME type of the file ($mime_type, optional).
 
 If you want to do something on download abort/finish,
 register_shutdown_function('function_name');
 */
 if(!is_readable($file)) die('File not found or inaccessible!');
 
 $size = filesize($file);
 $name = rawurldecode($name);
 
 /* Figure out the MIME type (if not specified) */
 $known_mime_types=array(
 	"pdf" => "application/pdf",
 	"txt" => "text/plain",
 	"log" => "text/html",
 	"htm" => "text/html",
	"exe" => "application/octet-stream",
	"zip" => "application/zip",
	"doc" => "application/msword",
	"xls" => "application/vnd.ms-excel",
	"ppt" => "application/vnd.ms-powerpoint",
	"gif" => "image/gif",
	"png" => "image/png",
	"jpeg"=> "image/jpg",
	"jpg" =>  "image/jpg",
	"php" => "text/plain"
 );
 
 if($mime_type==''){
	 $file_extension = strtolower(substr(strrchr($file,"."),1));
	 if(array_key_exists($file_extension, $known_mime_types)){
		$mime_type=$known_mime_types[$file_extension];
	 } else {
		$mime_type="application/force-download";
	 };
 };
 
 @ob_end_clean(); //turn off output buffering to decrease cpu usage
 
 // required for IE, otherwise Content-Disposition may be ignored
 if(ini_get('zlib.output_compression'))
  ini_set('zlib.output_compression', 'Off');
 
 header('Content-Type: ' . $mime_type);
 header('Content-Disposition: attachment; filename="'.$name.'"');
 header("Content-Transfer-Encoding: binary");
 header('Accept-Ranges: bytes');
 
 /* The three lines below basically make the 
    download non-cacheable */
 header("Cache-control: private");
 header('Pragma: private');
 header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
 
 // multipart-download and download resuming support
 if(isset($_SERVER['HTTP_RANGE']))
 {
	list($a, $range) = explode("=",$_SERVER['HTTP_RANGE'],2);
	list($range) = explode(",",$range,2);
	list($range, $range_end) = explode("-", $range);
	$range=intval($range);
	if(!$range_end) {
		$range_end=$size-1;
	} else {
		$range_end=intval($range_end);
	}
 
	$new_length = $range_end-$range+1;
	header("HTTP/1.1 206 Partial Content");
	header("Content-Length: $new_length");
	header("Content-Range: bytes $range-$range_end/$size");
 } else {
	$new_length=$size;
	header("Content-Length: ".$size);
 }
 
 /* output the file itself */
 $chunksize = 1*(1024*1024); //you may want to change this
 $bytes_send = 0;
 if ($file = fopen($file, 'r'))
 {
	if(isset($_SERVER['HTTP_RANGE']))
	fseek($file, $range);
 
	while(!feof($file) && 
		(!connection_aborted()) && 
		($bytes_send<$new_length)
	      )
	{
		$buffer = fread($file, $chunksize);
		print($buffer); //echo($buffer); // is also possible
		flush();
		$bytes_send += strlen($buffer);
	}
 fclose($file);
 } else die('Error - can not open file.');

die();
}






$page = new JediComponentPage( );

require_once("libs/zip.php");

/* 
Make sure script execution doesn't time out.
Set maximum execution time in seconds (0 means no limit).
*/

set_time_limit(0);

$ids = json_decode($_GET["instance_ids"]);
$ids = $ids->instance_ids;

$prefix = time() . rand();
$files = array();
$file_id = array();

for($i = 0; $i < sizeof($ids); $i++){

	//validar que existan
	$r = InstanciasController::BuscarPorId( $ids[$i] );

	if(is_null($r)){
		$page->addComponent("La instancia " . $ids[$i] . " no existe");
		$page->render();
		exit;
	}
}

$result = InstanciasController::Respaldar_Instancias($ids);//Respaldar_Instancias recibe como params un array
if (strlen($result) > 0){
	die("<html><head><meta HTTP-EQUIV='REFRESH' content='3; url=instancias.bd.php'><title>Error al descargar, perimisos</title></head><body><h1><center>".$result."</center></h1></body></html>");
}

$f = new zipfile;

for ($i=0; $i < sizeof($ids); $i++) { 
	//$f->add_file(file_get_contents($files[$i]), $file_id[$i] . ".sql");
	$final_path = str_replace("server","static_content/db_backups",POS_PATH_TO_SERVER_ROOT);
	$dbs_instance = trim(shell_exec("ls -lat -m1 ".$final_path."| grep ".$ids[$i].".sql"));
	Logger::log("Respaldos encontrados: ".$dbs_instance);

	/*dbs_instance almacena una cadena con un listado donde se encuentran archivos que tengan la teminacion
	con el id de la instancia y .sql, ademas de que la lista viene ordenada de mas reciente a antiguo
	la lista seria como lo siguiente:
	1353617611_pos_instance_82.sql 1353608687_pos_instance_82.sql 1353608206_pos_instance_82.sql 1353608191_pos_instance_82.sql
	en found se coloca un array y en cada posicion el nombre de cada respaldo
	*/
	$found = preg_split("/[\s,]+/", $dbs_instance,-1,PREG_SPLIT_NO_EMPTY);
	//Logger::log("No archivos: ".count($found));
	if(count($found) < 1){
		Logger::log("Error al restaurar la instancias ".$ids[$i].", no hay un respaldo existente");
		continue;
	}
	$contenido = file_get_contents($final_path."/".$found[0]);
	$f->add_file($contenido,$found[0]);
}

$folder_name = ( sizeof($ids) > 1 )? "instances_backup_".date("d-m-Y H:i:s") : "instance_".$ids[0]."_backup_".date("d-m-Y H:i:s");
Logger::log(":::::::ENVIANDO ARCHIVO A DESCARGAR");

header("Content-type: application/octet-stream");
header("Content-disposition: attachment; filename=$folder_name.zip");
echo $f->file();
