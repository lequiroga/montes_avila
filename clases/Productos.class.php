<?php

  class Productos{


    function retornarProductos($conexion,$descripcion_producto){

      $consulta="select count(*) as cant_productos from tb_productos where descripcion_producto LIKE '".$descripcion_producto."';";
      //echo $consulta;exit;        
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());
      $totalResultados=mysql_num_rows($resultadoConsulta);
     
      if($totalResultados>=1){        
  
        if(isset($_SESSION["cant_productos_exist"]))
          unset($_SESSION["cant_productos_exist"]);

        $i=0; 
        
        while ($resultRegistro=mysql_fetch_assoc($resultadoConsulta)){

          $_SESSION["cant_productos_exist"]=$resultRegistro["cant_productos"];
          $i++;          
          
        }           
        
      } 

    }


    
    function retornarCodigoProductos($conexion,$descripcion_producto){

      $consulta="select codigo_producto as codigo_producto from tb_productos where descripcion_producto=UPPER('".$descripcion_producto."');";
      //echo $consulta;exit;        
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());
      $totalResultados=mysql_num_rows($resultadoConsulta);
      $codigo_producto=0;

      if($totalResultados>=1){        

        $i=0; 
        
        while ($resultRegistro=mysql_fetch_assoc($resultadoConsulta)){

          $codigo_producto=$resultRegistro["codigo_producto"];
          $i++;          
          
        }           
        
      } 

      return $codigo_producto;

    }


    function guardarProductos($conexion,$descripcion_producto,$ruta_imagen,$precio){

      $consulta="insert into tb_productos (descripcion_producto,ruta_imagen) values (UPPER('$descripcion_producto'),'$ruta_imagen');";
      //echo $consulta;exit;        
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());      
     
      if(isset($_SESSION["codigo_producto_ins"]))
        unset($_SESSION["codigo_producto_ins"]);

      $productos=new Productos();
      $_SESSION["codigo_producto_ins"]=$productos->retornarCodigoProductos($conexion,$descripcion_producto);

      $consulta="insert into tb_productos_precios (codigo_producto,precio) values ('".$_SESSION["codigo_producto_ins"]."','$precio');";
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());
    }
    
    
    function modificarProductos($conexion,$codigo_producto,$descripcion_producto,$ruta_imagen,$precio){
      if($ruta_imagen!='' && $ruta_imagen!=' ')
        $consulta="update tb_productos set descripcion_producto=UPPER('$descripcion_producto'),ruta_imagen='$ruta_imagen' where codigo_producto='$codigo_producto';";
      else
        $consulta="update tb_productos set descripcion_producto=UPPER('$descripcion_producto') where codigo_producto='$codigo_producto';";
        
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());

      if(isset($_SESSION["codigo_producto_ins"]))
        unset($_SESSION["codigo_producto_ins"]);
        
      $productos=new Productos();
      $_SESSION["codigo_producto_ins"]=$productos->retornarCodigoProductos($conexion,$descripcion_producto);
        
      $consulta="update tb_productos_precios set fecha_final=CURRENT_TIMESTAMP where fecha_final='0000-00-00 00:00:00' and codigo_producto='$codigo_producto';";
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());

      $consulta="insert into tb_productos_precios (codigo_producto,precio) values (".$_SESSION['codigo_producto_ins'].",'$precio');";
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());

    }


    function retornarProductos1($conexion,$inicio,$numRegistros){
      
      if(!isset($_SESSION))
        session_start(); 
    
      $consulta="SELECT a.codigo_producto as codigo_producto,a.descripcion_producto as descripcion_producto,a.ruta_imagen as ruta_imagen,(select b.precio from tb_productos_precios b where b.codigo_producto=a.codigo_producto order by b.fecha_inicial desc limit 1) as precio from tb_productos a order by a.descripcion_producto asc LIMIT ".$inicio.",".$numRegistros;
      //echo $consulta;exit;       
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());
      $totalResultados=mysql_num_rows($resultadoConsulta);
      $_SESSION["cantRegProductoBusqTodosLim"]=$totalResultados;

      $consulta1="SELECT a.codigo_producto as codigo_producto,a.descripcion_producto as descripcion_producto,a.ruta_imagen as ruta_imagen,(select b.precio from tb_productos_precios b where b.codigo_producto=a.codigo_producto order by b.fecha_inicial desc limit 1) as precio from tb_productos a order by a.descripcion_producto asc";
      $resultadoConsulta1=mysql_query($consulta1,$conexion) or die(mysql_error());
      $totalResultados1=mysql_num_rows($resultadoConsulta1);
      $_SESSION["cantRegProductoBusqTodos"]=$totalResultados1;
     
      if($totalResultados>=1){        

        if(isset($_SESSION["listaProductos"]))
          unset($_SESSION["listaProductos"]);

        $i=0; 
        
        while ($resultRegistro = mysql_fetch_assoc($resultadoConsulta)){

          $_SESSION["listaProductos"][$i]["codigo_producto"]=$resultRegistro["codigo_producto"];
          $_SESSION["listaProductos"][$i]["descripcion_producto"]=$resultRegistro["descripcion_producto"];
          $_SESSION["listaProductos"][$i]["ruta_imagen"]=$resultRegistro["ruta_imagen"];
          $_SESSION["listaProductos"][$i]["precio"]=$resultRegistro["precio"];
          $i=$i+1;          
          
        }           
        
      } 

    }
    
    
    function retornarProductosCotizacion($conexion,$codigo_cotizacion,$inicio,$numRegistros){

      if(!isset($_SESSION))
        session_start();

      $consulta1=
      "SELECT
        c.codigo_cotizacion_producto as codigo_cotizacion_producto,
        b.codigo_cotizacion as codigo_cotizacion,
        a.codigo_producto as codigo_producto,
        c.cantidad as cantidad,
        c.precio_original as precio_original,
        c.variacion as variacion,
        c.porcen_variacion as porcen_variacion
      from
        tb_productos a,
        tb_cotizaciones b,
        tb_cotizaciones_productos c
      where
        a.codigo_producto=c.codigo_producto
        AND b.codigo_cotizacion=c.codigo_cotizacion
        AND b.codigo_cotizacion='$codigo_cotizacion'
      order by
        a.codigo_producto asc";

      $consulta="insert into tb_cotizaciones_productos_temp (codigo_cotizacion_producto,codigo_cotizacion,codigo_producto,cantidad,precio_original,variacion,porcen_variacion) (".$consulta1.")";
      //echo $consulta;exit;
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());

      $consulta="SELECT codigo_cotizacion_producto as codigo_cotizacion_producto,codigo_cotizacion as codigo_cotizacion,codigo_producto as codigo_producto,cantidad as cantidad,precio_original as precio_original,variacion as variacion,porcen_variacion as porcen_variacion from tb_cotizaciones_productos_temp where codigo_cotizacion='".$codigo_cotizacion."' LIMIT ".$inicio.",".$numRegistros;
      //echo $consulta;exit;
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());
      $totalResultados=mysql_num_rows($resultadoConsulta);
      $_SESSION["cantRegProductoCotizacionBusqTodosLim"]=$totalResultados;

      $consulta1=
      "SELECT
        c.codigo_cotizacion_producto as codigo_cotizacion_producto,
        a.descripcion_producto as descripcion_producto,
        a.ruta_imagen as ruta_imagen,
        c.cantidad as cantidad,
        c.precio_original as precio_original,
        c.variacion as variacion,
        c.porcen_variacion as porcen_variacion
      from
        tb_productos a,
        tb_cotizaciones b,
        tb_cotizaciones_productos_temp c
      where
        a.codigo_producto=c.codigo_producto
        AND b.codigo_cotizacion=c.codigo_cotizacion
        AND b.codigo_cotizacion='$codigo_cotizacion'
      order by
        a.codigo_producto asc";
      $resultadoConsulta1=mysql_query($consulta1,$conexion) or die(mysql_error());
      $totalResultados1=mysql_num_rows($resultadoConsulta1);
      $_SESSION["cantRegProductoCotizacionBusqTodos"]=$totalResultados1;

      if($totalResultados>=1){

        if(isset($_SESSION["listaProductosCotizacion"]))
          unset($_SESSION["listaProductosCotizacion"]);

        $i=0;

        while ($resultRegistro = mysql_fetch_assoc($resultadoConsulta)){

          $_SESSION["listaProductosCotizacion"][$i]["codigo_cotizacion_producto"]=$resultRegistro["codigo_cotizacion_producto"];
          $_SESSION["listaProductosCotizacion"][$i]["descripcion_producto"]=$resultRegistro["descripcion_producto"];
          $_SESSION["listaProductosCotizacion"][$i]["ruta_imagen"]=$resultRegistro["ruta_imagen"];
          $_SESSION["listaProductosCotizacion"][$i]["precio_original"]=$resultRegistro["precio_original"];
          $_SESSION["listaProductosCotizacion"][$i]["cantidad"]=$resultRegistro["precio"];
          $_SESSION["listaProductosCotizacion"][$i]["variacion"]=$resultRegistro["variacion"];
          $_SESSION["listaProductosCotizacion"][$i]["porcen_variacion"]=$resultRegistro["porcen_variacion"];
          $i=$i+1;

        }

      }

    }
    

    function retornarProductosPorParamLim($conexion,$consulta,$inicio,$numRegistros){
      if(!isset($_SESSION))
        session_start();       
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());
      $totalResultados=mysql_num_rows($resultadoConsulta);
      $_SESSION["cantRegProductoBusqTodos"]=$totalResultados;

      $consulta1=$consulta." LIMIT ".$inicio.",".$numRegistros;
      //echo $consulta1;exit;
      $resultadoConsulta1=mysql_query($consulta1,$conexion) or die(mysql_error());
      $totalResultados1=mysql_num_rows($resultadoConsulta1); 
      $_SESSION["cantRegProductoBusqTodosLim"]=$totalResultados1;
     
      if($totalResultados1>=1){        

        if(isset($_SESSION["listaProductos"]))
          unset($_SESSION["listaProductos"]);
        
        $i=0;

        while ($resultRegistro = mysql_fetch_assoc($resultadoConsulta1)){

          $_SESSION["listaProductos"][$i]["codigo_producto"]=$resultRegistro["codigo_producto"];
          $_SESSION["listaProductos"][$i]["descripcion_producto"]=$resultRegistro["descripcion_producto"];
          $_SESSION["listaProductos"][$i]["ruta_imagen"]=$resultRegistro["ruta_imagen"];
          $_SESSION["listaProductos"][$i]["precio"]=$resultRegistro["precio"];
          $i=$i+1;                           
          
        }           
        
      } 

    }    
    
    
    function retornarProductosCotizacionPorParamLim($conexion,$consulta,$codigo_cotizacion,$inicio,$numRegistros){

      $consulta4="delete from tb_cotizaciones_productos_temp where codigo_cotizacion=$codigo_cotizacion;";
      //echo $consulta4;exit;
      $resultadoConsulta4=mysql_query($consulta4,$conexion) or die(mysql_error());

    $consulta1=
      "SELECT
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
        tb_cotizaciones_productos c
      where
        a.codigo_producto=c.codigo_producto
        AND b.codigo_cotizacion=c.codigo_cotizacion
        AND b.codigo_cotizacion=$codigo_cotizacion
      order by
        a.codigo_producto asc";

      $consulta2="insert into tb_cotizaciones_productos_temp (codigo_cotizacion,codigo_producto,cantidad,precio_original,variacion,porcen_variacion,observacion) (".$consulta1.")";
      //echo $consulta2;exit;
      $resultadoConsulta=mysql_query($consulta2,$conexion) or die(mysql_error());

      if(!isset($_SESSION))
        session_start();
        
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());
      $totalResultados=mysql_num_rows($resultadoConsulta);
      $_SESSION["cantRegProductoCotizacionBusqTodos"]=$totalResultados;
      $consulta5=$consulta." LIMIT ".$inicio.",".$numRegistros;
      //echo $consulta5;exit;
      $resultadoConsulta1=mysql_query($consulta5,$conexion) or die(mysql_error());
      $totalResultados1=mysql_num_rows($resultadoConsulta1);
      $_SESSION["cantRegProductoCotizacionBusqTodosLim"]=$totalResultados1;

      if($totalResultados1>=1){

        if(isset($_SESSION["listaProductosCotizacion"]))
          unset($_SESSION["listaProductosCotizacion"]);

        $i=0;

        while ($resultRegistro = mysql_fetch_assoc($resultadoConsulta1)){

          $_SESSION["listaProductosCotizacion"][$i]["codigo_cotizacion_producto"]=$resultRegistro["codigo_cotizacion_producto"];
          $_SESSION["listaProductosCotizacion"][$i]["descripcion_producto"]=$resultRegistro["descripcion_producto"];
          $_SESSION["listaProductosCotizacion"][$i]["ruta_imagen"]=$resultRegistro["ruta_imagen"];
          $_SESSION["listaProductosCotizacion"][$i]["precio_original"]=$resultRegistro["precio_original"];
          $_SESSION["listaProductosCotizacion"][$i]["cantidad"]=$resultRegistro["cantidad"];
          $_SESSION["listaProductosCotizacion"][$i]["variacion"]=$resultRegistro["variacion"];
          $_SESSION["listaProductosCotizacion"][$i]["porcen_variacion"]=$resultRegistro["porcen_variacion"];
          if($resultRegistro["variacion"]=='AUMENTO'){
            $_SESSION["listaProductosCotizacion"][$i]["valor_unit"]=((1+($resultRegistro["porcen_variacion"]/100))*$resultRegistro["precio_original"]);
            $_SESSION["listaProductosCotizacion"][$i]["valor_total"]=((1+($resultRegistro["porcen_variacion"]/100))*$resultRegistro["precio_original"])*$resultRegistro["cantidad"];
          }
          else if($resultRegistro["variacion"]=='DESCUENTO'){
            $_SESSION["listaProductosCotizacion"][$i]["valor_unit"]=((1-($resultRegistro["porcen_variacion"]/100))*$resultRegistro["precio_original"]);
            $_SESSION["listaProductosCotizacion"][$i]["valor_total"]=((1-($resultRegistro["porcen_variacion"]/100))*$resultRegistro["precio_original"])*$resultRegistro["cantidad"];
          }
          else{
            $_SESSION["listaProductosCotizacion"][$i]["valor_unit"]=$resultRegistro["precio_original"];
            $_SESSION["listaProductosCotizacion"][$i]["valor_total"]=$resultRegistro["precio_original"]*$resultRegistro["cantidad"];
          }
          
          $i=$i+1;

        }

      }

    }
    

    function retornarProductosCotizacionPorParamLim2($conexion,$consulta,$codigo_cotizacion,$inicio,$numRegistros){

      if(!isset($_SESSION))
        session_start();

      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());
      $totalResultados=mysql_num_rows($resultadoConsulta);
      $_SESSION["cantRegProductoCotizacionBusqTodos"]=$totalResultados;
      $consulta5=$consulta." LIMIT ".$inicio.",".$numRegistros;
      //echo $consulta5;exit;
      $resultadoConsulta1=mysql_query($consulta5,$conexion) or die(mysql_error());
      $totalResultados1=mysql_num_rows($resultadoConsulta1);
      $_SESSION["cantRegProductoCotizacionBusqTodosLim"]=$totalResultados1;

      if($totalResultados1>=1){

        if(isset($_SESSION["listaProductosCotizacion"]))
          unset($_SESSION["listaProductosCotizacion"]);

        $i=0;

        while ($resultRegistro = mysql_fetch_assoc($resultadoConsulta1)){

          $_SESSION["listaProductosCotizacion"][$i]["codigo_cotizacion_producto"]=$resultRegistro["codigo_cotizacion_producto"];
          $_SESSION["listaProductosCotizacion"][$i]["descripcion_producto"]=$resultRegistro["descripcion_producto"];
          $_SESSION["listaProductosCotizacion"][$i]["ruta_imagen"]=$resultRegistro["ruta_imagen"];
          $_SESSION["listaProductosCotizacion"][$i]["precio_original"]=$resultRegistro["precio_original"];
          $_SESSION["listaProductosCotizacion"][$i]["cantidad"]=$resultRegistro["cantidad"];
          $_SESSION["listaProductosCotizacion"][$i]["variacion"]=$resultRegistro["variacion"];
          $_SESSION["listaProductosCotizacion"][$i]["porcen_variacion"]=$resultRegistro["porcen_variacion"];
          if($resultRegistro["variacion"]=='AUMENTO'){
            $_SESSION["listaProductosCotizacion"][$i]["valor_unit"]=((1+($resultRegistro["porcen_variacion"]/100))*$resultRegistro["precio_original"]);
            $_SESSION["listaProductosCotizacion"][$i]["valor_total"]=((1+($resultRegistro["porcen_variacion"]/100))*$resultRegistro["precio_original"])*$resultRegistro["cantidad"];
          }
          else if($resultRegistro["variacion"]=='DESCUENTO'){
            $_SESSION["listaProductosCotizacion"][$i]["valor_unit"]=((1-($resultRegistro["porcen_variacion"]/100))*$resultRegistro["precio_original"]);
            $_SESSION["listaProductosCotizacion"][$i]["valor_total"]=((1-($resultRegistro["porcen_variacion"]/100))*$resultRegistro["precio_original"])*$resultRegistro["cantidad"];
          }
          else{
            $_SESSION["listaProductosCotizacion"][$i]["valor_unit"]=$resultRegistro["precio_original"];
            $_SESSION["listaProductosCotizacion"][$i]["valor_total"]=$resultRegistro["precio_original"]*$resultRegistro["cantidad"];
          }

          $i=$i+1;

        }

      }

    }
    
    
    function retornarProductosCotizacionPorParamLim3($conexion,$consulta,$codigo_cotizacion){

      if(!isset($_SESSION))
        session_start();

      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());
      $totalResultados=mysql_num_rows($resultadoConsulta);
      $_SESSION["cantRegProductoCotizacionBusqTodos"]=$totalResultados;
      $consulta5=$consulta;
      //echo $consulta5;exit;
      $resultadoConsulta1=mysql_query($consulta5,$conexion) or die(mysql_error());
      $totalResultados1=mysql_num_rows($resultadoConsulta1);
      $_SESSION["cantRegProductoCotizacionBusqTodosLim"]=$totalResultados1;

      if($totalResultados1>=1){

        if(isset($_SESSION["listaProductosCotizacion"]))
          unset($_SESSION["listaProductosCotizacion"]);

        $i=0;

        while ($resultRegistro = mysql_fetch_assoc($resultadoConsulta1)){

          $_SESSION["listaProductosCotizacion"][$i]["codigo_cotizacion_producto"]=$resultRegistro["codigo_cotizacion_producto"];
          $_SESSION["listaProductosCotizacion"][$i]["descripcion_producto"]=$resultRegistro["descripcion_producto"];
          $_SESSION["listaProductosCotizacion"][$i]["ruta_imagen"]=$resultRegistro["ruta_imagen"];
          $_SESSION["listaProductosCotizacion"][$i]["precio_original"]=$resultRegistro["precio_original"];
          $_SESSION["listaProductosCotizacion"][$i]["cantidad"]=$resultRegistro["cantidad"];
          $_SESSION["listaProductosCotizacion"][$i]["variacion"]=$resultRegistro["variacion"];
          $_SESSION["listaProductosCotizacion"][$i]["porcen_variacion"]=$resultRegistro["porcen_variacion"];
          if($resultRegistro["variacion"]=='AUMENTO'){
            $_SESSION["listaProductosCotizacion"][$i]["valor_unit"]=((1+($resultRegistro["porcen_variacion"]/100))*$resultRegistro["precio_original"]);
            $_SESSION["listaProductosCotizacion"][$i]["valor_total"]=((1+($resultRegistro["porcen_variacion"]/100))*$resultRegistro["precio_original"])*$resultRegistro["cantidad"];
          }
          else if($resultRegistro["variacion"]=='DESCUENTO'){
            $_SESSION["listaProductosCotizacion"][$i]["valor_unit"]=((1-($resultRegistro["porcen_variacion"]/100))*$resultRegistro["precio_original"]);
            $_SESSION["listaProductosCotizacion"][$i]["valor_total"]=((1-($resultRegistro["porcen_variacion"]/100))*$resultRegistro["precio_original"])*$resultRegistro["cantidad"];
          }
          else{
            $_SESSION["listaProductosCotizacion"][$i]["valor_unit"]=$resultRegistro["precio_original"];
            $_SESSION["listaProductosCotizacion"][$i]["valor_total"]=$resultRegistro["precio_original"]*$resultRegistro["cantidad"];
          }

          $i=$i+1;

        }

      }

    }


    function retornarProductosPorCodigo($conexion,$codigo_producto){
      
      if(!isset($_SESSION))
        session_start(); 

      $consulta="SELECT a.descripcion_producto as descripcion_producto,a.ruta_imagen as ruta_imagen,(select b.precio from tb_productos_precios b where b.codigo_producto=a.codigo_producto order by b.fecha_inicial desc limit 1) as precio from tb_productos a where a.codigo_producto='$codigo_producto';";
      //echo $consulta;exit;
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());
      $totalResultados=mysql_num_rows($resultadoConsulta);
          
      if($totalResultados>=1){
      
        $_SESSION['cant_productos_exist']=$totalResultados;
        
        $i=0; 
        
        while ($resultRegistro = mysql_fetch_assoc($resultadoConsulta)){

          $_SESSION["descripcion_producto"]=$resultRegistro["descripcion_producto"];
          $_SESSION["ruta_imagen"]=$resultRegistro["ruta_imagen"];
          $_SESSION["precio"]=$resultRegistro["precio"];

          $i=$i+1;          
          
        }           
        
      } 
 
    } 
    
    
    function ingresarProductoCotizacion($conexion,$codigo_cotizacion,$codigo_producto){

      $consulta="insert into tb_cotizaciones_productos_temp (codigo_cotizacion,codigo_producto,precio_original) (SELECT $codigo_cotizacion,$codigo_producto,(select b.precio from tb_productos_precios b where b.codigo_producto=$codigo_producto order by b.fecha_inicial desc limit 1));";
      //echo $consulta;exit;
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());

    }
    
    function modificarProductoCotizacion($conexion,$codigo_cotizacion_producto,$cantidad,$variacion,$porcen_variacion){
      if($variacion=='0')
        $variacion='';
      else if($variacion=='1')
        $variacion='DESCUENTO';
      else
        $variacion='AUMENTO';
      $consulta="update tb_cotizaciones_productos_temp set cantidad='$cantidad',variacion='$variacion',porcen_variacion='$porcen_variacion' where codigo_cotizacion_producto='$codigo_cotizacion_producto';";
      //echo $consulta;exit;
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());

    }
    
    
    function eliminarProductoCotizacion($conexion,$codigo_cotizacion_producto){

      $consulta="delete from tb_cotizaciones_productos_temp where codigo_cotizacion_producto=$codigo_cotizacion_producto;";
      //echo $consulta;exit;
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());

    }
    
    
    /*function retornarProductosCotizacionPorCodigo($conexion,$codigo_cotizacion_producto){

      if(!isset($_SESSION))
        session_start();

      $consulta="SELECT a.descripcion_producto as descripcion_producto,a.ruta_imagen as ruta_imagen,(select b.precio from tb_productos_precios b where b.codigo_producto=a.codigo_producto order by b.fecha_inicial desc limit 1) as precio from tb_productos a where a.codigo_producto='".$codigo_producto."';";
      //echo $consulta;exit;
      $resultadoConsulta=mysql_query($consulta,$conexion) or die(mysql_error());
      $totalResultados=mysql_num_rows($resultadoConsulta);

      if($totalResultados>=1){

        $_SESSION['cant_productos_exist']=$totalResultados;

        $i=0;

        while ($resultRegistro = mysql_fetch_assoc($resultadoConsulta)){

          $_SESSION["descripcion_producto"]=$resultRegistro["descripcion_producto"];
          $_SESSION["ruta_imagen"]=$resultRegistro["ruta_imagen"];
          $_SESSION["precio"]=$resultRegistro["precio"];

          $i=$i+1;

        }

      }

    }*/


  }

?>
