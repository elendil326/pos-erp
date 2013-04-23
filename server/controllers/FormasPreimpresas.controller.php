<?php

require_once("interfaces/FormasPreimpresas.interface.php");

/**
 *
 *
 *
 * */
class FormasPreimpresasController extends ValidacionesController implements IFormasPreimpresas {

    /**
    *
    * Genera un documento en formato  PDF.
    *
    * @param id_documento int ID del documento que se desea imprimir.
    **/
    public static function GenerarPdf($id_documento) {
        
    }

      /**
       *
       * Esta funcionalidad permite crear un archivo de Excel 2007 a partir de un JSON, tambi?n usando plantillas predefinidas con palabras clave
       *
       * @param archivo_salida string La ruta y el nombre del archivo que se Va a crear con esta funcionalidad
       * @param datos json Es un arreglo asociativo que contiene los valores que se van a insertar dentro del archivo de salida. Si se usa plantilla las llaves apuntar�n a las palabras clave del archivo, si no se usa plantilla, las llaves del arreglo apuntaran a las coordenadas d�nde se desea insertar dichos valores. Si solo se envia un arreglo se toma como cabeceras, pero el arrelgo puede contener otro arreglo, el primero contiene las filas y los arreglos internos las columnas
       * @param archivo_plantilla string Si se establece, permite definir la ubicaci�n de la plantilla a usar
       * @param imagenes json Contiene un arreglo con las im�genes que se van a insertar en el archivo de salida
       * @return estado string Devuelve el estado de la funci�n, 0 para correcto; En caso de haber un error, devuelve la cadena con este.
       * */
      public static function GenerarExcel
      (
      $datos, $archivo_plantilla = "", $imagenes = ""
      ) {

            try {
                  /*$CarpetaSalida = POS_PATH_TO_SERVER_ROOT."/../static_content/" . IID . "/temp/";//Carpeta temporal para los archivos de salida
                  if (substr(decoct(fileperms($CarpetaSalida)), 2) != 775) {//Comprueba los permisos
                        chmod($CarpetaSalida, 0775); //Agrega los nuevos permisos
                  }*/
                  $ObjSalida = new PHPExcel(); //Crea el nuevo objeto de saĺida
                  if ($datos == null) {
                        Logger::error("No hay datos para trabajar"); //Termina la ejecución
                  } else {
                        $Datos = $datos; //Carga los datos con los que va a trabajar
                  }
                  if ($archivo_plantilla == null || $archivo_plantilla === "") {//Si no se especifica una plantilla
                        $itCol = 0;
                        $itFil = 0;
                        foreach ($Datos as $key => $value) {
                              if (substr($key, 0, 1) != "#" && substr($key, (strlen($key) - 1) != "#", 1)) {//Determina si NO es una palabra clave
                                    if (is_object($value) == true) {//Si el elemento que se obtiene es un objeto (incluye formato)
                                          $Pos = FormasPreimpresasController::SeparaColFil($key);
                                          foreach ($value as $nKey => $Filas) {
                                                if (is_array($Filas)) {//Si hay un arreglo con las columnas para esa fila
                                                      foreach ($Filas as $Columnas) {//Itera entre todas las columnas contenidas en el array
                                                            $ObjSalida->getActiveSheet()->getCellByColumnAndRow(($Pos->Col + $itCol), ($Pos->Fil + $itFil - 1))->setValue($Columnas);
                                                            $itCol++;
                                                      }
                                                      $itCol = 0;
                                                } else {//Si el primer arreglo contiene solo elementos de tipo string
                                                      echo "$nKey => $Filas INFO";
                                                      if ($nKey != "Formato") {//Si no se está iterando sobre la propiedad de formato
                                                            ECHO "FORMATO";
                                                      }
                                                }
                                                $itFil++;
                                          }
                                          unset($itCol);
                                          unset($itFil);
                                    } else { //Si el objeto que se obtiene es un array (solo textos)
                                          $ItCol = 0;
                                          $ItFil = 0;
                                          if (is_array($value) == true) {
                                                foreach ($value as $Valinter) {//itera entre todas las filas recibidas del arreglo
                                                      if (is_Array($Valinter)) {
                                                            foreach ($Valinter as $ValInterCol) {
                                                                  $Pos = (FormasPreimpresasController::SeparaColFil($key));
                                                                  $ObjSalida->getActiveSheet()->getCellByColumnAndRow(($Pos->Col + $ItCol), ($Pos->Fil + $ItFil))->setValue($ValInterCol);
                                                                  $ItCol++;
                                                            }
                                                            $ItCol = 0;
                                                      } else {
                                                            $Pos = (FormasPreimpresasController::SeparaColFil($key));
                                                            $ObjSalida->getActiveSheet()->getCellByColumnAndRow($Pos->Col + $ItCol, $Pos->Fil)->setValue($Valinter);
                                                            $ItCol++;
                                                      }
                                                      $ItFil++;
                                                }
                                          } else {
                                                $ObjSalida->getActiveSheet()->getCell($key)->setValue($value); //Establece el valor de la celda en base al arreglo obtenido
                                          }
                                          unset($ItCol);
                                          unset($ItFil);
                                    }
                              }
                        }
                        unset($itCol);
                        unset($itFil); //Libera las variable de la memoria
                  } else {//Si se especifica una plantilla
                        if (file_exists($archivo_plantilla) == 1) {//Comprueba si existe el archivo de plantilla indicado
                              $Extension = strrchr($archivo_plantilla, ".");
                              if ($Extension == ".xlsx" || $Extension == ".xls") {//Comprueba la extensión de la plantilla
                                    $objReader = new PHPExcel_Reader_Excel2007; //Crea el objeto lector
                                    $ObjetoPlantilla = $objReader->load($archivo_plantilla); //Carga el archivo al objeto plantilla
                                    $HojaPlantilla = $ObjetoPlantilla->getActiveSheet();

                                    $itCol = 0;
                                    $itFil = 0;
                                    foreach ($HojaPlantilla->getRowIterator() as $Fila) {//Iterador de Filas
                                          foreach ($Fila->getCellIterator() as $Celda) {//Iterador de Columnas
                                                if (array_key_exists($Celda->getValue(), $Datos)) {//Comprueba si la palabra clave existe dentro del arreglo de datos recibidos
                                                      if (is_object($Datos[$Celda->getValue()])) {//Si se recibe un arreglo de cadenas
                                                            foreach ($Datos[$Celda->getValue()] as $Key => $Valor) {//Itera entre todos los elementos del primer arreglo recibido (el de nivel superior)
                                                                  if (is_array($Valor)) {//Si el coontenido del elemento en el primer array es un array también, lo itera.
                                                                        foreach ($Valor as $Cols) {//Iteración entre el contenido de los arrays() del primer array(Filas) para revisar todas las columnas
                                                                              $HojaPlantilla->getCellByColumnAndRow((PHPExcel_Cell::columnIndexFromString($Celda->getColumn()) + $itCol - 1), ($Celda->getRow() + $itFil) - 1)->setValue($Cols);
                                                                              $itCol++;
                                                                        }
                                                                  }
                                                                  if ($Key != "Formato") {//Si solo se manda un arreglo con los datos de las primeras columnas
                                                                        
                                                                  }
                                                                  $itFil++;
                                                                  $itCol = 0;
                                                            }
                                                            unset($itCol);
                                                            unset($itFil);
                                                      } else {
                                                            if (is_array($Datos[$Celda->getValue()])) {//Si se recibe un arreglo, se consideran como cabeceras todos sus elementos hacia la derecha
                                                                  $Fini = $Celda->getRow();
                                                                  $Cini = PHPExcel_Cell::columnIndexFromString($Celda->getColumn());
                                                                  $Cini--;
                                                                  foreach ($Datos[$Celda->getValue()] as $Val) {
                                                                        if (is_array($Val)) {
                                                                              foreach ($Val as $CelCol) {
                                                                                    $HojaPlantilla->getCellByColumnAndRow(($Cini + $itCol), ($Fini + $itFil))->setValue($CelCol);
                                                                                    $itCol++;
                                                                              }
                                                                              $itFil++;
                                                                              $itCol = 0;
                                                                        } else {
                                                                              $HojaPlantilla->getCellByColumnAndRow($Cini, $Fini)->setValue($Val); //Pone una fila de datos
                                                                              $Cini++;
                                                                        }
                                                                  }
                                                                  unset($Fini);
                                                                  unset($Cini);
                                                                  unset($Val);
                                                            } else {
                                                                  $Celda->setValue($Datos[$Celda->getValue()]); //Cambia el valoren el archivo de plantilla de una sola celda
                                                            }
                                                      }
                                                }
                                          }
                                    }
                                    $ObjSalida->addExternalSheet($HojaPlantilla); //Carga la hoja de la plantilla al nuevo archivo
                                    $ObjSalida->removeSheetByIndex(0);
                              } else {
                                    Logger::error("La extensión de la plantilla no es compatible (" . $Extension . ") con esta función (.xlsx)");
                              }
                              unset($Extension); //Libera la variable
                        } else {
                              Logger::error("El archivo de plantilla indicado no existe.");
                              return;
                        }
                  }
                  if ($imagenes != NULL) {
                        foreach ($imagenes as $Img) {//Itera entre todas las imagenes recibidas
                              if ($Img->Nombre == "" || $Img->Ruta == "" || $Img->Celda == "") {
                                    Logger::error("No se pueden procesar las imagenes, faltan argumentos");
                              } else {
                                    if (file_exists($Img->Ruta) == 1) {//Comprueba que exista la imagen
                                          $Extension = strrchr($Img->Ruta, ".");
                                          if ($Extension == ".jpeg" || $Extension == ".jpg" || $Extension == ".gif" || $Extension == ".png" || $Extension == ".bmp") {//Extensiones admitidas
                                                $Imagen = new PHPExcel_Worksheet_Drawing();
                                                $Imagen->setName($Img->Nombre);
                                                $Imagen->setDescription($Img->Descripcion);
                                                $Imagen->setPath($Img->Ruta);
                                                $Imagen->setHeight($Img->Altura);
                                                $Imagen->setCoordinates($Img->Celda);
                                                $Imagen->setWorksheet($ObjSalida->getActiveSheet());
                                          } else {
                                                Logger::error("Extension de archivo de imagen invalida.");
                                          }
                                    } else {
                                          Logger::error("El archivo de imagen indicado no existe.");
                                    }
                                    unset($Extension); //Libera la variable
                              }
                        }
                  }
                  $objWriter = PHPExcel_IOFactory::createWriter($ObjSalida, 'Excel2007');
                  //$objWriter->save("/var/www/excel.xlsx"); //Crea el archivo de salida en la carpeta temporal
                  return $objWriter;//Regresa el objeto de escritura
            } catch (Exception $e) {
                  Logger::error("Ha ocurrido un error: $e");
            }
      }

