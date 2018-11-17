<?php
require_once("ejemplo.php");

$conn=conectarBD();
$output=array();

$data=file_get_contents("php://input");
$request=json_decode($data);

if(isset($request->id_tipo_identificacion))
  $id_tipo_identificacion=$request->id_tipo_identificacion;
else
  $id_tipo_identificacion=0;
if(isset($request->numero_identificacion))
  $numero_identificacion=$request->numero_identificacion;
else
  $numero_identificacion='';
if(isset($request->nombres))
  $nombres=$request->nombres;
else
  $nombres='';
if(isset($request->apellidos))
  $apellidos=$request->apellidos;
else
  $apellidos='';

$query = "SELECT b.descripcion AS tipo_identificacion,a.numero_identificacion,a.nombres,a.apellidos,extract(year from age(CURRENT_DATE,a.fecha_nacimiento)) AS edad,a.sexo,a.direccion,a.telefono,a.fecha_nacimiento::DATE FROM tb_clientes a INNER JOIN tb_tipo_identificacion b ON a.id_tipo_identificacion=b.id_tipo_identificacion
          WHERE
            a.id_tipo_identificacion = CASE WHEN $id_tipo_identificacion=0 THEN a.id_tipo_identificacion ELSE $id_tipo_identificacion END AND
            a.numero_identificacion LIKE '%$numero_identificacion%' AND
            a.nombres LIKE '%$nombres%' AND
            a.apellidos LIKE '%$apellidos%'
         ";

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
