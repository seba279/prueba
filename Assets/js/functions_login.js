$('.login-content [data-toggle="flip"]').click(function() {
      	$('.login-box').toggleClass('flipped');
      	return false;
});

var divLoading = document.querySelector("#divLoading");
document.addEventListener('DOMContentLoaded', function(){
    //SI EXISTE EL FORM
    if(document.querySelector("#formLogin")){
      
        //LET PERMITE SOLO USAR LA VARIABLE EN ESTA FUNCION
        let formLogin = document.querySelector("#formLogin");
        formLogin.onsubmit = function(e) {
            //EVITA QUE SE RECARGUE LA PAGINA AL MOMENTO DE DAR CLICK AL BOTON
            e.preventDefault();

            let strEmail = document.querySelector('#txtEmail').value;
            let strPassword = document.querySelector('#txtPassword').value;

            if(strEmail == "" || strPassword == "")
            {
                swal("Por favor", "Ingrese el usuario y contraseña.", "error");
                return false;
            }else{
                //colocamos un estilo a la variable para activarlo al loading
                divLoading.style.display = "flex";
                var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                var ajaxUrl = base_url+'/Login/loginUser'; 
                //formLogin permite obtener todos los campos y enviamos esos datos 
                //atraves de formData
                var formData = new FormData(formLogin);
                //ABRIMOS LA CONEXION Y MANDAMOS LOS DATOS POR POST
                request.open("POST",ajaxUrl,true);
                //ENVIAMOS INFO QUE CONTIENE FORMDATA
                request.send(formData);
                //console.log(request);  
                request.onreadystatechange = function(){
                    if(request.readyState != 4) return;
                    if(request.status == 200){
                        var objData = JSON.parse(request.responseText);
                        if(objData.status)
                        {   
                            //LE ASIGNAMOS LA DIRECCION DEL DASHBOARD
                            window.location = base_url+'/dashboard';
                        }else{
                            swal("Atención", objData.msg, "error");
                            //LIMPIAMOS EL CAMPO DE PASSWORD
                            document.querySelector('#txtPassword').value = "";
                        }
                    }else{
                        swal("Atención","Error en el proceso", "error");
                    }
                    //desactivamos el loading
                    divLoading.style.display = "none";
                    //formLogin.reset();
                    return false;
                }
               
            }
        }     
    }
    
    if(document.querySelector("#formResetPass")){        
        let formResetPass = document.querySelector("#formResetPass");
        formResetPass.onsubmit = function(e) {
            e.preventDefault();

            let strEmail = document.querySelector('#txtEmailReset').value;
            if(strEmail == "")
            {
                swal("Por favor", "Escribe tu correo electrónico.", "error");
                return false;
            }else{
                divLoading.style.display = "flex";
                var request = (window.XMLHttpRequest) ? 
                                new XMLHttpRequest() : 
                                new ActiveXObject('Microsoft.XMLHTTP');
                                
                var ajaxUrl = base_url+'/Login/resetPass'; 
                var formData = new FormData(formResetPass);
                request.open("POST",ajaxUrl,true);
                request.send(formData);
                request.onreadystatechange = function(){
                    //console.log(request);
                    if(request.readyState != 4) return;

                    if(request.status == 200){
                        var objData = JSON.parse(request.responseText);
                        if(request.status == 200){
                        var objData = JSON.parse(request.responseText);
                        if(objData.status)
                        {
                            swal({
                                title: "",
                                text: objData.msg,
                                type: "success",
                                confirmButtonText: "Aceptar",
                                closeOnConfirm: false,
                            }, function(isConfirm) {
                                if (isConfirm) {
                                    window.location = base_url;
                                }
                            });
                        }else{
                            swal("Atención", objData.msg, "error");
                            document.querySelector('#txtEmailReset').value = "";                   
                        }
                    }else{
                        swal("Atención","Error en el proceso", "error");
                        document.querySelector('#txtEmailReset').value = "";
                    }
                    divLoading.style.display = "none";
                    return false;
                    }
                }    
            }
        }
    }

    //ENVIO DE DATOS ATRAVES DE AJAX
    if(document.querySelector("#formCambiarPass")){
        let formCambiarPass = document.querySelector("#formCambiarPass");
        formCambiarPass.onsubmit = function(e) {
            e.preventDefault();

            let strPassword = document.querySelector('#txtPassword').value;
            let strPasswordConfirm = document.querySelector('#txtPasswordConfirm').value;
            let idUsuario = document.querySelector('#idUsuario').value;
            
            //VALIDAMOS QUE NO VENGAN VACIOS LOS CAMPOS DE PASS Y CONFIRMACION DE PASS
            if(strPassword == "" || strPasswordConfirm == ""){
                swal("Por favor", "Escribe la nueva contraseña." , "error");
                return false;
            }else{
                //VALIDAMOS QUE CONTENGA POR LO MENOS +6 CARACTERES
                if(strPassword.length < 5 ){
                    swal("Atención", "La contraseña debe tener un mínimo de 5 caracteres." , "info");
                    return false;
                }
                //VALIDAMOS QUE EL PASS Y LA CONFIRMACION DEL PASS SEAN IGUALES
                if(strPassword != strPasswordConfirm){
                    swal("Atención", "Las contraseñas no son iguales." , "error");
                    return false;
                }
                divLoading.style.display = "flex";
                var request = (window.XMLHttpRequest) ? 
                            new XMLHttpRequest() : 
                            new ActiveXObject('Microsoft.XMLHTTP');
                var ajaxUrl = base_url+'/Login/setPassword'; 
                var formData = new FormData(formCambiarPass);
                request.open("POST",ajaxUrl,true);
                request.send(formData);
                request.onreadystatechange = function(){
                    if(request.readyState != 4) return;
                    if(request.status == 200){
                        var objData = JSON.parse(request.responseText);
                        if(objData.status)
                        {
                            swal({
                                title: "",
                                text: objData.msg,
                                type: "success",
                                confirmButtonText: "Iniciar sessión",
                                closeOnConfirm: false,
                            }, function(isConfirm) {
                                if (isConfirm) {
                                    window.location = base_url+'/login';
                                }
                            });
                        }else{
                            swal("Atención",objData.msg, "error");
                        }
                    }else{
                        swal("Atención","Error en el proceso", "error");
                    }
                    divLoading.style.display = "none";
                }
            }
        }
    }

}, false);