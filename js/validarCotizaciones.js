function traerDatosCotizaciones(codigo_cotizacion){
  parent.location='formularioGestionCotizacion.php?codigo_cotizacion='+codigo_cotizacion+'&val=1';
}


function limpiarFormularioCotizacion(){
  parent.location='formularioGestionCotizacion.php';
}


/*function cliente_sel(datalist){
    alert("hola");
    alert(datalist.value);
}  */


function cliente_sel(cliente){

    var cliente_seleccionado="";
    var input = document.getElementById("cliente");
    var list = document.getElementById("clientes");
    var i;
    for (i = 0; i < list.options.length; ++i) {
        if (list.options[i].value === input.value) {
            cliente_seleccionado=list.options[i].getAttribute('data-id');
            if(cliente_seleccionado!=""){
              i=list.options.length+1;
            }
        }
    }
    
    if(cliente_seleccionado==""){
      document.getElementById("tipo_identificacion").value = "";
        document.getElementById("numero_identificacion").value = "";
        document.getElementById("telefono").value = "";
        document.getElementById("direccion").value = "";
        document.getElementById("correo_electronico").value = "";
        document.getElementById("codigo_cliente").value = "";
        document.getElementById("cliente").value = "";
    }
    else{
      showHint(cliente_seleccionado);
    }

}


function cliente_sel1(cliente){

    var cliente_seleccionado="";
    var input = document.getElementById("cliente");
    var list = document.getElementById("clientes");
    var i;
    for (i = 0; i < list.options.length; ++i) {
        if (list.options[i].value === input.value) {
            cliente_seleccionado=list.options[i].getAttribute('data-id');
            if(cliente_seleccionado!=""){
              i=list.options.length+1;
            }
        }
    }

    if(cliente_seleccionado==""){
        document.getElementById("codigo_cliente").value = "";
        document.getElementById("cliente").value = "";
    }
    else{
      document.getElementById("codigo_cliente").value = cliente_seleccionado;
    }

}


function showHint(str) {
    if (str.length == 0) {
        document.getElementById("tipo_identificacion").value = "";
        document.getElementById("numero_identificacion").value = "";
        document.getElementById("telefono").value = "";
        document.getElementById("direccion").value = "";
        document.getElementById("correo_electronico").value = "";
        document.getElementById("codigo_cliente").value = "";
        document.getElementById("cliente").value = "";
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var respuesta = this.responseText.split(',');
                document.getElementById("tipo_identificacion").value = respuesta[0].replace('"','').replace('"','');
                document.getElementById("numero_identificacion").value = respuesta[1].replace('"','').replace('"','');
                document.getElementById("telefono").value = respuesta[3].replace('"','').replace('"','');
                document.getElementById("direccion").value = respuesta[4].replace('"','').replace('"','');
                document.getElementById("correo_electronico").value = respuesta[5].replace('"','').replace('"','');
                document.getElementById("codigo_cliente").value = respuesta[6].replace('"','').replace('"','');
                document.getElementById("cliente").value = respuesta[2].replace('"','').replace('"','');
            }
        };
        xmlhttp.open("GET", "../controladores/getDatosCliente.php?codigo_cliente=" + str, true);
        xmlhttp.send();
    }
}


function validarGuardarCotizacion(formulario){
  if((formulario.codigo_cliente.value == "") && (formulario.cliente.value == "")){
    alert("Debe ingresar el cliente");
    return false;
  }
  if(formulario.vigencia.value == ""){
    alert("Debe ingresar la fecha de vigencia");
    return false;
  }
  return true;

}
