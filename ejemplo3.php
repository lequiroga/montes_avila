<?php
require_once("ejemplo.php");

$conn=conectarBD();
$output=array();

$data=file_get_contents("php://input");
$request=json_decode($data);

$id_tipo_identificacion=$request->id_tipo_identificacion;
$numero_identificacion=$request->numero_identificacion;

$query = "SELECT a.*,a.fecha_nacimiento::DATE AS fecha_nac, extract(year from age(CURRENT_DATE,a.fecha_nacimiento)) AS edad FROM tb_clientes a WHERE a.id_tipo_identificacion=$id_tipo_identificacion AND a.numero_identificacion LIKE '$numero_identificacion'";
$result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

if(pg_num_rows($result)>0){
  while($row = pg_fetch_array($result, null)){
    $output["id_clientes"]=$row[0];
    $output["numero_identificacion"]=$row[1];
    $output["nombres"]=$row[2];
    $output["apellidos"]=$row[3];
    $output["id_tipo_identificacion"]=$row[4];
    $output["sexo"]=$row[6];
    $output["direccion"]=$row[7];
    $output["telefono"]=$row[8];
    $output["fecha_nac"]=$row[10];
    $output["edad"]=$row[11];
  }
  echo json_encode($output);
}

// Liberando el conjunto de resultados
pg_free_result($result);

// Cerrando la conexión
pg_close($conn);

?>
