<?php

@session_start();

include("variables.php");


//Funcion para hacer conexion a la base de datos
function queryli($sqlstr = "", $HacerRow = 0, $AJAX = 0, $link = "")
{
  global $linki, $Filas, $TotalSql;

  $host = '127.0.0.1';
  $nombre_de_base = 'academisoft';
  $usuario = 'root';
  $contrasena = '';

  // $link = mysqli_connect($host, $usuario, $contrasena,'extramuros',8111);
  $link = mysqli_connect($host, $usuario, $contrasena, $nombre_de_base);

  mysqli_select_db($link, $nombre_de_base);

  $resultado = "";
  $row = "";
  $Tipo = explode(" ", trim($sqlstr));
  $TipoSql = strtoupper($Tipo[0]);
  if ($sqlstr != "") {
    $result = mysqli_query($link, $sqlstr);
    $GLOBALS['insert_id'] = mysqli_insert_id($link);
    $GLOBALS['linki'] = $link;
    /*SE VALIDA SI HAY UN ERROR EN LA CONSULTA*/

    if ($TipoSql == "SELECT") {
      $Filas = mysqli_num_rows($result);
      /*SE VALIDA SI SE RETORNA EL FECT_ARRAY*/
      if ($HacerRow == 1) {
        $row = mysqli_fetch_array($result);
      }
      /*SE VALIDA SI SE RETORNA UN ARREGLO*/ else if ($HacerRow == 2) {
        $row = array();
        while ($rowR = mysqli_fetch_assoc($result)) {
          $row[] = $rowR;
        }
        mysqli_free_result($result);
      }
    }
  }

  if ($row == "") {
    $row = $result;
  }

  // mysqli_close();
  return ($row);
}
