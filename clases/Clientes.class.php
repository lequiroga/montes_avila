<?php

  class Clientes{


    function retornarClientes($conexion,$numero_identificacion){

      $consulta="select count(*) as cant_clientes from tb_clientes where numero_identificacion='".$numero_identificacion."';"; 
      //echo $consulta;exit;        
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());
      $totalResultados=mysql_num_rows($resultadoConsulta);
     
      if($totalResultados>=1){        
  
        if(isset($_SESSION["cant_clientes_exist"]))
          unset($_SESSION["cant_clientes_exist"]);

        $i=0; 
        
        while ($resultRegistro=mysql_fetch_assoc($resultadoConsulta)){

          $_SESSION["cant_clientes_exist"]=$resultRegistro["cant_clientes"];                    
          $i++;          
          
        }           
        
      } 

    }
    
    
    function retornarClientesTodos($conexion){

      $consulta="select codigo_cliente,cliente from tb_clientes order by cliente asc";
      //echo $consulta;exit;
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());
      $totalResultados=mysql_num_rows($resultadoConsulta);

      if($totalResultados>=1){

        if(isset($_SESSION["listaClientes"]))
          unset($_SESSION["listaClientes"]);

        $i=0;

        while ($resultRegistro = mysql_fetch_assoc($resultadoConsulta)){

          $_SESSION["listaClientes"][$i]["codigo_cliente"]=$resultRegistro["codigo_cliente"];
          $_SESSION["listaClientes"][$i]["cliente"]=$resultRegistro["cliente"];
          $i=$i+1;

        }

      }

    }


    
    function retornarCodigoClientes($conexion,$numero_identificacion){

      $consulta="select codigo_cliente as codigo_cliente from tb_clientes where numero_identificacion='".$numero_identificacion."';"; 
      //echo $consulta;exit;        
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());
      $totalResultados=mysql_num_rows($resultadoConsulta);
      $codigo_cliente=0;

      if($totalResultados>=1){        

        $i=0; 
        
        while ($resultRegistro=mysql_fetch_assoc($resultadoConsulta)){

          $codigo_cliente=$resultRegistro["codigo_cliente"];                    
          $i++;          
          
        }           
        
      } 

      return $codigo_cliente;

    }


    function guardarClientes($conexion,$tipo_identificacion,$numero_identificacion,$cliente,$telefono,$direccion,$correo_electronico){

      $consulta="insert into tb_clientes (tipo_identificacion,numero_identificacion,cliente,telefono,direccion,correo_electronico) values ('$tipo_identificacion','$numero_identificacion',UPPER('$cliente'),'$telefono',UPPER('$direccion'),UPPER('$correo_electronico'));"; 
      //echo $consulta;exit;        
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());      
     
      if(isset($_SESSION["codigo_cliente_ins"]))
        unset($_SESSION["codigo_cliente_ins"]);

      $clientes=new Clientes(); 
      $_SESSION["codigo_cliente_ins"]=$clientes->retornarCodigoClientes($conexion,$numero_identificacion);

    }
    
    
    function modificarClientes($conexion,$codigo_cliente,$tipo_identificacion,$numero_identificacion,$cliente,$telefono,$direccion,$correo_electronico){

      $consulta="update tb_clientes set tipo_identificacion=UPPER('$tipo_identificacion'),numero_identificacion='$numero_identificacion',cliente=UPPER('$cliente'),telefono='$telefono',direccion=UPPER('$direccion'),correo_electronico=UPPER('$correo_electronico') where codigo_cliente='$codigo_cliente';";
      //echo $consulta;exit;
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());

      if(isset($_SESSION["codigo_cliente_ins"]))
        unset($_SESSION["codigo_cliente_ins"]);

      $clientes=new Clientes();
      $_SESSION["codigo_cliente_ins"]=$clientes->retornarCodigoClientes($conexion,$numero_identificacion);

    }


    function retornarClientes1($conexion,$inicio,$numRegistros){
      
      if(!isset($_SESSION))
        session_start(); 
    
      $consulta="SELECT codigo_cliente,tipo_identificacion,numero_identificacion,cliente from tb_clientes order by cliente desc LIMIT ".$inicio.",".$numRegistros;
      //echo $consulta;exit;       
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());
      $totalResultados=mysql_num_rows($resultadoConsulta);
      $_SESSION["cantRegClienteBusqTodosLim"]=$totalResultados;

      $consulta1="SELECT codigo_cliente,tipo_identificacion,numero_identificacion,cliente from tb_clientes order by cliente desc";
      $resultadoConsulta1=mysql_query($consulta1,$conexion) or die(mysql_error());
      $totalResultados1=mysql_num_rows($resultadoConsulta1);
      $_SESSION["cantRegClienteBusqTodos"]=$totalResultados1;
     
      if($totalResultados>=1){        

        if(isset($_SESSION["listaClientes"]))
          unset($_SESSION["listaClientes"]);

        $i=0; 
        
        while ($resultRegistro = mysql_fetch_assoc($resultadoConsulta)){

          $_SESSION["listaClientes"][$i]["codigo_cliente"]=$resultRegistro["codigo_cliente"];
          $_SESSION["listaClientes"][$i]["tipo_identificacion"]=$resultRegistro["tipo_identificacion"];
          $_SESSION["listaClientes"][$i]["numero_identificacion"]=$resultRegistro["numero_identificacion"];
          $_SESSION["listaClientes"][$i]["cliente"]=$resultRegistro["cliente"];
          $i=$i+1;          
          
        }           
        
      } 

    }

    function retornarClientesPorParamLim($conexion,$consulta,$inicio,$numRegistros){
      if(!isset($_SESSION))
        session_start();       
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());
      $totalResultados=mysql_num_rows($resultadoConsulta);
      $_SESSION["cantRegClienteBusqTodos"]=$totalResultados;

      $consulta1=$consulta." LIMIT ".$inicio.",".$numRegistros;
      //echo $consulta1;exit;
      $resultadoConsulta1=mysql_query($consulta1,$conexion) or die(mysql_error());
      $totalResultados1=mysql_num_rows($resultadoConsulta1); 
      $_SESSION["cantRegClienteBusqTodosLim"]=$totalResultados1;
     
      if($totalResultados1>=1){        

        if(isset($_SESSION["listaClientes"]))
          unset($_SESSION["listaClientes"]);
        
        $i=0;

        while ($resultRegistro = mysql_fetch_assoc($resultadoConsulta1)){

          $_SESSION["listaClientes"][$i]["codigo_cliente"]=$resultRegistro["codigo_cliente"];
          $_SESSION["listaClientes"][$i]["tipo_identificacion"]=$resultRegistro["tipo_identificacion"];
          $_SESSION["listaClientes"][$i]["numero_identificacion"]=$resultRegistro["numero_identificacion"];
          $_SESSION["listaClientes"][$i]["cliente"]=$resultRegistro["cliente"];
          $i=$i+1;                           
          
        }           
        
      } 

    }    

    function retornarClientesPorCodigo($conexion,$codigo_cliente){
      
      if(!isset($_SESSION))
        session_start(); 

      $consulta="SELECT tipo_identificacion,numero_identificacion,cliente,telefono,direccion,correo_electronico from tb_clientes where codigo_cliente='".$codigo_cliente."';";
      //echo $consulta;exit;
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());
      $totalResultados=mysql_num_rows($resultadoConsulta);
          
      if($totalResultados>=1){
      
        $_SESSION['cant_clientes_exist']=$totalResultados;
        
        $i=0; 
        
        while ($resultRegistro = mysql_fetch_assoc($resultadoConsulta)){

          $_SESSION["tipo_identificacion"]=$resultRegistro["tipo_identificacion"];
          $_SESSION["numero_identificacion"]=$resultRegistro["numero_identificacion"];
          $_SESSION["cliente"]=$resultRegistro["cliente"];
          $_SESSION["telefono"]=$resultRegistro["telefono"];
          $_SESSION["direccion"]=$resultRegistro["direccion"];
          $_SESSION["correo_electronico"]=$resultRegistro["correo_electronico"];

          $i=$i+1;          
          
        }           
        
      } 
 
    } 


  }

?>
