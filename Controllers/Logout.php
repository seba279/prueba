<?php
	class Logout
	{
		public function __construct()
		{   
			//INICIALIZAMOS SESSION
			session_start();
			//LIMPIAMOS TODAS LAS VARIABLES DE SESSION
			session_unset();
			//DESTRUIMOS TODAS LAS VARIABLES
			session_destroy();
			//REDIRECCIONAMOS AL LOGIN
			header('location: '.base_url().'/login');
		}
	}
 ?>