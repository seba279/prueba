
var tableRoles;
var divLoading = document.querySelector("#divLoading");
//AGREGAMOS UN EVENTO
document.addEventListener('DOMContentLoaded', function(){
    
    //SCRIPT DEL DATABLE
    //ID TABLEROLES DE LA VISTA
	tableRoles = $('#tableRoles').dataTable( {
		"aProcessing":true,
		"aServerSide":true,
        "language": {
        	"url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax":{
            //NOS DIRIGIMOS A LA URL DEL METODO
            "url": " "+base_url+"/Roles/getRoles",
            "dataSrc":""
        },
        //COLUMNAS DE LA TABLA
        "columns":[
            {"data":"idrol"},
            {"data":"nombrerol"},
            {"data":"descripcion"},
            {"data":"status"},
            {"data":"options"}
        ],
        "resonsieve":"true",
        "bDestroy": true,
        //MUESTRE 10 REGISTRO
        "iDisplayLength": 10,
        //ORDEN DESCENDENTE desc ASENDENTE ASC
        "order":[[0,"asc"]]  
    });

    //NUEVO ROL
    var formRol = document.querySelector("#formRol");
    
    formRol.onsubmit = function(e) {
        
        //No permite que se recargue la pagina
        e.preventDefault();
        
        //CAPTURAMOS LOS VALORES DE LAS VARIABLES
        var intIdRol = document.querySelector('#idRol').value;
        var strNombre = document.querySelector('#txtNombre').value;
        var strDescripcion = document.querySelector('#txtDescripcion').value;
        var intStatus = document.querySelector('#listStatus').value;        
        //validamos si las variables estan vacias
        if(strNombre == '' || strDescripcion == '' || intStatus == '')
        {
            swal("Atención", "Todos los campos son obligatorios." , "error");
            return false;
        }
        divLoading.style.display = "flex";
        var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        //ASIGNAMOS LA RUTA
        var ajaxUrl = base_url+'/Roles/setRol'; 
        var formData = new FormData(formRol);
        //SOLICITAMOS LOS DATOS POR MEDIO DE AJAX METODO POST
        request.open("POST",ajaxUrl,true);
        //ENVIAMOS LA INFORMACION
        request.send(formData);
        request.onreadystatechange = function(){
            //validamos la respuesta
           if(request.readyState == 4 && request.status == 200){
                
                //console.log(request.responseText);
                var objData = JSON.parse(request.responseText);
                //SI EL OBJETO ES STATUS OK
                if(objData.status)
                {   
                    //CERRAMOS EL MODAL
                    $('#modalFormRol').modal("hide");
                    //LIMPIAMOS EL FORMULARIO
                    formRol.reset();
                    //MENSAJE DE CONFIRMACION
                    swal("Roles de usuario", objData.msg ,"success");
                    //REFRESCAMOS EL DATABLE
                    tableRoles.api().ajax.reload();

                }else{
                    //MENSAJE DE ERROR
                    swal("Error", objData.msg , "error");
                    //AGREGADO
                    //formRol.reset();
                }                       
            }
            divLoading.style.display = "none";
            return false; 
        } 
    }

});

$('#tableRoles').DataTable();

//FUNCION PARA MOSTRAR EL MODAL DEL NUEVO ROL
function openModal(){
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML ="Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Rol";
    //LIMPIAMOS LOS CAMPOS
    document.querySelector("#formRol").reset();
    //MOSTRAMOS EL MODAL
    $('#modalFormRol').modal('show');
}

//agregamos el evento load para ejecutar la funcion fntEditRol
window.addEventListener('load', function() {
    //fntEditRol();
    //fntDelRol();
    //fntPermisos();
}, false);

//FUNCION PARA EDITAR UN ROL
function fntEditRol(idrol){

    //querySelector Nos referimos al elemento titleModal y con innerHTML le cambiamos el titulo Nuevo Rol a Actualizar Rol
    document.querySelector('#titleModal').innerHTML ="Actualizar Rol";
    //classList.replace REPLAZAMOS LA CLASE  headerRegister A headerUpdate
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML ="Actualizar";

    //this.getAttribute ACCEDEMOS AL valor del ATRIBUTO RL
    var idrol = idrol;
    //validamos si estamos en un navegador crome o firefox
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    //RUTA http://localhost/tienda2021/Roles/getRol/1
    var ajaxUrl  = base_url+'/Roles/getRol/'+idrol;
    //GET METODO POR EL CUAL OBTENDREMOS INFORMACION
    request.open("GET",ajaxUrl ,true);
    //ENVIAMOS LA PETICION
    request.send();
  
    //OBTENEMOS LA RESPUESTA
    request.onreadystatechange = function(){
    if(request.readyState == 4 && request.status == 200){
        //obtenemos los datos del json
        //console.log(request.responseText);
        //CONVERTIMOS EN OBJETO EL FORMATO JSON DE AJAX
        var objData = JSON.parse(request.responseText);
        // SI EL ESTADO ES IGUAL A VERDADERO=200
        if(objData.status)
        {   
            //COLOCAMOS LOS DATOS EN LOS CAMPOS DEL FORMULARIO
            document.querySelector("#idRol").value = objData.data.idrol;
            document.querySelector("#txtNombre").value = objData.data.nombrerol;
            document.querySelector("#txtDescripcion").value = objData.data.descripcion;
            
            //SI STATUS DEL ROL ES 1 
            if(objData.data.status == 1)
            {   
                //ENTONCES LE ASIGNAMOS EL VALOR ACTIVO
                var optionSelect = '<option value="1" selected class="notBlock">Activo</option>';
            }else{
                //SINO INACTIVO
                var optionSelect = '<option value="2" selected class="notBlock">Inactivo</option>';
            }
            //COLOCAMOS CON HTMSELECT LA OPCION ACTIVO O INACTIVO
            var htmlSelect = `${optionSelect}
                              <option value="1">Activo</option>
                              <option value="2">Inactivo</option>
                            `;
            //ASIGNAMOS EL VALOR
            document.querySelector("#listStatus").innerHTML = htmlSelect;
            //MOSTRAMOS EL MODAL
            $('#modalFormRol').modal('show');

        }else{
            swal("Error", objData.msg , "error");
        }

      }
    }       
}

//FUNCION PARA ELIMINAR UN ROL
function fntDelRol(idrol){
   
    //LE ASIGNAMOS EL VALOR DE RL
    var idrol = idrol;
    //MENSAJE
    swal({
        title: "Eliminar Rol",
        text: "¿Esta Seguro de eliminar el Rol?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Eliminar!",
        cancelButtonText: "Cancelar!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        
        //VALIDAMOS SI ISCONFIRM ES VERDADERO
        if (isConfirm) 
        {
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            //RUTA
            var ajaxUrl = base_url+'/Roles/delRol/';
            //VARIABLE QUE OBTENEMOS DE RL
            var strData = "idrol="+idrol;
            //ABRIMOS LA CONEXION Y INDICAMOS EL METODO LA RUTA
            request.open("POST",ajaxUrl,true);
            //COMO SE ENVIARAN LOS DATOS
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            //ENVIAMOS LOS DATOS
            request.send(strData);
            //VALIDACION DE LA RESPUESTA
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    //CONNVERTIMOS EN UN OBJETO LO QUE TRAE REQUEST
                    var objData = JSON.parse(request.responseText);
                    if(objData.status)
                    { 
                        swal("Eliminar!", objData.msg , "success");
                        tableRoles.api().ajax.reload();
                    }else{
                        swal("Atención!", objData.msg , "error");
                    }
                }
            }
        }
    });
}

