let  tableUsuarios;
//ALMACENA EL PARAMETRO QUE ENVIAMOS DE LA FUNCION fntEditUsuario
let rowTable = "";
let  divLoading = document.querySelector("#divLoading");
//CARGA LOS EVENTOS DEL FORMULARIO 
document.addEventListener('DOMContentLoaded', function(){

    tableUsuarios = $('#tableUsuarios').dataTable( {
        "aProcessing":true,
        "aServerSide":true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax":{
            "url": " "+base_url+"/Usuarios/getUsuarios",
            "dataSrc":""
        },
        "columns":[
            {"data":"idpersona"},
            {"data":"nombres"},
            {"data":"apellidos"},
            {"data":"email_user"},
            {"data":"telefono"},
            {"data":"nombrerol"},
            {"data":"status"},
            {"data":"options"}
        ],
        'dom': 'lBfrtip',
        'buttons': [
            {
                "extend": "copyHtml5",
                "text": "<i class='fas fa-paste fa-2x m-1 p-2'></i>",
                "titleAttr":"Copiar",
                "className": "btn btn-secondary mt-3"
            },{
                "extend": "excelHtml5",
                "text": "<i class='fas fa-file-excel fa-2x m-1 p-2'></i>",
                "titleAttr":"Exportar a Excel",
                "className": "btn btn-success mt-3"
            },{
                "extend": "pdfHtml5",
                "text": "<i class='fas fa-file-pdf fa-2x m-1 p-2'></i>",
                "titleAttr":"Exportar a PDF",
                "className": "btn btn-danger mt-3"
            },{
                "extend": "csvHtml5",
                "text": "<i class='fas fa-file-csv fa-2x m-1 p-2'></i>",
                "titleAttr":"Exportar a CSV",
                "className": "btn btn-info mt-3"
            }
        ],
        "responsive":"true",
        "bDestroy": true,
        "iDisplayLength": 10,
        "order":[[0,"asc"]]  
    });
    //Nuevo Usuario
    if(document.querySelector("#formUsuario")){
        let  formUsuario = document.querySelector("#formUsuario");
        //ONSUMIT ACTIVAMOS EL EVENTO
        formUsuario.onsubmit = function(e) {
            //EVITA QUE SE RECARGUE LOS DATOS AL DARLE CLICK A UN BOTON
            e.preventDefault();
            //VALUE ACCEDEMOS AL VALOR QUE SE LE ESTA COLOCANDO txtIdentificacion
            let  strIdentificacion = document.querySelector('#txtIdentificacion').value;
            let  strNombre = document.querySelector('#txtNombre').value;
            let  strApellido = document.querySelector('#txtApellido').value;
            let  strEmail = document.querySelector('#txtEmail').value;
            let  intTelefono = document.querySelector('#txtTelefono').value;
            let  intTipousuario = document.querySelector('#listRolid').value;
            let  strPassword = document.querySelector('#txtPassword').value;
            //creamos la variable status
            let intStatus = document.querySelector('#listStatus').value;

            //VALIDANDO CADA UNO DE LOS CAMPOS
            if(strIdentificacion == '' || strApellido == '' || strNombre == '' || strEmail == '' || intTelefono == '' || intTipousuario == '')
            {
                swal("Atención", "Todos los campos son obligatorios." , "error");
                return false;
            }
            divLoading.style.display = "flex";
            let  request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let  ajaxUrl = base_url+'/Usuarios/setUsuario'; 
            let  formData = new FormData(formUsuario);
            request.open("POST",ajaxUrl,true);
            request.send(formData);
            
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let  objData = JSON.parse(request.responseText);
                    if(objData.status)
                    {   
                        //creamos un nuevo usuario
                        if(rowTable == ""){
                            tableUsuarios.api().ajax.reload();
                        }else{
                            //modificamos un usuario
                            //obtenemos los valores de las celdas de la tabla
                            //verificamos el estado y asignamos
                            htmlStatus = intStatus == 1 ? 
                            '<span class="badge badge-success">Activo</span>' : 
                            '<span class="badge badge-danger">Inactivo</span>';
                            rowTable.cells[1].textContent = strNombre;
                            rowTable.cells[2].textContent = strApellido;
                            rowTable.cells[3].textContent = strEmail;
                            rowTable.cells[4].textContent = intTelefono;
                            //obtenemos el texto del select en vez del numero hacemos lo mismo con status
                            rowTable.cells[5].textContent = document.querySelector("#listRolid").selectedOptions[0].text;
                            rowTable.cells[6].innerHTML = htmlStatus;
                            rowTable="";
                        }
                        $('#modalFormUsuario').modal("hide");
                        formUsuario.reset();
                        swal("Usuarios", objData.msg ,"success");
                        //REFRESCAMOS LA TABLA
                        //$('#listRolid').selectpicker('render');

                    }else{
                        swal("Error", objData.msg , "error");
                    }
                }
                divLoading.style.display = "none";
                return false;
            }

        }
    }

    //Actualizar Perfil
    if(document.querySelector("#formPerfil")){
        let  formPerfil = document.querySelector("#formPerfil");
        formPerfil.onsubmit = function(e) {
            e.preventDefault();
            let  strIdentificacion = document.querySelector('#txtIdentificacion').value;
            let  strNombre = document.querySelector('#txtNombre').value;
            let  strApellido = document.querySelector('#txtApellido').value;
            let  intTelefono = document.querySelector('#txtTelefono').value;
            let  strPassword = document.querySelector('#txtPassword').value;
            let  strPasswordConfirm = document.querySelector('#txtPasswordConfirm').value;

            if(strIdentificacion == '' || strApellido == '' || strNombre == '' || intTelefono == '' )
            {
                swal("Atención", "Todos los campos son obligatorios." , "error");
                return false;
            }

            if(strPassword != "" || strPasswordConfirm != "")
            {   
                if( strPassword != strPasswordConfirm ){
                    swal("Atención", "Las contraseñas no son iguales." , "info");
                    return false;
                }           
                if(strPassword.length < 5 ){
                    swal("Atención", "La contraseña debe tener un mínimo de 5 caracteres." , "info");
                    return false;
                }
            }
            
            //Validacion de los Campos
            let elementsValid = document.getElementsByClassName("valid");
            for (let i = 0; i < elementsValid.length; i++) { 
                if(elementsValid[i].classList.contains('is-invalid')) { 
                    swal("Atención", "Por favor verifique los campos en rojo." , "error");
                    return false;
                } 
            } 
            //MOSTRAMOS EL LOADING
            divLoading.style.display = "flex";
            let  request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let  ajaxUrl = base_url+'/Usuarios/putPerfil'; 
            let  formData = new FormData(formPerfil);
            request.open("POST",ajaxUrl,true);
            request.send(formData);
            request.onreadystatechange = function(){
                if(request.readyState != 4) return; 
                if(request.status == 200){
                    let  objData = JSON.parse(request.responseText);
                    if(objData.status)
                    {
                        $('#modalFormPerfil').modal("hide");
                        swal({
                            title: "",
                            text: objData.msg,
                            type: "success",
                            confirmButtonText: "Aceptar",
                            closeOnConfirm: false,
                        }, function(isConfirm) {
                            if (isConfirm) {
                                //Refresca la Pagina si es verdadera
                                location.reload();
                            }
                        });
                    }else{
                        swal("Error", objData.msg , "error");
                    }
                }
                //OCULTAMOS EL LOADING
                divLoading.style.display = "none";
                return false;
            }
        }
    } 
    
    //Actualizar Datos Fiscales
    if(document.querySelector("#formDataFiscal")){
        let formDataFiscal = document.querySelector("#formDataFiscal");
        formDataFiscal.onsubmit = function(e) {
            e.preventDefault();
            let strNit = document.querySelector('#txtNit').value;
            let strNombreFiscal = document.querySelector('#txtNombreFiscal').value;
            let strDirFiscal = document.querySelector('#txtDirFiscal').value;
           
            if(strNit == '' || strNombreFiscal == '' || strDirFiscal == '' )
            {
                swal("Atención", "Todos los campos son obligatorios." , "error");
                return false;
            }
            divLoading.style.display = "flex";
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'/Usuarios/putDFical'; 
            let formData = new FormData(formDataFiscal);
            request.open("POST",ajaxUrl,true);
            request.send(formData);
            request.onreadystatechange = function(){
                if(request.readyState != 4 ) return; 
                if(request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status)
                    {
                        $('#modalFormPerfil').modal("hide");
                        swal({
                            title: "",
                            text: objData.msg,
                            type: "success",
                            confirmButtonText: "Aceptar",
                            closeOnConfirm: false,
                        }, function(isConfirm) {
                            if (isConfirm) {
                                location.reload();
                            }
                        });
                    }else{
                        swal("Error", objData.msg , "error");
                    }
                }
                divLoading.style.display = "none";
                return false;
            }
        }
    }

}, false);



