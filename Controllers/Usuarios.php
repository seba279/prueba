<?php 

	class Usuarios extends Controllers{
		public function __construct()
		{   
			parent::__construct();
            session_start();
            session_regenerate_id(true);
			if(empty($_SESSION['login']))
		    { 
		      header('Location: '.base_url().'/login');
		    }
			getPermisos(2);
		}

		public function Usuarios(){
			if(empty($_SESSION['permisosMod']['r'])){
				header("Location:".base_url().'/dashboard');
			}
			$data['page_tag'] = "Usuarios";
			$data['page_title'] = "USUARIOS";
			$data['page_name'] = "usuarios";
			$data['page_functions_js'] = "functions_usuarios.js";
			$this->views->getView($this,"usuarios",$data);
		}

		public function setUsuario(){
		    if($_POST){
                 
		    	//COMPROBAMOS  QUE LOS CAMPOS NO ESTEN VACIOS 
		    	if(empty($_POST['txtIdentificacion']) || empty($_POST['txtNombre']) || empty($_POST['txtApellido']) || empty($_POST['txtTelefono']) || empty($_POST['txtEmail']) || empty($_POST['listRolid']) || empty($_POST['listStatus']) )
				{   
					//RETORNAMOS UN MENSAJE SI ESTA VACIO ALGUN CAMPO DE ERROR
					$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
				}else{ 
					//CREAMOS LAS VARIABLES PARA ASIGNARLE LOS VALORES QUE ESTAMOS RECIBIENDO POR POST
					$idUsuario = intval($_POST['idUsuario']);
					$strIdentificacion = strClean($_POST['txtIdentificacion']);
					//ucwords CONVIERTE LAS PRIMERAS LETRAS EN MAYUSCULA
					$strNombre = ucwords(strClean($_POST['txtNombre']));
					$strApellido = ucwords(strClean($_POST['txtApellido']));
					$intTelefono = intval(strClean($_POST['txtTelefono']));
					//strtolower CONVIERTE TODAS LAS LETRAS EN MINUSCULAS
					$strEmail = strtolower(strClean($_POST['txtEmail']));
					$intTipoId = intval(strClean($_POST['listRolid']));
					$intStatus = intval(strClean($_POST['listStatus']));
                    $request_user = "";
                    //sino se envia el id creamos el usuario
					if($idUsuario == 0)
					{    
						$option = 1;
						//CREAMOS UNA VARIABLE PASWORD PARA ASIGNARLE ALEATORIAMENTE UN VALOR SI ESTA VACIO EL CAMPO
					    $strPassword =  empty($_POST['txtPassword']) ? hash("SHA256",passGenerator()) : hash("SHA256",$_POST['txtPassword']);
					    //VALIDAMOS SI TIENE PERMISOS DE ESCRITURA 
					    if($_SESSION['permisosMod']['w']){
						    //LLAMAMOS AL METODO insertUsuario PARA ENVIAR LOS DATOS Y GUARDARLOS EN LA BD
							$request_user = $this->model->insertUsuario($strIdentificacion,
								                                        $strNombre, 			
								                                        $strApellido,
								                                        $intTelefono,
								                                        $strEmail, 			  
									                                    $strPassword, 			  
									                                    $intTipoId, 		
									                                    $intStatus);
                        }
					}else{
                        $option = 2;
						$strPassword =  empty($_POST['txtPassword']) ? "" : hash("SHA256",$_POST['txtPassword']);
						//validamos si tiene permiso para actualizar
						if($_SESSION['permisosMod']['u']){
							$request_user = $this->model->updateUsuario($idUsuario,					                                    
								                                        $strIdentificacion, 				                            
								                                        $strNombre,				  
								                                        $strApellido, 			  
								                                        $intTelefono, 
								                                        $strEmail, 			  
								                                        $strPassword,	  
								                                        $intTipoId, 		
								                                        $intStatus);
						}	
					}
                    //SI ES MAYOR A 0 SE INGRESO EL REGISTRO
					if($request_user > 0 )
					{
						if($option == 1){
							$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
						}else{
							$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
						}
					}else if($request_user == 'exist'){
						$arrResponse = array('status' => false, 'msg' => '¡Atención! el email o la identificación ya existe, ingrese otro.');		
					}else{
						$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
					}
				}
				//sleep(3);
				//CONVERTIMOS EN FORMATO JSON
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function getUsuarios(){   
			//VALIDAMOS SI TIENE EL PERMISO PARA VER LOS USUARIOS
			if($_SESSION['permisosMod']['r']){
				$arrData = $this->model->selectUsuarios();
				//dep($arrData);
				//RECORREMOS CON EL CICLO FOR CADA UNO DE LOS ELEMENTOS DEL ARREGLO (USUARIOS)
				for ($i=0; $i < count($arrData); $i++){
					//CREAMOS LAS VARIABLES
	                $btnView = '';
					$btnEdit = '';
					$btnDelete = '';

					if($arrData[$i]['status'] == 1)
					{
						$arrData[$i]['status'] = '<span class="badge badge-success">Activo</span>';
					}else{
						$arrData[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
					}
	                //VALIDAMOS SI EL ELEMENTO R ES VERDADERO
	                if($_SESSION['permisosMod']['r'])
	                {
						$btnView = '<button class="btn btn-info btn-sm btnViewUsuario" onClick="fntViewUsuario('.$arrData[$i]['idpersona'].')" title="Ver usuario"><i class="far fa-eye"></i></button>';
					}

					if($_SESSION['permisosMod']['u']){
	                    //VALIDAMOS SI IDUSER ES EL SUPERUSUARIO
	                    if(($_SESSION['idUser'] == 1 and $_SESSION['userData']['idrol'] == 1) ||
								($_SESSION['userData']['idrol'] == 1 and $arrData[$i]['idrol'] != 1) ){
					        $btnEdit = '<button class="btn btn-primary  btn-sm btnEditUsuario" onClick="fntEditUsuario(this,'.$arrData[$i]['idpersona'].')" title="Editar usuario"><i class="fas fa-pencil-alt"></i></button>';
					    }else{
								$btnEdit = '<button class="btn btn-secondary btn-sm" disabled ><i class="fas fa-pencil-alt"></i></button>';
						}
					}

					if($_SESSION['permisosMod']['d']){
						if(($_SESSION['idUser'] == 1 and $_SESSION['userData']['idrol'] == 1) ||
							($_SESSION['userData']['idrol'] == 1 and $arrData[$i]['idrol'] != 1) and
							($_SESSION['userData']['idpersona'] != $arrData[$i]['idpersona'] )
							 ){
							$btnDelete = '<button class="btn btn-danger btn-sm btnDelUsuario" onClick="fntDelUsuario('.$arrData[$i]['idpersona'].')" title="Eliminar usuario"><i class="far fa-trash-alt"></i></button>';
						}else{
							$btnDelete = '<button class="btn btn-secondary btn-sm" disabled ><i class="far fa-trash-alt"></i></button>';
						}
					}

	                //AGREGANDO LOS BOTONES
	                //CONCATENAMOS CADA UNAS DE LAS VARIABLES (btnView, btnEdit, btnDelete)
					$arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>';
				}
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function getUsuario($idpersona){

			//OBTENEMOS LOS DATOS DEL USUARIO SEGUN EL ID
			//dep($idpersona);
			//die();
			//VALIDAMOS SI TIENE EL PERMISO PARA VER EL USUARIO
            if($_SESSION['permisosMod']['r']){
				$idusuario = intval($idpersona);
				if($idusuario > 0)
				{   
					//BUSCAMOS SI EXISTE EL USUARIO (ID)
					$arrData = $this->model->selectUsuario($idusuario);

					//OBTENEMOS DATOS DEL REGISTRO
					//dep($arrData);

					//VALIDAMOS SI VIENE VACIO
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
        
        //METODO PARA ELIMINAR EL USUARIO
		public function delUsuario(){   
			//VALIDAMOS SI SE ESTA REALIZANDO UNA PETICION POST
			if($_POST){
				//validamos si tiene permiso para eliminar
				if($_SESSION['permisosMod']['d']){
					$intIdpersona = intval($_POST['idUsuario']);
					$requestDelete = $this->model->deleteUsuario($intIdpersona);
					//dep($requestDelete);
					//die();
					if($requestDelete)
					{
						$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el usuario');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el usuario.');
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			die();
		}

		public function perfil(){
			$data['page_tag'] = "Perfil";
			$data['page_title'] = "Perfil de usuario";
			$data['page_name'] = "perfil";
			$data['page_functions_js'] = "functions_usuarios.js";
			$this->views->getView($this,"perfil",$data);
		}

		public function putPerfil(){
			//dep($_POST);
			//die();
			if($_POST){
				if(empty($_POST['txtIdentificacion']) || empty($_POST['txtNombre']) || empty($_POST['txtApellido']) || empty($_POST['txtTelefono']) )
				{
					$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
				}else{
					$idUsuario = $_SESSION['idUser'];
					$strIdentificacion = strClean($_POST['txtIdentificacion']);
					$strNombre = strClean($_POST['txtNombre']);
					$strApellido = strClean($_POST['txtApellido']);
					$intTelefono = intval(strClean($_POST['txtTelefono']));
					$strPassword = "";
					if(!empty($_POST['txtPassword'])){
						$strPassword = hash("SHA256",$_POST['txtPassword']);
					}
					$request_user = $this->model->updatePerfil($idUsuario,					  
						                                        $strIdentificacion, 		
						                                        $strNombre,				  
						                                        $strApellido, 			  
						                                        $intTelefono, 			  
						                                        $strPassword);
 
					if($request_user)
					{
						sessionUser($_SESSION['idUser']);
						$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
					}else{
						$arrResponse = array("status" => false, "msg" => 'No es posible actualizar los datos.');
					}
				}
				//RETRASO DE 3 SEGUNDOS DEL LOADING
				//sleep(3);
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function putDFical(){
			//dep($_POST);
			//VALIDAMOS SI VIENEN DATOS POR POST
			if($_POST){
				//VALIDAMOS SI NO ESTEN VACIOS NINGUN CAMPO
				if(empty($_POST['txtNit']) || empty($_POST['txtNombreFiscal']) || empty($_POST['txtDirFiscal']) )
				{
					$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
				}else{
					$idUsuario = $_SESSION['idUser'];
					$strNit = strClean($_POST['txtNit']);
					$strNomFiscal = strClean($_POST['txtNombreFiscal']);
					$strDirFiscal = strClean($_POST['txtDirFiscal']);
					$request_datafiscal = $this->model->updateDataFiscal($idUsuario,
																		$strNit,
																		$strNomFiscal, 
																		$strDirFiscal);
					if($request_datafiscal)
					{   
						//RECAGAMOS LA SESION CON LOS NUEVOS DATOS sessionUser
						sessionUser($_SESSION['idUser']);
						$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
					}else{
						$arrResponse = array("status" => false, "msg" => 'No es posible actualizar los datos.');
					}
				}
				//sleep(3);
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

	}
 ?>