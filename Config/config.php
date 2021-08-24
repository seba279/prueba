<?php 
   //VARIABLES GLOBALES
  //define("BASE_URL", "http://localhost/tienda_virtual/");
  const BASE_URL = "https://seba279.github.io/prueba";

  //ZONA HORARIA ARGENTINA
  date_default_timezone_set('America/Argentina/Tucuman');

  //DATOS DE CONEXION DE BASE DE DATOS
  const DB_HOST = "localhost";
  const DB_NAME = "tienda";
  const DB_USER = "root";
  const DB_PASSWORD = "";
  const DB_CHARSET = "utf8";

  //DELIMITADORES DECIMAL Y MILLAR EJE 24,1980.00
  const SPD = ","; //DECIMALES 25,20
  const SPM = "."; //MILLARES 1.000

  //SIMBOLO DE LA MONEDA
  const SMONEY = "$";

  //Datos envio de correo
   const NOMBRE_REMITENTE = "Tienda 2021";
   const EMAIL_REMITENTE = "seba@hotmail.com";
   const NOMBRE_EMPRESA = "Tienda 2021";
   const WEB_EMPRESA = "http://localhost/tienda2021";

?>
