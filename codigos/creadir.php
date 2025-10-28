<?php
	//Inicio la sesión
	session_start();

	//Utiliza los datos de sesion comprueba que el usuario este autenticado
	if ($_SESSION["autenticado"] != "SI") {
		header("Location: ../index.php");
		exit(); //fin del script
	}
    $usuario = $_SESSION['usuario'];

    $rutaBase = dirname(__DIR__) . "/archivos_usuarios";
    $rutaUsuario = $rutaBase . "/" . $usuario;

    
        // Crear directorio base si no existe
    if (!file_exists($rutaBase)) {
        if (!mkdir($rutaBase, 0777, true)) {
            throw new Exception("No se pudo crear el directorio base: $rutaBase");
        }
    }
        //directorio hijo
    if (!file_exists($rutaUsuario)) {
        if (!mkdir($rutaUsuario, 0777, true)) {
            throw new Exception("No se pudo crear directorio para almacenar archivos. Ruta: $rutaUsuario");
        }
    }

    $_SESSION["ruta_usuario"] = $rutaUsuario;
    header("location: ../carpetas.php");
    exit();

	//declara ruta carpeta del usuario
	//$ruta = "d:\\mybox";
	//$ruta = $ruta.'/'.$_SESSION["usuario"];

	if(!mkdir($ruta,0700)){
		echo 'ERROR:\\ NO se pudo crear directorio para almacenar archivos.<br>';
		echo 'Favor pongase en contacto con el departamento de servicio al cliente.<br>';
        echo 'Ruta.....'.$ruta;
    }else{
		header("Location: ../carpetas.php");
	} // fin del if del mkdir
?>
