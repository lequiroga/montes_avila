var app=angular.module("clientsModule",[]);

app.controller("clientsController1",

  function ($scope,$http){
  
    $scope.getTiposIdentificacion= function(){
      $http.get("ejemplo1.php")
      .then(function(response){
        $scope.ids_tipo_id=response.data;
      });
    }
    
    $scope.enviarAccionCliente=function(accion){
      if(!((angular.isUndefined($scope.cliente))||($scope.cliente==null))){
        $http.post("ejemplo2.php",{'id_clientes':$scope.cliente.id_clientes,'id_tipo_identificacion':$scope.cliente.selectedTypeID,'numero_identificacion':$scope.cliente.numero_identificacion,'nombres':$scope.cliente.nombres,'apellidos':$scope.cliente.apellidos,'edad':$scope.cliente.edad,'sexo':$scope.cliente.sexo,'direccion':$scope.cliente.direccion,'telefono':$scope.cliente.telefono,'fecha_nacimiento':$scope.cliente.fecha_nacimiento,'accion':accion})
        .then(function(response){
          if(response.data=="1"){
            alert("Se ha insertado exitosamente");
            $scope.cleanClients();
          }
          else if(response.data=="2"){
            alert("Ya existe un cliente con el numero de identificacion "+$scope.cliente.numero_identificacion+", por favor verifique");
          }
          else if(response.data=="3"){
            alert("Se ha actualizado exitosamente");
            $scope.cleanClients();
          }
          else if(response.data=="4"){
            alert("Ya existe otro cliente con el numero de identificacion "+$scope.cliente.numero_identificacion+", por favor verifique");
          }
          else if(response.data=="5"){
            alert("Se ha eliminado exitosamente");
            $scope.cleanClients();
          }
        });
      }
    }
    
    $scope.validateNumber= function(){
      if(isNaN($scope.cliente.numero_identificacion)){
        $scope.cliente.numero_identificacion='';
      }
      else{
          $scope.cliente.numero_identificacion=$scope.cliente.numero_identificacion.replace(".","");
          $scope.showClientsByID();
      }
    }
    
    $scope.listaClientes1= function(){
    
      var selectedTypeID;
      var numero_identificacion;
      var nombres;
      var apellidos;

      if((angular.isUndefined($scope.cliente))||($scope.cliente==null)){
        selectedTypeID=0;
        numero_identificacion='';
        nombres='';
        apellidos='';
      }
      else{
        selectedTypeID=$scope.cliente.selectedTypeID;
        numero_identificacion=$scope.cliente.numero_identificacion;
        nombres=$scope.cliente.nombres;
        apellidos=$scope.cliente.apellidos;
      }

      $http.post("ejemplo6.php",{'id_tipo_identificacion':selectedTypeID,'numero_identificacion':numero_identificacion,'nombres':nombres,'apellidos':apellidos})
      .then(function(response){
        $scope.l_clientes=response.data;
      })
      .catch(function(data){
        alert(response.data);
      });
    }

    $scope.calcularEdad= function(){

      var fecnac=new Date($scope.cliente.fecha_nacimiento);
      
      var yearNac = fecnac.getFullYear();
      var monthNac = fecnac.getMonth();
      var dayNac = fecnac.getDate();
      
      var fecac=new Date();
      
      var yearAc = fecac.getFullYear();
      var monthAc = fecac.getMonth();
      var dayAc = fecac.getDate();

      var diffec=0;

      if(yearAc>yearNac){
        diffec= (yearAc-yearNac)-1;
        if(monthNac<monthAc){
          diffec++;
        }
        else if(monthNac==monthAc){
          if(dayNac<=dayAc){
            diffec++;
          }
        }
      }
      
      //var diffec=parseInt((fecac-fecnac)/(1000*60*60*24*365));
      if(diffec>0){
        $scope.cliente.edad=diffec;
      }
      else{
        $scope.cliente.fecha_nacimiento=null;
        $scope.cliente.edad=null;
      }
    }
    
    $scope.showClientsByID= function(){
      if(!((angular.isUndefined($scope.cliente.numero_identificacion))||($scope.cliente==null))){
        if($scope.cliente.selectedTypeID && $scope.cliente.numero_identificacion.length > 3){
          $http.post("ejemplo3.php",{'id_tipo_identificacion':$scope.cliente.selectedTypeID,'numero_identificacion':$scope.cliente.numero_identificacion,'accion':'showByID'})
          .then(function(response){
            if(response.data.id_clientes){
              $scope.cliente.selectedTypeID=response.data.id_tipo_identificacion;
              $scope.cliente.numero_identificacion=response.data.numero_identificacion;
              $scope.cliente.nombres=response.data.nombres;
              $scope.cliente.apellidos=response.data.apellidos;
              $scope.cliente.edad=response.data.edad;
              $scope.cliente.sexo=response.data.sexo;
              $scope.cliente.direccion=response.data.direccion;
              $scope.cliente.telefono=response.data.telefono;
              $scope.cliente.fecha_nacimiento=new Date(response.data.fecha_nac);
              $scope.cliente.id_clientes=response.data.id_clientes;
            }
          });
        }
      }
    }
    
    $scope.cleanClients= function(){
      $scope.cliente=null;
      $scope.clientsForm.$setPristine();
      $scope.l_clientes=null;
    }
    
  }
);
