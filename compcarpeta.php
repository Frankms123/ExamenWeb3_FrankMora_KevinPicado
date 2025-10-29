<?php
	require_once('codigos/conexion.inc'); 
	$carpetaActual = $_GET["IDCarpeta"];
	$IDCarpeta = intval($carpetaActual);
	
	//Inicio la sesiÃ³n
    session_start();

	$usuarioActual = $_SESSION["usuario"];

    //Utiliza los datos de sesion comprueba que el usuario este autenticado
    if ($_SESSION["autenticado"] != "SI") {
       	header("Location: index.php");
    	exit(); //fin del scrip
    }

	// Carpeta a compartir
	$auxSql = sprintf("SELECT NombreCarpeta FROM carpetas WHERE IDCarpeta = '%d'", intval($IDCarpeta));
	$res = mysqli_query($conex, $auxSql);
	$fila = mysqli_fetch_assoc($res);
	$NombreCarpetaActual = $fila["NombreCarpeta"] ?? null;

	// Cargar usuarios
	$auxSql = sprintf("SELECT usuario FROM usuarios WHERE usuario <> '%s'", $_SESSION["usuario"]);
	$usuarios = mysqli_query($conex, $auxSql);

	//declara ruta carpeta del usuario
	$Accion_Formulario = $_SERVER['PHP_SELF'] . '?IDCarpeta=' . $IDCarpeta;
    if ((isset($_POST["OC_Aceptar"])) && ($_POST["OC_Aceptar"] == "frmCarpeta")) {
		$compartir_para = trim($_POST['selectUsuario']);
		
		if (empty($compartir_para)) {
        	echo "<script>alert('Debe seleccionar el usuario a compartir.');</script>";
		} else {
			$AuxSql = sprintf(
				"INSERT INTO carpetas_compartidas (CarpetaID, compartido_de, compartido_usuario) 
				VALUES (%d, '%s', '%s')",
				$IDCarpeta,
				$usuarioActual,
				$compartir_para
			);

			$Regis = mysqli_query($conex, $AuxSql);

			if ($Regis === false) {
				echo "<script>alert('Error al compartir la carpeta: " . mysqli_error($conex) . "');</script>";
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
    <title>Compartir carpeta.</title>
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
				<strong>Compartir carpeta: <?php echo $NombreCarpetaActual ?></strong>
			</div>
			<div class="panel-body">
				<form action="<?php echo $Accion_Formulario; ?>" method="post" name="frmCarpeta">
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
         			<input type="hidden" name="OC_Aceptar" value="frmCarpeta" />
      			</form>
			</div>
		</div>
    </main>

    <footer class="row">

    </footer>
	<?php include_once('partes/final.inc'); ?>
</body>
</html>
