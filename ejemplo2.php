<?php
require_once("ejemplo.php");

$conn=conectarBD();

$data=file_get_contents("php://input");
$request=json_decode($data);

$id_tipo_identificacion=$request->id_tipo_identificacion;
$numero_identificacion=$request->numero_identificacion;
$accion=$request->accion;

if($accion!='delete'){
  $nombres=$request->nombres;
  $apellidos=$request->apellidos;
  $edad=$request->edad;
  $fecha_nacimiento=$request->fecha_nacimiento;
  $sexo=$request->sexo;
  $direccion=$request->direccion;
  $telefono=$request->telefono;
}

if($accion=='insert'){

  $query1="SELECT COUNT(*) FROM tb_clientes WHERE numero_identificacion LIKE '$numero_identificacion'";
  $result1 = pg_query($query1) or die('La consulta fallo: ' . pg_last_error());
  $row = pg_fetch_row($result1, null);

  if($row[0]==0){

    $query="INSERT INTO tb_clientes(id_tipo_identificacion,numero_identificacion,nombres,apellidos,fecha_nacimiento,sexo,direccion,telefono)
            VALUES($id_tipo_identificacion,
                   '$numero_identificacion',
                   '$nombres',
                   '$apellidos',
                   DATE('$fecha_nacimiento'),
                   '$sexo',
                   '$direccion',
                   '$telefono'
            )";

    $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
    //Cuando inserta con exito
    echo "1";

  }

  else{
    //Cuando el registro ya existe
    echo "2";
  }
  // Liberando el conjunto de resultados
  pg_free_result($result);
}

else if($accion=='update'){

  $id_clientes=$request->id_clientes;

  $query1="SELECT COUNT(*) FROM tb_clientes WHERE numero_identificacion LIKE '$numero_identificacion' AND id_clientes<>$id_clientes";
  $result1 = pg_query($query1) or die('La consulta fallo: ' . pg_last_error());
  $row = pg_fetch_row($result1, null);

  if($row[0]==0){

    $query="UPDATE tb_clientes SET id_tipo_identificacion=$id_tipo_identificacion,numero_identificacion='$numero_identificacion',nombres='$nombres',apellidos='$apellidos',fecha_nacimiento='$fecha_nacimiento',sexo='$sexo',direccion='$direccion',telefono='$telefono' WHERE id_clientes=$id_clientes";
    $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
    //Cuando inserta con exito
    echo "3";

  }

  else{
    //Cuando el registro ya existe
    echo "4";
  }
  // Liberando el conjunto de resultados
  pg_free_result($result);

}

else if($accion=='delete'){

  $id_clientes=$request->id_clientes;

  $query1="DELETE FROM tb_clientes WHERE id_clientes=$id_clientes";
  $result1 = pg_query($query1) or die('La consulta fallo: ' . pg_last_error());

  echo "5";

}

// Liberando el conjunto de resultados
pg_free_result($result1);

// Cerrando la conexión
pg_close($conn);

?>
