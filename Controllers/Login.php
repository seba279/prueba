<?php 

  class Login extends Controllers{
  	
  	public function __construct(){ 
      //INICIALIZAMOS LAS VARIABLES DE SESSION
      //PERMITE CREAR LAS VARIABLES DE SESSION
      session_start();
      //si existe redireccionamos al dashboard
      //hacemos esto si ya estamos logueados
      if(isset($_SESSION['login']))
      { 
        header('Location: '.base_url().'/dashboard');
      }
  		parent::__construct();
      
  	}

    public function login(){ 
      $data['page_tag'] = "Login - Tienda Virtual";
      $data['page_title'] = "Login";
      $data['page_name'] = "login";
      $data['page_functions_js'] = "functions_login.js";
      $this->views->getView($this,"login",$data);
    }

    public function loginUser(){
      //dep($_POST);
      //die();
      if($_POST){
        //VALIDAMOS QUE LOS CAMPOS NO ESTEN VACIOS
        if(empty($_POST['txtEmail']) || empty($_POST['txtPassword'])){
          $arrResponse = array('status' => false, 'msg' => 'Error de datos' );
        }else{
          //CREAMOS LAS VARIABLES PARA ALMACENAR LOS DATOS DEL POST
          //strtolower CONVIERTE EN MINUSCULAS
          $strUsuario  =  strtolower(strClean($_POST['txtEmail']));
          //ENCRIPTAMOS EL PASSWORD
          $strPassword = hash("SHA256",$_POST['txtPassword']);
          $requestUser = $this->model->loginUser($strUsuario, $strPassword);
          //VALIDAMOS QUE NO TRAIGA VALORES VACIOS
          if(empty($requestUser)){
            $arrResponse = array('status' => false, 'msg' => 'El usuario o la contraseña es incorrecto.' ); 
          }else{
            $arrData = $requestUser;
            //SI EL ESTADO ES IGUAL A 1
            if($arrData['status'] == 1){
              //CREAMOS LAS VARIABLES DE SESSION
              $_SESSION['idUser'] = $arrData['idpersona'];
              $_SESSION['login'] = true;
              
              
              //OBTENEMOS TODOS LOS DATOS DEL USAURIO
              $arrData = $this->model->sessionLogin($_SESSION['idUser']);
              //traemos los datos del usuario logueado
              sessionUser($_SESSION['idUser']);
              //almacenamos todos los datos en la variable de sesion userdata
              //$_SESSION['userData'] = $arrData;             
              $arrResponse = array('status' => true, 'msg' => 'ok');
            }else{
              $arrResponse = array('status' => false, 'msg' => 'Usuario inactivo.');
            }
          }
        }
        //retrasa el msj los segundos 
        //sleep(3);
        echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
      }
      die();
    }

    public function resetPass(){
      //dep($_POST);
      //die();
      if($_POST){
        //EVITAMOS QUE SE MUESTRE EL ERROR DEL MAIL() SI NO TENEMOS CONFIGURADO
        error_reporting(0);
        
        //VALIDAMOS SI ESTA VACIO MSJ DE ERROR
        if(empty($_POST['txtEmailReset'])){
          $arrResponse = array('status' => false, 'msg' => 'Error de datos' );
        }else{
          //GENERAMOS EL TOKEN Y ASIGNAMOS A LA VARIABLE token
          $token = token();
        
          //OBTENEMOS EL EMAIL INGRESADO
          $strEmail  =  strtolower(strClean($_POST['txtEmailReset']));
          //BUSCAMOS ESE EMAIL EN LA BD
          $arrData = $this->model->getUserEmail($strEmail);
          
          //SI NO ENCUENTRA EL EMAIL
          if(empty($arrData)){
            $arrResponse = array('status' => false, 'msg' => 'Usuario no existente.' ); 
          }else{
            //OBTENEMOS DEL ARRAY EL ID Y NOMBRE Y APELLIDO
            $idpersona = $arrData['idpersona'];
            $nombreUsuario = $arrData['nombres'].' '.$arrData['apellidos'];
            //RUTA DE LA DIRECCION DE CONFIRMACION CON EL TOKEN
            $url_recovery = base_url().'/login/confirmUser/'.$strEmail.'/'.$token;
            //ACTUALIZAMOS EL CAMPO TOKEN
            $requestUpdate = $this->model->setTokenUser($idpersona,$token);
            //DATOS DEL USUARIO
            $dataUsuario = array('nombreUsuario' => $nombreUsuario,
                       'email' => $strEmail,
                       'asunto' => 'Recuperar cuenta - '.NOMBRE_REMITENTE,
                       'url_recovery' => $url_recovery);
            


            if($requestUpdate){
                
                //ENVIAMOS LOS DATOS DEL USUARIO Y LA VISTA
                $sendEmail = sendEmail($dataUsuario,'email_cambioPassword');
                //verificamos que nos devuelve la funcion
                //var_dump($sendEmail);
                //exit;

                if($sendEmail){
                  $arrResponse = array('status' => true, 
                           'msg' => 'Se ha enviado un email a tu cuenta de correo para cambiar tu contraseña.');
                }else{
                  $arrResponse = array('status' => false, 
                           'msg' => 'No es posible realizar el proceso, intenta más tarde.' );
                }
            }else{
              $arrResponse = array('status' => false, 
                         'msg' => 'No es posible realizar el proceso, intenta más tarde.' );
            }
          }
        }
        //RETRASO DE 3 SEGUNDOS
        //sleep(3);
        echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
      }
      die();
    }

    public function confirmUser(string $params){
      //SI PARAMS ESTA VACIO REDIRECCIONAMOS AL LOGIN
      if(empty($params)){

        header('Location: '.base_url());

      }else{
        
        //echo $params;
        //explode CONVERTIMOS EN UN ARRAY TODA ESA CADENA SEPARADA POR LAS COMAS
        $arrParams = explode(',',$params);
        
        //verificamos si obtenemos los parametros el correo y token
        //dep($arrParams);

        //LIMPIAMOS LOS DATOS STRCLEAN
        $strEmail = strClean($arrParams[0]);
        $strToken = strClean($arrParams[1]);

        //CONSULTAMOS A LA BD SI EXISTE
        $arrResponse = $this->model->getUsuario($strEmail,$strToken);
        //SI NO ENCONTRAMOS UN USUARIO CON EL EMAIL Y TOKEN REDIRECCIONA A LA PAGINA PRINCIPAL (LOGIN)
        if(empty($arrResponse)){

          header("Location: ".base_url());

        }else{

          $data['page_tag'] = "Cambiar contraseña";
          $data['page_name'] = "cambiar_contrasenia";
          $data['page_title'] = "Cambiar Contraseña";
          $data['email'] = $strEmail;
          $data['token'] = $strToken;
          //OBTENEMOS EL ID DE LA PERSONA arrResponse
          $data['idpersona'] = $arrResponse['idpersona'];
          $data['page_functions_js'] = "functions_login.js";
          $this->views->getView($this,"cambiar_password",$data);
        }
      }
      die(); 
    }

    public function setPassword(){
      
      //dep($_POST);
      //die();
      
      //VALIDAMOS QUE NO VENGAN VACIOS LOS CAMPOS
      if(empty($_POST['idUsuario']) || empty($_POST['txtEmail']) || empty($_POST['txtToken']) || empty($_POST['txtPassword']) || empty($_POST['txtPasswordConfirm'])){

          $arrResponse = array('status' => false, 
                     'msg' => 'Error de datos' );
        }else{
          //ASIGNAMOS LOS DATOS
          $intIdpersona = intval($_POST['idUsuario']);
          $strPassword = $_POST['txtPassword'];
          $strPasswordConfirm = $_POST['txtPasswordConfirm'];
          $strEmail = strClean($_POST['txtEmail']);
          $strToken = strClean($_POST['txtToken']);
          
          //VALIDAMOS LOS PASS INGRESADOS
          if($strPassword != $strPasswordConfirm){
            $arrResponse = array('status' => false, 
                       'msg' => 'Las contraseñas no son iguales.' );
          }else{
            //OBTENEMOS EL USUARIO
            $arrResponseUser = $this->model->getUsuario($strEmail,$strToken);
            //VALIDAMOS SI VIENE VACIO O NO
            if(empty($arrResponseUser)){
              $arrResponse = array('status' => false, 
                       'msg' => 'Error de datos.' );
            }else{
              //SINO VIENEN VACIO AL PASS LO ENCRIPTAMOS CON HASH
              $strPassword = hash("SHA256",$strPassword);
              //ACTUALIZAMOS EL PASS DEL USUARIO
              $requestPass = $this->model->insertPassword($intIdpersona,$strPassword);
              //MENSAJE DE CONFIRMACION
              if($requestPass){
                $arrResponse = array('status' => true, 
                           'msg' => 'La Contraseña se actualizo correctamente.');
              }else{
                $arrResponse = array('status' => false, 
                           'msg' => 'No es posible realizar el proceso, intente más tarde.');
              }
            }
          }
        }
      //sleep(3);
      echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
      die();
    }
  }

?>