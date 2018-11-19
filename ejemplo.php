<?php

// Conectando y seleccionado la base de datos
function conectarBD(){
   $dbconn = pg_connect("host=localhost port=5432 dbname=clientes user=postgres password=postgres0417")
                        or die('No se ha podido conectar: ' . pg_last_error());
   return $dbconn;
}

?>
