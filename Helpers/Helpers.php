<?php 
    //URL DEL PROYECTO
    function base_url()
    {   
    	return BASE_URL;
    }

    //URL DE los achivos de estilo js images
    function media()
    {   
        return BASE_URL."/Assets";
    }

    function headerAdmin($data="")
    {
        $view_header = "Views/Template/header_admin.php";
        require_once ($view_header);
    }

    function footerAdmin($data="")
    {
        $view_footer = "Views/Template/footer_admin.php";
        require_once ($view_footer);        
    }

    //MUESTRA INFORMACION FORMATEADA(DEPURACION)
    function dep($data)
    {
    	$format = print_r('<pre>');
    	$format = print_r($data);
    	$format = print_r('</pre>');
    	return $format;
    }
    //funcion para abrir el modal en donde enviamos el nombre del modal que queremos abrir
    function getModal(string $nameModal, $data)
    {
        $view_modal = "Views/Template/Modals/{$nameModal}.php";
        require_once $view_modal;        
    }

    //Envio de correos
    function sendEmail($data,$template)
    {
        $asunto = $data['asunto'];
        $emailDestino = $data['email'];
        $empresa = NOMBRE_REMITENTE;
        $remitente = EMAIL_REMITENTE;
        //ENVIO DE CORREO
        $de = "MIME-Version: 1.0\r\n";
        $de .= "Content-type: text/html; charset=UTF-8\r\n";
        $de .= "From: {$empresa} <{$remitente}>\r\n";
        //CARGA EN MEMORIA UN ARCHIVO Q ESPECIFICAMOS $template.PHP
        ob_start();
        require_once("Views/Template/Email/".$template.".php");
        //OBTENEMOS EL ARCHIVO PARA HACER USO DE TODOS LOS DATOS
        $mensaje = ob_get_clean();
        //MAIL FUNCION QUE HACE EL ENVIO DEL CORREO
        $send = mail($emailDestino, $asunto, $mensaje, $de);
        return $send;
    }
    
    //obtenemos los permisos
    function getPermisos(int $idmodulo){
        //necesitamos este arcivo
        require_once ("Models/PermisosModel.php");
        //CREAMOS UN OBJETO PARA USAR LOS METODOS DE LA CLASE PermisosModel
        $objPermisos = new PermisosModel();
        //OBTENEMOS EL ID DEL ROL ATRAVES DE LA VARIABLE DE SESSION
        $idrol = $_SESSION['userData']['idrol'];
        //OBTENEMOS LOS PERMISOS DEL MODULO
        $arrPermisos = $objPermisos->permisosModulo($idrol);
        //CREAMOS 2 VARIABLES permisos(ALMACENAMOS TODOS LOS PERMISOS DEL ROL)
        //permisosMod (ALMACENAMOS TODOS LOS PERMISOS DE CADA MODULO)
        $permisos = '';
        $permisosMod = '';
        //VALIDAMOS SI TIENE PERMISOS EL MODULO
        if(count($arrPermisos) > 0 ){
            $permisos = $arrPermisos;
            //SI EXISTE EL MODULO LE COLOCARA A permisosMod EL CONJUNTO DE ELEMENTOS $arrPermisos[$idmodulo]
            $permisosMod = isset($arrPermisos[$idmodulo]) ? $arrPermisos[$idmodulo] : "";
        }
        //CREAMOS LAS VARIABLES DE SESION PARA ALMACENAR LOS DATOS
        $_SESSION['permisos'] = $permisos;
        $_SESSION['permisosMod'] = $permisosMod;
    }

    function sessionUser(int $idpersona){
        require_once ("Models/LoginModel.php");
        $objLogin = new LoginModel();
        $request = $objLogin->sessionLogin($idpersona);
        return $request;
    }

    //ELIMINA EXCESO DE ESPACIOS ENTRE PALABRAS(CADENA)
    function strClean($strCadena)
    {
    	$string = preg_replace(['/\s+/','/^\s|\s$/'],[' ',''], $strCadena);
    	$string = trim($string);//Elimina espacio en blanco al inicio y al final
    	$string = stripslashes($string);//Elimina las \ invertidas
        //CAMBIA ALGUN CARACTER ESPECIAL SRT_IREPLACE
        $string = str_ireplace("<script>", "", $string);
        $string = str_ireplace("</script>", "", $string);
        $string = str_ireplace("<script src>", "", $string);
        $string = str_ireplace("<script type=>", "", $string);
        $string = str_ireplace("SELECT * FROM", "", $string);
        $string = str_ireplace("DELETE FROM", "", $string);
        $string = str_ireplace("INSERT INTO", "", $string);
        $string = str_ireplace("SELECT COUNT(*) FROM", "", $string);
        $string = str_ireplace("DROP TABLE", "", $string);
        $string = str_ireplace("OR '1'='1", "", $string);
        $string = str_ireplace('OR "1"="1"', "", $string);
        $string = str_ireplace('OR ´1´=´1´', "", $string);
        $string = str_ireplace("is NULL; --", "", $string);
        $string = str_ireplace("IS NULL; --", "", $string);
        $string = str_ireplace("LIKE '", "", $string);
        $string = str_ireplace('LIKE "', "", $string);
        $string = str_ireplace("LIKE ´", "", $string);
        $string = str_ireplace("OR 'a'='a", "", $string);
        $string = str_ireplace('OR "a"="a', "", $string);
        $string = str_ireplace("OR ´a´=´a", "", $string);
        $string = str_ireplace("OR ´a´=´a", "", $string);
        $string = str_ireplace("--", "", $string);
        $string = str_ireplace("^", "", $string);
        $string = str_ireplace("[", "", $string);
        $string = str_ireplace("]", "", $string);
        $string = str_ireplace("==", "", $string);
        return $string;
    }
    
    //GENERAR CONTRASEÑA ALEATORIA DE 10 CARACTERES
    function passGenerator($length = 10)
    {
    	$pass = "";
    	$longitudPass = $length;
    	$cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZWabcdefghijklmnopqrstuvwxz1234567890";
    	$longitudCadena=strlen($cadena);

    	for ($i=1; $i<=$longitudPass; $i++) 
    	{ 
    	  $pos = rand(0,$longitudCadena-1);
    	  $pass .= substr($cadena,$pos,1);	
    	}
    	return $pass;
    }

    //GENERA UN TOKEN (para reestablecer contraseña)
    function token()
    {
    	$r1 = bin2hex(random_bytes(10));
    	$r2 = bin2hex(random_bytes(10));
    	$r3 = bin2hex(random_bytes(10));
    	$r4 = bin2hex(random_bytes(10));
    	$token = $r1.'-'.$r2.'-'.$r3.'-'.$r4;
    	return $token;
    }

    //FORMATO PARA VALORES MONETARIOS
    function formatMoney($cantidad)
    {
    	$cantidad = number_format($cantidad,2,SPD,SPM);
    	return $cantidad;
    }

?>