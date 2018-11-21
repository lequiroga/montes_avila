function traerDatosCliente(codigo_cliente){
  parent.location='formularioGestionCliente.php?codigo_cliente='+codigo_cliente;
}


function limpiarFormularioCliente(){
  parent.location='formularioGestionCliente.php';
}


function validarGuardarCliente(formulario){
  if(formulario.tipo_identificacion.value == ""){
    alert("Debe ingresar el tipo de identificaci\u00F3n");
    return false;
  }
  if(formulario.numero_identificacion.value == ""){
    alert("Debe ingresar el n\u00FAmero de identificaci\u00F3n");
    return false;
  }
  else if(isNaN(formulario.numero_identificacion.value)){
    alert("El n\u00FAmero de identificaci\u00F3n debe ser un valor num\u00E9rico");
    return false;
  }
  if(formulario.cliente.value == ""){
    alert("Debe ingresar el cliente");
    return false;
  }
  if(formulario.telefono.value == ""){
    alert("Debe ingresar el tel\u00E9fono");
    return false;
  }
  else if(isNaN(formulario.telefono.value)){
    alert("El tel\u00E9fono debe ser un valor num\u00E9rico");
    return false;
  }
  if(formulario.direccion.value == ""){
    alert("Debe ingresar la direcci\u00F3n");
    return false;
  }
  /*if(formulario.correo_electronico.value == ""){
    alert("Debe ingresar el correo electr\u00F3nico");
    return false;
  }*/

  return true;

}
