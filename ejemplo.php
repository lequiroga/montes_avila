<?php

// Conectando y seleccionado la base de datos
function conectarBD(){
   $dbconn = pg_connect("host=ec2-18-234-252-5.compute-1.amazonaws.com port=5432 dbname=clientes user=postgres password=postgres0417")
                        or die('No se ha podido conectar: ' . pg_last_error());
   return $dbconn;
}

?>
