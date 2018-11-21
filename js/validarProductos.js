function traerDatosProducto(codigo_producto){
  parent.location='formularioGestionProducto.php?codigo_producto='+codigo_producto;
}


function limpiarFormularioProducto(){
  parent.location='formularioGestionProducto.php';
}


function ingresarProductoCotizacion(codigo_producto,codigo_cotizacion){
  var r = confirm("Desea agregar el producto a la cotizaci\u00F3n?");
  if (r == true) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var respuesta = this.responseText;
            }
        };
        xmlhttp.open("GET", "../controladores/ingresarProductoCotizacion.php?codigo_cotizacion="+codigo_cotizacion+"&codigo_producto="+codigo_producto, true);
        xmlhttp.send();
        
        alert("Operaci\u00F3n realizada exitosamente");

        window.opener.document.location.href="../pantallas/formularioGestionCotizacion.php?codigo_cotizacion="+codigo_cotizacion;
        window.close();
        
  }
}


function eliminarProductoCotizacion(codigo_cotizacion_producto,codigo_cotizacion){
  var r = confirm("Desea eliminar el producto de la cotizaci\u00F3n?");
  if (r == true) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var respuesta = this.responseText;
            }
        };
        //alert(respuesta);
        xmlhttp.open("GET", "../controladores/eliminarProductoCotizacion.php?codigo_cotizacion_producto="+codigo_cotizacion_producto, true);
        xmlhttp.send();

        alert("Operaci\u00F3n realizada exitosamente");

        window.location.href="../pantallas/formularioGestionCotizacion.php?codigo_cotizacion="+codigo_cotizacion;

        //window.close();

  }

}


function validarGuardarProducto(formulario){
  if(formulario.descripcion_producto.value == ""){
    alert("Debe ingresar la descripci\u00F3n del producto");
    return false;
  }
  if(formulario.precio.value == ""){
    alert("Debe ingresar el precio del producto");
    return false;
  }
  else if(isNaN(formulario.precio.value)){
    alert("El precio debe ser un valor num\u00E9rico");
    return false;
  }

  return true;

}

function validarGuardarProductoCotizacion(formulario){
  if(formulario.cantidad.value == "0"){
    alert("Debe ingresar la cantidad");
    return false;
  }
  if(formulario.variacion.value != "0"){
    if(formulario.porcen_variacion.value == "0"){
      alert("Debe ingresar el porcentaje de variaci\u00F3n");
      return false;
    }
  }
  return true;
}

function calculos(field){

  var precio_nuevo,precio_total;
  
  if(!isNaN(field.value)){
  
    if(document.getElementById("variacion").value=="2"){
      precio_nuevo=(document.getElementById("precio_original").value*(1+(document.getElementById("porcen_variacion").value/100))).toFixed(2);
    }
    else if(document.getElementById("variacion").value=="1"){
      precio_nuevo=(document.getElementById("precio_original").value*(1-(document.getElementById("porcen_variacion").value/100))).toFixed(2);
    }
    else{
      precio_nuevo=(document.getElementById("precio_original").value*1).toFixed(2);
      document.getElementById("porcen_variacion").value="0";
    }

    precio_total=(precio_nuevo*document.getElementById("cantidad").value).toFixed(2);
    document.getElementById("precio_nuevo").value=precio_nuevo;
    document.getElementById("precio_total").value=precio_total;
    
  }
  else
    field.value="0";

}