//EJECUTAMOS LA FUNCION
window.addEventListener('load', function() {
        fntRolesUsuario();
        //fntViewUsuario();
        //fntEditUsuario();
        //fntDelUsuario();
}, false);

function fntRolesUsuario(){
  if(document.querySelector('#listRolid')){  
    let  ajaxUrl = base_url+'/Roles/getSelectRoles';
    let  request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    request.open("GET",ajaxUrl,true);
    request.send();

    //OBTENEMOS LOS RESULTADOS
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            document.querySelector('#listRolid').innerHTML = request.responseText;
            //seleccionamos el 1 elemento de la lista
            document.querySelector('#listRolid').value = 1;
            //ACTUALIZAMOS LOS REGISTROS
            $('#listRolid').selectpicker('render');
            //$('#listRolid').selectpicker('refresh');
        }
    }
  }  
}

//FUNCION PARA MOSTRAR DATOS DEL USUARIO
function fntViewUsuario(idpersona){

  //OBTENEMOS EL VALOR DEL ATRIBUTO US
  //let  idpersona = idpersona;
  let  request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
  let  ajaxUrl = base_url+'/Usuarios/getUsuario/'+idpersona;
  request.open("GET",ajaxUrl,true);
  request.send();
  request.onreadystatechange = function(){
    if(request.readyState == 4 && request.status == 200){
        //creamos el obdData para acceder a los elementos del json
        let  objData = JSON.parse(request.responseText);

        if(objData.status)
        {  

           let  estadoUsuario = objData.data.status == 1 ? 
            '<span class="badge badge-success">Activo</span>' : 
            '<span class="badge badge-danger">Inactivo</span>';
            //asignando los valores
            document.querySelector("#celIdentificacion").innerHTML = objData.data.identificacion;
            document.querySelector("#celNombre").innerHTML = objData.data.nombres;
            document.querySelector("#celApellido").innerHTML = objData.data.apellidos;
            document.querySelector("#celTelefono").innerHTML = objData.data.telefono;
            document.querySelector("#celEmail").innerHTML = objData.data.email_user;
            document.querySelector("#celTipoUsuario").innerHTML = objData.data.nombrerol;
            document.querySelector("#celEstado").innerHTML = estadoUsuario;
            document.querySelector("#celFechaRegistro").innerHTML = objData.data.fechaRegistro; 
            $('#modalViewUser').modal('show');
        }else{
            swal("Error", objData.msg , "error");
        }
    }
  }  
}