      /**
       *
       * Esta funcionalidad permite leer las palabras clave de un archivo (Plantilla) y las devuelve en forma de arreglo
       *
       * @param archivo_plantilla string Indica el archivo que se va a leer
       * @return estado string Devuelve el estado de la ejecuci�n, 0 en caso de todo correcto
       * @return datos json Devuelve el arreglo asociativo de palabras clave encontradas con sus respectivas coordenadas
       * */

      private static function SeparaColFil($Coordenadas) {//EJ: H5
            $Ret = new stdClass();
            $Ret->Fil = "";
            $Ret->Col = "";
            $I = 0;

            while (is_numeric(substr($Coordenadas, $I, 1)) == false) {
                  $I++;
            }

            $Ret->Col = PHPExcel_Cell::columnIndexFromString(substr($Coordenadas, 0, $I)) - 1;
            $Ret->Fil = (int) substr($Coordenadas, $I);
            return $Ret;
      }
      
      public  static function LeerpalabrasclaveExcel      (
      $archivo_plantilla
      ) {
            $ArregloSalida = array();
            $ObjetoLector = new PHPExcel_Reader_Excel2007; //Crea el objeto lector
            $Libro = $ObjetoLector->load($archivo_plantilla); //Carga el archivo
            $Hoja = $Libro->getActiveSheet();

            foreach ($Hoja->getRowIterator() as $Fila) {
                  foreach ($Fila->getCellIterator() as $Columna) {
                        $Valor = $Columna->getValue(); //Carga el valor de las celdas
                        if (substr($Valor, 0, 1) == "#" && substr($Valor, (strlen($Valor) - 1) == "#", 1)) {//Determina si es una palabra clave
                              array_push($ArregloSalida, substr($Valor, 1,  (strlen($Valor)-2))); //Inserta el valor asociado a sus coordenadas en el arreglo de respuesta
                        }
                  }
            }
            return array("resultados" => $ArregloSalida); //Devuelve el arreglo procesado
      }
      public static function Generar2Excel($id_documento) {
            
            $DescDoc = DocumentoDAO::getDocumentWithValues($id_documento);//Descarga de documento
            $DescDoc=  array_reverse($DescDoc);
            $Valores=array();
            $i=0;
            //FALTA AGREGAR COMPATIBILIDAD PARA LEER LAS PALABRAS CLAVE DEL FORMATO DE DOCUMENTO, PARA ESO SE HA DE AGREGAR UN NUEVO CAMPO QUE LO ASOCIE CON 
            //LA PLANTILLA PARA, EN LUGAR DE OCUPAR LAS COORDENADAS Ax, QUE SE UTILICEN LAS PALABRAS CLAVE COMO REFERENCIA PARA PONER LOS VALORES
            foreach($DescDoc as $parms)
            {
                  $i++;
                  $Valores["A$i"]= array($parms["descripcion"]);
                  $i++;
                  $Valores["A$i"]= array($parms["val"]);
            }
            header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");//Cabeceras de salida
            header("Content-Disposition: attachment;filename=\"Excel.xlsx\"");
            header("Cache-Control: max-age=0");
            $Salida = FormasPreimpresasController::GenerarExcel($Valores);
            $Salida->save("php://output");
            
      }
}