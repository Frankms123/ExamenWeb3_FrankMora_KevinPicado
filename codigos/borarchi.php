<?php
	require_once('conexion.inc');
	
	$idArchivo = $_GET['id'];
	session_start();

	//Utiliza los datos de sesion comprueba que el usuario este autenticado
	if ($_SESSION["autenticado"] != "SI") {
		header("Location: ../index.php");
		exit();
	}

	// Eliminar el archivo de la base de datos
	$auxSql = sprintf("DELETE FROM archivos WHERE IDArchivo = %d", intval($idArchivo));
	$resultado = mysqli_query($conex, $auxSql);

	/*Se intenta eliminar un fichero y se informa del resultado.*/
	echo "<h3>";
		if ($resultado && mysqli_affected_rows($conex) > 0) {
			echo ("Se ha eliminado el fichero.");
		} else {
			echo ("NO se pudo eliminar el fichero.");
		}
	echo "</h3>";

	//Retorna al punto de invocación
	$Ir_A = $_SERVER["HTTP_REFERER"];
	echo "<script language='JavaScript'>";
	echo "location.href='".$Ir_A."'";
	echo "</script>";
?>