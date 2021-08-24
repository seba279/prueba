<?php 

  class Dashboard extends Controllers{
  	
  	public function __construct()
  	{   
  		parent::__construct();
      //inicializamos la session
      session_start();
      //generamos el id que tenemos y indicamos que el id se elimine(true) para mayor seguridad de session
      session_regenerate_id(true);
      //validamos si la variable login existe o no, sino existe 
      //HACEMOS ESTO PARA NO PODER ACCEDER AL DASHBOARD SI NO ESTA LOGUEADO
      if(empty($_SESSION['login']))
      { 
        //redireccionamos al login
        header('Location: '.base_url().'/login');
      }
      //Creamos una funcion para permitir acceder o no. 1 modulo dashboard
      getPermisos(1);
  	}
    
    public function dashboard()
    { 
      $data['page_id'] = 2;
      $data['page_tag'] = "Dashboard";
      $data['page_title'] = "Tienda";
      $data['page_name'] = "dashboard";
      $data['page_functions_js'] = "functions_dashboard.js";
      $this->views->getView($this,"dashboard",$data);
    }

  }

?>