<?php
	require_once('codigos/conexion.inc');
	
	session_start();

	//Utiliza los datos de sesion comprueba que el usuario este autenticado
	if ($_SESSION["autenticado"] != "SI") {
		header("Location: index.php");
		exit(); 
	}

	$IDCarpetaActual = $_GET["IDCarpeta"] ;

	// Si no hay carpeta especificada, buscar la carpeta raíz del usuario
	if (empty($IDCarpetaActual)) {
		$auxSql = sprintf("SELECT IDCarpeta FROM carpetas WHERE NombreCarpeta = '%s' AND IsRoot = 1", $_SESSION["usuario"]);
		$res = mysqli_query($conex, $auxSql);
		$fila = mysqli_fetch_assoc($res);
		$IDCarpetaActual = $fila["IDCarpeta"] ?? null;
	}

	$Accion_Formulario = $_SERVER['PHP_SELF'] . "?IDCarpeta=" . $IDCarpetaActual;

	if ((isset($_POST["OC_Aceptar"])) && ($_POST["OC_Aceptar"] == "frmArchi")) {
		$comentario = trim($_POST['txtComentario']);
		$archivo = $_FILES['txtArchi'];
		
		if ($archivo['error'] == UPLOAD_ERR_OK) {
			$Sali = $archivo['name'];
			$Sali = str_replace(' ', '_', $Sali);
			$pesoBytes = $archivo['size'];
			
			// Obtener extensión
			$extension = strtolower(pathinfo($Sali, PATHINFO_EXTENSION));
			
			//tipo de documento
			$tiposDocumento = [
				'pdf' => 'PDF',
				'doc' => 'Word',
				'docx' => 'Word',
				'xls' => 'Excel',
				'xlsx' => 'Excel',
				'ppt' => 'PowerPoint',
				'pptx' => 'PowerPoint',
				'txt' => 'Texto',
				'jpg' => 'Imagen',
				'jpeg' => 'Imagen',
				'png' => 'Imagen',
				'gif' => 'Imagen',
				'bmp' => 'Imagen',
				'avi' => 'Video',
				'mp4' => 'Video',
				'mkv' => 'Video',
				'mov' => 'Video',
				'mp3' => 'Audio',
				'wav' => 'Audio',
				'zip' => 'Comprimido',
				'rar' => 'Comprimido'
			];
			
			$tipoDocumento = $tiposDocumento[$extension] ?? 'Otro';
			
			// Leer contenido del archivo
			$contenidoArchivo = file_get_contents($archivo['tmp_name']);
			$contenidoArchivo = mysqli_real_escape_string($conex, $contenidoArchivo);

			$auxSql = sprintf(
				"INSERT INTO archivos (IDCarpeta, nombre_archivo, comentario, tipo_documento, extension, peso, contenido_archivo) 
				VALUES (%d, '%s', '%s', '%s', '%s', %d, '%s')",
				$IDCarpetaActual,
				mysqli_real_escape_string($conex, $Sali),
				mysqli_real_escape_string($conex, $comentario),
				$tipoDocumento,
				$extension,
				$pesoBytes,
				$contenidoArchivo
			);
			
			$resultado = mysqli_query($conex, $auxSql);
			
			if ($resultado) {
				header("Location: carpetas.php?IDCarpeta=" . $IDCarpetaActual);
				exit();
			} else {
				echo 'No se pudo guardar el archivo en la base de datos, consulte a su administrador';
			}
		} else {
			echo 'Error al subir el archivo';
		}
	}
?>
<!doctype html>
<html>
<head>
	<?php include_once('partes/encabe.inc'); ?>
	<title>Agregar archivos.</title>
</head>
<body class="container cuerpo">
	<header class="row">
		<div class="row">
			<div class="col-lg-6 col-sm-6">
				<img src="imagenes/encabe.png" alt="logo institucional" width="100%">
			</div>
		</div>
		<div class="row">
			<?php include_once('partes/menu.inc'); ?>
		</div>
		<br />
	</header>

	<main class="row">
		<div class="panel panel-primary datos1">
			<div class="panel-heading">
				<strong>Agregar archivo</strong>
			</div>
			<div class="panel-body">
				<form action="<?php echo $Accion_Formulario; ?>" method="post" enctype="multipart/form-data" name="frmArchi">
					<fieldset>
						<label><strong>Archivo</strong></label><input name="txtArchi" type="file" id="txtArchi" size="60" required /><br><br>
						<label><strong>Comentario</strong></label><input name="txtComentario" type="text" id="txtComentario" maxlength="50" size="60" />
						<input type="submit" name="Submit" value="Cargar" />
					</fieldset>
					<input type="hidden" name="OC_Aceptar" value="frmArchi" />
				</form>
			</div>
		</div>
	</main>

	<footer class="row">

	</footer>
	<?php include_once('partes/final.inc'); ?>
</body>
</html>