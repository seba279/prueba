<?php 

  class Conexion 
  {
   private $conect;

   public function __construct()
   { 

   	 $connectionString = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET;
   	 try{
        //conexion de la BD
        $this->conect = new PDO($connectionString,DB_USER,DB_PASSWORD);
        //DETECTAMOS LOS POSIBLES ERRORES
        $this->conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Conexion exitosa";

   	 }catch (Exception $e){

        $this->conect = 'Error de Conexion';
        echo "ERROR: ".$e->getMessage();

   	 }
   }
   //RETORNAMOS LA CONEXION
   public function connect()
   {
    return $this->conect;
   }

  }

?>