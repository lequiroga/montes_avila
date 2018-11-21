<?php

  class ConectaBD{
    function retornarConexion(){
      $connect = @mysql_connect("localhost","root","") or die ("<center>No se puede conectar con la base de datos\n</center>\n"); 
      @mysql_select_db("bd_quoting", $connect);           
      return $connect;
    }
  }

?>