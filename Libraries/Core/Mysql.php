<?php 
  //HEREDA LA CLASE CONEXION (PODEMOS USAR LOS METODOS)
  class Mysql extends Conexion
  {
  	private $conexion;
  	private $strquery;
  	private $arrValues;
  	
  	function __construct()
  	{  
  		//INTANCIA EL OBJETO CONEXION
  	  $this->conexion = new Conexion();
  	  //CONNECT INVOCA EL METODO DE LA CLASE CONEXION PARA DEVOLVERNOS LA CONEXION
      $this->conexion = $this->conexion->connect();
  	}

    //INSERTA UN REGISTRO
  	public function insert(string $query, array $arrValues)
  	{
       //ALMACENAMOS LOS VALORES QUE ENVIAMOS
	     $this->strquery = $query;
	     $this->arrValues = $arrValues;
	     //PREPARAMOS EL QUERY
	     $insert = $this->conexion->prepare($this->strquery);
	     //EJECUTAMOS LOS DATOS PARA ALMACENARLOS
	     $resInsert = $insert->execute($this->arrValues);
	     //SI VIENEN DATOS LOS ALMACENAMOS
	     if ($resInsert) 
	     {

	     	$lastInsert = $this->conexion->lastInsertId();
	    
	     }else{

	     	$lastInsert = 0;

	     }
	     //RETORNAMOS LOS VALORES DE LA VARIABLE
	     return $lastInsert;

  	}

  	//BUSCA UN REGISTRO
  	public function select(string $query)
  	{
	     $this->strquery = $query;
	     $result = $this->conexion->prepare($this->strquery);
	     $result->execute();
	     //OBTENEMOS EL REGISTRO FETCH (1 REGISTRO)
	     $data = $result->fetch(PDO::FETCH_ASSOC);
	     return $data;
  	}

    //DEVUELVE TODOS LOS REGISTROS
  	public function select_all(string $query)
  	{
	     $this->strquery = $query;
	     $result = $this->conexion->prepare($this->strquery);
	     $result->execute();
	     //OBTENEMOS TODOS LOS REGISTRO FETCHALL 
	     $data = $result->fetchall(PDO::FETCH_ASSOC);
	     return $data;
  	}
    
    //ACTUALIZAR UN REGISTRO
  	public function update(string $query, array $arrValues)
  	{
	     $this->strquery = $query;
	     $this->arrValues = $arrValues;
	     $update = $this->conexion->prepare($this->strquery);
	     $resExecute = $update->execute($this->arrValues);
	     return $resExecute;
  	}

  	//ELIMINAR UN REGISTRO
  	public function delete(string $query)
  	{
	     $this->strquery = $query;
	     $result = $this->conexion->prepare($this->strquery);
	     $del = $result->execute();
	     return $del;
  	}

  }

?>