//FUNCION PARA ACTUALIZAR DATOS DEL USUARIO
function fntEditUsuario(element, idpersona){
  //PARENTNODE NOS DIRIGIMOS AL ELEMENTO PADRE (DIV) LUEGO AL OTRO PADRE (TR) PARA LA FILA OTRO PARENT(TR)
  rowTable = element.parentNode.parentNode.parentNode;
  //cambio un valor a la celda
  //rowTable.cells[1].textContent = "Jose"; 
  //MUESTRA LOS DATOS DEL USUARIO ROWTABLE
  //console.log(rowTable);
  document.querySelector('#titleModal').innerHTML ="Actualizar Usuario";
  document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
  document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
  document.querySelector('#btnText').innerHTML ="Actualizar";
  
  //let  idpersona = idpersona;
  let  request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
  let  ajaxUrl = base_url+'/Usuarios/getUsuario/'+idpersona;
  request.open("GET",ajaxUrl,true);
  request.send();
  request.onreadystatechange = function(){
    if(request.readyState == 4 && request.status == 200){
        let  objData = JSON.parse(request.responseText);

        if(objData.status)
        {  
            document.querySelector("#idUsuario").value = objData.data.idpersona;
            document.querySelector("#txtIdentificacion").value = objData.data.identificacion;
            document.querySelector("#txtNombre").value = objData.data.nombres;
            document.querySelector("#txtApellido").value = objData.data.apellidos;
            document.querySelector("#txtTelefono").value = objData.data.telefono;
            document.querySelector("#txtEmail").value = objData.data.email_user;
            document.querySelector("#listRolid").value =objData.data.idrol; 
            $('#listRolid').selectpicker('render');

            if(objData.data.status == 1){
            document.querySelector("#listStatus").value = 1;
            }else{
                document.querySelector("#listStatus").value = 2;
            }
            $('#listStatus').selectpicker('render');
        }
    }
    $('#modalFormUsuario').modal('show');
  }
}

function fntDelUsuario(idpersona){
       
    //let  idUsuario = idpersona;
    //MENSAJE
    swal({
        title: "Eliminar Usuario",
        text: "¿Esta Seguro de eliminar el Usuario?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        
        //VALIDAMOS SI ISCONFIRM ES VERDADERO
        if (isConfirm) 
        {
            let  request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            //RUTA
            let  ajaxUrl = base_url+'/Usuarios/delUsuario/';
            //let IABLE QUE OBTENEMOS DE RL
            let  strData = "idUsuario="+idpersona;
            //ABRIMOS LA CONEXION Y INDICAMOS EL METODO LA RUTA
            request.open("POST",ajaxUrl,true);
            //COMO SE ENVIARAN LOS DATOS
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            //ENVIAMOS LOS DATOS
            request.send(strData);
            //VALIDACION DE LA RESPUESTA
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    //CONVERTIMOS EN UN OBJETO LO QUE TRAE REQUEST
                    let  objData = JSON.parse(request.responseText);
                    if(objData.status)
                    { 
                        swal("Eliminar!", objData.msg , "success");
                        tableUsuarios.api().ajax.reload();
                    }else{
                        swal("Atención!", objData.msg , "error");
                    }
                }
            }
        }

    });
}

function openModal(){
    //limpiamos los valores
    rowTable= "";
    document.querySelector('#idUsuario').value ="";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML ="Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Usuario";
    document.querySelector("#formUsuario").reset();
    $('#listRolid').selectpicker('render');
    $('#modalFormUsuario').modal('show');
}

function openModalPerfil(){
    $('#modalFormPerfil').modal('show');
}