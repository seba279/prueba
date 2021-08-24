<?php 
 //Load
  //CONVERTIMOS LA PRIMERA LETRA EN MAYUSCULA (UCWORDS) PARA EVITAR PROBLEMAS CON EL SERVIDOR
  $controller = ucwords($controller);
  //Busca los archivos en el directorio controllers
  $controllerFile = "Controllers/".$controller.".php";
  //echo $controllerFile;
   if (file_exists($controllerFile)) 
   {
  	  require_once($controllerFile);
  	  $controller = new $controller();
  	  if (method_exists($controller, $method)) 
  	  {
  	  	 $controller->{$method}($params);

  	  }else{
         require_once("Controllers/Error.php");
  	  }

   }else{

   	  require_once("Controllers/Error.php");
   }

?>