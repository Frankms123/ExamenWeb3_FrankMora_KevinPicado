<?php
	require_once('codigos/conexion.inc');
	
	session_start();

	//Utiliza los datos de sesion comprueba que el usuario este autenticado
	if ($_SESSION["autenticado"] != "SI") {
		header("Location: index.php");
		exit();
	}

	$idArchivo = $_GET['id'] ;

	if (empty($idArchivo)) {
		echo "No se especificó el archivo.";
		exit();
	}

	// Consultar el archivo en la base de datos
	$auxSql = sprintf("SELECT * FROM archivos WHERE IDArchivo = %d", intval($idArchivo));
	$resultado = mysqli_query($conex, $auxSql);

	if (!$resultado || mysqli_num_rows($resultado) == 0) {
		echo "Archivo no encontrado.";
		exit();
	}

	$archivo = mysqli_fetch_assoc($resultado);

	// Obtener datos del archivo
	$nombreArchivo = $archivo['nombre_archivo'];
	$extension = strtolower($archivo['extension']);
	$contenido = $archivo['contenido_archivo'];

	// Determinar el tipo MIME
	$tiposMime = [
		'pdf' => 'application/pdf',
		'jpg' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'png' => 'image/png',
		'gif' => 'image/gif',
		'bmp' => 'image/bmp',
		'txt' => 'text/plain',
		'html' => 'text/html',
		'htm' => 'text/html',
		'doc' => 'application/msword',
		'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'xls' => 'application/vnd.ms-excel',
		'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'ppt' => 'application/vnd.ms-powerpoint',
		'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		'zip' => 'application/zip',
		'rar' => 'application/x-rar-compressed',
		'mp3' => 'audio/mpeg',
		'mp4' => 'video/mp4',
		'avi' => 'video/x-msvideo',
		'mkv' => 'video/x-matroska',
		'mov' => 'video/quicktime',
		'wav' => 'audio/wav'
	];

	$mimeType = $tiposMime[$extension] ?? 'application/octet-stream';

	// Extensiones que se muestran en el navegador
	$extensionesVisualizables = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp'];

	// Si es PDF o imagen, mostrar en el navegador
	if (in_array($extension, $extensionesVisualizables)) {
		header("Content-Type: " . $mimeType);
		header("Content-Length: " . strlen($contenido));
		header("Content-Disposition: inline; filename=\"" . basename($nombreArchivo) . "\"");
		echo $contenido;
	} else {
		// Para otros archivos, forzar descarga
		header("Content-Type: " . $mimeType);
		header("Content-Disposition: attachment; filename=\"" . $nombreArchivo . "\"");
		header("Content-Length: " . strlen($contenido));
		echo $contenido;
	}
?>