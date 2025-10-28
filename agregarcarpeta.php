<?php
	require_once('codigos/conexion.inc'); 
	$carpetaActual = $_GET["IDCarpeta"];
    
	var_dump($_GET["IDCarpeta"]);
	var_dump($carpetaActual);

	$carpetaPadreID = intval($carpetaActual);
	var_dump($carpetaPadreID);

	//Inicio la sesiÃ³n
    session_start();

    //Utiliza los datos de sesion comprueba que el usuario este autenticado
    if ($_SESSION["autenticado"] != "SI") {
       header("Location: index.php");
        exit(); //fin del scrip
    }

	//declara ruta carpeta del usuario
	$Accion_Formulario = $_SERVER['PHP_SELF'];
    if ((isset($_POST["OC_Aceptar"])) && ($_POST["OC_Aceptar"] == "frmCarpeta")) {
		$nombreCarpeta = trim($_POST['txtCarpeta']);
    	
		echo "<script>alert('" . $carpetaPadreID . "');</script>";
		
		if ($nombreCarpeta === "") {
        	echo "<script>alert('Debe ingresar un nombre de carpeta.');</script>";
		} else {
			$AuxSql = sprintf(
				"INSERT INTO carpetas (NombreCarpeta, CarpetaPadreID, IsRoot) 
				VALUES ('%s', %d, 0)",
				$nombreCarpeta,
				$carpetaPadreID
			);

			$Regis = mysqli_query($conex, $AuxSql);

			if ($Regis === false) {
				echo "<script>alert('Error al registrar la carpeta: " . mysqli_error($conex) . "');</script>";
			} else {
				header("Location: carpetas.php?IDCarpeta=" . $carpetaPadreID);
				exit();
			}
		}
   }
?>
<!doctype html>
<html>
<head>
	<?php include_once('partes/encabe.inc'); ?>
    <title>Agregar carpeta.</title>
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
				<strong>Agregar carpeta</strong>
			</div>
			<div class="panel-body">
				<form action="<?php echo $Accion_Formulario; ?>" method="post" name="frmCarpeta">
					<fieldset>
           				<label><strong>Nombre</strong></label><input name="txtCarpeta" type="text" id="txtCarpeta" />
           				<input type="submit" name="Submit" value="Guardar" />
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
