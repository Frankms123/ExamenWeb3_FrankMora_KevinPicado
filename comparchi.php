<?php
	//Inicio la sesiÃ³n
    session_start();
	
	require_once('codigos/conexion.inc'); 
	$archivoActual = $_GET["IDArchivo"];
	$IDArchivo = intval($archivoActual);

	$usuarioActual = $_SESSION["usuario"];

    //Utiliza los datos de sesion comprueba que el usuario este autenticado
    if ($_SESSION["autenticado"] != "SI") {
       	header("Location: index.php");
    	exit(); //fin del scrip
    }

	// Carpeta a compartir
	$auxSql = sprintf("SELECT nombre_archivo FROM archivos WHERE IDArchivo = %d", intval($IDArchivo));
	$res = mysqli_query($conex, $auxSql);
	$fila = mysqli_fetch_assoc($res);
	$NombreArchivoActual = $fila["nombre_archivo"] ?? null;

	// Cargar usuarios
	$auxSql = sprintf("SELECT usuario FROM usuarios WHERE usuario <> '%s'", $_SESSION["usuario"]);
	$usuarios = mysqli_query($conex, $auxSql);

	//declara ruta carpeta del usuario
	$Accion_Formulario = $_SERVER['PHP_SELF'] . '?IDArchivo=' . $IDArchivo;
    if ((isset($_POST["OC_Aceptar"])) && ($_POST["OC_Aceptar"] == "frmArchivo")) {
		$compartir_para = trim($_POST['selectUsuario']);
		
		if (empty($compartir_para)) {
        	echo "<script>alert('Debe seleccionar el usuario a compartir.');</script>";
		} else {
			$AuxSql = sprintf(
				"INSERT INTO archivos_compartidos (ArchivoID, compartido_de, compartido_usuario) 
				VALUES (%d, '%s', '%s')",
				$IDArchivo,
				$usuarioActual,
				$compartir_para
			);

			$Regis = mysqli_query($conex, $AuxSql);

			if ($Regis === false) {
				echo "<script>alert('Error al compartir el archivo: " . mysqli_error($conex) . "');</script>";
			} else {
				header("Location: carpetas.php");
				exit();
			}
		}
   }
?>
<!doctype html>
<html>
<head>
	<?php include_once('partes/encabe.inc'); ?>
    <title>Compartir archivo.</title>
</head>
<body class="container cuerpo">
	<header class="row">
        <div class="row">
        	<div class="col-lg-6 col-sm-6">
        		<img  src="imagenes/encabe.png" alt="logo institucional" width="100%">
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
				<strong>Compartir archivo: <?php echo $NombreArchivoActual ?></strong>
			</div>
			<div class="panel-body">
				<form action="<?php echo $Accion_Formulario; ?>" method="post" name="frmArchivo">
					<fieldset>
           				<label><strong>Usuario</strong></label>
						<select name="selectUsuario" id="selectUsuario">
   							<option value="">-- Seleccione el usuario --</option>
							<?php
								if ($usuarios && mysqli_num_rows($usuarios) > 0) {
									while ($elem = mysqli_fetch_assoc($usuarios)) {
										echo '<option value="' . htmlspecialchars($elem['usuario']) . '">' . htmlspecialchars($elem['usuario']) . '</option>';
									}
								}
							?>
						</select>
           				<input type="submit" name="Submit" value="Compartir" />
         			</fieldset>
         			<input type="hidden" name="OC_Aceptar" value="frmArchivo" />
      			</form>
			</div>
		</div>
    </main>

    <footer class="row">

    </footer>
	<?php include_once('partes/final.inc'); ?>
</body>
</html>
