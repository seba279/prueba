<?php 

	class RolesModel extends Mysql
	{   
		//DEFINIMOS LAS VARIABLES
		public $intIdrol;
		public $strRol;
		public $strDescripcion;
		public $intStatus;

		public function __construct()
		{
			parent::__construct();
		}
        
		public function selectRoles()
		{
			$whereAdmin = "";
			if($_SESSION['idUser'] != 1 ){
				$whereAdmin = " and idrol != 1 ";
			}
			//EXTRAE ROLES
			$sql = "SELECT * FROM rol WHERE status != 0".$whereAdmin;
			$request = $this->select_all($sql);
			return $request;
		}

		public function selectRol(int $idrol)
		{
			//BUSCAR ROLE
			$this->intIdrol = $idrol;
			//INSTRUCCION
			$sql = "SELECT * FROM rol WHERE idrol = $this->intIdrol";
			$request = $this->select($sql);
			return $request;
		}

		public function insertRol(string $rol, string $descripcion, int $status){
            
			$return = "";
			//LE ASIGNAMOS LOS VALORES
			$this->strRol = $rol;
			$this->strDescripcion = $descripcion;
			$this->intStatus = $status;
            
            //CONSULTAMOS SI EXISTE ESE ROL
			$sql = "SELECT * FROM rol WHERE nombrerol = '{$this->strRol}' ";
			//OBTENEMOS LA RESPUESTA
			$request = $this->select_all($sql);
            
            //VALIDAMOS SI NO EXISTE ESE ROL ENTONCES LO INSERTAMOS SINO RETORNAMOS UN MSJ
			if(empty($request))
			{
				$query_insert  = "INSERT INTO rol(nombrerol,descripcion,status) VALUES(?,?,?)";
	        	$arrData = array($this->strRol, $this->strDescripcion, $this->intStatus);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
			return $return;
		}
        
        //ACTUALZIAR ROL
		public function updateRol(int $idrol, string $rol, string $descripcion, int $status){
			$this->intIdrol = $idrol;
			$this->strRol = $rol;
			$this->strDescripcion = $descripcion;
			$this->intStatus = $status;

			$sql = "SELECT * FROM rol WHERE nombrerol = '$this->strRol' AND idrol != $this->intIdrol";
			$request = $this->select_all($sql);
            
            //SI REQUEST ESTA VACION N0 SE ESTA CUMPLIENDO LA CONDICION DEL SELECT
			if(empty($request))
			{
				$sql = "UPDATE rol SET nombrerol = ?, descripcion = ?, status = ? WHERE idrol = $this->intIdrol ";
				$arrData = array($this->strRol, $this->strDescripcion, $this->intStatus);
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
		    return $request;			
		}

        //ELIMINAR ROL
		public function deleteRol(int $idrol)
		{   
			//ASIGNAMOS EL VALOR DEL PARAMETRO IDROL
			$this->intIdrol = $idrol;
			//INTRUCCION DE SQL DEL ROL Q QUEREMOS ELIMINAR SIEMPRE QUE NO ESTE ASOCIADO A UN USARIO
			$sql = "SELECT * FROM persona WHERE rolid = $this->intIdrol";
			//DEVUELVE EL RESULTADO
			$request = $this->select_all($sql);
			//SI ESTA VACIO NO HAY USUARIO ASOCIADO AL ROL
			if(empty($request))
			{   
				//ACTUALIZAMOS EL ESTADO DEL ROL NO LO ELIMINAMOS LE ASIGNAMOS UN VALOR 0
				$sql = "UPDATE rol SET status = ? WHERE idrol = $this->intIdrol ";
				$arrData = array(0);
				$request = $this->update($sql,$arrData);
				if($request)
				{
					$request = 'ok';	
				}else{
					$request = 'error';
				}
			}else{
				$request = 'exist';
			}
			return $request;
		}	
	}
 ?>