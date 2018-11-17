<?php
require_once("ejemplo.php");

$conn=conectarBD();
$output=array();

$query = 'SELECT * FROM tb_tipo_identificacion';
$result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

if(pg_num_rows($result)>0){
  while($row = pg_fetch_array($result, null)){
    $output[]=$row;
  }
  echo json_encode($output);
}

// Liberando el conjunto de resultados
pg_free_result($result);

// Cerrando la conexión
pg_close($conn);

?>
