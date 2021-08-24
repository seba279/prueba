<?php 

  class Permisos extends Controllers{
  	
  	public function __construct()
  	{   
  		parent::__construct();
  	}
    
    public function getPermisosRol(int $idrol)
    { 
        $rolid = intval($idrol);
        if($rolid > 0)
        { 
          //OBTENEMOS TODOS LOS MODULOS
          $arrModulos = $this->model->selectModulos();
          //OBTENEMOS TODOS LOS PERMISOS DE CADA MODULO
          $arrPermisosRol = $this->model->selectPermisosRol($rolid);
           
           //CHEQUEO PARA VER SI NOS ESTA TRAYENDO LOS MUDLOS Y PERMISOS
          //dep($arrModulos);
          //dep($arrPermisosRol);

          //ARRAY DE LOS DIFERENTES PERMISOS (LEER- ESCRIBIR - ACTUALIZAR - ELIMINAR)
          $arrPermisos = array('r' => 0, 'w' => 0, 'u' => 0, 'd' => 0);
          $arrPermisoRol = array('idrol' => $rolid );
          
          //VALIDAMOS SI ESTA VACIO
          if(empty($arrPermisosRol))
          { 
            //RECORREMOS EL ARRAY MODULOS
            for ($i=0; $i < count($arrModulos) ; $i++)
            { 
              //A CADA MODULO LE AGREGAMOS EL ITEM PERMISOS Y LE ASIGNAMOS EL ARRRAY DE LAS 4 OPERACIONES
              $arrModulos[$i]['permisos'] = $arrPermisos;
            }
          }else{
            for ($i=0; $i < count($arrModulos); $i++) {
              //REINICIAMOS LA VARIABLE CUANDO TERMINA EL CICLO
              $arrPermisos = array('r' => 0, 'w' => 0, 'u' => 0, 'd' => 0);
              //VALIDAMOS SI EXISTE EL MODULO EN LA TABLA DE PERMISOS
              if(isset($arrPermisosRol[$i])){
                //MODIFICAMOS CADA UNO DE LOS ITEM DEL ARRAY PERMISOS
                $arrPermisos = array('r' => $arrPermisosRol[$i]['r'], 
                     'w' => $arrPermisosRol[$i]['w'], 
                     'u' => $arrPermisosRol[$i]['u'], 
                     'd' => $arrPermisosRol[$i]['d'] 
                    );
              }
              //EN EL ARRAY MODULO EN LA POSICION QUE SE ENCUNETRA I EN EL ITEM PERMISOS LE ASIGNA arrPermisos PARA OBTENER LOS VALORES DE LA TABLA PERMISOS
              //SI NO EXISTE EL MODULO CARGA LOS QUE HAY
              $arrModulos[$i]['permisos'] = $arrPermisos;
              
            }
          }  
           $arrPermisoRol['modulos'] = $arrModulos;
          //LLAMAMOS AL MODAL
          $html = getModal("modalPermisos",$arrPermisoRol);
          //dep($arrPermisoRol);  
        }
        die();    
    }

    public function setPermisos()
    { 
      //VEMOS LOS DATOS QUE NO ESTAN ENVIANDO POR METODO POST
      //dep($_POST);
      //die();

      if($_POST)
      {
        $intIdrol = intval($_POST['idrol']);
        //TRAE TODOS LOS ELEMENTOS 
        $modulos = $_POST['modulos'];

        $this->model->deletePermisos($intIdrol);
        //RECORRE TODOS LOS ELEMENTOS
        foreach ($modulos as $modulo) {
          $idModulo = $modulo['idmodulo'];
          $r = empty($modulo['r']) ? 0 : 1;
          $w = empty($modulo['w']) ? 0 : 1;
          $u = empty($modulo['u']) ? 0 : 1;
          $d = empty($modulo['d']) ? 0 : 1;
          $requestPermiso = $this->model->insertPermisos($intIdrol, $idModulo, $r, $w, $u, $d);
        }
        if($requestPermiso > 0)
        {
          $arrResponse = array('status' => true, 'msg' => 'Los Permisos se asignaron correctamente.');
        }else{
          $arrResponse = array("status" => false, "msg" => 'No es posible asignar los permisos.');
        }
        echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
      }
      die();

    }
     
  }

?>