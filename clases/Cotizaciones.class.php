<?php

  class Cotizaciones{

    /*
    function retornarCotizaciones($conexion,$numero_identificacion){

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

    }*/
    
    /*
    function retornarCotizacionesTodas($conexion){

      $consulta="select codigo_cotizacion,codigo_cliente,numero_cotizacion,observacion,vigencia,cliente from tb_clientes order by cliente asc";
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
    */

    
    function retornarCodigoCotizaciones($conexion,$codigo_cliente){

      $consulta="select codigo_cotizacion from tb_cotizaciones where codigo_cliente='".$codigo_cliente."' order by fecha_creacion desc limit 1;";
      //echo $consulta;exit;        
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());
      $totalResultados=mysql_num_rows($resultadoConsulta);

      if($totalResultados>=1){        

        $i=0; 
        
        while ($resultRegistro=mysql_fetch_assoc($resultadoConsulta)){

          $codigo_cotizacion=$resultRegistro["codigo_cotizacion"];
          $i++;          
          
        }           
        
      } 

      return $codigo_cotizacion;

    }


    function guardarCotizaciones($conexion,$codigo_cliente,$observacion,$vigencia){

      $consulta="insert into tb_cotizaciones (codigo_cliente,observacion,fecha_vigencia) values ('".$codigo_cliente."',UPPER('".$observacion."'),'".$vigencia."');";
      //echo $consulta;exit;        
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());      
     
      if(isset($_SESSION["codigo_cotizacion"]))
        unset($_SESSION["codigo_cotizacion"]);

      $cotizaciones=new Cotizaciones();
      $_SESSION["codigo_cotizacion"]=$cotizaciones->retornarCodigoCotizaciones($conexion,$codigo_cliente);
      
      if(isset($_SESSION["numero_cotizacion"]))
        unset($_SESSION["numero_cotizacion"]);

      $numero_cotizacion=date('Y').'-'.$_SESSION["codigo_cotizacion"];
      
      $consulta="update tb_cotizaciones set numero_cotizacion='".$numero_cotizacion."' where codigo_cotizacion='".$_SESSION["codigo_cotizacion"]."';";
      //echo $consulta;exit;
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());

    }
    
    
    function modificarCotizaciones($conexion,$codigo_cotizacion,$codigo_cliente,$observacion,$vigencia){

      $consulta="update tb_cotizaciones set codigo_cliente='".$codigo_cliente."', observacion=UPPER('$observacion'),fecha_vigencia='$vigencia',fecha_modificacion=CURRENT_TIMESTAMP where codigo_cotizacion='$codigo_cotizacion';";
      //echo $consulta;exit;
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());

      if(isset($_SESSION["codigo_cotizacion"]))
        unset($_SESSION["codigo_cotizacion"]);

      $_SESSION["codigo_cotizacion"]=$codigo_cotizacion;
      
      $consulta="delete from tb_cotizaciones_productos where codigo_cotizacion='$codigo_cotizacion';";
      //echo $consulta;exit;
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());
      
      $consulta1=
      "SELECT
        c.codigo_cotizacion_producto as codigo_cotizacion_producto,
        b.codigo_cotizacion as codigo_cotizacion,
        a.codigo_producto as codigo_producto,
        c.cantidad as cantidad,
        c.precio_original as precio_original,
        c.variacion as variacion,
        c.porcen_variacion as porcen_variacion,
        c.observacion
      from
        tb_productos a,
        tb_cotizaciones b,
        tb_cotizaciones_productos_temp c
      where
        a.codigo_producto=c.codigo_producto
        AND b.codigo_cotizacion=c.codigo_cotizacion
        AND b.codigo_cotizacion='$codigo_cotizacion'
        AND c.cantidad not like '0'
      order by
        a.codigo_producto asc";
        
      $consulta="insert into tb_cotizaciones_productos (codigo_cotizacion_producto,codigo_cotizacion,codigo_producto,cantidad,precio_original,variacion,porcen_variacion,observacion) (".$consulta1.")";
      //echo $consulta;exit;
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());

    }


    function retornarCotizaciones1($conexion,$inicio,$numRegistros){
      
      if(!isset($_SESSION))
        session_start(); 
    
      $consulta="SELECT a.codigo_cotizacion,a.numero_cotizacion,b.cliente,a.fecha_creacion,a.fecha_vigencia,
                 ( SELECT
                    aa.cantidad*
                    CASE WHEN aa.variacion='AUMENTO' THEN   (((aa.porcen_variacion/100)+1)*aa.precio_original)
                         WHEN aa.variacion='DESCUENTO' THEN ((1-(aa.porcen_variacion/100))*aa.precio_original)
                         ELSE aa.precio_original
                    END
                  FROM
                    tb_cotizaciones_productos aa
                  WHERE
                    aa.codigo_cotizacion=a.codigo_cotizacion
                 ) from tb_cotizaciones a order by a.fecha_creacion desc LIMIT ".$inicio.",".$numRegistros;
      //echo $consulta;exit;
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());
      $totalResultados=mysql_num_rows($resultadoConsulta);
      $_SESSION["cantRegCotizacionBusqTodosLim"]=$totalResultados;

      $consulta1="SELECT a.codigo_cotizacion as codigo_cotizacion,a.numero_cotizacion as numero_cotizacion,b.cliente as cliente,a.fecha_creacion as fecha_creacion,a.fecha_vigencia as fecha_vigencia,
                 ( SELECT
                    aa.cantidad*
                    CASE WHEN aa.variacion='AUMENTO' THEN   (((aa.porcen_variacion/100)+1)*aa.precio_original)
                         WHEN aa.variacion='DESCUENTO' THEN ((1-(aa.porcen_variacion/100))*aa.precio_original)
                         ELSE aa.precio_original
                    END
                  FROM
                    tb_cotizaciones_productos aa
                  WHERE
                    aa.codigo_cotizacion=a.codigo_cotizacion
                 ) as total from tb_cotizaciones a, tb_clientes b WHERE a.codigo_cliente=b.codigo_cliente order by a.fecha_creacion desc";
      $resultadoConsulta1=mysql_query($consulta1,$conexion) or die(mysql_error());
      $totalResultados1=mysql_num_rows($resultadoConsulta1);
      $_SESSION["cantRegCotizacionBusqTodos"]=$totalResultados1;
     
      if($totalResultados>=1){        

        if(isset($_SESSION["listaCotizaciones"]))
          unset($_SESSION["listaCotizaciones"]);

        $i=0; 
        
        while ($resultRegistro = mysql_fetch_assoc($resultadoConsulta)){

          $_SESSION["listaCotizaciones"][$i]["codigo_cotizacion"]=$resultRegistro["codigo_cotizacion"];
          $_SESSION["listaCotizaciones"][$i]["numero_cotizacion"]=$resultRegistro["numero_cotizacion"];
          $_SESSION["listaCotizaciones"][$i]["cliente"]=$resultRegistro["cliente"];
          $_SESSION["listaCotizaciones"][$i]["fecha_creacion"]=$resultRegistro["fecha_creacion"];
          $_SESSION["listaCotizaciones"][$i]["vigencia"]=$resultRegistro["fecha_vigencia"];
          $_SESSION["listaCotizaciones"][$i]["total"]=$resultRegistro["total"];
          $i=$i+1;          
          
        }           
        
      } 

    }

    function retornarCotizacionesPorParamLim($conexion,$consulta,$inicio,$numRegistros){
      if(!isset($_SESSION))
        session_start();       
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());
      $totalResultados=mysql_num_rows($resultadoConsulta);
      $_SESSION["cantRegCotizacionBusqTodos"]=$totalResultados;

      $consulta1=$consulta." LIMIT ".$inicio.",".$numRegistros;
      //echo $consulta1;exit;
      $resultadoConsulta1=mysql_query($consulta1,$conexion) or die(mysql_error());
      $totalResultados1=mysql_num_rows($resultadoConsulta1); 
      $_SESSION["cantRegCotizacionBusqTodosLim"]=$totalResultados1;
     
      if($totalResultados1>=1){        

        if(isset($_SESSION["listaCotizaciones"]))
          unset($_SESSION["listaCotizaciones"]);
        
        $i=0;

        while ($resultRegistro = mysql_fetch_assoc($resultadoConsulta1)){

          $_SESSION["listaCotizaciones"][$i]["codigo_cotizacion"]=$resultRegistro["codigo_cotizacion"];
          $_SESSION["listaCotizaciones"][$i]["numero_cotizacion"]=$resultRegistro["numero_cotizacion"];
          $_SESSION["listaCotizaciones"][$i]["cliente"]=$resultRegistro["cliente"];
          $_SESSION["listaCotizaciones"][$i]["fecha_creacion"]=$resultRegistro["fecha_creacion"];
          $_SESSION["listaCotizaciones"][$i]["vigencia"]=$resultRegistro["fecha_vigencia"];
          $_SESSION["listaCotizaciones"][$i]["total"]=$resultRegistro["total"];
          $i=$i+1;                           
          
        }           
        
      } 

    }    

    function retornarCotizacionesPorCodigo($conexion,$codigo_cotizacion){
      
      if(!isset($_SESSION))
        session_start(); 

      $consulta="SELECT a.numero_cotizacion as numero_cotizacion,b.codigo_cliente as codigo_cliente,b.cliente as cliente,b.tipo_identificacion as tipo_identificacion,b.numero_identificacion as numero_identificacion,b.telefono as telefono,b.direccion as direccion,b.correo_electronico as correo_electronico,a.observacion as observacion,a.fecha_vigencia as fecha_vigencia,a.fecha_creacion as fecha_creacion from tb_cotizaciones a, tb_clientes b where a.codigo_cliente=b.codigo_cliente AND a.codigo_cotizacion='".$codigo_cotizacion."';";
      //echo $consulta;exit;
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());
      $totalResultados=mysql_num_rows($resultadoConsulta);
          
      if($totalResultados>=1){
      
        $_SESSION['cant_cotizaciones']=$totalResultados;
        
        $i=0; 
        
        while ($resultRegistro = mysql_fetch_assoc($resultadoConsulta)){

          $_SESSION["numero_cotizacion"]=$resultRegistro["numero_cotizacion"];
          $_SESSION["codigo_cliente"]=$resultRegistro["codigo_cliente"];
          $_SESSION["cliente"]=$resultRegistro["cliente"];
          $_SESSION["tipo_identificacion"]=$resultRegistro["tipo_identificacion"];
          $_SESSION["numero_identificacion"]=$resultRegistro["numero_identificacion"];
          $_SESSION["telefono"]=$resultRegistro["telefono"];
          $_SESSION["direccion"]=$resultRegistro["direccion"];
          $_SESSION["correo_electronico"]=$resultRegistro["correo_electronico"];
          $_SESSION["observacion"]=$resultRegistro["observacion"];
          $_SESSION["fecha_creacion"]=$resultRegistro["fecha_creacion"];
          $_SESSION["vigencia"]=$resultRegistro["fecha_vigencia"];

          $i=$i+1;          
          
        }           
        
      } 
 
    }
    
    
    function eliminarProductosTempCotizacion($conexion,$codigo_cotizacion){

      $consulta="delete from tb_cotizaciones_productos_temp where codigo_cotizacion=$codigo_cotizacion;";
      //echo $consulta;exit;
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());
    }


  }

?>