//FUNCION PARA PERMISOS DE UN USUARIO
function fntPermisos(idrol){
     
    //ID DEL ROL GETATTRIBUTE PERMITE OBNTENER EL VALOR DEL ATRIBUTO
    var idrol = idrol;
    //DETECTA QUE NAVAGADOR USAMOS
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url+'/Permisos/getPermisosRol/'+idrol;
    //HACEMOS UNA PETICION GET PARA OBTENER LOS REGISTROS
    request.open("GET",ajaxUrl,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            //MUESTRA LOS DATOS
            //console.log(request.responseText);
            //colocamos dentro del elemento contentAjax la respuesta
            document.querySelector('#contentAjax').innerHTML = request.responseText;
            //y lo mostramos
            $('.modalPermisos').modal('show');
            //TRAE TODOS LOS DATOS DEL formPermisos Y EVENTO SUBMIT PARA EJECUTAR LA FUNCION fntSavePermisos
            document.querySelector('#formPermisos').addEventListener('submit',fntSavePermisos,false);   
        }
    } 
}

//GUARDA LOS PERMISOS
function fntSavePermisos(event){
    //EVITANDO QUE SE RECARGUE LA PAGINA AL DARLE CLICK AL BOTON GUARDAR
    event.preventDefault();
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    //RETORNA LA RUTA
    var ajaxUrl = base_url+'/Permisos/setPermisos'; 
    var formElement = document.querySelector("#formPermisos");
    //CREAMOS UN OBJETO PARA MANEJAR LOS ELEMENTOS DEL FORMPERMISOS
    var formData = new FormData(formElement);
    //OPEN ABRIMOS LA CONEXION Y ENVIAMOS POR METODO POST LOS DATOS AL URL AJAXURL
    request.open("POST",ajaxUrl,true);
    request.send(formData);

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var objData = JSON.parse(request.responseText);
            if(objData.status)
            {
                swal("Permisos de usuario", objData.msg ,"success");
            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
    
}

