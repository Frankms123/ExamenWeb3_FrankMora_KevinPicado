<?php
	require_once('conexion.inc');
	
	$IDCarpeta = $_GET['IDCarpeta'];
	session_start();

	//Utiliza los datos de sesion comprueba que el usuario este autenticado
	if ($_SESSION["autenticado"] != "SI") {
		header("Location: ../index.php");
		exit();
	}

	// Eliminar el archivo de la base de datos
	$auxSql = sprintf("DELETE FROM carpetas WHERE IDCarpeta = %d", intval($IDCarpeta));
	$resultado = mysqli_query($conex, $auxSql);

	/*Se intenta eliminar un fichero y se informa del resultado.*/
	echo "<h3>";
		if ($resultado && mysqli_affected_rows($conex) > 0) {
			echo ("Se ha eliminado la carpeta.");
		} else {
			echo ("NO se pudo eliminar la carpeta.");
		}
	echo "</h3>";

	//Retorna al punto de invocación
	$Ir_A = $_SERVER["HTTP_REFERER"];
	echo "<script language='JavaScript'>";
	echo "location.href='".$Ir_A."'";
	echo "</script>";
?>