<?php 

  class Roles extends Controllers{
  	
  	public function __construct(){ 
      parent::__construct();
      //inicializamos la session
      session_start();
      //ERROR
      //session_regenerate_id(true);
      if(empty($_SESSION['login']))
      { 
        header('Location: '.base_url().'/login');
      }  
  		getPermisos(2);
  	}
    
    public function Roles(){ 
      if(empty($_SESSION['permisosMod']['r'])){
        header("Location:".base_url().'/dashboard');
      }
      $data['page_id'] = 3;
      $data['page_tag'] = "Roles Usuario";
      $data['page_name'] = "rol_usuario";
      $data['page_title'] = "Roles de Usuario";
      $data['page_functions_js'] = "functions_roles.js";
      $this->views->getView($this,"roles",$data);
    }
    
    //METODO PARA OBTENER TODOS LOS ROLES
    public function getRoles(){
      if($_SESSION['permisosMod']['r']){
        $btnView = '';
        $btnEdit = '';
        $btnDelete = '';
        $arrData = $this->model->selectRoles();

        for ($i=0; $i < count($arrData); $i++) {
          //VALIDAMOS SI EL ARREGLO EN LA POSICION 0 ES IGUAL A 1 LE ASIGNAMOS EL BADGE
          if($arrData[$i]['status'] == 1)
          {
            $arrData[$i]['status'] = '<span class="badge badge-success">Activo</span>';
          }else{
            $arrData[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
          }

          if($_SESSION['permisosMod']['u']){
            $btnView = '<button class="btn btn-secondary btn-sm btnPermisosRol" onClick="fntPermisos('.$arrData[$i]['idrol'].')" title="Permisos"><i class="fas fa-key"></i></button>';
            $btnEdit = '<button class="btn btn-primary btn-sm btnEditRol" onClick="fntEditRol('.$arrData[$i]['idrol'].')" title="Editar"><i class="fas fa-pencil-alt"></i></button>';
          }

          if($_SESSION['permisosMod']['d']){
            $btnDelete = '<button class="btn btn-danger btn-sm btnDelRol" onClick="fntDelRol('.$arrData[$i]['idrol'].')" title="Eliminar"><i class="far fa-trash-alt"></i></button>';
              
          }

          //agregamos contenido html (botones de acciones)
          //CONCATENAMOS CADA UNAS DE LAS VARIABLES (btnView, btnEdit, btnDelete)
          $arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>';
        }

        //dep($arrData[1]['status']);
        //exit;
        //convertimos el arreglo en formato json
        echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
      }
      die();
    }

    //CONSULTA PARA DEVOLVER LOS ROLES
    public function getSelectRoles(){
      $htmlOptions = "";
      $arrData = $this->model->selectRoles();
      //SI CANTIDAD DE REGISTRO ES > 0
      if(count($arrData) > 0 ){
        //RECORREMOS EL ARRAY
        for ($i=0; $i < count($arrData); $i++) {
          //VERIFICAMOS SI EL ROL ESTA ACTIVO 
          if($arrData[$i]['status'] == 1 ){
            //ARMAMOS LOS OPTION CONCATENAMOS
            $htmlOptions .= '<option value="'.$arrData[$i]['idrol'].'">'.$arrData[$i]['nombrerol'].'</option>';
          }
        }
      }
      echo $htmlOptions;
      die();    
    }
    
    //METODO PARA OBTENER UN  ROL
    public function getRol(int $idrol){
      if($_SESSION['permisosMod']['r']){
        $intIdrol = intval(strClean($idrol));
        if($intIdrol > 0)
        { 
          $arrData = $this->model->selectRol($intIdrol);
          if(empty($arrData))
          {
            $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
          }else{
            $arrResponse = array('status' => true, 'data' => $arrData);
          }
          echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
        }
      }
      die();
    }
    
    //METODO PARA CREAR UN  ROL
    public function setRol(){
      //obtenemos el resultado del json
      //dep($_POST);
      
      //strClean(FUNCION) LIMPIA LA CADENA DE CARACTERES
      $intIdrol = intval($_POST['idRol']);
      $strRol =  strClean($_POST['txtNombre']);
      $strDescipcion = strClean($_POST['txtDescripcion']);
      //intVal NOS PERMITE OBTENER UN ENTERO
      $intStatus = intval($_POST['listStatus']);
      $request_rol= "";
      if($intIdrol == 0)
      {
        //Crear Nuevo Rol
        //ENVIAMOS INFORMACION AL MODELO
        if($_SESSION['permisosMod']['w']){
          $request_rol = $this->model->insertRol($strRol, $strDescipcion,$intStatus);
          $option = 1;
        }
      }else{
        if($_SESSION['permisosMod']['u']){
          //Actualizar Rol 
          $request_rol = $this->model->updateRol($intIdrol, $strRol, $strDescipcion, $intStatus);
          $option = 2;
        }
      }
      
      //SI RESPUESTA ES MAYOR A 0 y es igual a opcion 1 SE INSERTA EL REGISTRO MSJ DATOS SE GUARDARON CORRECTAMENTE SINO SI EXISTE ENTONCES MANDA EL MSJ DE ACTUALIZADO

      if($request_rol > 0 )
      { 
        if($option == 1)
        {
          $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
        }else{
          $arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
        }
        //SI ROL EXISTE 
      }else if($request_rol == 'exist'){
        
        $arrResponse = array('status' => false, 'msg' => '¡Atención! El Rol ya existe.');
      }else{
        $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
      }
      //sleep(3);
      //RETORNAMOS LOS DATOS DEL ARREGLO EN FORMATO JSON
      echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
      die();
    }
    
    //METODO PARA ELIMINAR UN ROL
    public function delRol()
    { 

      //SI HAY UNA PETICION POST SE EJECUTA
      if($_POST){
        if($_SESSION['permisosMod']['d']){
          //INTVAL CONVIERTE EN ENTERO
          $intIdrol = intval($_POST['idrol']);
          //LLAMAMOS AL MAETODO DELETEROL
          $requestDelete = $this->model->deleteRol($intIdrol);
          //SI ENCUENTRA EL ROL ENTONCES 
          if($requestDelete == 'ok')
          { 
            //MANDA EL SIGUIENTE MSJ
            $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Rol');
            //SI EL ROL ESTA ASOCIADO A UN USUARIO NO SE PODRA ELIMINAR
          }else if($requestDelete == 'exist'){
            $arrResponse = array('status' => false, 'msg' => 'No es posible eliminar un Rol asociado a usuarios.');
          }else{
            $arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Rol.');
          }
          //CONVERTIMOS EN FORMATO JSON
          echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
        }
      }
      die();
    }

  }



?>